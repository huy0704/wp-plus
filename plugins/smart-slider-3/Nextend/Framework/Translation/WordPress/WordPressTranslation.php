<?php

namespace Nextend\Framework\Translation\WordPress;

use Nextend\Framework\Translation\AbstractTranslation;

class WordPressTranslation extends AbstractTranslation {

    public function __construct() {

        if (defined('QTRANSLATE_FILE')) {
            add_filter('nextend_translation', 'qtranxf_useCurrentLanguageIfNotFoundShowAvailable', 0);
        }
    }

    public function _($text) {
        return apply_filters('nextend_translation', $text);
    }

    public function getLocale() {
        return is_admin() && function_exists('get_user_locale') ? get_user_locale() : get_locale();
    }
}