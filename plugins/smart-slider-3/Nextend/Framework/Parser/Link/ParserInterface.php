<?php

namespace Nextend\Framework\Parser\Link;

interface ParserInterface {

    public function parse($argument, &$attributes);
}