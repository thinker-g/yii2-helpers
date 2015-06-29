<?php
namespace thinker_g\Helpers\traits;

/**
 * This trait is used for providing realtime console log messages.
 * It can be used in any classes extend [[\yii\console\Controller]].
 * @author Thinker_g
 */
trait RealtimeConsoleLog
{

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