<?php

namespace Nextend\Framework\View;

use Nextend\Framework\Platform\Platform;
use Nextend\Framework\Settings;

class Html {

    private static $closeSingleTags = true;

    /**
     * @var boolean whether to render special attributes value. Defaults to true. Can be set to false for HTML5.
     * @since 1.1.13
     */
    private static $renderSpecialAttributesValue = true;

    /**
     * Generates an HTML element.
     *
     * @param string  $tag         the tag name
     * @param array   $htmlOptions the element attributes. The values will be HTML-encoded using
     *                             {@link encodeAttribute()}. If an 'encode' attribute is given and its value is false,
     *                             the rest of the attribute values will NOT be HTML-encoded. Since version 1.1.5,
     *                             attributes whose value is null will not be rendered.
     * @param mixed   $content     the content to be enclosed between open and close element tags. It will not be
     *                             HTML-encoded. If false, it means there is no body content.
     * @param boolean $closeTag    whether to generate the close tag.
     *
     * @return string the generated HTML element tag
     */
    public static function tag($tag, $htmlOptions = array(), $content = "", $closeTag = true) {
        $html = '<' . $tag . self::renderAttributes($htmlOptions);
        if ($content === false) return $closeTag && self::$closeSingleTags ? $html . ' />' : $html . '>'; else
            return $closeTag ? $html . '>' . $content . '</' . $tag . '>' : $html . '>' . $content;
    }

    /**
     * Generates an open HTML element.
     *
     * @param string $tag         the tag name
     * @param array  $htmlOptions the element attributes. The values will be HTML-encoded using
     *                            {@link encodeAttribute()}.
     *                            If an 'encode' attribute is given and its value is false,
     *                            the rest of the attribute values will NOT be HTML-encoded.
     *                            Since version 1.1.5, attributes whose value is null will not be rendered.
     *
     * @return string the generated HTML element tag
     */
    public static function openTag($tag, $htmlOptions = array()) {
        return '<' . $tag . self::renderAttributes($htmlOptions) . '>';
    }

    /**
     * Generates a close HTML element.
     *
     * @param string $tag the tag name
     *
     * @return string the generated HTML element tag
     */
    public static function closeTag($tag) {
        return '</' . $tag . '>';
    }

    /**
     * Generates an image tag.
     *
     * @param string $src         the image URL
     * @param string $alt         the alternative text display
     * @param array  $htmlOptions additional HTML attributes (see {@link tag}).
     *
     * @return string the generated image tag
     */
    public static function image($src, $alt = '', $htmlOptions = array()) {
        $htmlOptions['src'] = $src;
        $htmlOptions['alt'] = $alt;

        return self::tag('img', $htmlOptions, false, false);
    }

    /**
     * Renders the HTML tag attributes.
     * Since version 1.1.5, attributes whose value is null will not be rendered.
     * Special attributes, such as 'checked', 'disabled', 'readonly', will be rendered
     * properly based on their corresponding boolean value.
     *
     * @param array $htmlOptions attributes to be rendered
     *
     * @return string the rendering result
     */
    public static function renderAttributes($htmlOptions = array()) {
        static $specialAttributes = array(
            'autofocus'          => 1,
            'autoplay'           => 1,
            'controls'           => 1,
            'declare'            => 1,
            'default'            => 1,
            'disabled'           => 1,
            'ismap'              => 1,
            'loop'               => 1,
            'muted'              => 1,
            'playsinline'        => 1,
            'webkit-playsinline' => 1,
            'nohref'             => 1,
            'noresize'           => 1,
            'novalidate'         => 1,
            'open'               => 1,
            'reversed'           => 1,
            'scoped'             => 1,
            'seamless'           => 1,
            'selected'           => 1,
            'typemustmatch'      => 1,
            'lazyload'           => 1,
        ), $specialAttributesNoValue = array(
            'defer' => 1,
            'async' => 1
        );

        if (empty($htmlOptions)) {
            return '';
        }

        if (isset($htmlOptions['style']) && empty($htmlOptions['style'])) {
            unset($htmlOptions['style']);
        }

        $html = '';
        if (isset($htmlOptions['encode'])) {
            $raw = !$htmlOptions['encode'];
            unset($htmlOptions['encode']);
        } else
            $raw = false;

        foreach ($htmlOptions as $name => $value) {
            if (isset($specialAttributes[$name])) {
                if ($value) {
                    $html .= ' ' . $name;
                    if (self::$renderSpecialAttributesValue) $html .= '="' . $name . '"';
                }
            } else if (isset($specialAttributesNoValue[$name])) {
                $html .= ' ' . $name;
            } else if ($value !== null) $html .= ' ' . $name . '="' . ($raw ? $value : self::encodeAttribute($value)) . '"';
        }

        return $html;
    }

