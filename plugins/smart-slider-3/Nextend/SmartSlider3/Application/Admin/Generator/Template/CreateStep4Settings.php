<?php

namespace Nextend\SmartSlider3\Application\Admin\Generator;

use Nextend\Framework\Asset\Js\Js;

/**
 * @var ViewGeneratorCreateStep4Settings $this
 */

$generatorGroup  = $this->getGeneratorGroup();
$generatorSource = $this->getGeneratorSource();

JS::addInline('new _N2.GeneratorAdd();');
?>

<form id="n2-ss-form-generator-add" action="<?php echo esc_url($this->getAjaxUrlGeneratorCreateSettings($this->getGeneratorGroup()
                                                                                                             ->getName(), $this->getGeneratorSource()
                                                                                                                               ->getName(), $this->getSliderID(), $this->getGroupID())); ?>" method="post">
    <?php

    $this->displayForm();
    ?>
    <input name="generator[group]" value="<?php echo esc_attr($generatorGroup->getName()); ?>" type="hidden">
    <input name="generator[type]" value="<?php echo esc_attr($generatorSource->getName()); ?>" type="hidden">
    <input name="slider-id" value="<?php echo esc_attr($this->getSliderID()); ?>" type="hidden">
</form>