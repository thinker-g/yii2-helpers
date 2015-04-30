<?php
namespace thinker_g\Helpers\controllers;

use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

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
    public function getMvMap($renew = false, $controllerID = null)
    {
        if (is_null($controllerID)) {
            // Return current controller's mv map.
            if (is_null($this->_mvMap) || $renew) {
                if (is_null($this->moduleAttr)) {
                    $this->_mvMap = $this->defaultMap;
                } else {
                    $this->_mvMap = $this->assembleMap($this->module->{$this->moduleAttr}, $this->id);
                }
            }
            return $this->_mvMap;
        } else {
            // Return other controller's mv map.
            return $this->assembleMap($this->module->{$this->moduleAttr}, $controllerID);
        }
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
        } elseif ($actionID === self::KEY_DEFAULT) {
            // return default model class
            return isset($this->getMvMap()[self::KEY_DEFAULT][$key]) ? $this->getMvMap()[self::KEY_DEFAULT][$key] : null;
        } else {
            // return other action's model class
            return $this->getActionMvMap($actionID)[$key];
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
    public function assembleMap(&$map, $targetKey)
    {
        if (isset($map[self::KEY_DEFAULT])) {
            return isset($map[$targetKey]) ? ArrayHelper::merge($map[self::KEY_DEFAULT], $map[$targetKey]) : $map[self::KEY_DEFAULT];
        } else {
            // No default map set
            return isset($map[$targetKey]) ? $map[$targetKey] : [];
        }
    }

    /**
     * @param string $actionID
     * @return Ambigous <multitype:, array, NULL, mixed, unknown>
     */
    public function getViewID($actionID = null, $renew = false)
    {
        $actionID || $actionID = $this->action->id;
        if (
            isset($this->getActionMvMap($actionID, $renew)[self::KEY_VIEW])
            && !empty($this->getActionMvMap($actionID, $renew)[self::KEY_VIEW])
        ) {
            return $this->getActionMvMap($actionID, $renew)[self::KEY_VIEW];
        } else {
            return $actionID;
        }
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

}

?>