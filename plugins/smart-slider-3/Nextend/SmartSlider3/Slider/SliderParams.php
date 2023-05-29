<?php


namespace Nextend\SmartSlider3\Slider;


use Nextend\Framework\Data\Data;
use Nextend\SmartSlider3\Application\ApplicationSmartSlider3;
use Nextend\SmartSlider3\Application\Model\ModelSlides;

class SliderParams extends Data {

    protected $sliderID;

    /**
     * @var string
     */
    protected $sliderType;

    public function __construct($sliderID, $sliderType, $data = null, $json = false) {

        $this->sliderID   = $sliderID;
        $this->sliderType = $sliderType;

        parent::__construct($data, $json);

        $this->upgradeData();
    }

    private function upgradeData() {

        $this->upgradeSliderTypeResponsive();

        $this->upgradeMaxSliderHeight();

        $this->upgradeLimitSlideWidth();

        $this->upgradeShowOn();

        $this->upgradeShowOn('widget-arrow-display-');

        $this->upgradeShowOn('widget-autoplay-display-');

        $this->upgradeShowOn('widget-bar-display-');

        $this->upgradeShowOn('widget-bullet-display-');

        $this->upgradeShowOn('widget-shadow-display-');

        $this->upgradeShowOn('widget-thumbnail-display-');

        $this->upgradeShowOn('widget-fullscreen-display-');

        $this->upgradeShowOn('widget-html-display-');

        $this->upgradeShowOn('widget-indicator-display-');

        $this->upgradeAdaptiveResponsiveMode();

        $this->upgradeCustomSliderSize();


        $this->upgradeLoadingType();

        $this->upgradeSlideBackgroundOptimize();

        $this->upgradeThumbnailsControlSize();

        $this->upgradeCarouselSideSpacing();

        $this->upgradeShowcaseSideSpacing();

        $this->upgradeShowcaseCarouselSideSpacingWithCustomSize();

        if ($this->has('optimize-background-image-width')) {
            /**
             * This setting was available only before version 3.5 so, if we end up here then it is an old slider.
             * If there are root absolute layers with disabled adaptive sizing, we enable the legacy font scale.
             */

            $slidesModel = new ModelSlides(ApplicationSmartSlider3::getInstance()
                                                                  ->getApplicationTypeFrontend());
            $hasAbsolute = false;
            $slides      = $slidesModel->getAll($this->sliderID);
            foreach ($slides as $slide) {
                $layers = json_decode($slide['slide'], true);
                foreach ($layers as $layer) {
                    if (isset($layer['type']) && $layer['type'] != 'content') {
                        if (isset($layer['adaptivefont']) && $layer['adaptivefont'] == 0 && isset($layer['item']) && in_array($layer['item']['type'], array(
                                'button',
                                'heading',
                                'text',
                                'animatedHeading',
                                'caption',
                                'highlightedHeading',
                                'html',
                                'list',
                                'imagebox',
                                'input'
                            ))) {
                            $hasAbsolute = true;
                            break;
                        }
                    }
                }
                if ($hasAbsolute) {
                    break;
                }
            }
            if ($hasAbsolute) {
                $this->set('legacy-font-scale', '1');
            }
        }
    }

    private function upgradeSliderTypeResponsive() {
        if ($this->sliderType == 'carousel' || $this->sliderType == 'showcase') {
            if ($this->get('responsive-mode') == 'fullpage') {
                $this->set('responsive-mode', 'fullwidth');
            }
        }
    }

