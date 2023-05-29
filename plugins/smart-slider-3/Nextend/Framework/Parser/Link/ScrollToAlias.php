<?php

namespace Nextend\Framework\Parser\Link;

use Nextend\Framework\Parser\Link;

class ScrollToAlias implements ParserInterface {

    public function parse($argument, &$attributes) {

        return Link::getParser('ScrollTo')
                   ->parse('[data-alias=\"' . $argument . '\"]', $attributes);
    }
}