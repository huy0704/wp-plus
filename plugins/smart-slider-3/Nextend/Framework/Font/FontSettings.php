<?php


namespace Nextend\Framework\Font;


use Nextend\Framework\Data\Data;
use Nextend\Framework\Model\Section;

class FontSettings {

    /**
     * @var Data
     */
    private static $data;

    /**
     * @var Data
     */
    private static $pluginsData;

    public function __construct() {
        self::load();
        FontRenderer::setDefaultFont(self::$data->get('default-family'));
    }

    public static function load() {

        self::$data = new Data(array(
            'default-family'  => n2_x('Roboto,Arial', 'Default font'),
            'preset-families' => n2_x(implode("\n", array(
                "Abel",
                "Arial",
                "Arimo",
                "Average",
                "Bevan",
                "Bitter",
                "'Bree Serif'",
                "Cabin",
                "Calligraffitti",
                "Chewy",
                "Comfortaa",
                "'Covered By Your Grace'",
                "'Crafty Girls'",
                "'Dancing Script'",
                "'Noto Sans'",
                "'Noto Serif'",
                "'Francois One'",
                "'Fredoka One'",
                "'Gloria Hallelujah'",
                "'Happy Monkey'",
                "'Josefin Slab'",
                "Lato",
                "Lobster",
                "'Luckiest Guy'",
                "Montserrat",
                "'Nova Square'",
                "Nunito",
                "'Open Sans'",
                "Oswald",
                "Oxygen",
                "Pacifico",
                "'Permanent Marker'",
                "'Playfair Display'",
                "'PT Sans'",
                "'Poiret One'",
                "Raleway",
                "Roboto",
                "'Rock Salt'",
                "Quicksand",
                "Satisfy",
                "'Squada One'",
                "'The Girl Next Door'",
                "'Titillium Web'",
                "'Varela Round'",
                "Vollkorn",
                "'Walter Turncoat'"
            )), 'Default font family list'),
            'plugins'         => array()
        ));

        foreach (Section::getAll('system', 'fonts') as $data) {
            self::$data->set($data['referencekey'], $data['value']);
        }

        self::$pluginsData = new Data(self::$data->get('plugins'), true);
    }

    public static function store($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (self::$data->has($key)) {
                    self::$data->set($key, $value);
                    Section::set('system', 'fonts', $key, $value, 1, 1);
                    unset($data[$key]);
                }
            }
            if (count($data)) {
                self::$pluginsData = new Data($data);
                Section::set('system', 'fonts', 'plugins', self::$pluginsData->toJSON(), 1, 1);

            }

            return true;
        }

        return false;
    }

    /**
     * @return Data
     */
    public static function getData() {

        return self::$data;
    }

    /**
     * @return Data
     */
    public static function getPluginsData() {

        return self::$pluginsData;
    }

    public static function getDefaultFamily() {
        return self::$data->get('default-family');
    }

    /**
     * @return array
     */
    public static function getPresetFamilies() {
        return array_filter(explode("\n", self::$data->get('preset-families')));
    }
}

new FontSettings();