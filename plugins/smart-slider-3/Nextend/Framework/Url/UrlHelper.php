<?php


namespace Nextend\Framework\Url;

use Nextend\Framework\Request\Request;

class UrlHelper {


    /**
     * Retrieves a modified URL query string.
     *
     * You can rebuild the URL and append query variables to the URL query by using this function.
     * There are two ways to use this function; either a single key and value, or an associative array.
     *
     * Using a single key and value:
     *
     *     add_query_arg( 'key', 'value', 'http://example.com' );
     *
     * Using an associative array:
     *
     *     add_query_arg( array(
     *         'key1' => 'value1',
     *         'key2' => 'value2',
     *     ), 'http://example.com' );
     *
     * Omitting the URL from either use results in the current URL being used
     * (the value of `$_SERVER['REQUEST_URI']`).
     *
     * Values are expected to be encoded appropriately with urlencode() or rawurlencode().
     *
     * Setting any query variable's value to boolean false removes the key (see remove_query_arg()).
     *
     * Important: The return value of add_query_arg() is not escaped by default. Output should be
     * late-escaped with esc_url() or similar to help prevent vulnerability to cross-site scripting
     * (XSS) attacks.
     *
     * @param string|array $key   Either a query variable key, or an associative array of query variables.
     * @param string       $value Optional. Either a query variable value, or a URL to act upon.
     * @param string       $url   Optional. A URL to act upon.
     *
     * @return string New URL query string (unescaped).
     *
     */
    public static function add_query_arg() {
        $args = func_get_args();
        if (is_array($args[0])) {
            if (count($args) < 2 || false === $args[1]) {
                $uri = Request::$SERVER->getVar('REQUEST_URI');
            } else {
                $uri = $args[1];
            }
        } else {
            if (count($args) < 3 || false === $args[2]) {
                $uri = Request::$SERVER->getVar('REQUEST_URI');
            } else {
                $uri = $args[2];
            }
        }

        if ($frag = strstr($uri, '#')) {
            $uri = substr($uri, 0, -strlen($frag));
        } else {
            $frag = '';
        }

        if (0 === stripos($uri, 'http://')) {
            $protocol = 'http://';
            $uri      = substr($uri, 7);
        } elseif (0 === stripos($uri, 'https://')) {
            $protocol = 'https://';
            $uri      = substr($uri, 8);
        } else {
            $protocol = '';
        }

        if (strpos($uri, '?') !== false) {
            list($base, $query) = explode('?', $uri, 2);
            $base .= '?';
        } elseif ($protocol || strpos($uri, '=') === false) {
            $base  = $uri . '?';
            $query = '';
        } else {
            $base  = '';
            $query = $uri;
        }

        self::wp_parse_str($query, $qs);
        $qs = self::urlencode_deep($qs); // this re-URL-encodes things that were already in the query string
        if (is_array($args[0])) {
            foreach ($args[0] as $k => $v) {
                $qs[$k] = $v;
            }
        } else {
            $qs[$args[0]] = $args[1];
        }

        foreach ($qs as $k => $v) {
            if ($v === false) {
                unset($qs[$k]);
            }
        }

        $ret = self::build_query($qs);
        $ret = trim($ret, '?');
        $ret = preg_replace('#=(&|$)#', '$1', $ret);
        $ret = $protocol . $base . $ret . $frag;
        $ret = rtrim($ret, '?');

        return $ret;
    }

    private static function wp_parse_str($string, &$array) {
        parse_str($string, $array);
        if (version_compare(PHP_VERSION, '7.4.0', '<')) {
            if (get_magic_quotes_gpc()) {
                $array = self::stripslashes_deep($array);
            }
        }

    }

    static function urlencode_deep($value) {
        return self::map_deep($value, 'urlencode');
    }

    /**
     * Build URL query based on an associative and, or indexed array.
     *
     * This is a convenient function for easily building url queries. It sets the
     * separator to '&' and uses _http_build_query() function.
     *
     * @param array $data URL-encode key/value pairs.
     *
     * @return string URL-encoded string.
     * @link  https://secure.php.net/manual/en/function.http-build-query.php for more on what
     *        http_build_query() does.
     *
     * @since 2.3.0
     *
     * @see   _http_build_query() Used to build the query
     */
    private static function build_query($data) {
        return self::_http_build_query($data, null, '&', '', false);
    }

