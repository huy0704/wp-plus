<?php

namespace Nextend\Framework\Cache;


use Nextend\Framework\Cache\Storage\AbstractStorage;

abstract class AbstractCache {

    protected $group = '';
    protected $isAccessible = false;

    /** @var AbstractStorage */
    public $storage;

    protected $_storageEngine = 'filesystem';

    /**
     * @param string $engine
     *
     * @return AbstractStorage
     */
    public static function getStorage($engine = "filesystem") {
        static $storage = null;
        if ($storage === null) {
            $storage = array(
                'filesystem' => new  Storage\Filesystem(),
                'database'   => new Storage\Database()
            );
        }

        return $storage[$engine];
    }

    public static function clearAll() {
        self::getStorage('filesystem')
            ->clearAll();
        self::getStorage('filesystem')
            ->clearAll('web');
    }

    public static function clearGroup($group) {
        self::getStorage('filesystem')
            ->clear($group);
        self::getStorage('filesystem')
            ->clear($group, 'web');
        self::getStorage('database')
            ->clear($group);
        self::getStorage('database')
            ->clear($group, 'web');
    }

    public function __construct($group, $isAccessible = false) {
        $this->group        = $group;
        $this->isAccessible = $isAccessible;
        $this->storage      = self::getStorage($this->_storageEngine);
    }

    protected function clearCurrentGroup() {
        $this->storage->clear($this->group, $this->getScope());
    }

    protected function getScope() {
        if ($this->isAccessible) {
            return 'web';
        }

        return 'notweb';
    }

    protected function exists($key) {
        return $this->storage->exists($this->group, $key, $this->getScope());
    }

    protected function get($key) {
        return $this->storage->get($this->group, $key, $this->getScope());
    }

    protected function set($key, $value) {
        $this->storage->set($this->group, $key, $value, $this->getScope());
    }

    protected function getPath($key) {
        return $this->storage->getPath($this->group, $key, $this->getScope());
    }

    protected function remove($key) {
        return $this->storage->remove($this->group, $key, $this->getScope());
    }
}