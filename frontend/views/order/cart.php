<?php
use backend\models\Product;
use yii\i18n\Formatter;

/** @var Product[] $items */

$urlManager = Yii::$app->urlManager;
$formatter = new Formatter();

function calculateTotal($item)
{
    return $item['quantity'] * $item['details']->finalPrice;
}

function calculateCartTotal($items)
{
    $total = 0;

    foreach ($items as $item) {
        $total += calculateTotal($item);
    }

    return $total;
}
?>

<div class="breadcrumb"><a href="index.html">Trang Chủ</a> / Giỏ Hàng</div>

<p class="well well-sm">
    <b>Hướng dẫn</b>: Để xóa sản phẩm khỏi giỏ hàng, ấn nút
    <kbd style="background-color: red;">Xóa</kbd>.
    Để thay đổi số lượng, điền số lượng sản phẩm vào ô và ấn nút
    <kbd style="background-color: white; color: black;">Cập nhật</kbd>
</p>

<table id="cart_summary" class="tbl-cart table table-responsive table-striped table-hover">

    <thead>
    <tr>
        <th class="cart_product first_item" style="width: 20%;">Thông tin</th>
        <th class="cart_description item" style="width: 26%;">Tên sản phẩm</th>
        <th class="cart_unit item text-right" style="width: 20%;">Giá/Đơn vị</th>
        <th class="cart_quantity item" style="width: 120px;">Số lượng</th>
        <th class="cart_total item text-right" style="20%;">Tổng cộng</th>
    </tr>
    </thead>

    <tbody>
    <?php foreach ($items as $id => $item) : ?>

        <tr>
            <td class="cart_product">...</td>
            <td class="cart_description"><?= $item['details']->name ?></td>
            <td class="cart_unit text-right"><?= $formatter->asCurrency($item['details']->finalPrice) ?></td>
            <td class="cart_quantity">
                <div class="input-group input-group-sm">
                    <input type="text" min="1" class="product-qty form-control"
                           value="<?= $item['quantity'] ?>"
                           data-prev-value="<?= $item['quantity'] ?>">
                    <span class="input-group-btn">
                        <button class="btn-update btn btn-default" data-id="<?= $id ?>">
                            <i class="glyphicon glyphicon-ok"></i>
                        </button>
                        <button class="btn-remove btn btn-danger" data-id="<?= $id ?>">
                            <i class="glyphicon glyphicon-remove"></i>
                        </button>
                    </span>
                </div>
            </td>
            <td class="total-cost cart_total text-right"><?= $formatter->asCurrency(calculateTotal($item)) ?></td>
        </tr>

    <?php endforeach ?>
    </tbody>

    <tfoot class="bg-primary">
    <tr style="font-weight: bold !important;">
        <td colspan="4" class="text-right">Tổng tiền đơn hàng (Đã bao gồm VAT)</td>
        <td colspan="2" class="cart-total-cost"><?= $formatter->asCurrency(calculateCartTotal($items)) ?></td>
    </tr>
    </tfoot>

</table>

<div class="row">
    <div class="col-sm-4">
        <h3>Thông tin khách hàng</h3>
        <hr>
        <form id="frm-checkout">
            <div class="form-group">
                <label>Tên khách hàng</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Giới tính</label>
                <ul class="list-group">
                    <li class="list-group-item">
                        <input type="radio" name="gender" value="1" checked> Nam
                    </li>
                    <li class="list-group-item">
                        <input type="radio" name="gender" value="0"> Nữ
                    </li>
                </ul>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" name="phone" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Địa chỉ</label>
                <input type="text" name="address1" class="form-control" required>
            </div>
            <div class="form-group text-right">
                <a class="btn btn-default btn-sm"
                   href="<?= $urlManager->createUrl('site/all') ?>">Tiếp tục xem hàng</a>
                <button class="btn-checkout btn btn-primary btn-sm">Thực hiện</button>
            </div>
        </form>
    </div>
</div>

<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script>
    $(function () {

        /**
         * Calculate total cost of a specific item in the cart
         *
         * @param {JSON} item
         * @returns {number}
         */
        var calculateTotal = function (item) {
            var price = item.details.sales_price > 0 ?
                item.details.sales_price : item.details.price;
            var qty = item.quantity;

            return price * qty;
        };

        /**
         * Calculate cart's total cost
         *
         * @param {Array} items
         * @returns {number}
         */
        var calculateCartTotal = function (items) {
            var total = 0;

            for (var id in items) {
                var item = items[id];
                total += calculateTotal(item);
            }

            return total;
        };

        $('.btn-update').click(function (evt) {

            var btn = this;
            var txtProductQty = $('.product-qty', $(this).closest('tr'));

            var id = $(this).data('id');
            var qty = txtProductQty.val();

            $.ajax({
                url: '<?= $urlManager->createUrl('order/ajax-set-in-cart') ?>',
                data: {
                    id: id,
                    qty: qty
                },
                success: function (data) {

                    var success = data.success;

                    if (success) {
                        alert('Cập nhật thành công số lượng sản phẩm');

                        var totalCost = calculateTotal(data.item);
                        var cartTotalCost = calculateCartTotal(data.items);

                        $('.total-cost', $(btn).closest('tr')).text(totalCost);
                        $('.cart-total-cost', $(btn).closest('table')).text(cartTotalCost);

                        // Set the new quantity as the previous quantity
                        txtProductQty.data('prev-value', qty);
                    }
                    else {
                        alert(data.message);
                        // Return to the previous quantity if failed
                        txtProductQty.val(txtProductQty.data('prev-value'));
                    }
                },
                error: function () {
                    console.log('Error happened on server');
                }
            });
        });

        $('.btn-remove').click(function (evt) {

            if (! confirm('Bạn thực sự muốn xóa sản phẩm này khỏi giỏ hàng?')) {
                return;
            }

            var btn = this;
            var id = $(this).data('id');

            $.ajax({
                url: '<?= $urlManager->createUrl('order/ajax-remove-from-cart') ?>',
                data: {
                    id: id
                },
                success: function (data) {
                    var success = data.success;

                    if (success) {
                        $(btn).closest('tr').remove();

                        // Calculate new cart's total cost
                        var cartTotalCost = calculateCartTotal(data.items);
                        $('.tbl-cart .cart-total-cost').text(cartTotalCost);
                    }
                    else {
                        alert(data.message);
                    }
                },
                error: function () {
                    alert('Error happened on server');
                }
            })
        });

        $('.btn-checkout').click(function (evt) {

            evt.preventDefault();

            var frmCheckoutData = $('#frm-checkout').serializeArray();

            $.ajax({
                url: '<?= $urlManager->createUrl('order/ajax-checkout') ?>',
                method: 'POST',
                data: frmCheckoutData,
                success: function (data) {
                    var success = data.success;

                    if (success) {
                        alert('Success');
                        location.reload();
                    }
                    else {
                        alert(data.message);
                    }
                },
                error: function (data) {
                    if (data.status == 401) {
                        alert('This request is not allowed');
                    }
                    else {
                        alert('Error happened on server');
                    }
                }
            });
        });
    });
</script>
