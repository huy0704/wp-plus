<?php

namespace Nextend\SmartSlider3\Generator\WordPress\Posts\Sources;

use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\MixedField\GeneratorOrder;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Select\Filter;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Textarea;
use Nextend\Framework\Parser\Common;
use Nextend\Framework\Translation\Translation;
use Nextend\SmartSlider3\Generator\AbstractGenerator;
use Nextend\SmartSlider3\Generator\WordPress\Posts\Elements\PostsCategories;
use Nextend\SmartSlider3\Generator\WordPress\Posts\Elements\PostsTags;
use Nextend\SmartSlider3\Generator\WordPress\Posts\Elements\PostsTaxonomies;
use Nextend\SmartSlider3\Generator\WordPress\Posts\GeneratorGroupPosts;

class PostsPosts extends AbstractGenerator {

    protected $layout = 'article';

    protected $postType = 'post';

    public function getDescription() {
        return n2_('Creates slides from your posts in the selected categories.');
    }

    public function renderFields($container) {
        $filterGroup = new ContainerTable($container, 'filter-group', n2_('Filter'));
        $filter      = $filterGroup->createRow('filter');

        new PostsCategories($filter, 'postscategory', n2_('Categories'), 0);
        new PostsTags($filter, 'posttags', n2_('Tags'), 0);
        new PostsTaxonomies($filter, 'postcustomtaxonomy', n2_('Taxonomies'), 0, array(
            'postType' => 'post',
            'skip'     => true
        ));

        $posts = $filterGroup->createRow('posts');
        new Filter($posts, 'poststicky', n2_('Sticky'), 0);
        new OnOff($posts, 'postshortcode', n2_('Remove shortcodes'), 1, array(
            'relatedFieldsOn' => array(
                'generatorpostshortcodevariables'
            ),
            'tipLabel'        => n2_('Remove shortcodes'),
            'tipDescription'  => n2_('You can remove shortcodes from variables to avoid 3rd party content rendering in your slider.')
        ));
        new Text($posts, 'postshortcodevariables', n2_('Remove from variables'), 'description, content, excerpt', array(
            'tipLabel'       => n2_('Remove from variables'),
            'tipDescription' => n2_('Write the name of the variables you want to remove the shortcodes from. Separate new variables with a comma and space. E.g. description, content')
        ));

        $date = $filterGroup->createRow('date');
        new Textarea($date, 'customdates', n2_('Custom date variables'), "variable||PHP date format\nmodified||Ymd\ndate||F j, Y, g:i a\nstarted||F\nended||D", array(
            'tipLabel'       => n2_('Custom date variables'),
            'tipDescription' => sprintf(n2_('You can create custom date variables from your existing date variables. Write each variable to a new line and use the following format: variable||format. %3$s The "variable" should be an existing variable. Based on this existing variable, we create a new one with the "_datetime" suffix. (E.g. date_datetime.) %3$s The "format" can be any %1$sPHP date format%2$s.'), '<a href="http://php.net/manual/en/function.date.php" target="_blank">', '</a>', '<br>'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1891-wordpress---posts-generator',
            'width'          => 300,
            'height'         => 100
        ));

        new Textarea($date, 'translatecustomdates', n2_('Translate custom dates'), "from||to\nMonday||Monday\njan||jan", array(
            'tipLabel'       => n2_('Translate custom dates'),
            'tipDescription' => sprintf(n2_('You can translate the content of the newly created variables. %1$s Use the following format: from||to. Eg.: Monday||Montag'), '<br>'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1891-wordpress---posts-generator',
            'width'          => 300,
            'height'         => 100
        ));

        new Select($date, 'datefunction', n2_('Date function'), 'date_i18n', array(
            'tipLabel'       => n2_('Date function'),
            'tipDescription' => n2_('This function will be used to format these custom date variables. Usually the date_i18n works, but if your date will be off a little bit, then try out the other one.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1891-wordpress---posts-generator',
            'options'        => array(
                'date_i18n' => 'date_i18n',
                'date'      => 'date'
            )
        ));

        $orderGroup = new ContainerTable($container, 'order-group', n2_('Order'));
        $order      = $orderGroup->createRow('order');
        new GeneratorOrder($order, 'postscategoryorder', 'post_date|*|desc', array(
            'options' => array(
                'none'          => n2_('None'),
                'post_date'     => n2_('Post date'),
                'ID'            => 'ID',
                'title'         => n2_('Title'),
                'post_modified' => n2_('Modification date'),
                'rand'          => n2_('Random'),
                'comment_count' => n2_('Comment count'),
                'menu_order'    => n2_('Menu order')
            )
        ));
    }

    private function translate($from, $translate) {
        if (!empty($translate) && !empty($from)) {
            foreach ($translate as $key => $value) {
                $from = str_replace($key, $value, $from);
            }
        }

        return $from;
    }

    private function linesToArray($lines) {
        $value = preg_split('/$\R?^/m', $lines);
        $data  = array();
        if (!empty($value)) {
            foreach ($value as $v) {
                $array = explode('||', $v);
                if (!empty($array) && count($array) == 2) {
                    $data[$array[0]] = trim($array[1]);
                }
            }
        }

        return $data;
    }

    private function isTimeStamp($timestamp) {
        return ((string)(int)$timestamp === $timestamp) && ($timestamp <= PHP_INT_MAX) && ($timestamp >= ~PHP_INT_MAX);
    }

    public function getPostType() {
        return $this->postType;
    }

    public function filterName($name) {
        return $name . Translation::getCurrentLocale();
    }

    function get_string_between($str, $startDelimiter, $endDelimiter) {
        $contents             = array();
        $startDelimiterLength = strlen($startDelimiter);
        $endDelimiterLength   = strlen($endDelimiter);
        $startFrom            = $contentStart = $contentEnd = 0;
        while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
            $contentStart += $startDelimiterLength;
            $contentEnd   = strpos($str, $endDelimiter, $contentStart);
            if (false === $contentEnd) {
                break;
            }
            $contents[] = substr($str, $contentStart, $contentEnd - $contentStart);
            $startFrom  = $contentEnd + $endDelimiterLength;
        }

        return $contents;
    }

    protected function _getData($count, $startIndex) {
        global $post, $wp_query;
        $tmpPost = $post;

        list($orderBy, $order) = Common::parse($this->data->get('postscategoryorder', 'post_date|*|desc'));

        $allTags   = $this->data->get('posttags', '');
        $tax_query = '';
        if (!empty($allTags)) {
            $tags = explode('||', $allTags);
            if (!in_array('0', $tags)) {
                $tax_query = array(
                    array(
                        'taxonomy' => 'post_tag',
                        'terms'    => $tags,
                        'field'    => 'id'
                    )
                );
            }
        }

        $allTerms = $this->data->get('postcustomtaxonomy', '');
        if (!empty($allTerms)) {
            $terms = explode('||', $allTerms);
            if (!in_array('0', $terms)) {
                $termarray = array();
                foreach ($terms as $key => $value) {
                    $term = explode("_x_", $value);
                    if (array_key_exists($term[0], $termarray)) {
                        $termarray[$term[0]][] = $term[1];
                    } else {
                        $termarray[$term[0]]   = array();
                        $termarray[$term[0]][] = $term[1];
                    }
                }

                $term_helper = array();
                foreach ($termarray as $taxonomy => $termids) {
                    $term_helper[] = array(
                        'taxonomy' => $taxonomy,
                        'terms'    => $termids,
                        'field'    => 'id'
                    );
                }
                if (!empty($tax_query)) {
                    array_unshift($tax_query, array('relation' => 'AND'));
                } else {
                    $tax_query = array('relation' => 'AND');
                }
                $tax_query = array_merge($tax_query, $term_helper);
            }
        }

        $postsFilter = array(
            'include'          => '',
            'exclude'          => '',
            'meta_key'         => '',
            'meta_value'       => '',
            'post_type'        => 'post',
            'post_mime_type'   => '',
            'post_parent'      => '',
            'post_status'      => 'publish',
            'suppress_filters' => false,
            'offset'           => $startIndex,
            'posts_per_page'   => $count,
            'tax_query'        => $tax_query
        );

        if ($orderBy != 'none') {
            $postsFilter += array(
                'orderby'            => $orderBy,
                'order'              => $order,
                'ignore_custom_sort' => true
            );
        }

        $categories = (array)Common::parse($this->data->get('postscategory'));
        if (!in_array(0, $categories)) {
            $postsFilter['category'] = implode(',', $categories);
        }

        $poststicky = $this->data->get('poststicky');
        switch ($poststicky) {
            case 1:
                $postsFilter += array(
                    'post__in' => get_option('sticky_posts')
                );
                break;
            case -1:
                $postsFilter += array(
                    'post__not_in' => get_option('sticky_posts')
                );
                break;
        }

        if (has_filter('the_content', 'siteorigin_panels_filter_content')) {
            $siteorigin_panels_filter_content = true;
            remove_filter('the_content', 'siteorigin_panels_filter_content');
        } else {
            $siteorigin_panels_filter_content = false;
        }

        $posts = get_posts($postsFilter);

        $custom_dates  = $this->linesToArray($this->data->get('customdates', ''));
        $translate     = $this->linesToArray($this->data->get('translatecustomdates', ''));
        $date_function = $this->data->get('datefunction', 'date_i18n');

        if ($this->data->get('postshortcode', 1)) {
            $remove_shortcode = array_map('trim', explode(',', $this->data->get('postshortcodevariables', 'description, content, excerpt')));
        } else {
            $remove_shortcode = null;
        }

        $data = array();
        for ($i = 0; $i < count($posts); $i++) {
            $record = array();

            $post = $posts[$i];
            setup_postdata($post);
            $wp_query->post = $post;

            $record['id']          = $post->ID;
            $record['url']         = get_permalink();
            $record['title']       = apply_filters('the_title', get_the_title(), $post->ID);
            $record['content']     = get_the_content();
            $record['description'] = $record['content'];
            if (class_exists('ET_Builder_Plugin')) {
                if (strpos($record['description'], 'et_pb_slide background_image') !== false) {
                    $et_slides = $this->get_string_between($record['description'], 'et_pb_slide background_image="', '"');
                    for ($j = 0; $j < count($et_slides); $j++) {
                        $record['et_slide' . $j] = $et_slides[$j];
                    }
                }
                if (strpos($record['description'], 'background_url') !== false) {
                    $et_backgrounds = $this->get_string_between($record['description'], 'background_url="', '"');
                    for ($j = 0; $j < count($et_backgrounds); $j++) {
                        $record['et_background' . $j] = $et_backgrounds[$j];
                    }
                }
                if (strpos($record['description'], 'logo_image_url') !== false) {
                    $et_logoImages = $this->get_string_between($record['description'], 'logo_image_url="', '"');
                    for ($j = 0; $j < count($et_logoImages); $j++) {
                        $record['et_logoImage' . $j] = $et_logoImages[$j];
                    }
                }
                if (strpos($record['description'], 'slider-content') !== false) {
                    $et_contents = $this->get_string_between($record['description'], 'slider-content">', '</p>');
                    for ($j = 0; $j < count($et_contents); $j++) {
                        $record['et_content' . $j] = $et_contents[$j];
                    }
                }
            }
            $record['slug']          = $post->post_name;
            $record['author_name']   = $record['author'] = get_the_author();
            $userID                  = get_the_author_meta('ID');
            $record['author_url']    = get_author_posts_url($userID);
            $record['author_avatar'] = get_avatar_url($userID);
            $record['date']          = get_the_date('Y-m-d H:i:s');
            $record['modified']      = get_the_modified_date('Y-m-d H:i:s');

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

            $tags = wp_get_post_tags($post->ID);
            for ($j = 0; $j < count($tags); $j++) {
                $record['tag_' . ($j + 1)] = $tags[$j]->name;
            }

            $record = array_merge($record, GeneratorGroupPosts::getACFData($post->ID));

            $record = array_merge($record, GeneratorGroupPosts::extractPostMeta(get_post_meta($post->ID)));

            if (isset($record['primarytermcategory'])) {
                $primary                         = get_category($record['primarytermcategory']);
                $record['primary_category_name'] = $primary->name;
                $record['primary_category_link'] = get_category_link($primary->cat_ID);
            }
            $record['excerpt']       = get_the_excerpt();
            $record['comment_count'] = $post->comment_count;
            $record['guid']          = $post->guid;

            if (!empty($custom_dates)) {
                foreach ($custom_dates as $custom_date_key => $custom_date_format) {
                    if (array_key_exists($custom_date_key, $record)) {
                        if ($this->isTimeStamp($record[$custom_date_key])) {
                            $date = $record[$custom_date_key];
                        } else {
                            $date = strtotime($record[$custom_date_key]);
                        }

                        if ($date_function == 'date_i18n') {
                            $record[$custom_date_key . '_datetime'] = $this->translate(date_i18n($custom_date_format, $date), $translate);
                        } else {
                            $record[$custom_date_key . '_datetime'] = $this->translate(date($custom_date_format, $date), $translate);
                        }
                    }
                }
            }

            /**
             * We used 'Y-m-d H:i:s' date format, so we can get the hour, minute and second for custom date variables.
             * but we need to set the date and modified variables back to the WordPress default date_format.
             */
            $record['date']     = get_the_date();
            $record['modified'] = get_the_modified_date();

            if (!empty($remove_shortcode)) {
                foreach ($remove_shortcode as $variable) {
                    if (isset($record[$variable])) {
                        $record[$variable] = GeneratorGroupPosts::removeShortcodes($record[$variable]);
                    }
                }
            }

            $record = apply_filters('smartslider3_posts_posts_data', $record);

            $data[$i] = &$record;
            unset($record);
        }

        if ($siteorigin_panels_filter_content) {
            add_filter('the_content', 'siteorigin_panels_filter_content');
        }

        $wp_query->post = $tmpPost;
        wp_reset_postdata();

        return $data;
    }
}