    private function upgradeMaxSliderHeight() {
        if ($this->has('responsiveSliderHeightMax')) {
            $maxSliderHeight = intval($this->get('responsiveSliderHeightMax', 3000));
            if ($maxSliderHeight < 1) {
                $maxSliderHeight = 3000;
            }

            $sliderWidth  = intval($this->get('width'));
            $sliderHeight = intval($this->get('height'));

            $maxSliderWidth = round($sliderWidth * ($maxSliderHeight / $sliderHeight));

            $maxSlideWidth = intval($this->get('responsiveSlideWidthMax', 3000));
            if ($this->has('responsiveSlideWidth')) {
                if ($this->get('responsiveSlideWidth', 1)) {
                    if ($maxSliderWidth < $maxSlideWidth) {
                        $this->set('responsiveSlideWidthMax', $maxSliderWidth);
                    }
                } else {
                    if ($maxSliderWidth > 100) {
                        $this->set('responsiveSlideWidth', 1);
                        $this->set('responsiveSlideWidthMax', $maxSliderWidth);
                    }
                }
            } else {
                $maxWidth = INF;
                if ($maxSlideWidth > 0) {
                    $maxWidth = min($maxWidth, $maxSlideWidth);
                }
                if ($maxSliderWidth > 0) {
                    $maxWidth = min($maxWidth, $maxSliderWidth);
                }
                if ($maxWidth != INF) {
                    $this->set('responsiveSlideWidth', 1);
                    $this->set('responsiveSlideWidthMax', $maxWidth);
                }
            }
            $this->un_set('responsiveSliderHeightMax');
        }

    }

    private function upgradeLimitSlideWidth() {
        if (!$this->has('responsiveLimitSlideWidth')) {
            if (!$this->has('responsiveSlideWidth')) {
                /**
                 * Layout: Auto, fullpage
                 */
                if ($this->get('responsiveSlideWidthMax') > 0) {
                    $this->set('responsiveLimitSlideWidth', 1);
                    $this->set('responsiveSlideWidth', 1);
                } else {
                    $this->set('responsiveLimitSlideWidth', 0);
                    $this->set('responsiveSlideWidth', 0);
                }
            } else {
                /**
                 * Layout: full width
                 */
                if (!$this->get('responsiveSlideWidth') && !$this->get('responsiveSlideWidthDesktopLandscape') && !$this->get('responsiveSlideWidthTablet') && !$this->get('responsiveSlideWidthTabletLandscape') && !$this->get('responsiveSlideWidthMobile') && !$this->get('responsiveSlideWidthMobileLandscape')) {
                    $this->set('responsiveLimitSlideWidth', 0);
                } else {
                    $this->set('responsiveLimitSlideWidth', 1);
                }
            }

        }
    }

    private function upgradeShowOn($pre = '') {

        $this->upgradeShowOnDevice($pre . 'desktop');
        $this->upgradeShowOnDevice($pre . 'tablet');
        $this->upgradeShowOnDevice($pre . 'mobile');
    }

    private function upgradeShowOnDevice($device, $pre = '') {
        if ($this->has($pre . $device)) {
            $value = $this->get($pre . $device);
            $this->un_set($pre . $device);

            $this->set($device . 'portrait', $value);
            $this->set($device . 'landscape', $value);
        }
    }

    private function upgradeAdaptiveResponsiveMode() {
        $responsiveMode = $this->get('responsive-mode');
        if ($responsiveMode === 'adaptive') {
            $this->set('responsiveScaleUp', 0);
        }
    }

    private function upgradeCustomSliderSize() {
        $deviceModes = array(
            'desktop-landscape',
            'tablet-portrait',
            'tablet-landscape',
            'mobile-portrait',
            'mobile-landscape'
        );

        foreach ($deviceModes as $deviceMode) {
            if (intval($this->get($deviceMode)) === 1) {

                if (intval($this->get('slider-size-override')) === 0) {
                    $this->set('slider-size-override', 1);
                }

                $this->set('slider-size-override-' . $deviceMode, 1);
                $this->set('responsive-breakpoint-' . $deviceMode . '-enabled', 1);
            }
        }

    }

    private function upgradeLoadingType() {
        if (!empty($this->get('dependency'))) {
            $this->set('loading-type', 'afterOnLoad');
        } else {
            if (!$this->has('loading-type') && $this->get('delay') > 0) {
                $this->set('loading-type', 'afterDelay');
            }
        }
    }

