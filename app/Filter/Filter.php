<?php

namespace App\Filter;

class Filter
{
    use \Nette\SmartObject;

    /**
     * Filter loader.
     *
     * @param $filter
     * @return mixed
     */
    public static function loader($filter)
    {
        return (method_exists(__CLASS__, $filter) ? call_user_func_array([__CLASS__, $filter], array_slice(func_get_args(), 1)) : NULL);
    }


    /**
     * Returns last datetime of file modification or random number if file is not found.
     * Used for browser cache management - css, js files etc...
     *
     * @param $filePath
     * @return int|string
     */
    public static function fileVersion($filePath)
    {
        if (file_exists(WWW_DIR . $filePath)) {
            return $filePath . '?v=' . filemtime(WWW_DIR . $filePath);
        } else {
            return $filePath;
        }
    }
}
