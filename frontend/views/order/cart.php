<?php
use backend\models\Product;
use yii\i18n\Formatter;

/** @var Product[] $items */

$app = Yii::$app;
$urlManager = $app->urlManager;
$GLOBALS['backendRootUrl'] = $backendRootUrl = $app->get('urlManagerBackEnd')->baseUrl;
$formatter = new Formatter();

function generateImageSrc($item) {
    $product = $item['details'];
    $mainImage = $product->mainImage;
    $urlManager = Yii::$app->urlManager;

    if (is_null($mainImage)) {
        return $urlManager->createUrl('estore/images/default_noimage.jpg');
    }
    else {
        return $GLOBALS['backendRootUrl'] . "/img/product/{$product->id}/{$mainImage->name}";
    }
}

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

<section id="maincontent" class="ten columns positionleft">

    <div class="breadcrumb"><a href="index.html">Trang Chủ</a> / Giỏ Hàng</div>

    <?php if (! empty($items)) : ?>
        <p class="well well-sm" style="color: #009900;">
            <b>Hướng dẫn</b>: Để xóa sản phẩm khỏi giỏ hàng, ấn nút "Xóa"
            Để thay đổi số lượng, điền số lượng sản phẩm vào ô và ấn nút "Cập nhật"
        </p>
    <?php endif ?>

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
        <?php if (empty($items)) : ?>
            <tr>
                <td colspan="5">
                    <span style="font-size: 1.3em !important;">Không có sản phẩm trong giỏ hàng. Vui lòng lựa chọn sản phẩm.</span>
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($items as $id => $item) : ?>
                <tr>
                    <td class="cart_product">
                        <img src="<?= generateImageSrc($item) ?>" class="product-img">
                    </td>
                    <td class="cart_description"><?= $item['details']->name ?></td>
                    <td class="cart_unit text-right"><?= $formatter->asCurrency($item['details']->finalPrice) ?></td>
                    <td class="cart_quantity">
                        <div class="form-group">
                            <input type="text" min="1" class="product-qty form-control"
                                   value="<?= $item['quantity'] ?>"
                                   data-prev-value="<?= $item['quantity'] ?>">
                        </div>
                        <button class="btn-update btn btn-default"
                                data-url="<?= $urlManager->createUrl('order/ajax-set-in-cart') ?>"
                                data-id="<?= $id ?>">
                            <span>Cập nhật</span>
                        </button>
                        <button class="btn-remove btn btn-danger"
                                data-url="<?= $urlManager->createUrl('order/ajax-remove-from-cart') ?>"
                                data-id="<?= $id ?>">
                            <span>Xóa</span>
                        </button>
                    </td>
                    <td class="total-cost cart_total text-right"><?= $formatter->asCurrency(calculateTotal($item)) ?></td>
                </tr>
            <?php endforeach ?>
        <?php endif ?>
        </tbody>

        <tfoot class="bg-primary">
        <tr style="font-weight: bold !important;">
            <td colspan="4" style="text-align: right;">Tổng tiền đơn hàng (Đã bao gồm VAT)</td>
            <td colspan="2" class="cart-total-cost"><?= $formatter->asCurrency(calculateCartTotal($items)) ?></td>
        </tr>
        </tfoot>

    </table>

    <div class="row">
        <div class="col-sm-4">
            <form id="frm-checkout">
                <div class="form-group">
                    <label>Tên khách hàng *</label>
                    <input type="text" name="customer[name]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Giới tính *</label>
                    <ul class="list-group list-group-inline">
                        <li class="list-group-item">
                            <input type="radio" name="customer[gender]" value="1"> Nam
                        </li>
                        <li class="list-group-item">
                            <input type="radio" name="customer[gender]" value="0"> Nữ
                        </li>
                    </ul>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" name="customer[email]" class="form-control">
                </div>
                <div class="form-group">
                    <label>Số điện thoại *</label>
                    <input type="text" name="customer[phone]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Địa chỉ *</label>
                    <input type="text" name="customer[address1]" class="form-control" required>
                </div>
                <div class="form-group text-right">
                    <a class="btn btn-default btn-sm"
                       href="<?= $urlManager->createUrl('site/all') ?>">Tiếp tục xem hàng</a>
                    <button class="btn-checkout btn btn-primary btn-sm"
                            data-url="<?= $urlManager->createUrl('order/ajax-checkout') ?>">Thực hiện</button>
                </div>
            </form>
        </div>
    </div>
</section>

<style>
    .form-group {
        width: 100%;
        margin-bottom: 15px;
    }
    .form-group > label {
        display: block;
        margin-bottom: 15px;
        font-weight: bold;
    }
    .form-control {
        display: block;
        width: 100%;
        line-height: 15px;
        margin: 0;
    }

    .list-group {
        margin-left: 0;
    }
    .list-group.list-group-inline > .list-group-item {
        display: inline-block;
    }
    .list-group.list-group-inline > .list-group-item + .list-group-item {
        padding-left: 15px;
    }
    .list-group-item {
        list-style: none;
        margin: 0;
    }

    .btn {
        border: solid 1px rgba(0,0,0,0.1);
        border-radius: 2px;
        padding: 5px;
    }
    .btn.btn-default {
        background-color: #ff5555;
        color: white;
    }

    .product-img {
        width: 100%;
    }

    td {
        padding: 10px !important;
        vertical-align: middle;
    }
</style>
