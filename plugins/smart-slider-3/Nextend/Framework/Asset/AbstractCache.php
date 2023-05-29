<?php

namespace Nextend\Framework\Asset;

use Nextend\Framework\Cache\Manifest;
use Nextend\Framework\Filesystem\Filesystem;

abstract class AbstractCache {

    public $outputFileType;

    protected $group, $files, $codes;

    public function getAssetFile($group, &$files = array(), &$codes = array()) {
        $this->group = $group;
        $this->files = $files;
        $this->codes = $codes;

        $cache = new Manifest($group, true, true);
        $hash  = $this->getHash();

        return $cache->makeCache($group . "." . $this->outputFileType, $hash, array(
            $this,
            'getCachedContent'
        ));
    }

    protected function getHash() {
        $hash = '';
        foreach ($this->files as $file) {
            $hash .= $this->makeFileHash($file);
        }
        foreach ($this->codes as $code) {
            $hash .= $code;
        }

        return md5($hash);
    }

    protected function getCacheFileName() {
        $hash = '';
        foreach ($this->files as $file) {
            $hash .= $this->makeFileHash($file);
        }
        foreach ($this->codes as $code) {
            $hash .= $code;
        }

        return md5($hash) . "." . $this->outputFileType;
    }

    /**
     * @param Manifest $cache
     *
     * @return string
     */
    public function getCachedContent($cache) {
        $fileContents = '';
        foreach ($this->files as $file) {
            $fileContents .= $this->parseFile($cache, Filesystem::readFile($file), $file) . "\n";
        }

        foreach ($this->codes as $code) {
            $fileContents .= $code . "\n";
        }

        return $fileContents;
    }

    protected function makeFileHash($file) {
        return $file . filemtime($file);
    }

    /**
     * @param Manifest        $cache
     * @param                 $content
     * @param                 $originalFilePath
     *
     * @return mixed
     */
    protected function parseFile($cache, $content, $originalFilePath) {
        return $content;
    }

}