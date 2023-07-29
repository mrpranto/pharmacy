<?php

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
