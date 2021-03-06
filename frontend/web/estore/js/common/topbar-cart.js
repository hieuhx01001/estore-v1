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

    $('a.buy, button.buy').click(function (evt) {

        evt.preventDefault();

        var btn = $(this);
        var url = btn.data('url');
        var id = btn.data('id');

        $.ajax({
            url: url,
            data: {
                id: id,
                qty: 1
            },
            success: function (data) {
                var success = data.success;

                if (success) {
                    alert('Đã thêm sản phẩm vào giỏ hàng');

                    // Redraw the cart on top bar
                    redrawCart(data.items);
                }
                else {
                    alert('Có vấn đề trong quá trình thêm sản phẩm vào giỏ hàng');
                }
            },
            error: function () {
                alert('Lỗi xảy ra trên máy chủ');
            }
        });

        return false;
    });

    $('a.cart, button.cart').click(function (evt) {

        evt.preventDefault();

        var btn = $(this);
        var url = btn.data('url');
        var id = btn.data('id');
        var qty = 1;

        $.ajax({
            url: url,
            data: {
                id: id,
                qty: qty
            },
            success: function (data) {
                var success = data.success;

                if (success) {
                    alert('Đã thêm sản phẩm vào giỏ hàng');

                    // Redraw the cart on top bar
                    redrawCart(data.items);
                }
                else {
                    alert('Có vấn đề trong quá trình thêm sản phẩm vào giỏ hàng');
                }
            },
            error: function () {
                alert('Lỗi xảy ra trên máy chủ');
            }
        });

        return false;
    });

});
