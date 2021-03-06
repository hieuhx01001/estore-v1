<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Attribute */
/* @var $attributeTypes Array */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Attribute',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attribute-create">

    <!--<h1><?/*= Html::encode($this->title) */?></h1>-->

    <?= $this->render('_form', [
        'model'          => $model,
        'attributeTypes' => $attributeTypes,
    ]) ?>

</div>
