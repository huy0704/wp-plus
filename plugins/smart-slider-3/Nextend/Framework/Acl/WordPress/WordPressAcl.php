<?php

namespace Nextend\Framework\Acl\WordPress;

use Nextend\Framework\Acl\AbstractPlatformAcl;
use function current_user_can;

class WordPressAcl extends AbstractPlatformAcl {

    public function authorise($action, $MVCHelper) {
        return current_user_can($action) && current_user_can('unfiltered_html');
    }
}