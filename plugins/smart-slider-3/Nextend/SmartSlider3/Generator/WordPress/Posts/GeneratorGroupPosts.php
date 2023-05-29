<?php

namespace Nextend\SmartSlider3\Generator\WordPress\Posts;

use Nextend\SmartSlider3\Generator\AbstractGeneratorGroup;
use Nextend\SmartSlider3\Generator\WordPress\Posts\Sources\PostsAllCustomPosts;
use Nextend\SmartSlider3\Generator\WordPress\Posts\Sources\PostsCustomPosts;
use Nextend\SmartSlider3\Generator\WordPress\Posts\Sources\PostsPosts;
use Nextend\SmartSlider3\Generator\WordPress\Posts\Sources\PostsPostsByIDs;

class GeneratorGroupPosts extends AbstractGeneratorGroup {

    protected $name = 'posts';

    public function getLabel() {
        return n2_('Posts');
    }

    public function getDescription() {
        return sprintf(n2_('Creates slides from %1$s.'), 'WordPress posts');
    }

    protected function loadSources() {

        new PostsPosts($this, 'posts', n2_('Posts by filter'));

        new PostsPostsByIDs($this, 'postsbyids', n2_('Posts by IDs'));
    }

    public static $ElementorCount = 0;
    public static $ElementorWidgetType = '';

    public static function getElementorTextEditors($array) {
        $datas = array();
        if (!is_array($array)) {
            $array = (array)$array;
        }
        foreach ($array as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $datas = array_merge($datas, self::getElementorTextEditors($value));
            } else {
                if (isset($array['widgetType'])) {
                    self::$ElementorWidgetType = $array['widgetType'];
                }
                if ($key == 'editor' && self::$ElementorWidgetType == 'text-editor') {
                    self::$ElementorCount++;
                    $datas[$key . self::$ElementorCount] = $value;
                }
            }
        }

        return $datas;
    }

    public static function resetElementorHelpers() {
        self::$ElementorCount      = 0;
        self::$ElementorWidgetType = '';
    }

    public static function extractPostMeta($post_meta, $pre = '') {
        $record = array();
        if (count($post_meta) && is_array($post_meta) && !empty($post_meta)) {
            $excluded_metas = array(
                'hc-editor-mode',
                'techline-sidebar'
            );

            foreach ($excluded_metas as $excluded_meta) {
                if (isset($post_meta[$excluded_meta])) {
                    unset($post_meta[$excluded_meta]);
                }
            }

            foreach ($post_meta as $key => $value) {
                if (count($value) && is_array($value) && !empty($value)) {
                    foreach ($value as $v) {
                        if (!empty($v) && !is_array($v) && !is_object($v)) {
                            $key = str_replace(array(
                                '_',
                                '-'
                            ), array(
                                '',
                                ''
                            ), $key);
                            $key = $pre . $key;
                            if (array_key_exists($key, $record)) {
                                $key = 'meta' . $key;
                            }
                            if (is_serialized($v)) {
                                $unserialize_values = unserialize($v);
                                $unserialize_count  = 1;
                                if (!empty($unserialize_values) && is_array($unserialize_values)) {
                                    foreach ($unserialize_values as $unserialize_value) {
                                        if (!empty($unserialize_value) && is_string($unserialize_value)) {
                                            $record['us_' . $key . $unserialize_count] = $unserialize_value;
                                            $unserialize_count++;
                                        } else if (is_array($unserialize_value)) {
                                            foreach ($unserialize_value as $u_v) {
                                                if (is_string($u_v)) {
                                                    $record['us_' . $key . $unserialize_count] = $u_v;
                                                    $unserialize_count++;
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                $record[$key] = $v;
                            }
                        }
                    }
                }
            }

            if (!empty($record['elementordata'])) {
                $elementordatas = json_decode($record['elementordata']);
                foreach ($elementordatas as $elementordata) {
                    foreach (self::getElementorTextEditors($elementordata) as $elementorKey => $elementorVal) {
                        $record[$elementorKey] = $elementorVal;
                    }
                }
            }
            self::resetElementorHelpers();
        }

        return $record;
    }

    public static function getACFData($postID, $pre = '') {
        $record = array();
        if (class_exists('acf')) {
            $fields = get_fields($postID);
            if (is_array($fields) && !empty($fields) && count($fields)) {
                foreach ($fields as $k => $v) {
                    $type = self::getACFType($k, $postID);
                    $k    = str_replace('-', '', $k);
                    $k    = $pre . $k;

                    while (isset($record[$k])) {
                        $k = 'acf_' . $k;
                    }
                    if (!is_array($v) && !is_object($v)) {
                        if ($type['type'] == "image" && is_numeric($type["value"])) {
                            $thumbnail_meta = wp_get_attachment_metadata($type["value"]);
                            $src            = wp_get_attachment_image_src($v, $thumbnail_meta['file']);
                            $v              = $src[0];
                        }
                        $record[$k] = $v;
                    } else if (!is_object($v)) {
                        if (isset($v['url'])) {
                            $record[$k] = $v['url'];
                        } else if (is_array($v)) {
                            foreach ($v as $v_v => $k_k) {
                                if (is_array($k_k) && isset($k_k['url'])) {
                                    $record[$k . $v_v] = $k_k['url'];
                                }
                            }
                        }
                    }
                    if ($type['type'] == "image" && (is_numeric($type["value"]) || is_array($type['value']))) {
                        if (is_array($type['value'])) {
                            $sizes = self::getImageSizes($type["value"]["id"], $type["value"]["sizes"], $k);
                        } else {
                            $thumbnail_meta = wp_get_attachment_metadata($type["value"]);
                            $sizes          = self::getImageSizes($type["value"], $thumbnail_meta['sizes'], $k);
                        }
                        $record = array_merge($record, $sizes);
                    }
                }
            }
        }

        return $record;
    }

    public static function getACFType($key, $post_id) {
        $type = get_field_object($key, $post_id);

        return $type;
    }

    public static function getImageSizes($thumbnail_id, $sizes, $prefix = false) {
        $data = array();
        if (!$prefix) {
            $prefix = "";
        } else {
            $prefix = $prefix . "_";
        }
        foreach ($sizes as $size => $image) {
            $imageSrc                                              = wp_get_attachment_image_src($thumbnail_id, $size);
            $data[$prefix . 'image_' . self::clearSizeName($size)] = $imageSrc[0];
        }

        return $data;
    }

    public static function clearSizeName($size) {
        return preg_replace("/-/", "_", $size);
    }

    public static function removeShortcodes($variable) {
        return preg_replace('#\[[^\]]+\]#', '', $variable);
    }

    public static function getCategoryData($postID) {
        $record   = array();
        $category = get_the_category($postID);
        if (isset($category[0])) {
            $record['category_name'] = $category[0]->name;
            $record['category_link'] = get_category_link($category[0]->cat_ID);
            $record['category_slug'] = $category[0]->slug;
        } else {
            $record['category_name'] = '';
            $record['category_link'] = '';
            $record['category_slug'] = '';
        }
        $j = 0;
        if (is_array($category) && count($category) > 1) {
            foreach ($category as $cat) {
                $record['category_name_' . $j] = $cat->name;
                $record['category_link_' . $j] = get_category_link($cat->cat_ID);
                $record['category_slug_' . $j] = $cat->slug;
                $j++;
            }
        } else {
            $record['category_name_0'] = $record['category_name'];
            $record['category_link_0'] = $record['category_link'];
            $record['category_slug_0'] = $record['category_slug'];
        }

        return $record;
    }
}