<?php

namespace Nextend\Framework\Font;

use Nextend\Framework\Settings;

class FontRenderer {

    public static $defaultFont = 'Montserrat';

    public static $pre = '';

    /**
     * @var FontStyle
     */
    public static $style;

    public static $mode;

    public static function setDefaultFont($fontFamily) {
        self::$defaultFont = $fontFamily;
    }

    public static function render($font, $mode, $pre = '', $fontSize = false) {
        self::$pre = $pre;

        if (!empty($font)) {
            $value = json_decode($font, true);
            if ($value) {
                $selector = 'n2-font-' . md5($font) . '-' . $mode;

                return array(
                    $selector . ' ',
                    self::renderFont($mode, $pre, $selector, $value['data'], $fontSize)
                );
            }
        }

        return false;
    }

    private static function renderFont($mode, $pre, $selector, $tabs, $fontSize) {
        $search  = array(
            '@pre',
            '@selector'
        );
        $replace = array(
            $pre,
            '.' . $selector
        );
        $tabs[0] = array_merge(array(
            'afont'         => self::$defaultFont,
            'color'         => '000000ff',
            'size'          => '14||px',
            'tshadow'       => '0|*|0|*|0|*|000000ff',
            'lineheight'    => '1.5',
            'bold'          => 0,
            'italic'        => 0,
            'underline'     => 0,
            'align'         => 'left',
            'letterspacing' => "normal",
            'wordspacing'   => "normal",
            'texttransform' => "none",
            'extra'         => ''
        ), $tabs[0]);

        if (self::$mode[$mode]['renderOptions']['combined']) {
            for ($i = 1; $i < count($tabs); $i++) {
                $tabs[$i] = array_merge($tabs[$i - 1], $tabs[$i]);
                if ($tabs[$i]['size'] == $tabs[0]['size']) {
                    $tabs[$i]['size'] = '100||%';
                } else {
                    $size1 = explode('||', $tabs[0]['size']);
                    $size2 = explode('||', $tabs[$i]['size']);
                    if (isset($size1[1]) && isset($size2[1]) && $size1[1] == 'px' && $size2[1] == 'px') {
                        $tabs[$i]['size'] = round($size2[0] / $size1[0] * 100) . '||%';
                    }
                }
            }
        }
        foreach ($tabs as $k => $tab) {
            $search[]            = '@tab' . $k;
            FontStyle::$fontSize = $fontSize;
            $replace[]           = self::$style->style($tab);
        }

        $template = '';
        foreach (self::$mode[$mode]['selectors'] as $s => $style) {
            $key = array_search($style, $search);
            if (is_numeric($key) && !empty($replace[$key])) {
                $template .= $s . "{" . $style . "}";
            }
        }

        return str_replace($search, $replace, $template);
    }
}

$frontendAccessibility = intval(Settings::get('frontend-accessibility', 1));

