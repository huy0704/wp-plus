<?php

namespace Nextend\Framework\Translation;

abstract class AbstractTranslation {

    public function _($text) {
        return $text;
    }

    public function getLocale() {
        return 'en_US';
    }
}