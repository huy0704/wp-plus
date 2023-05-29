<?php

namespace Nextend\SmartSlider3\Generator\WordPress\Posts\Sources;

use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\MixedField\GeneratorOrder;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Radio;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Textarea;
use Nextend\Framework\Parser\Common;
use Nextend\SmartSlider3\Generator\AbstractGenerator;
use Nextend\SmartSlider3\Generator\WordPress\Posts\Elements\PostsCustomFields;
use Nextend\SmartSlider3\Generator\WordPress\Posts\Elements\PostsOptions;
use Nextend\SmartSlider3\Generator\WordPress\Posts\Elements\PostsTaxonomies;
use Nextend\SmartSlider3\Generator\WordPress\Posts\GeneratorGroupPosts;

class PostsCustomPosts extends AbstractGenerator {

    protected $layout = 'article';

    protected $postType;

    public function __construct($group, $name, $post_type, $label) {
        $this->postType = $post_type;
        parent::__construct($group, $name, $label);
    }

    public function getPostType() {
        return $this->postType;
    }

    public function getDescription() {
        return sprintf(n2_('Creates slides from the following post type: %1$s.'), $this->postType);
    }

    private function checkKeywords($variable) {
        switch ($variable) {
            case 'current_date':
                $variable = current_time('mysql');
                break;
            case 'current_date_timestamp':
                $variable = current_time('timestamp');
                break;
            default:
                break;
        }

        return $variable;
    }


