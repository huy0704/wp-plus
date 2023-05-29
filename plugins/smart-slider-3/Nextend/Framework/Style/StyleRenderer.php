<?php

namespace Nextend\Framework\Style;

use Nextend\Framework\Settings;

class StyleRenderer {

    public static $pre = '';

    /**
     * @var Style
     */
    public static $style;

    public static $mode;

    public static function render($style, $mode, $pre = '') {
        self::$pre = $pre;

        if (!empty($style)) {

            $value = json_decode($style, true);
            if ($value) {
                $selector = 'n2-style-' . md5($style) . '-' . $mode;

                return array(
                    $selector . ' ',
                    self::renderStyle($mode, $pre, $selector, $value['data'])
                );
            }
        }

        return false;
    }

    private static function renderStyle($mode, $pre, $selector, $tabs) {
        $search  = array(
            '@pre',
            '@selector'
        );
        $replace = array(
            $pre,
            '.' . $selector
        );
        $tabs[0] = array_merge(array(
            'backgroundcolor' => 'ffffff00',
            'opacity'         => 100,
            'padding'         => '0|*|0|*|0|*|0|*|px',
            'boxshadow'       => '0|*|0|*|0|*|0|*|000000ff',
            'border'          => '0|*|solid|*|000000ff',
            'borderradius'    => '0',
            'extra'           => '',
        ), $tabs[0]);

        foreach ($tabs as $k => $tab) {
            $search[]  = '@tab' . $k;
            $replace[] = self::$style->style($tab);
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

StyleRenderer::$mode = array(
    '0'              => array(
        'id'            => '0',
        'label'         => n2_('Single'),
        'tabs'          => array(
            n2_('Text')
        ),
        'renderOptions' => array(
            'combined' => false
        ),
        'preview'       => '<div class="{styleClassName}">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>',
        'selectors'     => array(
            '@pre@selector' => '@tab'
        )
    ),
    'simple'         => array(
        'id'            => 'simple',
        'label'         => n2_('Simple'),
        'tabs'          => array(
            n2_('Normal')
        ),
        'renderOptions' => array(
            'combined' => true
        ),
        'preview'       => '<div class="{styleClassName}" style="width: 200px; height:100px;"></div>',
        'selectors'     => array(
            '@pre@selector' => '@tab0'
        )
    ),
    'box'            => array(
        'id'            => 'box',
        'label'         => n2_('Box'),
        'tabs'          => array(
            n2_('Normal'),
            n2_('Hover')
        ),
        'renderOptions' => array(
            'combined' => true
        ),
        'preview'       => '<div class="{styleClassName}" style="width: 200px; height:100px;"></div>',
        'selectors'     => array(
            '@pre@selector'       => '@tab0',
            '@pre@selector:HOVER' => '@tab1'
        )
    ),
    'button'         => array(
        'id'            => 'button',
        'label'         => n2_('Button'),
        'tabs'          => array(
            n2_('Normal'),
            n2_('Hover')
        ),
        'renderOptions' => array(
            'combined' => true
        ),
        'preview'       => '<div><a style="display:inline-block; margin:20px;" class="{styleClassName}" href="#" onclick="return false;">Button</a></div>',
        'selectors'     => $frontendAccessibility ? array(
            '@pre@selector'                                                  => '@tab0',
            '@pre@selector:Hover, @pre@selector:ACTIVE, @pre@selector:FOCUS' => '@tab1'
        ) : array(
            '@pre@selector, @pre@selector:FOCUS'        => '@tab0',
            '@pre@selector:Hover, @pre@selector:ACTIVE' => '@tab1'
        )
    ),
    'heading'        => array(
        'id'            => 'heading',
        'label'         => n2_('Heading'),
        'tabs'          => array(
            n2_('Normal'),
            n2_('Hover')
        ),
        'renderOptions' => array(
            'combined' => true
        ),
        'preview'       => '<div class="{styleClassName}">Heading</div>',
        'selectors'     => $frontendAccessibility ? array(
            '@pre@selector'                                                  => '@tab0',
            '@pre@selector:Hover, @pre@selector:ACTIVE, @pre@selector:FOCUS' => '@tab1'
        ) : array(
            '@pre@selector, @pre@selector:FOCUS'        => '@tab0',
            '@pre@selector:Hover, @pre@selector:ACTIVE' => '@tab1'
        )
    ),
    'heading-active' => array(
        'id'            => 'heading-active',
        'label'         => n2_('Heading active'),
        'tabs'          => array(
            n2_('Normal'),
            n2_('Active')
        ),
        'renderOptions' => array(
            'combined' => true
        ),
        'preview'       => '<div class="{styleClassName}">Heading</div>',
        'selectors'     => array(
            '@pre@selector'           => '@tab0',
            '@pre@selector.n2-active' => '@tab1'
        )
    ),
    'dot'            => array(
        'id'            => 'dot',
        'label'         => n2_('Dot'),
        'tabs'          => array(
            n2_('Normal'),
            n2_('Active')
        ),
        'renderOptions' => array(
            'combined' => true
        ),
        'preview'       => '<div><div class="{styleClassName}" style="display: inline-block; margin: 3px;"></div><div class="{styleClassName} n2-active" style="display: inline-block; margin: 3px;"></div><div class="{styleClassName}" style="display: inline-block; margin: 3px;"></div></div>',
        'selectors'     => array(
            '@pre@selector'                                                     => '@tab0',
            '@pre@selector.n2-active, @pre@selector:HOVER, @pre@selector:FOCUS' => '@tab1'
        )
    ),
    'highlight'      => array(
        'id'            => 'highlight',
        'label'         => n2_('Highlight'),
        'tabs'          => array(
            n2_('Normal'),
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


StyleRenderer::$style = new Style();