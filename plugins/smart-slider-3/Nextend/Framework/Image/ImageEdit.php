<?php


namespace Nextend\Framework\Image;


use Exception;
use Nextend\Framework\Cache\CacheImage;
use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Misc\Base64;
use Nextend\Framework\Parser\Color;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\Url\Url;

class ImageEdit {

    public static function resizeImage($group, $imageUrlOrPath, $targetWidth, $targetHeight, $lazy = false, $mode = 'cover', $backgroundColor = false, $resizeRemote = false, $quality = 100, $optimize = false, $x = 50, $y = 50) {

        if (strpos($imageUrlOrPath, Filesystem::getBasePath()) === 0) {
            $imageUrl = Url::pathToUri($imageUrlOrPath);
        } else {
            $imageUrl = ResourceTranslator::toUrl(ResourceTranslator::pathToResource($imageUrlOrPath));
        }

        if (!extension_loaded('gd') || $targetWidth <= 0 || $targetHeight <= 0) {
            return $imageUrl;
        }

        $quality          = max(0, min(100, $quality));
        $originalImageUrl = $imageUrl;

        if (substr($imageUrl, 0, 2) == '//') {
            $imageUrl = parse_url(Url::getFullUri(), PHP_URL_SCHEME) . ':' . $imageUrl;
        }

        $imageUrl  = Url::relativetoabsolute($imageUrl);
        $imagePath = Filesystem::absoluteURLToPath($imageUrl);

        $cache = new CacheImage($group);
        if ($lazy) {
            $cache->setLazy(true);
        }

        if ($imagePath == $imageUrl) {
            // The image is not local
            if (!$resizeRemote) {
                return $originalImageUrl;
            }

            $pathInfo  = pathinfo(parse_url($imageUrl, PHP_URL_PATH));
            $extension = false;
            if (isset($pathInfo['extension'])) {
                $extension = self::validateGDExtension($pathInfo['extension']);
            }
            if (!$extension) {
                return $originalImageUrl;
            }


            if (strtolower($extension) === 'webp' && !function_exists('imagecreatefromwebp')) {
                return $originalImageUrl;
            }

            $resizedPath = $cache->makeCache($extension, array(
                self::class,
                '_resizeRemoteImage'
            ), array(
                $extension,
                $imageUrl,
                $targetWidth,
                $targetHeight,
                $mode,
                $backgroundColor,
                $quality,
                $optimize,
                $x,
                $y
            ));

            if (substr($resizedPath, 0, 5) == 'http:' || substr($resizedPath, 0, 6) == 'https:') {
                return $resizedPath;
            }

            if ($resizedPath === $originalImageUrl) {
                return $originalImageUrl;
            }

            return Filesystem::pathToAbsoluteURL($resizedPath);

        } else {
            $extension = false;
            $imageType = @self::exif_imagetype($imagePath);
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    $extension = 'jpg';
                    break;
                case IMAGETYPE_PNG:
                    if (self::isPNG8($imagePath)) {
                        // GD cannot resize palette PNG so we return the original image
                        return $originalImageUrl;
                    }
                    $extension = 'png';
                    break;
                case IMAGETYPE_WEBP:
                    if (!function_exists('imagecreatefromwebp')) {
                        return $originalImageUrl;
                    }
                    $extension = 'webp';
                    break;
            }
            if (!$extension) {
                return $originalImageUrl;
            }

            return Filesystem::pathToAbsoluteURL($cache->makeCache($extension, array(
                self::class,
                '_resizeImage'
            ), array(
                $extension,
                $imagePath,
                $targetWidth,
                $targetHeight,
                $mode,
                $backgroundColor,
                $quality,
                $optimize,
                $x,
                $y
            )));
        }
    }

    public static function _resizeRemoteImage($targetFile, $extension, $imageUrl, $targetWidth, $targetHeight, $mode, $backgroundColor, $quality, $optimize, $x, $y) {
        return self::_resizeImage($targetFile, $extension, $imageUrl, $targetWidth, $targetHeight, $mode, $backgroundColor, $quality, $optimize, $x, $y);
    }

    public static function _resizeImage($targetFile, $extension, $imagePath, $targetWidth, $targetHeight, $mode, $backgroundColor, $quality = 100, $optimize = false, $x = 50, $y = 50) {
        $targetDir = dirname($targetFile);

        $rotated = false;

        if ($extension == 'png') {
            $image = @imagecreatefrompng($imagePath);
        } else if ($extension == 'jpg') {
            $image = @imagecreatefromjpeg($imagePath);
            if (function_exists("exif_read_data")) {
                $exif = @exif_read_data($imagePath);

                $rotated = self::getOrientation($exif, $image);
                if ($rotated) {
                    imagedestroy($image);
                    $image = $rotated;
                }
            }
        } else if ($extension == 'webp') {
            //@TODO: should we need to care about rotation?
            $image = @imagecreatefromwebp($imagePath);
        }

        if (isset($image) && $image) {
            $originalWidth  = imagesx($image);
            $originalHeight = imagesy($image);

            if ($optimize) {
                if ($originalWidth <= $targetWidth || $originalHeight <= $targetHeight) {
                    if (!Filesystem::existsFolder($targetDir)) {
                        Filesystem::createFolder($targetDir);
                    }
                    if ($extension == 'png') {
                        imagesavealpha($image, true);
                        imagealphablending($image, false);
                        imagepng($image, $targetFile);
                    } else if ($extension == 'jpg') {
                        imagejpeg($image, $targetFile, $quality);
                    } else if ($extension == 'webp') {
                        imagesavealpha($image, true);
                        imagealphablending($image, false);
                        imagewebp($image, $targetFile, $quality);
                    }
                    imagedestroy($image);

                    return true;
                }

                if ($originalWidth / $targetWidth > $originalHeight / $targetHeight) {
                    $targetWidth = round($originalWidth / ($originalHeight / $targetHeight));
                } else {
                    $targetHeight = round($originalHeight / ($originalWidth / $targetWidth));
                }
            }
            if ($rotated || $originalWidth != $targetWidth || $originalHeight != $targetHeight) {
                $newImage = imagecreatetruecolor($targetWidth, $targetHeight);
                if ($extension == 'png' || $extension == 'webp') {
                    imagesavealpha($newImage, true);
                    imagealphablending($newImage, false);
                    $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                    imagefilledrectangle($image, 0, 0, $targetWidth, $targetHeight, $transparent);
                } else if ($extension == 'jpg' && $backgroundColor) {
                    $rgb        = Color::hex2rgb($backgroundColor);
                    $background = imagecolorallocate($newImage, $rgb[0], $rgb[1], $rgb[2]);
                    imagefilledrectangle($newImage, 0, 0, $targetWidth, $targetHeight, $background);
                }

                list($dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h) = self::imageMode($targetWidth, $targetHeight, $originalWidth, $originalHeight, $mode, $x, $y);
                imagecopyresampled($newImage, $image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
                imagedestroy($image);

            } else {
                $newImage = $image;
            }

            if (!Filesystem::existsFolder($targetDir)) {
                Filesystem::createFolder($targetDir);
            }
            if ($extension == 'png') {
                imagepng($newImage, $targetFile);
            } else if ($extension == 'jpg') {
                imagejpeg($newImage, $targetFile, $quality);
            } else if ($extension == 'webp') {
                imagewebp($newImage, $targetFile, $quality);
            }
            imagedestroy($newImage);

            return true;
        }

        throw new Exception('Unable to resize image: ' . $imagePath);
    }

    public static function scaleImage($group, $imageUrlOrPath, $scale = 1, $resizeRemote = false, $quality = 100) {


        if (strpos($imageUrlOrPath, Filesystem::getBasePath()) === 0) {
            $imageUrl = Url::pathToUri($imageUrlOrPath);
        } else {
            $imageUrl = ResourceTranslator::toUrl($imageUrlOrPath);
        }

        if (!extension_loaded('gd') || $scale <= 0) {
            return $imageUrl;
        }

        $quality          = max(0, min(100, $quality));
        $originalImageUrl = $imageUrl;

        if (substr($imageUrl, 0, 2) == '//') {
            $imageUrl = parse_url(Url::getFullUri(), PHP_URL_SCHEME) . ':' . $imageUrl;
        }

        $imageUrl  = Url::relativetoabsolute($imageUrl);
        $imagePath = Filesystem::absoluteURLToPath($imageUrl);

        $cache = new CacheImage($group);
        if ($imagePath == $imageUrl) {
            // The image is not local
            if (!$resizeRemote) {
                return $originalImageUrl;
            }

            $pathInfo  = pathinfo(parse_url($imageUrl, PHP_URL_PATH));
            $extension = false;
            if (isset($pathInfo['extension'])) {
                $extension = self::validateGDExtension($pathInfo['extension']);
            }

            if (!$extension) {
                return $originalImageUrl;
            }

            if (strtolower($extension) === 'webp' && !function_exists('imagecreatefromwebp')) {
                return $originalImageUrl;
            }

            return ResourceTranslator::urlToResource(Filesystem::pathToAbsoluteURL($cache->makeCache($extension, array(
                self::class,
                '_scaleRemoteImage'
            ), array(
                $extension,
                $imageUrl,
                $scale,
                $quality
            ))));

        } else {
            $extension = false;
            $imageType = @self::exif_imagetype($imagePath);
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    $extension = 'jpg';
                    break;
                case IMAGETYPE_PNG:
                    if (self::isPNG8($imagePath)) {
                        // GD cannot resize palette PNG so we return the original image
                        return $originalImageUrl;
                    }
                    $extension = 'png';
                    break;
                case IMAGETYPE_WEBP:
                    if (!function_exists('imagecreatefromwebp')) {
                        return $originalImageUrl;
                    }
                    $extension = 'webp';
                    break;
            }
            if (!$extension) {
                return $originalImageUrl;
            }

            return ResourceTranslator::urlToResource(Filesystem::pathToAbsoluteURL($cache->makeCache($extension, array(
                self::class,
                '_scaleImage'
            ), array(
                $extension,
                $imagePath,
                $scale,
                $quality
            ))));
        }
    }

    public static function _scaleRemoteImage($targetFile, $extension, $imageUrl, $scale, $quality) {
        return self::_scaleImage($targetFile, $extension, $imageUrl, $scale, $quality);
    }

    public static function _scaleImage($targetFile, $extension, $imagePath, $scale, $quality = 100) {
        $targetDir = dirname($targetFile);

        $image = false;

        if ($extension == 'png') {
            $image = @imagecreatefrompng($imagePath);
        } else if ($extension == 'jpg') {
            $image = @imagecreatefromjpeg($imagePath);
            if (function_exists("exif_read_data")) {
                $exif = @exif_read_data($imagePath);

                $rotated = self::getOrientation($exif, $image);
                if ($rotated) {
                    imagedestroy($image);
                    $image = $rotated;
                }
            }
        } else if ($extension == 'webp') {
            //@TODO: should we need to care about rotation?
            $image = @imagecreatefromwebp($imagePath);
        }

        if ($image) {
            $originalWidth  = imagesx($image);
            $originalHeight = imagesy($image);
            $targetWidth    = $originalWidth * $scale;
            $targetHeight   = $originalHeight * $scale;
            if ((isset($rotated) && $rotated) || $originalWidth != $targetWidth || $originalHeight != $targetHeight) {
                $newImage = imagecreatetruecolor($targetWidth, $targetHeight);
                if ($extension == 'png' || $extension == 'webp') {
                    imagesavealpha($newImage, true);
                    imagealphablending($newImage, false);
                    $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                    imagefilledrectangle($image, 0, 0, $targetWidth, $targetHeight, $transparent);
                }

                list($dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h) = self::imageMode($targetWidth, $targetHeight, $originalWidth, $originalHeight);
                imagecopyresampled($newImage, $image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
                imagedestroy($image);

            } else {
                $newImage = $image;
            }

            if (!Filesystem::existsFolder($targetDir)) {
                Filesystem::createFolder($targetDir);
            }
            if ($extension == 'png') {
                imagepng($newImage, $targetFile);
            } else if ($extension == 'jpg') {
                imagejpeg($newImage, $targetFile, $quality);
            } else if ($extension == 'webp') {
                imagewebp($newImage, $targetFile, $quality);
            }
            imagedestroy($newImage);

            return true;
        }

        throw new Exception('Unable to scale image: ' . $imagePath);
    }

    private static function getOrientation($exif, $image) {
        if ($exif && !empty($exif['Orientation'])) {
            $rotated = false;
            switch ($exif['Orientation']) {
                case 3:
                    $rotated = imagerotate($image, 180, 0);
                    break;

                case 6:
                    $rotated = imagerotate($image, -90, 0);
                    break;

                case 8:
                    $rotated = imagerotate($image, 90, 0);
                    break;
            }

            return $rotated;
        }

        return false;
    }

    private static function imageMode($width, $height, $originalWidth, $OriginalHeight, $mode = 'cover', $x = 50, $y = 50) {
        $dst_x           = 0;
        $dst_y           = 0;
        $src_x           = 0;
        $src_y           = 0;
        $dst_w           = $width;
        $dst_h           = $height;
        $src_w           = $originalWidth;
        $src_h           = $OriginalHeight;
        $horizontalRatio = $width / $originalWidth;
        $verticalRatio   = $height / $OriginalHeight;

        if ($horizontalRatio > $verticalRatio) {
            $new_h = round($horizontalRatio * $OriginalHeight);
            $dst_y = round(($height - $new_h) / 2 * $y / 50);
            $dst_h = $new_h;
        } else {
            $new_w = round($verticalRatio * $originalWidth);
            $dst_x = round(($width - $new_w) / 2 * $x / 50);
            $dst_w = $new_w;
        }

        return array(
            $dst_x,
            $dst_y,
            $src_x,
            $src_y,
            $dst_w,
            $dst_h,
            $src_w,
            $src_h
        );
    }

    private static function validateGDExtension($extension) {
        static $validExtensions = array(
            'png'  => 'png',
            'jpg'  => 'jpg',
            'jpeg' => 'jpg',
            'gif'  => 'gif',
            'webp' => 'webp',
            'svg'  => 'svg'
        );
        $extension = strtolower($extension);
        if (isset($validExtensions[$extension])) {
            return $validExtensions[$extension];
        }

        return false;
    }

    private static function validateExtension($extension) {
        static $validExtensions = array(
            'png'  => 'png',
            'jpg'  => 'jpg',
            'jpeg' => 'jpg',
            'gif'  => 'gif',
            'webp' => 'webp',
            'svg'  => 'svg'
        );
        $extension = strtolower($extension);
        if (isset($validExtensions[$extension])) {
            return $validExtensions[$extension];
        }

        return false;
    }

    public static function base64Transparent() {
        return 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
    }

    public static function base64($imagePath, $image) {
        $pathInfo  = pathinfo(parse_url($imagePath, PHP_URL_PATH));
        $extension = self::validateExtension($pathInfo['extension']);
        if ($extension) {
            return 'data:image/' . $extension . ';base64,' . Base64::encode(Filesystem::readFile($imagePath));
        }

        return ResourceTranslator::toUrl($image);
    }

    public static function exif_imagetype($filename) {
        if (!function_exists('exif_imagetype')) {
            if ((list($width, $height, $type, $attr) = getimagesize($filename)) !== false) {
                return $type;
            }

            return false;
        }

        return exif_imagetype($filename);
    }

    public static function isPNG8($path) {
        $fp = fopen($path, 'r');
        fseek($fp, 25);
        $data = fgets($fp, 2);
        fclose($fp);
        if (ord($data) == 3) {
            return true;
        }

        return false;
    }

    public static function scaleImageWebp($group, $imageUrlOrPath, $options) {

        $options = array_merge(array(
            'mode'    => 'scale',
            'scale'   => 1,
            'quality' => 100,
            'remote'  => false
        ), $options);

        if (strpos($imageUrlOrPath, Filesystem::getBasePath()) === 0) {
            $imageUrl = Url::pathToUri($imageUrlOrPath);
        } else {
            $imageUrl = ResourceTranslator::toUrl($imageUrlOrPath);
        }

        if (!extension_loaded('gd') || $options['mode'] === 'scale' && $options['scale'] <= 0) {
            return Filesystem::pathToAbsoluteURL($imageUrl);
        }

        $options['quality'] = max(0, min(100, $options['quality']));
        $originalImageUrl   = $imageUrl;


        if (substr($imageUrl, 0, 2) == '//') {
            $imageUrl = parse_url(Url::getFullUri(), PHP_URL_SCHEME) . ':' . $imageUrl;
        }

        $imageUrl  = Url::relativetoabsolute($imageUrl);
        $imagePath = Filesystem::absoluteURLToPath($imageUrl);

        $cache = new CacheImage($group);
        if ($imagePath == $imageUrl) {
            // The image is not local
            if (!$options['remote']) {
                return $originalImageUrl;
            }

            $pathInfo = pathinfo(parse_url($imageUrl, PHP_URL_PATH));

            $extension = false;
            if (isset($pathInfo['extension'])) {
                $extension = self::validateGDExtension($pathInfo['extension']);
            }

            if (!$extension || (strtolower($extension) === 'webp' && !function_exists('imagecreatefromwebp')) || !ini_get('allow_url_fopen')) {
                return $originalImageUrl;
            }

            return ResourceTranslator::urlToResource(Filesystem::pathToAbsoluteURL($cache->makeCache('webp', array(
                self::class,
                '_scaleRemoteImageWebp'
            ), array(
                $extension,
                $imageUrl,
                $options
            ))));

        } else {
            $extension = false;
            $imageType = @self::exif_imagetype($imagePath);
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    $extension = 'jpg';
                    break;
                case IMAGETYPE_PNG:
                    $extension = 'png';
                    break;
                case IMAGETYPE_WEBP:
                    if (!function_exists('imagecreatefromwebp')) {
                        return $originalImageUrl;
                    }
                    $extension = 'webp';
                    break;
            }
            if (!$extension) {
                return $originalImageUrl;
            }

            return ResourceTranslator::urlToResource(Filesystem::pathToAbsoluteURL($cache->makeCache('webp', array(
                self::class,
                '_scaleImageWebp'
            ), array(
                $extension,
                $imagePath,
                $options
            ))));
        }
    }

    public static function _scaleRemoteImageWebp($targetFile, $extension, $imageUrl, $options) {
        return self::_scaleImageWebp($targetFile, $extension, $imageUrl, $options);
    }

    public static function _scaleImageWebp($targetFile, $extension, $imagePath, $options) {

        $options = array_merge(array(
            'focusX' => 50,
            'focusY' => 50,
        ), $options);

        $targetDir = dirname($targetFile);

        $image = false;

        if ($extension == 'png') {
            $image = @imagecreatefrompng($imagePath);
            if (!imageistruecolor($image)) {
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
            }
        } else if ($extension == 'jpg') {
            $image = @imagecreatefromjpeg($imagePath);
            if (function_exists("exif_read_data")) {
                $exif = @exif_read_data($imagePath);

                $rotated = self::getOrientation($exif, $image);
                if ($rotated) {
                    imagedestroy($image);
                    $image = $rotated;
                }
            }
        } else if ($extension == 'webp') {
            //@TODO: should we need to care about rotation?
            $image = @imagecreatefromwebp($imagePath);
            if (!imageistruecolor($image)) {
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
            }
        }

        if ($image) {
            $originalWidth  = imagesx($image);
            $originalHeight = imagesy($image);
            switch ($options['mode']) {
                case 'scale':
                    $targetWidth  = round($originalWidth * $options['scale']);
                    $targetHeight = round($originalHeight * $options['scale']);
                    break;
                case 'resize':
                    $targetWidth  = $options['width'];
                    $targetHeight = $options['height'];
                    break;
            }
            if ((isset($rotated) && $rotated) || $originalWidth != $targetWidth || $originalHeight != $targetHeight) {
                $newImage = imagecreatetruecolor($targetWidth, $targetHeight);
                if ($extension == 'png') {
                    imagesavealpha($newImage, true);
                    imagealphablending($newImage, false);
                    $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                    imagefilledrectangle($image, 0, 0, $targetWidth, $targetHeight, $transparent);
                }

                list($dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h) = self::imageMode($targetWidth, $targetHeight, $originalWidth, $originalHeight, 'cover', $options['focusX'], $options['focusY']);
                imagecopyresampled($newImage, $image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
                imagedestroy($image);

            } else {
                $newImage = $image;
            }

            if (!Filesystem::existsFolder($targetDir)) {
                Filesystem::createFolder($targetDir);
            }

            imagewebp($newImage, $targetFile, $options['quality']);
            imagedestroy($newImage);

            return true;
        }

        throw new Exception('Unable to scale image: ' . $imagePath);
    }
}