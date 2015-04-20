<?php
namespace thinker_g\Helpers\controllers;

use yii\web\Controller;

/**
 * Configurable controller for manipulating a single model with different actions using different views.
 * @author Thinker_g
 * @property array $mvConfig Return controller module's "mvConfig" attribute.
 * @property string $viewID
 * @property string $modelClass
 */
abstract class ModelViewController extends Controller
{
    public $moduleAttr = 'mvConfig';
    /**
     * @var array
     * A template of the array structure:
     * [
     *     CONTROLLER_ID => [
     *         'model' => FQN_OF_MODEL_CLASS,
     *         'search' => FQN_OF_MODEL_CLASS,
     *         'views' => [
     *             ACTION_ID => VIEW_ID,
     *             'create' => 'create',
     *             'view' => 'view',
     *             'update' => 'update',
     *             'index' => 'index
     *         ]
     *     ],
     *     ...
     * ]
     */
    private $_mvConfig;
    /**
     * Getter, if _crudConfig is null, try to get it from module attribute.
     * @param string $controllerID
     * @return array
     */
    public function getMvConfig($controllerID = null)
    {
        if (is_null($this->_mvConfig)) {
            $this->_mvConfig = $this->module->{$this->moduleAttr};
        }
        return $controllerID && isset($this->_mvConfig[$controllerID]) ?
            $this->_mvConfig[$controllerID] : $this->_mvConfig;
    }

    /**
     * Setter
     * @param array $config
     */
    public function setMvConfig($config)
    {
        $this->_mvConfig = $config;
    }

    /**
     * Return model class name according to the $key.
     * @param string $key 'model' for normal model, 'search' for search model class name.
     * @return string
     */
    public function getModelClass($key)
    {
        return $this->getMvConfig($this->id)[$key];
    }

    /**
     * Get view ID by Action ID, action is default to the current action id.
     * @param string $actionID
     * @return string
     */
    public function getViewID($actionID = null)
    {
        empty($actionID) && ($actionID = $this->action->id);
        return $this->getMvConfig($this->id)['views'][$actionID];
    }

    /**
     * Finds the target model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (is_array($modelClass = $this->getModelClass('model'))) {
            $modelClass = $modelClass['class'];
        }
        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

?>