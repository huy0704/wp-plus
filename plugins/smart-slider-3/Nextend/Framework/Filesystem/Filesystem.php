<?php

namespace Nextend\Framework\Filesystem;

use Nextend\Framework\Filesystem\Joomla\JoomlaFilesystem;
use Nextend\Framework\Filesystem\WordPress\WordPressFilesystem;

class Filesystem {

    /**
     * @var AbstractPlatformFilesystem
     */
    private static $platformFilesystem;

    public function __construct() {
        self::$platformFilesystem = new WordPressFilesystem();

        self::$platformFilesystem->init();
    }

    /**
     * @return AbstractPlatformFilesystem
     */
    public static function get() {

        return self::$platformFilesystem;
    }

    public static function getPaths() {

        return self::$platformFilesystem->getPaths();
    }

    public static function check($base, $folder) {

        self::$platformFilesystem->check($base, $folder);
    }

    public static function measurePermission($testDir) {

        self::$platformFilesystem->measurePermission($testDir);
    }

    /**
     * @param $path
     *
     * @return string
     */
    public static function toLinux($path) {

        return self::$platformFilesystem->toLinux($path);
    }

    /**
     * @return string
     */
    public static function getBasePath() {

        return self::$platformFilesystem->getBasePath();
    }

    /**
     * @param $path
     */
    public static function setBasePath($path) {

        self::$platformFilesystem->setBasePath($path);
    }

    public static function getWebCachePath() {

        return self::$platformFilesystem->getWebCachePath();
    }

    public static function getNotWebCachePath() {

        return self::$platformFilesystem->getNotWebCachePath();
    }

    /**
     * @param $path
     *
     * @return string
     */
    public static function pathToAbsoluteURL($path) {

        return self::$platformFilesystem->pathToAbsoluteURL($path);
    }

    /**
     * @param $path
     *
     * @return string
     */
    public static function pathToRelativePath($path) {

        return self::$platformFilesystem->pathToRelativePath($path);
    }

    /**
     * @param $path
     *
     * @return string
     */
    public static function pathToAbsolutePath($path) {

        return self::$platformFilesystem->pathToAbsolutePath($path);
    }

    /**
     * @param $url
     *
     * @return string
     */
    public static function absoluteURLToPath($url) {

        return self::$platformFilesystem->absoluteURLToPath($url);
    }

    /**
     * @param $file
     *
     * @return bool
     */
    public static function fileexists($file) {

        return self::$platformFilesystem->fileexists($file);
    }

    /**
     * @param $file
     *
     * @return bool
     */
    public static function safefileexists($file) {

        return self::$platformFilesystem->safefileexists($file);
    }

    /**
     *
     * @param $dir
     *
     * @return array Folder names without trailing slash
     */
    public static function folders($dir) {

        return self::$platformFilesystem->folders($dir);
    }

    /**
     * @param $path
     *
     * @return bool
     */
    public static function is_writable($path) {

        return self::$platformFilesystem->is_writable($path);
    }

    /**
     * @param $path
     *
     * @return bool
     */
    public static function createFolder($path) {

        return self::$platformFilesystem->createFolder($path);
    }

    /**
     * @param $dir
     *
     * @return bool
     */
    public static function deleteFolder($dir) {

        return self::$platformFilesystem->deleteFolder($dir);
    }

    /**
     * @param $path
     *
     * @return bool
     */
    public static function existsFolder($path) {

        return self::$platformFilesystem->existsFolder($path);
    }

    /**
     * @param $path
     *
     * @return array
     */
    public static function files($path) {

        return self::$platformFilesystem->files($path);
    }

    /**
     * @param $path
     *
     * @return bool
     */
    public static function existsFile($path) {

        return self::$platformFilesystem->existsFile($path);
    }

    /**
     * @param $path
     * @param $buffer
     *
     * @return int
     */
    public static function createFile($path, $buffer) {

        return self::$platformFilesystem->createFile($path, $buffer);
    }

    /**
     * @param $path
     *
     * @return string
     */
    public static function readFile($path) {

        return self::$platformFilesystem->readFile($path);
    }

    /**
     * convert dir alias to normal format
     *
     * @param $pathName
     *
     * @return mixed
     */
    public static function dirFormat($pathName) {

        return self::$platformFilesystem->dirFormat($pathName);
    }

    public static function getImagesFolder() {

        return self::$platformFilesystem->getImagesFolder();
    }

    public static function realpath($path) {

        return self::$platformFilesystem->realpath($path);
    }

    public static function registerTranslate($from, $to) {

        self::$platformFilesystem->registerTranslate($from, $to);
    }

    public static function convertToRealDirectorySeparator($path) {

        return self::$platformFilesystem->convertToRealDirectorySeparator($path);
    }

    public static function get_temp_dir() {

        return self::$platformFilesystem->get_temp_dir();
    }

    public static function tempnam($filename = '', $dir = '') {

        return self::$platformFilesystem->tempnam($filename = '', $dir = '');
    }
}

new Filesystem();