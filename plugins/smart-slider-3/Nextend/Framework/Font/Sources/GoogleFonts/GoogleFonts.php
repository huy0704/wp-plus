<?php


namespace Nextend\Framework\Font\Sources\GoogleFonts;

use Nextend\Framework\Asset\AssetManager;
use Nextend\Framework\Asset\Fonts\Google\Google;
use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Font\AbstractFontSource;
use Nextend\Framework\Font\FontSettings;
use Nextend\Framework\Font\FontSources;
use Nextend\Framework\Form\Container\ContainerRowGroup;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Fieldset\FieldsetRow;
use Nextend\Framework\Platform\Platform;
use Nextend\Framework\Plugin;


/*
jQuery.getJSON('https://www.googleapis.com/webfonts/v1/webfonts?sort=alpha&key=AIzaSyBIzBtder0-ef5a6kX-Ri9IfzVwFu21PGw').done(function(data){
    var f = [];
    for(var i = 0; i < data.items.length; i++){
    f.push(data.items[i].family);
    }

    var fontString='';
    f.forEach(function(font){
        fontString+= font+'\n';
    });

    console.log(fontString);
});
*/

class GoogleFonts extends AbstractFontSource {

    protected $name = 'google';

    private static $fonts = array();

    private static $styles = array();

    public function __construct() {
        $lines = file(dirname(__FILE__) . '/families.csv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        for ($i = 0; $i < count($lines); $i++) {
            self::$fonts[strtolower($lines[$i])] = $lines[$i];
        }
        self::$fonts['droid sans']      = 'Noto Sans';
        self::$fonts['droid sans mono'] = 'Roboto Mono';
        self::$fonts['droid serif']     = 'Noto Serif';
    }

    public function getLabel() {
        return 'Google';
    }

    public function renderFields($container) {

        $row1 = new FieldsetRow($container, 'fonts-google-1');
        new OnOff($row1, 'google-enabled', n2_('Frontend'), 1, array(
            'tipLabel'        => n2_('Frontend'),
            'tipDescription'  => n2_('You can load Google Fonts on the frontend.'),
            'relatedFieldsOn' => array(
                'fontsgoogle-cache'
            )
        ));
        new OnOff($row1, 'google-cache', n2_('Save fonts locally'), 0, array(
            'tipLabel'       => n2_('Save fonts locally'),
            'tipDescription' => n2_('You can store the used Google Fonts on your server. This way all Google Fonts will load from your own domain.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1787-fonts#google'
        ));
        new OnOff($row1, 'google-enabled-backend', n2_('Backend'), 1, array(
            'tipLabel'       => n2_('Backend'),
            'tipDescription' => n2_('You can load Google Fonts in the backend.')
        ));

        $rowGroupStyle = new ContainerRowGroup($container, 'fonts-google-style', n2_('Style'));
        $rowStyle      = $rowGroupStyle->createRow('fonts-google-style');
        new OnOff($rowStyle, 'google-style-100', '100', 0);
        new OnOff($rowStyle, 'google-style-100italic', '100 ' . n2_x('Italic', "Font style"), 0);
        new OnOff($rowStyle, 'google-style-200', '200', 0);
        new OnOff($rowStyle, 'google-style-200italic', '200 ' . n2_x('Italic', "Font style"), 0);
        new OnOff($rowStyle, 'google-style-300', '300', 1);
        new OnOff($rowStyle, 'google-style-300italic', '300 ' . n2_x('Italic', "Font style"), 0);
        new OnOff($rowStyle, 'google-style-400', n2_('Normal'), 1);
        new OnOff($rowStyle, 'google-style-400italic', n2_('Normal') . ' ' . n2_x('Italic', "Font style"), 0);
        new OnOff($rowStyle, 'google-style-500', '500', 0);
        new OnOff($rowStyle, 'google-style-500italic', '500 ' . n2_x('Italic', "Font style"), 0);
        new OnOff($rowStyle, 'google-style-600', '600', 0);
        new OnOff($rowStyle, 'google-style-600italic', '600 ' . n2_x('Italic', "Font style"), 0);
        new OnOff($rowStyle, 'google-style-700', '700', 0);
        new OnOff($rowStyle, 'google-style-700italic', '700 ' . n2_x('Italic', "Font style"), 0);
        new OnOff($rowStyle, 'google-style-800', '800', 0);
        new OnOff($rowStyle, 'google-style-800italic', '800 ' . n2_x('Italic', "Font style"), 0);
        new OnOff($rowStyle, 'google-style-900', '900', 0);
        new OnOff($rowStyle, 'google-style-900italic', '900 ' . n2_x('Italic', "Font style"), 0);
    }

    public function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . 'google' . DIRECTORY_SEPARATOR;
    }

    public function onFontManagerLoad($force = false) {
        static $loaded;
        if (!$loaded || $force) {
            $loaded     = true;
            $parameters = FontSettings::getPluginsData();

            if ((!Platform::isAdmin() && $parameters->get('google-enabled', 1)) || (Platform::isAdmin() && $parameters->get('google-enabled-backend', 1))) {
                Google::$enabled = 1;

                for ($i = 100; $i < 1000; $i += 100) {
                    $this->addStyle($parameters, $i);
                    $this->addStyle($parameters, $i . 'italic');
                }
                if (empty(self::$styles)) {
                    self::$styles[] = '300';
                    self::$styles[] = '400';
                }

                Plugin::addAction('fontFamily', array(
                    $this,
                    'onFontFamily'
                ));
            }
        }
    }

    public function onFontManagerLoadBackend() {
        $parameters = FontSettings::getPluginsData();

        if ($parameters->get('google-enabled-backend', 1)) {
            Js::addInline('new _N2.NextendFontServiceGoogle("' . implode(',', self::$styles) . '", ' . json_encode(self::$fonts) . ', ' . json_encode(AssetManager::$googleFonts->getLoadedFamilies()) . ');');
        }
    }

    function addStyle($parameters, $weight) {
        if ($parameters->get('google-style-' . $weight, 0)) {
            self::$styles[] = $weight;
        }
    }

    function onFontFamily($family) {
        $familyLower = strtolower($family);
        if (isset(self::$fonts[$familyLower])) {
            foreach (self::$styles as $style) {
                Google::addFont(self::$fonts[$familyLower], $style);
            }

            return self::$fonts[$familyLower];
        }

        return $family;
    }
}

FontSources::registerSource(GoogleFonts::class);