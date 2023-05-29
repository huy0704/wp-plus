<?php

namespace Nextend\SmartSlider3\Application\Admin\Settings;

use Nextend\Framework\Asset\Js\Js;

/**
 * @var $this ViewSettingsFonts
 */

JS::addInline('new _N2.SettingsFonts();');

?>

<form id="n2-ss-form-settings-fonts" method="post" action="<?php echo esc_url($this->getAjaxUrlSettingsFonts()); ?>">
    <?php
    $this->renderForm();
    ?>
</form>
