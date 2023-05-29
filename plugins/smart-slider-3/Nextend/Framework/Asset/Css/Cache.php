<?php

namespace Nextend\Framework\Asset\Css;

use Nextend\Framework\Asset\AbstractCache;
use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Url\Url;

class Cache extends AbstractCache {

    public $outputFileType = "css";

    private $baseUrl = '';

    private $basePath = '';

    public function getAssetFileFolder() {
        return Filesystem::getWebCachePath() . DIRECTORY_SEPARATOR . $this->group . DIRECTORY_SEPARATOR;
    }

    protected function parseFile($cache, $content, $originalFilePath) {

        $this->basePath = dirname($originalFilePath);
        $this->baseUrl  = Filesystem::pathToAbsoluteURL($this->basePath);

        return preg_replace_callback('#url\([\'"]?([^"\'\)]+)[\'"]?\)#', array(
            $this,
            'makeAbsoluteUrl'
        ), $content);
    }

    private function makeAbsoluteUrl($matches) {
        if (substr($matches[1], 0, 5) == 'data:') return $matches[0];
        if (substr($matches[1], 0, 4) == 'http') return $matches[0];
        if (substr($matches[1], 0, 2) == '//') return $matches[0];

        $exploded = explode('?', $matches[1]);

        $realPath = realpath($this->basePath . '/' . $exploded[0]);
        if ($realPath === false) {
            return 'url(' . str_replace(array(
                    'http://',
                    'https://'
                ), '//', $this->baseUrl) . '/' . $matches[1] . ')';
        }

        $realPath = Filesystem::convertToRealDirectorySeparator($realPath);

        return 'url(' . Url::pathToUri($realPath, false) . (isset($exploded[1]) ? '?' . $exploded[1] : '') . ')';
    }
}