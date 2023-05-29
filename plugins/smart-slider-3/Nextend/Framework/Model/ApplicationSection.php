<?php

namespace Nextend\Framework\Model;

class ApplicationSection {

    private $application = 'system';

    /**
     * Quick cache implementation to prevent duplicate queries. It might have bugs.
     *
     * @var array
     */
    protected $cache = array();

    public function __construct($application) {
        $this->application = $application;
    }

    public function getById($id, $section) {
        return Section::getById($id, $section);
    }

    public function setById($id, $value) {
        $this->cache = array();

        return Section::setById($id, $value);
    }

    public function get($section, $referenceKey = null, $default = null) {

        if (isset($this->cache[$section . '///' . $referenceKey])) {
            return $this->cache[$section . '///' . $referenceKey];
        }

        $attributes = array(
            "application" => $this->application,
            "section"     => $section
        );

        if ($referenceKey !== null) {
            $attributes['referencekey'] = $referenceKey;
        }
        $result = Section::$tableSectionStorage->findByAttributes($attributes);
        if (is_array($result)) {
            $this->cache[$section . '///' . $referenceKey] = $result['value'];

            return $result['value'];
        }

        return $default;
    }

    public function getAll($section, $referenceKey = null) {
        return Section::getAll($this->application, $section, $referenceKey);
    }

    public function set($section, $referenceKey, $value) {
        if (isset($this->cache[$section . '///' . $referenceKey])) {
            unset($this->cache[$section . '///' . $referenceKey]);
        }

        Section::set($this->application, $section, $referenceKey, $value);
    }

    public function add($section, $referenceKey, $value) {
        if (isset($this->cache[$section . '///' . $referenceKey])) {
            unset($this->cache[$section . '///' . $referenceKey]);
        }

        return Section::add($this->application, $section, $referenceKey, $value);
    }

    public function delete($section, $referenceKey = null) {
        if (isset($this->cache[$section . '///' . $referenceKey])) {
            unset($this->cache[$section . '///' . $referenceKey]);
        }

        return Section::delete($this->application, $section, $referenceKey);
    }

    public function deleteById($id) {
        return Section::deleteById($id);
    }
}