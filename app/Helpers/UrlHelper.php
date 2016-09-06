<?php

namespace App\Helpers;

class UrlHelper
{
    public static function isFileAvailable($url)
    {
        if (!$url || empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_exec($curl);

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if (($httpCode >=200) && ($httpCode < 300)) {
            return true;
        }
        else {
            return false;
        }
    }
}
