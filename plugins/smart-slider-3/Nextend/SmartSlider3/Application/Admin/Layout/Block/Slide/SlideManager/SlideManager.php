<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\SlideManager;

use Nextend\Framework\Asset\Js\Js;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\SlideBox\BlockSlideBox;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\SlideManager\ActionBar\BlockActionBar;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\SlideManager\AddSlide\BlockAddSlide;
use Nextend\SmartSlider3\Application\Model\ModelSlides;
use Nextend\SmartSlider3\Slider\Feature\Optimize;
use Nextend\SmartSlider3\Slider\Slide;
use Nextend\SmartSlider3\SmartSlider3Info;

/**
 * @var $this BlockSlideManager
 */

$sliderObj = $this->getSliderObject();

$sliderType = $sliderObj->data->get('type', 'simple');

SmartSlider3Info::initLicense();

$slidesModel = new ModelSlides($this);

$slides   = $slidesModel->getAll($sliderObj->sliderId);
$optimize = new Optimize($sliderObj);

$parameters = array();
$parameters['nonce']     = wp_create_nonce('internal-linking');
$parameters['wpAjaxUrl'] = admin_url('admin-ajax.php');


$sliderEditUrl = $this->getUrlSliderEdit($sliderObj->sliderId, $this->groupID);

$options = array(
    'url'            => $this->getUrlSlidesUniversal($sliderObj->sliderId, $this->groupID),
    'ajaxUrl'        => $this->getAjaxUrlSlidesUniversal($sliderObj->sliderId, $this->groupID),
    'sliderUrl'      => $sliderEditUrl,
    'contentAjaxUrl' => $this->getAjaxUrlContentSearchContent()
);

Js::addInline('new _N2.SlidesManager(' . json_encode($options) . ', ' . json_encode($parameters) . ', ' . (defined('N2_IMAGE_UPLOAD_DISABLE') ? 1 : 0) . ", '" . $this->createAjaxUrl(array('browse/upload')) . "', 'slider" . $sliderObj->sliderId . "');");

$slideCount            = 0;
$hasPublishedGenerator = false;
foreach ($slides as $slide) {
    if ($slide['published']) {
        $slideCount++;
        if (!empty($slide['generator_id'])) {
            $hasPublishedGenerator = true;
        }
    }
}

Js::addGlobalInline('document.documentElement.setAttribute("data-slides", "' . count($slides) . '");');
Js::addGlobalInline('document.documentElement.setAttribute("data-published-regular-slides", "' . $slideCount . '");');
?>

<script>
    <?php
    if($this->hasBreadcrumbOpener()):
    ?>
    _N2.r(['$', 'documentReady'], function () {
        var $ = _N2.$;
        var isVisible = false,
            $editorOverLay = $('.n2_admin_editor_overlay'),
            toggle = function () {
                isVisible = !isVisible;
                $editorOverLay.toggleClass('n2_admin_editor_overlay--show-slides', isVisible);
            },
            hide = function () {
                isVisible = true;
                toggle();
            },
            $slideManager = $('.n2_slide_manager');

        $('.n2_nav_bar__breadcrumb_button_slides').on('click', toggle);
        $slideManager.find('.n2_slide_manager__exit').on('click', hide);
    });
    <?php
    endif;
    ?>
</script>

<div class="<?php echo esc_attr($this->getClass()); ?>" data-breadcrumbopener="<?php echo $this->hasBreadcrumbOpener() ? 1 : 0; ?>">
    <div class="n2_slide_manager__inner">
        <?php

        $addSlide = new BlockAddSlide($this);
        $addSlide->setGroupID($this->groupID);
        $addSlide->setSliderID($sliderObj->sliderId);
        $addSlide->display();

        $actionBar = new BlockActionBar($this);
        $actionBar->display();

        ?>
        <div class="n2_slide_manager__content">

            <div class="n2_slide_manager__box n2_slide_manager__add_slide">
                <i class="n2_slide_manager__add_slide_icon ssi_48 ssi_48--plus"></i>
                <div class="n2_slide_manager__add_slide_label n2_slide_manager__add_slide_label--add-slide">
                    <?php n2_e('Add slide'); ?>
                </div>
                <div class="n2_slide_manager__add_slide_label n2_slide_manager__add_slide_label--close">
                    <?php n2_e('Close'); ?>
                </div>
            </div>

            <?php

            if ($sliderType == 'block'):
                ?>
                <div class="n2_slide_manager__box n2_slide_manager__block_notice">
                    <div class="n2_slide_box__footer_title n2_slide_manager__block_notice_description">
                        <?php n2_e('Block must contain only one slide. Need more?'); ?>
                    </div>
                    <a class="n2_slide_manager__block_notice_button" href="<?php echo esc_url($sliderEditUrl); ?>#changeslidertype">
                        <?php n2_e('Convert to slider'); ?>
                    </a>
                </div>
            <?php
            elseif (!$hasPublishedGenerator):
                ?>
                <div class="n2_slide_manager__box n2_slide_manager__autoplay_notice n2_form_element--hidden" data-field="autoplay-single-slide-notice">
                    <div class="n2_slide_box__footer_title n2_slide_manager__autoplay_notice_description">
                        <?php n2_e('Single slides are duplicated while autoplay is used.'); ?>
                    </div>
                    <a class="n2_slide_manager__autoplay_notice_button" href="#n2_top_bar_main_1">
                        <?php n2_e('autoplay settings'); ?>
                    </a>
                </div>
            <?php
            endif;

            $slidesObj = array();
            foreach ($slides as $i => $slide) {
                $slidesObj[$i] = new Slide($sliderObj, $slide);
                $slidesObj[$i]->initGenerator();
            }

            foreach ($slidesObj as $slideObj) {
                $slideObj->fillSample();

                $blockSlideBox = new BlockSlideBox($this);

                $blockSlideBox->setGroupID($this->groupID);
                $blockSlideBox->setSlider($sliderObj);
                $blockSlideBox->setSlide($slideObj);
                $blockSlideBox->setOptimize($optimize);

                $blockSlideBox->display();
            }
            ?>
            <div class="n2_slide_manager__box n2_slide_manager__dummy_slide">
                <i class="n2_slide_manager__dummy_slide_icon ssi_48 ssi_48--image"></i>
                <div class="n2_slide_manager__dummy_slide_label">
                    <?php n2_e('Slide one'); ?>
                </div>
            </div>
            <?php if ($sliderType != 'block'): ?>
                <div class="n2_slide_manager__box n2_slide_manager__dummy_slide">
                    <i class="n2_slide_manager__dummy_slide_icon ssi_48 ssi_48--image"></i>
                    <div class="n2_slide_manager__dummy_slide_label">
                        <?php n2_e('Slide two'); ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="n2_slide_manager__box n2_slide_manager__dummy_slide">
                <i class="n2_slide_manager__dummy_slide_icon ssi_48 ssi_48--drop"></i>
                <div class="n2_slide_manager__dummy_slide_label">
                    <?php n2_e('Drop images here'); ?>
                </div>
            </div>
        </div>
    </div>
    <?php if ($this->hasBreadcrumbOpener()): ?>
        <div class="n2_slide_manager__exit"></div>
    <?php endif; ?>
</div>
