<?php


namespace Nextend\SmartSlider3\Platform\WordPress;


use Nextend\Framework\Asset\AssetManager;
use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Asset\Predefined;
use Nextend\Framework\Pattern\GetAssetsPathTrait;
use Nextend\Framework\Pattern\SingletonTrait;
use Nextend\SmartSlider3\Application\ApplicationSmartSlider3;
use Nextend\SmartSlider3\Settings;
use Nextend\SmartSlider3\SmartSlider3Info;
use Nextend\Framework\Request\Request;

class HelperTinyMCE {

    use SingletonTrait, GetAssetsPathTrait;

    protected function init() {
        add_action('admin_init', array(
            $this,
            'addButton'
        ));
    }

    public function addButton() {

        if ((!current_user_can('edit_posts') && !current_user_can('edit_pages'))) {
            return;
        }
        if (in_array(basename(Request::$SERVER->getVar('PHP_SELF')), array(
            'post-new.php',
            'page-new.php',
            'post.php',
            'page.php'
        ))) {

            $this->addForced();

            if (intval(Settings::get('editor-icon', 1))) {
                if (get_user_option('rich_editing') == 'true') {
                    add_filter('mce_external_plugins', array(
                        $this,
                        'mceAddPlugin'
                    ));
                    add_filter('mce_buttons', array(
                        $this,
                        'mceRegisterButton'
                    ));
                }
            }
        }
    }

    public function addForcedFrontend($action = 'wp_print_footer_scripts') {
        $this->addForced('wp_print_footer_scripts');
    }

    public function addForced($action = 'admin_print_footer_scripts') {
        static $added = false;
        if (!$added) {

            AssetManager::getInstance();

            Js::addGlobalInline('window.N2DISABLESCHEDULER=1;');

            Predefined::frontend();
            Predefined::backend();
            ApplicationSmartSlider3::getInstance()
                                   ->getApplicationTypeAdmin()
                                   ->enqueueAssets();
            $this->initButtonDialog();

            add_action($action, array(
                $this,
                'addButtonDialog'
            ));

            $added = true;
        }
    }

    public function mceAddPlugin($plugin_array) {


        $plugin_array['smartslider3'] = self::getAssetsUri() . '/dist/wordpress-tinymce.min.js';

        return $plugin_array;
    }

    public function mceRegisterButton($buttons) {
        array_push($buttons, "|", "smartslider3");

        return $buttons;
    }

    public function initButtonDialog() {

        wp_register_style('smart-slider-editor', self::getAssetsUri() . '/dist/wordpress-editor.min.css', array(), SmartSlider3Info::$version, 'screen');
        wp_enqueue_style('smart-slider-editor');
    }

    public function addButtonDialog() {
        ?>
        <script>
            window.NextendSmartSliderWPTinyMCEModal = function (ed) {
                _N2.SelectSlider(n2_('Select Slider'), function (id, alias) {
                    if (alias) {
                        ed.execCommand('mceInsertContent', false, '<div>[smartslider3 alias="' + alias + '"]</div>');
                    } else if (id) {
                        ed.execCommand('mceInsertContent', false, '<div>[smartslider3 slider=' + id + ']</div>');
                    }
                });
            };

            if (typeof QTags !== 'undefined') {
                QTags.addButton('smart-slider-3', 'Smart Slider', function () {
                    _N2.SelectSlider(n2_('Select Slider'), function (id, alias) {
                        if (alias) {
                            QTags.insertContent("\n" + '<div>[smartslider3 alias="' + alias + '"]</div>');
                        } else if (id) {
                            QTags.insertContent("\n" + '<div>[smartslider3 slider=' + id + ']</div>');
                        }
                    });
                });
            }

            window.NextendSmartSliderSelectModal = function ($input) {
                _N2.SelectSlider(n2_('Select Slider'), function (id, alias) {
                    var idOrAlias = false;
                    if (alias) {
                        idOrAlias = alias;
                    } else if (id) {
                        idOrAlias = id;
                    }

                    if (idOrAlias) {
                        if (typeof $input === 'function') {
                            $input = $input();
                        }
                        $input.val(idOrAlias).trigger('input').trigger('change');
                    }
                });

                return false;
            };

            window.NextendSmartSliderSelectModalCallback = function (cb) {
                _N2.SelectSlider(n2_('Select Slider'), function (id, alias) {
                    var idOrAlias = false;
                    if (alias) {
                        idOrAlias = alias;
                    } else if (id) {
                        idOrAlias = id;
                    }

                    if (idOrAlias) {
                        cb(idOrAlias);
                    }
                });

                return false;
            }
        </script>
        <?php
    }
}