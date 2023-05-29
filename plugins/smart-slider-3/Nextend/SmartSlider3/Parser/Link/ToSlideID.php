<?php


namespace Nextend\SmartSlider3\Parser\Link;


use Nextend\Framework\Parser\Link\ParserInterface;

class ToSlideID implements ParserInterface {

    public function parse($argument, &$attributes) {

        preg_match('/([0-9]+)(,([0-1]))?/', $argument, $matches);
        if (!isset($matches[3])) {
            $attributes['role'] = 'button';

            $attributes['onclick'] = "n2ss.applyActionWithClick(event, 'slideToID', " . intval($matches[1]) . ");";

        } else {
            $attributes['onclick'] = "n2ss.applyActionWithClick(event, 'slideToID', " . intval($matches[1]) . ", " . intval($matches[3]) . ");";
        }

        return '#';
    }
}