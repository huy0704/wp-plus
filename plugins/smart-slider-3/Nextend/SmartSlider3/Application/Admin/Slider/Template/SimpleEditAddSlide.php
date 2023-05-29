<?php
namespace Nextend\SmartSlider3\Application\Admin\Slider;

use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Select\LinkTarget;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Textarea;
use Nextend\Framework\Form\Element\Token;
use Nextend\Framework\Form\Form;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonSave;

/**
 * @var $this ViewSliderSimpleEditAddSlide
 */

$slider = $this->getSlider();

?>
<form id="n2_slider_add_slide_form" action="<?php echo esc_url($this->getUrlSliderSimpleEditAddSlide($slider['id'], $this->groupID)); ?>" method="post">
    <div id="slider-add-slide-region" role="region" tabindex="0" aria-label="<?php n2_e('Add slide'); ?>">
        <?php
        $form = new Form($this, 'slide');

        new Token($form->getFieldsetHidden());

        $table = new ContainerTable($form->getContainer(), 'general', n2_('Add slide'));

        $row1 = $table->createRow('general-1');

        new Text($row1, 'title', n2_('Slide title'), '', array(
            'style' => 'width:300px;'
        ));

        new Textarea($row1, 'description', n2_('Description'), '', array(
            'width' => 314
        ));

        new Text\FieldImage($row1, 'backgroundImage', n2_('Slide background'), '', array(
            'width' => 300
        ));

        new Text($row1, 'video', n2_('Video url'), '', array(
            'style' => 'width:300px;'
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
    <div style="margin: 20px;">
        <?php
        $buttonSave = new BlockButtonSave($this);
        $buttonSave->setLabel(n2_('Add slide'));
        $buttonSave->addClass('n2_slider_add_slide');
        $buttonSave->display();
        ?>
    </div>
    <input type="hidden" name="save" value="1">
</form>

<script>
    _N2.r(['$', 'windowLoad'], function () {
        var $ = _N2.$;
        var $form = $('#n2_slider_add_slide_form');

        $('#slider-add-slide-region').trigger("focus");

        $('.n2_slider_add_slide').on('click', function (e) {
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
