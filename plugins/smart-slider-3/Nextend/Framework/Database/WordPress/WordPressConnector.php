<?php

namespace Nextend\Framework\Database\WordPress;

use Nextend\Framework\Database\AbstractPlatformConnector;
use Nextend\Framework\Notification\Notification;
use Nextend\SmartSlider3\Platform\SmartSlider3Platform;
use wpdb;

class WordPressConnector extends AbstractPlatformConnector {

    /** @var wpdb $wpdb */
    private $db;

    public function __construct() {
        /** @var wpdb $wpdb */ global $wpdb;
        $this->db      = $wpdb;
        $this->_prefix = $wpdb->prefix;

        WordPressConnectorTable::init($this, $this->db);
    }

    public function query($query, $attributes = false) {
        if ($attributes) {
            foreach ($attributes as $key => $value) {
                $replaceTo = is_numeric($value) ? $value : $this->db->prepare('%s', $value);
                $query     = str_replace($key, $replaceTo, $query);
            }
        }

        return $this->checkError($this->db->query($query));
    }

    public function insertId() {
        return $this->db->insert_id;
    }

    private function _querySQL($query, $attributes = false) {

        $args = array('');

        if ($attributes) {
            foreach ($attributes as $key => $value) {
                $replaceTo = is_numeric($value) ? '%d' : '%s';
                $query     = str_replace($key, $replaceTo, $query);
                $args[]    = $value;
            }
        }


        if (count($args) > 1) {
            $args[0] = $query;

            return call_user_func_array(array(
                $this->db,
                'prepare'
            ), $args);
        } else {
            return $query;
        }
    }

    public function queryRow($query, $attributes = false) {
        return $this->checkError($this->db->get_row($this->_querySQL($query, $attributes), ARRAY_A));
    }

    public function queryAll($query, $attributes = false, $type = "assoc", $key = null) {
        $result = $this->checkError($this->db->get_results($this->_querySQL($query, $attributes), $type == 'assoc' ? ARRAY_A : OBJECT_K));
        if (!$key) {
            return $result;
        }
        $realResult = array();

        for ($i = 0; $i < count($result); $i++) {
            $key              = $type == 'assoc' ? $result[i][$key] : $result[i]->{$key};
            $realResult[$key] = $result[i];
        }

        return $realResult;
    }

    public function quote($text, $escape = true) {
        return '\'' . (esc_sql($text)) . '\'';
    }

    public function quoteName($name, $as = null) {
        if (strpos($name, '.') !== false) {
            return $name;
        } else {
            $q = '`';
            if (strlen($q) == 1) {
                return $q . $name . $q;
            } else {
                return $q[0] . $name . $q[1];
            }
        }
    }

    public function checkError($result) {
        if (!empty($this->db->last_error)) {
            if (is_admin()) {
                $lastError = $this->db->last_error;
                $lastQuery = $this->db->last_query;

                $possibleErrors = array(
                    'Duplicate entry'    => 'Your table column doesn\'t have auto increment, while it should have.',
                    'command denied'     => 'Your database user has limited access and isn\'t able to run all commands which are necessary for our code to work.',
                    'Duplicate key name' => 'Your database user has limited access and isn\'t able to run DROP or ALTER database commands.',
                    'Can\'t DROP'        => 'Your database user has limited access and isn\'t able to run DROP or ALTER database commands.'
                );

                $errorMessage = sprintf(n2_('If you see this message after the repair database process, please %1$scontact us%2$s with the log:'), '<a href="https://smartslider3.com/contact-us/support/" target="_blank">', '</a>');

                foreach ($possibleErrors as $error => $cause) {
                    if (strpos($lastError, $error) !== false) {
                        $errorMessage = n2_($cause) . ' ' . n2_('Contact your server host and ask them to fix this for you!');
                        break;
                    }
                }

                $message = array(
                    n2_('Unexpected database error.'),
                    '',
                    '<a href="' . wp_nonce_url(add_query_arg(array('repairss3' => '1'), SmartSlider3Platform::getAdminUrl()), 'repairss3') . '" class="n2_button n2_button--big n2_button--blue">' . n2_('Try to repair database') . '</a>',
                    '',
                    $errorMessage,
                    '',
                    '<b>' . $lastError . '</b>',
                    $lastQuery
                );
                Notification::error(implode('<br>', $message), array(
                    'wide' => true
                ));
            }
        }

        return $result;
    }

    public function getCharsetCollate() {

        return $this->db->get_charset_collate();
    }
}