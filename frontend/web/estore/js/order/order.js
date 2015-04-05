$(function () {

    function getFinalPrice (item) {
        var product = item.details;

        var price = product.sales_price > 0 ?
            product.sales_price : product.price;

        return price;
    }

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

    function redrawCart(items) {
        redrawCartItems(items);
        redrawCartTotal(items);
    }

    function redrawCartItems(items) {
        var shopBox = $('.shop-box');

        // Clear the current cart list
        $('.cart-item', shopBox).remove();

        for (var id in items) {
            var item = items[id];
            var product = item.details;

            var nameComponent = $('<h2>')
                .text(product.name)
                .addClass('name');

            var priceComponent = $('<div>')
                .text(item.quantity + ' x ' + getFinalPrice(item))
                .addClass('price');

            var itemComponent = $('<li>')
                .addClass('cart-item')
                .append(nameComponent)
                .append(priceComponent);

            shopBox.prepend(itemComponent);
        }
    }

    function redrawCartTotal(items) {
        $('.shop-box .total .price').text(calculateCartTotal(items));
    }

    /**
     * On button Update click
     */
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

                    // Redraw
                    redrawCart(data.items);
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

    /**
     * On button Remove click
     */
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

                    // Redraw
                    redrawCart(data.items);
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

    /**
     * On button Checkout click
     */
    $('.btn-checkout').click(function (evt) {

        evt.preventDefault();

        var btn = $(this);
        var url = btn.data('url');
        var frmCheckoutData = $('#frm-checkout').serializeArray();

        $.ajax({
            url: url,
            type: 'POST',
            data: frmCheckoutData,
            success: function (data) {
                var success = data.success;

                if (success) {
                    alert('Xin cảm ơn. Bạn đã đặt hàng thành công.');
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
