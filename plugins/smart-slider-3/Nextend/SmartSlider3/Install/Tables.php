<?php


namespace Nextend\SmartSlider3\Install;


use Nextend\Framework\Database\Database;
use Nextend\Framework\Notification\Notification;

class Tables {

    protected $tables = array(
        'nextend2_image_storage'             => "(
                `id`    INT(11)     NOT NULL AUTO_INCREMENT,
                `hash`  VARCHAR(32) NOT NULL,
                `image` TEXT        NOT NULL,
                `value` MEDIUMTEXT  NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `hash` (`hash`)
            )",
        'nextend2_section_storage'           => "(
                `id`           INT(11)     NOT NULL AUTO_INCREMENT,
                `application`  VARCHAR(20) NOT NULL,
                `section`      VARCHAR(128) NOT NULL,
                `referencekey` VARCHAR(128) DEFAULT '',
                `value`        MEDIUMTEXT  NOT NULL,
                `isSystem`       INT(11)     NOT NULL DEFAULT '0',
                `editable`     INT(11)     NOT NULL DEFAULT '1',
                PRIMARY KEY (`id`),
                KEY `application` (`application`, `section`(50), `referencekey`(50)),
                KEY `application_2` (`application`, `section`(50)),
                INDEX (`isSystem`),
                INDEX (`editable`)
            )
            AUTO_INCREMENT = 10000",
        'nextend2_smartslider3_generators'   => "(
                `id`     INT(11)      NOT NULL AUTO_INCREMENT,
                `group`  VARCHAR(254) NOT NULL,
                `type`   VARCHAR(254) NOT NULL,
                `params` TEXT         NOT NULL,
                PRIMARY KEY (`id`)
            )",
        'nextend2_smartslider3_sliders'      => "(
          `id`     INT(11)      NOT NULL AUTO_INCREMENT,
          `alias`  VARCHAR(255) NULL DEFAULT NULL,
          `title`  VARCHAR(200) NOT NULL,
          `type`   VARCHAR(30)  NOT NULL,
          `params` MEDIUMTEXT   NOT NULL,
          `slider_status` VARCHAR(50) NOT NULL DEFAULT 'published',
          `time`   DATETIME     NOT NULL,
          `thumbnail` VARCHAR( 255 ) NOT NULL,
          `ordering` INT NOT NULL DEFAULT '0',
          INDEX (`slider_status`),
          INDEX (`time`),
          PRIMARY KEY (`id`)
        )",
        'nextend2_smartslider3_sliders_xref' => "(
          `group_id` int(11) NOT NULL,
          `slider_id` int(11) NOT NULL,
          `ordering` int(11) NOT NULL DEFAULT '0',
          PRIMARY KEY (`group_id`,`slider_id`),
          INDEX (`ordering`)
        )",
        'nextend2_smartslider3_slides'       => "(
          `id`           INT(11)      NOT NULL AUTO_INCREMENT,
          `title`        VARCHAR(200) NOT NULL,
          `slider`       INT(11)      NOT NULL,
          `publish_up`   DATETIME     NOT NULL default '1970-01-01 00:00:00',
          `publish_down` DATETIME     NOT NULL default '1970-01-01 00:00:00',
          `published`    TINYINT(1)   NOT NULL,
          `first`        INT(11)      NOT NULL,
          `slide`        LONGTEXT,
          `description`  TEXT         NOT NULL,
          `thumbnail`    VARCHAR(255) NOT NULL,
          `params`       TEXT         NOT NULL,
          `ordering`     INT(11)      NOT NULL,
          `generator_id` INT(11)      NOT NULL,
          PRIMARY KEY (`id`),
          INDEX (`published`),
          INDEX (`publish_up`),
          INDEX (`publish_down`),
          INDEX (`generator_id`),
          KEY `thumbnail` (`thumbnail`(100)),
          INDEX (`ordering`),
          INDEX (`slider`)
        )"
    );


    public function install() {
        foreach ($this->tables as $tableName => $structure) {
            $this->installTable($tableName, $structure);
        }

        $hasIndex = Database::queryRow(Database::parsePrefix("SHOW INDEXES FROM `#__nextend2_section_storage` WHERE Key_name = 'system'"));
        if ($hasIndex) {
            $this->query("ALTER TABLE `#__nextend2_section_storage` DROP INDEX `system`");
        }

        if ($this->hasColumn('#__nextend2_section_storage', 'system')) {
            $this->query("ALTER TABLE `#__nextend2_section_storage` CHANGE  `system`  `isSystem` INT(11) NOT NULL DEFAULT '0'");
        }

        $hasIndex = Database::queryRow(Database::parsePrefix("SHOW INDEXES FROM `#__nextend2_section_storage` WHERE Key_name = 'application'"));
        if ($hasIndex) {
            $this->query("ALTER TABLE `#__nextend2_section_storage` DROP INDEX `application`");
        }

        $hasIndex = Database::queryRow(Database::parsePrefix("SHOW INDEXES FROM `#__nextend2_section_storage` WHERE Key_name = 'application_2'"));
        if ($hasIndex) {
            $this->query("ALTER TABLE `#__nextend2_section_storage` DROP INDEX `application_2`");
        }

        $this->query("ALTER TABLE `#__nextend2_section_storage` CHANGE  `section`  `section` VARCHAR( 128 ) NOT NULL");
        $this->query("ALTER TABLE `#__nextend2_section_storage` CHANGE  `referencekey`  `referencekey` VARCHAR( 128 ) NOT NULL");

        $this->query("ALTER TABLE `#__nextend2_section_storage` ADD INDEX `application` (`application`, `section`(50), `referencekey`(50))");
        $this->query("ALTER TABLE `#__nextend2_section_storage` ADD INDEX `application_2` (`application`, `section`(50))");

        self::fixIndex('#__nextend2_section_storage', 'isSystem');
        self::fixIndex('#__nextend2_section_storage', 'editable');

        if (!$this->hasColumn('#__nextend2_smartslider3_sliders', 'thumbnail')) {
            $this->query("ALTER TABLE `#__nextend2_smartslider3_sliders` ADD `thumbnail` VARCHAR( 255 ) NOT NULL");
        }

        if (!$this->hasColumn('#__nextend2_smartslider3_sliders', 'ordering')) {
            $this->query("ALTER TABLE `#__nextend2_smartslider3_sliders` ADD `ordering` INT NOT NULL DEFAULT '0'");
        }

        if (!$this->hasColumn('#__nextend2_smartslider3_sliders', 'alias')) {
            $this->query("ALTER TABLE `#__nextend2_smartslider3_sliders` ADD `alias` VARCHAR( 255 ) NULL DEFAULT NULL");
        }

        $hasIndex = Database::queryRow(Database::parsePrefix("SHOW INDEXES FROM `#__nextend2_smartslider3_sliders` WHERE Key_name = 'status'"));
        if ($hasIndex) {
            $this->query("ALTER TABLE `#__nextend2_smartslider3_sliders` DROP INDEX `status`");
        }

        if (!$this->hasColumn('#__nextend2_smartslider3_sliders', 'slider_status')) {
            if ($this->hasColumn('#__nextend2_smartslider3_sliders', 'status')) {
                $this->query("ALTER TABLE `#__nextend2_smartslider3_sliders` CHANGE  `status`  `slider_status` VARCHAR(50) NOT NULL DEFAULT 'published'");
            } else {
                $this->query("ALTER TABLE `#__nextend2_smartslider3_sliders` ADD `slider_status` VARCHAR(50) NOT NULL DEFAULT 'published'");
            }
        }

        $this->query("ALTER TABLE `#__nextend2_smartslider3_sliders` CHANGE  `title`  `title` VARCHAR( 200 ) NOT NULL");

        self::fixIndex('#__nextend2_smartslider3_sliders', 'slider_status');
        self::fixIndex('#__nextend2_smartslider3_sliders', 'time');

        self::fixIndex('#__nextend2_smartslider3_sliders_xref', 'ordering');

        $this->query("ALTER TABLE `#__nextend2_smartslider3_slides` CHANGE `publish_up` `publish_up` DATETIME NOT NULL DEFAULT '1970-01-01 00:00:00'");
        $this->query("ALTER TABLE `#__nextend2_smartslider3_slides` CHANGE `publish_down` `publish_down` DATETIME NOT NULL DEFAULT '1970-01-01 00:00:00'");

        /**
         * Fix automated publish down dates
         *
         * @todo Remove this in 2021
         */
        $this->query("UPDATE `#__nextend2_smartslider3_slides` SET `publish_down` = '1970-01-01 00:00:00' WHERE `publish_down` > '2023-04-02 00:00:00'");

        $this->query("DELETE FROM `#__nextend2_section_storage` WHERE `application` LIKE 'smartslider' AND `section` LIKE 'sliderChanged'");


        self::fixIndex('#__nextend2_smartslider3_slides', 'published');
        self::fixIndex('#__nextend2_smartslider3_slides', 'publish_up');
        self::fixIndex('#__nextend2_smartslider3_slides', 'publish_down');
        self::fixIndex('#__nextend2_smartslider3_slides', 'generator_id');


        $hasIndex = Database::queryRow(Database::parsePrefix("SHOW INDEXES FROM `#__nextend2_smartslider3_slides` WHERE Key_name = 'thumbnail'"));
        if ($hasIndex) {
            $this->query("ALTER TABLE `#__nextend2_smartslider3_slides` DROP INDEX `thumbnail`");
        }
        $this->query("ALTER TABLE `#__nextend2_smartslider3_slides` ADD INDEX `thumbnail` (`thumbnail`(100))");

        self::fixIndex('#__nextend2_smartslider3_slides', 'ordering');
        self::fixIndex('#__nextend2_smartslider3_slides', 'slider');

        if (Notification::hasErrors()) {
            Notification::displayPlainErrors();
            exit;
        }
    }

    private function installTable($tableName, $structure) {
        $query = 'CREATE TABLE IF NOT EXISTS `' . Database::getPrefix() . $tableName . '` ';

        $query .= $structure;
        $query .= ' ' . Database::getCharsetCollate();

        $this->query($query);
    }

    private function query($query) {

        Database::query(Database::parsePrefix($query));
    }

    private function hasColumn($table, $col) {
        return !!Database::queryRow(Database::parsePrefix("SHOW COLUMNS FROM `" . $table . "` LIKE '" . $col . "'"));
    }

    public static function repair() {

        self::fixPrimaryKey('#__nextend2_section_storage', 'id', true);

        self::fixPrimaryKey('#__nextend2_image_storage', 'id', true);

        self::fixPrimaryKey('#__nextend2_smartslider3_generators', 'id', true);

        self::fixPrimaryKey('#__nextend2_smartslider3_sliders', 'id', true);

        self::fixPrimaryKey('#__nextend2_smartslider3_slides', 'id', true);

        self::fixPrimaryKey('#__nextend2_smartslider3_sliders_xref', array(
            'slider_id',
            'group_id'
        ));
    }


    public function reindexOrders() {
        $query   = "SELECT
            sliders.*
        FROM
            `#__nextend2_smartslider3_sliders` AS sliders
        LEFT JOIN `#__nextend2_smartslider3_sliders_xref` AS xref
        ON
            xref.slider_id = sliders.id
        WHERE
            (
                xref.group_id IS NULL OR xref.group_id = 0
            )
        ORDER BY ordering";
        $sliders = Database::queryAll(Database::parsePrefix($query));
        foreach ($sliders as $idx => $slider) {
            $this->query("UPDATE `#__nextend2_smartslider3_sliders` SET `ordering` = '" . $idx . "'  WHERE `id` = " . $slider['id'] . " ");
        }

    }


    /**
     * @param string       $tableName
     * @param array|string $colNames
     * @param bool         $autoIncrement
     */
    private static function fixPrimaryKey($tableName, $colNames, $autoIncrement = false) {
        if (!is_array($colNames)) {
            $colNames = array($colNames);
        }
        $tableName = Database::parsePrefix($tableName);

        Database::query('DELETE FROM ' . $tableName . ' WHERE ' . $colNames[0] . ' = 0;');
        $hasIndex = Database::queryRow("SHOW INDEXES FROM " . $tableName . " WHERE Key_name = 'PRIMARY'");
        if (!$hasIndex) {
            Database::query('ALTER TABLE ' . $tableName . ' ADD PRIMARY KEY(' . implode(', ', $colNames) . ');');
        }

        if (count($colNames) == 1 && $autoIncrement) {
            Database::query('ALTER TABLE ' . $tableName . ' MODIFY `' . $colNames[0] . '` INT NOT NULL AUTO_INCREMENT;');
        }
    }

    private static function fixIndex($tableName, $colName) {
        $tableName = Database::parsePrefix($tableName);


        $hasIndex = Database::queryRow("SHOW INDEXES FROM " . $tableName . " WHERE Key_name = '" . $colName . "'");
        if (!$hasIndex) {
            Database::query("ALTER TABLE " . $tableName . " ADD INDEX `" . $colName . "` (`" . $colName . "`)");
        }
    }
}