<?php

namespace Nextend\SmartSlider3\Application\Admin\Generator;

use Nextend\Framework\Asset\Js\Js;
use Nextend\SmartSlider3\Settings;

/**
 * @var ViewGeneratorEdit $this
 */

$generator       = $this->getGenerator();
$generatorGroup  = $this->getGeneratorGroup();
$generatorSource = $this->getGeneratorSource();

JS::addInline('new _N2.GeneratorEdit(' . json_encode(array(
        'previewInNewWindow' => !!Settings::get('preview-new-window', 0),
        'previewUrl'         => $this->getUrlPreviewGenerator($generator['id'])
    )) . ');');

?>
<form id="n2-ss-form-generator-edit" action="<?php echo esc_url($this->getAjaxUrlGeneratorEdit($generator['id'], $this->getGroupID())); ?>" method="post">
    <?php
    $this->renderForm();
    ?>
    <input name="generator[group]" value="<?php echo esc_attr($generatorGroup->getName()); ?>" type="hidden">
    <input name="generator[type]" value="<?php echo esc_attr($generatorSource->getName()); ?>" type="hidden">
    <input name="slider-id" value="<?php echo esc_attr($this->getSliderID()); ?>" type="hidden">
</form>