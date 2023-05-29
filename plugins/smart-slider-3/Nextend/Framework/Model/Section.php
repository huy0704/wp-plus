<?php

namespace Nextend\Framework\Model;

use Nextend\Framework\Database\AbstractPlatformConnectorTable;
use Nextend\Framework\Database\Database;
use Nextend\Framework\Plugin;

class Section {

    /** @var AbstractPlatformConnectorTable */
    public static $tableSectionStorage;

    public function __construct() {

        self::$tableSectionStorage = Database::getTable("nextend2_section_storage");
    }

    public static function get($application, $section, $referenceKey = null) {
        $attributes = array(
            "application" => $application,
            "section"     => $section
        );

        if ($referenceKey !== null) {
            $attributes['referencekey'] = $referenceKey;
        }

        return self::$tableSectionStorage->findByAttributes($attributes);
    }

    public static function getById($id, $section = null) {
        static $cache = array();
        if ($id === 0) {
            return null;
        }
        if (!isset($cache[$section])) {
            $cache[$section] = array();
        } else if (isset($cache[$section][$id])) {
            return $cache[$section][$id];
        }

        $cache[$section][$id] = null;
        if ($section) {
            Plugin::doAction($section, array(
                $id,
                &$cache[$section][$id]
            ));
            if ($cache[$section][$id]) {
                return $cache[$section][$id];
            }
        }

        $cache[$section][$id] = self::$tableSectionStorage->findByAttributes(array(
            "id" => $id
        ));
        if ($section && $cache[$section][$id]['section'] != $section) {
            $cache[$section][$id] = null;

            return $cache[$section][$id];
        }

        return $cache[$section][$id];
    }

    public static function getAll($application, $section, $referenceKey = null) {
        $attributes = array(
            "application" => $application,
            "section"     => $section
        );

        if ($referenceKey !== null) {
            $attributes['referencekey'] = $referenceKey;
        }

        $rows = self::$tableSectionStorage->findAllByAttributes($attributes, array(
            "id",
            "referencekey",
            "value",
            "isSystem",
            "editable"
        ));

        Plugin::doAction($application . $section, array(
            $referenceKey,
            &$rows
        ));

        return $rows;
    }

    public static function add($application, $section, $referenceKey, $value, $isSystem = 0, $editable = 1) {
        $row = array(
            "application"  => $application,
            "section"      => $section,
            "referencekey" => '',
            "value"        => $value,
            "isSystem"     => $isSystem,
            "editable"     => $editable
        );

        if ($referenceKey !== null) {
            $row["referencekey"] = $referenceKey;
        }

        self::$tableSectionStorage->insert($row);

        return self::$tableSectionStorage->insertId();
    }


    public static function set($application, $section, $referenceKey, $value, $isSystem = 0, $editable = 1) {

        $result = self::getAll($application, $section, $referenceKey);

        if (empty($result)) {
            return self::add($application, $section, $referenceKey, $value, $isSystem, $editable);
        } else {
            $attributes = array(
                "application" => $application,
                "section"     => $section
            );

            if ($referenceKey !== null) {
                $attributes['referencekey'] = $referenceKey;
            }
            self::$tableSectionStorage->update(array('value' => $value), $attributes);

            return true;
        }
    }

    public static function setById($id, $value) {

        $result = self::getById($id);

        if ($result !== null && $result['editable']) {
            self::$tableSectionStorage->update(array('value' => $value), array(
                "id" => $id
            ));

            return true;
        }

        return false;
    }

    public static function delete($application, $section, $referenceKey = null) {

        $attributes = array(
            "application" => $application,
            "section"     => $section,
            "isSystem"    => 0
        );

        if ($referenceKey !== null) {
            $attributes['referencekey'] = $referenceKey;
        }

        self::$tableSectionStorage->deleteByAttributes($attributes);

        return true;
    }

    public static function deleteById($id) {

        self::$tableSectionStorage->deleteByAttributes(array(
            "id"       => $id,
            "isSystem" => 0
        ));

        return true;
    }
}

new Section();