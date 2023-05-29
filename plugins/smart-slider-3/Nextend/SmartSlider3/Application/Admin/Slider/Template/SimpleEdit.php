<?php
namespace Nextend\SmartSlider3\Application\Admin\Slider;

/**
 * @var $this ViewSliderSimpleEdit
 */

use Nextend\Framework\Data\Data;
use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Select\LinkTarget;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Textarea;
use Nextend\Framework\Form\Element\Token;
use Nextend\Framework\Form\Form;
use Nextend\Framework\Sanitize;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonSave;
use Nextend\SmartSlider3\Application\Model\ModelSlides;
use Nextend\SmartSlider3\Slider\SliderParams;


$slider = $this->getSlider();

$sliderParams = new SliderParams($slider['id'], $slider['type'], $slider['params'], true);

$sliderData              = $sliderParams->toArray();
$sliderData['title']     = $slider['title'];
$sliderData['type']      = $slider['type'];
$sliderData['thumbnail'] = $slider['thumbnail'];
$sliderData['alias']     = isset($slider['alias']) ? $slider['alias'] : '';

?>
<form id="n2_slider_form" action="<?php echo esc_url($this->getUrlSliderSimpleEdit($slider['id'], $this->groupID)); ?>" method="post">
    <div id="slider-settings-region" role="region" tabindex="0" aria-label="<?php echo esc_attr(n2_('Slider settings') . ': ' . $slider['title']); ?>">
        <?php
        $form = new Form($this, 'slider');

        new Token($form->getFieldsetHidden());

        $form->loadArray($sliderData);

        $table = new ContainerTable($form->getContainer(), 'general', n2_('Slider settings'));

        $row1 = $table->createRow('general-1');

        new OnOff($row1, 'delete-slider', n2_('Delete slider'), 0);

        new Text($row1, 'title', n2_('Name'), n2_('Slider'), array(
            'style' => 'width:300px;'
        ));

        new Text($row1, 'aria-label', n2_('ARIA label'), n2_('Slider'), array(
            'style'          => 'width:200px;',
            'tipLabel'       => n2_('ARIA label'),
            'tipDescription' => n2_('It allows you to label your slider for screen readers.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1722-slider-settings-general#aria-label'
        ));

        $form->render();
        ?>
    </div>
    <?php

    $modelSlides = new ModelSlides($this);
    $slides      = $modelSlides->getAll($slider['id']);

    foreach ($slides as $slide) {
        $slideParams              = new Data($slide['params']);
        $slideData                = $slideParams->toArray();
        $slideData['ordering']    = $slide['ordering'];
        $slideData['title']       = $slide['title'];
        $slideData['description'] = $slide['description'];
        ?>
        <div role="region" tabindex="0" aria-label="<?php echo esc_attr(n2_('Edit slide') . ': ' . $slide['title']); ?>">
            <?php

            $form = new Form($this, 'slide[' . $slide['id'] . ']');

            $form->loadArray($slideData);

            $table = new ContainerTable($form->getContainer(), 'general', n2_('Slide') . ': ' . $slideData['title']);

            $row1 = $table->createRow('general-1');

            new OnOff($row1, 'delete-slide', n2_('Delete slide'), 0);

            new Text\Number($row1, 'ordering', n2_('Ordering'), 0, array(
                'wide' => 4
            ));

            new Text($row1, 'title', n2_('Slide title'), '', array(
                'style' => 'width:300px;'
            ));

            new Textarea($row1, 'description', n2_('Description'), '', array(
                'width' => 314
            ));

            new Text\FieldImage($row1, 'backgroundImage', n2_('Slide background'), '', array(
                'width' => 300
            ));


            new Select($row1, 'thumbnailType', n2_('Thumbnail type'), 'default', array(
                'options' => array(
                    'default'   => n2_('Default'),
                    'videoDark' => n2_('Video')
                )
            ));

            new Text($row1, 'href', n2_('Link'), '');
            new LinkTarget($row1, 'href-target', n2_('Target window'));

            $form->render();

            ?>
        </div>
        <?php
    }

    ?>
    <div style="margin: 20px;">
        <?php
        $buttonSave = new BlockButtonSave($this);
        $buttonSave->addClass('n2_slider_save');
        $buttonSave->display();
        ?>
    </div>
    <input type="hidden" name="save" value="1">
</form>

<script>
    _N2.r(['$', 'windowLoad'], function () {
        var $ = _N2.$;
        var $form = $('#n2_slider_form');

        $('#slider-settings-region').trigger("focus");

        $('.n2_slider_save').on('click', function (e) {
            e.preventDefault();

            $form.trigger("submit");
        });

        document.addEventListener('keydown', function (e) {
            if (e.ctrlKey || e.metaKey) {
                if (e.code === 'KeyS') { // ctrl + s
                    e.preventDefault();

                    $form.trigger("submit");
                }
            }
        }, {
            capture: true
        });
    });
</script>

<style>
    :FOCUS {
        box-shadow: 0 0 3px 1px #1d81f9 !important;
    }
</style>
