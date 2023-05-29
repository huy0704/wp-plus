<?php

namespace Nextend\Framework\Form;

use Nextend\Framework\Form\Insert\AbstractInsert;

class ContainerGeneral extends AbstractContainer {

    /**
     * @var ContainerInterface
     */
    protected $parent;

    protected $name = '';

    protected $label = '';

    protected $class = '';

    /**
     * Container constructor.
     *
     * @param ContainerInterface|AbstractInsert $insertAt
     * @param string                            $name
     * @param boolean|string                    $label
     * @param array                             $parameters
     */
    public function __construct($insertAt, $name, $label = false, $parameters = array()) {

        $this->name  = $name;
        $this->label = $label;

        if ($insertAt instanceof ContainerInterface) {
            $this->parent = $insertAt;
            $this->parent->addElement($this);
        } else if ($insertAt instanceof AbstractInsert) {
            $this->parent = $insertAt->insert($this);
        }

        $this->controlName = $this->parent->getControlName();

        foreach ($parameters as $option => $value) {
            $option = 'set' . $option;
            $this->{$option}($value);
        }

    }

    public function removeElement($element) {
        $previous = $element->getPrevious();
        $next     = $element->getNext();

        if ($this->first === $element) {
            $this->first = $next;
        }

        if ($this->last === $element) {
            $this->last = $previous;
        }

        if ($previous) {
            $previous->setNext($next);
        } else {
            $next->setPrevious();
        }
    }

    /**
     * @return ContainerInterface
     */
    public function getParent() {
        return $this->parent;
    }

    public function getPath() {
        return $this->parent->getPath() . '/' . $this->name;
    }

    /**
     * @param string $class
     */
    public function setClass($class) {
        $this->class = $class;
    }

    /**
     * @return bool
     */
    public function hasLabel() {
        return !empty($this->label);
    }

    /**
     * @return string
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return Form
     */
    public function getForm() {
        return $this->parent->getForm();
    }

    /**
     * @return string
     */
    public function getControlName() {
        return $this->controlName;
    }

    /**
     * @param string $controlName
     */
    public function setControlName($controlName) {
        $this->controlName = $controlName;
    }
}