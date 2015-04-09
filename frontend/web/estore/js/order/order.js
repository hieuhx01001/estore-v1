$(function () {

    /**
     * This immediately-invoked function is used to
     * setting the accounting library for later use.
     */
    (function () {
        accounting.settings = {
            currency: {
                symbol: '₫',
                format: '%v %s',
                decimal: ',',
                thousand: '.',
                precision: 0
            },
            number: {
                precision: 0,
                decimal: ',',
                thousand: '.'
            }
        };
    })();

    function getFinalPrice (item) {
        var product = item.details;

        var price = product.sales_price > 0 ?
            product.sales_price : product.price;

        return price;
    }

    function calculateTotal(item) {
        return getFinalPrice(item) * item.quantity;
    }

    function calculateCartTotal(items) {
        var total = 0;

        for (var id in items) {
            var item = items[id];
            total += calculateTotal(item);
        }

        return total;
    }

    function countItems(items) {
        var count = 0;
        for (var i in items) {
            ++count;
        }
        return count;
    }

    function redrawCart(items) {
        redrawCartCount(items);
        redrawCartItems(items);
        redrawCartTotal(items);
    }

    function redrawCartCount(items) {
        $('.cart-count').text(countItems(items));
    }

    function redrawCartItems(items) {
        var shopBox = $('.shop-box');

        // Clear the current cart list
        $('.cart-item', shopBox).remove();

        for (var id in items) {
            var item = items[id];
            var product = item.details;
            var finalPrice = getFinalPrice(item);

            var nameComponent = $('<h2>')
                .text(product.name)
                .addClass('name');

            var priceComponent = $('<div>')
                .text(item.quantity + ' x ' + accounting.formatMoney(finalPrice))
                .addClass('price');

            var itemComponent = $('<li>')
                .addClass('cart-item')
                .append(nameComponent)
                .append(priceComponent);

            shopBox.prepend(itemComponent);
        }
    }

    function redrawCartTotal(items) {
        var cartTotal = calculateCartTotal(items);
        $('.shop-box .total .price').text(accounting.formatMoney(cartTotal));
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

                    $('.total-cost', $(btn).closest('tr')).text(accounting.formatMoney(totalCost));
                    $('.cart-total-cost', $(btn).closest('table')).text(accounting.formatMoney(cartTotalCost));

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
                    $('.tbl-cart .cart-total-cost').text(accounting.formatMoney(cartTotalCost));

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
