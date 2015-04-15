<?php
namespace thinker_g\Helpers\controllers;

use yii\web\Controller;

/**
 *
 * @author Thinker_g
 * @property array $crudConfig Return controller module's "crudConfig" attribute.
 */
abstract class CrudController extends Controller
{
    public $moduleAttr = 'crudConfig';
    /**
     * @var array
     * A template of the array structure:
     * [
     *     CONTROLLER_ID => [
     *         'model' => FQN_OF_MODEL_CLASS,
     *         'search' => FQN_OF_MODEL_CLASS,
     *         'views' => [
     *             'i' => 'index',
     *             'c' => 'create',
     *             'v' => 'view',
     *             'u' => 'update'
     *         ]
     *     ],
     *     ...
     * ]
     */
    private $_crudConfig;
    /**
     * Getter, if _crudConfig is null, try to get it from module attribute.
     * @param string $controllerID
     * @return array
     */
    public function getCrudConfig($controllerID = null)
    {
        if (is_null($this->_crudConfig)) {
            $this->_crudConfig = $this->module->{$this->moduleAttr};
        }
        return $controllerID && isset($this->_crudConfig[$controllerID]) ?
            $this->_crudConfig[$controllerID] : $this->_crudConfig;
    }
    
    /**
     * Setter
     * @param array $config
     */
    public function setCrudConfig($config)
    {
        $this->_crudConfig = $config;
    }
}

?>