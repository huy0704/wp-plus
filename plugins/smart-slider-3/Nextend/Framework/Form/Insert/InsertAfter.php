<?php


namespace Nextend\Framework\Form\Insert;


class InsertAfter extends AbstractInsert {

    public function insert($element) {
        $parent = $this->at->getParent();
        $parent->insertElementAfter($element, $this->at);

        return $parent;
    }
}