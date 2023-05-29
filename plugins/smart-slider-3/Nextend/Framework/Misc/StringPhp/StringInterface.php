<?php


namespace Nextend\Framework\Misc\StringPhp;


interface StringInterface {

    public function strpos($haystack, $needle, $offset = 0);

    public function substr($string, $start, $length = null);

    public function strlen($string);
}