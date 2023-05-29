<?php


namespace Nextend\SmartSlider3\Slider\Feature;


use Nextend\Framework\Data\Data;
use Nextend\SmartSlider3\Application\Admin\Settings\ViewSettingsGeneral;
use Nextend\SmartSlider3\Settings;
use Nextend\SmartSlider3\Slider\ResponsiveType\AbstractResponsiveTypeFrontend;
use Nextend\SmartSlider3\Slider\ResponsiveType\ResponsiveTypeFactory;
use Nextend\SmartSlider3\Slider\Slider;
use Nextend\SmartSlider3\SmartSlider3Info;

class Responsive {

    /** @var  Slider */
    public $slider;

    /**
     * @var AbstractResponsiveTypeFrontend
     */
    protected $responsivePlugin;

    protected $hideOnDesktopLandscape = 1;
    protected $hideOnDesktopPortrait = 1;

    protected $hideOnTabletLandscape = 1;
    protected $hideOnTabletPortrait = 1;

    protected $hideOnMobileLandscape = 1;
    protected $hideOnMobilePortrait = 1;

    public $onResizeEnabled = 1;

    public $type = 'auto';

    public $scaleDown = 1;

    public $scaleUp = 1;

    public $forceFull = 0;

    public $forceFullOverflowX = 'body';

    public $forceFullHorizontalSelector = '';

    public $minimumHeight = -1;

    public $maximumSlideWidthLandscape = -1;
    public $maximumSlideWidth = 10000;
    public $maximumSlideWidthTabletLandscape = -1;
    public $maximumSlideWidthTablet = -1;
    public $maximumSlideWidthMobileLandscape = -1;
    public $maximumSlideWidthMobile = -1;

    public $sliderHeightBasedOn = 'real';
    public $responsiveDecreaseSliderHeight = 0;

    public $focusUser = 1;

    public $focusEdge = 'auto';

    protected $enabledDevices = array(
        'desktopLandscape' => 0,
        'desktopPortrait'  => 1,
        'tabletLandscape'  => 0,
        'tabletPortrait'   => 1,
        'mobileLandscape'  => 0,
        'mobilePortrait'   => 1
    );

    protected $breakpoints = array();

    /**
     * @var array[]
     */
    public $mediaQueries = array(
        'all' => false
    );

    public $sizes = array(
        'desktopPortrait' => array(
            'width'  => 800,
            'height' => 600
        ),
    );

    public static $translation = array(
        'desktoplandscape' => 'desktopLandscape',
        'desktopportrait'  => 'desktopPortrait',
        'tabletlandscape'  => 'tabletLandscape',
        'tabletportrait'   => 'tabletPortrait',
        'mobilelandscape'  => 'mobileLandscape',
        'mobileportrait'   => 'mobilePortrait'
    );

