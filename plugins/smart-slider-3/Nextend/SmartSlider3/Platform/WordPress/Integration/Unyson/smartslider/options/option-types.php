<?php

use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Application\ApplicationSmartSlider3;
use Nextend\SmartSlider3\Application\Model\ModelSliders;
use Nextend\SmartSlider3\Platform\WordPress\HelperTinyMCE;

class FW_Option_Type_SmartSliderChooser extends FW_Option_Type_Select {

    protected function _enqueue_static($id, $option, $data) {

        HelperTinyMCE::getInstance()
                     ->addForced();
    }

    public function get_type() {
        return 'smartsliderchooser';
    }

    protected function _render($id, $option, $data) {

        $applicationType = ApplicationSmartSlider3::getInstance()
                                                  ->getApplicationTypeAdmin();

        $slidersModel = new ModelSliders($applicationType);

        $choices = array();
        foreach ($slidersModel->getAll(0, 'published') as $slider) {
            if ($slider['type'] == 'group') {

                $subChoices = array();
                if (!empty($slider['alias'])) {
                    $subChoices[$slider['alias']] = n2_('Whole group') . ' - ' . $slider['title'] . ' #Alias: ' . $slider['alias'];
                }
                $subChoices[$slider['id']] = n2_('Whole group') . ' - ' . $slider['title'] . ' #' . $slider['id'];
                foreach ($slidersModel->getAll($slider['id'], 'published') as $_slider) {
                    if (!empty($_slider['alias'])) {
                        $subChoices[$_slider['alias']] = $_slider['title'] . ' #Alias: ' . $_slider['alias'];
                    }
                    $subChoices[$_slider['id']] = $_slider['title'] . ' #' . $_slider['id'];
                }

                $choices[$slider['id']] = array(
                    'label'   => $slider['title'] . ' #' . $slider['id'],
                    'choices' => $subChoices
                );
            } else {
                if (!empty($slider['alias'])) {
                    $choices[$slider['alias']] = $slider['title'] . ' #Alias: ' . $slider['alias'];
                }
                $choices[$slider['id']] = $slider['title'] . ' #' . $slider['id'];
            }
        }

        $option['choices'] = $choices;

        $option['attr']['style'] = 'width:240px;vertical-align: middle';

        return Html::tag('div', array(), Html::link(n2_('Select Slider'), '#', array(
                'style'   => 'vertical-align:middle;',
                'class'   => 'button button-primary',
                'onclick' => "NextendSmartSliderSelectModal(jQuery('#fw-edit-options-modal-id')); return false;"
            )) . '<span style="margin: 0 10px;vertical-align:middle;text-transform: uppercase;">' . n2_('OR') . '</span>' . parent::_render($id, $option, $data));
    }

    protected function _get_value_from_input($option, $input_value) {
        if (is_null($input_value)) {
            return $option['value'];
        }

        return (string)$input_value;
    }

}

FW_Option_Type::register('FW_Option_Type_SmartSliderChooser');