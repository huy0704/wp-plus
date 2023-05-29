<?php


namespace Nextend\SmartSlider3\SlideBuilder;

class BuilderComponentCol extends AbstractBuilderComponent {

    protected $defaultData = array(
        "type"                   => 'col',
        "name"                   => 'Column',
        "colwidth"               => '1/1',
        "layers"                 => array(),
        "desktopportraitpadding" => '10|*|10|*|10|*|10|*|px'
    );

    /** @var AbstractBuilderComponent[] */
    private $layers = array();

    /**
     *
     * @param BuilderComponentRow                $container
     * @param                                    $width
     */
    public function __construct($container, $width = '1/1') {

        $this->defaultData['colwidth'] = $width;

        $container->add($this);
    }

    /**
     * @param $layer AbstractBuilderComponent
     */
    public function add($layer) {
        $this->layers[] = $layer;
    }

    public function getData() {
        $this->data['layers'] = array();
        foreach ($this->layers as $layer) {
            $this->data['layers'][] = $layer->getData();
        }

        return parent::getData();
    }
}