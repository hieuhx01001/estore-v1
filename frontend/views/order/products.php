<?php
use backend\models\Product;

/** @var Product[] $products */

$urlManager = Yii::$app->urlManager;
?>

<?php foreach ($products as $product) : ?>

    <div class="product-card">
        <h2><?= $product->name ?></h2>
        <form class="product-card-form">
            <div class="form-group">
                <label>Quantity</label>
                <input type="hidden" class="txt-product-id" value="<?= $product->id ?>">
                <input type="text" class="txt-product-qty form-control" value="1">
            </div>
            <div class="form-group">
                <button class="btn-add-to-cart btn btn-default pull-right">Add to cart</button>
            </div>
        </form>
    </div>

<?php endforeach ?>

<style>
    .product-card {
        display: inline-block;
        padding: 15px;
        border: solid 1px #CCC;
        border-radius: 3px;
    }
    .product-card h2 {
        margin-top: 0;
    }
</style>

<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script>
    $(function () {

        $('.product-card-form').submit(function (evt) {

            // Prevent page reloading on form submission
            evt.preventDefault();

            var id = $('.txt-product-id', this).val();
            var qty = $('.txt-product-qty', this).val();

            $.ajax({
                url: '<?= $urlManager->createUrl(['order/ajax-add-to-cart']) ?>',
                data: {
                    id: id,
                    qty: qty
                },
                success: function (data) {
                    var success = data.success;
                    if (success) {
                        // Show success message
                        alert('Added to cart successfully');
                        // Print log in browser's console
                        console.log('-- Added to cart successfully --');
                        console.log(data);
                    }
                    else {
                        // Show failure message
                        alert(data.message);
                    }
                },
                error: function (data) {
                    alert('Error happened on server');
                    console.log('-- Error happened on server');
                    console.log(data);
                }
            });
        });
    });
</script>
