<?php

namespace Nextend\Framework\Cache\Storage;

use Nextend\Framework\Model\ApplicationSection;
use Nextend\Framework\Platform\Platform;

class Database extends AbstractStorage {

    protected $db;

    public function __construct() {

        $this->paths['web']    = 'web';
        $this->paths['notweb'] = 'notweb';
        $this->paths['image']  = 'image';

        $this->db = new  ApplicationSection('cache');
    }

    public function clearAll($scope = 'notweb') {

    }

    public function clear($group, $scope = 'notweb') {

        $this->db->delete($scope . '/' . $group);
    }

    public function exists($group, $key, $scope = 'notweb') {

        if ($this->db->get($scope . '/' . $group, $key)) {
            return true;
        }

        return false;
    }

    public function set($group, $key, $value, $scope = 'notweb') {

        $this->db->set($scope . '/' . $group, $key, $value);
    }

    public function get($group, $key, $scope = 'notweb') {
        return $this->db->get($scope . '/' . $group, $key);
    }

    public function remove($group, $key, $scope = 'notweb') {
        $this->db->delete($scope . '/' . $group, $key);
    }

    public function getPath($group, $key, $scope = 'notweb') {

        return Platform::getSiteUrl() . '?nextendcache=1&g=' . urlencode($group) . '&k=' . urlencode($key);
    }
}