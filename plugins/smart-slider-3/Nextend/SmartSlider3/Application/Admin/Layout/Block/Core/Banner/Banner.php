<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Banner;

/**
 * @var $this BlockBanner
 */

$closeUrl = $this->getCloseUrl();
?>

<div id="<?php echo esc_attr($this->getID()); ?>" class="n2_admin__banner">
    <div class="n2_admin__banner_inner">
        <img src="<?php echo esc_url($this->getImage()); ?>" alt="">
        <div class="n2_admin__banner_inner_title"><?php echo esc_attr($this->getTitle()); ?></div>
        <div class="n2_admin__banner_inner_description"><?php echo esc_attr($this->getDescription()); ?></div>
        <a class="n2_admin__banner_inner_button n2_button n2_button--big n2_button--green"
           href="<?php echo esc_url($this->getButtonHref()); ?>"
           onclick="<?php echo esc_js($this->getButtonOnclick()); ?>"
           target="_blank">
            <?php echo esc_html($this->getButtonTitle()); ?>
        </a>
    </div>
    <?php if (!empty($closeUrl)): ?>
        <div class="n2_admin__banner_close">
            <i class="ssi_16 ssi_16--remove"></i>
        </div>

        <script>
            _N2.r(['$', 'documentReady'], function () {
                var $ = _N2.$;
                var $banner = $('#<?php echo esc_html($this->getID()); ?>');

                $banner.find('.n2_admin__banner_close').on('click', function (e) {
                    e.preventDefault();

                    _N2.AjaxHelper.ajax({url: <?php echo json_encode(esc_url($closeUrl)); ?>});
                    $banner.remove();
                });
            });
        </script>
    <?php endif; ?>
</div>