<?php

namespace Nextend\SmartSlider3\Generator\WordPress\Posts\Sources;

use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Textarea;
use Nextend\SmartSlider3\Generator\AbstractGenerator;
use Nextend\SmartSlider3\Generator\WordPress\Posts\GeneratorGroupPosts;

class PostsPostsByIDs extends AbstractGenerator {

    protected $layout = 'article';

    public function getDescription() {
        return n2_('Creates slides from the posts with the set IDs.');
    }

    public function renderFields($container) {
        $filterGroup = new ContainerTable($container, 'filter-group', n2_('Filter'));
        $filter      = $filterGroup->createRow('filter');
        new Textarea($filter, 'ids', n2_('Post or Page IDs'), '', array(
            'width'          => 280,
            'height'         => 160,
            'tipLabel'       => n2_('Post or Page IDs'),
            'tipDescription' => sprintf(n2_('You can write the ID of the page you want to show in your generator. %1$s Write one ID per line.'), '<br>')
        ));
    }

    protected function _getData($count, $startIndex) {
        global $post, $wp_query;
        $tmpPost = $post;

        if (has_filter('the_content', 'siteorigin_panels_filter_content')) {
            $siteorigin_panels_filter_content = true;
            remove_filter('the_content', 'siteorigin_panels_filter_content');
        } else {
            $siteorigin_panels_filter_content = false;
        }

        $i    = 0;
        $data = array();

        foreach ($this->getIDs() as $id) {
            $record = array();
            $post   = get_post($id);
            if (!$post) continue;
            setup_postdata($post);
            $wp_query->post = $post;

            $record['id']            = $post->ID;
            $record['url']           = get_permalink();
            $record['title']         = apply_filters('the_title', get_the_title(), $post->ID);
            $record['description']   = $record['content'] = GeneratorGroupPosts::removeShortcodes(get_the_content());
            $record['author_name']   = $record['author'] = get_the_author();
            $userID                  = get_the_author_meta('ID');
            $record['author_url']    = get_author_posts_url($userID);
            $record['author_avatar'] = get_avatar_url($userID);
            $record['date']          = get_the_date();
            $record['modified']      = get_the_modified_date();

            $record = array_merge($record, GeneratorGroupPosts::getCategoryData($post->ID));

            $thumbnail_id             = get_post_thumbnail_id($post->ID);
            $record['featured_image'] = wp_get_attachment_image_url($thumbnail_id, 'full');
            if (!$record['featured_image']) {
                $record['featured_image'] = '';
            } else {
                $thumbnail_meta = get_post_meta($thumbnail_id, '_wp_attachment_metadata', true);
                if (isset($thumbnail_meta['sizes'])) {
                    $sizes  = GeneratorGroupPosts::getImageSizes($thumbnail_id, $thumbnail_meta['sizes']);
                    $record = array_merge($record, $sizes);
                }
                $record['alt'] = '';
                $alt           = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
                if (isset($alt)) {
                    $record['alt'] = $alt;
                }
            }

            $record['thumbnail'] = $record['image'] = $record['featured_image'];
            $record['url_label'] = 'View post';

            $record = array_merge($record, GeneratorGroupPosts::getACFData($post->ID));

            $record = array_merge($record, GeneratorGroupPosts::extractPostMeta(get_post_meta($post->ID)));

            if (isset($record['primarytermcategory'])) {
                $primary                         = get_category($record['primarytermcategory']);
                $record['primary_category_name'] = $primary->name;
                $record['primary_category_link'] = get_category_link($primary->cat_ID);
            }
            $record['excerpt'] = get_the_excerpt();

            $record = apply_filters('smartslider3_posts_postsbyids_data', $record);

            $data[$i] = &$record;
            unset($record);
            $i++;
        }
        if ($siteorigin_panels_filter_content) {
            add_filter('the_content', 'siteorigin_panels_filter_content');
        }

        $wp_query->post = $tmpPost;
        wp_reset_postdata();

        return $data;
    }
}