<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout;


use Nextend\Framework\Platform\Platform;
use Nextend\Framework\Request\Request;
use Nextend\Framework\View\AbstractLayout;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\NavBar\BlockNavBar;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;
use Nextend\SmartSlider3\SmartSlider3Info;

abstract class AbstractLayoutMenu extends AbstractLayout {

    use TraitAdminUrl;

    /** @var BlockNavBar */
    protected $header;

    protected $classes = array();

    public function __construct($view) {

        $this->header = new BlockNavBar($this);

        parent::__construct($view);

        $this->header->setLogo($this->getApplicationType()
                                    ->getLogo());
        $this->header->setSidebarLink($this->getUrlDashboard());

        $cmd = Request::$REQUEST->getVar("nextendcontroller", "sliders");
        $this->header->addMenuItem(Html::link(n2_('Go Pro'), SmartSlider3Info::getProUrlPricing(array(
            'utm_source'   => 'go-pro-button-top-menu',
            'utm_medium'   => 'smartslider-' . Platform::getName() . '-' . SmartSlider3Info::$plan,
            'utm_campaign' => SmartSlider3Info::$campaign
        )), array(
            'target' => '_blank'
        )));
        $this->header->addMenuItem(Html::link(n2_('Settings'), $this->getUrlSettingsDefault()), $cmd == "settings");
    

        $this->header->addMenuItem(Html::link(n2_('Help'), $this->getUrlHelp()), $cmd == "help");

    }

    public function addHeaderMenuItem($item) {
        $this->header->addMenuItem($item);
    }

    /**
     * @param        $label
     * @param        $icon
     * @param string $url
     *
     * @return Helper\Breadcrumb
     */
    public function addBreadcrumb($label, $icon = '', $url = '#') {

        return $this->header->addBreadcrumb($label, $icon, $url);
    }

    public function getHeader() {

        return $this->header->toHTML();
    }

    public function getClasses() {

        return $this->classes;
    }

    public function addClass($class) {

        $this->classes[] = $class;
    }
}