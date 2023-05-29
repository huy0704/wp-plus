<?php

namespace Nextend\SmartSlider3\Slider\SliderType;

class SVGFlip {

    private static $viewBoxX;
    private static $viewBoxY;

    /**
     * @param string $svg
     * @param bool   $x
     * @param bool   $y
     *
     * @return string
     */
    public static function mirror($svg, $x, $y) {
        /* @var callable $callable */

        if ($x && $y) {
            $callable = array(
                self::class,
                'xy'
            );
        } else if ($x) {
            $callable = array(
                self::class,
                'x'
            );
        } else if ($y) {
            $callable = array(
                self::class,
                'y'
            );
        } else {
            return $svg;
        }

        preg_match('/(viewBox)=[\'"](.*?)[\'"]/i', $svg, $viewBoxResult);
        $viewBox        = explode(' ', end($viewBoxResult));
        self::$viewBoxX = $viewBox[2];
        self::$viewBoxY = $viewBox[3];

        $pattern = '/d=[\'"](.*?)[\'"]/i';

        return preg_replace_callback($pattern, $callable, $svg);
    }

    private static function x($matches) {
        $path = $matches[1];

        $path    = substr($path, 0, -1);
        $values  = explode(' ', $path);
        $newPath = '';
        for ($i = 0; $i < count($values); $i++) {
            $pathCommand = substr($values[$i], 0, 1);
            $pathPart    = substr($values[$i], 1);
            $points      = explode(',', $pathPart);
            if ($pathCommand === 'A') {
                $points[2] = -$points[2];
                $points[4] = ($points[4]) ? 1 : 0;
                $points[5] = self::$viewBoxX - $points[5];
            } else if ($pathCommand == 'a') {
                $points[2] = -$points[2];
                $points[4] = ($points[4]) ? 1 : 0;
                $points[5] = -$points[5];
            } else {
                for ($j = 0; $j < count($points); $j = $j + 2) {
                    switch ($pathCommand) {
                        case 'l':
                        case 'm':
                        case 'h':
                        case 'c':
                        case 's':
                        case 'q':
                        case 't':
                            $points[$j] = -$points[$j];
                            break;
                        case 'L':
                        case 'M':
                        case 'H':
                        case 'C':
                        case 'S':
                        case 'Q':
                        case 'T':
                            $points[$j] = self::$viewBoxX - $points[$j];
                            break;
                    }
                }
            }
            $newPath .= $pathCommand . implode(',', $points);
        }

        return 'd="' . $newPath . 'z"';
    }

    private static function y($matches) {
        $path = $matches[1];

        $path    = substr($path, 0, -1);
        $values  = explode(' ', $path);
        $newPath = '';
        for ($i = 0; $i < count($values); $i++) {
            $pathCommand = substr($values[$i], 0, 1);
            $pathPart    = substr($values[$i], 1);
            $points      = explode(',', $pathPart);
            if ($pathCommand === 'A') {
                $points[2] = -$points[2];
                $points[4] = ($points[4]) ? 1 : 0;
                $points[6] = self::$viewBoxY - $points[6];
            } else if ($pathCommand === 'a') {
                $points[2] = -$points[2];
                $points[4] = ($points[4]) ? 1 : 0;
                $points[6] = -$points[6];
            } else {
                for ($j = 0; $j < count($points); $j = $j + 2) {
                    switch ($pathCommand) {
                        case 'v':
                            $points[$j] = -$points[$j];
                            break;
                        case 'V':
                            $points[$j] = self::$viewBoxY - $points[$j];
                            break;
                        case 'l':
                        case 'm':
                        case 'c':
                        case 's':
                        case 'q':
                        case 't':
                            $points[$j + 1] = -$points[$j + 1];
                            break;
                        case 'L':
                        case 'M':
                        case 'C':
                        case 'S':
                        case 'Q':
                        case 'T':
                            $points[$j + 1] = self::$viewBoxY - $points[$j + 1];
                            break;
                    }
                }
            }
            $newPath .= $pathCommand . implode(',', $points);
        }

        return 'd="' . $newPath . 'z"';
    }

    private static function xy($matches) {
        $path = $matches[1];

        $path    = substr($path, 0, -1);
        $values  = explode(' ', $path);
        $newPath = '';
        for ($i = 0; $i < count($values); $i++) {
            $pathCommand = substr($values[$i], 0, 1);
            $pathPart    = substr($values[$i], 1);
            $points      = explode(',', $pathPart);
            if ($pathCommand === 'A') {
                $points[5] = self::$viewBoxX - $points[5];
                $points[6] = self::$viewBoxY - $points[6];
            } else if ($pathCommand == 'a') {
                $points[5] = -$points[5];
                $points[6] = -$points[6];
            } else {
                for ($j = 0; $j < count($points); $j = $j + 2) {
                    switch ($pathCommand) {
                        case 'h':
                        case 'v':
                            $points[$j] = -$points[$j];
                            break;
                        case 'H':
                            $points[$j] = self::$viewBoxX - $points[$j];
                            break;
                        case 'V':
                            $points[$j] = self::$viewBoxY - $points[$j];
                            break;
                        case 'l':
                        case 'm':
                        case 'c':
                        case 's':
                        case 'q':
                        case 't':
                            $points[$j]     = -$points[$j];
                            $points[$j + 1] = -$points[$j + 1];
                            break;
                        case 'L':
                        case 'M':
                        case 'C':
                        case 'S':
                        case 'Q':
                        case 'T':
                            $points[$j]     = self::$viewBoxX - $points[$j];
                            $points[$j + 1] = self::$viewBoxY - $points[$j + 1];
                            break;
                    }
                }
            }
            $newPath .= $pathCommand . implode(',', $points);
        }

        return 'd="' . $newPath . 'z"';
    }
}