<?php


namespace Nextend\Framework\Form;


use Nextend\Framework\Pattern\MVCHelperTrait;

abstract class AbstractFormManager {

    use MVCHelperTrait;

    /**
     * AbstractFormManager constructor.
     *
     * @param MVCHelperTrait $MVCHelper
     */
    public function __construct($MVCHelper) {

        $this->setMVCHelper($MVCHelper);
    }
}