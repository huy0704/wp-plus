<?php

namespace Nextend\SmartSlider3\Application\Admin\Generator;


use Nextend\SmartSlider3\Application\Admin\Layout\Block\Generator\GeneratorBox\BlockGeneratorBox;
use Nextend\SmartSlider3\Generator\AbstractGeneratorGroup;

/**
 * @var ViewGeneratorCreateStep1Groups $this
 */

$generatorGroups = $this->getGeneratorGroups();

/** @var AbstractGeneratorGroup[] $installed */
$installed = array();

/** @var AbstractGeneratorGroup[] $notInstalled */
$notInstalled = array();

foreach ($generatorGroups as $generatorGroup) {

    if (!$generatorGroup->isDeprecated()) {
        if ($generatorGroup->isInstalled()) {
            $installed[] = $generatorGroup;
        } else {
            $notInstalled[] = $generatorGroup;
        }
    }
}

?>

<div class="n2_slide_generator_step1">
    <div class="n2_slide_generator_step1__installed_generators">
        <?php

        foreach ($installed as $generatorGroup) {

            $blockGeneratorBox = new BlockGeneratorBox($this);
            $blockGeneratorBox->setImageUrl($generatorGroup->getImageUrl());
            $blockGeneratorBox->setLabel($generatorGroup->getLabel());
            $blockGeneratorBox->setButtonLabel(n2_('Choose'));
            $blockGeneratorBox->setDescription($generatorGroup->getDescription());
            $blockGeneratorBox->setDocsLink($generatorGroup->getDocsLink());

            if ($generatorGroup->hasConfiguration()) {
                $url = $this->getUrlGeneratorCheckConfiguration($generatorGroup->getName(), $this->getSliderID(), $this->groupID);
            } else {
                $url = $this->getUrlGeneratorCreateStep2($generatorGroup->getName(), $this->getSliderID(), $this->groupID);
            }
            $blockGeneratorBox->setButtonLink($url);

            $blockGeneratorBox->display();
        }
        ?>
    </div>

    <?php if (!empty($notInstalled)): ?>
        <div class="n2_slide_generator_step1__not_installed">
            <div class="n2_slide_generator_step1__not_installed_label">
                <?php n2_e('Not installed'); ?>
            </div>
            <div class="n2_slide_generator_step1__not_installed_generators">
                <?php
                foreach ($notInstalled as $generatorGroup) {
                    $blockGeneratorBox = new BlockGeneratorBox($this);
                    $blockGeneratorBox->setImageUrl($generatorGroup->getImageUrl());
                    $blockGeneratorBox->setLabel($generatorGroup->getLabel());
                    $blockGeneratorBox->setButtonLabel(n2_('Visit'));
                    $blockGeneratorBox->setButtonLinkTarget('_blank');
                    $blockGeneratorBox->setButtonLink($generatorGroup->getUrl());
                    $blockGeneratorBox->setDescription($generatorGroup->getDescription());

                    $blockGeneratorBox->display();
                }
                ?>
            </div>
        </div>
    <?php endif; ?>
</div>