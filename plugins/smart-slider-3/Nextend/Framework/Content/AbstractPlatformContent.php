<?php

namespace Nextend\Framework\Content;

abstract class AbstractPlatformContent {

    /**
     * @param $keyword
     *
     * @return array links
     * $links[] = array(
     * 'title'       => '',
     * 'link'        => '',
     * 'info'        => ''
     * );
     */
    abstract public function searchLink($keyword);


    /**
     * @param $keyword
     *
     * @return array links
     * $links[] = array(
     * 'title'       => '',
     * 'description' => '',
     * 'image'       => '',
     * 'link'        => '',
     * 'info'        => ''
     * );
     */
    abstract public function searchContent($keyword);
}