    public function __construct($slider, $features) {

        $this->slider = $slider;

        $this->hideOnDesktopLandscape = !intval($slider->params->get('desktoplandscape', 1));
        $this->hideOnDesktopPortrait  = !intval($slider->params->get('desktopportrait', 1));

        $this->hideOnTabletLandscape = !intval($slider->params->get('tabletlandscape', 1));
        $this->hideOnTabletPortrait  = !intval($slider->params->get('tabletportrait', 1));

        $this->hideOnMobileLandscape = !intval($slider->params->get('mobilelandscape', 1));
        $this->hideOnMobilePortrait  = !intval($slider->params->get('mobileportrait', 1));


        $this->focusUser = intval($slider->params->get('responsiveFocusUser', 1));

        $this->focusEdge = $slider->params->get('responsiveFocusEdge', 'auto');

        $this->responsivePlugin = ResponsiveTypeFactory::createFrontend($slider->params->get('responsive-mode', 'auto'), $this);
        $this->type             = $this->responsivePlugin->getType();
        $this->responsivePlugin->parse($slider->params, $this, $features);

        $this->onResizeEnabled = !$slider->disableResponsive;

        if (!$this->scaleDown && !$this->scaleUp) {
            $this->onResizeEnabled = 0;
        }

        $overrideSizeEnabled = !!$slider->params->get('slider-size-override', 0);

        $this->sizes['desktopPortrait']['width']  = max(10, intval($slider->params->get('width', 1200)));
        $this->sizes['desktopPortrait']['height'] = max(10, intval($slider->params->get('height', 600)));

        $heightHelperRatio = $this->sizes['desktopPortrait']['height'] / $this->sizes['desktopPortrait']['width'];

        $this->enabledDevices['desktopLandscape'] = intval($slider->params->get('responsive-breakpoint-desktop-landscape-enabled', 0));
        $this->enabledDevices['tabletLandscape']  = intval($slider->params->get('responsive-breakpoint-tablet-landscape-enabled', 0));
        $this->enabledDevices['tabletPortrait']   = intval($slider->params->get('responsive-breakpoint-tablet-portrait-enabled', 1));
        $this->enabledDevices['mobileLandscape']  = intval($slider->params->get('responsive-breakpoint-mobile-landscape-enabled', 0));
        $this->enabledDevices['mobilePortrait']   = intval($slider->params->get('responsive-breakpoint-mobile-portrait-enabled', 1));

        $useLocalBreakpoints = !$slider->params->get('responsive-breakpoint-global', 0);

        $landscapePortraitWidth = $breakpointWidthLandscape = 3001;
        $previousSize           = false;

        if ($this->enabledDevices['desktopLandscape']) {

            $landscapePortraitWidth   = $breakpointWidthPortrait = intval($useLocalBreakpoints ? $slider->params->get('responsive-breakpoint-desktop-portrait', ViewSettingsGeneral::defaults['desktop-large-portrait']) : Settings::get('responsive-screen-width-desktop-portrait', ViewSettingsGeneral::defaults['desktop-large-portrait']));
            $breakpointWidthLandscape = max($landscapePortraitWidth, intval($useLocalBreakpoints ? $slider->params->get('responsive-breakpoint-desktop-portrait-landscape', ViewSettingsGeneral::defaults['desktop-large-landscape']) : Settings::get('responsive-screen-width-desktop-portrait-landscape', ViewSettingsGeneral::defaults['desktop-large-landscape'])));

            $this->breakpoints[] = array(
                'device'         => 'desktopLandscape',
                'type'           => 'min-screen-width',
                'portraitWidth'  => $breakpointWidthPortrait,
                'landscapeWidth' => $breakpointWidthLandscape
            );

            $editorWidth = intval($slider->params->get('desktop-landscape-width', 1440));

            if ($overrideSizeEnabled && $slider->params->get('slider-size-override-desktop-landscape', 0) && $editorWidth > 10) {

                $customHeight = false;
                $editorHeight = intval($slider->params->get('desktop-landscape-height', 900));

                if ($editorWidth < $breakpointWidthPortrait) {
                    if ($editorHeight > 0) {
                        $editorHeight = $breakpointWidthPortrait / $editorWidth * $editorHeight;
                    }

                    $editorWidth = $breakpointWidthPortrait;
                }

                if ($editorHeight <= 0) {
                    switch ($this->slider->data->get('type', 'simple')) {
                        case 'carousel':
                        case 'showcase':
                            $editorHeight = 0;
                            break;
                        default:
                            $editorHeight = $editorWidth * $heightHelperRatio;
                    }
                } else {
                    $customHeight = true;
                }

                $this->sizes['desktopLandscape'] = array(
                    'width'        => $editorWidth,
                    'height'       => floor($editorHeight),
                    'customHeight' => $customHeight
                );
            } else {

                $this->sizes['desktopLandscape'] = array(
                    'width'        => $this->sizes['desktopPortrait']['width'],
                    'height'       => $this->sizes['desktopPortrait']['height'],
                    'customHeight' => false
                );
            }

            $this->sizes['desktopLandscape']['max'] = 3000;
            $this->sizes['desktopLandscape']['min'] = $breakpointWidthPortrait;

            $previousSize = &$this->sizes['desktopLandscape'];

        }

        $this->sizes['desktopPortrait']['max'] = max($this->sizes['desktopPortrait']['width'], $landscapePortraitWidth - 1, $breakpointWidthLandscape - 1);

        $previousSize = &$this->sizes['desktopPortrait'];

        /**
         * Keep a copy of the current smallest width to be able to disable smaller devices
         */
        $smallestWidth = $this->sizes['desktopPortrait']['width'];

        if ($this->enabledDevices['tabletLandscape']) {

            $breakpointWidthPortrait  = intval($useLocalBreakpoints ? $slider->params->get('responsive-breakpoint-tablet-landscape', ViewSettingsGeneral::defaults['tablet-large-portrait']) : Settings::get('responsive-screen-width-tablet-landscape', ViewSettingsGeneral::defaults['tablet-large-portrait']));
            $breakpointWidthLandscape = max($breakpointWidthPortrait, intval($useLocalBreakpoints ? $slider->params->get('responsive-breakpoint-tablet-landscape-landscape', ViewSettingsGeneral::defaults['tablet-large-landscape']) : Settings::get('responsive-screen-width-tablet-landscape-landscape', ViewSettingsGeneral::defaults['tablet-large-landscape'])));

            $this->breakpoints[] = array(
                'device'         => 'tabletLandscape',
                'type'           => 'max-screen-width',
                'portraitWidth'  => $breakpointWidthPortrait,
                'landscapeWidth' => $breakpointWidthLandscape
            );

            $editorWidth = intval($slider->params->get('tablet-landscape-width', 1024));

            if ($overrideSizeEnabled && $slider->params->get('slider-size-override-tablet-landscape', 0) && $editorWidth > 10) {

                $customHeight = false;
                $editorHeight = intval($slider->params->get('tablet-landscape-height', 768));

                if ($editorWidth > $breakpointWidthPortrait) {
                    if ($editorHeight > 0) {
                        $editorHeight = $breakpointWidthPortrait / $editorWidth * $editorHeight;
                    }

                    $editorWidth = $breakpointWidthPortrait;
                }

                if ($editorHeight <= 0) {
                    $editorHeight = $editorWidth * $heightHelperRatio;
                } else {
                    $customHeight = true;
                }

                $this->sizes['tabletLandscape'] = array(
                    'width'        => $editorWidth,
                    'height'       => floor($editorHeight),
                    'customHeight' => $customHeight
                );

                $smallestWidth = min($smallestWidth, $editorWidth);
            } else {
                $width = min($smallestWidth, $breakpointWidthPortrait);

                $this->sizes['tabletLandscape'] = array(
                    'width'        => $width,
                    'height'       => floor($width * $heightHelperRatio),
                    'auto'         => true,
                    'customHeight' => false
                );

                $smallestWidth = min($smallestWidth, $breakpointWidthPortrait);
            }

            $this->sizes['tabletLandscape']['max'] = max($this->sizes['tabletLandscape']['width'], $breakpointWidthPortrait, $breakpointWidthLandscape);

            $previousSize['min'] = min($previousSize['width'], $breakpointWidthPortrait + 1);

            $previousSize = &$this->sizes['tabletLandscape'];

        }

        if ($this->enabledDevices['tabletPortrait']) {

            $breakpointWidthPortrait  = intval($useLocalBreakpoints ? $slider->params->get('responsive-breakpoint-tablet-portrait', ViewSettingsGeneral::defaults['tablet-portrait']) : Settings::get('responsive-screen-width-tablet-portrait', ViewSettingsGeneral::defaults['tablet-portrait']));
            $breakpointWidthLandscape = max($breakpointWidthPortrait, intval($useLocalBreakpoints ? $slider->params->get('responsive-breakpoint-tablet-portrait-landscape', ViewSettingsGeneral::defaults['tablet-landscape']) : Settings::get('responsive-screen-width-tablet-portrait-landscape', ViewSettingsGeneral::defaults['tablet-landscape'])));

            $this->breakpoints[] = array(
                'device'         => 'tabletPortrait',
                'type'           => 'max-screen-width',
                'portraitWidth'  => $breakpointWidthPortrait,
                'landscapeWidth' => $breakpointWidthLandscape
            );

            $editorWidth = intval($slider->params->get('tablet-portrait-width', 768));

            if ($overrideSizeEnabled && $slider->params->get('slider-size-override-tablet-portrait', 0) && $editorWidth > 10) {

                $customHeight = false;
                $editorHeight = intval($slider->params->get('tablet-portrait-height', 1024));

                if ($editorWidth > $breakpointWidthPortrait) {
                    if ($editorHeight > 0) {
                        $editorHeight = $breakpointWidthPortrait / $editorWidth * $editorHeight;
                    }

                    $editorWidth = $breakpointWidthPortrait;
                }

                if ($editorHeight <= 0) {
                    $editorHeight = $editorWidth * $heightHelperRatio;
                } else {
                    $customHeight = true;
                }

                $this->sizes['tabletPortrait'] = array(
                    'width'        => $editorWidth,
                    'height'       => floor($editorHeight),
                    'customHeight' => $customHeight
                );

                $smallestWidth = min($smallestWidth, $editorWidth);
            } else {
                $width = min($smallestWidth, $breakpointWidthPortrait);

                $this->sizes['tabletPortrait'] = array(
                    'width'        => $width,
                    'height'       => floor($width * $heightHelperRatio),
                    'auto'         => true,
                    'customHeight' => false
                );

                $smallestWidth = min($smallestWidth, $breakpointWidthPortrait);
            }

            $this->sizes['tabletPortrait']['max'] = max($this->sizes['tabletPortrait']['width'], $breakpointWidthPortrait, $breakpointWidthLandscape);

            $previousSize['min'] = min($previousSize['width'], $breakpointWidthPortrait + 1);

            $previousSize = &$this->sizes['tabletPortrait'];
        }

        if ($this->enabledDevices['mobileLandscape']) {

            $breakpointWidthPortrait  = intval($useLocalBreakpoints ? $slider->params->get('responsive-breakpoint-mobile-landscape', ViewSettingsGeneral::defaults['mobile-large-portrait']) : Settings::get('responsive-screen-width-mobile-landscape', ViewSettingsGeneral::defaults['mobile-large-portrait']));
            $breakpointWidthLandscape = max($breakpointWidthPortrait, intval($useLocalBreakpoints ? $slider->params->get('responsive-breakpoint-mobile-landscape-landscape', ViewSettingsGeneral::defaults['mobile-large-landscape']) : Settings::get('responsive-screen-width-mobile-landscape-landscape', ViewSettingsGeneral::defaults['mobile-large-landscape'])));

            $this->breakpoints[] = array(
                'device'         => 'mobileLandscape',
                'type'           => 'max-screen-width',
                'portraitWidth'  => $breakpointWidthPortrait,
                'landscapeWidth' => $breakpointWidthLandscape
            );


            $editorWidth = intval($slider->params->get('mobile-landscape-width', 568));

            if ($overrideSizeEnabled && $slider->params->get('slider-size-override-mobile-landscape', 0) && $editorWidth > 10) {

                $customHeight = false;
                $editorHeight = intval($slider->params->get('mobile-landscape-height', 320));

                if ($editorWidth > $breakpointWidthPortrait) {
                    if ($editorHeight > 0) {
                        $editorHeight = $breakpointWidthPortrait / $editorWidth * $editorHeight;
                    }

                    $editorWidth = $breakpointWidthPortrait;
                }

                if ($editorHeight <= 0) {
                    $editorHeight = $editorWidth * $heightHelperRatio;
                } else {
                    $customHeight = true;
                }

                $this->sizes['mobileLandscape'] = array(
                    'width'        => $editorWidth,
                    'height'       => floor($editorHeight),
                    'customHeight' => $customHeight
                );

                $smallestWidth = min($smallestWidth, $editorWidth);
            } else {

                $width = min($smallestWidth, $breakpointWidthPortrait);

                $this->sizes['mobileLandscape'] = array(
                    'width'        => $width,
                    'height'       => floor($width * $heightHelperRatio),
                    'auto'         => true,
                    'customHeight' => false
                );

                $smallestWidth = min($smallestWidth, $breakpointWidthPortrait);
            }

            $this->sizes['mobileLandscape']['max'] = max($this->sizes['mobileLandscape']['width'], $breakpointWidthPortrait, $breakpointWidthLandscape);

            $previousSize['min'] = min($previousSize['width'], $breakpointWidthPortrait + 1);

            $previousSize = &$this->sizes['mobileLandscape'];
        }

        if ($this->enabledDevices['mobilePortrait']) {

            $breakpointWidthPortrait  = intval($useLocalBreakpoints ? $slider->params->get('responsive-breakpoint-mobile-portrait', ViewSettingsGeneral::defaults['mobile-portrait']) : Settings::get('responsive-screen-width-mobile-portrait', ViewSettingsGeneral::defaults['mobile-portrait']));
            $breakpointWidthLandscape = max($breakpointWidthPortrait, intval($useLocalBreakpoints ? $slider->params->get('responsive-breakpoint-mobile-portrait-landscape', ViewSettingsGeneral::defaults['mobile-landscape']) : Settings::get('responsive-screen-width-mobile-portrait-landscape', ViewSettingsGeneral::defaults['mobile-landscape'])));

            $this->breakpoints[] = array(
                'device'         => 'mobilePortrait',
                'type'           => 'max-screen-width',
                'portraitWidth'  => $breakpointWidthPortrait,
                'landscapeWidth' => $breakpointWidthLandscape
            );


            $editorWidth = intval($slider->params->get('mobile-portrait-width', 320));

            if ($overrideSizeEnabled && $slider->params->get('slider-size-override-mobile-portrait', 0) && $editorWidth > 10) {

                $customHeight = false;
                $editorHeight = intval($slider->params->get('mobile-portrait-height', 568));

                if ($editorWidth > $breakpointWidthPortrait) {
                    if ($editorHeight > 0) {
                        $editorHeight = $breakpointWidthPortrait / $editorWidth * $editorHeight;
                    }

                    $editorWidth = $breakpointWidthPortrait;
                }

                if ($editorHeight <= 0) {
                    $editorHeight = $editorWidth * $heightHelperRatio;
                } else {
                    $customHeight = true;
                }

                $this->sizes['mobilePortrait'] = array(
                    'width'        => $editorWidth,
                    'height'       => floor($editorHeight),
                    'customHeight' => $customHeight
                );
            } else {
                $width = min(320, $smallestWidth, $breakpointWidthPortrait);

                $this->sizes['mobilePortrait'] = array(
                    'width'        => $width,
                    'height'       => floor($width * $heightHelperRatio),
                    'customHeight' => false
                );
            }

            $this->sizes['mobilePortrait']['max'] = max($this->sizes['mobilePortrait']['width'], $breakpointWidthPortrait, $breakpointWidthLandscape);

            $previousSize['min'] = min($previousSize['width'], $breakpointWidthPortrait + 1);

            $previousSize = &$this->sizes['mobilePortrait'];
        }

        $previousSize['min'] = min(320, $previousSize['width']);

        if (isset($this->sizes['mobileLandscape']['auto'])) {
            unset($this->sizes['mobileLandscape']['auto']);

            $this->sizes['mobileLandscape']['width']  = $this->sizes['mobileLandscape']['min'];
            $this->sizes['mobileLandscape']['height'] = floor($this->sizes['mobileLandscape']['width'] * $heightHelperRatio);
        }

        if (isset($this->sizes['tabletPortrait']['auto'])) {
            unset($this->sizes['tabletPortrait']['auto']);

            $this->sizes['tabletPortrait']['width']  = $this->sizes['tabletPortrait']['min'];
            $this->sizes['tabletPortrait']['height'] = floor($this->sizes['tabletPortrait']['width'] * $heightHelperRatio);
        }

        if (isset($this->sizes['tabletLandscape']['auto'])) {
            unset($this->sizes['tabletLandscape']['auto']);

            $this->sizes['tabletLandscape']['width']  = $this->sizes['tabletLandscape']['min'];
            $this->sizes['tabletLandscape']['height'] = floor($this->sizes['tabletLandscape']['width'] * $heightHelperRatio);
        }

        $this->parseLimitSlideWidth($slider->params);

        $breakpointData = array();
        foreach ($this->breakpoints as $breakpoint) {
            $breakpointData[$breakpoint['device']] = $breakpoint;
        }

        if (isset($breakpointData['desktopLandscape'])) {

            $portraitMinWidth  = $breakpointData['desktopLandscape']['portraitWidth'];
            $landscapeMinWidth = $breakpointData['desktopLandscape']['landscapeWidth'];

            if ($portraitMinWidth == $landscapeMinWidth || $this->slider->isFrame) {
                $this->mediaQueries['desktoplandscape'] = array('(min-width: ' . $portraitMinWidth . 'px)');

            } else {
                $this->mediaQueries['desktoplandscape'] = array(
                    '(orientation: landscape) and (min-width: ' . $landscapeMinWidth . 'px)',
                    '(orientation: portrait) and (min-width: ' . $portraitMinWidth . 'px)'
                );
            }
        }

        $nextSize = null;
        foreach (array(
                     'tabletLandscape',
                     'tabletPortrait',
                     'mobileLandscape',
                     'mobilePortrait'
                 ) as $nextDevice) {
            if (isset($breakpointData[$nextDevice])) {
                $nextSize = $breakpointData[$nextDevice];
                break;
            }
        }

        $portraitMaxWidth  = 0;
        $landscapeMaxWidth = 0;
        if (isset($breakpointData['desktopLandscape'])) {
            $portraitMaxWidth  = $breakpointData['desktopLandscape']['portraitWidth'] - 1;
            $landscapeMaxWidth = $breakpointData['desktopLandscape']['landscapeWidth'] - 1;
        }
        $portraitMinWidth  = $nextSize['portraitWidth'] + 1;
        $landscapeMinWidth = $nextSize['landscapeWidth'] + 1;

        if ($portraitMaxWidth == 0 || $landscapeMaxWidth == 0) {
            if ($portraitMinWidth == $landscapeMinWidth || $this->slider->isFrame) {
                $this->mediaQueries['desktopportrait'] = array('(min-width: ' . $portraitMinWidth . 'px)');

            } else {
                $this->mediaQueries['desktopportrait'] = array(
                    '(orientation: landscape) and (min-width: ' . $landscapeMinWidth . 'px)',
                    '(orientation: portrait) and (min-width: ' . $portraitMinWidth . 'px)'
                );
            }
        } else {
            if (($portraitMinWidth == $landscapeMinWidth && $portraitMaxWidth == $landscapeMaxWidth) || $this->slider->isFrame) {
                $this->mediaQueries['desktopportrait'] = array('(min-width: ' . $portraitMinWidth . 'px) and (max-width: ' . $portraitMaxWidth . 'px)');

            } else {
                $this->mediaQueries['desktopportrait'] = array(
                    '(orientation: landscape) and (min-width: ' . $landscapeMinWidth . 'px) and (max-width: ' . $landscapeMaxWidth . 'px)',
                    '(orientation: portrait) and (min-width: ' . $portraitMinWidth . 'px) and (max-width: ' . $portraitMaxWidth . 'px)'
                );
            }
        }


        $this->initMediaQuery($breakpointData, 'tabletLandscape', array(
            'tabletPortrait',
            'mobileLandscape',
            'mobilePortrait'
        ));

        $this->initMediaQuery($breakpointData, 'tabletPortrait', array(
            'mobileLandscape',
            'mobilePortrait'
        ));

        $this->initMediaQuery($breakpointData, 'mobileLandscape', array(
            'mobilePortrait'
        ));

        $this->initMediaQuery($breakpointData, 'mobilePortrait', array());
    }

