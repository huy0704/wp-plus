<?php


namespace Nextend\SmartSlider3\Install\WordPress;


class InstallWordPress {

    public static function install() {
        $role = get_role('administrator');
        if (is_object($role)) {

            $role->add_cap('smartslider');
            $role->add_cap('smartslider_config');
            $role->add_cap('smartslider_edit');
            $role->add_cap('smartslider_delete');
        }

        wp_get_current_user()->for_site();
    }
}