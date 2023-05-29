<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\TopBarMain\TopBarMainEditor;


use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\TopBarMain\BlockTopBarMain;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;

class BlockTopBarMainEditor extends BlockTopBarMain {

    use TraitAdminUrl;

    public function display() {

        $this->renderTemplatePart('TopBarMainEditor');
    }
}