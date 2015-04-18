<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

<!--    <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'order_code',
            [
                'attribute' => 'customer_name',
                'value' => 'customer.name'
            ],
            [
                'attribute' => 'order_status',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->order_status === $model::STATUS_NEW) {
                        $class = 'label-success';
                    } elseif ($model->order_status === $model::STATUS_PROCESSING) {
                        $class = 'label-warning';
                    }elseif ($model->order_status === $model::STATUS_FINISH) {
                        $class = 'label-info';
                    } else {
                        $class = 'label-danger';
                    }
                    return '<span class="label ' . $class . '">' . $model->statusLabel . '</span>';
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'order_status',
                    $arrayStatus,
                    ['class' => 'form-control', 'prompt' => Yii::t('app', 'Please Filter')]
                )
            ],
            [
                'attribute' => 'order_date',
                'format' => ['date', 'd-M-Y'],
            ],
            // 'order_detail:ntext',
            // 'shipment_status',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    $link = '#';
                    switch ($action) {
                        case 'view':
                            $link = Yii::$app->getUrlManager()->createUrl(['order/update', 'id' => $model->id]);
                            break;
                        case 'update':
                            $link = Yii::$app->getUrlManager()->createUrl(['order/update', 'id' => $model->id]);
                            break;
                    }
                    return $link;
                },
            ],
        ],
    ]); ?>

</div>
