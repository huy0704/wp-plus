<?php

namespace Nextend\SmartSlider3\Application\Admin\Settings;

use Nextend\Framework\Asset\Js\Js;

/**
 * @var $this ViewSettingsFramework
 */

JS::addInline('new _N2.SettingsFramework();');
?>
<form id="n2-ss-form-settings-framework" method="post" action="<?php echo esc_url($this->getAjaxUrlSettingsFramework()); ?>">
    <?php
    $this->renderForm();
    ?>
</form>
