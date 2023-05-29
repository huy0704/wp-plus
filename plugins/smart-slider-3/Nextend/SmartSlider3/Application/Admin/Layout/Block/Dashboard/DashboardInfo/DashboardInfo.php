<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Dashboard\DashboardInfo;

use Nextend\Framework\Asset\Js\Js;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonDashboardInfo;
use Nextend\SmartSlider3\SmartSlider3Info;

/**
 * @var $this BlockDashboardInfo
 */

Js::addInline('new _N2.DashboardInfo();');
?>

<div class="n2_dashboard_info">
    <?php
    $info = new BlockButtonDashboardInfo($this);
    $info->display();
    ?>
    <div class="n2_dashboard_info__content">

        <div class="n2_dashboard_info__row_icon n2_dashboard_info__row_icon_version">
            <i class="ssi_24 ssi_24--circularinfo"></i>
        </div>

        <div class="n2_dashboard_info__row_content n2_dashboard_info__row_content_version">
            Smart Slider
            <?php
            echo esc_html(SmartSlider3Info::$version . '-' . SmartSlider3Info::$plan);
            ?>
        </div>
        <div class="n2_dashboard_info__row_action n2_dashboard_info__row_action_version">
            <a target="_blank" href="https://smartslider.helpscoutdocs.com/article/1746-changelog"><?php n2_e('Changelog') ?></a>
        </div>

        <?php
        $this->getRouter()
             ->setMultiSite();
        $checkForUpdateUrl = $this->getUrlUpdateDownload();
        $this->getRouter()
             ->unSetMultiSite();
        ?>
        <div class="n2_dashboard_info__row_icon n2_dashboard_info__row_icon_check_update">
            <i class="ssi_24 ssi_24--refresh"></i>
        </div>

        <div class="n2_dashboard_info__row_content n2_dashboard_info__row_content_check_update">
            <?php n2_e('Check for update'); ?>
        </div>
        <div class="n2_dashboard_info__row_action n2_dashboard_info__row_action_check_update">
            <a target="_blank" href="<?php echo esc_url($checkForUpdateUrl); ?>"><?php n2_e('Check') ?></a>
        </div>

        <?php
        ?>
    </div>
</div>
<?php
