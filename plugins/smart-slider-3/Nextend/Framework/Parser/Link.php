<?php

namespace Nextend\Framework\Parser;

use Nextend\Framework\Parser\Link\ParserInterface;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;

class Link {

    /**
     * @var ParserInterface[]
     */
    private static $parsers = array();

    public static $registeredNamespaces = array(
        '\\Nextend\\Framework\\Parser\\Link\\',
        '\\Nextend\\SmartSlider3\\Parser\\Link\\'
    );

    public static function parse($url, &$attributes, $isEditor = false) {
        if ($url == '#' || $isEditor) {
            $attributes['onclick'] = "return false;";
        } else {
            $url = trim($url);
            if (substr($url, 0, 11) === "javascript:") {
                return '#';
            } else {
                preg_match('/^([a-zA-Z]+)\[(.*)]$/', $url, $matches);
                if (!empty($matches)) {
                    $matches[1] = ucfirst($matches[1]);
                    $parser     = self::getParser($matches[1]);
                    if ($parser) {
                        $url = $parser->parse($matches[2], $attributes);
                    }
                } else {
                    $url = ResourceTranslator::toUrl($url);
                }
            }
        }

        return $url;
    }

    public static function getParser($className) {
        if (!isset(self::$parsers[$className])) {

            foreach (self::$registeredNamespaces as $namespace) {
                $class = $namespace . $className;
                if (class_exists($class)) {
                    self::$parsers[$className] = new $class();
                    break;
                }
            }
            if (!isset(self::$parsers[$className])) {
                self::$parsers[$className] = false;
            }
        }

        return self::$parsers[$className];
    }
}