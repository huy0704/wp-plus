<?php


namespace Nextend\SmartSlider3\Application\Admin\FormManager\Slider;


use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\MarginPadding;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Text\FieldImage;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Form\Element\Text\NumberAutoComplete;
use Nextend\Framework\Form\Fieldset\FieldsetRowPlain;
use Nextend\Framework\Form\FormTabbed;
use Nextend\SmartSlider3\Application\Admin\FormManager\FormManagerSlider;
use Nextend\SmartSlider3\Form\Element\PublishSlider;

class SliderGeneral extends AbstractSliderTab {

    /**
     * @var FormManagerSlider
     */
    protected $manager;

    /**
     * SliderGeneral constructor.
     *
     * @param FormManagerSlider $manager
     * @param FormTabbed        $form
     */
    public function __construct($manager, $form) {

        $this->manager = $manager;

        parent::__construct($form);

        $this->publish();
        $this->general();
        $this->alias();
        $this->sliderDesign();
    }

    /**
     * @return string
     */
    protected function getName() {
        return 'general';
    }

    /**
     * @return string
     */
    protected function getLabel() {
        return n2_('General');
    }

    protected function publish() {

        $table = new ContainerTable($this->tab, 'publish', n2_('Publish'));
        $row   = new FieldsetRowPlain($table, 'publish');
        new PublishSlider($row);
    }

    protected function general() {

        $table = new ContainerTable($this->tab, 'general', n2_('General') . ' - ' . $this->manager->getSliderType()
                                                                                                  ->getLabelFull());

        $row1 = $table->createRow('general-1');

        new Text($row1, 'title', n2_('Name'), n2_('Slider'), array(
            'style' => 'width:300px;'
        ));
        new FieldImage($row1, 'thumbnail', n2_('Thumbnail'), '', array(
            'tipLabel'       => n2_('Thumbnail'),
            'tipDescription' => n2_('Slider thumbnail which appears in the slider list.')
        ));

        new Text($row1, 'aria-label', n2_('ARIA label'), '', array(
            'style'          => 'width:200px;',
            'tipLabel'       => n2_('ARIA label'),
            'tipDescription' => n2_('It allows you to label your slider for screen readers.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1722-slider-settings-general#aria-label'
        ));
    }


    protected function alias() {

        $table = new ContainerTable($this->tab, 'alias', n2_('Alias'));

        $row1 = $table->createRow('alias-1');

        new Text($row1, 'alias', n2_('Alias'), '', array(
            'style'          => 'width:200px;',
            'tipLabel'       => n2_('Alias'),
            'tipDescription' => n2_('You can use this alias in the slider\'s shortcode.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1722-slider-settings-general#alias'
        ));

        new OnOff($row1, 'alias-id', n2_('Use as anchor'), '', array(
            'tipLabel'        => n2_('Use as anchor'),
            'tipDescription'  => n2_('Creates an empty div before the slider, using the alias as the ID of this div. As a result, you can use #your-alias in the URL to make the page jump to the slider.'),
            'tipLink'         => 'https://smartslider.helpscoutdocs.com/article/1722-slider-settings-general#use-as-anchor',
            'relatedFieldsOn' => array(
                'slideralias-smoothscroll',
                'slideralias-slideswitch'
            )
        ));

        new OnOff($row1, 'alias-smoothscroll', n2_('Smooth scroll'), '', array(
            'tipLabel'       => n2_('Smooth scroll'),
            'tipDescription' => n2_('The #your-alias urls in links would be forced to smooth scroll to the slider.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1722-slider-settings-general#smooth-scroll-to-this-element'
        ));

        /**
         * Used for field removal: /general/alias/alias-1/alias-slideswitch
         */
        new OnOff($row1, 'alias-slideswitch', n2_('Switch slide'), '', array(
            'tipLabel'        => n2_('Switch slide'),
            'tipDescription'  => n2_('Use #your-alias-2 as an anchor to jump to the slider and switch to the 2nd slide immediately. Use #your-alias-3 for the 3rd slide and so on.'),
            'tipLink'         => 'https://smartslider.helpscoutdocs.com/article/1722-slider-settings-general#allow-slide-switching-for-anchor',
            'relatedFieldsOn' => array(
                'slideralias-slideswitch-scroll'
            )
        ));

        new OnOff($row1, 'alias-slideswitch-scroll', n2_('Scroll to slide'), 1, array(
            'tipLabel'       => n2_('Scroll to slide'),
            'tipDescription' => n2_('The "Switch slide" option won\'t scroll you to the slider. Only the slides will switch.')
        ));
    }

    protected function sliderDesign() {

        $table = new ContainerTable($this->tab, 'design', n2_('Slider design'));

        /**
         * Used for field injection: /general/design/design-1
         */
        $row1 = $table->createRow('design-1');

        new Select($row1, 'align', n2_('Align'), 'normal', array(
            'options' => array(
                'normal' => n2_('Normal'),
                'left'   => n2_('Left'),
                'center' => n2_('Center'),
                'right'  => n2_('Right')
            )
        ));

        /**
         * Used for field injection: /general/design/design-1/margin
         */
        $margin = new MarginPadding($row1, 'margin', n2_('Margin'), '0|*|0|*|0|*|0', array(
            'unit'           => 'px',
            'tipLabel'       => n2_('Margin'),
            'tipDescription' => n2_('Puts a fix margin around your slider.')
        ));

        for ($i = 1; $i < 5; $i++) {
            new Number($margin, 'col-border-width-' . $i, false, '', array(
                'wide' => 3
            ));
        }
    }
}