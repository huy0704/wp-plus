<?php


namespace Nextend\SmartSlider3\Renderable\Item\Button;


use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Font;
use Nextend\Framework\Form\Element\Hidden\HiddenFont;
use Nextend\Framework\Form\Element\Hidden\HiddenStyle;
use Nextend\Framework\Form\Element\Icon;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Select\LinkTarget;
use Nextend\Framework\Form\Element\Style;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Text\NumberSlider;
use Nextend\Framework\Form\Element\Text\Url;
use Nextend\Framework\Form\Fieldset;
use Nextend\Framework\Parser\Common;
use Nextend\SmartSlider3\Renderable\Item\AbstractItem;

class ItemButton extends AbstractItem {

    protected $ordering = 4;

    protected $fonts = array(
        'font' => array(
            'defaultName' => 'item-button-font',
            'value'       => '{"data":[{"color":"ffffffff","size":"14||px","align":"center"}, {"extra":""}]}'
        )
    );

    protected $styles = array(
        'style' => array(
            'defaultName' => 'item-button-style',
            'value'       => '{"data":[{"backgroundcolor":"5cba3cff","padding":"10|*|30|*|10|*|30|*|px"}, {"extra":""}]}'
        )
    );

    protected function isBuiltIn() {
        return true;
    }

    public function getType() {
        return 'button';
    }

    public function getTitle() {
        return n2_('Button');
    }

    public function getIcon() {
        return 'ssi_32 ssi_32--button';
    }

    public function createFrontend($id, $itemData, $layer) {
        return new ItemButtonFrontend($this, $id, $itemData, $layer);
    }

    public function globalDefaultItemFontAndStyle($container) {

        $table = new ContainerTable($container, $this->getType(), $this->getTitle());
        $row1  = $table->createRow($this->getType() . '-1');

        new Font($row1, 'item-button-font', false, $this->fonts['font']['value'], array(
            'mode' => 'link'
        ));

        new Style($row1, 'item-button-style', false, $this->styles['style']['value'], array(
            'mode' => 'button'
        ));
    }

    public function getValues() {

        return parent::getValues() + array(
                'content'       => n2_x('MORE', 'Button layer default text'),
                'nowrap'        => 1,
                'fullwidth'     => 0,
                'href'          => '#',
                'href-target'   => '_self',
                'href-rel'      => '',
                'class'         => '',
                'icon'          => '',
                'iconsize'      => '100',
                'iconspacing'   => '30',
                'iconplacement' => 'left',
            );
    }

    public function upgradeData($data) {
        $linkV1 = $data->get('link', '');
        if (!empty($linkV1)) {
            list($link, $target, $rel) = array_pad((array)Common::parse($linkV1), 3, '');
            $data->un_set('link');
            $data->set('href', $link);
            $data->set('href-target', $target);
            $data->set('href-rel', $rel);
        }
    }

    public function getFilled($slide, $data) {
        $data = parent::getFilled($slide, $data);

        $data->set('content', $slide->fill($data->get('content', '')));
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

    public function loadResources($renderable) {
        parent::loadResources($renderable);

        $renderable->addLess(self::getAssetsPath() . "/button.n2less", array(
            "sliderid" => $renderable->elementId
        ));
    }

    public function renderFields($container) {
        $settings = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-button', n2_('General'));

        new Text($settings, 'content', n2_('Label'), n2_('Button'), array(
            'style' => 'width:302px;'
        ));
        new HiddenFont($settings, 'font', false, '', array(
            'mode' => 'link'
        ));
        new HiddenStyle($settings, 'style', false, '', array(
            'mode' => 'button'
        ));

        new OnOff($settings, 'fullwidth', n2_('Full width'), 1);
        new OnOff($settings, 'nowrap', n2_('No wrap'), 1, array(
            'tipLabel'       => n2_('No wrap'),
            'tipDescription' => n2_('Prevents the text from breaking into more lines')
        ));

        $link = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-button-link', n2_('Link'));
        new Url($link, 'href', n2_('Link'), '', array(
            'relatedFields' => array(
                'item_buttonhref-target',
                'item_buttonhref-rel'
            ),
            'width'         => 248
        ));
        new LinkTarget($link, 'href-target', n2_('Target window'));
        new Text($link, 'href-rel', n2_('Rel'), '', array(
            'style'          => 'width:195px;',
            'tipLabel'       => n2_('Rel'),
            'tipDescription' => sprintf(n2_('Enter the %1$s rel attribute %2$s that represents the relationship between the current document and the linked document. Multiple rel attributes can be separated with space. E.g. nofollow noopener noreferrer'), '<a href="https://www.w3schools.com/TAGS/att_a_rel.asp" target="_blank">', '</a>')
        ));
    }
}