    private function initMediaQuery(&$breakpointData, $deviceName, $nextDevices) {
        if (isset($breakpointData[$deviceName])) {

            $deviceNameLower = strtolower($deviceName);

            $nextSize = null;
            foreach ($nextDevices as $nextDevice) {
                if (isset($breakpointData[$nextDevice])) {
                    $nextSize = $breakpointData[$nextDevice];
                    break;
                }
            }

            $portraitMaxWidth  = $breakpointData[$deviceName]['portraitWidth'];
            $landscapeMaxWidth = $breakpointData[$deviceName]['landscapeWidth'];

            if ($nextSize) {
                if (($nextSize['portraitWidth'] == $nextSize['landscapeWidth'] && $portraitMaxWidth == $landscapeMaxWidth) || $this->slider->isFrame) {
                    $this->mediaQueries[$deviceNameLower] = array('(max-width: ' . $portraitMaxWidth . 'px) and (min-width: ' . ($nextSize['portraitWidth'] + 1) . 'px)');

                } else {
                    $this->mediaQueries[$deviceNameLower] = array(
                        '(orientation: landscape) and (max-width: ' . $landscapeMaxWidth . 'px) and (min-width: ' . ($nextSize['landscapeWidth'] + 1) . 'px)',
                        '(orientation: portrait) and (max-width: ' . $portraitMaxWidth . 'px) and (min-width: ' . ($nextSize['portraitWidth'] + 1) . 'px)'
                    );
                }
            } else {
                if (($portraitMaxWidth == $landscapeMaxWidth) || $this->slider->isFrame) {
                    $this->mediaQueries[$deviceNameLower] = array('(max-width: ' . $portraitMaxWidth . 'px)');

                } else {
                    $this->mediaQueries[$deviceNameLower] = array(
                        '(orientation: landscape) and (max-width: ' . $landscapeMaxWidth . 'px)',
                        '(orientation: portrait) and (max-width: ' . $portraitMaxWidth . 'px)'
                    );
                }
            }
        }

    }

