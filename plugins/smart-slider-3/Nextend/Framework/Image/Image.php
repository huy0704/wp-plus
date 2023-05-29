<?php

namespace Nextend\Framework\Image;

use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Image\Joomla\JoomlaImage;
use Nextend\Framework\Image\WordPress\WordPressImage;
use Nextend\Framework\Misc\Base64;
use Nextend\Framework\Pattern\SingletonTrait;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;

class Image {

    use SingletonTrait;

    /**
     * @var AbstractPlatformImage
     */
    private static $platformImage;

    public function __construct() {
        self::$platformImage = new WordPressImage();
    }

    public static function init() {

        if (!self::$platformImage) {
            new Image();
        }
    }

    public static function enqueueHelper() {
        $parameters = array(
            'siteKeywords'     => ResourceTranslator::getResourceIdentifierKeywords(),
            'imageUrls'        => ResourceTranslator::getResourceIdentifierUrls(),
            'protocolRelative' => ResourceTranslator::isProtocolRelative()
        );

        $parameters['placeholderImage']         = '$ss3-frontend$/images/placeholder/image.png';
        $parameters['placeholderRepeatedImage'] = '$ss3-frontend$/images/placeholder/image.png';

        Js::addFirstCode('new _N2.ImageHelper(' . json_encode($parameters) . ');');
    }

    public static function initLightbox() {

        self::$platformImage->initLightbox();
    }

    public static function onImageUploaded($filename) {

        self::$platformImage->onImageUploaded($filename);
    }

    public static function SVGToBase64($image) {

        $ext = pathinfo($image, PATHINFO_EXTENSION);
        if ($ext == 'svg' && ResourceTranslator::isResource($image)) {
            return 'data:image/svg+xml;base64,' . Base64::encode(Filesystem::readFile(ResourceTranslator::toPath($image)));
        }

        return ResourceTranslator::toUrl($image);
    }
}