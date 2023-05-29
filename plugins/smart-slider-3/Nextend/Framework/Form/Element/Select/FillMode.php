<?php


namespace Nextend\Framework\Form\Element\Select;

use Nextend\Framework\Form\Element\Select;

class FillMode extends Select {

    protected $useGlobal = false;

    protected function fetchElement() {

        $this->options = array(
            'fill'    => n2_('Fill'),
            'blurfit' => n2_('Blur fit'),
            'fit'     => n2_('Fit'),
            'stretch' => n2_('Stretch'),
            'center'  => n2_('Center')
        );

        if ($this->useGlobal) {
            $this->options = array_merge(array(
                'default' => n2_('Slider\'s default')
            ), $this->options);
        }

        return parent::fetchElement();
    }

    /**
     * @param bool $useGlobal
     */
    public function setUseGlobal($useGlobal) {
        $this->useGlobal = $useGlobal;
    }
}