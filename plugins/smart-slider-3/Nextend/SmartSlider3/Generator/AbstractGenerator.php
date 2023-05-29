<?php

namespace Nextend\SmartSlider3\Generator;

use Nextend\Framework\Data\Data;
use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\ContainerInterface;
use Nextend\Framework\Form\Element\Message\Warning;
use Nextend\SmartSlider3\Platform\WordPress\Shortcode\Shortcode;

abstract class AbstractGenerator {

    protected $name = '';

    protected $label = '';

    protected $layout = '';

    /** @var  AbstractGeneratorGroup */
    protected $group;

    /** @var Data */
    protected $data;

    /**
     *
     * @param AbstractGeneratorGroup $group
     * @param string                 $name
     * @param string                 $label
     */
    public function __construct($group, $name, $label) {
        $this->group = $group;
        $this->name  = $name;
        $this->label = $label;

        $this->group->addSource($name, $this);
    }

    /**
     *
     * @param ContainerInterface $container
     */
    public function renderFields($container) {

        if ($this->group->isDeprecated()) {
            $table = new ContainerTable($container, 'deprecation', n2_('Deprecation'));

            $row = $table->createRow('deprecation-row');
            new Warning($row, 'deprecation-warning', n2_('This generator will get deprecated soon, so you shouldn\'t use it anymore!'));
        }
    }

    public function setData($data) {
        $this->data = $data;
    }

    public final function getData($slides, $startIndex, $group) {
        Shortcode::shortcodeModeToNoop();
    

        $this->resetState();

        $data       = array();
        $linearData = $this->_getData($slides * $group, $startIndex - 1);
        if ($linearData != null) {
            $keys = array();
            for ($i = 0; $i < count($linearData); $i++) {
                $keys = array_merge($keys, array_keys($linearData[$i]));
            }

            $columns = array_fill_keys($keys, '');

            for ($i = 0; $i < count($linearData); $i++) {
                $firstIndex = intval($i / $group);
                if (!isset($data[$firstIndex])) {
                    $data[$firstIndex] = array();
                }
                $data[$firstIndex][$i % $group] = array_merge($columns, $linearData[$i]);
            }

            if (count($data) && count($data[count($data) - 1]) != $group) {
                if (count($data) - 1 == 0 && count($data[count($data) - 1]) > 0) {
                    while (count($data[0]) < $group) {
                        $data[0][] = $columns;
                    }
                } else {
                    array_pop($data);
                }
            }
        }
        Shortcode::shortcodeModeToNormal();
    

        return $data;
    }

    protected function resetState() {

    }

    protected abstract function _getData($count, $startIndex);

    function makeClickableLinks($s) {
        return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $s);
    }

    protected function getIDs($field = 'ids') {
        return array_map('intval', explode("\n", str_replace(array(
            "\r\n",
            "\n\r",
            "\r"
        ), "\n", $this->data->get($field))));
    }

    public function filterName($name) {
        return $name;
    }

    public function hash($key) {
        return md5($key);
    }

    public static function cacheKey($params) {
        return '';
    }

    /**
     * @return string
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return n2_('No description.');
    }

    /**
     * @return string
     */
    public function getLayout() {
        return $this->layout;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return AbstractGeneratorGroup
     */
    public function getGroup() {
        return $this->group;
    }

}