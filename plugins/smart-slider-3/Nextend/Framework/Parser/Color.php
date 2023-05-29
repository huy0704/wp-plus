<?php

namespace Nextend\Framework\Parser;


/**
 *
 * Color values manipulation utilities. Provides methods to convert from and to
 * Hex, RGB, HSV and HSL color representattions.
 *
 * Several color conversion logic are based on pseudo-code from
 * http://www.easyrgb.com/math.php
 *
 * @category Lux
 *
 * @package  Lux_Color
 *
 * @author   Rodrigo Moraes <rodrigo.moraes@gmail.com>
 *
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 *
 * @version  $Id$
 *
 */
class Color {

    public static function colorToRGBA($value) {
        $rgba = self::hex2rgba($value);

        return 'RGBA(' . $rgba[0] . ',' . $rgba[1] . ',' . $rgba[2] . ',' . round($rgba[3] / 127, 2) . ')';
    }

    public static function hex2alpha($value) {
        if (strlen($value) == 6) {
            return 127;
        }

        return intval(hexdec(substr($value, 6, 2)) / 2);
    }

    public static function hex2opacity($value) {
        return self::hex2alpha($value) / 127;
    }

    public static function colorToSVG($value) {
        $rgba = self::hex2rgba($value);

        return array(
            substr($value, 0, 6),
            round($rgba[3] / 127, 2)
        );
    }

    /**
     *
     * Converts hexadecimal colors to RGB.
     *
     * @param string $hex Hexadecimal value. Accepts values with 3 or 6 numbers,
     *                    with or without #, e.g., CCC, #CCC, CCCCCC or #CCCCCC.
     *
     * @return array RGB values: 0 => R, 1 => G, 2 => B
     *
     */
    public static function hex2rgb($hex) {


        // Remove #.
        if (strpos($hex, '#') === 0) {
            $hex = substr($hex, 1);
        }
        if (strlen($hex) == 3) {
            $hex .= $hex;
        }
        if (strlen($hex) != 6) {
            return false;
        }

        // Convert each tuple to decimal.
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return array(
            $r,
            $g,
            $b
        );
    }

    public static function hex2rgba($hex) {


        // Remove #.
        if (strpos($hex, '#') === 0) {
            $hex = substr($hex, 1);
        }
        if (strlen($hex) == 6) {
            $hex .= 'ff';
        }
        if (strlen($hex) != 8) {
            $hex = '00000000';
        }

        // Convert each tuple to decimal.
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $a = intval(hexdec(substr($hex, 6, 2)) / 2);

        return array(
            $r,
            $g,
            $b,
            $a
        );
    }

    public static function hex82hex($hex) {


        // Remove #.
        if (strpos($hex, '#') === 0) {
            $hex = substr($hex, 1);
        }
        if (strlen($hex) == 6) {
            $hex .= 'ff';
        }
        if (strlen($hex) != 8) {
            $hex = '00000000';
        }

        return array(
            substr($hex, 0, 6),
            substr($hex, 6, 2)
        );
    }

}