<?php

namespace Nextend\SmartSlider3\Application\Admin\Generator;

use Nextend\SmartSlider3\Application\Admin\Layout\Block\Generator\GeneratorBox\BlockGeneratorBox;

/**
 * @var ViewGeneratorCreateStep3Sources $this
 */

$generatorGroup = $this->getGeneratorGroup();

?>
<div class="n2_slide_generator_step3">
    <?php

    foreach ($generatorGroup->getSources() as $source) {

        $blockGeneratorBox = new BlockGeneratorBox($this);
        $blockGeneratorBox->setImageUrl($generatorGroup->getImageUrl());
        $blockGeneratorBox->setLabel($source->getLabel());
        $blockGeneratorBox->setButtonLink($this->getUrlGeneratorCreateSettings($generatorGroup->getName(), $source->getName(), $this->getSliderID(), $this->groupID));
        $blockGeneratorBox->setButtonLabel(n2_('Choose'));
        $blockGeneratorBox->setDescription($source->getDescription());

        $blockGeneratorBox->display();

    }
    ?>
</div>