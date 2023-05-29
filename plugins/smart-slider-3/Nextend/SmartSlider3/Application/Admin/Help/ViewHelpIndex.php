<?php


namespace Nextend\SmartSlider3\Application\Admin\Help;

use Nextend\Framework\View\AbstractView;
use Nextend\SmartSlider3\Application\Admin\Layout\LayoutDefault;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;
use Nextend\SmartSlider3\Conflict\Conflict;

class ViewHelpIndex extends AbstractView {

    use TraitAdminUrl;

    /** @var Conflict */
    protected $conflict;

    public function __construct($controller) {
        parent::__construct($controller);

        $this->conflict = Conflict::getInstance();
    }

    public function display() {

        $this->layout = new LayoutDefault($this);

        $this->layout->addBreadcrumb(n2_('Help center'), '', $this->getUrlHelp());

        $this->layout->addContent($this->render('Index'));

        $this->layout->render();
    }

    public function getConflicts() {

        return $this->conflict->getConflicts();
    }

    public function getDebugConflicts() {

        return $this->conflict->getDebugConflicts();
    }

    public function getCurlLog() {

        return $this->conflict->getCurlLog();
    }

    /**
     * @return array
     */
    public function getArticles() {
        $arr = array(
            array(
                'url'   => 'https://smartslider.helpscoutdocs.com/article/1923-free-vs-pro',
                'label' => 'Free vs Pro'
            ),
            array(
                'url'   => 'https://smartslider.helpscoutdocs.com/article/1918-upgrading-from-free-to-pro',
                'label' => 'How to update to the Pro version?'
            )
        );
    

        return array_merge($arr, array(
            array(
                'url'   => 'https://smartslider.helpscoutdocs.com/article/1916-slide-editing-in-smart-slider-3#why-is-the-slider-so-tall-on-mobile',
                'label' => 'Why is the slider tall on mobile?'
            ), 
            array(
                'url'   => 'https://smartslider.helpscoutdocs.com/article/1916-slide-editing-in-smart-slider-3',
                'label' => 'Slide editing in Smart Slider 3'
            ),
            array(
                'url'   => 'https://smartslider.helpscoutdocs.com/article/1922-how-to-set-your-background-image#cropped',
                'label' => 'Why are my images cropped?'
            ),
            array(
                'url'   => 'https://smartslider.helpscoutdocs.com/article/1924-how-to-add-a-video',
                'label' => 'How can I add a video?'
            ),
            array(
                'url'   => 'https://smartslider.helpscoutdocs.com/article/1807-slider-settings-autoplay',
                'label' => 'Where is the autoplay?'
            ),
            array(
                'url'   => 'https://smartslider.helpscoutdocs.com/article/1919-video-autoplay-handling',
                'label' => 'Why isn\'t my video autoplaying?'
            ),
            array(
                'url'   => 'https://smartslider.helpscoutdocs.com/article/1925-how-to-speed-up-your-site',
                'label' => 'How can I speed up my site?'
            ),
            array(
                'url'   => 'https://smartslider.helpscoutdocs.com/category/1699-publishing',
                'label' => 'How can I publish my sliders?'
            ),
            array(
                'url'   => 'https://smartslider.helpscoutdocs.com/article/1828-using-your-own-fonts',
                'label' => 'How to use different fonts in the slider?'
            ),
            array(
                'url'   => 'https://smartslider.helpscoutdocs.com/article/1725-dynamic-slide-basics',
                'label' => 'What is a dynamic slide?'
            ),
            array(
                'url'   => 'https://smartslider.helpscoutdocs.com/collection/1712-troubleshooting',
                'label' => 'Troubleshooting'
            )
        ));
    }
}