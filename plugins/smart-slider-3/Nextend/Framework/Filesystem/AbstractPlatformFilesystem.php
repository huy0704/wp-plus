<?php

namespace Nextend\Framework\Filesystem;

use Nextend\Framework\Url\Url;

if (!defined('NEXTEND_RELATIVE_CACHE_WEB')) {
    define('NEXTEND_RELATIVE_CACHE_WEB', '/cache/nextend/web');
    define('NEXTEND_CUSTOM_CACHE', 0);
} else {
    define('NEXTEND_CUSTOM_CACHE', 1);
}
if (!defined('NEXTEND_RELATIVE_CACHE_NOTWEB')) {
    define('NEXTEND_RELATIVE_CACHE_NOTWEB', '/cache/nextend/notweb');
}

abstract class AbstractPlatformFilesystem {

    public $paths = array();

    /**
     * @var string Absolute path which match to the baseuri. It must not end with /
     * @example /asd/xyz/wordpress
     */
    protected $_basepath;

    protected $dirPermission = 0777;

    protected $filePermission = 0666;


    protected $translate = array();

    public function init() {

    }

    public function getPaths() {

        return $this->paths;
    }

    public function check($base, $folder) {
        static $checked = array();
        if (!isset($checked[$base . '/' . $folder])) {
            $cacheFolder = $base . '/' . $folder;
            if (!$this->existsFolder($cacheFolder)) {
                if ($this->is_writable($base)) {
                    $this->createFolder($cacheFolder);
                } else {
                    die('<div style="position:fixed;background:#fff;width:100%;height:100%;top:0;left:0;z-index:100000;">' . sprintf('<h2><b>%s</b> is not writable.</h2>', esc_html($base)) . '</div>');
                }
            } else if (!$this->is_writable($cacheFolder)) {
                die('<div style="position:fixed;background:#fff;width:100%;height:100%;top:0;left:0;z-index:100000;">' . sprintf('<h2><b>%s</b> is not writable.</h2>', esc_html($cacheFolder)) . '</div>');
            }
            $checked[$base . '/' . $folder] = true;
        }
    }

    public function measurePermission($testDir) {
        while ('.' != $testDir && !is_dir($testDir)) {
            $testDir = dirname($testDir);
        }

        if ($stat = @stat($testDir)) {
            $this->dirPermission  = $stat['mode'] & 0007777;
            $this->filePermission = $this->dirPermission & 0000666;
        }
    }

    /**
     * @param $path
     *
     * @return mixed
     */
    public function toLinux($path) {
        return str_replace(DIRECTORY_SEPARATOR, '/', $path);
    }

    /**
     * @return string
     */
    public function getBasePath() {

        return $this->_basepath;
    }

    /**
     * @param $path
     */
    public function setBasePath($path) {

        $this->_basepath = $path;
    }

    public function getWebCachePath() {

        return $this->getBasePath() . NEXTEND_RELATIVE_CACHE_WEB;
    }

    public function getNotWebCachePath() {
        return $this->getBasePath() . NEXTEND_RELATIVE_CACHE_NOTWEB;
    }

    /**
     * @param $path
     *
     * @return string
     */
    public function pathToAbsoluteURL($path) {
        return Url::pathToUri($path);
    }

    /**
     * @param $path
     *
     * @return string
     */
    public function pathToRelativePath($path) {

        return preg_replace('/^' . preg_quote($this->_basepath, '/') . '/', '', str_replace('/', DIRECTORY_SEPARATOR, $path));
    }

    /**
     * @param $path
     *
     * @return string
     */
    public function pathToAbsolutePath($path) {

        return $this->_basepath . str_replace('/', DIRECTORY_SEPARATOR, $path);
    }

    /**
     * @param $url
     *
     * @return string
     */
    public function absoluteURLToPath($url) {

        $fullUri = Url::getFullUri();
        if (substr($url, 0, strlen($fullUri)) == $fullUri) {

            return str_replace($fullUri, $this->_basepath, $url);
        }

        return $url;
    }

    /**
     * @param $file
     *
     * @return bool
     */
    public function fileexists($file) {
        return is_file($file);
    }

    /**
     * @param $file
     *
     * @return bool
     */
    public function safefileexists($file) {
        return realpath($file) && is_file($file);
    }

