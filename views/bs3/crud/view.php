<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use thinker_g\TheArticle\models\Comment;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

/* @var $this \yii\web\View */
/* @var $model \yii\base\Model */

$this->title = Yii::t('app', 'View ' . $model->formName());
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', $this->context->module->name),
    'url' => ['/' . $this->context->module->uniqueId]
];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'All ' . $model->formName() . '(s)'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= $model->formName() ?>-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="btn-group">
                <?= Html::a(Yii::t('app', 'Update'),
                    ArrayHelper::merge(['update'], $model->getPrimaryKey(true)),
                    ['class' => 'btn btn-primary']);
                ?>
                <?= Html::a(Yii::t('app', 'Delete'),
                    ArrayHelper::merge(['delete'], $model->getPrimaryKey(true)),
                    [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]
                ) ?>
            </span><!-- $span.btn-group -->
        </div>
        <?= DetailView::widget([
            'model' => $model
        ]) ?>
    </div>

</div>
