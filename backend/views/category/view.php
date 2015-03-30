<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Category */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-view">

    <!--<h1><?/*= Html::encode($this->title) */?></h1>-->

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'parent.name',
            'name',
            'level',
        ],
    ]) ?>

    <div>
        <ul class="list-group">
            <li class="list-group-item list-group-item-heading list-group-item-info">
                <b>Category Attributes</b>
            </li>
            <?php foreach ($categoryAttributes as $ca) : ?>
                <li class="list-group-item">
                    <?= $ca->attr->name ?>
                </li>
            <?php endforeach ?>
        </ul>
        <ul class="list-group">
            <li class="list-group-item list-group-item-heading list-group-item-info">
                <b>Inherited Attributes</b>
            </li>
            <?php foreach ($model->allAncestors as $ancestor) : ?>
                <?php foreach ($ancestor->attrs as $attr) : ?>
                    <li class="list-group-item">
                        <?= $attr->name ?>
                    </li>
                <?php endforeach ?>
            <?php endforeach ?>
        </ul>
    </div>

</div>
