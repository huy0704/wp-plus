<?php

namespace Nextend\Framework\Form\WordPress;

use Nextend\Framework\Form\Base\PlatformFormBase;
use Nextend\Framework\Request\Request;

class PlatformForm extends PlatformFormBase {

    public function tokenize() {
        return '<input type="hidden" name="nextend_nonce" value="' . wp_create_nonce('nextend_security') . '">';
    }

    public function tokenizeUrl() {
        $a                  = array();
        $a['nextend_nonce'] = wp_create_nonce('nextend_security');

        return $a;
    }

    public function checkToken() {
        return wp_verify_nonce(Request::$REQUEST->getVar('nextend_nonce'), 'nextend_security');
    }
}