<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Dashboard\DashboardManager\Boxes;

use Nextend\SmartSlider3\SmartSlider3Info;

/**
 * @var BlockDashboardUpgradePro $this
 */
?>
    <div class="n2_dashboard_manager_upgrade_pro">

        <div class="n2_dashboard_manager_upgrade_pro__logo">
            <i class="ssi_48 ssi_48--upgrade"></i>
        </div>

        <div class="n2_dashboard_manager_upgrade_pro__heading">
            <?php n2_e('Why upgrade to Smart Slider 3 Pro?'); ?>
        </div>

        <div class="n2_dashboard_manager_upgrade_pro__details">
            <a target="_blank" href="<?php echo esc_url(SmartSlider3Info::decorateExternalUrl('https://smartslider3.com/sample-sliders/', array('utm_source' => $this->getSource() . '-sample-sliders'))); ?>" class="n2_dashboard_manager_upgrade_pro__details_option">
                <i class="ssi_16 ssi_16--filledcheck"></i>
                <div class="n2_dashboard_manager_upgrade_pro__details_option_label"><?php echo sprintf(n2_('%d+ slider templates'), '120'); ?></div>
            </a>
            <a target="_blank" href="<?php echo esc_url(SmartSlider3Info::decorateExternalUrl('https://smartslider3.com/slide-library/', array('utm_source' => $this->getSource() . '-slide-library'))); ?>" class="n2_dashboard_manager_upgrade_pro__details_option">
                <i class="ssi_16 ssi_16--filledcheck"></i>
                <div class="n2_dashboard_manager_upgrade_pro__details_option_label"><?php n2_e('Full slide library access'); ?></div>
            </a>
            <a target="_blank" href="<?php echo esc_url(SmartSlider3Info::decorateExternalUrl('https://smartslider3.com/layers/', array('utm_source' => $this->getSource() . '-layers'))); ?>" class="n2_dashboard_manager_upgrade_pro__details_option">
                <i class="ssi_16 ssi_16--filledcheck"></i>
                <div class="n2_dashboard_manager_upgrade_pro__details_option_label"><?php echo sprintf(n2_('%d new layers'), '20'); ?></div>
            </a>
            <a target="_blank" href="<?php echo esc_url(SmartSlider3Info::decorateExternalUrl('https://smartslider3.com/features/', array('utm_source' => $this->getSource() . '-free-pro'))); ?>" class="n2_dashboard_manager_upgrade_pro__details_option">
                <i class="ssi_16 ssi_16--filledcheck"></i>
                <div class="n2_dashboard_manager_upgrade_pro__details_option_label"><?php n2_e('Extra advanced options'); ?></div>
            </a>
            <a target="_blank" href="<?php echo esc_url(SmartSlider3Info::decorateExternalUrl('https://smartslider3.com/animations-and-effects/', array('utm_source' => $this->getSource() . '-animations'))); ?>" class="n2_dashboard_manager_upgrade_pro__details_option">
                <i class="ssi_16 ssi_16--filledcheck"></i>
                <div class="n2_dashboard_manager_upgrade_pro__details_option_label"><?php n2_e('New animations & effects'); ?></div>
            </a>
            <a target="_blank" href="<?php echo esc_url(SmartSlider3Info::decorateExternalUrl('https://smartslider3.com/help/', array('utm_source' => $this->getSource() . '-support'))); ?>" class="n2_dashboard_manager_upgrade_pro__details_option">
                <i class="ssi_16 ssi_16--filledcheck"></i>
                <div class="n2_dashboard_manager_upgrade_pro__details_option_label"><?php n2_e('Lifetime update & support'); ?></div>
            </a>
        </div>

        <a href="<?php echo esc_url(SmartSlider3Info::getWhyProUrl(array('utm_source' => $this->getSource()))); ?>" target="_blank" class="n2_dashboard_manager_upgrade_pro__button">
            <?php n2_e('Upgrade to Pro'); ?>
        </a>

        <?php
        if ($this->hasDismiss()):
            ?>
            <div class="n2_dashboard_manager_upgrade_pro__close">
                <i class="ssi_16 ssi_16--remove"></i>
            </div>
        <?php
        endif;
        ?>
    </div>

    <?php
if ($this->hasDismiss()):
    ?>
    <script>
        _N2.r(['$', 'documentReady'], function () {
            var $ = _N2.$;
            var $box = $('.n2_dashboard_manager_upgrade_pro'),
                close = function () {
                    _N2.AjaxHelper
                        .ajax({
                            type: "POST",
                            url: _N2.AjaxHelper.makeAjaxUrl(_N2.AjaxHelper.getAdminUrl('ss3-admin'), {
                                nextendcontroller: 'settings',
                                nextendaction: 'dismissupgradepro'
                            }),
                            dataType: 'json'
                        });

                    $box.remove();
                };

            $box.find('.n2_dashboard_manager_upgrade_pro__close')
                .on('click', close);
        });
    </script>
<?php
endif;
?>