<?php


namespace Nextend\Framework\Form\Element\Text;


use Nextend\Framework\Browse\BrowseManager;

class Video extends FieldImage {

    protected function fetchElement() {

        BrowseManager::enqueue($this->getForm());

        $html = parent::fetchElement();

        return $html;
    }
}