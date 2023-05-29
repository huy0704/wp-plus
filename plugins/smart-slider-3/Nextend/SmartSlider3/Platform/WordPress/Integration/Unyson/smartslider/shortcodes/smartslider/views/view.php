<?php if (!defined('FW')) {
    die('Forbidden');
}

/**
 * @var array $atts
 */

if (is_numeric($atts['id'])) {
    echo do_shortcode('[smartslider3 slider=' . $atts['id'] . ']');
} else {
    echo do_shortcode('[smartslider3 alias="' . $atts['id'] . '"]');
}