    public function makeJavaScriptProperties(&$properties) {

        if ($this->maximumSlideWidthLandscape <= 0) {
            $this->maximumSlideWidthLandscape = $this->maximumSlideWidth;
        }

        if ($this->maximumSlideWidthTablet <= 0) {
            $this->maximumSlideWidthTablet = $this->maximumSlideWidth;
        }

        if ($this->maximumSlideWidthTabletLandscape <= 0) {
            $this->maximumSlideWidthTabletLandscape = $this->maximumSlideWidthTablet;
        }

        if ($this->maximumSlideWidthMobile <= 0) {
            $this->maximumSlideWidthMobile = $this->maximumSlideWidth;
        }

        if ($this->maximumSlideWidthMobileLandscape <= 0) {
            $this->maximumSlideWidthMobileLandscape = $this->maximumSlideWidthMobile;
        }

        if (!$this->scaleDown) {
            $this->slider->addDeviceCSS('all', 'div#' . $this->slider->elementId . '-align{min-width:' . $this->sizes['desktopPortrait']['width'] . 'px;}');
        }

        if (!$this->scaleUp) {
            $this->slider->addDeviceCSS('all', 'div#' . $this->slider->elementId . '-align{max-width:' . $this->sizes['desktopPortrait']['width'] . 'px;}');
        }


        if ($this->minimumHeight > 0) {
            $this->slider->sliderType->handleSliderMinHeight($this->minimumHeight);
        }

        foreach ($this->mediaQueries as $device => $mediaQuery) {
            if ($mediaQuery) {
                $this->slider->addDeviceCSS($device, 'div#' . $this->slider->elementId . ' [data-hide-' . $device . '="1"]{display: none !important;}');
            }
        }

        if (!$this->slider->isAdmin) {
            if ($this->hideOnDesktopLandscape) {
                $this->slider->addDeviceCSS('desktoplandscape', '.n2-section-smartslider[data-ssid="' . $this->slider->sliderId . '"]{display: none;}');
            }
            if (!SmartSlider3Info::$forceDesktop && $this->hideOnDesktopPortrait) {
                $this->slider->addDeviceCSS('desktopportrait', '.n2-section-smartslider[data-ssid="' . $this->slider->sliderId . '"]{display: none;}');
            }

            if ($this->hideOnTabletLandscape) {
                $this->slider->addDeviceCSS('tabletlandscape', '.n2-section-smartslider[data-ssid="' . $this->slider->sliderId . '"]{display: none;}');
            }
            if ($this->hideOnTabletPortrait) {
                $this->slider->addDeviceCSS('tabletportrait', '.n2-section-smartslider[data-ssid="' . $this->slider->sliderId . '"]{display: none;}');
            }

            if ($this->hideOnMobileLandscape) {
                $this->slider->addDeviceCSS('mobilelandscape', '.n2-section-smartslider[data-ssid="' . $this->slider->sliderId . '"]{display: none;}');
            }
            if ($this->hideOnMobilePortrait) {
                $this->slider->addDeviceCSS('mobileportrait', '.n2-section-smartslider[data-ssid="' . $this->slider->sliderId . '"]{display: none;}');
            }
        }


        $properties['responsive'] = array(
            'mediaQueries' => $this->mediaQueries,
            'base'         => $this->slider->assets->base,
            'hideOn'       => array(
                'desktopLandscape' => SmartSlider3Info::$forceAllDevices ? false : $this->hideOnDesktopLandscape,
                'desktopPortrait'  => SmartSlider3Info::$forceDesktop ? false : $this->hideOnDesktopPortrait,
                'tabletLandscape'  => SmartSlider3Info::$forceAllDevices ? false : $this->hideOnTabletLandscape,
                'tabletPortrait'   => SmartSlider3Info::$forceAllDevices ? false : $this->hideOnTabletPortrait,
                'mobileLandscape'  => SmartSlider3Info::$forceAllDevices ? false : $this->hideOnMobileLandscape,
                'mobilePortrait'   => SmartSlider3Info::$forceAllDevices ? false : $this->hideOnMobilePortrait,
            ),

            'onResizeEnabled'     => $this->onResizeEnabled,
            'type'                => $this->type,
            'sliderHeightBasedOn' => $this->sliderHeightBasedOn,

            'focusUser' => $this->focusUser,
            'focusEdge' => $this->focusEdge,

            'breakpoints'    => $this->breakpoints,
            'enabledDevices' => $this->enabledDevices,
            'sizes'          => $this->sizes,

            'overflowHiddenPage' => intval($this->slider->params->get('overflow-hidden-page', 0))
        );
    }

