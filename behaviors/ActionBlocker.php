<?php
namespace thinker_g\Helpers\behaviors;

use Yii;

/**
 * ActionBlocker is used for blocking inline controllers/actions by patterns of an action ID.
 *
 * @author Thinker_g
 *
 */
class ActionBlocker extends RegexActionFilter
{
    /**
     * The exception threw while the filter is activated.
     * @var string|array Class name or config array for [[\Yii::createObject()]].
     */
    public $exception = 'yii\web\ForbiddenHttpException';

    /**
     * This will be passed as 2nd parameter of [[\Yii::createObject()]].
     * @var array
     */
    public $exceptionParams = [];

    /**
     * @inheritdoc
     * @see \yii\base\ActionFilter::beforeAction()
     */
    public function beforeAction($action)
    {
        if ($this->isActive($action)) {
            throw Yii::createObject($this->exception, $this->exceptionParams);
        }
    }
}

?>