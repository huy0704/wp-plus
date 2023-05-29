<?php

namespace Nextend\Framework\Asset;


use JHtml;
use Nextend\Framework\Asset\Css\Css;
use Nextend\Framework\Asset\Fonts\Google\Google;
use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Font\FontSources;
use Nextend\Framework\Form\Form;
use Nextend\Framework\Platform\Platform;
use Nextend\Framework\Plugin;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\SmartSlider3\Application\Frontend\ApplicationTypeFrontend;
use Nextend\SmartSlider3\Settings;

class Predefined {

    public static function backend($force = false) {
        static $once;
        if ($once != null && !$force) {
            return;
        }
        $once = true;
        wp_enqueue_script('jquery');
        $jQueryFallback = site_url('wp-includes/js/jquery/jquery.js');

        Js::addGlobalInline('_N2._jQueryFallback=\'' . $jQueryFallback . '\';');

        Js::addFirstCode("_N2.r(['AjaxHelper'],function(){_N2.AjaxHelper.addAjaxArray(" . json_encode(Form::tokenizeUrl()) . ");});");

        Plugin::addAction('afterApplicationContent', array(
            FontSources::class,
            'onFontManagerLoadBackend'
        ));
    }

    public static function frontend($force = false) {
        static $once;
        if ($once != null && !$force) {
            return;
        }
        $once = true;
        AssetManager::getInstance();
        if (Platform::isAdmin()) {
            Js::addGlobalInline('window.N2GSAP=' . N2GSAP . ';');
            Js::addGlobalInline('window.N2PLATFORM="' . Platform::getName() . '";');
        }
    
        Js::addGlobalInline('(function(){this._N2=this._N2||{_r:[],_d:[],r:function(){this._r.push(arguments)},d:function(){this._d.push(arguments)}}}).call(window);');

        /*
         * +1px needed for Safari to fix: https://bugs.webkit.org/show_bug.cgi?id=225962
        (function(ua){
            if(ua.indexOf('Safari') > 0 && ua.indexOf('Chrome') === -1){
                document.documentElement.style.setProperty('--ss-safari-fix-225962', '1px');
            }
        })(navigator.userAgent);
        */
        Js::addGlobalInline('!function(a){a.indexOf("Safari")>0&&-1===a.indexOf("Chrome")&&document.documentElement.style.setProperty("--ss-safari-fix-225962","1px")}(navigator.userAgent);');

        Js::addStaticGroup(ApplicationTypeFrontend::getAssetsPath() . "/dist/n2.min.js", 'n2');

        FontSources::onFontManagerLoad($force);
    }

    public static function loadLiteBox() {
    }
}