    private function upgradeSlideBackgroundOptimize() {
        $optimize = $this->get('optimize');

        //Slide Background Resize
        $isResizeBackgroundEnabled = $this->get('optimize-background-image-custom');
        $resizeBackgroundWidth     = $this->get('optimize-background-image-width');
        if (!empty($optimize) && $optimize) {
            $this->set('optimize-thumbnail-scale', 1);
            $this->set('optimize-thumbnail-quality', intval($this->get('optimize-quality', 70)));

            if (!empty($isResizeBackgroundEnabled) && $isResizeBackgroundEnabled && !empty($resizeBackgroundWidth)) {
                $this->set('optimize-scale', 1);

                $this->set('optimize-slide-width-normal', (int)$resizeBackgroundWidth);
            }
        }
    }

    private function upgradeThumbnailsControlSize() {
        $isThumbnailEnabled = $this->get('widget-thumbnail-enabled');

        if ($isThumbnailEnabled) {

            if (!$this->has('widget-thumbnail-tablet-width') && !$this->has('widget-thumbnail-mobile-width')) {
                $defaultThumbnailWidth = intval($this->get('widget-thumbnail-width', 100));
                $this->set('widget-thumbnail-tablet-width', $defaultThumbnailWidth);
                $this->set('widget-thumbnail-mobile-width', $defaultThumbnailWidth);
            }
            if (!$this->has('widget-thumbnail-tablet-height') && !$this->has('widget-thumbnail-mobile-height')) {
                $defaultThumbnailHeight = intval($this->get('widget-thumbnail-height', 60));
                $this->set('widget-thumbnail-tablet-height', $defaultThumbnailHeight);
                $this->set('widget-thumbnail-mobile-height', $defaultThumbnailHeight);
            }

        }
    }

    private function upgradeCarouselSideSpacing() {
        if ($this->sliderType == 'carousel') {
            if ($this->has('optimize-background-image-width')) {
                /**
                 * This setting was available only before version 3.5 so, if we end up here then it is an old slider.
                 * Earlier we automatically created top and bottom side spacing: (Slider Height - Slide Height) / 2
                 * so for old sliders we need to set those values for Side Spacing top and bottom.
                 */
                $sliderHeight = intval($this->get('height'));
                $slideHeight  = intval($this->get('slide-height'));
                if ($sliderHeight > $slideHeight) {
                    $heightDifference = $sliderHeight - $slideHeight;
                    $spacingValue     = intval($heightDifference / 2);

                    if (!$this->get('side-spacing-desktop-enable')) {
                        $this->set('side-spacing-desktop-enable', 1);
                        $this->set('side-spacing-desktop', $spacingValue . '|*|0|*|' . $spacingValue . '|*|0');
                        $this->set('height', ($sliderHeight - $heightDifference));
                    }
                }
            }
        }
    }

    private function upgradeShowcaseSideSpacing() {
        if ($this->sliderType == 'showcase') {
            if ($this->has('optimize-background-image-width')) {
                /**
                 * This setting was available only before version 3.5 so, if we end up here then it is an old slider.
                 * Earlier we automatically created top and bottom side spacing: (Slider Height - Slide Height) / 2
                 * so for old sliders we need to set those values for Side Spacing top and bottom.
                 */
                $sliderHeight = intval($this->get('height'));
                $slideHeight  = intval($this->get('slide-height'));
                if ($sliderHeight > $slideHeight) {
                    $heightDifference = $sliderHeight - $slideHeight;
                    $spacingValue     = intval($heightDifference / 2);
                    $this->set('side-spacing-desktop-enable', 1);
                    $this->set('side-spacing-desktop', $spacingValue . '|*|20|*|' . $spacingValue . '|*|20');
                    $this->set('height', ($sliderHeight - $heightDifference));
                }
            }
        }
    }

