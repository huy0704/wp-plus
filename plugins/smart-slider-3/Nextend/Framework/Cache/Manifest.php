<?php

namespace Nextend\Framework\Cache;

class Manifest extends AbstractCache {

    private $isRaw = false;

    private $manifestData;

    public function __construct($group, $isAccessible = false, $isRaw = false) {
        parent::__construct($group, $isAccessible);
        $this->isRaw = $isRaw;
    }

    protected function decode($data) {
        return $data;
    }

    /**
     * @param          $fileName
     * @param          $hash
     * @param callback $callable
     *
     * @return bool
     */
    public function makeCache($fileName, $hash, $callable) {
        if (!$this->isCached($fileName, $hash)) {

            $return = call_user_func($callable, $this);
            if ($return === false) {
                return false;
            }

            return $this->createCacheFile($fileName, $hash, $return);
        }
        if ($this->isAccessible) {
            return $this->getPath($fileName);
        }

        return $this->decode($this->get($fileName));
    }

    private function isCached($fileName, $hash) {


        $manifestKey = $this->getManifestKey($fileName);
        if ($this->exists($manifestKey)) {

            $this->manifestData = json_decode($this->get($manifestKey), true);

            if (!$this->isCacheValid($this->manifestData) || $this->manifestData['hash'] != $hash || !$this->exists($fileName)) {
                $this->clean($fileName);

                return false;
            }

            return true;
        }

        return false;
    }

    protected function createCacheFile($fileName, $hash, $content) {

        $this->manifestData = array();

        $this->manifestData['hash'] = $hash;
        $this->addManifestData($this->manifestData);

        $this->set($this->getManifestKey($fileName), json_encode($this->manifestData));

        $this->set($fileName, $this->isRaw ? $content : json_encode($content));

        if ($this->isAccessible) {
            return $this->getPath($fileName);
        }

        return $content;
    }

    protected function isCacheValid(&$manifestData) {
        return true;
    }

    protected function addManifestData(&$manifestData) {

    }

    public function clean($fileName) {

        $this->remove($this->getManifestKey($fileName));
        $this->remove($fileName);
    }

    protected function getManifestKey($fileName) {
        return $fileName . '.manifest';
    }

    public function getData($key, $default = 0) {
        return isset($this->manifestData[$key]) ? $this->manifestData[$key] : $default;
    }
}