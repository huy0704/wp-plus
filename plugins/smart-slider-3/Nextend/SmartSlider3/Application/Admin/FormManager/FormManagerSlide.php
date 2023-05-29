<?php


namespace Nextend\SmartSlider3\Application\Admin\FormManager;


use Nextend\Framework\Form\AbstractFormManager;
use Nextend\Framework\Form\Element\Hidden;
use Nextend\Framework\Form\Form;
use Nextend\Framework\Pattern\MVCHelperTrait;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;
use Nextend\SmartSlider3\Slider\Slider;

class FormManagerSlide extends AbstractFormManager {

    use TraitAdminUrl;

    protected $data;

    /**
     * @var int
     */
    protected $groupID;

    /** @var Slider */
    private $slider;

    private $slide;

    /**
     * @var Form
     */
    protected $form;

    /**
     * FormManagerSlide constructor.
     *
     * @param MVCHelperTrait $MVCHelper
     * @param int            $groupID
     * @param Slider         $slider
     * @param                $slide
     */
    public function __construct($MVCHelper, $groupID, $slider, $slide) {

        $this->groupID = $groupID;
        $this->slider  = $slider;
        $this->slide   = $slide;

        parent::__construct($MVCHelper);

        $params = json_decode($slide['params'], true);
        if ($params == null) $params = array();
        $params                 += $slide;
        $params['sliderid']     = $slide['slider'];
        $params['generator_id'] = $slide['generator_id'];

        $params['first'] = isset($slide['first']) ? $slide['first'] : 0;

        $this->data = $params;

        $this->initForm();
    }

    public function render() {

        $this->form->render();
    }

    private function initForm() {

        $this->form = new Form($this, 'slide');

        if (!empty($this->data['guides'])) {
            $this->form->set('guides', $this->data['guides']);
        }

        $hidden = $this->form->getFieldsetHidden();

        new Hidden($hidden, 'slide', '');

        new Hidden($hidden, 'guides', '');
    }
}