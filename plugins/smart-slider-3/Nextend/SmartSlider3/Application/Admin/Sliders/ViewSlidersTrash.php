<?php


namespace Nextend\SmartSlider3\Application\Admin\Sliders;


use Nextend\Framework\View\AbstractView;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\TopBarMain\BlockTopBarMain;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButton;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonBack;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slider\SliderTrash\BlockSliderTrash;
use Nextend\SmartSlider3\Application\Admin\Layout\LayoutDefault;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;

class ViewSlidersTrash extends AbstractView {

    use TraitAdminUrl;

    public function display() {

        $this->layout = new LayoutDefault($this);

        $this->layout->addBreadcrumb(n2_('Trash'), 'ssi_16 ssi_16--delete', $this->getUrlTrash());

        $topBar = new BlockTopBarMain($this);

        $buttonEmptyTrash = new BlockButton($this);
        $buttonEmptyTrash->setLabel(n2_('Empty trash'));
        $buttonEmptyTrash->setBig();
        $buttonEmptyTrash->setRed();
        $buttonEmptyTrash->addClass('n2_slider_empty_trash');
        $buttonEmptyTrash->addClass('n2_button--inactive');
        $topBar->addPrimaryBlock($buttonEmptyTrash);


        $buttonBack = new BlockButtonBack($this);
        $buttonBack->setUrl($this->getUrlDashboard());
        $buttonBack->addClass('n2_slider_settings_back');
        $topBar->addPrimaryBlock($buttonBack);

        $this->layout->setTopBar($topBar->toHTML());

        $this->displaySliderTrash();

        $this->layout->render();
    }

    protected function displaySliderTrash() {

        $sliderManager = new BlockSliderTrash($this);
        $this->layout->addContentBlock($sliderManager);
    }
}