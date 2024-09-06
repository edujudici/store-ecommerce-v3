<?php

if (! function_exists('debug')) {

    /**
     * log information quickly, handling string or list
     *
     * @param [string] $path
     *
     * @return void
     */
    function debug($arg)
    {
        $str = is_string($arg) ? $arg : var_export($arg, true);
        Log::debug($str);
    }
}

if (! function_exists('checkIfFolderExists')) {

    /**
     * if folder does not exists, make it happen!.. have fun ;)
     *
     * @param [string] $path
     *
     * @return void
     */
    function checkIfFolderExists($path)
    {
        if (! is_dir($path)) {
            mkdir($path, 751);
        }
    }
}

if (! function_exists('removeMask')) {

    /**
     * exclude all charecter not number
     *
     * @param [string] $path
     *
     * @return void
     */
    function removeMask($str)
    {
        return preg_replace('/[^0-9]/', '', $str);
    }
}

if (! function_exists('parseDateToEn')) {

    /**
     * format date pt to pattern en
     *
     * @param [date] $datePt
     *
     * @return date
     */
    function parseDateToEn($datePt)
    {
        $date = \Carbon\Carbon::createFromFormat('d/m/Y', $datePt);
        return $date->format('Y-m-d');
    }
}

if (! function_exists('parseDateToPt')) {

    /**
     * format date en to pattern pt
     *
     * @param [date] $dateEn
     *
     * @return date
     */
    function parseDateToPt($dateEn)
    {
        $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dateEn);
        return $date->format('d/m/Y');
    }
}

if (! function_exists('zeroLeft')) {

    /**
     * complete text with number zero on the left
     *
     * @param [string] $text
     * @param [int] $amount
     *
     * @return string
     */
    function zeroLeft($text, $amount)
    {
        return str_pad($text, $amount, 0, STR_PAD_LEFT);
    }
}

if (! function_exists('getMonthYearDesc')) {

    /**
     * Get string date
     *
     * @param [string] $date
     *
     * @return string date formatted
     */
    function getMonthYearDesc($dataVencimento)
    {
        $lastMonth = date(
            'Y-m-d',
            strtotime('-1 month', strtotime($dataVencimento))
        );

        setlocale(
            LC_ALL,
            'pt_BR',
            'pt_BR.iso-8859-1',
            'pt_BR.utf-8',
            'portuguese'
        );

        date_default_timezone_set('America/Sao_Paulo');
        return strftime('%B/%Y', strtotime($lastMonth));
    }
}

if (! function_exists('uploadImage')) {

    /**
     * Upload image to store folder
     *
     * @param Request $request
     * @param array $params
     * @param string $fieldName
     *
     * @return string image path
     */
    function uploadImage($request, &$params = [], $fieldName = 'image')
    {
        $image = $request->file('file');
        if ($request->hasFile('file') && $image->isValid()) {
            $response = $image->store('public/uploads');
            $response = explode('public/uploads/', $response);
            $path = $response[1];

            $params[$fieldName] = $path;

            return $path;
        }

        return null;
    }
}

if (! function_exists('uploadImages')) {

    /**
     * Upload images to store folder
     *
     * @param Request $request
     * @param array $params
     * @param string $fieldName
     *
     * @return array image path
     */
    function uploadImages($request)
    {
        $paths = [];
        if ($request->hasFile('file')) {
            $files = $request->file('file');
            if (is_array($files)) {
                foreach ($files as $image) {
                    if ($image->isValid()) {
                        $response = $image->store('public/uploads');
                        $response = explode('public/uploads/', $response);
                        $paths[] = $response[1];
                    }
                }
            }
        }
        return $paths;
    }
}

if (! function_exists('castNull')) {

    /**
     * Convert string null to NULL
     *
     * @param string $param
     *
     * @return mixed|NULL
     */
    function castNull($param)
    {
        return $param === 'null' ? null : $param;
    }
}

if (! function_exists('randomText')) {

    /**
     * Generate random string
     *
     * @param string $param
     *
     * @return mixed|NULL
     */
    function randomText($size)
    {
        return Str::upper(Str::random($size));
    }
}

if (! function_exists('randomNumber')) {

    /**
     * Generate random number
     *
     * @param int $min
     * @param int $max
     *
     * @return int|NULL
     */
    function randomNumber(int $min, int $max)
    {
        return mt_rand($min, $max);
    }
}
