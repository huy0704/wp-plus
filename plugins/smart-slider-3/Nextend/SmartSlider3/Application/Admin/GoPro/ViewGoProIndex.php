<?php


namespace Nextend\SmartSlider3\Application\Admin\GoPro;

use Nextend\Framework\View\AbstractView;
use Nextend\SmartSlider3\Application\Admin\Layout\LayoutDefault;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;

class ViewGoProIndex extends AbstractView {

    use TraitAdminUrl;


    public function __construct($controller) {
        parent::__construct($controller);

    }

    public function display() {

        $this->layout = new LayoutDefault($this);

        $this->layout->addBreadcrumb(n2_('Go Pro'));

        $this->layout->addContent($this->render('Index'));

        $this->layout->render();
    }
}