<?php

namespace Nextend\SmartSlider3\Application\Admin\Settings;

use Nextend\Framework\Asset\Js\Js;

/**
 * @var ViewGeneratorConfigure $this
 */

JS::addInline('new _N2.GeneratorConfigure();');
?>
<form id="n2-ss-form-generator-configure" action="<?php echo esc_url($this->getAjaxUrlSettingsGenerator($this->getGeneratorGroup()
                                                                                                             ->getName())); ?>" method="post">
    <?php
    $this->renderForm();
    ?>
</form>

<div style="height: 200px"></div>