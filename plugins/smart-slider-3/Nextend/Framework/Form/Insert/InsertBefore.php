<?php


namespace Nextend\Framework\Form\Insert;


class InsertBefore extends AbstractInsert {

    public function insert($element) {
        $parent = $this->at->getParent();
        $parent->insertElementBefore($element, $this->at);

        return $parent;
    }
}