<?php


namespace Nextend\SmartSlider3\Renderable\Item\Image;


use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Hidden;
use Nextend\Framework\Form\Element\MixedField;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select\LinkTarget;
use Nextend\Framework\Form\Element\Style;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Text\FieldImage;
use Nextend\Framework\Form\Element\Text\Url;
use Nextend\Framework\Form\Fieldset;
use Nextend\Framework\Parser\Common;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\SmartSlider3\Renderable\Item\AbstractItem;

class ItemImage extends AbstractItem {

    protected $ordering = 3;

    protected $layerProperties = array("desktopportraitwidth" => "300");

    protected $styles = array(
        'style' => array(
            'defaultName' => 'item-image-style',
            'value'       => ''
        )
    );

    protected function isBuiltIn() {
        return true;
    }

    public function getType() {
        return 'image';
    }

    public function getTitle() {
        return n2_('Image');
    }

    public function getIcon() {
        return 'ssi_32 ssi_32--image';
    }

    public function createFrontend($id, $itemData, $layer) {
        return new ItemImageFrontend($this, $id, $itemData, $layer);
    }

    public function globalDefaultItemFontAndStyle($container) {

        $table = new ContainerTable($container, $this->getType(), $this->getTitle());
        $row1  = $table->createRow($this->getType() . '-1');

        new Style($row1, 'item-image-style', false, $this->styles['style']['value'], array(
            'mode' => 'box'
        ));
    }

    public function getValues() {

        return parent::getValues() + array(
                'image'          => '$ss3-frontend$/images/placeholder/image.png',
                'alt'            => '',
                'title'          => '',
                'href'           => '#',
                'href-target'    => '_self',
                'href-rel'       => '',
                'href-class'     => '',
                'size'           => 'auto|*|auto',
                'cssclass'       => '',
                'image-optimize' => 1
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

        $data->set('image', $slide->fill($data->get('image', '')));
        $data->set('alt', $slide->fill($data->get('alt', '')));
        $data->set('title', $slide->fill($data->get('title', '')));
        $data->set('href', $slide->fill($data->get('href', '#|*|')));

        return $data;
    }

    public function prepareExport($export, $data) {
        parent::prepareExport($export, $data);

        $export->addImage($data->get('image'));
        $export->addVisual($data->get('style'));
        $export->addLightbox($data->get('href'));
    }

    public function prepareImport($import, $data) {
        $data = parent::prepareImport($import, $data);

        $data->set('image', $import->fixImage($data->get('image')));
        $data->set('style', $import->fixSection($data->get('style')));
        $data->set('href', $import->fixLightbox($data->get('href')));

        return $data;
    }

    public function prepareSample($data) {
        $data->set('image', ResourceTranslator::toUrl($data->get('image')));

        return $data;
    }

    public function renderFields($container) {
        $settings = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-image', n2_('General'));

        new FieldImage($settings, 'image', n2_('Image'), '', array(
            'relatedAlt' => 'item_imagealt',
            'width'      => 220
        ));

        $link = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-image-link', n2_('Link'));
        new Url($link, 'href', n2_('Link'), '', array(
            'style'         => 'width:236px;',
            'relatedFields' => array(
                'item_imagehref-target',
                'item_imagehref-rel'
            ),
            'width'         => 248
        ));
        new LinkTarget($link, 'href-target', n2_('Target window'));
        new Text($link, 'href-rel', n2_('Rel'), '', array(
            'style'          => 'width:195px;',
            'tipLabel'       => n2_('Rel'),
            'tipDescription' => sprintf(n2_('Enter the %1$s rel attribute %2$s that represents the relationship between the current document and the linked document. Multiple rel attributes can be separated with space. E.g. nofollow noopener noreferrer'), '<a href="https://www.w3schools.com/TAGS/att_a_rel.asp" target="_blank">', '</a>')
        ));

        $size = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-image-misc', n2_('Size'));
        $misc = new MixedField($size, 'size', false, 'auto|*|auto');
        new Text($misc, 'size-1', n2_('Width'), '', array(
            'style'          => 'width:60px;',
            'tipLabel'       => n2_('Width'),
            'tipDescription' => sprintf(n2_('Fix width for the %1$s.'), $this->getTitle())
        ));
        new Text($misc, 'size-2', n2_('Height'), '', array(
            'style'          => 'width:60px;',
            'tipLabel'       => n2_('Height'),
            'tipDescription' => sprintf(n2_('Fix height for the %1$s.'), $this->getTitle())
        ));

        $seo = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-image-seo', n2_('SEO'));
        new Text($seo, 'alt', 'SEO - ' . n2_('Alt tag'), '', array(
            'style' => 'width:133px;'
        ));
        new Text($seo, 'title', 'SEO - ' . n2_('Title'), '', array(
            'style' => 'width:133px;'
        ));

        $dev = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-image-dev', n2_('Advanced'));
        new Text($dev, 'href-class', n2_('CSS Class') . ' - ' . n2_('Link'), '', array(
            'tipLabel'       => n2_('CSS Class'),
            'tipDescription' => sprintf(n2_('Class on the %s element.'), '&lt;a&gt;'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1833-image-layer#advanced',
            'style'          => 'width:133px;'
        ));
    }
}