    /**
     *
     * @param $dir
     *
     * @return array Folder names without trailing slash
     */
    public function folders($dir) {
        if (!is_dir($dir)) {
            return array();
        }
        $folders = array();
        foreach (scandir($dir) as $file) {
            if ($file == '.' || $file == '..') continue;
            if (is_dir($dir . DIRECTORY_SEPARATOR . $file)) $folders[] = $file;
        }

        return $folders;
    }

    /**
     * @param $path
     *
     * @return bool
     */
    public function is_writable($path) {
        return is_writable($path);
    }

    /**
     * @param $path
     *
     * @return bool
     */
    public function createFolder($path) {

        return mkdir($path, $this->dirPermission, true);
    }

    public function deleteFolder($dir) {
        if (!is_dir($dir) || is_link($dir)) return unlink($dir);
        foreach (scandir($dir) as $file) {
            if ($file == '.' || $file == '..') continue;
            if (!$this->deleteFolder($dir . DIRECTORY_SEPARATOR . $file)) {
                chmod($dir . DIRECTORY_SEPARATOR . $file, $this->dirPermission);
                if (!$this->deleteFolder($dir . DIRECTORY_SEPARATOR . $file)) return false;
            }
        }

        return rmdir($dir);
    }

    public function existsFolder($path) {
        return is_dir($path);
    }

    public function files($path) {
        $files = array();
        if (is_dir($path)) {
            if ($dh = opendir($path)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file[0] != ".") {
                        $files[] = $file;
                    }
                }
                closedir($dh);
            }
        }

        return $files;
    }

    /**
     * @param $path
     *
     * @return bool
     */
    public function existsFile($path) {

        return file_exists($path);
    }

    /**
     * @param $path
     * @param $buffer
     *
     * @return int
     */
    public function createFile($path, $buffer) {
        return file_put_contents($path, $buffer);
    }

    /**
     * @param $path
     *
     * @return string
     */
    public function readFile($path) {
        return file_get_contents($path);
    }

    /**
     * convert dir alias to normal format
     *
     * @param $pathName
     *
     * @return mixed
     */
    public function dirFormat($pathName) {
        return str_replace(".", DIRECTORY_SEPARATOR, $pathName);
    }

    public function getImagesFolder() {
        return '';
    }

    public function realpath($path) {
        return rtrim(realpath($path), '/\\');
    }

    public function registerTranslate($from, $to) {
        $this->translate[$from] = $to;
    }

    protected function trailingslashit($string) {
        return $this->untrailingslashit($string) . '/';
    }

    protected function untrailingslashit($string) {
        return rtrim($string, '/\\');
    }

    public function convertToRealDirectorySeparator($path) {
        return str_replace(DIRECTORY_SEPARATOR == '/' ? '\\' : '/', DIRECTORY_SEPARATOR, $path);
    }

    public function get_temp_dir() {
        static $temp = '';
        if (defined('SS_TEMP_DIR')) return $this->trailingslashit(SS_TEMP_DIR);

        if ($temp) return $this->trailingslashit($temp);

        if (function_exists('sys_get_temp_dir')) {
            $temp = sys_get_temp_dir();
            if (@is_dir($temp) && $this->is_writable($temp)) return $this->trailingslashit($temp);
        }

        $temp = ini_get('upload_tmp_dir');
        if (@is_dir($temp) && $this->is_writable($temp)) return $this->trailingslashit($temp);

        $temp = $this->getNotWebCachePath() . '/';
        if (is_dir($temp) && $this->is_writable($temp)) return $temp;

        return '/tmp/';
    }

    public function tempnam($filename = '', $dir = '') {
        if (empty($dir)) {
            $dir = $this->get_temp_dir();
        }

        if (empty($filename) || '.' == $filename || '/' == $filename || '\\' == $filename) {
            $filename = time();
        }

        // Use the basename of the given file without the extension as the name for the temporary directory
        $temp_filename = basename($filename);
        $temp_filename = preg_replace('|\.[^.]*$|', '', $temp_filename);

        // If the folder is falsey, use its parent directory name instead.
        if (!$temp_filename) {
            return $this->tempnam(dirname($filename), $dir);
        }

        // Suffix some random data to avoid filename conflicts
        $temp_filename .= '-' . md5(uniqid(rand() . time()));
        $temp_filename .= '.tmp';
        $temp_filename = $dir . $temp_filename;

        $fp = @fopen($temp_filename, 'x');
        if (!$fp && is_writable($dir) && file_exists($temp_filename)) {
            return $this->tempnam($filename, $dir);
        }
        if ($fp) {
            fclose($fp);
        }

        return $temp_filename;
    }
}