<?php

namespace Nextend\SmartSlider3\SlideBuilder;

class BuilderComponentSlide extends AbstractBuilderComponent {

    protected $data = array(
        'title'                  => '',
        'published'              => 1,
        'first'                  => 0,
        'description'            => '',
        'thumbnail'              => '',
        'ordering'               => 0,
        'generator_id'           => 0,
        "static-slide"           => 0,
        "backgroundColor"        => "ffffff00",
        "backgroundImage"        => "",
        "backgroundImageOpacity" => 100,
        "backgroundAlt"          => "",
        "backgroundTitle"        => "",
        "backgroundMode"         => "default",
        "backgroundVideoMp4"     => "",
        "backgroundVideoOpacity" => 100,
        "backgroundVideoLoop"    => 1,
        "backgroundVideoReset"   => 1,
        "backgroundVideoMode"    => "fill",
        "href"                   => "",
        "href-target"            => "",
        "slide-duration"         => 0,
        "desktopportraitpadding" => '10|*|10|*|10|*|10'
    );

    /** @var AbstractBuilderComponent[] */
    private $layers = array();

    /** @var BuilderComponentContent */
    public $content;

    public function __construct($properties = array()) {
        foreach ($properties as $k => $v) {
            $this->data[$k] = $v;
        }

        $this->content = new BuilderComponentContent($this);
    }

    /**
     * @param $layer AbstractBuilderComponent
     */
    public function add($layer) {
        array_unshift($this->layers, $layer);
    }

    public function getData() {
        $this->data['layers'] = array();
        foreach ($this->layers as $layer) {
            $this->data['layers'][] = $layer->getData();
        }

        return parent::getData();
    }

    public function getLayersData() {
        $data = $this->getData();

        return $data['layers'];
    }
}