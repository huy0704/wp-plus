<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\AddLayer;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Plugin;
use Nextend\Framework\Style\ModelCss;
use Nextend\Framework\View\AbstractBlock;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonPlainIcon;
use Nextend\SmartSlider3\Renderable\Item\ItemFactory;
use Nextend\SmartSlider3\Slider\SliderType\SliderTypeFactory;

class BlockAddLayer extends AbstractBlock {

    protected $groups = array();

    protected $sliderType = '';

    public function display() {

        $this->groups[n2_x('Basic', 'Layer group')] = array(
            array(
                'label'      => n2_('Row'),
                'icon'       => 'ssi_32 ssi_32--col2',
                'attributes' => array(
                    'data-item' => 'structure-2col'
                )
            )
        );


        $cssModel = new ModelCss($this);

        $itemDefaults = SliderTypeFactory::getType($this->sliderType)
                                         ->getItemDefaults();

        foreach (ItemFactory::getItemGroups() as $groupLabel => $group) {
            foreach ($group as $type => $item) {
                if (!$item->isLegacy()) {
                    if (!isset($this->groups[$groupLabel])) {
                        $this->groups[$groupLabel] = array();
                    }
                    $visualKey = 'ss3item' . $type;
                    $visuals   = $cssModel->getVisuals($visualKey);
                    Plugin::doAction($visualKey . 'Storage', array(
                        &$visuals
                    ));
                    Js::addInline('window["' . $visualKey . '"] = ' . json_encode($visuals) . ';');

                    $this->groups[$groupLabel][] = array(
                        'label'      => $item->getTitle(),
                        'icon'       => $item->getIcon(),
                        'attributes' => array(
                            'data-item'            => $type,
                            'data-layerproperties' => json_encode((object)array_merge($item->getLayerProperties(), $itemDefaults))
                        )
                    );
                }
            }
        }

        $this->renderTemplatePart('AddLayer');
    }

    /**
     * @return array
     */
    public function getGroups() {
        return $this->groups;
    }

    /**
     * @param string $sliderType
     */
    public function setSliderType($sliderType) {
        $this->sliderType = $sliderType;
    }

    public function displayAddShortcut($type, $icon, $label) {
        $button = new BlockButtonPlainIcon($this);
        $button->addClass('n2_add_layer__bar_button');
        $button->setIcon($icon);
        $button->addAttribute('data-add-layer-shortcut', $type);
        $button->addAttribute('data-n2tip', $label);
        $button->display();
    }
}