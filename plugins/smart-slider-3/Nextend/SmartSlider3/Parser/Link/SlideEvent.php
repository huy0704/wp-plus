<?php


namespace Nextend\SmartSlider3\Parser\Link;


use Nextend\Framework\Parser\Link\ParserInterface;

class SlideEvent implements ParserInterface {

    public function parse($argument, &$attributes) {
        $attributes['role'] = 'button';

        $attributes['onclick'] = "n2ss.trigger(this, '" . $argument . "', event);";

        return '#';
    }
}