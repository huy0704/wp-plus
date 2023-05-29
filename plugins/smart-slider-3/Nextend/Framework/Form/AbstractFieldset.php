<?php


namespace Nextend\Framework\Form;

use Nextend\Framework\Form\Insert\AbstractInsert;

abstract class AbstractFieldset implements ContainerContainedInterface {

    use TraitFieldset;

    /**
     * @var ContainedInterface;
     */
    private $previous, $next;

    public function getPrevious() {
        return $this->previous;
    }

    public function setPrevious($element = null) {
        $this->previous = $element;
    }

    public function getNext() {
        return $this->next;
    }

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

    /**
     * @var ContainerInterface
     */
    protected $parent;

    protected $name = '';

    protected $label = '';

    protected $controlName = '';

    protected $class = '';

    /**
     * Container constructor.
     *
     * @param ContainerInterface|AbstractInsert $insertAt
     * @param                                   $name
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

    public function render() {
        $this->renderContainer();
    }

    /**
     * @param AbstractField $element
     *
     * @return string
     */
    public function decorateElement($element) {

        ob_start();

        $element->displayElement();

        return ob_get_clean();
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

    public function hasFields() {

        return !empty($this->flattenElements);
    }

    /**
     * @param string $class
     */
    public function setClass($class) {
        $this->class = $class;
    }
}