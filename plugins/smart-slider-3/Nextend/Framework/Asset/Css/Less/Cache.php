<?php

namespace Nextend\Framework\Asset\Css\Less;

use Exception;
use Nextend\Framework\Cache\Manifest;

class Cache extends \Nextend\Framework\Asset\Css\Cache {


    public $outputFileType = "less.css";

    public function getAssetFile($group, &$files = array(), &$codes = array()) {
        $this->group = $group;
        $this->files = $files;
        $this->codes = $codes;

        $cache = new Manifest($group, false, true);
        $hash  = $this->getHash();

        return $cache->makeCache($group . "." . $this->outputFileType, $hash, array(
            $this,
            'getCachedContent'
        ));
    }

    /**
     * @param Manifest $cache
     *
     * @return string
     * @throws Exception
     */
    public function getCachedContent($cache) {

        $fileContents = '';

        foreach ($this->files as $parameters) {
            $compiler = new LessCompiler();

            if (!empty($parameters['importDir'])) {
                $compiler->addImportDir($parameters['importDir']);
            }

            $compiler->setVariables($parameters['context']);
            $fileContents .= $compiler->compileFile($parameters['file']);
        }

        return $fileContents;
    }

    protected function makeFileHash($parameters) {
        return json_encode($parameters) . filemtime($parameters['file']);
    }

    protected function parseFile($cache, $content, $lessParameters) {

        return parent::parseFile($cache, $content, $lessParameters['file']);
    }
}