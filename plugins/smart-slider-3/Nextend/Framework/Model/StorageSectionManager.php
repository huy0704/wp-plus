<?php


namespace Nextend\Framework\Model;


class StorageSectionManager {

    /** @var ApplicationSection[] */
    private static $storageTypes = array();

    /**
     * @param $type
     *
     * @return ApplicationSection
     */
    public static function getStorage($type) {

        if (!isset(self::$storageTypes[$type])) {
            self::$storageTypes[$type] = new ApplicationSection($type);
        }

        return self::$storageTypes[$type];
    }
}