    /**
     * @param Data $params
     */
    private function parseLimitSlideWidth($params) {
        if ($params->get('responsiveLimitSlideWidth', 1)) {

            if ($this->enabledDevices['desktopLandscape']) {
                if ($params->get('responsiveSlideWidthDesktopLandscape', 0)) {
                    $this->maximumSlideWidthLandscape = intval($params->get('responsiveSlideWidthMaxDesktopLandscape', 1600));

                    $this->slider->addDeviceCSS('desktoplandscape', 'div#' . $this->slider->elementId . ' .n2-ss-slide-limiter{max-width:' . $this->maximumSlideWidthLandscape . 'px;}');
                }
            }

            if ($params->get('responsiveSlideWidth', 0)) {
                $this->maximumSlideWidth = intval($params->get('responsiveSlideWidthMax', 3000));
            } else {
                $this->maximumSlideWidth = $this->sizes['desktopPortrait']['width'];
            }

            if ($this->maximumSlideWidth < 1) {
                $this->maximumSlideWidth = 10000;
            }

            $this->slider->addDeviceCSS('all', 'div#' . $this->slider->elementId . ' .n2-ss-slide-limiter{max-width:' . $this->maximumSlideWidth . 'px;}');


            if ($this->enabledDevices['tabletLandscape']) {
                if ($params->get('responsiveSlideWidthTabletLandscape', 0)) {
                    $this->maximumSlideWidthTabletLandscape = intval($params->get('responsiveSlideWidthMaxTabletLandscape', 1200));

                    $this->slider->addDeviceCSS('tabletlandscape', 'div#' . $this->slider->elementId . ' .n2-ss-slide-limiter{max-width:' . $this->maximumSlideWidthTabletLandscape . 'px;}');
                }
            }

            if ($params->get('responsiveSlideWidthTablet', 0)) {
                $this->maximumSlideWidthTablet = intval($params->get('responsiveSlideWidthMaxTablet', 980));

                $this->slider->addDeviceCSS('tabletportrait', 'div#' . $this->slider->elementId . ' .n2-ss-slide-limiter{max-width:' . $this->maximumSlideWidthTablet . 'px;}');
            }


            if ($this->enabledDevices['mobileLandscape']) {
                if ($params->get('responsiveSlideWidthMobileLandscape', 0)) {
                    $this->maximumSlideWidthMobileLandscape = intval($params->get('responsiveSlideWidthMaxMobileLandscape', 780));

                    $this->slider->addDeviceCSS('mobilelandscape', 'div#' . $this->slider->elementId . ' .n2-ss-slide-limiter{max-width:' . $this->maximumSlideWidthMobileLandscape . 'px;}');
                }
            }

            if ($params->get('responsiveSlideWidthMobile', 0)) {
                $this->maximumSlideWidthMobile = intval($params->get('responsiveSlideWidthMaxMobile', 480));

                $this->slider->addDeviceCSS('mobileportrait', 'div#' . $this->slider->elementId . ' .n2-ss-slide-limiter{max-width:' . $this->maximumSlideWidthMobile . 'px;}');
            }
        }
    }
}