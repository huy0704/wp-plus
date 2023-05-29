<?php


namespace Nextend\Framework;


class Cast {

    /**
     * @param $number
     *
     * @return string the JavaScript float representation of the string
     */
    public static function floatToString($number) {

        return json_encode(floatval($number));
    }
}