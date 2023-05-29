<?php


namespace Nextend\Framework\Form;


interface ContainerInterface {

    /**
     * @param ContainedInterface $element
     */
    public function addElement($element);

    /**
     * @param ContainedInterface $element
     * @param ContainedInterface $target
     */
    public function insertElementBefore($element, $target);

    /**
     * @param ContainedInterface $element
     * @param ContainedInterface $target
     */
    public function insertElementAfter($element, $target);

    /**
     * @param ContainedInterface $element
     */
    public function removeElement($element);

    /**
     * @param $path
     *
     * @return ContainedInterface
     */
    public function getElement($path);

    /**
     * @return string
     */
    public function getPath();

    /**
     * @return Form
     */
    public function getForm();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getControlName();

    public function renderContainer();
}