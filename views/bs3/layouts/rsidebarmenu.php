<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use frontend\widgets\Alert;
use yii\helpers\ArrayHelper;

/** @var $this \yii\web\View */
/** @var $content string Rendered view content */

?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>
    <div class="row">
        <div class="col-sm-10">
            <?= Alert::widget() ?>
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
<?php $this->endContent(); ?>
