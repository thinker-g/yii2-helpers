<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this \yii\web\View */
/* @var $searchModel \yii\base\Model */
/* @var $dataProvider \yii\data\DataProvider */

$this->title = Yii::t('app', $searchModel->formName() . '(s)');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', $this->context->module->name),
    'url' => ['/' . $this->context->module->uniqueId]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">

    <h1><?= $searchModel->formName() ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class="btn-group">
        <?= Html::a(Yii::t('app', 'New Model'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => ArrayHelper::merge(
            $searchModel->attributes(),
            [['class' => 'yii\grid\ActionColumn', 'header' => 'Actions']]
        ),
    ]); ?>

</div>
