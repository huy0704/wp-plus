<?php

namespace Nextend\Framework\Browse\BulletProof;

use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Image\ImageEdit;
use Nextend\Framework\Request\Request;

/**
 * BULLETPROOF,
 *
 * This is a one-file solution for a quick and safe way of
 * uploading, watermarking, cropping and resizing images
 * during and after uploads with PHP with best security.
 *
 * This class is heavily commented, to be as much friendly as possible.
 * Please help out by posting out some bugs/flaws if you encounter any. Thanks!
 *
 * @category    Image uploader
 * @package     BulletProof
 * @version     1.4.0
 * @author      samayo
 * @link        https://github.com/samayo/BulletProof
 * @license     Luke 3:11 ( Free )
 */
class BulletProof {

    /*
    |--------------------------------------------------------------------------
    | Image Upload Properties
    \--------------------------------------------------------------------------*/

    /**
     * Set a group of default image types to upload.
     *
     * @var array
     */
    protected $imageType = array(
        "jpg",
        "jpeg",
        "png",
        "gif",
        "webp",
        "svg"
    );

    /**
     * Set a default file size to upload. Values are in bytes. Remember: 1kb ~ 1000 bytes.
     *
     * @var array
     */
    protected $imageSize = array(
        "min" => 1,
        "max" => 20000000
    );

    /**
     * Set a default min & maximum height & width for image to upload.
     *
     * @var array
     */
    protected $imageDimension = array(
        "height" => 10000,
        "width"  => 10000
    );

    /**
     * Set a default folder to upload images, if it does not exist, it will be created.
     *
     * @var string
     */
    protected $uploadDir = "uploads";

    /**
     * To get the real image/mime type. i.e gif, jpeg, png, ....
     *
     * @var string
     */
    protected $getMimeType;

    /*
    |--------------------------------------------------------------------------
    | Image Upload Methods
    \--------------------------------------------------------------------------*/

    /**
     * Stores image types to upload
     *
     * @param array $fileTypes -  ex: ['jpg', 'doc', 'txt'].
     *
     * @return $this
     */
    public function fileTypes(array $fileTypes) {
        $this->imageType = $fileTypes;

        return $this;
    }

    /**
     * Minimum and Maximum allowed image size for upload (in bytes),
     *
     * @param array $fileSize - ex: ['min'=>500, 'max'=>1000]
     *
     * @return $this
     */
    public function limitSize(array $fileSize) {
        $this->imageSize = $fileSize;

        return $this;
    }

    /**
     * Default & maximum allowed height and width image to download.
     *
     * @param array $dimensions
     *
     * @return $this
     */
    public function limitDimension(array $dimensions) {
        $this->imageDimension = $dimensions;

        return $this;
    }

    /**
     * Get the real image's Extension/mime type
     *
     * @param $imageName
     *
     * @return mixed
     * @throws Exception
     */
    protected function getMimeType($imageName) {
        if (!file_exists($imageName)) {
            throw new Exception("Image " . $imageName . " does not exist");
        }

        $listOfMimeTypes = array(
            1 => "gif",
            "jpeg",
            "png",
            "swf",
            "psd",
            "bmp",
            "tiff",
            "tiff",
            "jpc",
            "jp2",
            "jpx",
            "jb2",
            "swc",
            "iff",
            "wbmp",
            "xmb",
            "ico",
            "webp",
            "svg"
        );

        $imageType = ImageEdit::exif_imagetype($imageName);
        if (isset($listOfMimeTypes[$imageType])) {
            return $listOfMimeTypes[$imageType];
        }

        return false;
    }

    /**
     * Handy method for getting image dimensions (W & H) in pixels.
     *
     * @param $getImage - The image name
     *
     * @return array
     */
    protected function getPixels($getImage) {
        list($width, $height) = getImageSize($getImage);

        return array(
            "width"  => $width,
            "height" => $height
        );
    }

    /**
     * Rename file either from method or by generating a random one.
     *
     * @param $isNameProvided - A new name for the file.
     *
     * @return string
     */
    protected function imageRename($isNameProvided) {
        if ($isNameProvided) {
            return $isNameProvided . "." . $this->getMimeType;
        }

        return uniqid(true) . "_" . str_shuffle(implode(range("E", "Q"))) . "." . $this->getMimeType;
    }

    /**
     * Get the specified upload dir, if it does not exist, create a new one.
     *
     * @param $directoryName - directory name where you want your files to be uploaded
     *
     * @return $this
     * @throws Exception
     */
    public function uploadDir($directoryName) {
        if (!file_exists($directoryName) && !is_dir($directoryName)) {

            $createFolder = Filesystem::createFolder("" . $directoryName);
            if (!$createFolder) {
                throw new Exception("Folder " . $directoryName . " could not be created");
            }
        }
        $this->uploadDir = $directoryName;

        return $this;
    }

