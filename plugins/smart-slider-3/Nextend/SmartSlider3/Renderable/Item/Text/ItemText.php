<?php


namespace Nextend\SmartSlider3\Renderable\Item\Text;


use Nextend\Framework\Data\Data;
use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Font;
use Nextend\Framework\Form\Element\Hidden\HiddenFont;
use Nextend\Framework\Form\Element\Hidden\HiddenStyle;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\RichTextarea;
use Nextend\Framework\Form\Element\Style;
use Nextend\Framework\Form\Fieldset;
use Nextend\SmartSlider3\Renderable\Item\AbstractItem;

class ItemText extends AbstractItem {

    protected $ordering = 2;

    protected $layerProperties = array(
        "desktopportraitleft"   => 0,
        "desktopportraittop"    => 0,
        "desktopportraitwidth"  => 400,
        "desktopportraitalign"  => "left",
        "desktopportraitvalign" => "top"
    );

    protected $fonts = array(
        'font' => array(
            'defaultName' => 'item-text-font',
            'value'       => '{"data":[{"color":"ffffffff","size":"14||px","align":"inherit"},{"color":"1890d7ff"},{"color":"1890d7ff"}]}'
        )
    );

    protected $styles = array(
        'style' => array(
            'defaultName' => 'item-text-style',
            'value'       => ''
        )
    );

    protected function isBuiltIn() {
        return true;
    }

    public function getType() {
        return 'text';
    }

    public function getTitle() {
        return n2_('Text');
    }

    public function getIcon() {
        return 'ssi_32 ssi_32--text';
    }

    public function createFrontend($id, $itemData, $layer) {
        return new ItemTextFrontend($this, $id, $itemData, $layer);
    }

    public function globalDefaultItemFontAndStyle($container) {

        $table = new ContainerTable($container, $this->getType(), $this->getTitle());
        $row1  = $table->createRow($this->getType() . '-1');

        new Font($row1, 'item-text-font', false, $this->fonts['font']['value'], array(
            'mode' => 'paragraph'
        ));

        new Style($row1, 'item-text-style', false, $this->styles['style']['value'], array(
            'mode' => 'heading'
        ));
    }

    public function getValues() {

        return parent::getValues() + array(
                'content'                => 'Lorem ipsum dolor sit amet, <a href="#">consectetur adipiscing</a> elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
                'content-tablet-enabled' => 0,
                'contenttablet'          => '',
                'content-mobile-enabled' => 0,
                'contentmobile'          => ''
            );
    }

    /**
     * @param Data $data
     */
    public function upgradeData($data) {
        if (!$data->has('content-tablet-enabled')) {
            if ($data->get('contenttablet', '') != '') {
                $data->set('content-tablet-enabled', 1);
            }
        }
        if (!$data->has('content-mobile-enabled')) {
            if ($data->get('contentmobile', '') != '') {
                $data->set('content-mobile-enabled', 1);
            }
        }
    }

    public function getFilled($slide, $data) {
        $data = parent::getFilled($slide, $data);

        $data->set('content', $slide->fill($data->get('content', '')));
        $data->set('contenttablet', $slide->fill($data->get('contenttablet', '')));
        $data->set('contentmobile', $slide->fill($data->get('contentmobile', '')));

        return $data;
    }

    public function prepareExport($export, $data) {
        parent::prepareExport($export, $data);

        $export->addVisual($data->get('font'));
        $export->addVisual($data->get('style'));
    }

    public function prepareImport($import, $data) {
        $data = parent::prepareImport($import, $data);

        $data->set('font', $import->fixSection($data->get('font')));
        $data->set('style', $import->fixSection($data->get('style')));

        return $data;
    }

    public function renderFields($container) {
        $settings = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-text', n2_('General'));

        new RichTextarea($settings, 'content', n2_('Text'), '', array(
            'fieldStyle' => 'height: 120px; width: 314px;resize: vertical;'
        ));

        new HiddenFont($settings, 'font', false, '', array(
            'mode' => 'paragraph'
        ));
        new HiddenStyle($settings, 'style', false, '', array(
            'mode' => 'heading'
        ));

        new OnOff($settings, 'content-tablet-enabled', n2_('Tablet'), 0, array(
            'relatedFieldsOn' => array(
                'item_textcontenttablet'
            ),
            'tipLabel'        => n2_('Tablet'),
            'tipDescription'  => n2_('Custom text for tablet')
        ));

        new RichTextarea($settings, 'contenttablet', n2_('Tablet text'), '', array(
            'fieldStyle' => 'height: 120px; width: 314px;resize: vertical;'
        ));

        new OnOff($settings, 'content-mobile-enabled', n2_('Mobile'), 0, array(
            'relatedFieldsOn' => array(
                'item_textcontentmobile'
            ),
            'tipLabel'        => n2_('Mobile'),
            'tipDescription'  => n2_('Custom text for mobile')
        ));

        new RichTextarea($settings, 'contentmobile', n2_('Mobile text'), '', array(
            'fieldStyle' => 'height: 120px; width: 314px;resize: vertical;'
        ));
    }
}