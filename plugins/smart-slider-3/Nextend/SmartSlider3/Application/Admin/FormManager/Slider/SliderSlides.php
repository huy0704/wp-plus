<?php

namespace Nextend\SmartSlider3\Application\Admin\FormManager\Slider;


use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Form\Element\Text\NumberSlider;
use Nextend\Framework\Form\FormTabbed;

class SliderSlides extends AbstractSliderTab {

    /**
     * SliderSlides constructor.
     *
     * @param FormTabbed $form
     */
    public function __construct($form) {
        parent::__construct($form);

        $this->design();
    }

    /**
     * @return string
     */
    protected function getName() {
        return 'slides';
    }

    /**
     * @return string
     */
    protected function getLabel() {
        return n2_('Slides');
    }

    protected function design() {
        $table = new ContainerTable($this->tab, 'slides-design', n2_('Slides design'));

        /**
         * Used for field injection: /slides/slides-design/slides-design-1
         */
        $row1 = $table->createRow('slides-design-1');

        /**
         * Used for field injection: /slides/slides-design/slides-design-1/backgroundMode
         */
        new Select\FillMode($row1, 'backgroundMode', n2_('Slide background image fill'), 'fill', array(
            'tipLabel'           => n2_('Slide background image fill'),
            'tipDescription'     => n2_('If the size of your image is not the same as your slider\'s, you can improve the result with the filling modes.'),
            'tipLink'            => 'https://smartslider.helpscoutdocs.com/article/1809-slider-settings-slides#slide-background-image-fill',
            'relatedValueFields' => array(
                array(
                    'values' => array(
                        'blurfit'
                    ),
                    'field'  => array(
                        'sliderbackgroundBlurFit'
                    )
                )
            )
        ));

        new NumberSlider($row1, 'backgroundBlurFit', n2_('Background Blur'), 7, array(
            'unit'  => 'px',
            'min'   => 7,
            'max'   => '50',
            'style' => 'width:22px;'
        ));
    }

    protected function slides() {

        /**
         * Used for field removal: /slides/slides-randomize
         */
        $table = new ContainerTable($this->tab, 'slides-randomize', n2_('Randomize'));

        $row1 = $table->createRow('slides-randomize-1');
        new OnOff($row1, 'randomize', n2_('Randomize slides'), 0);
        new OnOff($row1, 'randomizeFirst', n2_('Randomize first'), 0);
        new OnOff($row1, 'randomize-cache', n2_('Cache support'), 1);
        new Number($row1, 'variations', n2_('Cache variations'), 5, array(
            'wide' => 5
        ));

        /**
         * Used for field removal: /slides/other
         */
        $table = new ContainerTable($this->tab, 'other', n2_('Other'));

        $row2 = $table->createRow('other-1');

        new OnOff($row2, 'reverse-slides', n2_('Reverse'), 0, array(
            'tipLabel'       => n2_('Reverse'),
            'tipDescription' => n2_('You can make your slides appear in the slider in a reversed order.')
        ));

        new Number($row2, 'maximumslidecount', n2_('Max count'), 1000, array(
            'wide'           => 4,
            'tipLabel'       => n2_('Max count'),
            'tipDescription' => n2_('You can limit how many slides you want to show from your slider. It\'s best used with the Randomize feature, to improve the experience.')
        ));

        new OnOff($row2, 'maintain-session', n2_('Maintain session'), 0, array(
            'tipLabel'       => n2_('Maintain session'),
            'tipDescription' => n2_('The slider continues from the last viewed slide when the visitor comes back to the page.')
        ));

        $row3 = $table->createRow('slides-2');

        new OnOff($row3, 'global-lightbox', n2_('Backgrounds in lightbox'), 0, array(
            'tipLabel'        => n2_('Backgrounds in lightbox'),
            'tipDescription'  => n2_('Creates a lightbox from your slide background images. This feature only works if all slides have background images.'),
            'tipLink'         => 'https://smartslider.helpscoutdocs.com/article/1809-slider-settings-slides#backgrounds-in-lightbox',
            'relatedFieldsOn' => array(
                'sliderglobal-lightbox-label'
            )
        ));
        new Select($row3, 'global-lightbox-label', n2_('Show label'), '0', array(
            'options' => array(
                '0'        => n2_('No'),
                'name'     => n2_('Only slide name'),
                'namemore' => n2_('Slide name and description')
            )
        ));
    }

    protected function parallax() {


        /**
         * Used for field removal: /slides/slides-parallax
         */
        $table = new ContainerTable($this->tab, 'slides-parallax', n2_('Background parallax'));

        new OnOff($table->getFieldsetLabel(), 'slide-background-parallax', false, 0, array(
            'relatedFieldsOn' => array(
                'table-rows-slides-parallax'
            )
        ));

        $row1 = $table->createRow('slides-parallax-1');
        new Select($row1, 'slide-background-parallax-strength', n2_('Strength'), 50, array(
            'options' => array(
                10  => n2_('Super soft') . ' 10%',
                30  => n2_('Soft') . ' 30%',
                50  => n2_('Normal') . ' 50%',
                75  => n2_('Strong') . ' 75%',
                100 => n2_('Super strong') . ' 100%'
            )
        ));

        new OnOff($row1, 'bg-parallax-tablet', n2_('Tablet'), 0);
        new OnOff($row1, 'bg-parallax-mobile', n2_('Mobile'), 0);
    }
}