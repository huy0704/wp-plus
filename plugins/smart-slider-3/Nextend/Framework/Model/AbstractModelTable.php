<?php

namespace Nextend\Framework\Model;


use Nextend\Framework\Database\AbstractPlatformConnectorTable;

abstract class AbstractModelTable extends AbstractModel {

    /**
     * @var AbstractPlatformConnectorTable
     */
    protected $table;

    protected function init() {

        $this->table = $this->createConnectorTable();
    }

    /**
     * @return AbstractPlatformConnectorTable
     */
    protected abstract function createConnectorTable();

    public function getTableName() {
        return $this->table->getTableName();
    }
}