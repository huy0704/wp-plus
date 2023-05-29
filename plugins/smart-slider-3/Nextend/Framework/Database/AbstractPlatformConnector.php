<?php

namespace Nextend\Framework\Database;

abstract class AbstractPlatformConnector {

    protected $_prefixJoker = '#__';

    protected $_prefix = '';

    public function getPrefix() {
        return $this->_prefix;
    }

    public function parsePrefix($query) {
        return str_replace($this->_prefixJoker, $this->_prefix, $query);
    }

    abstract public function insertId();

    abstract public function query($query, $attributes = false);

    /**
     * Return with one row by query string
     *
     * @param string     $query
     * @param array|bool $attributes for parameter binding
     *
     * @return mixed
     */
    abstract public function queryRow($query, $attributes = false);

    abstract public function queryAll($query, $attributes = false, $type = "assoc", $key = null);


    /**
     * @param string $text
     * @param bool   $escape
     *
     * @return string
     */
    abstract public function quote($text, $escape = true);

    /**
     * @param string $name
     * @param null   $as
     *
     * @return mixed
     */
    abstract public function quoteName($name, $as = null);

    public function checkError($result) {
        return $result;
    }

    /**
     * @return string
     */
    abstract public function getCharsetCollate();
}