    public function renderFields($container) {
        $filterGroup = new ContainerTable($container, 'filter-group', n2_('Filter'));
        $filter      = $filterGroup->createRow('filter');
        new PostsTaxonomies($filter, 'taxonomies', n2_('Taxonomies'), 0, array(
            'postType'      => $this->postType,
            'postSeparator' => '|*|'
        ));

        new Select($filter, 'taxonomies_relation', n2_('Relation'), 'OR', array(
            'options' => array(
                'OR'  => 'OR',
                'AND' => 'AND'
            )
        ));

        $ids = $filterGroup->createRow('ids');
        new Textarea($ids, 'ids', n2_('Post IDs to display'), '', array(
            'width'          => 150,
            'height'         => 150,
            'tipLabel'       => n2_('Post IDs to display'),
            'tipDescription' => sprintf(n2_('You can make your generator display only the posts with the set ID. No other post will be fetched, even if they match the set filters. %1$s Write one ID per line.'), '<br>')
        ));

        new Textarea($ids, 'exclude_ids', n2_('Exclude posts'), '', array(
            'width'          => 150,
            'height'         => 150,
            'tipLabel'       => n2_('Exclude posts'),
            'tipDescription' => sprintf(n2_('The selected post IDs won\'t appear in the generator, even if they they match the set filters. %1$s Write one ID per line.'), '<br>')
        ));

        $status   = $filterGroup->createRow('status');
        $statuses = get_post_stati();
        $statuses += array(
            'any'   => 'any',
            'unset' => 'unset',
        );
        new Select($status, 'poststatus', n2_('Post status'), 'publish', array(
            'options' => $statuses
        ));

        $postMetaGroup = $filterGroup->createRowGroup('postmetaGroup', n2_('Post meta comparison'));
        $postMeta      = $postMetaGroup->createRow('postmeta');
        new PostsCustomFields($postMeta, 'postmetakey', n2_('Field name'), 0, array(
            'postType'       => $this->postType,
            'tipLabel'       => n2_('Field name'),
            'tipDescription' => n2_('Only show posts, where the given meta key is equal to the given meta value.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1900-wordpress-custom-posts-generator#post-meta-comparison'
        ));

        new Select($postMeta, 'postmetacompare', n2_('Compare method'), '=', array(
            'options' => array(
                '='           => '=',
                '!='          => '!=',
                '>'           => '>',
                '>='          => '>=',
                '<'           => '<',
                '<='          => '<=',
                'LIKE'        => 'LIKE',
                'NOT LIKE'    => 'NOT LIKE',
                'IN'          => 'IN',
                'NOT IN'      => 'NOT IN',
                'BETWEEN'     => 'BETWEEN',
                'NOT BETWEEN' => 'NOT BETWEEN',
                'REGEXP'      => 'REGEXP',
                'NOT REGEXP'  => 'NOT REGEXP',
                'RLIKE'       => 'RLIKE',
                'EXISTS'      => 'EXISTS',
                'NOT EXISTS'  => 'NOT EXISTS'
            )
        ));

        new Text($postMeta, 'postmetavalue', n2_('Field value'));

        new Select($postMeta, 'postmetatype', n2_('Field type'), 'CHAR', array(
            'options' => array(
                'CHAR'     => 'CHAR',
                'NUMERIC'  => 'NUMERIC',
                'DATE'     => 'DATE',
                'DATETIME' => 'DATETIME',
                'TIME'     => 'TIME',
                'BINARY'   => 'BINARY',
                'DECIMAL'  => 'DECIMAL',
                'SIGNED'   => 'SIGNED',
                'UNSIGNED' => 'UNSIGNED'
            )
        ));

        $postMetaMore = $filterGroup->createRow('postmeta-more');
        new Textarea($postMetaMore, 'postmetakeymore', n2_('Meta comparison'), '', array(
            'tipLabel'       => n2_('Meta comparison'),
            'tipDescription' => sprintf(n2_('You can create other comparisons based on the previous "Post Meta Comparison" options. Use the following format: name||compare||value||type%1$s%1$s Example:%1$spublished||=||yes||CHAR%1$s%1$sWrite one comparison per line.'), '<br>'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1900-wordpress-custom-posts-generator#post-meta-comparison',
            'width'          => 300,
            'height'         => 100
        ));


        $option = $filterGroup->createRow('option');
        new PostsOptions($option, 'postoption', n2_('Post option'), '0', array(
            'tipLabel'       => n2_('Post option'),
            'tipDescription' => n2_('Posts can have options, like a post can be "sticky" or not. You can choose to only display posts, which are selected to be IN or NOT IN this option.')
        ));

        new Select($option, 'postoptionin', n2_('Post relationship with selected option'), '0', array(
            'options' => array(
                0 => 'IN',
                1 => 'NOT IN'
            )
        ));

        $dateGroup = $filterGroup->createRowGroup('dateGroup', n2_('Date configuration'));
        $date      = $filterGroup->createRow('date');
        new OnOff($date, 'identifydatetime', n2_('Identify datetime'), 0, array(
            'tipLabel'       => n2_('Identify datetime'),
            'tipDescription' => n2_('Our system tries to identify the date and time in your variables.')
        ));
        new Text($date, 'datetimeformat', n2_('Datetime format'), 'm-d-Y H:i:s', array(
            'tipLabel'       => n2_('Datetime format'),
            'tipDescription' => sprintf(n2_('You can use any %1$sPHP date format%2$s.'), '<a href="http://php.net/manual/en/function.date.php">', '</a>')
        ));
        new Textarea($date, 'translatedate', n2_('Translate dates'), "from||to\nMonday||Monday\njan||jan", array(
            'tipLabel'       => n2_('Translate dates'),
            'tipDescription' => sprintf(n2_('Write one per line in the following format: from||to %1$s E.g.: Monday||Montag'), '<br>'),
            'width'          => 300,
            'height'         => 200
        ));

        $replaceGroup = $filterGroup->createRowGroup('replaceGroup', n2_('Replace variables'));
        $variables    = $filterGroup->createRow('variables');
        new Text($variables, 'timestampvariables', n2_('Timestamp variables'), '', array(
            'tipLabel'       => n2_('Replace timestamp variables'),
            'tipDescription' => sprintf(n2_('The "Datetime format" will be used to create dates from the given timestamp containing variables. %1$s Separate them with comma.'), '<br>')
        ));

        new Text($variables, 'filevariables', n2_('File variables'), '', array(
            'tipLabel'       => n2_('Replace file variables'),
            'tipDescription' => sprintf(n2_('If you have IDs of files, you can replace those variables with the urls of the files instead. %1$s Separate them with comma.'), '<br>')
        ));

        new Text($variables, 'uniquevariable', n2_('Remove duplicate results'), '', array(
            'tipLabel'       => n2_('Remove duplicate results'),
            'tipDescription' => n2_('You can remove results based on one variable\'s uniqueness. For example if you want the images to be unique, you could write the "image" variable into this field (without quotemarks).')
        ));

        $orderGroup = new ContainerTable($container, 'order-group', n2_('Order'));
        $order      = $orderGroup->createRow('order');
        new GeneratorOrder($order, 'postsorder', 'post_date|*|desc', array(
            'options' => array(
                'none'          => n2_('None'),
                'post_date'     => n2_('Post date'),
                'ID'            => 'ID',
                'title'         => n2_('Title'),
                'post_modified' => n2_('Modification date'),
                'rand'          => n2_('Random'),
                'post__in'      => n2_('Given IDs'),
                'menu_order'    => n2_('Menu order')
            )
        ));

        $metaOrder = $orderGroup->createRow('meta-order');
        new PostsCustomFields($metaOrder, 'meta_order_key', n2_('Custom field name'), 0, array(
            'tipLabel'       => n2_('Custom field name'),
            'tipDescription' => n2_('If it\'s set, this will be used instead of the \'Field\' value.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1900-wordpress-custom-posts-generator#custom-field-name',
            'postType'       => $this->postType
        ));

        new Radio($metaOrder, 'meta_orderby', n2_('Order'), 'meta_value_num', array(
            'options' => array(
                'meta_value_num' => n2_('Numeric'),
                'meta_value'     => n2_('Alphabetic')
            )
        ));
    }

    protected function _getData($count, $startIndex) {
        global $post, $wp_query;
        $tmpPost = $post;

        $identifyDateTime = $this->data->get('identifydatetime', 0);

        if (has_filter('the_content', 'siteorigin_panels_filter_content')) {
            $siteorigin_panels_filter_content = true;
            remove_filter('the_content', 'siteorigin_panels_filter_content');
        } else {
            $siteorigin_panels_filter_content = false;
        }

        $taxonomies = array_diff(explode('||', $this->data->get('taxonomies', '')), array(
            '',
            0
        ));

        if (count($taxonomies)) {
            $tax_array = array();
            foreach ($taxonomies as $tax) {
                $parts = explode('|*|', $tax);
                if (!is_array(@$tax_array[$parts[0]]) || !in_array($parts[1], $tax_array[$parts[0]])) {
                    $tax_array[$parts[0]][] = $parts[1];
                }
            }

            $tax_query = array();
            foreach ($tax_array as $taxonomy => $terms) {
                $tax_query[] = array(
                    'taxonomy' => $taxonomy,
                    'terms'    => $terms,
                    'field'    => 'id'
                );
            }
            $tax_query['relation'] = $this->data->get('taxonomies_relation', 'OR');
        } else {
            $tax_query = '';
        }

        list($orderBy, $order) = Common::parse($this->data->get('postsorder', 'post_date|*|desc'));

        $compare       = array();
        $compare_value = $this->data->get('postmetacompare', '');
        if (!empty($compare_value)) {
            $compare = array('compare' => $compare_value);
        }

        $postMetaKey = $this->data->get('postmetakey', '0');
        if (!empty($postMetaKey)) {
            $postMetaValue = $this->data->get('postmetavalue', '');
            $postMetaValue = $this->checkKeywords($postMetaValue);
            $getPostMeta   = array(
                'meta_query' => array(
                    array(
                        'key'   => $postMetaKey,
                        'value' => $postMetaValue,
                        'type'  => $this->data->get('postmetatype', 'CHAR')
                    ) + $compare
                )
            );
        } else {
            $getPostMeta = array();
        }
        $metaMore = $this->data->get('postmetakeymore', '');
        if (!empty($metaMore) && $metaMore != 'field_name||compare_method||field_value') {
            $metaMoreValues = explode(PHP_EOL, $metaMore);
            foreach ($metaMoreValues as $metaMoreValue) {
                $metaMoreValue = trim($metaMoreValue);
                if ($metaMoreValue != 'field_name||compare_method||field_value') {
                    $metaMoreArray = explode('||', $metaMoreValue);
                    if (count($metaMoreArray) >= 2) {
                        $compare = array('compare' => $metaMoreArray[1]);

                        $key_query = array(
                            'key' => $metaMoreArray[0]
                        );

                        if (!empty($metaMoreArray[2])) {
                            $key_query += array(
                                'value' => $this->checkKeywords($metaMoreArray[2])
                            );
                        }

                        if (!empty($metaMoreArray[3])) {
                            $key_query += array(
                                'type' => $metaMoreArray[3]
                            );
                        }

                        $getPostMeta['meta_query'][] = $key_query + $compare;
                    }
                }
            }
        }

        $post_status = explode(",", $this->data->get('poststatus', 'publish'));

        $meta_order_key = $this->data->get('meta_order_key');
        $meta_key       = '';
        if (!empty($meta_order_key)) {
            $orderBy  = $this->data->get('meta_orderby', 'meta_value_num');
            $meta_key = $meta_order_key;
        }

        $getPosts = array(
            'include'          => '',
            'exclude'          => '',
            'meta_key'         => $meta_key,
            'meta_value'       => '',
            'post_type'        => $this->postType,
            'post_mime_type'   => '',
            'post_parent'      => '',
            'post_status'      => $post_status,
            'suppress_filters' => false,
            'offset'           => $startIndex,
            'posts_per_page'   => $count,
            'tax_query'        => $tax_query
        );

        if ($orderBy != 'none') {
            $getPosts += array(
                'orderby'            => $orderBy,
                'order'              => $order,
                'ignore_custom_sort' => true
            );
        }

        $getPosts = array_merge($getPosts, $getPostMeta);

        $ids = array_diff($this->getIDs(), array(0));

        if (count($ids) > 0) {
            $getPosts += array(
                'post__in' => $ids
            );
        }

        $exclude_ids = array_diff($this->getIDs('exclude_ids'), array(0));

        if (count($exclude_ids) > 0) {
            $getPosts += array(
                'post__not_in' => $exclude_ids
            );
        }

        $post_option = $this->data->get('postoption', 0);
        if (!empty($post_option)) {
            $post_option_in = $this->data->get('postoptionin', 0);
            switch ($post_option_in) {
                case 0:
                    $getPosts += array(
                        'post__in' => get_option($post_option)
                    );
                    break;
                case 1:
                    $getPosts += array(
                        'post__not_in' => get_option($post_option)
                    );
                    break;
            }
        }

        $posts = get_posts($getPosts);

        $data = array();

        $timestampVariables = array_map('trim', explode(',', $this->data->get('timestampvariables', '')));
        $fileVariables      = array_map('trim', explode(',', $this->data->get('filevariables', '')));
        $datetimeformat     = $this->data->get('datetimeformat', 'm-d-Y H:i:s');

        for ($i = 0; $i < count($posts); $i++) {
            $record = array();

            $post = $posts[$i];
            setup_postdata($post);
            $wp_query->post = $post;

            $record['id'] = $post->ID;

            $record['url']           = get_permalink();
            $record['title']         = apply_filters('the_title', get_the_title(), $post->ID);
            $record['content']       = get_the_content();
            $record['description']   = GeneratorGroupPosts::removeShortcodes($record['content']);
            $record['author_name']   = $record['author'] = get_the_author();
            $userID                  = get_the_author_meta('ID');
            $record['author_url']    = get_author_posts_url($userID);
            $record['author_avatar'] = get_avatar_url($userID);

            if ($identifyDateTime) {
                $record['date']     = get_the_date('Y-m-d H:i:s');
                $record['modified'] = get_the_modified_date('Y-m-d H:i:s');
            } else {
                $record['date']     = get_the_date();
                $record['modified'] = get_the_modified_date();
            }

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
            $record['url_label'] = 'View';

            $record = array_merge($record, GeneratorGroupPosts::extractPostMeta(get_post_meta($post->ID)));

            $taxonomies = get_post_taxonomies($post->ID);
            $args       = array(
                'orderby' => 'parent',
                'order'   => 'ASC',
                'fields'  => 'all'
            );

            foreach ($taxonomies as $taxonomy) {
                $post_terms = wp_get_object_terms($post->ID, $taxonomy, $args);
                $taxonomy   = str_replace('-', '', $taxonomy);

                for ($j = 0; $j < count($post_terms); $j++) {
                    $record[$taxonomy . '_' . ($j + 1)]                  = $post_terms[$j]->name;
                    $record[$taxonomy . '_' . ($j + 1) . '_ID']          = $post_terms[$j]->term_id;
                    $record[$taxonomy . '_' . ($j + 1) . '_description'] = $post_terms[$j]->description;
                }
            }

            $record = array_merge($record, GeneratorGroupPosts::getACFData($post->ID));

            if (isset($record['primarytermcategory'])) {
                $primary                         = get_category($record['primarytermcategory']);
                $record['primary_category_name'] = $primary->name;
                $record['primary_category_link'] = get_category_link($primary->cat_ID);
            }
            $record['excerpt'] = get_the_excerpt();

            if (!empty($timestampVariables)) {
                foreach ($timestampVariables as $timestampVariable) {
                    if (isset($record[$timestampVariable])) {
                        $record[$timestampVariable] = date($datetimeformat, intval($record[$timestampVariable]));
                    }
                }
            }

            if (!empty($fileVariables)) {
                foreach ($fileVariables as $fileVariable) {
                    if (isset($record[$fileVariable])) {
                        $record[$fileVariable] = wp_get_attachment_url($record[$fileVariable]);
                    }
                }
            }

            $record = apply_filters('smartslider3_posts_customposts_data', $record);

            $data[$i] = &$record;
            unset($record);
        }

        $unique_variable = $this->data->get('uniquevariable', '');
        if (!empty($unique_variable)) {
            $count         = count($data);
            $unique_helper = array();
            for ($i = 0; $i < $count; $i++) {
                if (!in_array($data[$i][$unique_variable], $unique_helper)) {
                    $unique_helper[] = $data[$i][$unique_variable];
                } else {
                    unset($data[$i]);
                }
            }
            $data = array_values($data);
        }

        if ($siteorigin_panels_filter_content) {
            add_filter('the_content', 'siteorigin_panels_filter_content');
        }

        $wp_query->post = $tmpPost;
        wp_reset_postdata();

        if ($identifyDateTime) {
            $translate_dates = $this->data->get('translatedate', '');
            $translateValue  = explode(PHP_EOL, $translate_dates);
            $translate       = array();
            if (!empty($translateValue)) {
                foreach ($translateValue as $tv) {
                    $translateArray = explode('||', $tv);
                    if (!empty($translateArray) && count($translateArray) == 2) {
                        $translate[$translateArray[0]] = $translateArray[1];
                    }
                }
            }
            for ($i = 0; $i < count($data); $i++) {
                foreach ($data[$i] as $key => $value) {
                    if ($this->isDate($value)) {
                        $data[$i][$key] = $this->translate($this->formatDate($value, $datetimeformat), $translate);
                    }
                }
            }
        }

        return $data;
    }

    protected function isDate($value) {
        if (!$value) {
            return false;
        } else {
            $date = date_parse($value);
            if ($date['error_count'] == 0 && $date['warning_count'] == 0) {
                return checkdate($date['month'], $date['day'], $date['year']);
            } else {
                return false;
            }
        }
    }

    protected function formatDate($date, $format) {
        return date($format, strtotime($date));
    }

    protected function translate($from, $translate) {
        if (!empty($translate) && !empty($from)) {
            foreach ($translate as $key => $value) {
                $from = str_replace($key, trim($value), $from);
            }
        }

        return $from;
    }
}
