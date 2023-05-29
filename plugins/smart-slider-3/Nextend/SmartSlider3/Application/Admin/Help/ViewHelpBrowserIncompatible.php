<?php


namespace Nextend\SmartSlider3\Application\Admin\Help;

use Nextend\Framework\View\AbstractView;
use Nextend\SmartSlider3\Application\Admin\Layout\LayoutError;

class ViewHelpBrowserIncompatible extends AbstractView {

    public function display() {

        $this->layout = new LayoutError($this);

        $browsers = array(
            sprintf(n2_('%s or later'), 'Chrome 68'),
            sprintf(n2_('%s or later'), 'Firefox 52'),
            sprintf(n2_('%s or later'), 'Safari 10'),
            sprintf(n2_('%s or later'), 'Opera 55'),
            sprintf(n2_('%s or later'), 'Edge 18'),
        );

        $this->layout->setError(n2_('You are using an unsupported browser!'), sprintf(n2_('Smart Slider 3 does not support your current browser for editing. Supported browsers are the following: %s.'), implode(', ', $browsers)), 'https://smartslider.helpscoutdocs.com/article/1716-system-requirements');


        $this->layout->render();
    }
}