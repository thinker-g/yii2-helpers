<?php
namespace thinker_g\Helpers\traits;

/**
 * When using this trait, a private attribute [[_nsedModuleViewPath]] will be added to the host class.
 * The class's code should never try to access this attribute.
 * @author Thinker_g
 */
trait NSedModuleViewPath
{
    private $_nsedModuleViewPath;

    /**
     * @inheritdoc
     * @see \yii\base\Module::setViewPath($path)
     */
    public function setViewPath($path)
    {
        $this->_nsedModuleViewPath = $path;
        parent::setViewPath($path);
    }

    /**
     * If the viewPath is not explicitly set for in this module's configuration,
     * it will return default "[[basePath]]/views/(back|front)".
     * @inheritdoc
     * @see \yii\base\Module::getViewPath()
     */
    public function getViewPath()
    {
        return is_null($this->_nsedModuleViewPath) ?
        parent::getViewPath()
        . DIRECTORY_SEPARATOR
        . array_pop(explode('\\', $this->controllerNamespace)) :
        parent::getViewPath();
    }

}

?>