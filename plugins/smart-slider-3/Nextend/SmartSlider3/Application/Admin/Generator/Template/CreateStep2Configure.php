<?php

namespace Nextend\SmartSlider3\Application\Admin\Generator;

use Nextend\Framework\Asset\Js\Js;

/**
 * @var ViewGeneratorCreateStep2Configure $this
 */

JS::addInline('new _N2.GeneratorConfigure();');
?>
<form id="n2-ss-form-generator-configure" action="<?php echo esc_url($this->getAjaxUrlGeneratorCheckConfiguration($this->getGeneratorGroup()
                                                                                                                       ->getName(), $this->getSliderID(), $this->getGroupID())); ?>" method="post">
    <?php
    $this->renderForm();
    ?>
</form>