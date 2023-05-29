<?php

namespace Nextend\Framework\Form;

trait TraitFieldset {

    use TraitContainer;

    /**
     * @var AbstractField
     */
    protected $first, $last;

    /**
     * @var bool
     */
    protected $isVisible = true;

    public function hide() {
        $this->isVisible = false;
    }

    /**
     * @return ContainerInterface
     */
    public function getParent() {
        return $this->parent;
    }

    public function getPath() {
        return $this->getParent()
                    ->getPath() . '/' . $this->name;
    }

    public function renderContainer() {

    }

    abstract public function getControlName();

    /**
     * @return Form
     */
    abstract public function getForm();

    /**
     * @param AbstractField $element
     *
     * @return string
     */
    abstract public function decorateElement($element);

    abstract public function getName();
}