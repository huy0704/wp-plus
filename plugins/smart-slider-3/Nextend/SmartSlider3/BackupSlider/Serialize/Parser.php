<?php

/**
 * SerializeParser
 *
 * @copyright Jason Judge
 *
 * @license   http://opensource.org/licenses/MIT MIT
 *
 * @link      https://github.com/academe/SerializeParser/blob/master/src/Parser.php
 *
 * The original code was modified by Nextendweb. We stripped the not relevant parts.
 */

namespace Nextend\SmartSlider3\BackupSlider\Serialize;

use Nextend\Framework\Notification\Notification;

class Parser {

    protected $allowedClassNames = [
        'Nextend\SmartSlider3\BackupSlider\BackupData',
        'Nextend\SmartSlider3\Slider\SliderParams'
    ];

    /**
     * This is the recursive parser.
     */
    protected function doParse(StringReader $string) {

        // May be : or ; as a terminator, depending on what the data
        // type is.

        $type = substr($string->read(2), 0, 1);

        switch ($type) {
            case 'a':
                // Associative array: a:length:{[index][value]...}
                $count = (int)$string->readUntil(':');

                // Eat the opening "{" of the array.
                $string->read(1);

                for ($i = 0; $i < $count; $i++) {
                    $this->doParse($string);//array key
                    $this->doParse($string);//array value
                }

                // Eat "}" terminating the array.
                $string->read(1);

                break;

            case 'O':
                // Object: O:length:"class":length:{[property][value]...}
                $len = (int)$string->readUntil(':');

                // +2 for quotes
                $class = $string->read(2 + $len);

                if (!in_array($class, $this->allowedClassNames)) {
                    Notification::error(sprintf(n2_('The importing failed as the slider export contained an invalid class name: %1$s'), '<br>' . $class));


                    return false;
                }

                // Eat the separator
                $string->read(1);


                // Read the number of properties.
                $len = (int)$string->readUntil(':');

                // Eat "{" holding the properties.
                $string->read(1);

                for ($i = 0; $i < $len; $i++) {
                    $this->doParse($string);//prop key
                    $this->doParse($string);//prop value
                }

                // Eat "}" terminating properties.
                $string->read(1);

                break;

            case 's':
                $len = (int)$string->readUntil(':');
                $string->read($len + 2);

                // Eat the separator
                $string->read(1);
                break;

            case 'i':
                $string->readUntil(';');
                break;

            case 'd':
                $string->readUntil(';');
                break;

            case 'b':
                // Boolean is 0 or 1
                $string->read(2);
                break;

            case 'N':
                break;

            default:
                Notification::error(sprintf(n2_('The importing failed as we are not able to unserialize the type: "%s"'), '<br>' . $type));
                return false;
        }

        return true;
    }

    /**
     * @param $string
     *
     *
     * Checks if the $string contains any Class names which are not allowed by us!
     * This is the initial entry point into the recursive parser.
     *
     * @return bool
     * @throws \Exception
     */
    public function isValidData($string) {
        return $this->doParse(new StringReader($string));
    }
}