<?php

namespace Nextend\Framework\Misc\StringPhp;

class MultiByte implements StringInterface {

    public function strpos($haystack, $needle, $offset = 0) {
        return mb_strpos($haystack, $needle, $offset);
    }

    public function substr($string, $start, $length = null) {
        return mb_substr($string, $start, $length);
    }

    public function strlen($string) {
        return mb_strlen($string);
    }
}