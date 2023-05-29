<?php

namespace Nextend\Framework\Router\WordPress;

use Nextend\Framework\Router\Base;

class WordPressRouter extends Base\PlatformRouter {

    private $originalBaseUrl = '';

    public function setMultiSite() {
        if (is_multisite()) {
            $this->originalBaseUrl = $this->router->getBaseUrl();
            $this->router->setBaseUrl($this->router->getNetworkUrl());
        }
    }

    public function unSetMultiSite() {
        if (is_multisite()) {
            $this->router->setBaseUrl($this->originalBaseUrl);
        }
    }
}