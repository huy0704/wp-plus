<?php

namespace Nextend\SmartSlider3\Application\Admin\Settings;


use Nextend\Framework\Asset\Js\Js;

/**
 * @var $this ViewSettingsGeneral
 */

JS::addInline('new _N2.SettingsGeneral();');
?>

<form id="n2-ss-form-settings-general" action="<?php echo esc_url($this->getAjaxUrlSettingsDefault()); ?>" method="post">
    <?php
    $this->renderForm();
    ?>
</form>