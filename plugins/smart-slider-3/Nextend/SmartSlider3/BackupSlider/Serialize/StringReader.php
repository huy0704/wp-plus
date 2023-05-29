<?php

/**
 * SerializeParser
 *
 * @copyright Jason Judge
 *
 * @license   http://opensource.org/licenses/MIT MIT
 *
 * @link      https://github.com/academe/SerializeParser/blob/master/src/StringReader.php
 */

namespace Nextend\SmartSlider3\BackupSlider\Serialize;

/**
 * Given a string, this class will read through the string using
 * one of a number of terminating rules:
 * - One character.
 * - A specified number of characters.
 * - Until a matching character is found.
 */

class StringReader
{
    protected $pos = 0;
    protected $max = 0;
    protected $string = [];

    public function __construct($string)
    {
        // Split the string up into an array of UTF-8 characters.
        // As an array we can read through it one character at a time.

        //$this->string = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
        //$this->max = count($this->string) - 1;
        $this->string = $string;
        $this->max = strlen($this->string) - 1;
    }

    /**
     * Read the next character from the supplied string.
     * Return null when we have run out of characters.
     */
    public function readOne()
    {
        if ($this->pos <= $this->max) {
            $value = $this->string[$this->pos];
            $this->pos += 1;
        } else {
            $value = null;
        }

        return $value;
    }

    /**
     * Read characters until we reach the given character $char.
     * By default, discard that final matching character and return
     * the rest.
     */
    public function readUntil($char, $discard_char = true)
    {
        $value = '';

        while(null !== ($one = $this->readOne())) {
            if ($one !== $char || !$discard_char) {
                $value .= $one;
            }

            if ($one === $char) {
                break;
            }
        }

        return $value;
    }

    /**
     * Read $count characters, or until we have reached the end,
     * whichever comes first.
     * By default, remove enclosing double-quotes from the result.
     */
    public function read($count, $strip_quotes = true)
    {
        $value = '';

        while($count > 0 && null != ($one = $this->readOne())) {
            $value .= $one;
            $count -= 1;
        }

        return $strip_quotes ? $this->stripQuotes($value) : $value;
    }

    /**
     * Remove a single set of double-quotes from around a string.
     *  abc => abc
     *  "abc" => abc
     *  ""abc"" => "abc"
     *
     * @param string string
     * @returns string
     */
    public function stripQuotes($string)
    {
        // Only remove exactly one quote from the start and the end,
        // and then only if there is one at each end.

        if (strlen($string) < 2 || substr($string, 0, 1) !== '"' || substr($string, -1, 1) !== '"') {
            // Too short, or does not start or end with a quote.
            return $string;
        }

        // Return the middle of the string, from the second character to the second-but-last.
        return substr($string, 1, -1);
    }
}