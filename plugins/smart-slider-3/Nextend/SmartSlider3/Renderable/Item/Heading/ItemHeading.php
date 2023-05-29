<?php


namespace Nextend\SmartSlider3\Renderable\Item\Heading;


use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Font;
use Nextend\Framework\Form\Element\Hidden\HiddenFont;
use Nextend\Framework\Form\Element\Hidden\HiddenStyle;
use Nextend\Framework\Form\Element\MixedField;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Select\LinkTarget;
use Nextend\Framework\Form\Element\Style;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Form\Element\Text\NumberAutoComplete;
use Nextend\Framework\Form\Element\Text\Url;
use Nextend\Framework\Form\Element\Textarea;
use Nextend\Framework\Form\Fieldset;
use Nextend\Framework\Parser\Common;
use Nextend\SmartSlider3\Renderable\Item\AbstractItem;
use Nextend\SmartSlider3Pro\Form\Element\SplitTextAnimation;

class ItemHeading extends AbstractItem {

    protected $ordering = 1;

    protected $fonts = array(
        'font' => array(
            'defaultName' => 'item-heading-font',
            'value'       => '{"data":[{"color":"ffffffff","size":"36||px","align":"inherit"},{"extra":""}]}'
        )
    );

    protected $styles = array(
        'style' => array(
            'defaultName' => 'item-heading-style',
            'value'       => ''
        )
    );

    protected function isBuiltIn() {
        return true;
    }

    public function getType() {
        return 'heading';
    }

    public function getTitle() {
        return n2_('Heading');
    }

    public function getIcon() {
        return 'ssi_32 ssi_32--heading';
    }

    public function createFrontend($id, $itemData, $layer) {
        return new ItemHeadingFrontend($this, $id, $itemData, $layer);
    }

    public function getValues() {

        return parent::getValues() + array(
                'priority'    => 'div',
                'fullwidth'   => 1,
                'nowrap'      => 0,
                'heading'     => n2_('Heading layer'),
                'title'       => '',
                'href'        => '#',
                'href-target' => '_self',
                'href-rel'    => '',

                'split-text-transform-origin'    => '50|*|50|*|0',
                'split-text-backface-visibility' => 1,

                'split-text-animation-in' => '',
                'split-text-delay-in'     => 0,

                'split-text-animation-out' => '',
                'split-text-delay-out'     => 0,

                'class' => ''
            );
    }

    public function upgradeData($data) {
        $linkV1 = $data->get('link', '');
        if (!empty($linkV1)) {
            list($link, $target, $rel) = array_pad((array)Common::parse($linkV1), 3, '');
            $data->un_set('link');
            if (is_array($link)) {
                $data->set('href', implode('', $link));
            } else {
                $data->set('href', $link);
            }
            $data->set('href-target', $target);
            $data->set('href-rel', $rel);
        }
    }

    public function getFilled($slide, $data) {
        $data = parent::getFilled($slide, $data);

        $data->set('heading', $slide->fill($data->get('heading', '')));
        $data->set('href', $slide->fill($data->get('href', '#|*|')));

        return $data;
    }

    public function prepareExport($export, $data) {
        parent::prepareExport($export, $data);

        $export->addVisual($data->get('font'));
        $export->addVisual($data->get('style'));
        $export->addLightbox($data->get('href'));
    }

    public function prepareImport($import, $data) {
        $data = parent::prepareImport($import, $data);

        $data->set('font', $import->fixSection($data->get('font')));
        $data->set('style', $import->fixSection($data->get('style')));
        $data->set('href', $import->fixLightbox($data->get('href')));

        return $data;
    }

    public function globalDefaultItemFontAndStyle($container) {

        $table = new ContainerTable($container, $this->getType(), $this->getTitle());
        $row1  = $table->createRow($this->getType() . '-1');

        new Font($row1, 'item-heading-font', false, $this->fonts['font']['value'], array(
            'mode' => 'hover'
        ));

        new Style($row1, 'item-heading-style', false, $this->styles['style']['value'], array(
            'mode' => 'heading'
        ));
    }

    public function renderFields($container) {
        $settings = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-heading', n2_('General'));

        new Textarea($settings, 'heading', n2_('Text'), n2_('Heading'), array(
            'width' => 314
        ));

        new Select($settings, 'priority', 'Tag', 'div', array(
            'options' => array(
                'div' => 'div',
                '1'   => 'H1',
                '2'   => 'H2',
                '3'   => 'H3',
                '4'   => 'H4',
                '5'   => 'H5',
                '6'   => 'H6'
            )
        ));

        new OnOff($settings, 'fullwidth', n2_('Full width'), 1);
        new OnOff($settings, 'nowrap', n2_('No wrap'), 0, array(
            'tipLabel'       => n2_('No wrap'),
            'tipDescription' => n2_('Prevents the text from breaking into more lines')
        ));

        $link = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-heading-link', n2_('Link'));
        new Url($link, 'href', n2_('Link'), '', array(
            'width'         => 248,
            'relatedFields' => array(
                'item_headinghref-target',
                'item_headinghref-rel'
            )
        ));
        new LinkTarget($link, 'href-target', n2_('Target window'));
        new Text($link, 'href-rel', n2_('Rel'), '', array(
            'style'          => 'width:195px;',
            'tipLabel'       => n2_('Rel'),
            'tipDescription' => sprintf(n2_('Enter the %1$s rel attribute %2$s that represents the relationship between the current document and the linked document. Multiple rel attributes can be separated with space. E.g. nofollow noopener noreferrer'), '<a href="https://www.w3schools.com/TAGS/att_a_rel.asp" target="_blank">', '</a>')
        ));

        new HiddenFont($settings, 'font', false, '', array(
            'mode' => 'hover'
        ));
        new HiddenStyle($settings, 'style', false, '', array(
            'mode' => 'heading'
        ));

    }
}