<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this \yii\web\View */
/* @var $model \thinker_g\TheArticle\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= $model->formName()?>-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php foreach ($model->attributes() as $attribute): ?>
        <?= $form->field($model, $attribute)->textInput(); ?>
    <?php endforeach; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
