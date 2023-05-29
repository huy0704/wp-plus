<?php


namespace Nextend\SmartSlider3\Application\Admin\FormManager\Slider;


use Nextend\Framework\Form\Container\ContainerTab;
use Nextend\Framework\Form\FormTabbed;

abstract class AbstractSliderTab {

    /**
     * @var FormTabbed
     */
    protected $form;

    /**
     * @var ContainerTab
     */
    protected $tab;

    /**
     * AbstractSliderTab constructor.
     *
     * @param FormTabbed $form
     */
    public function __construct($form) {
        $this->form = $form;
        $this->tab  = $form->createTab($this->getName(), $this->getLabel());
    }

    /**
     * @return string
     */
    abstract protected function getName();

    /**
     * @return string
     */
    abstract protected function getLabel();

}