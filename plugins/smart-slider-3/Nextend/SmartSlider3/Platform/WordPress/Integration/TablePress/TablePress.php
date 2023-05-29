<?php


namespace Nextend\SmartSlider3\Platform\WordPress\Integration\TablePress;


use Nextend\SmartSlider3\Platform\WordPress\Shortcode\Shortcode;

class TablePress {

    private $level = 0;

    public function __construct() {

        if (defined('TABLEPRESS_ABSPATH')) {
            $this->init();
        }
    }

    public function init() {
        add_filter('pre_do_shortcode_tag', array(
            $this,
            'before'
        ), 10, 2);
        add_filter('do_shortcode_tag', array(
            $this,
            'after'
        ), 10, 2);

    }

    public function before($ret, $tag) {

        if ($tag == 'table') {
            $this->level++;
            if ($this->level == 1) {
                Shortcode::shortcodeModeToSkip();
            }
        }

        return $ret;
    }

    public function after($output, $tag) {

        if ($tag == 'table') {
            $this->level--;
            if ($this->level <= 0) {
                Shortcode::shortcodeModeRestore();

                global $shortcode_tags;
                $tmp            = $shortcode_tags;
                $shortcode_tags = array(
                    'smartslider3' => array(
                        Shortcode::class,
                        'doShortcode'
                    )
                );

                $output = do_shortcode($output);

                $shortcode_tags = $tmp;
            }
        }

        return $output;
    }
}