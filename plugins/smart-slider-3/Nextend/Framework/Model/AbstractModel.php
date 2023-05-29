<?php


namespace Nextend\Framework\Model;


use Nextend\Framework\Pattern\MVCHelperTrait;

abstract class AbstractModel {

    use MVCHelperTrait;

    /**
     * AbstractModel constructor.
     *
     * @param MVCHelperTrait $helper
     */
    public function __construct($helper) {

        $this->setMVCHelper($helper);
        $this->init();
    }

    protected function init() {

    }
}