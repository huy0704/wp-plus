<?php


namespace Nextend\SmartSlider3\Application\Admin\FormManager\Slider;


use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Grouping;
use Nextend\Framework\Form\Element\Message\Notice;
use Nextend\Framework\Form\Element\Message\Warning;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Form\FormTabbed;

class SliderOptimize extends AbstractSliderTab {

    /**
     * SliderOptimize constructor.
     *
     * @param FormTabbed $form
     */
    public function __construct($form) {
        parent::__construct($form);

        $this->loading();

        $this->optimizeSlide();

        $this->other();
    }

    /**
     * @return string
     */
    protected function getName() {
        return 'optimize';
    }

    /**
     * @return string
     */
    protected function getLabel() {
        return n2_('Optimize');
    }

    protected function loading() {

        $table = new ContainerTable($this->tab, 'loading', n2_('Loading'));

        $row1 = $table->createRow('loading-1');

        new Select($row1, 'loading-type', n2_('Loading type'), '', array(
            'options'            => array(
                ''            => n2_('Instant'),
                'afterOnLoad' => n2_('After page loaded'),
                'afterDelay'  => n2_('After delay')
            ),
            'relatedValueFields' => array(
                array(
                    'values' => array(
                        'afterDelay'
                    ),
                    'field'  => array(
                        'sliderdelay'
                    )
                )
            ),
            'tipLabel'           => n2_('Loading type'),
            'tipDescription'     => n2_('If your slider is above the fold, you can load it immediately. Otherwise, you can load it only after the page has loaded.'),
            'tipLink'            => 'https://smartslider.helpscoutdocs.com/article/1801-slider-settings-optimize#loading-type'
        ));

        new Number($row1, 'delay', n2_('Load delay'), 0, array(
            'wide' => 5,
            'unit' => 'ms'
        ));

        new OnOff($row1, 'playWhenVisible', n2_('Play when visible'), 1, array(
            'relatedFieldsOn' => array(
                'sliderplayWhenVisibleAt'
            ),
            'tipLabel'        => n2_('Play when visible'),
            'tipDescription'  => n2_('Makes sure that the autoplay and layer animations only start when your slider is visible.')
        ));
        new Number($row1, 'playWhenVisibleAt', n2_('At'), 50, array(
            'unit' => '%',
            'wide' => 3
        ));
    }

    protected function optimizeSlide() {

        $table = new ContainerTable($this->tab, 'optimize-slide', n2_('Slide background images'));

        $row2 = $table->createRow('optimize-slide-2');

        $memoryLimitText = '';
        if (function_exists('ini_get')) {
            $memory_limit = ini_get('memory_limit');
            if (!empty($memory_limit)) {
                $memoryLimitText = ' (' . $memory_limit . ')';
            }
        }

        new Warning($row2, 'optimize-notice', sprintf(n2_('Convert to WebP and image resizing require a lot of memory. Lift the memory limit%s if you get a blank page.'), $memoryLimitText));

        $row3 = $table->createRow('optimize-slide-3');

        $optimizeWebp = new Grouping($row3, 'optimize-slide-webp');
        new OnOff($optimizeWebp, 'optimize-scale', n2_('Resize'), '0', array(
            'relatedFieldsOn' => array(
                'slideroptimize-slide-width-normal',
                'slideroptimize-quality'
            )
        ));
    

        new Number($optimizeWebp, 'optimize-quality', n2_('Quality'), 70, array(
            'min'  => 0,
            'max'  => 100,
            'unit' => '%',
            'wide' => 3,
            'post' => 'break'
        ));

        new Number($optimizeWebp, 'optimize-slide-width-normal', n2_('Default width'), 1920, array(
            'min'  => 0,
            'unit' => 'px',
            'wide' => 4
        ));

        $row4 = $table->createRow('optimize-slide-4');

        new OnOff($row4, 'optimize-thumbnail-scale', n2_('Resize Thumbnail'), '0', array(
            'relatedFieldsOn' => array(
                'slideroptimize-thumbnail-quality',
                'slideroptimizeThumbnailWidth',
                'slideroptimizeThumbnailHeight'
            )
        ));

        new Number($row4, 'optimize-thumbnail-quality', n2_('Thumbnail Quality'), 70, array(
            'min'  => 0,
            'max'  => 100,
            'unit' => '%',
            'wide' => 3,
            'post' => 'break'
        ));

        new Number($row4, 'optimizeThumbnailWidth', n2_('Thumbnail width'), 100, array(
            'min'  => 0,
            'unit' => 'px',
            'wide' => 4
        ));
        new Number($row4, 'optimizeThumbnailHeight', n2_('Thumbnail height'), 60, array(
            'min'  => 0,
            'unit' => 'px',
            'wide' => 4
        ));

    }

    protected function optimizeLayer() {
    }

    protected function optimizeSliderBackgroundImage() {
    }

    protected function other() {
        if (defined('JETPACK__VERSION')) {
            $table = new ContainerTable($this->tab, 'optimize-other', n2_('Other'));

            $row1 = $table->createRow('optimize-other-1');
        
            new OnOff($row1, 'optimize-jetpack-photon', n2_('JetPack Photon image optimizer'), 0);
        }
    }
}