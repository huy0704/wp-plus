<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\Tab;


use Nextend\Framework\Form\ContainerInterface;
use Nextend\Framework\Form\Form;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\BlockLayerWindow;

abstract class AbstractTab {

    /**
     * @var BlockLayerWindow
     */
    protected $blockLayerWindow;

    /**
     * @var Form
     */
    protected $form;

    /**
     * AbstractTab constructor.
     *
     * @param BlockLayerWindow $blockLayerWindow
     */
    public function __construct($blockLayerWindow) {

        $this->blockLayerWindow = $blockLayerWindow;

        $this->form = new Form($blockLayerWindow, 'layer');
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer() {
        return $this->form->getContainer();
    }

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @return string
     */
    abstract public function getLabel();

    /**
     * @return string
     */
    abstract public function getIcon();

    public function display() {

        $this->form->render();
    }
}