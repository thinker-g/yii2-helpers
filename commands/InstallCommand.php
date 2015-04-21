<?php
namespace thinker_g\Helpers\commands;

use yii\console\Controller;

/**
 * Install console command.
 * @author Thinker_g
 *
 */
class InstallCommand extends Controller
{
    public $migration;
    public $actions = [];

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