    /**
     * From php.net (modified by Mark Jaquith to behave like the native PHP5 function).
     *
     * @param array|object $data        An array or object of data. Converted to array.
     * @param string       $prefix      Optional. Numeric index. If set, start parameter numbering with it.
     *                                  Default null.
     * @param string       $sep         Optional. Argument separator; defaults to 'arg_separator.output'.
     *                                  Default null.
     * @param string       $key         Optional. Used to prefix key name. Default empty.
     * @param bool         $urlencode   Optional. Whether to use urlencode() in the result. Default true.
     *
     * @return string The query string.
     * @since  3.2.0
     * @access private
     *
     * @see    https://secure.php.net/manual/en/function.http-build-query.php
     *
     */
    private static function _http_build_query($data, $prefix = null, $sep = null, $key = '', $urlencode = true) {
        $ret = array();

        foreach ((array)$data as $k => $v) {
            if ($urlencode) {
                $k = urlencode($k);
            }
            if (is_int($k) && $prefix != null) {
                $k = $prefix . $k;
            }
            if (!empty($key)) {
                $k = $key . '%5B' . $k . '%5D';
            }
            if ($v === null) {
                continue;
            } elseif ($v === false) {
                $v = '0';
            }

            if (is_array($v) || is_object($v)) {
                array_push($ret, self::_http_build_query($v, '', $sep, $k, $urlencode));
            } elseif ($urlencode) {
                array_push($ret, $k . '=' . urlencode($v));
            } else {
                array_push($ret, $k . '=' . $v);
            }
        }

        if (null === $sep) {
            $sep = ini_get('arg_separator.output');
        }

        return implode($sep, $ret);
    }

    /**
     * Parses a string into variables to be stored in an array.
     *
     * Uses {@link https://secure.php.net/parse_str parse_str()} and stripslashes if
     * {@link https://secure.php.net/magic_quotes magic_quotes_gpc} is on.
     *
     * @param string $string The string to be parsed.
     * @param array  $array  Variables will be stored in this array.
     *
     * @since 2.2.1
     *
     */
    private static function parse_str($string, &$array) {
        parse_str($string, $array);
        if (version_compare(PHP_VERSION, '7.4.0', '<')) {
            if (get_magic_quotes_gpc()) {
                $array = self::stripslashes_deep($array);
            }
        }
    }

    /**
     * Navigates through an array, object, or scalar, and removes slashes from the values.
     *
     * @param mixed $value The value to be stripped.
     *
     * @return mixed Stripped value.
     * @since 2.0.0
     *
     */
    private static function stripslashes_deep($value) {
        return self::map_deep($value, array(
            self::class,
            'stripslashes_from_strings_only'
        ));
    }

    /**
     * Callback function for `stripslashes_deep()` which strips slashes from strings.
     *
     * @param mixed $value The array or string to be stripped.
     *
     * @return mixed $value The stripped value.
     * @since 4.4.0
     *
     */
    public static function stripslashes_from_strings_only($value) {
        return is_string($value) ? stripslashes($value) : $value;
    }

    /**
     * Maps a function to all non-iterable elements of an array or an object.
     *
     * This is similar to `array_walk_recursive()` but acts upon objects too.
     *
     * @param mixed    $value    The array, object, or scalar.
     * @param callable $callback The function to map onto $value.
     *
     * @return mixed The value with the callback applied to all non-arrays and non-objects inside it.
     * @since 4.4.0
     *
     */
    private static function map_deep($value, $callback) {
        if (is_array($value)) {
            foreach ($value as $index => $item) {
                $value[$index] = self::map_deep($item, $callback);
            }
        } elseif (is_object($value)) {
            $object_vars = get_object_vars($value);
            foreach ($object_vars as $property_name => $property_value) {
                $value->$property_name = self::map_deep($property_value, $callback);
            }
        } else {
            $value = call_user_func($callback, $value);
        }

        return $value;
    }
}