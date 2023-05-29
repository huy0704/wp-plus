<?php

namespace Nextend\Framework\Content\WordPress;

use Nextend\Framework\Content\AbstractPlatformContent;
use WP_Query;
use function get_post_thumbnail_id;
use function get_post_type;
use function get_post_type_object;
use function get_the_excerpt;
use function get_the_ID;
use function get_the_permalink;
use function get_the_title;
use function wp_get_attachment_url;

class WordPressContent extends AbstractPlatformContent {

    public function searchLink($keyword) {

        $the_query = new WP_Query('post_type=any&posts_per_page=20&post_status=publish&s=' . $keyword);

        $links = array();
        if ($the_query->have_posts()) {
            while ($the_query->have_posts()) {
                $the_query->the_post();

                $link = array(
                    'title' => get_the_title(),
                    'link'  => get_the_permalink(),
                    'info'  => get_post_type_object(get_post_type())->labels->singular_name
                );

                $links[] = $link;

            }
        }
        /* Restore original Post Data */
        wp_reset_postdata();

        return $links;
    }

    public function searchContent($keyword) {

        $the_query = new WP_Query('post_type=any&posts_per_page=20&post_status=publish&s=' . $keyword);

        $links = array();
        if ($the_query->have_posts()) {
            while ($the_query->have_posts()) {
                $the_query->the_post();

                $link = array(
                    'title'       => get_the_title(),
                    'description' => get_the_excerpt(),
                    'image'       => wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())),
                    'link'        => get_the_permalink(),
                    'info'        => get_post_type_object(get_post_type())->labels->singular_name
                );

                $links[] = $link;

            }
        }
        /* Restore original Post Data */
        wp_reset_postdata();

        return $links;
    }
}