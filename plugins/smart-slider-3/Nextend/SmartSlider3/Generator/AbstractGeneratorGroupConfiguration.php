<?php


namespace Nextend\SmartSlider3\Generator;


use Nextend\Framework\Pattern\MVCHelperTrait;

abstract class AbstractGeneratorGroupConfiguration {

    /** @var AbstractGeneratorGroup */
    protected $generatorGroup;

    /**
     * AbstractGeneratorGroupConfiguration constructor.
     *
     * @param AbstractGeneratorGroup $generatorGroup
     */
    public function __construct($generatorGroup) {

        $this->generatorGroup = $generatorGroup;
    }

    /**
     * @return bool
     */
    public abstract function wellConfigured();

    /**
     * @return array
     */
    public abstract function getData();

    /**
     * @param      $data
     * @param bool $store
     */
    public abstract function addData($data, $store = true);

    /**
     * @param MVCHelperTrait $MVCHelper
     */
    public abstract function render($MVCHelper);

    /**
     * @param MVCHelperTrait $MVCHelper
     */
    public abstract function startAuth($MVCHelper);

    /**
     * @param MVCHelperTrait $MVCHelper
     */
    public abstract function finishAuth($MVCHelper);
}