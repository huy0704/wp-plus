<?php


namespace Nextend\Framework\Cache\Storage;


class Filesystem extends AbstractStorage {

    public function __construct() {
        $this->paths['web']    = \Nextend\Framework\Filesystem\Filesystem::getWebCachePath();
        $this->paths['notweb'] = \Nextend\Framework\Filesystem\Filesystem::getNotWebCachePath();
        $this->paths['image']  = \Nextend\Framework\Filesystem\Filesystem::getImagesFolder();
    }

    public function isFilesystem() {
        return true;
    }

    public function clearAll($scope = 'notweb') {
        if (\Nextend\Framework\Filesystem\Filesystem::existsFolder($this->paths[$scope])) {
            \Nextend\Framework\Filesystem\Filesystem::deleteFolder($this->paths[$scope]);
        }
    }

    public function clear($group, $scope = 'notweb') {

        if (\Nextend\Framework\Filesystem\Filesystem::existsFolder($this->paths[$scope] . '/' . $group)) {
            \Nextend\Framework\Filesystem\Filesystem::deleteFolder($this->paths[$scope] . '/' . $group);
        }
    }

    public function exists($group, $key, $scope = 'notweb') {
        if (\Nextend\Framework\Filesystem\Filesystem::existsFile($this->paths[$scope] . '/' . $group . '/' . $key)) {
            return true;
        }

        return false;
    }

    public function set($group, $key, $value, $scope = 'notweb') {
        $path = $this->paths[$scope] . '/' . $group . '/' . $key;
        $dir  = dirname($path);
        if (!\Nextend\Framework\Filesystem\Filesystem::existsFolder($dir)) {
            \Nextend\Framework\Filesystem\Filesystem::createFolder($dir);
        }
        \Nextend\Framework\Filesystem\Filesystem::createFile($path, $value);
    }

    public function get($group, $key, $scope = 'notweb') {
        return \Nextend\Framework\Filesystem\Filesystem::readFile($this->paths[$scope] . '/' . $group . '/' . $key);
    }

    public function remove($group, $key, $scope = 'notweb') {
        if ($this->exists($group, $key, $scope)) {
            @unlink($this->paths[$scope] . '/' . $group . '/' . $key);
        }
    }

    public function getPath($group, $key, $scope = 'notweb') {
        return $this->paths[$scope] . DIRECTORY_SEPARATOR . $group . DIRECTORY_SEPARATOR . $key;
    }
}