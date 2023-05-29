<?php

namespace Nextend\Framework\Misc;

use Nextend\Framework\Misc\Base64\Decoder;
use Nextend\Framework\Misc\Base64\Encoder;

class Base64 {

    /**
     * @param $data
     *
     * @return string
     */
    public static function decode($data) {
        return Decoder::decode($data);
    }

    public static function encode($data) {
        return Encoder::encode($data);
    }
}