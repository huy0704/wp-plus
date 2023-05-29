<?php


namespace Nextend\SmartSlider3\Application\Admin\Sliders;


use Nextend\Framework\View\AbstractView;
use Nextend\SmartSlider3\Application\Admin\Layout\LayoutDefault;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;

class ViewSlidersGettingStarted extends AbstractView {

    use TraitAdminUrl;

    public function display() {

        $this->layout = new LayoutDefault($this);

        $this->layout->addContent($this->render('GettingStarted'));

        $this->layout->render();
    }
}