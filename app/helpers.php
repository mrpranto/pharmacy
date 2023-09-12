<?php

use Illuminate\Support\Facades\Cache;

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

if (! function_exists('pagination')){
    /**
     * @return mixed
     */
    function pagination(): mixed
    {
        return Cache::get('general_setting') ? Cache::get('general_setting')['pagination'] : 10;
    }
}

if (! function_exists('format_date')){
    /**
     * @return mixed
     */
    function format_date(): mixed
    {
        return Cache::get('general_setting') ? Cache::get('general_setting')['date_format'] : 'Y-m-d';
    }
}

if (! function_exists('format_time')){
    /**
     * @return mixed
     */
    function format_time(): mixed
    {
        return Cache::get('general_setting') ? Cache::get('general_setting')['time_format'] : 'h:i:s a';
    }
}

if (! function_exists('show_currency')){

    /**
     * @param $amount
     * @return string|void
     */
    function show_currency($amount)
    {
        $general_setting = Cache::get('general_setting');
        $currency_position = $general_setting ? $general_setting['currency_symbol_position'] : 'before_with_space_amount';
        $currency_symbol = $general_setting ? $general_setting['currency_symbol'] : '\u09f3';

        if ($currency_position === 'before_amount'){
            return $currency_symbol . $amount;
        }else if ($currency_position === 'before_with_space_amount'){
            return $currency_symbol .' '. $amount;
        }else if ($currency_position === 'after_amount'){
            return $amount . $currency_symbol;
        }else if ($currency_position === 'after_with_space_amount'){
            return $amount .' '. $currency_symbol;
        }
    }
}
