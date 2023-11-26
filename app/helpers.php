<?php

use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

if (! function_exists('format_datetime')){

    /**
     * @param $dateTime
     * @return string
     */
    function format_datetime($dateTime): string
    {
        return \Carbon\Carbon::parse($dateTime)->format(format_date() .' '. format_time());
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
            return $currency_symbol . number_format($amount, 2);
        }else if ($currency_position === 'before_with_space_amount'){
            return $currency_symbol .' '. number_format($amount, 2);
        }else if ($currency_position === 'after_amount'){
            return number_format($amount, 2) . $currency_symbol;
        }else if ($currency_position === 'after_with_space_amount'){
            return number_format($amount, 2) .' '. $currency_symbol;
        }
    }
}

if (! function_exists('app_information')){
    /**
     * @param $key
     * @return CacheManager|Application|mixed
     */
    function app_information($key = null): mixed
    {
       $app_information = cache('app_setting');
       if ($key){
           return $app_information[$key];
       }
       return $app_information;
    }
}

if (! function_exists('generate_pdf')){
    /**
     * @return StreamedResponse
     * @throws \Mpdf\MpdfException
     */
    function generate_pdf($file, $dependentData = []): StreamedResponse
    {
        $mpdf = new Mpdf([
            'default_font' => 'FreeSerif',
            'mode' => 'utf-8',
            'format' => "A4",
            'margin_top' => 34,
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_bottom' => 12,
            'tempDir'=> base_path('storage/app/mpdf'),
            'allow_charset_conversion' => true,
        ]);

        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Pdf-preview"'
        ];

        $html = view($file, $dependentData);
        $html = $html->render();
        $mpdf->SetTitle('Pdf-preview');
        $mpdf->WriteHTML($html, 0);

        // Save PDF on your public storage
        Storage::disk('public')->put('Pdf-preview.pdf', $mpdf->Output('Pdf-preview', 'S'));

        // Get file back from storage with the give header information
        return Storage::disk('public')->download('Pdf-preview.pdf', 'Request', $header);
    }
}
