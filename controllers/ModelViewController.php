<?php
namespace thinker_g\Helpers\controllers;

use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\helpers\StringHelper;

/**
 * @author Thinker_g
 * 
 * @property array $mvMap
 * @property array $actionMvMap
 * @property string|array $modelClass
 * @property string $viewID
 */
abstract class ModelViewController extends Controller
{
    const KEY_DEFAULT = 0;
    const KEY_MODEL = 'model';
    const KEY_SEARCH = 'search';
    const KEY_VIEW = 'view';
    public $moduleMvMapAttr;
    /**
     * This will be replaced if [[moduleAttr]] is set.
     * @var array
     */
    public $controllerMvMap = [
        [
            self::KEY_MODEL => 'Model',
            self::KEY_SEARCH => 'ModelSearch',
        ],
    ];
    private $_mvMap;
    private $_lastActionID;
    private $_actionMvMap;

    /**
     *
     * @param array $map
     * @param string $targetKey
     * @return array|null
     */
    public static function assembleMap(&$map, $targetKey)
    {
        $default = isset($map[self::KEY_DEFAULT]) ? $map[self::KEY_DEFAULT] : [];
        if ($targetKey == (string)self::KEY_DEFAULT) {
            // retrieving the default map
            return $default;
        } else {
            // not retrieving the default map
            $target = isset($map[$targetKey]) ? $map[$targetKey] : [];
            return ArrayHelper::merge($default, $target);
        }
    }

    /**
     * Get class name from a Yii configuration array.
     * Returns the parameter itself if it's a string, or the element indexed by 'class' if the parameter is an array.
     * @param string|array $config
     * @return string The class name fetched from conf array.
     */
    public static function classNameFromConf($config)
    {
        return is_string($config) ? $config : $config['class'];
    }

    /**
     * Return model-view map by controller id.
     * Leave $controllerID as null to fetch all maps indexed by the given controller ID.
     * @param bool $renew
     * @param string $controllerID
     * @return array
     */
    public function getMvMap($renew = false, $controllerID = null)
    {
        if (is_null($controllerID) || $controllerID == $this->id) {
            // Return current controller's mv map.
            if (is_null($this->_mvMap) || $renew) {
                if (is_null($this->moduleMvMapAttr)) {
                    $this->_mvMap = $this->controllerMvMap;
                } else {
                    $this->_mvMap = $this->assembleMap($this->module->{$this->moduleMvMapAttr}, $this->id);
                }
            }
            return $this->_mvMap;
        } else {
            // Return other controller's mv map.
            return $this->assembleMap($this->module->{$this->moduleMvMapAttr}, $controllerID);
        }
    }

    /**
     * Set mv map for ONLY current controller.
     * @param array $config
     */
    public function setMvMap($config)
    {
        $this->_mvMap = $config;
    }

    /**
     *
     * @param string $actionID
     * @param bool $renew
     * @param array $contextMap Controller level model-view map array.
     * @return array
     */
    public function getActionMvMap($actionID = null, $renew = false, $contextMap = null)
    {
        $actionID || $actionID = $this->action->id;
        if (is_null($contextMap)) {
            if ($renew || is_null($this->_actionMvMap) || $actionID != $this->_lastActionID) {
                $this->_lastActionID = $actionID;
                $this->_actionMvMap = $this->assembleMap($this->getMvMap(), $actionID);
            }
            return $this->_actionMvMap;
        } else {
            return $this->assembleMap($contextMap, $actionID)[$actionID];
        }
    }

    /**
     *
     * @param string $key
     * @param string $actionID
     * @param array $contextMap
     * @return string|array|null
     */
    public function getModelClass($key = self::KEY_MODEL, $actionID = null, $contextMap = null)
    {
        return $this->getActionMvMap($actionID, false, $contextMap)[$key];
    }

    /**
     * @param string $actionID
     * @param bool $renew
     * @param array $contextMap
     * @return string
     */
    public function getViewID($actionID = null, $renew = false, $contextMap = null)
    {
        $targetMap = $this->getActionMvMap($actionID, $renew, $contextMap);
        return isset($targetMap[self::KEY_VIEW]) ? $targetMap[self::KEY_VIEW] : $this->action->id;
    }

    /**
     * @param string $id
     * @param string $actionID
     * @param array $contextMap
     * @throws NotFoundHttpException
     * @return \yii\db\ActiveRecordInterface
     */
    protected function findModel($id, $actionID = null, $contextMap = null)
    {
        $modelClass = static::classNameFromConf($this->getModelClass(self::KEY_MODEL, $actionID, $contextMap));
        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Model not found.');
        }
    }

}

?>