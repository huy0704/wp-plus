<?php


namespace Nextend\Framework\Form;


use Nextend\Framework\Form\Fieldset\FieldsetHidden;

class ContainerMain extends AbstractContainer {

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var FieldsetHidden
     */
    protected $fieldsetHidden;

    /**
     * ContainerMain constructor.
     *
     * @param Form $form
     */
    public function __construct($form) {
        $this->form        = $form;
        $this->controlName = $form->getControlName();

        $this->fieldsetHidden = new FieldsetHidden($this);
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

    public function getParent() {
        return false;
    }

    public function getPath() {
        return '';
    }

    /**
     * @return Form
     */
    public function getForm() {
        return $this->form;
    }

    /**
     * @return string
     */
    public function getName() {
        return 'ContainerMain';
    }

    /**
     * @return FieldsetHidden
     */
    public function getFieldsetHidden() {
        return $this->fieldsetHidden;
    }

    /**
     *
     * @return ContainerContainedInterface
     */
    public function getFirst() {
        return $this->first;
    }
}