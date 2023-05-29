<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\Settings;


use Nextend\Framework\Form\Container\LayerWindow\ContainerSettings;
use Nextend\Framework\Form\ContainerInterface;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\BlockLayerWindow;

abstract class AbstractLayerWindowSettings {

    /**
     * @var BlockLayerWindow
     */
    protected $blockLayerWindow;

    /**
     * @var ContainerSettings
     */
    protected $contentContainer;

    /**
     * @var ContainerSettings
     */
    protected $styleContainer;

    /**
     * AbstractLayerWindowSettings constructor.
     *
     * @param BlockLayerWindow $blockLayerWindow
     */
    public function __construct($blockLayerWindow) {

        $this->blockLayerWindow = $blockLayerWindow;
    }

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @param ContainerInterface $contentContainer
     * @param ContainerInterface $styleContainer
     */
    public function extendForm($contentContainer, $styleContainer) {
        $this->createContentContainer($contentContainer);
        $this->createStyleContainer($styleContainer);

        $this->extendContent();
        $this->extendStyle();
    }

    /**
     * @param ContainerInterface $container
     */
    protected function createContentContainer($container) {
        $this->contentContainer = new ContainerSettings($container, $this->getName());
    }

    /**
     * @param ContainerInterface $container
     */
    protected function createStyleContainer($container) {
        $this->styleContainer = new ContainerSettings($container, $this->getName());
    }

    protected function extendContent() {

    }

    protected function extendStyle() {

    }
}