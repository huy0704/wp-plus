<?php

namespace Nextend\Framework\Request;

use Nextend\Framework\Request\Parser\AbstractRequestParser;
use Nextend\Framework\Request\Parser\JoomlaRequestParser;
use Nextend\Framework\Request\Parser\WordPressRequestParser;

class Storage {

    public $originalStorage;
    public $returnOriginal;
    public $storage;

    /**
     * @var AbstractRequestParser
     */
    public $parserInstance;

    public function __construct($data, $returnOriginal = false) {
        $this->parserInstance = new WordPressRequestParser();


        $this->originalStorage = $data;
        $this->returnOriginal  = $returnOriginal;
        $this->storage         = array();
    }

    public function set($var, $val) {
        $this->storage[$var] = $val;
    }

    protected function get($var, $default = false) {
        if (!$this->returnOriginal) {
            if (isset($this->storage[$var])) {
                return $this->storage[$var];
            } else if (isset($this->originalStorage[$var])) {

                $this->storage[$var] = $this->parserInstance->parseData($this->originalStorage[$var]);

                return $this->storage[$var];
            }
        } else if (isset($this->originalStorage[$var])) {
            return $this->originalStorage[$var];
        }

        return $default;
    }

    public function getVar($var, $default = null) {
        return $this->get($var, $default);
    }

    public function getInt($var, $default = 0) {
        return intval($this->get($var, $default));
    }

    public function getCmd($var, $default = '') {
        return preg_replace("/[^\w_]/", "", $this->get($var, $default));
    }

    public function exists($var) {

        if (isset($this->storage[$var])) {
            return true;
        } else if (isset($this->originalStorage[$var])) {
            return true;
        }

        return false;
    }
}