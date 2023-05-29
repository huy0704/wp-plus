<?php


namespace Nextend\SmartSlider3\Application\Admin\FormManager;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Font\FontManager;
use Nextend\Framework\Form\AbstractFormManager;
use Nextend\Framework\Form\Element\Hidden;
use Nextend\Framework\Form\FormTabbed;
use Nextend\Framework\Pattern\MVCHelperTrait;
use Nextend\Framework\Style\StyleManager;
use Nextend\SmartSlider3\Application\Admin\FormManager\Slider\SliderAnimations;
use Nextend\SmartSlider3\Application\Admin\FormManager\Slider\SliderAutoplay;
use Nextend\SmartSlider3\Application\Admin\FormManager\Slider\SliderControls;
use Nextend\SmartSlider3\Application\Admin\FormManager\Slider\SliderDeveloper;
use Nextend\SmartSlider3\Application\Admin\FormManager\Slider\SliderGeneral;
use Nextend\SmartSlider3\Application\Admin\FormManager\Slider\SliderOptimize;
use Nextend\SmartSlider3\Application\Admin\FormManager\Slider\SliderSize;
use Nextend\SmartSlider3\Application\Admin\FormManager\Slider\SliderSlides;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Header\BlockHeader;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;
use Nextend\SmartSlider3\BackgroundAnimation\BackgroundAnimationManager;
use Nextend\SmartSlider3\Slider\SliderParams;
use Nextend\SmartSlider3\Slider\SliderType\AbstractSliderTypeAdmin;
use Nextend\SmartSlider3\Slider\SliderType\SliderTypeFactory;
use Nextend\SmartSlider3Pro\PostBackgroundAnimation\PostBackgroundAnimationManager;

class FormManagerSlider extends AbstractFormManager {

    use TraitAdminUrl;

    protected $slider;
    protected $data;

    /**
     * @var FormTabbed
     */
    protected $form;

    /**
     * @var AbstractSliderTypeAdmin
     */
    protected $sliderType;

    /**
     * FormManagerSlider constructor.
     *
     * @param MVCHelperTrait $MVCHelper
     * @param                $slider
     */
    public function __construct($MVCHelper, $slider) {

        parent::__construct($MVCHelper);

        $this->slider = $slider;

        $sliderParams = new SliderParams($slider['id'], $slider['type'], $slider['params'], true);

        $data              = $sliderParams->toArray();
        $data['title']     = $slider['title'];
        $data['type']      = $slider['type'];
        $data['thumbnail'] = $slider['thumbnail'];
        $data['alias']     = isset($slider['alias']) ? $slider['alias'] : '';
        $this->data        = $data;

        $this->initForm();
    }

    public function render() {

        $this->form->render();
    }

    /**
     * @return array|mixed|object
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param BlockHeader $blockHeader
     */
    public function addTabsToHeader($blockHeader) {
        $this->form->addTabsToHeader($blockHeader);
    }

    /**
     * @return AbstractSliderTypeAdmin
     */
    public function getSliderType() {
        return $this->sliderType;
    }

    private function initForm() {

        FontManager::enqueue($this);
        StyleManager::enqueue($this);

        // Background animations are required for simple type. We need to load the lightbox, because it is not working over AJAX slider type change.
        BackgroundAnimationManager::enqueue($this);

        $this->form = new FormTabbed($this, 'slider');
        $this->form->setSessionID('slider-' . $this->slider['id']);
        $this->form->set('sliderID', $this->slider['id']);
        $this->form->set('class', 'nextend-smart-slider-admin');

        $this->form->loadArray($this->data);

        $this->initSliderType();

        new SliderGeneral($this, $this->form);

        new SliderSize($this->form);

        new SliderControls($this->form);

        new SliderAnimations($this->form);

        new SliderAutoplay($this->form);

        new SliderOptimize($this->form);

        new SliderSlides($this->form);

        new SliderDeveloper($this->form);

        $this->sliderType->prepareForm($this->form);
    }

    private function initSliderType() {

        new Hidden($this->form->getFieldsetHidden(), 'type', 'simple');

        $availableTypes = SliderTypeFactory::getAdminTypes();
        $sliderType     = $this->form->get('type', 'simple');
        if (!isset($availableTypes[$sliderType])) {
            $sliderType = 'simple';
        }

        $this->sliderType = $availableTypes[$sliderType];

        $types = array();
        foreach ($availableTypes as $type) {
            if (!$type->isDepreciated() || $type->getName() == $sliderType) {
                $types[$type->getName()] = array(
                    'icon'  => $type->getIcon(),
                    'label' => $type->getLabel()
                );
            }
        }

        JS::addInline('new _N2.SliderChangeType(' . json_encode(array(
                'types'       => $types,
                'currentType' => $sliderType,
                'ajaxUrl'     => $this->form->createAjaxUrl(array(
                    "slider/changeSliderType",
                    array(
                        'sliderID' => $this->form->get('sliderID')
                    )
                ))
            )) . ');');
    }
}