<?php

namespace Nextend\Framework\Misc\Zip;

use Nextend\Framework\Misc\Zip\Reader\Custom;
use Nextend\Framework\Misc\Zip\Reader\ZipExtension;

class Reader {

    public static function read($path) {

        if (class_exists('ZipArchive') && strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            $reader = new ZipExtension();
        } else {
            $reader = new Custom();
        }

        return $reader->read($path);
    }
}