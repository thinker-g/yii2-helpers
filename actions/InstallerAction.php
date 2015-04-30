<?php
/**
 * @link https://github.com/thinker-g/yii2-helpers
 * @copyright Copyright (c) thinker_g (Jiyan.guo@gmail.com)
 * @license MIT
 * @version v1.0.0
 * @author thinker_g
 */

namespace thinker_g\Helpers\actions;

use Yii;
use yii\base\Action;
use yii\helpers\Console;

abstract class InstallerAction extends Action
{
    public $giiID = 'gii';

    /**
     * Check whether Gii module is loaded.
     */
    public function checkGii()
    {
        if (!isset(Yii::$app->controllerMap[$this->giiID])) {
            $msg = "Command \"{$this->giiID}\" is not available.\n";
            $msg .= "Please check to ensure the module is mounted and added to bootstrap phase.\n";
            $this->controller->stderr($msg, Console::FG_RED);
            Yii::$app->end(1);
        }
    }

    protected function getModelClassName($tableName, $migration = null)
    {
        $db = is_null($migration) ? Yii::$app->getDb() : $this->migration->db;
        $className = preg_replace(
            ["/(?:^{$db->tablePrefix}|$db->tablePrefix$)/", '/_/'],
            [null, ' '],
            $tableName
        );
        return str_replace(' ', null, ucwords($className));
    }
}

?>