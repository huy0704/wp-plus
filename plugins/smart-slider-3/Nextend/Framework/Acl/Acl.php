<?php

namespace Nextend\Framework\Acl;

use Nextend\Framework\Acl\Joomla\JoomlaAcl;
use Nextend\Framework\Acl\WordPress\WordPressAcl;
use Nextend\Framework\Pattern\MVCHelperTrait;

class Acl {

    /**
     * @var AbstractPlatformAcl
     */
    private static $instance;

    public function __construct() {
        self::$instance = new WordPressAcl();
    }

    /**
     * @param                $action
     * @param MVCHelperTrait $MVCHelper
     *
     * @return bool
     */
    public static function canDo($action, $MVCHelper) {
        return self::$instance->authorise($action, $MVCHelper);
    }
}

new Acl();