<?php


namespace Nextend\SmartSlider3\Slider\Feature;


use Nextend\Framework\Data\Data;
use Nextend\Framework\Parser\Common;
use Nextend\SmartSlider3\Slider\Slider;

#[\AllowDynamicProperties]
class Autoplay {

    /**
     * @var Slider
     */
    private $slider;

    public $isEnabled = 0, $isStart = 0, $duration = 8000;
    public $isLoop = 1, $interval = 1, $intervalModifier = 'loop', $intervalSlide = 'current', $allowReStart = 0;
    public $stopOnClick = 1, $stopOnMediaStarted = 1;
    public $resumeOnMediaEnded = 1, $resumeOnSlideChanged = 0;


    public function __construct($slider) {

        $this->slider = $slider;
        $params       = $slider->params;

        $this->isEnabled = intval($params->get('autoplay', 0));
        $this->isStart   = intval($params->get('autoplayStart', 1));
        $this->duration  = intval($params->get('autoplayDuration', 8000));

        if ($this->duration < 1) {
            $this->duration = 1500;
        }

        list($this->interval, $this->intervalModifier, $this->intervalSlide) = (array)Common::parse($slider->params->get('autoplayfinish', '1|*|loop|*|current'));

        $this->allowReStart = intval($params->get('autoplayAllowReStart', 0));

        $this->isLoop = intval($params->get('autoplayLoop', 1));

        $this->interval = intval($this->interval);

        $this->stopOnClick        = intval($params->get('autoplayStopClick', 1));
        $this->stopOnMouse        = $params->get('autoplayStopMouse', 'enter');
        $this->stopOnMediaStarted = intval($params->get('autoplayStopMedia', 1));


        $this->resumeOnClick      = intval($params->get('autoplayResumeClick', 0));
        $this->resumeOnMouse      = $params->get('autoplayResumeMouse', 0);
        $this->resumeOnMediaEnded = intval($params->get('autoplayResumeMedia', 1));


    }

    public function makeJavaScriptProperties(&$properties) {
        $properties['autoplay'] = array(
            'enabled'          => $this->isEnabled,
            'start'            => $this->isStart,
            'duration'         => $this->duration,
            'autoplayLoop'     => $this->isLoop,
            'allowReStart'     => $this->allowReStart,
            'pause'            => array(
                'click'        => $this->stopOnClick,
                'mouse'        => $this->stopOnMouse,
                'mediaStarted' => $this->stopOnMediaStarted
            ),
            'resume'           => array(
                'click'        => $this->resumeOnClick,
                'mouse'        => $this->resumeOnMouse,
                'mediaEnded'   => $this->resumeOnMediaEnded,
                'slidechanged' => $this->resumeOnSlideChanged
            ),
            'interval'         => $this->interval,
            'intervalModifier' => $this->intervalModifier,
            'intervalSlide'    => $this->intervalSlide
        );
    }

    /**
     * For compatibility with legacy autoplay values.
     *
     * @param Data $params
     */
    protected function upgradeData($params) {
    }
}