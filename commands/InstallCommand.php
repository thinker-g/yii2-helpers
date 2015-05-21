<?php
namespace thinker_g\Helpers\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Loader controller for actions of installing/initialization tasks.
 * You could mount your Actions to this controller instead of creating a new console controller.
 * @author Thinker_g
 *
 */
class InstallCommand extends Controller
{
    public $migration = null;
    public $actions = [];

    /* (non-PHPdoc)
     * @see \yii\console\Controller::options()
     */
    public function options($actionID)
    {
        $options = [
            'migrate' => [
                'migration'
            ]
        ];
        return $options[$actionID];
    }

    /**
     * @inheritdoc
     * @see \yii\base\Controller::actions()
     */
    public function actions()
    {
        return $this->actions;
    }

    /**
     * Display this help message.
     * @return number
     */
    public function actionIndex()
    {
        $this->run("/help", [$this->id]);
        return 0;
    }

    /**
     * Run migration with FQN of a migration class.
     * @param string $operation The method of migration to run.
     * @return number
     */
    public function actionMigrate($operation = 'up')
    {
        if ($this->confirm("Migrate {$operation} migration: " . $this->migration)) {
            $migration = Yii::createObject($this->migration);
            $migration->{$operation}();
        } else {
            $this->stdout('User abort.' . PHP_EOL, Console::FG_YELLOW);
        }
        return 0;
    }

    /**
     * Log method.
     * @param string $msg
     * @param bool $enableTS
     * @param bool $returnLine
     * @param bool | int $padLength Dots will be appended till this length.
     */
    public function consoleLog(
        $msg,
        $enableTS = true,
        $fgColor = null,
        $returnLine = true,
        $padLength = false
    )
    {
        if ($enableTS) {
            $msg = '[' . date('Y-m-d H:i:s') . '] ' . $msg;
        }
        if ($padLength) {
            $msg = str_pad($msg, $padLength, '.', STR_PAD_RIGHT);
        }
        if ($returnLine) {
            $msg .= PHP_EOL;
        }
        $this->stdout($msg, $fgColor);
    }
}

?>