    private function upgradeShowcaseCarouselSideSpacingWithCustomSize() {
        if ($this->sliderType == 'showcase' || $this->sliderType == 'carousel') {
            /**
             * Showcase and Carousel slider types no longer have Custom size option.
             * If earlier there was a custom slider size set, then we need to add top and bottom side spacings
             */
            $customSliderSizeEnabled = intval($this->get('slider-size-override'));
            if ($customSliderSizeEnabled) {
                $sliderHeight = intval($this->get('height'));
                $slideHeight  = intval($this->get('slide-height'));

                $customTabletSizeEnabled = intval($this->get('slider-size-override-tablet-portrait'));
                if ($customTabletSizeEnabled) {
                    $customTabletSliderHeight    = intval($this->get('tablet-portrait-height'));
                    $tabletSideSpacingDifference = 0;
                    if ($customTabletSliderHeight > 0) {
                        if (($slideHeight >= $sliderHeight && $slideHeight < $customTabletSliderHeight) || ($slideHeight < $sliderHeight && $sliderHeight >= $customTabletSliderHeight)) {
                            $tabletSideSpacingDifference = round(($customTabletSliderHeight - $slideHeight) / 2);
                        }
                        if ($slideHeight < $sliderHeight && $sliderHeight < $customTabletSliderHeight) {
                            $tabletSideSpacingDifference = round((($customTabletSliderHeight - $sliderHeight) / 2) + (($sliderHeight - $slideHeight) / 2));
                        }

                        if ($slideHeight >= $customTabletSliderHeight) {
                            $tabletSideSpacingDifference = 0;
                        }
                    }

                    if ($tabletSideSpacingDifference > 0) {
                        if ($this->get('side-spacing-tablet-enable', 0)) {
                            $tabletSideSpacing = array_pad(array_map('intval', explode('|*|', $this->get('side-spacing-tablet'))), 4, 0);
                            $this->set('side-spacing-tablet', ($tabletSideSpacing[0] + $tabletSideSpacingDifference) . '|*|' . $tabletSideSpacing[1] . '|*|' . ($tabletSideSpacing[2] + $tabletSideSpacingDifference) . '|*|' . $tabletSideSpacing[3]);
                        } else {
                            $this->set('side-spacing-tablet-enable', 1);
                            $this->set('side-spacing-tablet', $tabletSideSpacingDifference . '|*|0|*|' . $tabletSideSpacingDifference . '|*|0');
                        }
                    }
                }

                $customMobileSizeEnabled = intval($this->get('slider-size-override-mobile-portrait'));
                if ($customMobileSizeEnabled) {
                    $customMobileSliderHeight    = intval($this->get('mobile-portrait-height'));
                    $mobileSideSpacingDifference = 0;
                    if ($customMobileSliderHeight > 0) {
                        if (($slideHeight >= $sliderHeight && $slideHeight < $customMobileSliderHeight) || ($slideHeight < $sliderHeight && $sliderHeight >= $customMobileSliderHeight)) {
                            $mobileSideSpacingDifference = round(($customMobileSliderHeight - $slideHeight) / 2);
                        }
                        if ($slideHeight < $sliderHeight && $sliderHeight < $customMobileSliderHeight) {
                            $mobileSideSpacingDifference = round((($customMobileSliderHeight - $sliderHeight) / 2) + (($sliderHeight - $slideHeight) / 2));
                        }

                        if ($slideHeight >= $customMobileSliderHeight) {
                            $mobileSideSpacingDifference = 0;
                        }
                    }

                    if ($mobileSideSpacingDifference > 0) {
                        if ($this->get('side-spacing-mobile-enable', 0)) {
                            $mobileSideSpacing = array_pad(array_map('intval', explode('|*|', $this->get('side-spacing-mobile'))), 4, 0);
                            $this->set('side-spacing-mobile', ($mobileSideSpacing[0] + $mobileSideSpacingDifference) . '|*|' . $mobileSideSpacing[1] . '|*|' . ($mobileSideSpacing[2] + $mobileSideSpacingDifference) . '|*|' . $mobileSideSpacing[3]);
                        } else {
                            $this->set('side-spacing-mobile-enable', 1);
                            $this->set('side-spacing-mobile', $mobileSideSpacingDifference . '|*|0|*|' . $mobileSideSpacingDifference . '|*|0');
                        }
                    }
                }
            }
        }
    }

}