FontRenderer::$mode = array(
    '0'         => array(
        'id'            => '0',
        'label'         => n2_('Text'),
        'tabs'          => array(
            n2_('Text')
        ),
        'renderOptions' => array(
            'combined' => false
        ),
        'preview'       => '<div class="{fontClassName}">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>',
        'selectors'     => array(
            '@pre@selector' => '@tab0'
        )
    ),
    'simple'    => array(
        'id'            => 'simple',
        'label'         => n2_('Text'),
        'tabs'          => array(
            n2_('Text')
        ),
        'renderOptions' => array(
            'combined' => false
        ),
        'preview'       => '<div class="{fontClassName}">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>',
        'selectors'     => array(
            '@pre@selector' => '@tab0'
        )
    ),
    'hover'     => array(
        'id'            => 'hover',
        'label'         => n2_('Hover'),
        'tabs'          => array(
            n2_('Text'),
            n2_('Hover')
        ),
        'renderOptions' => array(
            'combined' => false
        ),
        'preview'       => '<div class="{fontClassName}">' . n2_('Heading') . '</div>',
        'selectors'     => $frontendAccessibility ? array(
            '@pre@selector'                                                  => '@tab0',
            '@pre@selector:HOVER, @pre@selector:ACTIVE, @pre@selector:FOCUS' => '@tab1'
        ) : array(
            '@pre@selector, @pre@selector:FOCUS'        => '@tab0',
            '@pre@selector:HOVER, @pre@selector:ACTIVE' => '@tab1'
        )
    ),
    'link'      => array(
        'id'            => 'link',
        'label'         => n2_('Link'),
        'tabs'          => array(
            n2_('Text'),
            n2_('Hover')
        ),
        'renderOptions' => array(
            'combined' => false
        ),
        'preview'       => '<div class="{fontClassName}"><a href="#" onclick="return false;">' . n2_('Button') . '</a></div>',
        'selectors'     => $frontendAccessibility ? array(
            '@pre@selector a'                                                      => '@tab0',
            '@pre@selector a:HOVER, @pre@selector a:ACTIVE, @pre@selector a:FOCUS' => '@tab1'
        ) : array(
            '@pre@selector a, @pre@selector a:FOCUS'        => '@tab0',
            '@pre@selector a:HOVER, @pre@selector a:ACTIVE' => '@tab1'
        )
    ),
    'paragraph' => array(
        'id'            => 'paragraph',
        'label'         => n2_('Paragraph'),
        'tabs'          => array(
            n2_('Text'),
            n2_('Link'),
            n2_('Hover')
        ),
        'renderOptions' => array(
            'combined' => true
        ),
        'preview'       => '<div class="{fontClassName}">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do <a href="#">test link</a> incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in <a href="#">test link</a> velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat <a href="#">test link</a>, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>',
        'selectors'     => array(
            '@pre@selector'                                 => '@tab0',
            '@pre@selector a, @pre@selector a:FOCUS'        => '@tab1',
            '@pre@selector a:HOVER, @pre@selector a:ACTIVE' => '@tab2'
        )
    ),
    'input'     => array(
        'id'            => 'input',
        'label'         => 'Input',
        'tabs'          => array(
            n2_('Text'),
            n2_('Hover')
        ),
        'renderOptions' => array(
            'combined' => false
        ),
        'preview'       => '<div class="{fontClassName}">Excepteur sint occaecat</div>',
        'selectors'     => array(
            '@pre@selector'                            => '@tab0',
            '@pre@selector:HOVER, @pre@selector:FOCUS' => '@tab2'
        )
    ),
    'dot'       => array(
        'id'            => 'dot',
        'label'         => n2_('Dot'),
        'tabs'          => array(
            n2_('Text'),
            n2_('Active')
        ),
        'renderOptions' => array(
            'combined' => false
        ),
        'preview'       => '',
        'selectors'     => array(
            '@pre@selector, @pre@selector:FOCUS'                                 => '@tab0',
            '@pre@selector.n2-active, @pre@selector:HOVER, @pre@selector:ACTIVE' => '@tab1'
        )
    ),
    'list'      => array(
        'id'            => 'list',
        'label'         => n2_('List'),
        'tabs'          => array(
            n2_('Text'),
            n2_('Link'),
            n2_('Hover')
        ),
        'renderOptions' => array(
            'combined' => false
        ),
        'preview'       => '',
        'selectors'     => array(
            '@pre@selector li'                                    => '@tab0',
            '@pre@selector li a, @pre@selector li a:FOCUS'        => '@tab1',
            '@pre@selector li a:HOVER, @pre@selector li a:ACTIVE' => '@tab2'
        )
    ),
    'highlight' => array(
        'id'            => 'highlight',
        'label'         => n2_('Highlight'),
        'tabs'          => array(
            n2_('Text'),
            n2_('Highlight'),
            n2_('Hover')
        ),
        'renderOptions' => array(
            'combined' => true
        ),
        'preview'       => '<div class="{fontClassName}">' . n2_('Button') . '</div>',
        'selectors'     => $frontendAccessibility ? array(
            '@pre@selector'                                                                                                  => '@tab0',
            '@pre@selector .n2-highlighted'                                                                                  => '@tab1',
            '@pre@selector .n2-highlighted:HOVER, @pre@selector .n2-highlighted:ACTIVE, @pre@selector .n2-highlighted:FOCUS' => '@tab2'
        ) : array(
            '@pre@selector'                                                             => '@tab0',
            '@pre@selector .n2-highlighted, @pre@selector .n2-highlighted:FOCUS'        => '@tab1',
            '@pre@selector .n2-highlighted:HOVER, @pre@selector .n2-highlighted:ACTIVE' => '@tab2'
        )
    ),
);

FontRenderer::$style = new FontStyle();