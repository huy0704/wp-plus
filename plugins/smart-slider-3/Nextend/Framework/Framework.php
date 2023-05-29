<?php


namespace Nextend\Framework;


use Nextend\Framework\Font\FontStorage;
use Nextend\Framework\Localization\Localization;
use Nextend\Framework\Pattern\SingletonTrait;
use Nextend\Framework\Style\StyleStorage;

class Framework {

    use SingletonTrait;

    protected function init() {

        Localization::getInstance();

        FontStorage::getInstance();
        StyleStorage::getInstance();
    }
}