<?php


namespace Nextend\Framework\Font;


use Nextend\Framework\Parser\Color;
use Nextend\Framework\Parser\Common;
use Nextend\Framework\Plugin;
use Nextend\Framework\Sanitize;

class FontStyle {

    public static $fontSize = false;

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

    /**
     * @param $v
     *
     * @return string
     */
    public function parseColor($v) {
        $hex = Color::hex82hex($v);
        if ($hex[1] == 'ff') {
            return 'color: #' . $hex[0] . ';';
        }

        $rgba = Color::hex2rgba($v);

        return 'color: RGBA(' . $rgba[0] . ',' . $rgba[1] . ',' . $rgba[2] . ',' . round($rgba[3] / 127, 2) . ');';

    }

    /**
     * @param $v
     *
     * @return string
     */
    public function parseSize($v) {
        if (self::$fontSize) {
            $fontSize = Common::parse($v);
            if ($fontSize[1] == 'px') {
                return 'font-size:' . ($fontSize[0] / self::$fontSize * 100) . '%;';
            }
        }

        return 'font-size:' . Sanitize::esc_css_value(Common::parse($v, '')) . ';';
    }

    /**
     * @param $v
     *
     * @return string
     */
    public function parseTshadow($v) {
        $v    = Common::parse($v);
        $rgba = Color::hex2rgba($v[3]);
        if ($v[0] == 0 && $v[1] == 0 && $v[2] == 0) return 'text-shadow: none;';

        return 'text-shadow: ' . Sanitize::esc_css_value($v[0]) . 'px ' . Sanitize::esc_css_value($v[1]) . 'px ' . Sanitize::esc_css_value($v[2]) . 'px RGBA(' . $rgba[0] . ',' . $rgba[1] . ',' . $rgba[2] . ',' . round($rgba[3] / 127, 2) . ');';
    }

    /**
     * @param $v
     *
     * @return string
     */
    public function parseAfont($v) {
        return 'font-family: ' . $this->loadFont(Sanitize::esc_css_value($v)) . ';';
    }

    /**
     * @param $v
     *
     * @return string
     */
    public function parseLineheight($v) {
        if ($v == '') return '';

        return 'line-height: ' . Sanitize::esc_css_value($v) . ';';
    }

    /**
     * @param $v
     *
     * @return string
     */
    public function parseBold($v) {
        return $this->parseWeight($v);
    }

    public function parseWeight($v) {
        if ($v == '1') return 'font-weight: bold;';
        if ($v > 1) return 'font-weight: ' . intval($v) . ';';

        return 'font-weight: normal;';
    }

    /**
     * @param $v
     *
     * @return string
     */
    public function parseItalic($v) {
        if ($v == '1') return 'font-style: italic;';

        return 'font-style: normal;';
    }

    /**
     * @param $v
     *
     * @return string
     */
    public function parseUnderline($v) {
        if ($v == '1') return 'text-decoration: underline;';

        return 'text-decoration: none;';
    }

    /**
     * @param $v
     *
     * @return string
     */
    public function parseAlign($v) {
        return 'text-align: ' . Sanitize::esc_css_value($v) . ';';
    }

    public function parseLetterSpacing($v) {
        return 'letter-spacing: ' . Sanitize::esc_css_value($v) . ';';
    }

    public function parseWordSpacing($v) {
        return 'word-spacing: ' . Sanitize::esc_css_value($v) . ';';
    }

    public function parseTextTransform($v) {
        return 'text-transform: ' . Sanitize::esc_css_value($v) . ';';
    }

    public function parseExtra($v) {

        return Sanitize::esc_css_string($v);
    }

    /**
     * @param $families
     *
     * @return mixed
     */
    public function loadFont($families) {
        $families = explode(',', $families);
        for ($i = 0; $i < count($families); $i++) {
            if ($families[$i] != "inherit") {
                $families[$i] = $this->getFamily(trim(trim($families[$i]), '\'"'));
            }
        }

        return implode(',', $families);
    }

    private function getFamily($family) {
        return "'" . Plugin::applyFilters('fontFamily', $family) . "'";
    }
}