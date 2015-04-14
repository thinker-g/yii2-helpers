<?php
namespace thinker_g\Helpers\controllers;

use yii\web\Controller;

/**
 *
 * @author Thinker_g
 * @property array $crudConfig Return controller module's "crudModels" attribute.
 */
abstract class CrudController extends Controller
{
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
     *
     * @param string $controllerID
     * @return array
     */
    public function getCrudConfig($controllerID = null)
    {
        if (is_null($this->_crudConfig)) {
            $this->_crudConfig = $this->module->crudModels;
        }
        return $controllerID && isset($this->_crudConfig[$controllerID]) ?
            $this->_crudConfig[$controllerID] : $this->_crudConfig;
    }
}

?>