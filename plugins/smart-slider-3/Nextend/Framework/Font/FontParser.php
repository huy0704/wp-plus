<?php


namespace Nextend\Framework\Font;


use Nextend\Framework\Misc\Base64;
use Nextend\Framework\Model\Section;

class FontParser {

    /**
     * @param $data
     *
     * @return string
     */
    public static function parse($data) {
        if (empty($data)) {
            return '';
        } else if (is_numeric($data)) {
            /**
             * Linked font
             */

            $font = Section::getById($data, 'font');

            if (!$font) {
                /**
                 * Linked font not exists anymore
                 */
                return '';
            }


            if (is_string($font['value'])) {
                /**
                 * Old format when value stored as Base64
                 */
                $decoded = $font['value'];
                if ($decoded[0] != '{') {
                    $decoded = Base64::decode($decoded);
                }

                return $decoded;
            }

            /**
             * Value stored as array
             */
            $value = json_encode($font['value']);
            if ($value == false) {
                return '';
            }

            return $value;
        } else if ($data[0] != '{') {
            return Base64::decode($data);
        }

        return $data;
    }
}