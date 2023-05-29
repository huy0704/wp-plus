<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (is_numeric($settings->sliderid)) {
    echo '[smartslider3 slider=' . esc_html($settings->sliderid) . ']';
} else {
    echo '[smartslider3 alias="' . esc_html($settings->sliderid) . '"]';
}