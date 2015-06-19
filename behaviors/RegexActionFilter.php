<?php
namespace thinker_g\Helpers\behaviors;

use yii\base\ActionFilter;
use yii\base\Module;

/**
 * RegexActionFilter is the base class of all pattern based action filters.
 *
 * This filter works exactly the same way the [[\yii\base\ActionFilter]] works,
 * only the IDs in [[only]] and [[except]] are treated as regular expression patterns.
 *
 * @author Thinker_g
 *
 */
class RegexActionFilter extends ActionFilter
{
    public $patternDelimiter = '@';

    /**
     * @inheritdoc
     * @see \yii\base\ActionFilter::isActive()
     */
    protected function isActive($action)
    {
        if ($this->owner instanceof Module) {
            // convert action uniqueId into an ID relative to the module
            $mid = $this->owner->getUniqueId();
            $id = $action->getUniqueId();
            if ($mid !== '' && strpos($id, $mid) === 0) {
                $id = substr($id, strlen($mid) + 1);
            }
        } else {
            $id = $action->id;
        }
        if (!empty($this->only)) {
            $onlyPatterns = empty($this->except) ? $this->only : array_diff($this->only, $this->except);
            if (self::hasAnyMatch($id, $onlyPatterns, $this->patternDelimiter)) {
                return true;
            }
        }

        if (!empty($this->except)) {
            if (!self::hasAnyMatch($id, $this->except, $this->patternDelimiter)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Return if anyone in the given patterns matches the needle.
     *
     * @param string $needle
     * @param array $patterns
     * @param string $delimiter Default to '/', change
     */
    public static function hasAnyMatch($needle, $patterns, $delimiter = '/')
    {
        foreach ($patterns as $pattern) {
            if (preg_match($delimiter . $pattern . $delimiter, $needle)) {
                return true;
            }
        }
        return false;
    }

}

?>