<?php


namespace Nextend\SmartSlider3\Conflict;

use Nextend\Framework\Database\Database;
use Nextend\Framework\Model\StorageSectionManager;

class Conflict {

    /**
     * @var Conflict
     */
    private static $platformConflict;

    protected $conflicts = array();

    protected $debugConflicts = array();

    public $curlLog = false;

    /**
     * @return Conflict
     */
    final public static function getInstance() {

        if (!isset(self::$platformConflict)) {
            self::$platformConflict = new WordPress\WordPressConflict();
        }

        return static::$platformConflict;
    }


    protected function __construct() {

        $this->testPHPINIMaxInputVars();
        $this->testOpcache();
        $this->testApiConnection();
        $this->testDatabaseTables();

        $this->testGantry4();
    }

    public function getConflicts() {
        return $this->conflicts;
    }

    public function getDebugConflicts() {

        return $this->debugConflicts;
    }

    public function getCurlLog() {

        return $this->curlLog;
    }

    protected function displayConflict($title, $description, $url = '') {
        $this->conflicts[]      = '<b>' . $title . '</b> - ' . $description . (!empty($url) ? ' <a href="' . $url . '" target="_blank">' . n2_('Learn more') . '</a>' : '');
        $this->debugConflicts[] = $title;
    }

    private function testPHPINIMaxInputVars() {
        if (function_exists('ini_get')) {
            $max_input_vars = intval(ini_get('max_input_vars'));
            if ($max_input_vars < 1000) {
                $this->displayConflict('PHP - max_input_vars', sprintf(n2_('Increase %1$s in php.ini to 1000 or more. Current value: %2$s'), '<b>max_input_vars</b>', $max_input_vars), 'https://smartslider.helpscoutdocs.com/article/1717-wordpress-installation');
            }
        }
    }

    private function testOpcache() {
        if (function_exists('ini_get') && ini_get('opcache.enable')) {
            $revalidateFrequenty = intval(ini_get('opcache.revalidate_freq'));
            if ($revalidateFrequenty >= 15) {
                $this->displayConflict('PHP - opcache', sprintf(n2_('Decrease %1$s below 15 in php.ini to prevent fatal errors on plugin updates. Current value: %2$s'), '<b>opcache.revalidate_freq</b>', $revalidateFrequenty), 'https://smartslider.helpscoutdocs.com/article/1717-wordpress-installation');
            }
        }
    }

    private function testApiConnection() {
        $log = StorageSectionManager::getStorage('smartslider')
                                    ->get('log', 'api');
        if (!empty($log)) {
            if (strpos($log, 'ACTION_MISSING') === false) {
                $this->displayConflict(n2_('Unable to connect to the API'), sprintf(n2_('See %1$sDebug Information%2$s for more details!'), '<b>', '</b>'));

                $this->curlLog = json_decode($log, true);
            }
        }
    }

    private function testDatabaseTables() {
        $tables = array(
            '#__nextend2_image_storage',
            '#__nextend2_section_storage',
            '#__nextend2_smartslider3_generators',
            '#__nextend2_smartslider3_sliders',
            '#__nextend2_smartslider3_sliders_xref',
            '#__nextend2_smartslider3_slides'
        );

        foreach ($tables as $table) {
            $table  = Database::parsePrefix($table);
            $result = Database::queryRow('SHOW TABLES LIKE :table', array(
                ":table" => $table
            ));

            if (empty($result)) {
                $this->conflicts[]      = n2_('MySQL table missing') . ': ' . $table;
                $this->debugConflicts[] = n2_('MySQL table missing') . ': ' . $table;
            }
        }
    }

    private function testGantry4() {

        if (defined('GANTRY_VERSION') && version_compare(GANTRY_VERSION, '5', '<')) {
            $this->displayConflict('Gantry 4', n2_('Your theme uses an outdated MooTools library which is not compatible.'), 'https://wordpress.org/support/topic/mootools-overwrites-the-native-bind/');
        }
    }
}