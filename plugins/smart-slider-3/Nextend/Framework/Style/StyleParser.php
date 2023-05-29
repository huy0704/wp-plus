<?php


namespace Nextend\Framework\Style;


use Nextend\Framework\Misc\Base64;
use Nextend\Framework\Model\Section;

class StyleParser {

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
             * Linked style
             */

            $style = Section::getById($data, 'style');

            if (!$style) {
                /**
                 * Linked style not exists anymore
                 */
                return '';
            }


            if (is_string($style['value'])) {
                /**
                 * Old format when value stored as Base64
                 */
                $decoded = $style['value'];
                if ($decoded[0] != '{') {
                    $decoded = Base64::decode($decoded);
                }

                return $decoded;
            }

            /**
             * Value stored as array
             */
            $value = json_encode($style['value']);
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