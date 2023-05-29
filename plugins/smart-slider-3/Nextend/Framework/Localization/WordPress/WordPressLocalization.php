<?php

namespace Nextend\Framework\Localization\WordPress;

use Mo;
use Nextend\Framework\Localization\AbstractLocalization;
use NOOP_Translations;
use function get_locale;
use function get_user_locale;
use function is_admin;

class WordPressLocalization extends AbstractLocalization {


    public function getLocale() {

        return is_admin() && function_exists('\\get_user_locale') ? get_user_locale() : get_locale();
    }

    public function createMo() {

        require_once ABSPATH . WPINC . '/pomo/mo.php';

        return new MO();
    }

    public function createNOOP_Translations() {

        require_once ABSPATH . WPINC . '/pomo/mo.php';

        return new NOOP_Translations();
    }
}