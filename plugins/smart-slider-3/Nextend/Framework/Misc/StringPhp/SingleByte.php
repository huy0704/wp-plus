<?php

namespace Nextend\Framework\Misc\StringPhp;

class SingleByte implements StringInterface {

    public function strpos($haystack, $needle, $offset = 0) {
        return strpos($haystack, $needle, $offset);
    }

    public function substr($string, $start, $length = null) {
        return substr($string, $start, $length);
    }

    public function strlen($string) {
        return strlen($string);
    }
}