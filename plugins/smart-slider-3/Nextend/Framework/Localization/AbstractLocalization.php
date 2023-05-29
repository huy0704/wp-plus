<?php

namespace Nextend\Framework\Localization;

abstract class AbstractLocalization {

    public function getLocale() {
        return 'en_US';
    }

    abstract public function createMo();

    abstract public function createNOOP_Translations();
}