<?php


namespace Nextend\SmartSlider3\Slider\SliderType\Simple;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Model\Section;
use Nextend\Framework\Parser\Color;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\Sanitize;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\BackgroundAnimation\BackgroundAnimationStorage;
use Nextend\SmartSlider3\Slider\SliderType\AbstractSliderTypeFrontend;

class SliderTypeSimpleFrontend extends AbstractSliderTypeFrontend {

    private $backgroundAnimation = false;

    public function getDefaults() {
        return array(
            'background'                             => '',
            'background-size'                        => 'cover',
            'background-fixed'                       => 0,
            'padding'                                => '0|*|0|*|0|*|0',
            'border-width'                           => 0,
            'border-color'                           => '3E3E3Eff',
            'border-radius'                          => 0,
            'slider-css'                             => '',
            'slide-css'                              => '',
            'animation'                              => 'horizontal',
            'animation-duration'                     => 800,
            'animation-delay'                        => 0,
            'animation-easing'                       => 'easeOutQuad',
            'animation-shifted-background-animation' => 'auto',
            'carousel'                               => 1,

            'background-animation' => '',
            'kenburns-animation'   => ''
        );
    }

    protected function renderType($css) {

        $params = $this->slider->params;

        $this->loadResources();

        $sliderCSS = $params->get('slider-css');

        $this->initSliderBackground('.n2-ss-slider-2');

        $slideCSS = $params->get('slide-css');

        $this->initBackgroundAnimation();

        echo wp_kses($this->openSliderElement(), Sanitize::$basicTags);

        ob_start();

        $slides = $this->slider->getSlides();
        ?>

        <div class="n2-ss-slider-1 n2_ss__touch_element n2-ow"<?php echo empty($sliderCSS) ? '' : ' style="' . esc_attr($sliderCSS) . '"'; ?>>
            <div class="n2-ss-slider-2 n2-ow">
                <?php
                echo wp_kses($this->getBackgroundVideo($params), Sanitize::$videoTags);
                ?>
                <?php if ($this->backgroundAnimation): ?>
                    <div class="n2-ss-background-animation n2-ow"></div>
                <?php endif; ?>
                <div class="n2-ss-slider-3 n2-ow"<?php echo empty($slideCSS) ? '' : ' style="' . esc_attr($slideCSS) . '"'; ?>>

                    <?php

                    // PHPCS - Content already escaped
                    echo $this->slider->staticHtml; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

                    echo wp_kses(Html::openTag('div', array('class' => 'n2-ss-slide-backgrounds n2-ow-all')), Sanitize::$basicTags);
                    foreach ($slides as $slide) {

                        // PHPCS - Content already escaped
                        echo $slide->background; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    }
                    echo wp_kses(Html::closeTag('div'), Sanitize::$basicTags);
                    ?>
                    <div class="n2-ss-slider-4 n2-ow">
                        <?php
                        $this->displaySizeSVGs($css);

                        foreach ($slides as $slide) {
                            $slide->finalize();

                            // PHPCS - Content already escaped
                            echo Html::tag('div', Html::mergeAttributes($slide->attributes, $slide->linkAttributes, array( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                                                                                           'class' => 'n2-ss-slide n2-ow ' . $slide->classes,
                                                                                                                           'style' => $slide->style
                            )), $slide->getHTML());
                        }
                        ?>
                    </div>

                    <?php
                    ?>
                </div>
            </div>
        </div>
        <?php

        // PHPCS - Content already escaped
        echo $this->widgets->wrapSlider(ob_get_clean()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        echo wp_kses($this->closeSliderElement(), Sanitize::$basicTags);

        $this->javaScriptProperties['mainanimation'] = array(
            'type'                       => $params->get('animation'),
            'duration'                   => intval($params->get('animation-duration')),
            'delay'                      => intval($params->get('animation-delay')),
            'ease'                       => $params->get('animation-easing'),
            'shiftedBackgroundAnimation' => $params->get('animation-shifted-background-animation')
        );
        $this->javaScriptProperties['mainanimation']['shiftedBackgroundAnimation'] = 0;
    

        $this->javaScriptProperties['carousel'] = intval($params->get('carousel'));

        $this->style .= $css->getCSS();

        $this->jsDependency[] = 'ss-simple';
    }

    public function getScript() {
        return "_N2.r(" . json_encode(array_unique($this->jsDependency)) . ",function(){new _N2.SmartSliderSimple('{$this->slider->elementId}', " . $this->encodeJavaScriptProperties() . ");});";
    }

    public function loadResources() {

        Js::addStaticGroup(SliderTypeSimple::getAssetsPath() . '/dist/ss-simple.min.js', 'ss-simple');
    }

    private function initBackgroundAnimation() {
        $speed = $this->slider->params->get('background-animation-speed', 'normal');
        $color = Color::colorToRGBA($this->slider->params->get('background-animation-color', '333333ff'));

        $this->javaScriptProperties['bgAnimations'] = array(
            'global' => $this->parseBackgroundAnimations($this->slider->params->get('background-animation', '')),
            'color'  => $color,
            'speed'  => $speed
        );

        $slides    = array();
        $hasCustom = false;

        foreach ($this->slider->getSlides() as $i => $slide) {
            $animation = $this->parseBackgroundAnimations($slide->parameters->get('background-animation'));
            if ($animation) {
                $slideSpeed = $slide->parameters->get('background-animation-speed', 'default');
                if ($slideSpeed == 'default') {
                    $slideSpeed = $speed;
                }
                $slides[$i] = array(
                    'animation' => $this->parseBackgroundAnimations($slide->parameters->get('background-animation')),
                    'speed'     => $slideSpeed
                );

                $localColor = $slide->parameters->get('background-animation-color', '');
                if (!empty($localColor)) {
                    $slides[$i]['color'] = Color::colorToRGBA($localColor);
                }

                if ($slides[$i]) {
                    $hasCustom = true;
                }
            }
        }
        if ($hasCustom) {
            $this->javaScriptProperties['bgAnimations']['slides'] = $slides;
        } else if (!$this->javaScriptProperties['bgAnimations']['global']) {
            $this->javaScriptProperties['bgAnimations'] = 0;
        }

        if ($this->javaScriptProperties['bgAnimations'] != 0) {

            $this->jsDependency[] = "smartslider-backgroundanimation";
            // We have background animation so load the required JS files

            Js::addStaticGroup(SliderTypeSimple::getAssetsPath() . '/dist/smartslider-backgroundanimation.min.js', 'smartslider-backgroundanimation');

            $this->slider->addLess(SliderTypeSimple::getAssetsPath() . '/BackgroundAnimation/style.n2less', array(
                'sliderid' => "~'#{$this->slider->elementId}'",
                "color"    => $color
            ));
        }

    }

    private function parseBackgroundAnimations($backgroundAnimation) {
        $backgroundAnimations = array_unique(array_map('intval', explode('||', $backgroundAnimation)));

        $jsProps = array();

        if (count($backgroundAnimations)) {
            BackgroundAnimationStorage::getInstance();

            foreach ($backgroundAnimations as $animationId) {
                $animation = Section::getById($animationId, 'backgroundanimation');
                if (isset($animation)) {
                    $data = $animation['value']['data'];
                    if (isset($data['displacementImage'])) {
                        $data['displacementImage'] = ResourceTranslator::toUrl($data['displacementImage']);
                    }
                    $jsProps[] = $data;
                }

            }

            if (count($jsProps)) {
                $this->backgroundAnimation = true;

                return $jsProps;
            }
        }

        return 0;
    }
}