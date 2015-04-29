<?php
namespace thinker_g\Helpers\controllers;

use yii\web\Controller;
use yii\helpers\ArrayHelper;

/**
 * @author Thinker_g
 */
abstract class ModelViewController extends Controller
{
    const KEY_DEFAULT = 0;
    const KEY_MODEL = 'model';
    const KEY_SEARCH = 'search';
    const KEY_VIEW = 'view';
    public $moduleAttr;
    /**
     * This will be replaced if [[moduleAttr]] is set.
     * @var array
     */
    public $defaultMap = [
        [
            self::KEY_MODEL => 'Model',
            self::KEY_SEARCH => 'ModelSearch',
            self::KEY_VIEW => 'index'
        ],
        'create' => [self::KEY_VIEW => 'create'],
        'view' => [self::KEY_VIEW => 'view'],
        'update' => [self::KEY_VIEW => 'update']
    ];
    private $_mvMap;
    private $_lastActionID;
    private $_actionMvMap;
    /**
     * Return model-view map by controller id.
     * Leave $controllerID as null to fetch all maps indexed by controller ID.
     * @param string $controllerID
     * @return array
     */
    public function getMvMap($renew = false)
    {
        if (is_null($this->_mvMap) || $renew) {
            if (is_null($this->moduleAttr)) {
                $this->_mvMap = $this->defaultMap;
            } else {
                $this->_mvMap = $this->assembleMap($this->module->{$this->moduleAttr}, $this->id);
            }
        }
        return $this->_mvMap;
    }

    /**
     * Setter
     * @param array $config
     */
    public function setMvMap($config)
    {
        $this->_mvMap = $config;
    }

    /**
     *
     * @param string $key
     * @param string $actionID
     * @return string|array|null
     */
    public function getModelClass($key = self::KEY_MODEL, $actionID = null)
    {
        if (is_null($actionID)) {
            // return current action's model class
            return $this->getActionMvMap()[$key];
        } elseif ($actionID === 0) {
            // return default model class
            return isset($this->getMvMap()[0][$key]) ? $this->getMvMap()[0][$key] : null;
        } else {
            // return other action's model class
            return $this->getActionMvMap($actionID)[$key];
        }

    }

    /**
     * @param string $actionID
     * @return Ambigous <multitype:, array, NULL, mixed, unknown>
     */
    public function getViewID($actionID = null, $renew = false)
    {
        $actionID || $actionID = $this->action->id;
        return $this->getActionMvMap($actionID, $renew)[self::KEY_VIEW];
    }

    /**
     * @param string $id
     * @throws NotFoundHttpException
     * @return \yii\db\ActiveRecordInterface
     */
    protected function findModel($id, $actionID = null)
    {
        if (is_array($modelClass = $this->getModelClass(self::KEY_MODEL, $actionID))) {
            $modelClass = $modelClass['class'];
        }
        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Model not found.');
        }
    }

    /**
     *
     * @param string $actionID
     * @param bool $renew
     * @return array
     */
    public function getActionMvMap($actionID = null, $renew = false)
    {
        $actionID || $actionID = $this->action->id;
        if ($renew || is_null($this->_actionMvMap) || $actionID != $this->_lastActionID) {
            $this->_lastActionID = $actionID;
            $this->_actionMvMap = $this->assembleMap($this->getMvMap(), $actionID);
        }
        return $this->_actionMvMap;
    }

    /**
     *
     * @param array $map
     * @param string $targetKey
     * @return array|null
     */
    protected function assembleMap(&$map, $targetKey)
    {
        if (isset($map[0])) {
            return isset($map[$targetKey]) ? ArrayHelper::merge($map[0], $map[$targetKey]) : $map[0];
        } else {
            // No default map set
            return isset($map[$targetKey]) ? $map[$targetKey] : [];
        }
    }
}

?>