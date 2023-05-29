<?php

namespace Nextend\SmartSlider3\BackgroundAnimation;

use Nextend\Framework\Pattern\VisualManagerTrait;
use Nextend\SmartSlider3\BackgroundAnimation\Block\BackgroundAnimationManager\BlockBackgroundAnimationManager;

class BackgroundAnimationManager {

    use VisualManagerTrait;

    public function display() {

        $backgroundAnimationManagerBlock = new BlockBackgroundAnimationManager($this->MVCHelper);
        $backgroundAnimationManagerBlock->display();
    }
}