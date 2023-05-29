<?php


namespace Nextend\SmartSlider3\Renderable\Component;


class Style {

    public $styles = array(
        'all'              => array(),
        'desktoplandscape' => array(),
        'tabletlandscape'  => array(),
        'tabletportrait'   => array(),
        'mobilelandscape'  => array(),
        'mobileportrait'   => array(),

    );

    /**
     * @var AbstractComponent
     */
    protected $component;

    /**
     * Style constructor.
     *
     * @param AbstractComponent $component
     */
    public function __construct($component) {
        $this->component = $component;
    }

    public function add($device, $selector, $css) {

        if (!empty($css)) {

            if ($device == 'desktopportrait') {
                $device = 'all';
            }

            $this->addOnly($device, $selector, $css);
        }
    }

    public function addOnly($device, $selector, $css) {

        if (!empty($css)) {

            if (!isset($this->styles[$device][$selector])) {
                $this->styles[$device][$selector] = array();
            }

            $this->styles[$device][$selector][] = $css;
        }
    }

}