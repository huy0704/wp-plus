<?php


namespace Nextend\Framework\Form;


interface ContainedInterface {

    /**
     * @return ContainedInterface|null
     */
    public function getPrevious();

    /**
     * @param ContainedInterface|null $element
     */
    public function setPrevious($element = null);

    /**
     * @return ContainedInterface|null
     */
    public function getNext();

    /**
     * @param ContainedInterface|null $element
     */
    public function setNext($element = null);

    public function remove();

    /**
     * @return ContainerInterface
     */
    public function getParent();

    /**
     * @return string
     */
    public function getPath();

    public function render();
}