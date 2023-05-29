<?php


namespace Nextend\SmartSlider3\Application\Admin\FormManager\Slider;


use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Message\Warning;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Form\Element\Textarea;
use Nextend\Framework\Form\FormTabbed;

class SliderDeveloper extends AbstractSliderTab {

    /**
     * SliderDeveloper constructor.
     *
     * @param FormTabbed $form
     */
    public function __construct($form) {
        parent::__construct($form);

        $this->developer();
    }

    /**
     * @return string
     */
    protected function getName() {
        return 'developer';
    }

    /**
     * @return string
     */
    protected function getLabel() {
        return n2_('Developer');
    }

    protected function developer() {

        $table = new ContainerTable($this->tab, 'developer', n2_('Developer'));

        $row1 = $table->createRow('developer-1');

        /**
         * Used for field removal: /developer/developer/developer-1/controlsBlockCarouselInteraction
         */
        new OnOff($row1, 'controlsBlockCarouselInteraction', n2_('Block carousel'), 1, array(
            'tipLabel'       => n2_('Block carousel'),
            'tipDescription' => n2_('Stops the carousel at the last slide when the source of interaction is vertical touch, vertical pointer, mouse wheel or vertical keyboard.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1806-slider-settings-developer#block-carousel'
        ));

        new OnOff($row1, 'clear-both', n2_('Clear before'), 1, array(
            'tipLabel'       => n2_('Clear before'),
            'tipDescription' => n2_('Closes the unclosed float CSS codes before the slider.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1806-slider-settings-developer#clear-before'
        ));
        new OnOff($row1, 'clear-both-after', n2_('Clear after'), 1, array(
            'tipLabel'       => n2_('Clear after'),
            'tipDescription' => n2_('Allows you to put your slider next to your text.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1806-slider-settings-developer#clear-after'
        ));

        $rowHideScrollbar = $table->createRow('developer-hide-scrollbar');
        new OnOff($rowHideScrollbar, 'overflow-hidden-page', n2_('Hide scrollbar'), 0, array(
            'relatedFieldsOn' => array(
                'slideroverflow-hidden-page-notice'
            )
        ));
        new Warning($rowHideScrollbar, 'overflow-hidden-page-notice', n2_('Your website won\'t be scrollable anymore! All out of screen elements will be hidden.'));

        $row2 = $table->createRow('developer-2');

        new OnOff($row2, 'responsiveFocusUser', n2_('Scroll to slider'), 1, array(
            'tipLabel'        => n2_('Scroll to slider'),
            'tipDescription'  => n2_('The page scrolls back to the slider when the user interacts with it.'),
            'relatedFieldsOn' => array(
                'sliderresponsiveFocusEdge'
            )
        ));

        new Select($row2, 'responsiveFocusEdge', n2_('Edge'), 'auto', array(
            'options' => array(
                'auto'         => n2_('Auto'),
                'top'          => n2_('Top - when needed'),
                'top-force'    => n2_('Top - always'),
                'bottom'       => n2_('Bottom - when needed'),
                'bottom-force' => n2_('Bottom - always'),
            )
        ));

        $row21 = $table->createRow('developer-21');

        new OnOff($row21, 'is-delayed', n2_('Delayed (for lightbox/tabs)'), 0, array(
            'tipLabel'       => n2_('Delayed (for lightbox/tabs)'),
            'tipDescription' => n2_('Delays the loading of the slider until its container gets visible. Useful when you display the slider in a lightbox or tab.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1801-slider-settings-optimize#delayed-for-lightboxtabs'
        ));

        $row211 = $table->createRow('developer-211');

        new OnOff($row211, 'legacy-font-scale', n2_('Legacy font scale'), 0, array(
            'relatedFieldsOn' => array(
                'sliderlegacy-font-scale-notice'
            )
        ));
        new Warning($row211, 'legacy-font-scale-notice', n2_('This feature brings back the non-adaptive font size for absolute layers which were made before version 3.5. Turning on can affect website performance, so we suggest to keep it disabled.
'));

        $row22 = $table->createRow('developer-22');

        new Text($row22, 'classes', n2_('Slider CSS classes'), '', array(
            'tipLabel'       => n2_('Slider CSS classes'),
            'tipDescription' => n2_('You can put custom CSS classes to the slider\'s container.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1806-slider-settings-developer#css'
        ));

        $row3 = $table->createRow('developer-3');
        new Textarea($row3, 'custom-css-codes', 'CSS', '', array(
            'height' => 26
        ));

        $row11 = $table->createRow('developer-11');
        new Number($row11, 'loading-time', n2_('Loading animation waiting time'), 2000, array(
            'wide' => 5,
            'unit' => 'ms',
        ));
        $row10 = $table->createRow('developer-10');
        new Textarea($row10, 'related-posts', n2_('Post IDs') . ' (' . n2_('one per line') . ')', '', array(
            'tipLabel'       => n2_('Post IDs') . ' (' . n2_('one per line') . ')',
            'tipDescription' => n2_('The cache of the posts with the given ID will be cleared upon save.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1806-slider-settings-developer#post-ids-one-per-line',
            'height'         => 26
        ));

    }
}