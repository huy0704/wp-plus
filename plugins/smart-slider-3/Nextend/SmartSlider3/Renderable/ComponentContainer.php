<?php


namespace Nextend\SmartSlider3\Renderable;


use Exception;
use Nextend\Framework\Notification\Notification;
use Nextend\SmartSlider3\Renderable\Component\AbstractComponent;
use Nextend\SmartSlider3\Renderable\Component\ComponentCol;
use Nextend\SmartSlider3\Renderable\Component\ComponentContent;
use Nextend\SmartSlider3\Renderable\Component\ComponentLayer;
use Nextend\SmartSlider3\Renderable\Component\ComponentRow;
use Nextend\SmartSlider3\Slider\Slide;

class ComponentContainer {

    /** @var AbstractComponent[] */
    protected $layers = array();

    protected $index = 0;

    /** @var Slide */
    protected $slide;

    /** @var AbstractComponent */
    protected $component;

    /**
     *
     * @param Slide             $slide
     * @param AbstractComponent $component
     * @param array             $componentsData
     */
    public function __construct($slide, $component, $componentsData) {
        $this->slide     = $slide;
        $this->component = $component;

        if (is_array($componentsData)) {

            if ($component->getType() == 'slide') {
                $componentsData = array_reverse($componentsData);
            }

            foreach ($componentsData as $componentData) {
                $this->addComponent($componentData);
            }
        }
    }

    private function addComponent($componentData) {
        $this->index++;
        if (!isset($componentData['type'])) {
            $componentData['type'] = 'layer';
        }
        switch ($componentData['type']) {
            case 'content':
                $this->layers[] = new ComponentContent($this->index, $this->slide, $this->component, $componentData);
                break;
            case 'row':
                $this->layers[] = new ComponentRow($this->index, $this->slide, $this->component, $componentData);
                break;
            case 'col':
                $this->layers[] = new ComponentCol($this->index, $this->slide, $this->component, $componentData);
                break;
            case 'layer':
                try {
                    if (empty($componentData['item'])) {
                        if (empty($componentData['items'])) {
                            $this->index--;
                            break;
                        }
                        $componentData['item'] = $componentData['items'][0];
                    }

                    $layer          = new ComponentLayer($this->index, $this->slide, $this->component, $componentData);
                    $this->layers[] = $layer;

                } catch (Exception $e) {
                    $this->index--;
                    Notification::error($e->getMessage());
                }
                break;
            case 'group':
                $componentData['layers'] = array_reverse($componentData['layers']);
                foreach ($componentData['layers'] as $subComponentData) {
                    $this->addComponent($subComponentData);
                }
                break;

        }
    }

    public function addContentLayer($slide, $component) {
        $content    = false;
        $layerCount = count($this->layers);
        for ($i = 0; $i < $layerCount; $i++) {
            if ($this->layers[$i] instanceof ComponentContent) {
                $content = $this->layers[$i];
                break;
            }
        }

        if ($content === false) {
            array_unshift($this->layers, new ComponentContent($layerCount + 1, $slide, $component, array(
                'bgimage'                   => '',
                'bgimagex'                  => 50,
                'bgimagey'                  => 50,
                'bgcolor'                   => '00000000',
                'bgcolorgradient'           => 'off',
                'verticalalign'             => 'center',
                'desktopportraitinneralign' => 'inherit',
                'desktopportraitpadding'    => '10|*|10|*|10|*|10|*|px',
                'layers'                    => array()
            ), 'absolute'));
        }

        return $content;
    }

    /**
     * @return AbstractComponent[]
     */
    public function getLayers() {
        return $this->layers;
    }

    public function render($isAdmin) {
        $html = '';
        foreach ($this->layers as $layer) {
            $html .= $layer->render($isAdmin);
        }

        return $html;
    }
}