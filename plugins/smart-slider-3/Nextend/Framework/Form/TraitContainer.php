<?php


namespace Nextend\Framework\Form;


trait TraitContainer {

    /**
     * @var ContainedInterface
     */
    protected $first, $last;

    /**
     * @var ContainedInterface[]
     */
    protected $flattenElements = array();

    /**
     * @param ContainedInterface $element
     */
    public function addElement($element) {

        if (!$this->first) {
            $this->first = $element;
        }

        if ($this->last) {
            $this->last->setNext($element);
        }

        $this->last = $element;

        $name = $element->getName();
        if ($name) {
            $this->flattenElements[$name] = $element;
        }
    }

    /**
     * @param ContainedInterface $element
     * @param ContainedInterface $target
     */
    public function insertElementBefore($element, $target) {
        $previous = $target->getPrevious();
        if ($previous) {
            $previous->setNext($element);
        } else {
            $this->first = $element;
        }

        $element->setNext($target);

        $name = $element->getName();
        if ($name) {
            $this->flattenElements[$name] = $element;
        }
    }

    /**
     * @param AbstractField $element
     * @param AbstractField $target
     */
    public function insertElementAfter($element, $target) {

        $next = $target->getNext();
        $target->setNext($element);

        if ($next) {
            $element->setNext($next);
        } else {
            $this->last = $element;
        }

        $name = $element->getName();
        if ($name) {
            $this->flattenElements[$name] = $element;
        }
    }

    /**
     * @param AbstractField $element
     */
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
     * @param $path
     *
     * @return AbstractField
     */
    public function getElement($path) {
        $parts   = explode('/', $path, 2);
        $element = $this->flattenElements[$parts[0]];
        if (!empty($parts[1]) && $element instanceof ContainerInterface) {
            $element = $element->getElement($parts[1]);
        }

        return $element;
    }

    public function getElementIdentifiers() {
        return array_keys($this->flattenElements);
    }
}