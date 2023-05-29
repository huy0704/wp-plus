<?php

namespace Nextend\Framework\Acl;

use Nextend\Framework\Pattern\MVCHelperTrait;

abstract class AbstractPlatformAcl {

    /**
     * @param                $action
     * @param MVCHelperTrait $MVCHelper
     *
     * @return bool
     */
    abstract public function authorise($action, $MVCHelper);
}