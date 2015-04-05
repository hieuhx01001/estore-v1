$(function () {

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

    $('.buy').click(function (evt) {

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

    $('.cart').click(function (evt) {

        evt.preventDefault();

        var btn = $(this);
        var url = btn.data('url');
        var id = btn.data('id');
        var qty = $('.qty_list select').val();

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
