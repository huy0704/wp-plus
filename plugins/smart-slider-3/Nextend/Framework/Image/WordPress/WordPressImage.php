<?php

namespace Nextend\Framework\Image\WordPress;

use Nextend\Framework\Image\AbstractPlatformImage;
use Nextend\Framework\Platform\Platform;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use function wp_check_filetype;
use function wp_upload_dir;

class WordPressImage extends AbstractPlatformImage {

    public function __construct() {
        $wp_upload_dir = wp_upload_dir();

        ResourceTranslator::createResource('$upload$', rtrim(Platform::getPublicDirectory(), "/\\"), rtrim($wp_upload_dir['baseurl'], "/\\"));
    }

    public function initLightbox() {

        wp_enqueue_media();
    }

    public function onImageUploaded($filename) {
        $filename = str_replace(DIRECTORY_SEPARATOR, '/', $filename); // fix for Windows servers

        // Check the type of file. We'll use this as the 'post_mime_type'.
        $filetype = wp_check_filetype(basename($filename), null);

        // Get the path to the upload directory.
        $wp_upload_dir = wp_upload_dir();

        // Prepare an array of post data for the attachment.
        $attachment = array(
            'guid'           => $wp_upload_dir['url'] . '/' . basename($filename),
            'post_mime_type' => $filetype['type'],
            'post_title'     => preg_replace('/\.[^.]+$/', '', basename($filename)),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        // Insert the attachment.
        $attach_id = wp_insert_attachment($attachment, $filename);

        // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        // Generate the metadata for the attachment, and update the database record.
        $attach_data = wp_generate_attachment_metadata($attach_id, $filename);
        wp_update_attachment_metadata($attach_id, $attach_data);
    }
}