    /**
     * @param $text
     *
     * @return string
     */
    public static function encode($text) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    /**
     * @param $text
     *
     * @return string
     */
    public static function encodeAttribute($text) {

        /**
         * Do not encode: '
         */
        return htmlspecialchars($text, ENT_COMPAT | ENT_HTML5, 'UTF-8');
    }

    public static function link($name, $url = '#', $htmlOptions = array()) {
        $htmlOptions["href"] = $url;

        $url = self::openTag("a", $htmlOptions);
        if (isset($htmlOptions["encode"]) && $htmlOptions["encode"]) {
            $url .= self::encode($name);
        } else {
            $url .= $name;
        }

        $url .= self::closeTag("a");

        return $url;
    }

    /**
     * Insert stylesheet
     *
     * @param string $script
     * @param bool   $file
     * @param array  $scriptOptions
     *
     * @return string
     */
    public static function style($script, $file = false, $scriptOptions = array()) {
        if ($file) {
            $options = array(
                "rel"  => "stylesheet",
                "type" => "text/css",
                "href" => $script
            );
            $options = array_merge($options, $scriptOptions);

            return self::tag('link', $options, false, false);
        }

        return self::tag("style", $scriptOptions, $script);
    }

    /**
     * Insert script
     *
     * @param string $script
     *
     * @return string
     */
    public static function script($script) {

        return self::tag('script', array(
            'encode' => false
        ), $script);
    }

    public static function scriptFile($script, $attributes = array()) {
        return self::tag('script', array(
                'src' => $script
            ) + self::getScriptAttributes() + $attributes, '');
    }

    private static function getScriptAttributes() {
        static $attributes = null;

        if (Platform::isAdmin()) {
            return array();
        }

        if ($attributes === null) {
            if (class_exists('\\Nextend\\Framework\\Settings', false)) {
                $value       = trim(html_entity_decode(strip_tags(Settings::get('scriptattributes', ''))));
                $_attributes = explode(' ', str_replace(array(
                    '\'',
                    "\""
                ), "", str_replace(array(
                    "onload",
                    "onerror"
                ), "not-allowed", $value)));
                if (!empty($value) && !empty($_attributes)) {
                    foreach ($_attributes as $attr) {
                        if (strpos($attr, '=') !== false) {
                            $atts = explode("=", $attr);
                            if (count($atts) <= 2) {
                                $attributes[$atts[0]] = $atts[1];
                            } else {
                                $attributes[$attr] = $attr;
                            }
                        } else {
                            $attributes[$attr] = $attr;
                        }
                    }
                } else {
                    $attributes = array();
                }
            } else {
                return array();
            }
        }

        return $attributes;
    }

    /**
     * @param array $array1
     * @param array $array2 [optional]
     * @param array $_      [optional]
     *
     * @return array the resulting array.
     * @since 4.0
     * @since 5.0
     */
    public static function mergeAttributes($array1, $array2 = null, $_ = null) {
        $arguments = func_get_args();
        $target    = array_shift($arguments);
        foreach ($arguments as $array) {
            if (isset($array['style'])) {
                if (!isset($target['style'])) $target['style'] = '';
                $target['style'] .= $array['style'];
                unset($array['style']);
            }
            if (isset($array['class'])) {
                if (empty($target['class'])) {
                    $target['class'] = $array['class'];
                } else {
                    $target['class'] .= ' ' . $array['class'];
                }
                unset($array['class']);
            }

            $target = array_merge($target, $array);
        }

        return $target;
    }

    public static function addExcludeLazyLoadAttributes($target = array()) {

        return self::mergeAttributes($target, self::getExcludeLazyLoadAttributes());
    }

    public static function getExcludeLazyLoadAttributes() {
        static $attrs;
        if ($attrs === null) {
            $attrs = array(
                'class'          => 'skip-lazy',
                'data-skip-lazy' => 1
            );

            if (defined('JETPACK__VERSION')) {
                $attrs['class'] .= ' jetpack-lazy-image';
            }

            if (defined('PERFMATTERS_VERSION')) {
                $attrs['class'] .= ' no-lazy';
            }
        }

        return $attrs;
    }
}