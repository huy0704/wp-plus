<?php

namespace Nextend\Framework\Asset;

use Nextend\Framework\Data\Data;
use Nextend\Framework\PageFlow;
use Nextend\Framework\Plugin;
use Nextend\Framework\View\Html;

/**
 * Class Manager
 *
 */
class AssetManager {

    /**
     * Helper to safely store AssetManager related optimization data
     *
     * @var Data
     */
    public static $stateStorage;

    /**
     * @var CSS\Asset
     */
    public static $css;

    private static $cssStack = array();

    /**
     * @var Css\Less\Asset
     */
    public static $less;

    private static $lessStack = array();

    /**
     * @var Js\Asset
     */
    public static $js;

    private static $jsStack = array();

    /**
     * @var Fonts\Google\Asset
     */
    public static $googleFonts;

    /**
     * @var Image\Asset
     */
    public static $image;

    private static $imageStack = array();

    private static $googleFontsStack = array();

    public static $cacheAll = true;

    public static $cachedGroups = array();

    public static function getInstance() {
        static $instance = null;
        if (null === $instance) {
            $instance = new self();
            self::createStack();

            Plugin::doAction('n2_assets_manager_started');
        }

        return $instance;
    }

    public static function createStack() {

        self::$stateStorage = new Data();

        self::$css = new Css\Asset();
        array_unshift(self::$cssStack, self::$css);

        self::$less = new Css\Less\Asset();
        array_unshift(self::$lessStack, self::$less);

        self::$js = new Js\Asset();
        array_unshift(self::$jsStack, self::$js);

        self::$googleFonts = new Fonts\Google\Asset();
        array_unshift(self::$googleFontsStack, self::$googleFonts);

        self::$image = new Image\Asset();
        array_unshift(self::$imageStack, self::$image);
    }

    public static function removeStack() {
        if (count(self::$cssStack) > 0) {

            self::$stateStorage = new Data();

            /**
             * @var $previousCSS          Css\Asset
             * @var $previousLESS         Css\Less\Asset
             * @var $previousJS           Js\Asset
             * @var $previousGoogleFons   Fonts\Google\Asset
             * @var $previousImage        Image\Asset
             */
            $previousCSS = array_shift(self::$cssStack);
            self::$css   = self::$cssStack[0];

            $previousLESS = array_shift(self::$lessStack);
            self::$less   = self::$lessStack[0];

            $previousJS = array_shift(self::$jsStack);
            self::$js   = self::$jsStack[0];

            $previousGoogleFons = array_shift(self::$googleFontsStack);
            self::$googleFonts  = self::$googleFontsStack[0];

            $previousImage = array_shift(self::$imageStack);
            self::$image   = self::$imageStack[0];

            return array(
                'css'         => $previousCSS->serialize(),
                'less'        => $previousLESS->serialize(),
                'js'          => $previousJS->serialize(),
                'googleFonts' => $previousGoogleFons->serialize(),
                'image'       => $previousImage->serialize()
            );
        }

        echo "Too much remove stack on the asset manager...";
        PageFlow::exitApplication();

    }

    public static function enableCacheAll() {
        self::$cacheAll = true;
    }

    public static function disableCacheAll() {
        self::$cacheAll = false;
    }

    public static function addCachedGroup($group) {
        if (!in_array($group, self::$cachedGroups)) {
            self::$cachedGroups[] = $group;
        }
    }

    public static function loadFromArray($array) {

        self::$css->unSerialize($array['css']);
        self::$less->unSerialize($array['less']);
        self::$js->unSerialize($array['js']);
        self::$googleFonts->unSerialize($array['googleFonts']);
        self::$image->unSerialize($array['image']);
    }

    /**
     * @param $path
     *
     * @return array|string contains already escaped data
     */
    public static function getCSS($path = false) {
        if (self::$css) {
            if ($path) {
                return self::$css->get();
            }

            return self::$css->getOutput();
        }

        return '';
    }

    /**
     * @param $path
     *
     * @return array|string contains already escaped data
     */
    public static function getJs($path = false) {
        if (self::$js) {
            if ($path) {
                return self::$js->get();
            }

            return self::$js->getOutput();
        }

        return '';
    }

    public static function generateAjaxCSS() {

        return Html::style(self::$css->getAjaxOutput());
    }


    public static function generateAjaxJS() {

        return self::$js->getAjaxOutput();
    }

}