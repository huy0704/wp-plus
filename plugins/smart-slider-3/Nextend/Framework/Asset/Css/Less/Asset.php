<?php

namespace Nextend\Framework\Asset\Css\Less;

use Nextend\Framework\Asset\AbstractAsset;

class Asset extends AbstractAsset {

    public function __construct() {
        $this->cache = new Cache();
    }

    protected function uniqueFiles() {
        $this->initGroups();
    }

    public function getFiles() {
        $this->uniqueFiles();

        $files = array();
        foreach ($this->groups as $group) {
            $files[$group] = $this->cache->getAssetFile($group, $this->files[$group], $this->codes[$group]);
        }

        return $files;
    }
}