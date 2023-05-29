<?php


namespace Nextend\Framework\Misc\Base64;

class Encoder {

    public static function encode($data) {

        if (function_exists('base64_encode')) {
            return base64_encode($data);
        }

        return self::encodeShim($data);
    }

    private static function encodeShim($data) {
        $b64     = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
        $o1      = $o2 = $o3 = $h1 = $h2 = $h3 = $h4 = $bits = $i = 0;
        $ac      = 0;
        $enc     = '';
        $tmp_arr = array();
        if (!$data) {
            return $data;
        }
        do {
            // pack three octets into four hexets
            $o1   = self::charCodeAt($data, $i++);
            $o2   = self::charCodeAt($data, $i++);
            $o3   = self::charCodeAt($data, $i++);
            $bits = $o1 << 16 | $o2 << 8 | $o3;
            $h1   = $bits >> 18 & 0x3f;
            $h2   = $bits >> 12 & 0x3f;
            $h3   = $bits >> 6 & 0x3f;
            $h4   = $bits & 0x3f;
            // use hexets to index into b64, and append result to encoded string
            $tmp_arr[$ac++] = self::charAt($b64, $h1) . self::charAt($b64, $h2) . self::charAt($b64, $h3) . self::charAt($b64, $h4);
        } while ($i < strlen($data));
        $enc = implode('', $tmp_arr);
        $r   = (strlen($data) % 3);

        return ($r ? substr($enc, 0, ($r - 3)) . substr('===', $r) : $enc);
    }

    private static function charCodeAt($data, $char) {
        return ord(substr($data, $char, 1));
    }

    private static function charAt($data, $char) {
        return substr($data, $char, 1);
    }
}