    /**
     * For getting common error messages from FILES[] array during upload.
     *
     * @return array
     */
    protected function commonUploadErrors($key) {
        $uploadErrors = array(
            UPLOAD_ERR_OK         => "...",
            UPLOAD_ERR_INI_SIZE   => "File is larger than the specified amount set by the server",
            UPLOAD_ERR_FORM_SIZE  => "File is larger than the specified amount specified by browser",
            UPLOAD_ERR_PARTIAL    => "File could not be fully uploaded. Please try again later",
            UPLOAD_ERR_NO_FILE    => "File is not found",
            UPLOAD_ERR_NO_TMP_DIR => "Can't write to disk, due to server configuration ( No tmp dir found )",
            UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk. Please check you file permissions",
            UPLOAD_ERR_EXTENSION  => "A PHP extension has halted this file upload process"
        );

        return $uploadErrors[$key];
    }

    /**
     * Simple file check and delete wrapper.
     *
     * @param $fileToDelete
     *
     * @return bool
     * @throws Exception
     */
    public function deleteFile($fileToDelete) {
        if (file_exists($fileToDelete) && !unlink($fileToDelete)) {
            throw new Exception("File may have been deleted or does not exist");
        }

        return true;
    }

    /**
     * Final image uploader method, to check for errors and upload
     *
     * @param      $fileToUpload
     * @param null $isNameProvided
     *
     * @return string
     * @throws Exception
     */
    public function upload($fileToUpload, $isNameProvided = null) {

        $isMedia = false;
        // Check if any errors are thrown by the FILES[] array
        if ($fileToUpload["error"]) {
            throw new Exception($this->commonUploadErrors($fileToUpload["error"]));
        }

        if (function_exists("mime_content_type")) {
            $rawMime = mime_content_type($fileToUpload["tmp_name"]);
        } else {
            if (!empty($fileToUpload['name'])) {
                $path_parts = pathinfo($fileToUpload['name']);
                switch ($path_parts['extension']) {
                    case 'mp4':
                        $rawMime = 'video/mp4';
                        break;
                    case 'mp3':
                        $rawMime = 'audio/mpeg';
                        break;
                    default:
                        $rawMime = '';
                        break;
                }
            }
        }

        switch ($rawMime) {
            case 'video/mp4':
                $this->getMimeType = 'mp4';
                $isMedia           = true;
                break;
            case 'audio/mpeg':
                $this->getMimeType = 'mp3';
                $isMedia           = true;
                break;
        }

        if (!$isMedia) {
            // First get the real file extension
            $this->getMimeType = $this->getMimeType($fileToUpload["tmp_name"]);

            $specialImage = false;
            if ($this->getMimeType === false) {
                if (isset($fileToUpload["type"]) && strpos($fileToUpload["type"], 'image/') !== false) {
                    $this->getMimeType = str_replace(array(
                        'image/',
                        'svg+xml'
                    ), array(
                        '',
                        'svg'
                    ), $fileToUpload["type"]);
                    $specialImage      = true;
                }
            }

            // Check if this file type is allowed for upload
            if (!in_array($this->getMimeType, $this->imageType)) {
                throw new Exception(" This is not allowed file type!
             Please only upload ( " . implode(", ", $this->imageType) . " ) file types");
            }

            //Check if size (in bytes) of the image are above or below of defined in 'limitSize()'
            if ($fileToUpload["size"] < $this->imageSize["min"] || $fileToUpload["size"] > $this->imageSize["max"]) {
                throw new Exception("File sizes must be between " . implode(" to ", $this->imageSize) . " bytes");
            }

            // check if image is valid pixel-wise.
            if (!$specialImage) {
                $pixel = $this->getPixels($fileToUpload["tmp_name"]);

                if ($pixel["width"] < 4 || $pixel["height"] < 4) {
                    throw new Exception("This file is either too small or corrupted to be an image");
                }

                if ($pixel["height"] > $this->imageDimension["height"] || $pixel["width"] > $this->imageDimension["width"]) {
                    throw new Exception("Image pixels/size must be below " . implode(", ", $this->imageDimension) . " pixels");
                }
            }
        }

        // create upload directory if it does not exist
        $this->uploadDir($this->uploadDir);

        $i           = '';
        $newFileName = $this->imageRename($isNameProvided);

        while (file_exists($this->uploadDir . "/" . $newFileName)) {
            // The file already uploaded, nothing to do here
            if (self::isFilesIdentical($this->uploadDir . "/" . $newFileName, $fileToUpload["tmp_name"])) {
                return $this->uploadDir . "/" . $newFileName;
            }
            $i++;
            $newFileName = $this->imageRename($isNameProvided . $i);
        }

        // Upload the file
        $moveUploadedFile = $this->moveUploadedFile($fileToUpload["tmp_name"], $this->uploadDir . "/" . $newFileName);

        if ($moveUploadedFile) {
            return $this->uploadDir . "/" . $newFileName;
        } else {
            throw new Exception(" File could not be uploaded. Unknown error occurred. ");
        }
    }

    public function moveUploadedFile($uploaded_file, $new_file) {
        if (!is_uploaded_file($uploaded_file)) {
            return copy($uploaded_file, $new_file);
        }

        return move_uploaded_file($uploaded_file, $new_file);
    }

    private static function isFilesIdentical($fn1, $fn2) {
        if (filetype($fn1) !== filetype($fn2)) return FALSE;

        if (filesize($fn1) !== filesize($fn2)) return FALSE;

        if (sha1_file($fn1) != sha1_file($fn2)) return false;

        return true;
    }
}