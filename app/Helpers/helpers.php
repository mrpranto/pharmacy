<?php

include 'settings.php';

if (!function_exists('active_class')) {
    /**
     * @param $path
     * @param $active
     * @return mixed
     */
    function active_class($path, $active = 'active'): mixed
    {
        return call_user_func_array('Request::is', (array)$path) ? $active : '';
    }
}

if (!function_exists('is_active_route')) {
    /**
     * @param $path
     * @return string
     */
    function is_active_route($path): string
    {
        return call_user_func_array('Request::is', (array)$path) ? 'true' : 'false';
    }
}

if (!function_exists('show_class')) {
    /**
     * @param $path
     * @return string
     */
    function show_class($path): string
    {
        return call_user_func_array('Request::is', (array)$path) ? 'show' : '';
    }
}

if (!function_exists('__t')) {

    /**
     * @param $key
     * @param $options
     * @param $isCapitalized
     * @return string
     */
    function __t($key = '', $options = [], $isCapitalized = false): string
    {
        $vars = count($options) ? array_merge(...array_map(function ($k) use ($options) {
            $value = __("default.$options[$k]");
            return [
                "{" . $k . "}" => $value,
                "{ $k }" => $value,
                "{ $k}" => $value,
                "{" . $k . " }" => $value,
                ":$k" => $value
            ];
        }, array_keys($options))) : [];

        $string = strtr(__("default.{$key}"), $vars);
        return $isCapitalized ? ucwords($string) : $string;
    }
}

