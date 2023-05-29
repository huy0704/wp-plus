<?php

namespace Nextend\Framework\Misc\Base64;

class Decoder {

    public static function decode($data) {

        if (function_exists('base64_decode')) {
            return base64_decode($data);
        }

        return self::decodeShim($data);
    }

    private static function decodeShim($input) {
        $keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
        $i      = 0;
        $output = "";

        // remove all characters that are not A-Z, a-z, 0-9, +, /, or =
        $filter = $input;
        $input  = preg_replace("[^A-Za-z0-9\+\/\=]", "", $input);
        if ($filter != $input) {
            return false;
        }

        do {
            $enc1   = strpos($keyStr, substr($input, $i++, 1));
            $enc2   = strpos($keyStr, substr($input, $i++, 1));
            $enc3   = strpos($keyStr, substr($input, $i++, 1));
            $enc4   = strpos($keyStr, substr($input, $i++, 1));
            $chr1   = ($enc1 << 2) | ($enc2 >> 4);
            $chr2   = (($enc2 & 15) << 4) | ($enc3 >> 2);
            $chr3   = (($enc3 & 3) << 6) | $enc4;
            $output = $output . chr((int)$chr1);
            if ($enc3 != 64) {
                $output = $output . chr((int)$chr2);
            }
            if ($enc4 != 64) {
                $output = $output . chr((int)$chr3);
            }
        } while ($i < strlen($input));

        return urldecode($output);
    }
}