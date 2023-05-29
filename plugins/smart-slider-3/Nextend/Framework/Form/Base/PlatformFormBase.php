<?php


namespace Nextend\Framework\Form\Base;

class PlatformFormBase {

    public function tokenize() {
        return '';
    }

    public function tokenizeUrl() {
        return '';
    }

    public function checkToken() {
        return true;
    }
}