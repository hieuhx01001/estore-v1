<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;

$formatter = new \yii\i18n\Formatter();
?>
<div class="product-index">

    <!--<h1><?/*= Html::encode($this->title) */?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'name',
            'code',
            'price',
            'quantity',
            [
                'attribute' => 'created_at',
                'content' => function($model) use ($formatter)
                {
                    return $formatter->asDate($model->created_at);
                }
            ],
            [
                'attribute' => 'updated_at',
                'content' => function($model) use ($formatter)
                {
                    return $formatter->asDate($model->updated_at);
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
