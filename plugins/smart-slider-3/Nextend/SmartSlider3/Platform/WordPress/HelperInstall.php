<?php


namespace Nextend\SmartSlider3\Platform\WordPress;


use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Install\Install;
use Nextend\SmartSlider3\Install\Tables;
use Nextend\SmartSlider3\Platform\SmartSlider3Platform;
use Nextend\SmartSlider3\SmartSlider3Info;

class HelperInstall {

    public function __construct() {

        global $wp_version;

        if (version_compare($wp_version, '5.1') >= 0) {
            add_action('wp_delete_site', array(
                $this,
                'delete_site'
            ), 10);
        } else {
            add_action('delete_blog', array(
                $this,
                'action_delete_blog'
            ), 10, 2);
        }
    }

    public function installOrUpgrade() {

        if (get_option("n2_ss3_version") != SmartSlider3Info::$completeVersion) {
            $this->install();
        } else if (Request::$REQUEST->getInt('repairss3') && current_user_can('manage_options') && check_admin_referer('repairss3')) {
            $this->install();

            Tables::repair();
            wp_redirect(SmartSlider3Platform::getAdminUrl());
            exit;
        }
    }

    private function install() {

        if (Install::install()) {

            update_option("n2_ss3_version", SmartSlider3Info::$completeVersion);

            if (function_exists('opcache_reset')) {
                opcache_reset();
            }

            return true;
        }

        return false;
    }

    public function delete_site($old_site) {
        $this->action_delete_blog($old_site->blog_id, true);
    }

    public function action_delete_blog($blog_id, $drop) {

        if ($drop) {
            global $wpdb;

            $prefix = $wpdb->get_blog_prefix($blog_id);

            $wpdb->query('DROP TABLE IF EXISTS ' . $prefix . 'nextend2_image_storage, ' . $prefix . 'nextend2_section_storage;');
            $wpdb->query('DROP TABLE IF EXISTS ' . $prefix . 'nextend2_smartslider3_generators, ' . $prefix . 'nextend2_smartslider3_sliders,	' . $prefix . 'nextend2_smartslider3_slides, ' . $prefix . 'nextend2_smartslider3_sliders_xref;');

        }
    }
}