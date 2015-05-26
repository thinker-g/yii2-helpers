<?php

use yii\helpers\Html;


/* @var $this \yii\web\View */
/* @var $model \yii\base\Model */

$this->title = Yii::t('app', 'New ' . $model->formName());
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', $this->context->module->name),
    'url' => ['/' . $this->context->module->uniqueId]
];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'All ' . $model->formName() . '(s)'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= $model->formName() ?>-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
