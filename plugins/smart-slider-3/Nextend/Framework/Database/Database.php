<?php

namespace Nextend\Framework\Database;

use Nextend\Framework\Database\Joomla\JoomlaConnector;
use Nextend\Framework\Database\Joomla\JoomlaConnectorTable;
use Nextend\Framework\Database\WordPress\WordPressConnector;
use Nextend\Framework\Database\WordPress\WordPressConnectorTable;
use Nextend\Framework\Pattern\SingletonTrait;

class Database {

    use SingletonTrait;

    /**
     * @var AbstractPlatformConnector
     */
    private static $platformConnector;

    protected function init() {
        self::$platformConnector = new WordPressConnector();
    }

    /**
     * @param $tableName
     *
     * @return AbstractPlatformConnectorTable
     */
    public static function getTable($tableName) {
        return new WordPressConnectorTable($tableName);
    }

    public static function getPrefix() {
        return self::$platformConnector->getPrefix();
    }

    public static function parsePrefix($query) {
        return self::$platformConnector->parsePrefix($query);
    }

    public static function insertId() {

        return self::$platformConnector->insertId();
    }

    public static function query($query, $attributes = false) {

        return self::$platformConnector->query($query, $attributes);
    }

    /**
     * Return with one row by query string
     *
     * @param string     $query
     * @param array|bool $attributes for parameter binding
     *
     * @return mixed
     */
    public static function queryRow($query, $attributes = false) {

        return self::$platformConnector->queryRow($query, $attributes);
    }

    public static function queryAll($query, $attributes = false, $type = "assoc", $key = null) {

        return self::$platformConnector->queryAll($query, $attributes, $type, $key);
    }


    /**
     * @param string $text
     * @param bool   $escape
     *
     * @return string
     */
    public static function quote($text, $escape = true) {

        return self::$platformConnector->quote($text, $escape);
    }

    /**
     * @param string $name
     * @param null   $as
     *
     * @return mixed
     */
    public static function quoteName($name, $as = null) {

        return self::$platformConnector->quoteName($name, $as);
    }

    /**
     * @return string
     */
    public static function getCharsetCollate() {

        return self::$platformConnector->getCharsetCollate();
    }
}

Database::getInstance();