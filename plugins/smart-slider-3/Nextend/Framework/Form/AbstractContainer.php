<?php


namespace Nextend\Framework\Form;


abstract class AbstractContainer implements ContainerContainedInterface {

    use TraitContainer;

    /**
     * @var ContainerContainedInterface
     */
    protected $first, $last;

    protected $controlName = '';

    /**
     * @var ContainerInterface;
     */
    private $previous, $next;

    public function getPrevious() {
        return $this->previous;
    }

    /**
     * @param ContainedInterface|null $element
     */
    public function setPrevious($element = null) {
        $this->previous = $element;
    }

    public function getNext() {
        return $this->next;
    }

    /**
     * @param ContainedInterface|null $element
     */
    public function setNext($element = null) {
        $this->next = $element;
        if ($element) {
            $element->setPrevious($this);
        }
    }

    public function remove() {
        $this->getParent()
             ->removeElement($this);
    }

    public function render() {
        $this->renderContainer();
    }

    public function renderContainer() {
        $element = $this->first;
        while ($element) {
            $element->renderContainer();
            $element = $element->getNext();
        }
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