<?php


namespace Nextend\Framework\Cache\Storage;


abstract class AbstractStorage {

    protected $paths = array();

    public function isFilesystem() {
        return false;
    }

    public abstract function clearAll($scope = 'notweb');

    public abstract function clear($group, $scope = 'notweb');

    public abstract function exists($group, $key, $scope = 'notweb');

    public abstract function set($group, $key, $value, $scope = 'notweb');

    public abstract function get($group, $key, $scope = 'notweb');

    public abstract function remove($group, $key, $scope = 'notweb');

    public abstract function getPath($group, $key, $scope = 'notweb');
}