<?php


namespace Nextend\Framework\Database\WordPress;

use Nextend\Framework\Database\AbstractPlatformConnector;
use Nextend\Framework\Database\AbstractPlatformConnectorTable;
use wpdb;

class WordPressConnectorTable extends AbstractPlatformConnectorTable {

    /** @var wpdb */
    protected static $db;

    /**
     * @param AbstractPlatformConnector $connector
     * @param wpdb                      $db
     */
    public static function init($connector, $db) {
        self::$connector = $connector;
        self::$db        = $db;
    }

    public function findByPk($primaryKey) {
        $query = self::$db->prepare("SELECT * FROM " . $this->tableName . " WHERE " . self::$connector->quoteName($this->primaryKeyColumn) . " = %s", $primaryKey);

        return self::$connector->checkError(self::$db->get_row($query, ARRAY_A));
    }

    public function findByAttributes(array $attributes, $fields = false, $order = false) {

        return self::$connector->checkError(self::$db->get_row($this->_findByAttributesSQL($attributes, $fields, $order), ARRAY_A));
    }


    public function findAll($order = false) {

        return self::$connector->checkError(self::$db->get_results($this->_findByAttributesSQL(array(), false, $order), ARRAY_A));
    }

    public function findAllByAttributes(array $attributes, $fields = false, $order = false) {

        return self::$connector->checkError(self::$db->get_results($this->_findByAttributesSQL($attributes, $fields, $order), ARRAY_A));
    }

    public function insert(array $attributes) {
        return self::$connector->checkError(self::$db->insert($this->tableName, $attributes));
    }

    public function insertId() {
        return self::$db->insert_id;
    }

    public function update(array $attributes, array $conditions) {

        return self::$connector->checkError(self::$db->update($this->tableName, $attributes, $conditions));
    }

    public function updateByPk($primaryKey, array $attributes) {

        $where                          = array();
        $where[$this->primaryKeyColumn] = $primaryKey;
        self::$connector->checkError(self::$db->update($this->tableName, $attributes, $where));
    }

    public function deleteByPk($primaryKey) {
        $where                          = array();
        $where[$this->primaryKeyColumn] = $primaryKey;
        self::$connector->checkError(self::$db->delete($this->tableName, $where));
    }

    public function deleteByAttributes(array $conditions) {
        self::$connector->checkError(self::$db->delete($this->tableName, $conditions));
    }

    private function _findByAttributesSQL(array $attributes, $fields = array(), $order = false) {

        $args = array('');

        $query = 'SELECT ';
        if (!empty($fields)) {

            $fields = array_map(array(
                self::$connector,
                'quoteName'
            ), $fields);

            $query .= implode(', ', $fields);
        } else {
            $query .= '*';
        }
        $query .= ' FROM ' . $this->tableName;

        $where = array();
        foreach ($attributes as $key => $val) {
            $where[] = self::$connector->quoteName($key) . ' = ' . (is_numeric($val) ? '%d' : '%s');
            $args[]  = $val;
        }
        if (count($where)) {
            $query .= ' WHERE ' . implode(' AND ', $where);
        }

        if ($order) {
            $query .= ' ORDER BY ' . $order;
        }

        if (count($args) > 1) {
            $args[0] = $query;

            return call_user_func_array(array(
                self::$db,
                'prepare'
            ), $args);
        } else {
            return $query;
        }
    }
}