<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Product */
/* @var $attributes \backend\models\Attribute[] */

$this->title = 'Create Product';
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="col-sm-6">
        <?= $this->render('_form', [
            'model' => $model,
            'categories' => $categories,
        ]) ?>
    </div>

</div>
