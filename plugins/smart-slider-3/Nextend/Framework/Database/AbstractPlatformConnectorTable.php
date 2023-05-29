<?php


namespace Nextend\Framework\Database;


abstract class AbstractPlatformConnectorTable {

    protected $primaryKeyColumn = "id";

    /** @var AbstractPlatformConnector */
    protected static $connector;

    protected $tableName;

    public function __construct($tableName) {

        $this->tableName = self::$connector->getPrefix() . $tableName;
    }

    public function getTableName() {
        return $this->tableName;
    }

    abstract public function findByPk($primaryKey);

    abstract public function findByAttributes(array $attributes, $fields = false, $order = false);

    abstract public function findAll($order = false);

    /**
     * Return with all row by attributes
     *
     * @param array       $attributes
     * @param bool|array  $fields
     * @param bool|string $order
     *
     * @return mixed
     */
    abstract public function findAllByAttributes(array $attributes, $fields = false, $order = false);

    /**
     * Insert new row
     *
     * @param array $attributes
     *
     * @return mixed|void
     */
    abstract public function insert(array $attributes);

    abstract public function insertId();

    /**
     * Update row(s) by param(s)
     *
     * @param array $attributes
     * @param array $conditions
     *
     * @return mixed
     */
    abstract public function update(array $attributes, array $conditions);

    /**
     * Update one row by primary key with $attributes
     *
     * @param mixed $primaryKey
     * @param array $attributes
     *
     * @return mixed
     */
    abstract public function updateByPk($primaryKey, array $attributes);

    /**
     * Delete one with by primary key
     *
     * @param mixed $primaryKey
     *
     * @return mixed
     */
    abstract public function deleteByPk($primaryKey);

    /**
     * Delete all rows by attributes
     *
     * @param array $conditions
     *
     * @return mixed
     */
    abstract public function deleteByAttributes(array $conditions);
}