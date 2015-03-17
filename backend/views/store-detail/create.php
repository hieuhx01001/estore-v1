<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\StoreDetail */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Store Detail',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Store Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-detail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
