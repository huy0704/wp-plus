<?php


namespace Nextend\Framework;


use Nextend\Framework\Model\Section;

class Settings {

    private static $data;

    public function __construct() {

        $config = array(
            'jquery'                 => 1,
            'scriptattributes'       => '',
            'javascript-inline'      => 'head',
            'protocol-relative'      => 1,
            'force-english-backend'  => 0,
            'frontend-accessibility' => 1,
            'curl'                   => 1,
            'curl-clean-proxy'       => 0,
            'async-non-primary-css'  => 0,
            'icon-fa'                => 1,
            'header-preload'         => 0
        );
        if (!defined('NEXTEND_INSTALL')) {
            global $wpdb;
            if ($wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix . "nextend2_section_storage'") != $wpdb->prefix . 'nextend2_section_storage') {
                define('NEXTEND_INSTALL', 1);
            }
        }
    

        if (!defined('NEXTEND_INSTALL')) {
            foreach (Section::getAll('system', 'global') as $data) {
                $config[$data['referencekey']] = $data['value'];
            }
        }

        self::$data = new Data\Data();
        self::$data->loadArray($config);
    }

    public static function get($key, $default = '') {
        return self::$data->get($key, $default);
    }

    public static function getAll() {
        return self::$data->toArray();
    }

    public static function set($key, $value) {
        self::$data->set($key, $value);
        Section::set('system', 'global', $key, $value, 1, 1);
    }

    public static function setAll($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (self::$data->get($key, null) !== null) {
                    self::set($key, $value);
                }
            }

            return true;
        }

        return false;
    }
}

new Settings();