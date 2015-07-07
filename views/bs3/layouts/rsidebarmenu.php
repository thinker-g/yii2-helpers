<?php
use yii\bootstrap\Nav;
use thinker_g\Helpers\widgets\FlashAlert;

/** @var $this \yii\web\View */
/** @var $content string Rendered view content */
?>
<?php Yii::$app->layout && $this->beginContent(
    Yii::$app->getLayoutPath() . DIRECTORY_SEPARATOR . Yii::$app->layout . '.' . Yii::$app->getView()->defaultExtension
); ?>
    <div class="row">
        <div class="col-sm-10">
            <?= FlashAlertAlert::widget() ?>
            <?= $content ?>
        </div><!-- .col-sm-10 -->
        <div class="col-sm-2">
            <?php if (isset($this->params['sidebarMenu'])): ?>
                <div class="panel panel-default">
                        <div class="panel-heading">Nav Menu</div>
                        <?= Nav::widget([
                        'options' => [
                            'class' => 'nav-pills nav-stacked',
                        ],
                        'items' => $this->params['sidebarMenu']
                    ]); ?>
                </div>
            <?php endif; ?>
        </div><!-- .col-sm-2 -->
    </div><!-- .row -->
<?php Yii::$app->layout && $this->endContent(); ?>
