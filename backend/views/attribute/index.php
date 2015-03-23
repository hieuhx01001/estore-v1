<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AttributeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Attributes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attribute-index">

    <!--<h1><?/*= Html::encode($this->title) */?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Attribute',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'name',
            'type',
            'is_required',
            'regex',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
