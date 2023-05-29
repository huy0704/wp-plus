<?php


namespace Nextend\Framework\Style;


use Nextend\Framework\Parser\Color;
use Nextend\Framework\Sanitize;

class Style {

    /**
     * @param string $tab
     *
     * @return string
     */
    public function style($tab) {
        $style = '';
        $extra = '';
        if (isset($tab['extra'])) {
            $extra = $tab['extra'];
            unset($tab['extra']);
        }
        foreach ($tab as $k => $v) {
            $style .= $this->parse($k, $v);
        }
        $style .= $this->parse('extra', $extra);

        return $style;
    }

    /**
     * @param $property
     * @param $value
     *
     * @return mixed
     */
    public function parse($property, $value) {
        $fn = 'parse' . $property;

        return $this->$fn($value);
    }

    public function parseBackgroundColor($v) {
        $hex = Color::hex82hex($v);
        if ($hex[1] == 'ff') {
            return 'background: #' . $hex[0] . ';';
        }

        $rgba = Color::hex2rgba($v);

        return 'background: RGBA(' . $rgba[0] . ',' . $rgba[1] . ',' . $rgba[2] . ',' . round($rgba[3] / 127, 2) . ');';
    }

    public function parseOpacity($v) {
        return 'opacity:' . (intval($v) / 100) . ';';
    }

    public function parsePadding($v) {
        $padding   = array_map(array(
            Sanitize::class,
            'esc_css_value'
        ), explode('|*|', $v));
        $unit      = array_pop($padding);
        $padding[] = '';

        return 'padding:' . implode($unit . ' ', $padding) . ';';
    }

    public function parseBoxShadow($v) {
        $boxShadow = array_map(array(
            Sanitize::class,
            'esc_css_value'
        ), explode('|*|', $v));

        if ($boxShadow[0] == '0' && $boxShadow[1] == '0' && $boxShadow[2] == '0' && $boxShadow[3] == '0') {
            return 'box-shadow: none;';
        } else {
            $rgba = Color::hex2rgba($boxShadow[4]);

            return 'box-shadow: ' . $boxShadow[0] . 'px ' . $boxShadow[1] . 'px ' . $boxShadow[2] . 'px ' . $boxShadow[3] . 'px RGBA(' . $rgba[0] . ',' . $rgba[1] . ',' . $rgba[2] . ',' . round($rgba[3] / 127, 2) . ');';
        }
    }

    public function parseBorder($v) {

        $border = array_map(array(
            Sanitize::class,
            'esc_css_value'
        ), explode('|*|', $v));
        $rgba   = Color::hex2rgba($border[2]);

        return 'border: ' . $border[0] . 'px ' . $border[1] . ' RGBA(' . $rgba[0] . ',' . $rgba[1] . ',' . $rgba[2] . ',' . round($rgba[3] / 127, 2) . ');';
    }

    public function parseBorderRadius($v) {
        return 'border-radius:' . Sanitize::esc_css_value($v) . 'px;';
    }

    public function parseExtra($v) {

        return Sanitize::esc_css_string($v);
    }
}