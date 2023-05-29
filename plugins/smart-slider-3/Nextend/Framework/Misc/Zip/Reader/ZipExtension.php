<?php

namespace Nextend\Framework\Misc\Zip\Reader;

use Nextend\Framework\Misc\Zip\ReaderInterface;
use ZipArchive;

class ZipExtension implements ReaderInterface {

    public function read($path) {

        $zip = new ZipArchive();

        if (!$zip->open($path)) {
            return array();
        }

        $data = array();

        for ($i = 0; $i < $zip->numFiles; $i++) {

            $stat = $zip->statIndex($i);

            $this->recursiveRead($data, explode('/', $stat['name']), $zip->getFromIndex($i));

        }

        $zip->close();

        return $data;
    }

    private function recursiveRead(&$data, $parts, $content) {
        if (count($parts) == 1) {
            $data[$parts[0]] = $content;
        } else {
            if (!isset($data[$parts[0]])) {
                $data[$parts[0]] = array();
            }
            $this->recursiveRead($data[array_shift($parts)], $parts, $content);
        }
    }
}