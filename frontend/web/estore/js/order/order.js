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

        var btn = $(this);
        var url = btn.data('url');
        var txtProductQty = $('.product-qty', $(this).closest('tr'));

        var id = $(this).data('id');
        var qty = txtProductQty.val();

        $.ajax({
            url: url,
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

        var btn = $(this);
        var url = btn.data('url');
        var id = $(this).data('id');

        $.ajax({
            url: url,
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

        var btn = $(this);
        var url = btn.data('url');
        var frmCheckoutData = $('#frm-checkout').serializeArray();

        $.ajax({
            url: url,
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
