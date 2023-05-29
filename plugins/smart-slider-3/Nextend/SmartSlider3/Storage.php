<?php


namespace Nextend\SmartSlider3;


use Nextend\Framework\Pattern\SingletonTrait;
use Nextend\Framework\Plugin;

class Storage {

    use SingletonTrait;

    protected function init() {
        Plugin::addAction('fontStorage', array(
            $this,
            'fontStorage'
        ));
        Plugin::addAction('styleStorage', array(
            $this,
            'styleStorage'
        ));

        Plugin::addAction('splitTextAnimationStorage', array(
            $this,
            'splitTextAnimationStorage'
        ));
        Plugin::addAction('backgroundAnimationStorage', array(
            $this,
            'backgroundAnimationStorage'
        ));
        Plugin::addAction('postBackgroundAnimationStorage', array(
            $this,
            'postBackgroundAnimationStorage'
        ));
        Plugin::addAction('layoutStorage', array(
            $this,
            'layoutStorage'
        ));

        Plugin::addAction('ss3itemheadingStorage', array(
            $this,
            'itemheadingStorage'
        ));
        Plugin::addAction('ss3itemtextStorage', array(
            $this,
            'itemtextStorage'
        ));
        Plugin::addAction('ss3itembuttonStorage', array(
            $this,
            'itembuttonStorage'
        ));
        Plugin::addAction('ss3itemiconStorage', array(
            $this,
            'itemiconStorage'
        ));
    }


    public function styleStorage(&$sets, &$styles) {

        array_push($sets, array(
            'id'           => 1000,
            'referencekey' => '',
            'value'        => n2_('Heading')
        ));

        array_push($styles, array(
            'id'           => 1001,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Blue'),
                'data' => array(
                    array(
                        'backgroundcolor' => '01add3ff',
                        'padding'         => '5|*|20|*|5|*|20|*|px',
                    ),

                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1002,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('White'),
                'data' => array(
                    array(
                        'backgroundcolor' => 'ffffffcc',
                        'padding'         => '5|*|20|*|5|*|20|*|px',
                    ),

                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1003,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Green'),
                'data' => array(
                    array(
                        'backgroundcolor' => '5cba3cff',
                        'padding'         => '5|*|20|*|5|*|20|*|px',
                    ),

                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1004,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Purple'),
                'data' => array(
                    array(
                        'backgroundcolor' => '8757b2ff',
                        'padding'         => '5|*|20|*|5|*|20|*|px',
                    ),

                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1005,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Grey'),
                'data' => array(
                    array(
                        'backgroundcolor' => '81898dff',
                        'padding'         => '5|*|20|*|5|*|20|*|px',
                    ),

                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1006,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Black'),
                'data' => array(
                    array(
                        'backgroundcolor' => '000000cc',
                        'padding'         => '5|*|20|*|5|*|20|*|px',
                    ),

                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1007,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Rounded Blue'),
                'data' => array(
                    array(
                        'backgroundcolor' => '01add3ff',
                        'padding'         => '5|*|20|*|5|*|20|*|px',
                        'borderradius'    => '3',
                    ),

                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1008,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Rounded Green'),
                'data' => array(
                    array(
                        'backgroundcolor' => '5cba3cff',
                        'padding'         => '5|*|20|*|5|*|20|*|px',
                        'borderradius'    => '3',
                    ),

                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1009,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Rounded Purple'),
                'data' => array(
                    array(
                        'backgroundcolor' => '8757b2ff',
                        'padding'         => '5|*|20|*|5|*|20|*|px',
                        'borderradius'    => '3',
                    ),

                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1010,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Rounded Grey'),
                'data' => array(
                    array(
                        'backgroundcolor' => '81898dff',
                        'padding'         => '5|*|20|*|5|*|20|*|px',
                        'borderradius'    => '3',
                    ),

                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1011,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Rounded White'),
                'data' => array(
                    array(
                        'backgroundcolor' => 'ffffffcc',
                        'padding'         => '5|*|20|*|5|*|20|*|px',
                        'borderradius'    => '3',
                    ),

                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1012,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Rounded Black'),
                'data' => array(
                    array(
                        'backgroundcolor' => '000000cc',
                        'padding'         => '5|*|20|*|5|*|20|*|px',
                        'borderradius'    => '3',
                    ),

                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1013,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Border White'),
                'data' => array(
                    array(
                        'backgroundcolor' => '00000000',
                        'padding'         => '5|*|20|*|5|*|20|*|px',
                        'border'          => '2|*|solid|*|ffffffff',
                    ),

                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1014,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Border Dark'),
                'data' => array(
                    array(
                        'backgroundcolor' => '00000000',
                        'padding'         => '5|*|20|*|5|*|20|*|px',
                        'border'          => '2|*|solid|*|000000cc',
                    ),

                ),
            )
        ));


        array_push($sets, array(
            'id'           => 1100,
            'referencekey' => '',
            'value'        => n2_('Button')
        ));

        array_push($styles, array(
            'id'           => 1101,
            'referencekey' => 1100,
            'value'        => array(
                'name' => n2_('Rectangle Green'),
                'data' => array(
                    array(
                        'backgroundcolor' => '5cba3cff',
                        'padding'         => '10|*|30|*|10|*|30|*|px',
                    ),

                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1102,
            'referencekey' => 1100,
            'value'        => array(
                'name' => n2_('Rectangle Blue'),
                'data' => array(
                    array(
                        'backgroundcolor' => '01add3ff',
                        'padding'         => '10|*|30|*|10|*|30|*|px',
                    ),

                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1103,
            'referencekey' => 1100,
            'value'        => array(
                'name' => n2_('Rectangle Purple'),
                'data' => array(
                    array(
                        'backgroundcolor' => '8757b2ff',
                        'padding'         => '10|*|30|*|10|*|30|*|px',
                    ),

                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1104,
            'referencekey' => 1100,
            'value'        => array(
                'name' => n2_('Rectangle Grey'),
                'data' => array(
                    array(
                        'backgroundcolor' => '81898dff',
                        'padding'         => '10|*|30|*|10|*|30|*|px',
                    ),

                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1105,
            'referencekey' => 1100,
            'value'        => array(
                'name' => n2_('Rounded Rectangle Green'),
                'data' => array(
                    array(
                        'backgroundcolor' => '5cba3cff',
                        'padding'         => '10|*|30|*|10|*|30|*|px',
                        'borderradius'    => '3',
                    ),
                    array(
                        'backgroundcolor' => '58ad3bff',
                    ),
                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1106,
            'referencekey' => 1100,
            'value'        => array(
                'name' => n2_('Rounded Rectangle Blue'),
                'data' => array(
                    array(
                        'backgroundcolor' => '01add3ff',
                        'padding'         => '10|*|30|*|10|*|30|*|px',
                        'borderradius'    => '3',
                    ),
                    array(
                        'backgroundcolor' => '04a0c3ff',
                    ),
                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1107,
            'referencekey' => 1100,
            'value'        => array(
                'name' => n2_('Rounded Rectangle Purple'),
                'data' => array(
                    array(
                        'backgroundcolor' => '8757b2ff',
                        'padding'         => '10|*|30|*|10|*|30|*|px',
                        'borderradius'    => '3',
                    ),
                    array(
                        'backgroundcolor' => '7b51a1ff',
                    ),
                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1108,
            'referencekey' => 1100,
            'value'        => array(
                'name' => n2_('Rounded Grey'),
                'data' => array(
                    array(
                        'backgroundcolor' => '81898dff',
                        'padding'         => '10|*|30|*|10|*|30|*|px',
                        'borderradius'    => '3',
                    ),

                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1109,
            'referencekey' => 1100,
            'value'        => array(
                'name' => n2_('Rounded Green'),
                'data' => array(
                    array(
                        'backgroundcolor' => '5cba3cff',
                        'padding'         => '10|*|30|*|10|*|30|*|px',
                        'borderradius'    => '30',
                    ),

                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1110,
            'referencekey' => 1100,
            'value'        => array(
                'name' => n2_('Rounded Blue'),
                'data' => array(
                    array(
                        'backgroundcolor' => '01add3ff',
                        'padding'         => '10|*|30|*|10|*|30|*|px',
                        'borderradius'    => '30',
                    ),

                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1111,
            'referencekey' => 1100,
            'value'        => array(
                'name' => n2_('Rounded Purple'),
                'data' => array(
                    array(
                        'backgroundcolor' => '8757b2ff',
                        'padding'         => '10|*|30|*|10|*|30|*|px',
                        'borderradius'    => '30',
                    ),

                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1112,
            'referencekey' => 1100,
            'value'        => array(
                'name' => n2_('Rounded Grey'),
                'data' => array(
                    array(
                        'backgroundcolor' => '81898dff',
                        'padding'         => '10|*|30|*|10|*|30|*|px',
                        'borderradius'    => '30',
                    ),

                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1113,
            'referencekey' => 1100,
            'value'        => array(
                'name' => n2_('Border Dark'),
                'data' => array(
                    array(
                        'backgroundcolor' => '00000000',
                        'padding'         => '10|*|30|*|10|*|30|*|px',
                        'border'          => '2|*|solid|*|000000cc',
                    ),

                ),
            )
        ));

        array_push($styles, array(
            'id'           => 1114,
            'referencekey' => 1100,
            'value'        => array(
                'name' => n2_('Border Light'),
                'data' => array(
                    array(
                        'backgroundcolor' => '00000000',
                        'padding'         => '10|*|30|*|10|*|30|*|px',
                        'border'          => '2|*|solid|*|ffffffff',
                    ),

                ),
            )
        ));

        array_push($sets, array(
            'id'           => 1800,
            'referencekey' => '',
            'value'        => n2_('Other')
        ));

        array_push($styles, array(
            'id'           => 1801,
            'referencekey' => 1800,
            'value'        => array(
                'name' => n2_('List'),
                'data' => array(
                    array(
                        'padding' => '10|*|20|*|10|*|20|*|px',
                        'extra'   => 'margin:0;'
                    ),

                ),
            )
        ));

        array_push($sets, array(
            'id'           => 1900,
            'referencekey' => '',
            'value'        => n2_('My styles')
        ));
    }

    public function fontStorage(&$sets, &$fonts) {

        array_push($sets, array(
            'id'           => 1000,
            'referencekey' => '',
            'value'        => n2_('Default')
        ));

        array_push($fonts, array(
            'id'           => 1001,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('X-small Light'),
                'data' => array(
                    array(
                        'color' => 'ffffffff',
                        'size'  => '12||px',
                    ),


                ),
            )
        ));
        array_push($fonts, array(
            'id'           => 1002,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('X-small Dark'),
                'data' => array(
                    array(
                        'color' => '282828ff',
                        'size'  => '12||px',
                    ),


                ),
            )
        ));
        array_push($fonts, array(
            'id'           => 1003,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Small Light'),
                'data' => array(
                    array(
                        'color' => 'ffffffff',
                        'size'  => '14||px',
                    ),


                ),
            )
        ));
        array_push($fonts, array(
            'id'           => 1004,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Small Dark'),
                'data' => array(
                    array(
                        'color' => '282828ff',
                        'size'  => '14||px',
                    ),


                ),
            )
        ));
        array_push($fonts, array(
            'id'           => 1005,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Medium Light'),
                'data' => array(
                    array(
                        'color' => 'ffffffff',
                        'size'  => '24||px',
                    ),


                ),
            )
        ));
        array_push($fonts, array(
            'id'           => 1006,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Medium Dark'),
                'data' => array(
                    array(
                        'color' => '282828ff',
                        'size'  => '24||px',
                    ),


                ),
            )
        ));
        array_push($fonts, array(
            'id'           => 1007,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Large Light'),
                'data' => array(
                    array(
                        'color' => 'ffffffff',
                        'size'  => '30||px',
                    ),


                ),
            )
        ));
        array_push($fonts, array(
            'id'           => 1008,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Large Dark'),
                'data' => array(
                    array(
                        'color' => '282828ff',
                        'size'  => '30||px',
                    ),


                ),
            )
        ));
        array_push($fonts, array(
            'id'           => 1009,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('X-large Light'),
                'data' => array(
                    array(
                        'color' => 'ffffffff',
                        'size'  => '36||px',
                        'align' => 'center'
                    ),


                ),
            )
        ));
        array_push($fonts, array(
            'id'           => 1010,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('X-large Dark'),
                'data' => array(
                    array(
                        'color' => '282828ff',
                        'size'  => '36||px',
                    ),


                ),
            )
        ));

        array_push($sets, array(
            'id'           => 1100,
            'referencekey' => '',
            'value'        => n2_('Center')
        ));

        array_push($fonts, array(
            'id'           => 1101,
            'referencekey' => 1100,
            'value'        => array(
                'name' => n2_('X-small Light'),
                'data' => array(
                    array(
                        'color' => 'ffffffff',
                        'size'  => '12||px',
                        'align' => 'center'
                    ),


                ),
            )
        ));
        array_push($fonts, array(
            'id'           => 1102,
            'referencekey' => 1100,
            'value'        => array(
                'name' => n2_('X-small Dark'),
                'data' => array(
                    array(
                        'color' => '282828ff',
                        'size'  => '12||px',
                        'align' => 'center'
                    ),


                ),
            )
        ));
        array_push($fonts, array(
            'id'           => 1103,
            'referencekey' => 1100,
            'value'        => array(
                'name' => n2_('Small Light'),
                'data' => array(
                    array(
                        'color' => 'ffffffff',
                        'size'  => '14||px',
                        'align' => 'center'
                    ),


                ),
            )
        ));
        array_push($fonts, array(
            'id'           => 1104,
            'referencekey' => 1100,
            'value'        => array(
                'name' => n2_('Small Dark'),
                'data' => array(
                    array(
                        'color' => '282828ff',
                        'size'  => '14||px',
                        'align' => 'center'
                    ),


                ),
            )
        ));
        array_push($fonts, array(
            'id'           => 1105,
            'referencekey' => 1100,
            'value'        => array(
                'name' => n2_('Medium Light'),
                'data' => array(
                    array(
                        'color' => 'ffffffff',
                        'size'  => '24||px',
                        'align' => 'center'
                    ),


                ),
            )
        ));
        array_push($fonts, array(
            'id'           => 1106,
            'referencekey' => 1100,
            'value'        => array(
                'name' => n2_('Medium Dark'),
                'data' => array(
                    array(
                        'color' => '282828ff',
                        'size'  => '24||px',
                        'align' => 'center'
                    ),


                ),
            )
        ));
        array_push($fonts, array(
            'id'           => 1107,
            'referencekey' => 1100,
            'value'        => array(
                'name' => n2_('Large Light'),
                'data' => array(
                    array(
                        'color' => 'ffffffff',
                        'size'  => '30||px',
                        'align' => 'center'
                    ),


                ),
            )
        ));
        array_push($fonts, array(
            'id'           => 1108,
            'referencekey' => 1100,
            'value'        => array(
                'name' => n2_('Large Dark'),
                'data' => array(
                    array(
                        'color' => '282828ff',
                        'size'  => '30||px',
                        'align' => 'center'
                    ),


                ),
            )
        ));
        array_push($fonts, array(
            'id'           => 1109,
            'referencekey' => 1100,
            'value'        => array(
                'name' => n2_('X-large Light'),
                'data' => array(
                    array(
                        'color' => 'ffffffff',
                        'size'  => '36||px',
                        'align' => 'center'
                    ),


                ),
            )
        ));
        array_push($fonts, array(
            'id'           => 1110,
            'referencekey' => 1100,
            'value'        => array(
                'name' => n2_('X-large Dark'),
                'data' => array(
                    array(
                        'color' => '282828ff',
                        'size'  => '36||px',
                        'align' => 'center'
                    ),


                ),
            )
        ));

        array_push($sets, array(
            'id'           => 1300,
            'referencekey' => '',
            'value'        => n2_('Link')
        ));
        array_push($fonts, array(
            'id'           => 1303,
            'referencekey' => 1300,
            'value'        => array(
                'name' => n2_('Small Light'),
                'data' => array(
                    array(
                        'color' => 'ffffffff',
                        'size'  => '14||px',
                        'align' => 'left'
                    ),
                    array(
                        'color' => '1890d7ff'
                    ),

                ),
            )
        ));
        array_push($fonts, array(
            'id'           => 1304,
            'referencekey' => 1300,
            'value'        => array(
                'name' => n2_('Small Dark'),
                'data' => array(
                    array(
                        'color' => '282828ff',
                        'size'  => '14||px',
                        'align' => 'left'
                    ),
                    array(
                        'color' => '1890d7ff'
                    ),

                ),
            )
        ));

        array_push($sets, array(
            'id'           => 1900,
            'referencekey' => '',
            'value'        => n2_('My fonts')
        ));
    }

    public function splitTextAnimationStorage(&$sets, &$animations) {

        array_push($sets, array(
            'id'           => 1000,
            'referencekey' => '',
            'value'        => n2_('Default')
        ));

        array_push($animations, array(
            'id'           => 1001,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Fade'),
                'data' => array(
                    'transformOrigin' => '50|*|50|*|0',
                    'animation'       => array(
                        'opacity' => 0
                    )
                )
            )
        ));

        array_push($animations, array(
            'id'           => 1002,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Left'),
                'data' => array(
                    'transformOrigin' => '50|*|50|*|0',
                    'animation'       => array(
                        'opacity' => 0,
                        'x'       => -100
                    )
                )
            )
        ));

        array_push($animations, array(
            'id'           => 1003,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Right'),
                'data' => array(
                    'transformOrigin' => '50|*|50|*|0',
                    'animation'       => array(
                        'opacity' => 0,
                        'x'       => 100
                    )
                )
            )
        ));

        array_push($animations, array(
            'id'           => 1004,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Top'),
                'data' => array(
                    'transformOrigin' => '50|*|50|*|0',
                    'animation'       => array(
                        'opacity' => 0,
                        'y'       => -80
                    )
                )
            )
        ));

        array_push($animations, array(
            'id'           => 1005,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Bottom'),
                'data' => array(
                    'transformOrigin' => '50|*|50|*|0',
                    'animation'       => array(
                        'opacity' => 0,
                        'y'       => 80
                    )
                )
            )
        ));

        array_push($animations, array(
            'id'           => 1006,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Scale up'),
                'data' => array(
                    'transformOrigin' => '50|*|50|*|0',
                    'animation'       => array(
                        'opacity' => 0,
                        'scale'   => 0
                    )
                )
            )
        ));

        array_push($animations, array(
            'id'           => 1007,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Scale down'),
                'data' => array(
                    'transformOrigin' => '50|*|50|*|0',
                    'animation'       => array(
                        'opacity' => 0,
                        'scale'   => 5
                    )
                )
            )
        ));

        array_push($animations, array(
            'id'           => 1008,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Warp'),
                'data' => array(
                    'transformOrigin' => '50|*|50|*|0',
                    'animation'       => array(
                        'ease'      => 'easeInBack',
                        'opacity'   => 0,
                        'x'         => 20,
                        'scale'     => 5,
                        'rotationX' => 90
                    )
                )
            )
        ));

        array_push($animations, array(
            'id'           => 1009,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Twirl'),
                'data' => array(
                    'transformOrigin' => '100|*|100|*|0',
                    'animation'       => array(
                        'ease'      => 'easeInBack',
                        'opacity'   => 0,
                        'scale'     => 5,
                        'rotationX' => 360,
                        'rotationY' => -360,
                        'rotationZ' => 360
                    )
                )
            )
        ));

        array_push($animations, array(
            'id'           => 1010,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Domino'),
                'data' => array(
                    'transformOrigin' => '0|*|0|*|0',
                    'animation'       => array(
                        'ease'      => 'easeInBack',
                        'rotationY' => 90
                    )
                )
            )
        ));

        array_push($animations, array(
            'id'           => 1011,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Stand up'),
                'data' => array(
                    'transformOrigin' => '100|*|100|*|0',
                    'animation'       => array(
                        'ease'      => 'easeInBack',
                        'opacity'   => 0,
                        'rotationZ' => 90
                    )
                )
            )
        ));

        array_push($animations, array(
            'id'           => 1012,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Rotate down'),
                'data' => array(
                    'transformOrigin' => '50|*|0|*|0',
                    'animation'       => array(
                        'ease'      => 'easeInBack',
                        'rotationX' => 90
                    )
                )
            )
        ));

        array_push($animations, array(
            'id'           => 1013,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Elastic'),
                'data' => array(
                    'transformOrigin' => '50|*|50|*|0',
                    'animation'       => array(
                        'ease'     => 'easeOutElastic',
                        'y'        => 30,
                        'duration' => 1,
                        'opacity'  => 0
                    )
                )
            )
        ));

        array_push($animations, array(
            'id'           => 1014,
            'referencekey' => 1000,
            'value'        => array(
                'name' => n2_('Random elastic'),
                'data' => array(
                    'transformOrigin' => '50|*|50|*|0',
                    'animation'       => array(
                        'ease'     => 'easeOutElastic',
                        'y'        => -30,
                        'duration' => 1,
                        'sort'     => 'random',
                        'opacity'  => 0
                    )
                )
            )
        ));

        array_push($sets, array(
            'id'           => 1900,
            'referencekey' => '',
            'value'        => n2_('My text animations')
        ));
    }

    public function backgroundAnimationStorage(&$sets, &$animations) {

        array_push($sets, array(
            'id'           => 1000,
            'referencekey' => '',
            'value'        => n2_('Default')
        ));

        array_push($animations, array(
            "id"           => 1402,
            'referencekey' => 1000,
            "value"        => array(
                'name' => n2_('Scale to left'),
                'data' => array(
                    'type'   => 'Flat',
                    'tiles'  => array(
                        'crop'     => true,
                        'delay'    => 0,
                        'sequence' => 'ForwardDiagonal'
                    ),
                    'main'   => array(
                        'type'     => 'both',
                        'duration' => 1,
                        'current'  => array(
                            'ease'  => 'easeOutCubic',
                            'scale' => 0.7
                        ),
                        'next'     => array(
                            'ease' => 'easeOutCubic',
                            'xP'   => 100
                        )
                    ),
                    'invert' => array(
                        'zIndex'  => 2,
                        'current' => array(
                            'xP'    => 100,
                            'scale' => 1
                        ),
                        'next'    => array(
                            'scale' => 0.7,
                            'xP'    => 0
                        )
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1012,
            'referencekey' => 1000,
            "value"        => array(
                'name' => n2_('Zoom'),
                'data' => array(
                    'type'   => 'Flat',
                    'tiles'  => array(
                        'crop'     => false,
                        'delay'    => 0,
                        'sequence' => 'ForwardDiagonal'
                    ),
                    'main'   => array(
                        'type'     => 'both',
                        'duration' => .75,
                        'current'  => array(
                            'ease'    => 'easeOutCubic',
                            'scale'   => 0.5,
                            'opacity' => 0
                        ),
                        'next'     => array(
                            'ease'    => 'easeOutCubic',
                            'opacity' => 0,
                            'scale'   => 1.5
                        )
                    ),
                    'invert' => array(
                        'current' => array(
                            'scale' => 1.5
                        ),
                        'next'    => array(
                            'scale' => 0.5
                        )
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1025,
            'referencekey' => 1000,
            "value"        => array(
                'name' => n2_('Zoom out'),
                'data' => array(
                    'type'   => 'Flat',
                    'tiles'  => array(
                        'crop'     => false,
                        'delay'    => 0,
                        'sequence' => 'ForwardDiagonal'
                    ),
                    'main'   => array(
                        'type'     => 'both',
                        'duration' => .75,
                        'current'  => array(
                            'ease'    => 'easeOutCubic',
                            'scale'   => 1.5,
                            'opacity' => 0
                        ),
                        'next'     => array(
                            'ease'    => 'easeOutCubic',
                            'opacity' => 0,
                            'scale'   => 0.5
                        )
                    ),
                    'invert' => array(
                        'current' => array(
                            'scale' => 0.5
                        ),
                        'next'    => array(
                            'scale' => 1.5
                        )
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1013,
            'referencekey' => 1000,
            "value"        => array(
                'name' => n2_('Fade'),
                'data' => array(
                    'type'  => 'Flat',
                    'tiles' => array(
                        'delay'    => 0,
                        'sequence' => 'ForwardDiagonal'
                    ),
                    'main'  => array(
                        'type'     => 'both',
                        'duration' => 1,
                        'zIndex'   => 2,
                        'current'  => array(
                            'ease'    => 'easeOutCubic',
                            'opacity' => 0
                        )
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1014,
            'referencekey' => 1000,
            "value"        => array(
                'name' => n2_('Curtain to left'),
                'data' => array(
                    'type'        => 'Flat',
                    'rows'        => 1,
                    'columns'     => 25,
                    'tiles'       => array(
                        'delay'    => .03,
                        'sequence' => 'BackwardCol'
                    ),
                    'main'        => array(
                        'type'     => 'next',
                        'duration' => .35,
                        'next'     => array(
                            'ease'    => 'easeInQuart',
                            'opacity' => "0",
                            'xP'      => -100
                        )
                    ),
                    'invert'      => array(
                        'next' => array(
                            'xP' => 100
                        )
                    ),
                    'invertTiles' => array(
                        'sequence' => 'ForwardCol'
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1024,
            'referencekey' => 1000,
            "value"        => array(
                'name' => n2_('Puzzle'),
                'data' => array(
                    'type'    => 'Flat',
                    'rows'    => 5,
                    'columns' => 7,
                    'tiles'   => array(
                        'delay'    => 1,
                        'sequence' => 'Random'
                    ),
                    'main'    => array(
                        'type'     => 'next',
                        'duration' => 0.8,
                        'next'     => array(
                            'ease'    => 'easeInQuart',
                            'opacity' => 0
                        )
                    )
                )
            )
        ));

        array_push($sets, array(
            'id'           => 1100,
            'referencekey' => '',
            'value'        => n2_('Vertical')
        ));

        array_push($animations, array(
            "id"           => 1404,
            'referencekey' => 1100,
            "value"        => array(
                'name' => n2_('Scale to top'),
                'data' => array(
                    'type'   => 'Flat',
                    'tiles'  => array(
                        'crop'     => true,
                        'delay'    => 0,
                        'sequence' => 'ForwardDiagonal'
                    ),
                    'main'   => array(
                        'type'     => 'both',
                        'duration' => 1,
                        'current'  => array(
                            'ease'  => 'easeOutCubic',
                            'scale' => 0.7
                        ),
                        'next'     => array(
                            'ease' => 'easeOutCubic',
                            'yP'   => 100
                        )
                    ),
                    'invert' => array(
                        'zIndex'  => 2,
                        'current' => array(
                            'scale' => 1,
                            'yP'    => 100
                        ),
                        'next'    => array(
                            'scale' => 0.7,
                            'yP'    => 0
                        )
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1403,
            'referencekey' => 1100,
            "value"        => array(
                'name' => n2_('Scale to bottom'),
                'data' => array(
                    'type'   => 'Flat',
                    'tiles'  => array(
                        'crop'     => true,
                        'delay'    => 0,
                        'sequence' => 'ForwardDiagonal'
                    ),
                    'main'   => array(
                        'type'     => 'both',
                        'duration' => 1,
                        'current'  => array(
                            'ease'  => 'easeOutCubic',
                            'scale' => 0.7
                        ),
                        'next'     => array(
                            'ease' => 'easeOutCubic',
                            'yP'   => -100
                        )
                    ),
                    'invert' => array(
                        'zIndex'  => 2,
                        'current' => array(
                            'scale' => 1,
                            'yP'    => -100
                        ),
                        'next'    => array(
                            'scale' => 0.7,
                            'yP'    => 0
                        )
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1016,
            'referencekey' => 1100,
            "value"        => array(
                'name' => n2_('Curtain to bottom'),
                'data' => array(
                    'type'        => 'Flat',
                    'rows'        => 25,
                    'columns'     => 1,
                    'tiles'       => array(
                        'delay'    => .03,
                        'sequence' => 'ForwardRow'
                    ),
                    'main'        => array(
                        'type'     => 'next',
                        'duration' => .35,
                        'next'     => array(
                            'ease'    => 'easeInQuart',
                            'opacity' => "0",
                            'yP'      => 100
                        )
                    ),
                    'invert'      => array(
                        'next' => array(
                            'yP' => -100
                        )
                    ),
                    'invertTiles' => array(
                        'sequence' => 'BackwardRow'
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1017,
            'referencekey' => 1100,
            "value"        => array(
                'name' => n2_('Curtain to top'),
                'data' => array(
                    'type'        => 'Flat',
                    'rows'        => 25,
                    'columns'     => 1,
                    'tiles'       => array(
                        'delay'    => .03,
                        'sequence' => 'BackwardRow'
                    ),
                    'main'        => array(
                        'type'     => 'next',
                        'duration' => .35,
                        'next'     => array(
                            'ease'    => 'easeInQuart',
                            'opacity' => "0",
                            'yP'      => -100
                        )
                    ),
                    'invert'      => array(
                        'next' => array(
                            'yP' => 100
                        )
                    ),
                    'invertTiles' => array(
                        'sequence' => 'ForwardRow'
                    )
                )
            )
        ));

        array_push($sets, array(
            'id'           => 1200,
            'referencekey' => '',
            'value'        => 'RTL'
        ));

        array_push($animations, array(
            "id"           => 1401,
            'referencekey' => 1200,
            "value"        => array(
                'name' => n2_('Scale to right'),
                'data' => array(
                    'type'   => 'Flat',
                    'tiles'  => array(
                        'crop'     => true,
                        'delay'    => 0,
                        'sequence' => 'ForwardDiagonal'
                    ),
                    'main'   => array(
                        'type'     => 'both',
                        'duration' => 1,
                        'current'  => array(
                            'ease'  => 'easeOutCubic',
                            'scale' => 0.7
                        ),
                        'next'     => array(
                            'ease' => 'easeOutCubic',
                            'xP'   => -100
                        )
                    ),
                    'invert' => array(
                        'zIndex'  => 2,
                        'current' => array(
                            'xP'    => -100,
                            'scale' => 1
                        ),
                        'next'    => array(
                            'scale' => 0.7,
                            'xP'    => 0
                        )
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1015,
            'referencekey' => 1200,
            "value"        => array(
                'name' => n2_('Curtain to right'),
                'data' => array(
                    'type'        => 'Flat',
                    'rows'        => 1,
                    'columns'     => 25,
                    'tiles'       => array(
                        'delay'    => .03,
                        'sequence' => 'ForwardCol'
                    ),
                    'main'        => array(
                        'type'     => 'next',
                        'duration' => .35,
                        'next'     => array(
                            'ease'    => 'easeInQuart',
                            'opacity' => "0",
                            'xP'      => 100
                        )
                    ),
                    'invert'      => array(
                        'next' => array(
                            'xP' => -100
                        )
                    ),
                    'invertTiles' => array(
                        'sequence' => 'BackwardCol'
                    )
                )
            )
        ));

    }

    public function postBackgroundAnimationStorage(&$sets, &$animations) {

        array_push($sets, array(
            'id'           => 1000,
            'referencekey' => '',
            'value'        => n2_('Default')
        ));

        array_push($animations, array(
            "id"           => 1001,
            'referencekey' => 1000,
            "value"        => array(
                'name' => n2_('Downscale'),
                'data' => array(
                    'duration' => 5,
                    'strength' => array('scale'),
                    'from'     => array(
                        'scale' => 1.5
                    ),
                    'to'       => array(
                        'scale' => 1.2
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1002,
            'referencekey' => 1000,
            "value"        => array(
                'name' => n2_('Downscale left'),
                'data' => array(
                    'duration' => 5,
                    'strength' => array(
                        'scale',
                        'x'
                    ),
                    'from'     => array(
                        'scale' => 1.5,
                        'x'     => 0

                    ),
                    'to'       => array(
                        'scale' => 1.2,
                        'x'     => -100
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1003,
            'referencekey' => 1000,
            "value"        => array(
                'name' => n2_('Downscale right'),
                'data' => array(
                    'duration' => 5,
                    'strength' => array(
                        'scale',
                        'x'
                    ),
                    'from'     => array(
                        'scale' => 1.5,
                        'x'     => 0

                    ),
                    'to'       => array(
                        'scale' => 1.2,
                        'x'     => 100
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1004,
            'referencekey' => 1000,
            "value"        => array(
                'name' => n2_('Downscale top'),
                'data' => array(
                    'duration' => 5,
                    'strength' => array(
                        'scale',
                        'y'
                    ),
                    'from'     => array(
                        'scale' => 1.5,
                        'y'     => 0

                    ),
                    'to'       => array(
                        'scale' => 1.2,
                        'y'     => -100
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1005,
            'referencekey' => 1000,
            "value"        => array(
                'name' => n2_('Downscale bottom'),
                'data' => array(
                    'duration' => 5,
                    'strength' => array(
                        'scale',
                        'y'
                    ),
                    'from'     => array(
                        'scale' => 1.5,
                        'y'     => 0

                    ),
                    'to'       => array(
                        'scale' => 1.2,
                        'y'     => 100
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1006,
            'referencekey' => 1000,
            "value"        => array(
                'name' => n2_('Upscale'),
                'data' => array(
                    'duration' => 5,
                    'strength' => array('scale'),
                    'from'     => array(
                        'scale' => 1.2
                    ),
                    'to'       => array(
                        'scale' => 1.5
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1007,
            'referencekey' => 1000,
            "value"        => array(
                'name' => n2_('Upscale left'),
                'data' => array(
                    'duration' => 5,
                    'strength' => array(
                        'scale',
                        'x'
                    ),
                    'from'     => array(
                        'scale' => 1.2,
                        'x'     => 0

                    ),
                    'to'       => array(
                        'scale' => 1.5,
                        'x'     => 100
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1008,
            'referencekey' => 1000,
            "value"        => array(
                'name' => n2_('Upscale right'),
                'data' => array(
                    'duration' => 5,
                    'strength' => array(
                        'scale',
                        'x'
                    ),
                    'from'     => array(
                        'scale' => 1.2,
                        'x'     => 0

                    ),
                    'to'       => array(
                        'scale' => 1.5,
                        'x'     => -100
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1009,
            'referencekey' => 1000,
            "value"        => array(
                'name' => n2_('Upscale top'),
                'data' => array(
                    'duration' => 5,
                    'strength' => array(
                        'scale',
                        'y'
                    ),
                    'from'     => array(
                        'scale' => 1.2,
                        'y'     => 0

                    ),
                    'to'       => array(
                        'scale' => 1.5,
                        'y'     => 100
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1010,
            'referencekey' => 1000,
            "value"        => array(
                'name' => n2_('Upscale bottom'),
                'data' => array(
                    'duration' => 5,
                    'strength' => array(
                        'scale',
                        'y'
                    ),
                    'from'     => array(
                        'scale' => 1.2,
                        'y'     => 0

                    ),
                    'to'       => array(
                        'scale' => 1.5,
                        'y'     => -100
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1011,
            'referencekey' => 1000,
            "value"        => array(
                'name' => n2_('To left'),
                'data' => array(
                    'duration' => 5,
                    'strength' => array('x'),
                    'from'     => array(
                        'scale' => 1.5,
                        'x'     => 0
                    ),
                    'to'       => array(
                        'scale' => 1.5,
                        'x'     => 100
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1012,
            'referencekey' => 1000,
            "value"        => array(
                'name' => n2_('To right'),
                'data' => array(
                    'duration' => 5,
                    'strength' => array('x'),
                    'from'     => array(
                        'scale' => 1.5,
                        'x'     => 0
                    ),
                    'to'       => array(
                        'scale' => 1.5,
                        'x'     => -100
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1013,
            'referencekey' => 1000,
            "value"        => array(
                'name' => n2_('To top'),
                'data' => array(
                    'duration' => 5,
                    'strength' => array('y'),
                    'from'     => array(
                        'scale' => 1.5,
                        'y'     => 0
                    ),
                    'to'       => array(
                        'scale' => 1.5,
                        'y'     => 100
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1014,
            'referencekey' => 1000,
            "value"        => array(
                'name' => n2_('To bottom'),
                'data' => array(
                    'duration' => 5,
                    'strength' => array('y'),
                    'from'     => array(
                        'scale' => 1.5,
                        'y'     => 0
                    ),
                    'to'       => array(
                        'scale' => 1.5,
                        'y'     => -100
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1015,
            'referencekey' => 1000,
            "value"        => array(
                'name' => n2_('To top left'),
                'data' => array(
                    'duration' => 5,
                    'strength' => array(
                        'x',
                        'y'
                    ),
                    'from'     => array(
                        'scale' => 1.5,
                        'x'     => 0,
                        'y'     => 0
                    ),
                    'to'       => array(
                        'scale' => 1.5,
                        'x'     => 100,
                        'y'     => 100
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1016,
            'referencekey' => 1000,
            "value"        => array(
                'name' => n2_('To top right'),
                'data' => array(
                    'duration' => 5,
                    'strength' => array(
                        'x',
                        'y'
                    ),
                    'from'     => array(
                        'scale' => 1.5,
                        'x'     => 0,
                        'y'     => 0
                    ),
                    'to'       => array(
                        'scale' => 1.5,
                        'x'     => -100,
                        'y'     => 100
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1017,
            'referencekey' => 1000,
            "value"        => array(
                'name' => n2_('To bottom left'),
                'data' => array(
                    'duration' => 5,
                    'strength' => array(
                        'x',
                        'y'
                    ),
                    'from'     => array(
                        'scale' => 1.5,
                        'x'     => 0,
                        'y'     => 0
                    ),
                    'to'       => array(
                        'scale' => 1.5,
                        'x'     => 100,
                        'y'     => -100
                    )
                )
            )
        ));

        array_push($animations, array(
            "id"           => 1018,
            'referencekey' => 1000,
            "value"        => array(
                'name' => n2_('To bottom right'),
                'data' => array(
                    'duration' => 5,
                    'strength' => array(
                        'x',
                        'y'
                    ),
                    'from'     => array(
                        'scale' => 1.5,
                        'x'     => 0,
                        'y'     => 0
                    ),
                    'to'       => array(
                        'scale' => 1.5,
                        'x'     => -100,
                        'y'     => -100
                    )
                )
            )
        ));
    }

    public function layoutStorage(&$sets, &$layouts) {

        array_push($sets, array(
            'id'           => 1900,
            'referencekey' => '',
            'value'        => n2_('My layouts')
        ));
    }

    public function itemheadingStorage(&$styles) {

        array_push($styles, array(
            'id'    => 1000,
            'value' => 'eyJuYW1lIjoiUmFsZXdheSBCbHVlIENhcHRpb24iLCJkYXRhIjp7ImZvbnQiOnsiZm9udCI6ImV5SnVZVzFsSWpvaVUzUmhkR2xqSWl3aVpHRjBZU0k2VzNzaVpYaDBjbUVpT2lJaUxDSmpiMnh2Y2lJNkltWm1abVptWm1abUlpd2ljMmw2WlNJNklqTTJmSHh3ZUNJc0luUnphR0ZrYjNjaU9pSXdmQ3A4TUh3cWZEQjhLbnd3TURBd01EQm1aaUlzSW1GbWIyNTBJam9pVW1Gc1pYZGhlU3dnUVhKcFlXd2lMQ0pzYVc1bGFHVnBaMmgwSWpvaU1TNDFJaXdpWW05c1pDSTZNQ3dpYVhSaGJHbGpJam93TENKMWJtUmxjbXhwYm1VaU9qQXNJbUZzYVdkdUlqb2lZMlZ1ZEdWeUlpd2liR1YwZEdWeWMzQmhZMmx1WnlJNkltNXZjbTFoYkNJc0luZHZjbVJ6Y0dGamFXNW5Jam9pYm05eWJXRnNJaXdpZEdWNGRIUnlZVzV6Wm05eWJTSTZJbTV2Ym1VaWZTeDdJbVY0ZEhKaElqb2lJbjFkZlE9PSJ9LCJzdHlsZSI6eyJzdHlsZSI6ImV5SnVZVzFsSWpvaVUzUmhkR2xqSWl3aVpHRjBZU0k2VzNzaVpYaDBjbUVpT2lJaUxDSmlZV05yWjNKdmRXNWtZMjlzYjNJaU9pSXdNV0ZrWkRObVppSXNJbkJoWkdScGJtY2lPaUkyZkNwOE1UVjhLbncyZkNwOE1UVjhLbnh3ZUNJc0ltSnZlSE5vWVdSdmR5STZJakI4S253d2ZDcDhNSHdxZkRCOEtud3dNREF3TURCbVppSXNJbUp2Y21SbGNpSTZJakI4S254emIyeHBaSHdxZkdabVptWm1aakF3SWl3aVltOXlaR1Z5Y21Ga2FYVnpJam9pTUNKOUxIc2laWGgwY21FaU9pSWlmVjE5In19fQ=='
        ));

        array_push($styles, array(
            'id'    => 1001,
            'value' => 'eyJuYW1lIjoiUmFsZXdheSBXaGl0ZSBDYXB0aW9uIiwiZGF0YSI6eyJmb250Ijp7ImZvbnQiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0pqYjJ4dmNpSTZJak16TXpNek0yWm1JaXdpYzJsNlpTSTZJak0yZkh4d2VDSXNJblJ6YUdGa2IzY2lPaUl3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltRm1iMjUwSWpvaVVtRnNaWGRoZVN3Z1FYSnBZV3dpTENKc2FXNWxhR1ZwWjJoMElqb2lNUzQxSWl3aVltOXNaQ0k2TUN3aWFYUmhiR2xqSWpvd0xDSjFibVJsY214cGJtVWlPakFzSW1Gc2FXZHVJam9pWTJWdWRHVnlJaXdpYkdWMGRHVnljM0JoWTJsdVp5STZJbTV2Y20xaGJDSXNJbmR2Y21SemNHRmphVzVuSWpvaWJtOXliV0ZzSWl3aWRHVjRkSFJ5WVc1elptOXliU0k2SW01dmJtVWlmU3g3SW1WNGRISmhJam9pSW4xZGZRPT0ifSwic3R5bGUiOnsic3R5bGUiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0ppWVdOclozSnZkVzVrWTI5c2IzSWlPaUptWm1abVptWmpZeUlzSW5CaFpHUnBibWNpT2lJMmZDcDhNVFY4S253MmZDcDhNVFY4S254d2VDSXNJbUp2ZUhOb1lXUnZkeUk2SWpCOEtud3dmQ3A4TUh3cWZEQjhLbnd3TURBd01EQm1aaUlzSW1KdmNtUmxjaUk2SWpCOEtueHpiMnhwWkh3cWZHWm1abVptWmpBd0lpd2lZbTl5WkdWeWNtRmthWFZ6SWpvaU1DSjlMSHNpWlhoMGNtRWlPaUlpZlYxOSJ9fX0='
        ));
        array_push($styles, array(
            'id'    => 1002,
            'value' => 'eyJuYW1lIjoiUmFsZXdheSBHcmVlbiBDYXB0aW9uIiwiZGF0YSI6eyJmb250Ijp7ImZvbnQiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0pqYjJ4dmNpSTZJbVptWm1abVptWm1JaXdpYzJsNlpTSTZJak0yZkh4d2VDSXNJblJ6YUdGa2IzY2lPaUl3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltRm1iMjUwSWpvaVVtRnNaWGRoZVN3Z1FYSnBZV3dpTENKc2FXNWxhR1ZwWjJoMElqb2lNUzQxSWl3aVltOXNaQ0k2TUN3aWFYUmhiR2xqSWpvd0xDSjFibVJsY214cGJtVWlPakFzSW1Gc2FXZHVJam9pWTJWdWRHVnlJaXdpYkdWMGRHVnljM0JoWTJsdVp5STZJbTV2Y20xaGJDSXNJbmR2Y21SemNHRmphVzVuSWpvaWJtOXliV0ZzSWl3aWRHVjRkSFJ5WVc1elptOXliU0k2SW01dmJtVWlmU3g3SW1WNGRISmhJam9pSW4xZGZRPT0ifSwic3R5bGUiOnsic3R5bGUiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0ppWVdOclozSnZkVzVrWTI5c2IzSWlPaUkxWTJKaE0yTm1aaUlzSW5CaFpHUnBibWNpT2lJMmZDcDhNVFY4S253MmZDcDhNVFY4S254d2VDSXNJbUp2ZUhOb1lXUnZkeUk2SWpCOEtud3dmQ3A4TUh3cWZEQjhLbnd3TURBd01EQm1aaUlzSW1KdmNtUmxjaUk2SWpCOEtueHpiMnhwWkh3cWZHWm1abVptWmpBd0lpd2lZbTl5WkdWeWNtRmthWFZ6SWpvaU1DSjlMSHNpWlhoMGNtRWlPaUlpZlYxOSJ9fX0='
        ));
        array_push($styles, array(
            'id'    => 1003,
            'value' => 'eyJuYW1lIjoiUmFsZXdheSBTdWJoZWFkaW5nIExpZ2h0IiwiZGF0YSI6eyJmb250Ijp7ImZvbnQiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0pqYjJ4dmNpSTZJbVptWm1abVpqZ3dJaXdpYzJsNlpTSTZJakkwZkh4d2VDSXNJblJ6YUdGa2IzY2lPaUl3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltRm1iMjUwSWpvaVVtRnNaWGRoZVN3Z1FYSnBZV3dpTENKc2FXNWxhR1ZwWjJoMElqb2lNUzQxSWl3aVltOXNaQ0k2TUN3aWFYUmhiR2xqSWpvd0xDSjFibVJsY214cGJtVWlPakFzSW1Gc2FXZHVJam9pWTJWdWRHVnlJaXdpYkdWMGRHVnljM0JoWTJsdVp5STZJbTV2Y20xaGJDSXNJbmR2Y21SemNHRmphVzVuSWpvaWJtOXliV0ZzSWl3aWRHVjRkSFJ5WVc1elptOXliU0k2SW01dmJtVWlmU3g3SW1WNGRISmhJam9pSW4xZGZRPT0ifSwic3R5bGUiOnsic3R5bGUiOiIifX19'
        ));
        array_push($styles, array(
            'id'    => 1004,
            'value' => 'eyJuYW1lIjoiUmFsZXdheSBUaXRsZSBEYXJrIiwiZGF0YSI6eyJmb250Ijp7ImZvbnQiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0pqYjJ4dmNpSTZJak16TXpNek0yWm1JaXdpYzJsNlpTSTZJakUxZkh4d2VDSXNJblJ6YUdGa2IzY2lPaUl3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltRm1iMjUwSWpvaVVtRnNaWGRoZVN3Z1FYSnBZV3dpTENKc2FXNWxhR1ZwWjJoMElqb2lNUzQxSWl3aVltOXNaQ0k2TVN3aWFYUmhiR2xqSWpvd0xDSjFibVJsY214cGJtVWlPakFzSW1Gc2FXZHVJam9pWTJWdWRHVnlJaXdpYkdWMGRHVnljM0JoWTJsdVp5STZJbTV2Y20xaGJDSXNJbmR2Y21SemNHRmphVzVuSWpvaWJtOXliV0ZzSWl3aWRHVjRkSFJ5WVc1elptOXliU0k2SW5Wd2NHVnlZMkZ6WlNKOUxIc2laWGgwY21FaU9pSWlmVjE5In0sInN0eWxlIjp7InN0eWxlIjoiIn19fQ=='
        ));
        array_push($styles, array(
            'id'    => 1005,
            'value' => 'eyJuYW1lIjoiUmFsZXdheSBUaXRsZSBMaWdodCIsImRhdGEiOnsiZm9udCI6eyJmb250IjoiZXlKdVlXMWxJam9pVTNSaGRHbGpJaXdpWkdGMFlTSTZXM3NpWlhoMGNtRWlPaUlpTENKamIyeHZjaUk2SW1abVptWm1abVptSWl3aWMybDZaU0k2SWpFMWZIeHdlQ0lzSW5SemFHRmtiM2NpT2lJd2ZDcDhNSHdxZkRCOEtud3dNREF3TURCbVppSXNJbUZtYjI1MElqb2lVbUZzWlhkaGVTd2dRWEpwWVd3aUxDSnNhVzVsYUdWcFoyaDBJam9pTVM0MUlpd2lZbTlzWkNJNk1Td2lhWFJoYkdsaklqb3dMQ0oxYm1SbGNteHBibVVpT2pBc0ltRnNhV2R1SWpvaVkyVnVkR1Z5SWl3aWJHVjBkR1Z5YzNCaFkybHVaeUk2SW01dmNtMWhiQ0lzSW5kdmNtUnpjR0ZqYVc1bklqb2libTl5YldGc0lpd2lkR1Y0ZEhSeVlXNXpabTl5YlNJNkluVndjR1Z5WTJGelpTSjlMSHNpWlhoMGNtRWlPaUlpZlYxOSJ9LCJzdHlsZSI6eyJzdHlsZSI6IiJ9fX0='
        ));
        array_push($styles, array(
            'id'    => 1006,
            'value' => 'eyJuYW1lIjoiUmFsZXdheSBCbGFjayBDYXB0aW9uIiwiZGF0YSI6eyJmb250Ijp7ImZvbnQiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0pqYjJ4dmNpSTZJbVptWm1abVptWm1JaXdpYzJsNlpTSTZJak0yZkh4d2VDSXNJblJ6YUdGa2IzY2lPaUl3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltRm1iMjUwSWpvaVVtRnNaWGRoZVN3Z1FYSnBZV3dpTENKc2FXNWxhR1ZwWjJoMElqb2lNUzQxSWl3aVltOXNaQ0k2TUN3aWFYUmhiR2xqSWpvd0xDSjFibVJsY214cGJtVWlPakFzSW1Gc2FXZHVJam9pWTJWdWRHVnlJaXdpYkdWMGRHVnljM0JoWTJsdVp5STZJbTV2Y20xaGJDSXNJbmR2Y21SemNHRmphVzVuSWpvaWJtOXliV0ZzSWl3aWRHVjRkSFJ5WVc1elptOXliU0k2SW01dmJtVWlmU3g3SW1WNGRISmhJam9pSW4xZGZRPT0ifSwic3R5bGUiOnsic3R5bGUiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0ppWVdOclozSnZkVzVrWTI5c2IzSWlPaUl3TURBd01EQmpZeUlzSW5CaFpHUnBibWNpT2lJMmZDcDhNVFY4S253MmZDcDhNVFY4S254d2VDSXNJbUp2ZUhOb1lXUnZkeUk2SWpCOEtud3dmQ3A4TUh3cWZEQjhLbnd3TURBd01EQm1aaUlzSW1KdmNtUmxjaUk2SWpCOEtueHpiMnhwWkh3cWZHWm1abVptWmpBd0lpd2lZbTl5WkdWeWNtRmthWFZ6SWpvaU1DSjlMSHNpWlhoMGNtRWlPaUlpZlYxOSJ9fX0='
        ));
        array_push($styles, array(
            'id'    => 1007,
            'value' => 'eyJuYW1lIjoiUmFsZXdheSBHaG9zdCBIZWFkaW5nIExpZ2h0IiwiZGF0YSI6eyJmb250Ijp7ImZvbnQiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0pqYjJ4dmNpSTZJbVptWm1abVptWm1JaXdpYzJsNlpTSTZJak15Zkh4d2VDSXNJblJ6YUdGa2IzY2lPaUl3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltRm1iMjUwSWpvaVVtRnNaWGRoZVN3Z1FYSnBZV3dpTENKc2FXNWxhR1ZwWjJoMElqb2lNUzQxSWl3aVltOXNaQ0k2TVN3aWFYUmhiR2xqSWpvd0xDSjFibVJsY214cGJtVWlPakFzSW1Gc2FXZHVJam9pWTJWdWRHVnlJaXdpYkdWMGRHVnljM0JoWTJsdVp5STZJbTV2Y20xaGJDSXNJbmR2Y21SemNHRmphVzVuSWpvaWJtOXliV0ZzSWl3aWRHVjRkSFJ5WVc1elptOXliU0k2SW01dmJtVWlmU3g3SW1WNGRISmhJam9pSW4xZGZRPT0ifSwic3R5bGUiOnsic3R5bGUiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0ppWVdOclozSnZkVzVrWTI5c2IzSWlPaUptWm1abVptWXdNQ0lzSW5CaFpHUnBibWNpT2lJMmZDcDhNVFY4S253MmZDcDhNVFY4S254d2VDSXNJbUp2ZUhOb1lXUnZkeUk2SWpCOEtud3dmQ3A4TUh3cWZEQjhLbnd3TURBd01EQm1aaUlzSW1KdmNtUmxjaUk2SWpOOEtueHpiMnhwWkh3cWZHWm1abVptWm1abUlpd2lZbTl5WkdWeWNtRmthWFZ6SWpvaU1DSjlMSHNpWlhoMGNtRWlPaUlpZlYxOSJ9fX0='
        ));
        array_push($styles, array(
            'id'    => 1008,
            'value' => 'eyJuYW1lIjoiUmFsZXdheSBHaG9zdCBIZWFkaW5nIERhcmsiLCJkYXRhIjp7ImZvbnQiOnsiZm9udCI6ImV5SnVZVzFsSWpvaVUzUmhkR2xqSWl3aVpHRjBZU0k2VzNzaVpYaDBjbUVpT2lJaUxDSmpiMnh2Y2lJNklqTXpNek16TTJabUlpd2ljMmw2WlNJNklqTXlmSHh3ZUNJc0luUnphR0ZrYjNjaU9pSXdmQ3A4TUh3cWZEQjhLbnd3TURBd01EQm1aaUlzSW1GbWIyNTBJam9pVW1Gc1pYZGhlU3dnUVhKcFlXd2lMQ0pzYVc1bGFHVnBaMmgwSWpvaU1TNDFJaXdpWW05c1pDSTZNU3dpYVhSaGJHbGpJam93TENKMWJtUmxjbXhwYm1VaU9qQXNJbUZzYVdkdUlqb2lZMlZ1ZEdWeUlpd2liR1YwZEdWeWMzQmhZMmx1WnlJNkltNXZjbTFoYkNJc0luZHZjbVJ6Y0dGamFXNW5Jam9pYm05eWJXRnNJaXdpZEdWNGRIUnlZVzV6Wm05eWJTSTZJbTV2Ym1VaWZTeDdJbVY0ZEhKaElqb2lJbjFkZlE9PSJ9LCJzdHlsZSI6eyJzdHlsZSI6ImV5SnVZVzFsSWpvaVUzUmhkR2xqSWl3aVpHRjBZU0k2VzNzaVpYaDBjbUVpT2lJaUxDSmlZV05yWjNKdmRXNWtZMjlzYjNJaU9pSm1abVptWm1Zd01DSXNJbkJoWkdScGJtY2lPaUkyZkNwOE1UVjhLbncyZkNwOE1UVjhLbnh3ZUNJc0ltSnZlSE5vWVdSdmR5STZJakI4S253d2ZDcDhNSHdxZkRCOEtud3dNREF3TURCbVppSXNJbUp2Y21SbGNpSTZJak44S254emIyeHBaSHdxZkRNek16TXpNMlptSWl3aVltOXlaR1Z5Y21Ga2FYVnpJam9pTUNKOUxIc2laWGgwY21FaU9pSWlmVjE5In19fQ=='
        ));
        array_push($styles, array(
            'id'    => 1009,
            'value' => 'eyJuYW1lIjoiUmFsZXdheSBIZWFkaW5nIExpZ2h0IiwiZGF0YSI6eyJmb250Ijp7ImZvbnQiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0pqYjJ4dmNpSTZJbVptWm1abVptWm1JaXdpYzJsNlpTSTZJalE0Zkh4d2VDSXNJblJ6YUdGa2IzY2lPaUl3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltRm1iMjUwSWpvaVVtRnNaWGRoZVN3Z1FYSnBZV3dpTENKc2FXNWxhR1ZwWjJoMElqb2lNUzQxSWl3aVltOXNaQ0k2TUN3aWFYUmhiR2xqSWpvd0xDSjFibVJsY214cGJtVWlPakFzSW1Gc2FXZHVJam9pWTJWdWRHVnlJaXdpYkdWMGRHVnljM0JoWTJsdVp5STZJbTV2Y20xaGJDSXNJbmR2Y21SemNHRmphVzVuSWpvaWJtOXliV0ZzSWl3aWRHVjRkSFJ5WVc1elptOXliU0k2SW01dmJtVWlmU3g3SW1WNGRISmhJam9pSWl3aVkyOXNiM0lpT2lKbVptWm1abVptWmlKOVhYMD0ifSwic3R5bGUiOnsic3R5bGUiOiIifX19'
        ));
        array_push($styles, array(
            'id'    => 1010,
            'value' => 'eyJuYW1lIjoiUmFsZXdheSBTdWJoZWFkaW5nIERhcmsiLCJkYXRhIjp7ImZvbnQiOnsiZm9udCI6ImV5SnVZVzFsSWpvaVUzUmhkR2xqSWl3aVpHRjBZU0k2VzNzaVpYaDBjbUVpT2lJaUxDSmpiMnh2Y2lJNklqZGtOMlEzWkdabUlpd2ljMmw2WlNJNklqSTBmSHh3ZUNJc0luUnphR0ZrYjNjaU9pSXdmQ3A4TUh3cWZEQjhLbnd3TURBd01EQm1aaUlzSW1GbWIyNTBJam9pVW1Gc1pYZGhlU3dnUVhKcFlXd2lMQ0pzYVc1bGFHVnBaMmgwSWpvaU1TNDFJaXdpWW05c1pDSTZNQ3dpYVhSaGJHbGpJam93TENKMWJtUmxjbXhwYm1VaU9qQXNJbUZzYVdkdUlqb2lZMlZ1ZEdWeUlpd2liR1YwZEdWeWMzQmhZMmx1WnlJNkltNXZjbTFoYkNJc0luZHZjbVJ6Y0dGamFXNW5Jam9pYm05eWJXRnNJaXdpZEdWNGRIUnlZVzV6Wm05eWJTSTZJbTV2Ym1VaWZTeDdJbVY0ZEhKaElqb2lJaXdpWTI5c2IzSWlPaUkzWkRka04yUm1aaUo5WFgwPSJ9LCJzdHlsZSI6eyJzdHlsZSI6IiJ9fX0='
        ));
        array_push($styles, array(
            'id'    => 1011,
            'value' => 'eyJuYW1lIjoiUmFsZXdheSBIZWFkaW5nIERhcmsiLCJkYXRhIjp7ImZvbnQiOnsiZm9udCI6ImV5SnVZVzFsSWpvaVUzUmhkR2xqSWl3aVpHRjBZU0k2VzNzaVpYaDBjbUVpT2lJaUxDSmpiMnh2Y2lJNklqTXpNek16TTJabUlpd2ljMmw2WlNJNklqUTRmSHh3ZUNJc0luUnphR0ZrYjNjaU9pSXdmQ3A4TUh3cWZEQjhLbnd3TURBd01EQm1aaUlzSW1GbWIyNTBJam9pVW1Gc1pYZGhlU3dnUVhKcFlXd2lMQ0pzYVc1bGFHVnBaMmgwSWpvaU1TNDFJaXdpWW05c1pDSTZNQ3dpYVhSaGJHbGpJam93TENKMWJtUmxjbXhwYm1VaU9qQXNJbUZzYVdkdUlqb2lZMlZ1ZEdWeUlpd2liR1YwZEdWeWMzQmhZMmx1WnlJNkltNXZjbTFoYkNJc0luZHZjbVJ6Y0dGamFXNW5Jam9pYm05eWJXRnNJaXdpZEdWNGRIUnlZVzV6Wm05eWJTSTZJbTV2Ym1VaWZTeDdJbVY0ZEhKaElqb2lJaXdpWTI5c2IzSWlPaUl6TXpNek16Tm1aaUo5WFgwPSJ9LCJzdHlsZSI6eyJzdHlsZSI6IiJ9fX0='
        ));
        array_push($styles, array(
            'id'    => 1012,
            'value' => 'eyJuYW1lIjoiUmFsZXdheSBQdXJwbGUgQ2FwdGlvbiIsImRhdGEiOnsiZm9udCI6eyJmb250IjoiZXlKdVlXMWxJam9pVTNSaGRHbGpJaXdpWkdGMFlTSTZXM3NpWlhoMGNtRWlPaUlpTENKamIyeHZjaUk2SW1abVptWm1abVptSWl3aWMybDZaU0k2SWpNMmZIeHdlQ0lzSW5SemFHRmtiM2NpT2lJd2ZDcDhNSHdxZkRCOEtud3dNREF3TURCbVppSXNJbUZtYjI1MElqb2lVbUZzWlhkaGVTd2dRWEpwWVd3aUxDSnNhVzVsYUdWcFoyaDBJam9pTVM0MUlpd2lZbTlzWkNJNk1Dd2lhWFJoYkdsaklqb3dMQ0oxYm1SbGNteHBibVVpT2pBc0ltRnNhV2R1SWpvaVkyVnVkR1Z5SWl3aWJHVjBkR1Z5YzNCaFkybHVaeUk2SW01dmNtMWhiQ0lzSW5kdmNtUnpjR0ZqYVc1bklqb2libTl5YldGc0lpd2lkR1Y0ZEhSeVlXNXpabTl5YlNJNkltNXZibVVpZlN4N0ltVjRkSEpoSWpvaUluMWRmUT09In0sInN0eWxlIjp7InN0eWxlIjoiZXlKdVlXMWxJam9pVTNSaGRHbGpJaXdpWkdGMFlTSTZXM3NpWlhoMGNtRWlPaUlpTENKaVlXTnJaM0p2ZFc1a1kyOXNiM0lpT2lJNE56VTNZakptWmlJc0luQmhaR1JwYm1jaU9pSTJmQ3A4TVRWOEtudzJmQ3A4TVRWOEtueHdlQ0lzSW1KdmVITm9ZV1J2ZHlJNklqQjhLbnd3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltSnZjbVJsY2lJNklqQjhLbnh6YjJ4cFpId3FmR1ptWm1abVpqQXdJaXdpWW05eVpHVnljbUZrYVhWeklqb2lNQ0o5TEhzaVpYaDBjbUVpT2lJaWZWMTkifX19'
        ));
        array_push($styles, array(
            'id'    => 1013,
            'value' => 'eyJuYW1lIjoiUGxheWZhaXIgSGVhZGluZyBEYXJrIiwiZGF0YSI6eyJmb250Ijp7ImZvbnQiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0pqYjJ4dmNpSTZJak16TXpNek0yWm1JaXdpYzJsNlpTSTZJalE0Zkh4d2VDSXNJblJ6YUdGa2IzY2lPaUl3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltRm1iMjUwSWpvaUoxQnNZWGxtWVdseUlFUnBjM0JzWVhrbkxDQkJjbWxoYkNJc0lteHBibVZvWldsbmFIUWlPaUl4TGpVaUxDSmliMnhrSWpvd0xDSnBkR0ZzYVdNaU9qQXNJblZ1WkdWeWJHbHVaU0k2TUN3aVlXeHBaMjRpT2lKalpXNTBaWElpTENKc1pYUjBaWEp6Y0dGamFXNW5Jam9pYm05eWJXRnNJaXdpZDI5eVpITndZV05wYm1jaU9pSnViM0p0WVd3aUxDSjBaWGgwZEhKaGJuTm1iM0p0SWpvaWJtOXVaU0o5TEhzaVpYaDBjbUVpT2lJaWZWMTkifSwic3R5bGUiOnsic3R5bGUiOiIifX19'
        ));
        array_push($styles, array(
            'id'    => 1014,
            'value' => 'eyJuYW1lIjoiUGxheWZhaXIgSGVhZGluZyBMaWdodCIsImRhdGEiOnsiZm9udCI6eyJmb250IjoiZXlKdVlXMWxJam9pVTNSaGRHbGpJaXdpWkdGMFlTSTZXM3NpWlhoMGNtRWlPaUlpTENKamIyeHZjaUk2SW1abVptWm1abVptSWl3aWMybDZaU0k2SWpRNGZIeHdlQ0lzSW5SemFHRmtiM2NpT2lJd2ZDcDhNSHdxZkRCOEtud3dNREF3TURCbVppSXNJbUZtYjI1MElqb2lKMUJzWVhsbVlXbHlJRVJwYzNCc1lYa25MQ0JCY21saGJDSXNJbXhwYm1Wb1pXbG5hSFFpT2lJeExqVWlMQ0ppYjJ4a0lqb3dMQ0pwZEdGc2FXTWlPakFzSW5WdVpHVnliR2x1WlNJNk1Dd2lZV3hwWjI0aU9pSmpaVzUwWlhJaUxDSnNaWFIwWlhKemNHRmphVzVuSWpvaWJtOXliV0ZzSWl3aWQyOXlaSE53WVdOcGJtY2lPaUp1YjNKdFlXd2lMQ0owWlhoMGRISmhibk5tYjNKdElqb2libTl1WlNKOUxIc2laWGgwY21FaU9pSWlmVjE5In0sInN0eWxlIjp7InN0eWxlIjoiIn19fQ=='
        ));
        array_push($styles, array(
            'id'    => 1015,
            'value' => 'eyJuYW1lIjoiUGxheWZhaXIgU3ViaGVhZGluZyBEYXJrIiwiZGF0YSI6eyJmb250Ijp7ImZvbnQiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0pqYjJ4dmNpSTZJak16TXpNek0yWm1JaXdpYzJsNlpTSTZJakkwZkh4d2VDSXNJblJ6YUdGa2IzY2lPaUl3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltRm1iMjUwSWpvaUoxQnNZWGxtWVdseUlFUnBjM0JzWVhrbkxDQkJjbWxoYkNJc0lteHBibVZvWldsbmFIUWlPaUl4TGpVaUxDSmliMnhrSWpveExDSnBkR0ZzYVdNaU9qQXNJblZ1WkdWeWJHbHVaU0k2TUN3aVlXeHBaMjRpT2lKalpXNTBaWElpTENKc1pYUjBaWEp6Y0dGamFXNW5Jam9pYm05eWJXRnNJaXdpZDI5eVpITndZV05wYm1jaU9pSnViM0p0WVd3aUxDSjBaWGgwZEhKaGJuTm1iM0p0SWpvaWRYQndaWEpqWVhObEluMHNleUpsZUhSeVlTSTZJaUo5WFgwPSJ9LCJzdHlsZSI6eyJzdHlsZSI6IiJ9fX0='
        ));
        array_push($styles, array(
            'id'    => 1016,
            'value' => 'eyJuYW1lIjoiUGxheWZhaXIgU3ViaGVhZGluZyBMaWdodCIsImRhdGEiOnsiZm9udCI6eyJmb250IjoiZXlKdVlXMWxJam9pVTNSaGRHbGpJaXdpWkdGMFlTSTZXM3NpWlhoMGNtRWlPaUlpTENKamIyeHZjaUk2SW1abVptWm1abVptSWl3aWMybDZaU0k2SWpJMGZIeHdlQ0lzSW5SemFHRmtiM2NpT2lJd2ZDcDhNSHdxZkRCOEtud3dNREF3TURCbVppSXNJbUZtYjI1MElqb2lKMUJzWVhsbVlXbHlJRVJwYzNCc1lYa25MQ0JCY21saGJDSXNJbXhwYm1Wb1pXbG5hSFFpT2lJeExqVWlMQ0ppYjJ4a0lqb3hMQ0pwZEdGc2FXTWlPakFzSW5WdVpHVnliR2x1WlNJNk1Dd2lZV3hwWjI0aU9pSmpaVzUwWlhJaUxDSnNaWFIwWlhKemNHRmphVzVuSWpvaWJtOXliV0ZzSWl3aWQyOXlaSE53WVdOcGJtY2lPaUp1YjNKdFlXd2lMQ0owWlhoMGRISmhibk5tYjNKdElqb2lkWEJ3WlhKallYTmxJbjBzZXlKbGVIUnlZU0k2SWlKOVhYMD0ifSwic3R5bGUiOnsic3R5bGUiOiIifX19'
        ));
        array_push($styles, array(
            'id'    => 1017,
            'value' => 'eyJuYW1lIjoiUGxheWZhaXIgSHVnZSBEYXJrIiwiZGF0YSI6eyJmb250Ijp7ImZvbnQiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0pqYjJ4dmNpSTZJak16TXpNek0yWm1JaXdpYzJsNlpTSTZJamsyZkh4d2VDSXNJblJ6YUdGa2IzY2lPaUl3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltRm1iMjUwSWpvaUoxQnNZWGxtWVdseUlFUnBjM0JzWVhrbkxDQkJjbWxoYkNJc0lteHBibVZvWldsbmFIUWlPaUl4TGpVaUxDSmliMnhrSWpveExDSnBkR0ZzYVdNaU9qQXNJblZ1WkdWeWJHbHVaU0k2TUN3aVlXeHBaMjRpT2lKalpXNTBaWElpTENKc1pYUjBaWEp6Y0dGamFXNW5Jam9pYm05eWJXRnNJaXdpZDI5eVpITndZV05wYm1jaU9pSnViM0p0WVd3aUxDSjBaWGgwZEhKaGJuTm1iM0p0SWpvaWJtOXVaU0o5TEhzaVpYaDBjbUVpT2lJaWZWMTkifSwic3R5bGUiOnsic3R5bGUiOiIifX19'
        ));
        array_push($styles, array(
            'id'    => 1018,
            'value' => 'eyJuYW1lIjoiUGxheWZhaXIgSHVnZSBMaWdodCIsImRhdGEiOnsiZm9udCI6eyJmb250IjoiZXlKdVlXMWxJam9pVTNSaGRHbGpJaXdpWkdGMFlTSTZXM3NpWlhoMGNtRWlPaUlpTENKamIyeHZjaUk2SW1abVptWm1abVptSWl3aWMybDZaU0k2SWprMmZIeHdlQ0lzSW5SemFHRmtiM2NpT2lJd2ZDcDhNSHdxZkRCOEtud3dNREF3TURCbVppSXNJbUZtYjI1MElqb2lKMUJzWVhsbVlXbHlJRVJwYzNCc1lYa25MQ0JCY21saGJDSXNJbXhwYm1Wb1pXbG5hSFFpT2lJeExqVWlMQ0ppYjJ4a0lqb3hMQ0pwZEdGc2FXTWlPakFzSW5WdVpHVnliR2x1WlNJNk1Dd2lZV3hwWjI0aU9pSmpaVzUwWlhJaUxDSnNaWFIwWlhKemNHRmphVzVuSWpvaWJtOXliV0ZzSWl3aWQyOXlaSE53WVdOcGJtY2lPaUp1YjNKdFlXd2lMQ0owWlhoMGRISmhibk5tYjNKdElqb2libTl1WlNKOUxIc2laWGgwY21FaU9pSWlmVjE5In0sInN0eWxlIjp7InN0eWxlIjoiIn19fQ=='
        ));
        array_push($styles, array(
            'id'    => 1019,
            'value' => 'eyJuYW1lIjoiUGxheWZhaXIgQ2FwdGlvbiBEYXJrIiwiZGF0YSI6eyJmb250Ijp7ImZvbnQiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0pqYjJ4dmNpSTZJbVptWm1abVptWm1JaXdpYzJsNlpTSTZJalE0Zkh4d2VDSXNJblJ6YUdGa2IzY2lPaUl3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltRm1iMjUwSWpvaUoxQnNZWGxtWVdseUlFUnBjM0JzWVhrbkxDQkJjbWxoYkNJc0lteHBibVZvWldsbmFIUWlPaUl4TGpVaUxDSmliMnhrSWpveExDSnBkR0ZzYVdNaU9qQXNJblZ1WkdWeWJHbHVaU0k2TUN3aVlXeHBaMjRpT2lKalpXNTBaWElpTENKc1pYUjBaWEp6Y0dGamFXNW5Jam9pYm05eWJXRnNJaXdpZDI5eVpITndZV05wYm1jaU9pSnViM0p0WVd3aUxDSjBaWGgwZEhKaGJuTm1iM0p0SWpvaWJtOXVaU0o5TEhzaVpYaDBjbUVpT2lJaWZWMTkifSwic3R5bGUiOnsic3R5bGUiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0ppWVdOclozSnZkVzVrWTI5c2IzSWlPaUl3TURBd01EQmpZeUlzSW5CaFpHUnBibWNpT2lJNGZDcDhNakI4S253NGZDcDhNakI4S254d2VDSXNJbUp2ZUhOb1lXUnZkeUk2SWpCOEtud3dmQ3A4TUh3cWZEQjhLbnd3TURBd01EQm1aaUlzSW1KdmNtUmxjaUk2SWpCOEtueHpiMnhwWkh3cWZEQXdNREF3TUdabUlpd2lZbTl5WkdWeWNtRmthWFZ6SWpvaU1DSjlMSHNpWlhoMGNtRWlPaUlpZlYxOSJ9fX0='
        ));
        array_push($styles, array(
            'id'    => 1020,
            'value' => 'eyJuYW1lIjoiUGxheWZhaXIgQ2FwdGlvbiBMaWdodCIsImRhdGEiOnsiZm9udCI6eyJmb250IjoiZXlKdVlXMWxJam9pVTNSaGRHbGpJaXdpWkdGMFlTSTZXM3NpWlhoMGNtRWlPaUlpTENKamIyeHZjaUk2SWpNek16TXpNMlptSWl3aWMybDZaU0k2SWpRNGZIeHdlQ0lzSW5SemFHRmtiM2NpT2lJd2ZDcDhNSHdxZkRCOEtud3dNREF3TURCbVppSXNJbUZtYjI1MElqb2lKMUJzWVhsbVlXbHlJRVJwYzNCc1lYa25MQ0JCY21saGJDSXNJbXhwYm1Wb1pXbG5hSFFpT2lJeExqVWlMQ0ppYjJ4a0lqb3hMQ0pwZEdGc2FXTWlPakFzSW5WdVpHVnliR2x1WlNJNk1Dd2lZV3hwWjI0aU9pSmpaVzUwWlhJaUxDSnNaWFIwWlhKemNHRmphVzVuSWpvaWJtOXliV0ZzSWl3aWQyOXlaSE53WVdOcGJtY2lPaUp1YjNKdFlXd2lMQ0owWlhoMGRISmhibk5tYjNKdElqb2libTl1WlNKOUxIc2laWGgwY21FaU9pSWlmVjE5In0sInN0eWxlIjp7InN0eWxlIjoiZXlKdVlXMWxJam9pVTNSaGRHbGpJaXdpWkdGMFlTSTZXM3NpWlhoMGNtRWlPaUlpTENKaVlXTnJaM0p2ZFc1a1kyOXNiM0lpT2lKbVptWm1abVpqWXlJc0luQmhaR1JwYm1jaU9pSTRmQ3A4TWpCOEtudzRmQ3A4TWpCOEtueHdlQ0lzSW1KdmVITm9ZV1J2ZHlJNklqQjhLbnd3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltSnZjbVJsY2lJNklqQjhLbnh6YjJ4cFpId3FmREF3TURBd01EQXdJaXdpWW05eVpHVnljbUZrYVhWeklqb2lNQ0o5TEhzaVpYaDBjbUVpT2lJaWZWMTkifX19'
        ));
        array_push($styles, array(
            'id'    => 1021,
            'value' => 'eyJuYW1lIjoiTW9udHNlcnJhdCBIZWFkaW5nIERhcmsiLCJkYXRhIjp7ImZvbnQiOnsiZm9udCI6ImV5SnVZVzFsSWpvaVUzUmhkR2xqSWl3aVpHRjBZU0k2VzNzaVpYaDBjbUVpT2lJaUxDSmpiMnh2Y2lJNklqTXpNek16TTJabUlpd2ljMmw2WlNJNklqUTRmSHh3ZUNJc0luUnphR0ZrYjNjaU9pSXdmQ3A4TUh3cWZEQjhLbnd3TURBd01EQm1aaUlzSW1GbWIyNTBJam9pVFc5dWRITmxjbkpoZEN3Z1FYSnBZV3dpTENKc2FXNWxhR1ZwWjJoMElqb2lNUzQxSWl3aVltOXNaQ0k2TUN3aWFYUmhiR2xqSWpvd0xDSjFibVJsY214cGJtVWlPakFzSW1Gc2FXZHVJam9pWTJWdWRHVnlJaXdpYkdWMGRHVnljM0JoWTJsdVp5STZJbTV2Y20xaGJDSXNJbmR2Y21SemNHRmphVzVuSWpvaWJtOXliV0ZzSWl3aWRHVjRkSFJ5WVc1elptOXliU0k2SW01dmJtVWlmU3g3SW1WNGRISmhJam9pSW4xZGZRPT0ifSwic3R5bGUiOnsic3R5bGUiOiIifX19'
        ));
        array_push($styles, array(
            'id'    => 1022,
            'value' => 'eyJuYW1lIjoiTW9udHNlcnJhdCBIZWFkaW5nIExpZ2h0IiwiZGF0YSI6eyJmb250Ijp7ImZvbnQiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0pqYjJ4dmNpSTZJbVptWm1abVptWm1JaXdpYzJsNlpTSTZJalE0Zkh4d2VDSXNJblJ6YUdGa2IzY2lPaUl3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltRm1iMjUwSWpvaVRXOXVkSE5sY25KaGRDd2dRWEpwWVd3aUxDSnNhVzVsYUdWcFoyaDBJam9pTVM0MUlpd2lZbTlzWkNJNk1Dd2lhWFJoYkdsaklqb3dMQ0oxYm1SbGNteHBibVVpT2pBc0ltRnNhV2R1SWpvaVkyVnVkR1Z5SWl3aWJHVjBkR1Z5YzNCaFkybHVaeUk2SW01dmNtMWhiQ0lzSW5kdmNtUnpjR0ZqYVc1bklqb2libTl5YldGc0lpd2lkR1Y0ZEhSeVlXNXpabTl5YlNJNkltNXZibVVpZlN4N0ltVjRkSEpoSWpvaUluMWRmUT09In0sInN0eWxlIjp7InN0eWxlIjoiIn19fQ=='
        ));
        array_push($styles, array(
            'id'    => 1023,
            'value' => 'eyJuYW1lIjoiTW9udHNlcnJhdCBTdWJoZWFkaW5nIERhcmsiLCJkYXRhIjp7ImZvbnQiOnsiZm9udCI6ImV5SnVZVzFsSWpvaVUzUmhkR2xqSWl3aVpHRjBZU0k2VzNzaVpYaDBjbUVpT2lJaUxDSmpiMnh2Y2lJNklqTXpNek16TTJabUlpd2ljMmw2WlNJNklqSTBmSHh3ZUNJc0luUnphR0ZrYjNjaU9pSXdmQ3A4TUh3cWZEQjhLbnd3TURBd01EQm1aaUlzSW1GbWIyNTBJam9pVFc5dWRITmxjbkpoZEN3Z1FYSnBZV3dpTENKc2FXNWxhR1ZwWjJoMElqb2lNUzQxSWl3aVltOXNaQ0k2TUN3aWFYUmhiR2xqSWpvd0xDSjFibVJsY214cGJtVWlPakFzSW1Gc2FXZHVJam9pWTJWdWRHVnlJaXdpYkdWMGRHVnljM0JoWTJsdVp5STZJbTV2Y20xaGJDSXNJbmR2Y21SemNHRmphVzVuSWpvaWJtOXliV0ZzSWl3aWRHVjRkSFJ5WVc1elptOXliU0k2SW01dmJtVWlmU3g3SW1WNGRISmhJam9pSW4xZGZRPT0ifSwic3R5bGUiOnsic3R5bGUiOiIifX19'
        ));
        array_push($styles, array(
            'id'    => 1024,
            'value' => 'eyJuYW1lIjoiTW9udHNlcnJhdCBTdWJoZWFkaW5nIExpZ2h0IiwiZGF0YSI6eyJmb250Ijp7ImZvbnQiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0pqYjJ4dmNpSTZJbVptWm1abVptWm1JaXdpYzJsNlpTSTZJakkwZkh4d2VDSXNJblJ6YUdGa2IzY2lPaUl3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltRm1iMjUwSWpvaVRXOXVkSE5sY25KaGRDd2dRWEpwWVd3aUxDSnNhVzVsYUdWcFoyaDBJam9pTVM0MUlpd2lZbTlzWkNJNk1Dd2lhWFJoYkdsaklqb3dMQ0oxYm1SbGNteHBibVVpT2pBc0ltRnNhV2R1SWpvaVkyVnVkR1Z5SWl3aWJHVjBkR1Z5YzNCaFkybHVaeUk2SW01dmNtMWhiQ0lzSW5kdmNtUnpjR0ZqYVc1bklqb2libTl5YldGc0lpd2lkR1Y0ZEhSeVlXNXpabTl5YlNJNkltNXZibVVpZlN4N0ltVjRkSEpoSWpvaUluMWRmUT09In0sInN0eWxlIjp7InN0eWxlIjoiIn19fQ=='
        ));
        array_push($styles, array(
            'id'    => 1025,
            'value' => 'eyJuYW1lIjoiTW9udHNlcnJhdCBIdWdlIERhcmsiLCJkYXRhIjp7ImZvbnQiOnsiZm9udCI6ImV5SnVZVzFsSWpvaVUzUmhkR2xqSWl3aVpHRjBZU0k2VzNzaVpYaDBjbUVpT2lJaUxDSmpiMnh2Y2lJNklqTXpNek16TTJabUlpd2ljMmw2WlNJNklqUTRmSHh3ZUNJc0luUnphR0ZrYjNjaU9pSXdmQ3A4TUh3cWZEQjhLbnd3TURBd01EQm1aaUlzSW1GbWIyNTBJam9pVFc5dWRITmxjbkpoZEN3Z1FYSnBZV3dpTENKc2FXNWxhR1ZwWjJoMElqb2lNUzQxSWl3aVltOXNaQ0k2TVN3aWFYUmhiR2xqSWpvd0xDSjFibVJsY214cGJtVWlPakFzSW1Gc2FXZHVJam9pWTJWdWRHVnlJaXdpYkdWMGRHVnljM0JoWTJsdVp5STZJbTV2Y20xaGJDSXNJbmR2Y21SemNHRmphVzVuSWpvaWJtOXliV0ZzSWl3aWRHVjRkSFJ5WVc1elptOXliU0k2SW5Wd2NHVnlZMkZ6WlNKOUxIc2laWGgwY21FaU9pSWlmVjE5In0sInN0eWxlIjp7InN0eWxlIjoiIn19fQ=='
        ));
        array_push($styles, array(
            'id'    => 1026,
            'value' => 'eyJuYW1lIjoiTW9udHNlcnJhdCBIdWdlIExpZ2h0IiwiZGF0YSI6eyJmb250Ijp7ImZvbnQiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0pqYjJ4dmNpSTZJbVptWm1abVptWm1JaXdpYzJsNlpTSTZJalE0Zkh4d2VDSXNJblJ6YUdGa2IzY2lPaUl3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltRm1iMjUwSWpvaVRXOXVkSE5sY25KaGRDd2dRWEpwWVd3aUxDSnNhVzVsYUdWcFoyaDBJam9pTVM0MUlpd2lZbTlzWkNJNk1Td2lhWFJoYkdsaklqb3dMQ0oxYm1SbGNteHBibVVpT2pBc0ltRnNhV2R1SWpvaVkyVnVkR1Z5SWl3aWJHVjBkR1Z5YzNCaFkybHVaeUk2SW01dmNtMWhiQ0lzSW5kdmNtUnpjR0ZqYVc1bklqb2libTl5YldGc0lpd2lkR1Y0ZEhSeVlXNXpabTl5YlNJNkluVndjR1Z5WTJGelpTSjlMSHNpWlhoMGNtRWlPaUlpZlYxOSJ9LCJzdHlsZSI6eyJzdHlsZSI6IiJ9fX0='
        ));
        array_push($styles, array(
            'id'    => 1027,
            'value' => 'eyJuYW1lIjoiTW9udHNlcnJhdCBCbGFjayBDYXB0aW9uIiwiZGF0YSI6eyJmb250Ijp7ImZvbnQiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0pqYjJ4dmNpSTZJbVptWm1abVptWm1JaXdpYzJsNlpTSTZJalE0Zkh4d2VDSXNJblJ6YUdGa2IzY2lPaUl3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltRm1iMjUwSWpvaVRXOXVkSE5sY25KaGRDd2dRWEpwWVd3aUxDSnNhVzVsYUdWcFoyaDBJam9pTVM0MUlpd2lZbTlzWkNJNk1Dd2lhWFJoYkdsaklqb3dMQ0oxYm1SbGNteHBibVVpT2pBc0ltRnNhV2R1SWpvaVkyVnVkR1Z5SWl3aWJHVjBkR1Z5YzNCaFkybHVaeUk2SW01dmNtMWhiQ0lzSW5kdmNtUnpjR0ZqYVc1bklqb2libTl5YldGc0lpd2lkR1Y0ZEhSeVlXNXpabTl5YlNJNkltNXZibVVpZlN4N0ltVjRkSEpoSWpvaUluMWRmUT09In0sInN0eWxlIjp7InN0eWxlIjoiZXlKdVlXMWxJam9pVTNSaGRHbGpJaXdpWkdGMFlTSTZXM3NpWlhoMGNtRWlPaUlpTENKaVlXTnJaM0p2ZFc1a1kyOXNiM0lpT2lJd01EQXdNREJqWXlJc0luQmhaR1JwYm1jaU9pSTJmQ3A4TWpCOEtudzJmQ3A4TWpCOEtueHdlQ0lzSW1KdmVITm9ZV1J2ZHlJNklqQjhLbnd3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltSnZjbVJsY2lJNklqQjhLbnh6YjJ4cFpId3FmREF3TURBd01EQXdJaXdpWW05eVpHVnljbUZrYVhWeklqb2lNQ0o5TEhzaVpYaDBjbUVpT2lJaWZWMTkifX19'
        ));
        array_push($styles, array(
            'id'    => 1028,
            'value' => 'eyJuYW1lIjoiTW9udHNlcnJhdCBXaGl0ZSBDYXB0aW9uIiwiZGF0YSI6eyJmb250Ijp7ImZvbnQiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0pqYjJ4dmNpSTZJak16TXpNek0yWm1JaXdpYzJsNlpTSTZJalE0Zkh4d2VDSXNJblJ6YUdGa2IzY2lPaUl3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltRm1iMjUwSWpvaVRXOXVkSE5sY25KaGRDd2dRWEpwWVd3aUxDSnNhVzVsYUdWcFoyaDBJam9pTVM0MUlpd2lZbTlzWkNJNk1Dd2lhWFJoYkdsaklqb3dMQ0oxYm1SbGNteHBibVVpT2pBc0ltRnNhV2R1SWpvaVkyVnVkR1Z5SWl3aWJHVjBkR1Z5YzNCaFkybHVaeUk2SW01dmNtMWhiQ0lzSW5kdmNtUnpjR0ZqYVc1bklqb2libTl5YldGc0lpd2lkR1Y0ZEhSeVlXNXpabTl5YlNJNkltNXZibVVpZlN4N0ltVjRkSEpoSWpvaUluMWRmUT09In0sInN0eWxlIjp7InN0eWxlIjoiZXlKdVlXMWxJam9pVTNSaGRHbGpJaXdpWkdGMFlTSTZXM3NpWlhoMGNtRWlPaUlpTENKaVlXTnJaM0p2ZFc1a1kyOXNiM0lpT2lKbVptWm1abVpqWXlJc0luQmhaR1JwYm1jaU9pSTJmQ3A4TWpCOEtudzJmQ3A4TWpCOEtueHdlQ0lzSW1KdmVITm9ZV1J2ZHlJNklqQjhLbnd3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltSnZjbVJsY2lJNklqQjhLbnh6YjJ4cFpId3FmREF3TURBd01EQXdJaXdpWW05eVpHVnljbUZrYVhWeklqb2lNQ0o5TEhzaVpYaDBjbUVpT2lJaWZWMTkifX19'
        ));

    }

    public function itembuttonStorage(&$styles) {
        array_push($styles, array(
            'id'    => 1001,
            'value' => 'eyJuYW1lIjoiUmVjdGFuZ2xlIFJhbGV3YXkgR3JlZW4iLCJkYXRhIjp7ImZvbnQiOnsiZm9udCI6ImV5SnVZVzFsSWpvaVUzUmhkR2xqSWl3aVpHRjBZU0k2VzNzaVpYaDBjbUVpT2lJaUxDSmpiMnh2Y2lJNkltWm1abVptWm1abUlpd2ljMmw2WlNJNklqRTBmSHh3ZUNJc0luUnphR0ZrYjNjaU9pSXdmQ3A4TUh3cWZEQjhLbnd3TURBd01EQm1aaUlzSW1GbWIyNTBJam9pVW1Gc1pYZGhlU3dnUVhKcFlXd2lMQ0pzYVc1bGFHVnBaMmgwSWpvaU1TNDFJaXdpWW05c1pDSTZNU3dpYVhSaGJHbGpJam93TENKMWJtUmxjbXhwYm1VaU9qQXNJbUZzYVdkdUlqb2lZMlZ1ZEdWeUlpd2liR1YwZEdWeWMzQmhZMmx1WnlJNkltNXZjbTFoYkNJc0luZHZjbVJ6Y0dGamFXNW5Jam9pYm05eWJXRnNJaXdpZEdWNGRIUnlZVzV6Wm05eWJTSTZJbTV2Ym1VaWZTeDdJbVY0ZEhKaElqb2lJbjFkZlE9PSJ9LCJzdHlsZSI6eyJzdHlsZSI6ImV5SnVZVzFsSWpvaVUzUmhkR2xqSWl3aVpHRjBZU0k2VzNzaVpYaDBjbUVpT2lJaUxDSmlZV05yWjNKdmRXNWtZMjlzYjNJaU9pSTFZMkpoTTJObVppSXNJbkJoWkdScGJtY2lPaUl4TUh3cWZETXdmQ3A4TVRCOEtud3pNSHdxZkhCNElpd2lZbTk0YzJoaFpHOTNJam9pTUh3cWZEQjhLbnd3ZkNwOE1Id3FmREF3TURBd01HWm1JaXdpWW05eVpHVnlJam9pTUh3cWZITnZiR2xrZkNwOE1EQXdNREF3Wm1ZaUxDSmliM0prWlhKeVlXUnBkWE1pT2lJd0luMHNleUpsZUhSeVlTSTZJaUlzSW1KaFkydG5jbTkxYm1SamIyeHZjaUk2SWpVeVlUY3pObVptSW4xZGZRPT0ifX19'
        ));
        array_push($styles, array(
            'id'    => 1002,
            'value' => 'eyJuYW1lIjoiUmVjdGFuZ2xlIFJhbGV3YXkgQmx1ZSIsImRhdGEiOnsiZm9udCI6eyJmb250IjoiZXlKdVlXMWxJam9pVTNSaGRHbGpJaXdpWkdGMFlTSTZXM3NpWlhoMGNtRWlPaUlpTENKamIyeHZjaUk2SW1abVptWm1abVptSWl3aWMybDZaU0k2SWpFMGZIeHdlQ0lzSW5SemFHRmtiM2NpT2lJd2ZDcDhNSHdxZkRCOEtud3dNREF3TURCbVppSXNJbUZtYjI1MElqb2lVbUZzWlhkaGVTd2dRWEpwWVd3aUxDSnNhVzVsYUdWcFoyaDBJam9pTVM0MUlpd2lZbTlzWkNJNk1Td2lhWFJoYkdsaklqb3dMQ0oxYm1SbGNteHBibVVpT2pBc0ltRnNhV2R1SWpvaVkyVnVkR1Z5SWl3aWJHVjBkR1Z5YzNCaFkybHVaeUk2SW01dmNtMWhiQ0lzSW5kdmNtUnpjR0ZqYVc1bklqb2libTl5YldGc0lpd2lkR1Y0ZEhSeVlXNXpabTl5YlNJNkltNXZibVVpZlN4N0ltVjRkSEpoSWpvaUluMWRmUT09In0sInN0eWxlIjp7InN0eWxlIjoiZXlKdVlXMWxJam9pVTNSaGRHbGpJaXdpWkdGMFlTSTZXM3NpWlhoMGNtRWlPaUlpTENKaVlXTnJaM0p2ZFc1a1kyOXNiM0lpT2lJd01XRmtaRE5tWmlJc0luQmhaR1JwYm1jaU9pSXhNSHdxZkRNd2ZDcDhNVEI4S253ek1Id3FmSEI0SWl3aVltOTRjMmhoWkc5M0lqb2lNSHdxZkRCOEtud3dmQ3A4TUh3cWZEQXdNREF3TUdabUlpd2lZbTl5WkdWeUlqb2lNSHdxZkhOdmJHbGtmQ3A4TURBd01EQXdabVlpTENKaWIzSmtaWEp5WVdScGRYTWlPaUl3SW4wc2V5SmxlSFJ5WVNJNklpSXNJbUpoWTJ0bmNtOTFibVJqYjJ4dmNpSTZJakF4T1dKaVpHWm1JbjFkZlE9PSJ9fX0='
        ));
        array_push($styles, array(
            'id'    => 1003,
            'value' => 'eyJuYW1lIjoiUmVjdGFuZ2xlIFJhbGV3YXkgUHVycGxlIiwiZGF0YSI6eyJmb250Ijp7ImZvbnQiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0pqYjJ4dmNpSTZJbVptWm1abVptWm1JaXdpYzJsNlpTSTZJakUwZkh4d2VDSXNJblJ6YUdGa2IzY2lPaUl3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltRm1iMjUwSWpvaVVtRnNaWGRoZVN3Z1FYSnBZV3dpTENKc2FXNWxhR1ZwWjJoMElqb2lNUzQxSWl3aVltOXNaQ0k2TVN3aWFYUmhiR2xqSWpvd0xDSjFibVJsY214cGJtVWlPakFzSW1Gc2FXZHVJam9pWTJWdWRHVnlJaXdpYkdWMGRHVnljM0JoWTJsdVp5STZJbTV2Y20xaGJDSXNJbmR2Y21SemNHRmphVzVuSWpvaWJtOXliV0ZzSWl3aWRHVjRkSFJ5WVc1elptOXliU0k2SW01dmJtVWlmU3g3SW1WNGRISmhJam9pSW4xZGZRPT0ifSwic3R5bGUiOnsic3R5bGUiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0ppWVdOclozSnZkVzVrWTI5c2IzSWlPaUk0TnpVM1lqSm1aaUlzSW5CaFpHUnBibWNpT2lJeE1Id3FmRE13ZkNwOE1UQjhLbnd6TUh3cWZIQjRJaXdpWW05NGMyaGhaRzkzSWpvaU1Id3FmREI4S253d2ZDcDhNSHdxZkRBd01EQXdNR1ptSWl3aVltOXlaR1Z5SWpvaU1Id3FmSE52Ykdsa2ZDcDhNREF3TURBd1ptWWlMQ0ppYjNKa1pYSnlZV1JwZFhNaU9pSXdJbjBzZXlKbGVIUnlZU0k2SWlJc0ltSmhZMnRuY205MWJtUmpiMnh2Y2lJNklqYzVOR1ZoTUdabUluMWRmUT09In19fQ=='
        ));
        array_push($styles, array(
            'id'    => 1004,
            'value' => 'eyJuYW1lIjoiUGlsbCBSYWxld2F5IEdob3N0IE9yYW5nZSIsImRhdGEiOnsiZm9udCI6eyJmb250IjoiZXlKdVlXMWxJam9pVTNSaGRHbGpJaXdpWkdGMFlTSTZXM3NpWlhoMGNtRWlPaUlpTENKamIyeHZjaUk2SW1Vd05HVXhaV1ptSWl3aWMybDZaU0k2SWpFMGZIeHdlQ0lzSW5SemFHRmtiM2NpT2lJd2ZDcDhNSHdxZkRCOEtud3dNREF3TURCbVppSXNJbUZtYjI1MElqb2lVbUZzWlhkaGVTd2dRWEpwWVd3aUxDSnNhVzVsYUdWcFoyaDBJam9pTVM0MUlpd2lZbTlzWkNJNk1Dd2lhWFJoYkdsaklqb3dMQ0oxYm1SbGNteHBibVVpT2pBc0ltRnNhV2R1SWpvaVkyVnVkR1Z5SWl3aWJHVjBkR1Z5YzNCaFkybHVaeUk2SW01dmNtMWhiQ0lzSW5kdmNtUnpjR0ZqYVc1bklqb2libTl5YldGc0lpd2lkR1Y0ZEhSeVlXNXpabTl5YlNJNkltNXZibVVpZlN4N0ltVjRkSEpoSWpvaUlpd2lZMjlzYjNJaU9pSm1abVptWm1abVppSjlYWDA9In0sInN0eWxlIjp7InN0eWxlIjoiZXlKdVlXMWxJam9pVTNSaGRHbGpJaXdpWkdGMFlTSTZXM3NpWlhoMGNtRWlPaUlpTENKaVlXTnJaM0p2ZFc1a1kyOXNiM0lpT2lJd01EQXdNREF3TUNJc0luQmhaR1JwYm1jaU9pSXhNSHdxZkRNd2ZDcDhNVEI4S253ek1Id3FmSEI0SWl3aVltOTRjMmhoWkc5M0lqb2lNSHdxZkRCOEtud3dmQ3A4TUh3cWZEQXdNREF3TUdabUlpd2lZbTl5WkdWeUlqb2lNWHdxZkhOdmJHbGtmQ3A4WlRBMFpURmxabVlpTENKaWIzSmtaWEp5WVdScGRYTWlPaUk1T1NKOUxIc2laWGgwY21FaU9pSWlMQ0ppWVdOclozSnZkVzVrWTI5c2IzSWlPaUpsTURSbE1XVm1aaUo5WFgwPSJ9fX0='
        ));
        array_push($styles, array(
            'id'    => 1005,
            'value' => 'eyJuYW1lIjoiUGlsbCBSYWxld2F5IE9yYW5nZSBJbnZlcnNlIiwiZGF0YSI6eyJmb250Ijp7ImZvbnQiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0pqYjJ4dmNpSTZJbVV3TkdVeFpXWm1JaXdpYzJsNlpTSTZJakUwZkh4d2VDSXNJblJ6YUdGa2IzY2lPaUl3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltRm1iMjUwSWpvaVVtRnNaWGRoZVN3Z1FYSnBZV3dpTENKc2FXNWxhR1ZwWjJoMElqb2lNUzQxSWl3aVltOXNaQ0k2TUN3aWFYUmhiR2xqSWpvd0xDSjFibVJsY214cGJtVWlPakFzSW1Gc2FXZHVJam9pWTJWdWRHVnlJaXdpYkdWMGRHVnljM0JoWTJsdVp5STZJbTV2Y20xaGJDSXNJbmR2Y21SemNHRmphVzVuSWpvaWJtOXliV0ZzSWl3aWRHVjRkSFJ5WVc1elptOXliU0k2SW01dmJtVWlmU3g3SW1WNGRISmhJam9pSWl3aVkyOXNiM0lpT2lKbE1EUmxNV1ZtWmlKOVhYMD0ifSwic3R5bGUiOnsic3R5bGUiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0ppWVdOclozSnZkVzVrWTI5c2IzSWlPaUl3TURBd01EQXdNQ0lzSW5CaFpHUnBibWNpT2lJeE1Id3FmRE13ZkNwOE1UQjhLbnd6TUh3cWZIQjRJaXdpWW05NGMyaGhaRzkzSWpvaU1Id3FmREI4S253d2ZDcDhNSHdxZkRBd01EQXdNR1ptSWl3aVltOXlaR1Z5SWpvaU1Yd3FmSE52Ykdsa2ZDcDhaVEEwWlRGbFptWWlMQ0ppYjNKa1pYSnlZV1JwZFhNaU9pSTVPU0o5TEhzaVpYaDBjbUVpT2lJaUxDSmlZV05yWjNKdmRXNWtZMjlzYjNJaU9pSm1abVptWm1abVppSXNJbUp2Y21SbGNpSTZJakY4S254emIyeHBaSHdxZkdabVptWm1abVptSW4xZGZRPT0ifX19'
        ));
        array_push($styles, array(
            'id'    => 1006,
            'value' => 'eyJuYW1lIjoiUm91bmRlZCBNb250c2VycmF0IEdyZWVuIiwiZGF0YSI6eyJmb250Ijp7ImZvbnQiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0pqYjJ4dmNpSTZJbVptWm1abVptWm1JaXdpYzJsNlpTSTZJakUwZkh4d2VDSXNJblJ6YUdGa2IzY2lPaUl3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltRm1iMjUwSWpvaVRXOXVkSE5sY25KaGRDd2dRWEpwWVd3aUxDSnNhVzVsYUdWcFoyaDBJam9pTVM0MUlpd2lZbTlzWkNJNk1Dd2lhWFJoYkdsaklqb3dMQ0oxYm1SbGNteHBibVVpT2pBc0ltRnNhV2R1SWpvaVkyVnVkR1Z5SWl3aWJHVjBkR1Z5YzNCaFkybHVaeUk2SW01dmNtMWhiQ0lzSW5kdmNtUnpjR0ZqYVc1bklqb2libTl5YldGc0lpd2lkR1Y0ZEhSeVlXNXpabTl5YlNJNkltNXZibVVpZlN4N0ltVjRkSEpoSWpvaUluMWRmUT09In0sInN0eWxlIjp7InN0eWxlIjoiZXlKdVlXMWxJam9pVTNSaGRHbGpJaXdpWkdGMFlTSTZXM3NpWlhoMGNtRWlPaUlpTENKaVlXTnJaM0p2ZFc1a1kyOXNiM0lpT2lJMVkySmhNMk5tWmlJc0luQmhaR1JwYm1jaU9pSXhNSHdxZkRJd2ZDcDhNVEI4S253eU1Id3FmSEI0SWl3aVltOTRjMmhoWkc5M0lqb2lNSHdxZkRCOEtud3dmQ3A4TUh3cWZEQXdNREF3TUdabUlpd2lZbTl5WkdWeUlqb2lNSHdxZkhOdmJHbGtmQ3A4TURBd01EQXdabVlpTENKaWIzSmtaWEp5WVdScGRYTWlPaUkxSW4wc2V5SmxlSFJ5WVNJNklpSXNJbUpoWTJ0bmNtOTFibVJqYjJ4dmNpSTZJalV5WVRjek5tWm1JaXdpWW05eVpHVnljbUZrYVhWeklqb2lOU0lzSW1KdmNtUmxjaUk2SWpCOEtueHpiMnhwWkh3cWZHWm1abVptWm1abUluMWRmUT09In19fQ=='
        ));
        array_push($styles, array(
            'id'    => 1007,
            'value' => 'eyJuYW1lIjoiUm91bmRlZCBNb250c2VycmF0IEJsdWUiLCJkYXRhIjp7ImZvbnQiOnsiZm9udCI6ImV5SnVZVzFsSWpvaVUzUmhkR2xqSWl3aVpHRjBZU0k2VzNzaVpYaDBjbUVpT2lJaUxDSmpiMnh2Y2lJNkltWm1abVptWm1abUlpd2ljMmw2WlNJNklqRTBmSHh3ZUNJc0luUnphR0ZrYjNjaU9pSXdmQ3A4TUh3cWZEQjhLbnd3TURBd01EQm1aaUlzSW1GbWIyNTBJam9pVFc5dWRITmxjbkpoZEN3Z1FYSnBZV3dpTENKc2FXNWxhR1ZwWjJoMElqb2lNUzQxSWl3aVltOXNaQ0k2TUN3aWFYUmhiR2xqSWpvd0xDSjFibVJsY214cGJtVWlPakFzSW1Gc2FXZHVJam9pWTJWdWRHVnlJaXdpYkdWMGRHVnljM0JoWTJsdVp5STZJbTV2Y20xaGJDSXNJbmR2Y21SemNHRmphVzVuSWpvaWJtOXliV0ZzSWl3aWRHVjRkSFJ5WVc1elptOXliU0k2SW01dmJtVWlmU3g3SW1WNGRISmhJam9pSW4xZGZRPT0ifSwic3R5bGUiOnsic3R5bGUiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0ppWVdOclozSnZkVzVrWTI5c2IzSWlPaUl3TVdGa1pETm1aaUlzSW5CaFpHUnBibWNpT2lJeE1Id3FmREl3ZkNwOE1UQjhLbnd5TUh3cWZIQjRJaXdpWW05NGMyaGhaRzkzSWpvaU1Id3FmREI4S253d2ZDcDhNSHdxZkRBd01EQXdNR1ptSWl3aVltOXlaR1Z5SWpvaU1Id3FmSE52Ykdsa2ZDcDhNREF3TURBd1ptWWlMQ0ppYjNKa1pYSnlZV1JwZFhNaU9pSTFJbjBzZXlKbGVIUnlZU0k2SWlJc0ltSmhZMnRuY205MWJtUmpiMnh2Y2lJNklqQXhPV0ppWkdabUlpd2lZbTl5WkdWeWNtRmthWFZ6SWpvaU5TSXNJbUp2Y21SbGNpSTZJakI4S254emIyeHBaSHdxZkdabVptWm1abVptSW4xZGZRPT0ifX19'
        ));
        array_push($styles, array(
            'id'    => 1008,
            'value' => 'eyJuYW1lIjoiUm91bmRlZCBNb250c2VycmF0IFB1cnBsZSIsImRhdGEiOnsiZm9udCI6eyJmb250IjoiZXlKdVlXMWxJam9pVTNSaGRHbGpJaXdpWkdGMFlTSTZXM3NpWlhoMGNtRWlPaUlpTENKamIyeHZjaUk2SW1abVptWm1abVptSWl3aWMybDZaU0k2SWpFMGZIeHdlQ0lzSW5SemFHRmtiM2NpT2lJd2ZDcDhNSHdxZkRCOEtud3dNREF3TURCbVppSXNJbUZtYjI1MElqb2lUVzl1ZEhObGNuSmhkQ3dnUVhKcFlXd2lMQ0pzYVc1bGFHVnBaMmgwSWpvaU1TNDFJaXdpWW05c1pDSTZNQ3dpYVhSaGJHbGpJam93TENKMWJtUmxjbXhwYm1VaU9qQXNJbUZzYVdkdUlqb2lZMlZ1ZEdWeUlpd2liR1YwZEdWeWMzQmhZMmx1WnlJNkltNXZjbTFoYkNJc0luZHZjbVJ6Y0dGamFXNW5Jam9pYm05eWJXRnNJaXdpZEdWNGRIUnlZVzV6Wm05eWJTSTZJbTV2Ym1VaWZTeDdJbVY0ZEhKaElqb2lJbjFkZlE9PSJ9LCJzdHlsZSI6eyJzdHlsZSI6ImV5SnVZVzFsSWpvaVUzUmhkR2xqSWl3aVpHRjBZU0k2VzNzaVpYaDBjbUVpT2lJaUxDSmlZV05yWjNKdmRXNWtZMjlzYjNJaU9pSTROelUzWWpKbVppSXNJbkJoWkdScGJtY2lPaUl4TUh3cWZESXdmQ3A4TVRCOEtud3lNSHdxZkhCNElpd2lZbTk0YzJoaFpHOTNJam9pTUh3cWZEQjhLbnd3ZkNwOE1Id3FmREF3TURBd01HWm1JaXdpWW05eVpHVnlJam9pTUh3cWZITnZiR2xrZkNwOE1EQXdNREF3Wm1ZaUxDSmliM0prWlhKeVlXUnBkWE1pT2lJMUluMHNleUpsZUhSeVlTSTZJaUlzSW1KaFkydG5jbTkxYm1SamIyeHZjaUk2SWpjNU5HVmhNR1ptSWl3aVltOXlaR1Z5Y21Ga2FYVnpJam9pTlNJc0ltSnZjbVJsY2lJNklqQjhLbnh6YjJ4cFpId3FmR1ptWm1abVptWm1JbjFkZlE9PSJ9fX0='
        ));
        array_push($styles, array(
            'id'    => 1009,
            'value' => 'eyJuYW1lIjoiUGlsbCBNb250c2VycmF0IEdob3N0IE9yYW5nZSIsImRhdGEiOnsiZm9udCI6eyJmb250IjoiZXlKdVlXMWxJam9pVTNSaGRHbGpJaXdpWkdGMFlTSTZXM3NpWlhoMGNtRWlPaUlpTENKamIyeHZjaUk2SW1Vd05HVXhaV1ptSWl3aWMybDZaU0k2SWpFMGZIeHdlQ0lzSW5SemFHRmtiM2NpT2lJd2ZDcDhNSHdxZkRCOEtud3dNREF3TURCbVppSXNJbUZtYjI1MElqb2lUVzl1ZEhObGNuSmhkQ3dnUVhKcFlXd2lMQ0pzYVc1bGFHVnBaMmgwSWpvaU1TNDFJaXdpWW05c1pDSTZNQ3dpYVhSaGJHbGpJam93TENKMWJtUmxjbXhwYm1VaU9qQXNJbUZzYVdkdUlqb2lZMlZ1ZEdWeUlpd2liR1YwZEdWeWMzQmhZMmx1WnlJNkltNXZjbTFoYkNJc0luZHZjbVJ6Y0dGamFXNW5Jam9pYm05eWJXRnNJaXdpZEdWNGRIUnlZVzV6Wm05eWJTSTZJbTV2Ym1VaWZTeDdJbVY0ZEhKaElqb2lJaXdpWTI5c2IzSWlPaUpsTURSbE1XVm1aaUo5WFgwPSJ9LCJzdHlsZSI6eyJzdHlsZSI6ImV5SnVZVzFsSWpvaVUzUmhkR2xqSWl3aVpHRjBZU0k2VzNzaVpYaDBjbUVpT2lJaUxDSmlZV05yWjNKdmRXNWtZMjlzYjNJaU9pSXdNREF3TURBd01DSXNJbkJoWkdScGJtY2lPaUl4Tlh3cWZETXdmQ3A4TVRWOEtud3pNSHdxZkhCNElpd2lZbTk0YzJoaFpHOTNJam9pTUh3cWZEQjhLbnd3ZkNwOE1Id3FmREF3TURBd01HWm1JaXdpWW05eVpHVnlJam9pTW53cWZITnZiR2xrZkNwOFpUQTBaVEZsWm1ZaUxDSmliM0prWlhKeVlXUnBkWE1pT2lJNU9TSjlMSHNpWlhoMGNtRWlPaUlpTENKaVlXTnJaM0p2ZFc1a1kyOXNiM0lpT2lKbVptWm1abVptWmlJc0ltSnZjbVJsY2lJNklqSjhLbnh6YjJ4cFpId3FmR1ptWm1abVptWm1JbjFkZlE9PSJ9fX0='
        ));
        array_push($styles, array(
            'id'    => 1010,
            'value' => 'eyJuYW1lIjoiUGlsbCBNb250c2VycmF0IEdob3N0IFdoaXRlIiwiZGF0YSI6eyJmb250Ijp7ImZvbnQiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0pqYjJ4dmNpSTZJbVptWm1abVptWm1JaXdpYzJsNlpTSTZJakUwZkh4d2VDSXNJblJ6YUdGa2IzY2lPaUl3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltRm1iMjUwSWpvaVRXOXVkSE5sY25KaGRDd2dRWEpwWVd3aUxDSnNhVzVsYUdWcFoyaDBJam9pTVM0MUlpd2lZbTlzWkNJNk1Dd2lhWFJoYkdsaklqb3dMQ0oxYm1SbGNteHBibVVpT2pBc0ltRnNhV2R1SWpvaVkyVnVkR1Z5SWl3aWJHVjBkR1Z5YzNCaFkybHVaeUk2SW01dmNtMWhiQ0lzSW5kdmNtUnpjR0ZqYVc1bklqb2libTl5YldGc0lpd2lkR1Y0ZEhSeVlXNXpabTl5YlNJNkltNXZibVVpZlN4N0ltVjRkSEpoSWpvaUlpd2lZMjlzYjNJaU9pSXpNek16TXpObVppSjlYWDA9In0sInN0eWxlIjp7InN0eWxlIjoiZXlKdVlXMWxJam9pVTNSaGRHbGpJaXdpWkdGMFlTSTZXM3NpWlhoMGNtRWlPaUlpTENKaVlXTnJaM0p2ZFc1a1kyOXNiM0lpT2lJd01EQXdNREF3TUNJc0luQmhaR1JwYm1jaU9pSXhOWHdxZkRNd2ZDcDhNVFY4S253ek1Id3FmSEI0SWl3aVltOTRjMmhoWkc5M0lqb2lNSHdxZkRCOEtud3dmQ3A4TUh3cWZEQXdNREF3TUdabUlpd2lZbTl5WkdWeUlqb2lNbndxZkhOdmJHbGtmQ3A4Wm1abVptWm1abVlpTENKaWIzSmtaWEp5WVdScGRYTWlPaUk1T1NKOUxIc2laWGgwY21FaU9pSWlMQ0ppWVdOclozSnZkVzVrWTI5c2IzSWlPaUptWm1abVptWm1aaUlzSW1KdmNtUmxjaUk2SWpKOEtueHpiMnhwWkh3cWZHWm1abVptWm1abUluMWRmUT09In19fQ=='
        ));
        array_push($styles, array(
            'id'    => 1011,
            'value' => 'eyJuYW1lIjoiUGlsbCBNb250c2VycmF0IEdob3N0IERhcmsiLCJkYXRhIjp7ImZvbnQiOnsiZm9udCI6ImV5SnVZVzFsSWpvaVUzUmhkR2xqSWl3aVpHRjBZU0k2VzNzaVpYaDBjbUVpT2lJaUxDSmpiMnh2Y2lJNklqTXpNek16TTJabUlpd2ljMmw2WlNJNklqRTBmSHh3ZUNJc0luUnphR0ZrYjNjaU9pSXdmQ3A4TUh3cWZEQjhLbnd3TURBd01EQm1aaUlzSW1GbWIyNTBJam9pVFc5dWRITmxjbkpoZEN3Z1FYSnBZV3dpTENKc2FXNWxhR1ZwWjJoMElqb2lNUzQxSWl3aVltOXNaQ0k2TUN3aWFYUmhiR2xqSWpvd0xDSjFibVJsY214cGJtVWlPakFzSW1Gc2FXZHVJam9pWTJWdWRHVnlJaXdpYkdWMGRHVnljM0JoWTJsdVp5STZJbTV2Y20xaGJDSXNJbmR2Y21SemNHRmphVzVuSWpvaWJtOXliV0ZzSWl3aWRHVjRkSFJ5WVc1elptOXliU0k2SW01dmJtVWlmU3g3SW1WNGRISmhJam9pSWl3aVkyOXNiM0lpT2lKbVptWm1abVptWmlKOVhYMD0ifSwic3R5bGUiOnsic3R5bGUiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0ppWVdOclozSnZkVzVrWTI5c2IzSWlPaUl3TURBd01EQXdNQ0lzSW5CaFpHUnBibWNpT2lJeE5Yd3FmRE13ZkNwOE1UVjhLbnd6TUh3cWZIQjRJaXdpWW05NGMyaGhaRzkzSWpvaU1Id3FmREI4S253d2ZDcDhNSHdxZkRBd01EQXdNR1ptSWl3aVltOXlaR1Z5SWpvaU1ud3FmSE52Ykdsa2ZDcDhNek16TXpNelptWWlMQ0ppYjNKa1pYSnlZV1JwZFhNaU9pSTVPU0o5TEhzaVpYaDBjbUVpT2lJaUxDSmlZV05yWjNKdmRXNWtZMjlzYjNJaU9pSXpNek16TXpObVppSXNJbUp2Y21SbGNpSTZJako4S254emIyeHBaSHdxZkRNek16TXpNMlptSW4xZGZRPT0ifX19'
        ));
        array_push($styles, array(
            'id'    => 1012,
            'value' => 'eyJuYW1lIjoiUGlsbCBNb250c2VycmF0IEdyZWVuIiwiZGF0YSI6eyJmb250Ijp7ImZvbnQiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0pqYjJ4dmNpSTZJbVptWm1abVptWm1JaXdpYzJsNlpTSTZJakUwZkh4d2VDSXNJblJ6YUdGa2IzY2lPaUl3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltRm1iMjUwSWpvaVRXOXVkSE5sY25KaGRDd2dRWEpwWVd3aUxDSnNhVzVsYUdWcFoyaDBJam9pTVM0MUlpd2lZbTlzWkNJNk1Dd2lhWFJoYkdsaklqb3dMQ0oxYm1SbGNteHBibVVpT2pBc0ltRnNhV2R1SWpvaVkyVnVkR1Z5SWl3aWJHVjBkR1Z5YzNCaFkybHVaeUk2SW01dmNtMWhiQ0lzSW5kdmNtUnpjR0ZqYVc1bklqb2libTl5YldGc0lpd2lkR1Y0ZEhSeVlXNXpabTl5YlNJNkltNXZibVVpZlN4N0ltVjRkSEpoSWpvaUlpd2lZMjlzYjNJaU9pSm1abVptWm1abVppSjlYWDA9In0sInN0eWxlIjp7InN0eWxlIjoiZXlKdVlXMWxJam9pVTNSaGRHbGpJaXdpWkdGMFlTSTZXM3NpWlhoMGNtRWlPaUlpTENKaVlXTnJaM0p2ZFc1a1kyOXNiM0lpT2lJMVkySmhNMk5tWmlJc0luQmhaR1JwYm1jaU9pSXhOWHdxZkRNd2ZDcDhNVFY4S253ek1Id3FmSEI0SWl3aVltOTRjMmhoWkc5M0lqb2lNSHdxZkRCOEtud3dmQ3A4TUh3cWZEQXdNREF3TUdabUlpd2lZbTl5WkdWeUlqb2lNSHdxZkhOdmJHbGtmQ3A4TURBd01EQXdNREFpTENKaWIzSmtaWEp5WVdScGRYTWlPaUk1T1NKOUxIc2laWGgwY21FaU9pSWlMQ0ppWVdOclozSnZkVzVrWTI5c2IzSWlPaUkxTW1FM016Wm1aaUlzSW1KdmNtUmxjaUk2SWpCOEtueHpiMnhwWkh3cWZEQXdNREF3TURBd0luMWRmUT09In19fQ=='
        ));
        array_push($styles, array(
            'id'    => 1013,
            'value' => 'eyJuYW1lIjoiUGlsbCBNb250c2VycmF0IEJsdWUiLCJkYXRhIjp7ImZvbnQiOnsiZm9udCI6ImV5SnVZVzFsSWpvaVUzUmhkR2xqSWl3aVpHRjBZU0k2VzNzaVpYaDBjbUVpT2lJaUxDSmpiMnh2Y2lJNkltWm1abVptWm1abUlpd2ljMmw2WlNJNklqRTBmSHh3ZUNJc0luUnphR0ZrYjNjaU9pSXdmQ3A4TUh3cWZEQjhLbnd3TURBd01EQm1aaUlzSW1GbWIyNTBJam9pVFc5dWRITmxjbkpoZEN3Z1FYSnBZV3dpTENKc2FXNWxhR1ZwWjJoMElqb2lNUzQxSWl3aVltOXNaQ0k2TUN3aWFYUmhiR2xqSWpvd0xDSjFibVJsY214cGJtVWlPakFzSW1Gc2FXZHVJam9pWTJWdWRHVnlJaXdpYkdWMGRHVnljM0JoWTJsdVp5STZJbTV2Y20xaGJDSXNJbmR2Y21SemNHRmphVzVuSWpvaWJtOXliV0ZzSWl3aWRHVjRkSFJ5WVc1elptOXliU0k2SW01dmJtVWlmU3g3SW1WNGRISmhJam9pSWl3aVkyOXNiM0lpT2lKbVptWm1abVptWmlKOVhYMD0ifSwic3R5bGUiOnsic3R5bGUiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0ppWVdOclozSnZkVzVrWTI5c2IzSWlPaUl3TVdGa1pETm1aaUlzSW5CaFpHUnBibWNpT2lJeE5Yd3FmRE13ZkNwOE1UVjhLbnd6TUh3cWZIQjRJaXdpWW05NGMyaGhaRzkzSWpvaU1Id3FmREI4S253d2ZDcDhNSHdxZkRBd01EQXdNR1ptSWl3aVltOXlaR1Z5SWpvaU1Id3FmSE52Ykdsa2ZDcDhNREF3TURBd01EQWlMQ0ppYjNKa1pYSnlZV1JwZFhNaU9pSTVPU0o5TEhzaVpYaDBjbUVpT2lJaUxDSmlZV05yWjNKdmRXNWtZMjlzYjNJaU9pSXdNVGxpWW1SbVppSXNJbUp2Y21SbGNpSTZJakI4S254emIyeHBaSHdxZkRBd01EQXdNREF3SW4xZGZRPT0ifX19'
        ));
        array_push($styles, array(
            'id'    => 1014,
            'value' => 'eyJuYW1lIjoiUGlsbCBNb250c2VycmF0IFB1cnBsZSIsImRhdGEiOnsiZm9udCI6eyJmb250IjoiZXlKdVlXMWxJam9pVTNSaGRHbGpJaXdpWkdGMFlTSTZXM3NpWlhoMGNtRWlPaUlpTENKamIyeHZjaUk2SW1abVptWm1abVptSWl3aWMybDZaU0k2SWpFMGZIeHdlQ0lzSW5SemFHRmtiM2NpT2lJd2ZDcDhNSHdxZkRCOEtud3dNREF3TURCbVppSXNJbUZtYjI1MElqb2lUVzl1ZEhObGNuSmhkQ3dnUVhKcFlXd2lMQ0pzYVc1bGFHVnBaMmgwSWpvaU1TNDFJaXdpWW05c1pDSTZNQ3dpYVhSaGJHbGpJam93TENKMWJtUmxjbXhwYm1VaU9qQXNJbUZzYVdkdUlqb2lZMlZ1ZEdWeUlpd2liR1YwZEdWeWMzQmhZMmx1WnlJNkltNXZjbTFoYkNJc0luZHZjbVJ6Y0dGamFXNW5Jam9pYm05eWJXRnNJaXdpZEdWNGRIUnlZVzV6Wm05eWJTSTZJbTV2Ym1VaWZTeDdJbVY0ZEhKaElqb2lJaXdpWTI5c2IzSWlPaUptWm1abVptWm1aaUo5WFgwPSJ9LCJzdHlsZSI6eyJzdHlsZSI6ImV5SnVZVzFsSWpvaVUzUmhkR2xqSWl3aVpHRjBZU0k2VzNzaVpYaDBjbUVpT2lJaUxDSmlZV05yWjNKdmRXNWtZMjlzYjNJaU9pSTROelUzWWpKbVppSXNJbkJoWkdScGJtY2lPaUl4Tlh3cWZETXdmQ3A4TVRWOEtud3pNSHdxZkhCNElpd2lZbTk0YzJoaFpHOTNJam9pTUh3cWZEQjhLbnd3ZkNwOE1Id3FmREF3TURBd01HWm1JaXdpWW05eVpHVnlJam9pTUh3cWZITnZiR2xrZkNwOE1EQXdNREF3TURBaUxDSmliM0prWlhKeVlXUnBkWE1pT2lJNU9TSjlMSHNpWlhoMGNtRWlPaUlpTENKaVlXTnJaM0p2ZFc1a1kyOXNiM0lpT2lJM09UUmxZVEJtWmlJc0ltSnZjbVJsY2lJNklqQjhLbnh6YjJ4cFpId3FmREF3TURBd01EQXdJbjFkZlE9PSJ9fX0='
        ));
    }

    public function itemtextStorage(&$styles) {
        array_push($styles, array(
            'id'    => 1001,
            'value' => 'eyJuYW1lIjoiUmFsZXdheSBCaWcgQ2VudGVyIEFsaWduZWQiLCJkYXRhIjp7ImZvbnQiOnsiZm9udCI6ImV5SnVZVzFsSWpvaVUzUmhkR2xqSWl3aVpHRjBZU0k2VzNzaVpYaDBjbUVpT2lJaUxDSmpiMnh2Y2lJNklqZGtOMlEzWkdabUlpd2ljMmw2WlNJNklqRTRmSHh3ZUNJc0luUnphR0ZrYjNjaU9pSXdmQ3A4TUh3cWZEQjhLbnd3TURBd01EQm1aaUlzSW1GbWIyNTBJam9pVW1Gc1pYZGhlU3dnUVhKcFlXd2lMQ0pzYVc1bGFHVnBaMmgwSWpvaU1TNDFJaXdpWW05c1pDSTZNQ3dpYVhSaGJHbGpJam93TENKMWJtUmxjbXhwYm1VaU9qQXNJbUZzYVdkdUlqb2lZMlZ1ZEdWeUlpd2liR1YwZEdWeWMzQmhZMmx1WnlJNkltNXZjbTFoYkNJc0luZHZjbVJ6Y0dGamFXNW5Jam9pYm05eWJXRnNJaXdpZEdWNGRIUnlZVzV6Wm05eWJTSTZJbTV2Ym1VaWZTeDdJbVY0ZEhKaElqb2lJaXdpWTI5c2IzSWlPaUl4T0Rrd1pEZG1aaUo5TEhzaVpYaDBjbUVpT2lJaWZWMTkifSwic3R5bGUiOnsic3R5bGUiOiIifX19'
        ));
        array_push($styles, array(
            'id'    => 1002,
            'value' => 'eyJuYW1lIjoiUmFsZXdheSBMaWdodCBMZWZ0IEFsaWduZWQiLCJkYXRhIjp7ImZvbnQiOnsiZm9udCI6ImV5SnVZVzFsSWpvaVUzUmhkR2xqSWl3aVpHRjBZU0k2VzNzaVpYaDBjbUVpT2lJaUxDSmpiMnh2Y2lJNkltWm1abVptWm1abUlpd2ljMmw2WlNJNklqRTBmSHh3ZUNJc0luUnphR0ZrYjNjaU9pSXdmQ3A4TUh3cWZEQjhLbnd3TURBd01EQm1aaUlzSW1GbWIyNTBJam9pVW1Gc1pYZGhlU3dnUVhKcFlXd2lMQ0pzYVc1bGFHVnBaMmgwSWpvaU1TNDFJaXdpWW05c1pDSTZNQ3dpYVhSaGJHbGpJam93TENKMWJtUmxjbXhwYm1VaU9qQXNJbUZzYVdkdUlqb2liR1ZtZENJc0lteGxkSFJsY25Od1lXTnBibWNpT2lKdWIzSnRZV3dpTENKM2IzSmtjM0JoWTJsdVp5STZJbTV2Y20xaGJDSXNJblJsZUhSMGNtRnVjMlp2Y20waU9pSnViMjVsSW4wc2V5SmxlSFJ5WVNJNklpSXNJbU52Ykc5eUlqb2lNVGc1TUdRM1ptWWlmU3g3SW1WNGRISmhJam9pSW4xZGZRPT0ifSwic3R5bGUiOnsic3R5bGUiOiIifX19'
        ));
        array_push($styles, array(
            'id'    => 1003,
            'value' => 'eyJuYW1lIjoiUmFsZXdheSBEYXJrIExlZnQgQWxpZ25lZCIsImRhdGEiOnsiZm9udCI6eyJmb250IjoiZXlKdVlXMWxJam9pVTNSaGRHbGpJaXdpWkdGMFlTSTZXM3NpWlhoMGNtRWlPaUlpTENKamIyeHZjaUk2SWpNek16TXpNMlptSWl3aWMybDZaU0k2SWpFMGZIeHdlQ0lzSW5SemFHRmtiM2NpT2lJd2ZDcDhNSHdxZkRCOEtud3dNREF3TURCbVppSXNJbUZtYjI1MElqb2lVbUZzWlhkaGVTd2dRWEpwWVd3aUxDSnNhVzVsYUdWcFoyaDBJam9pTVM0MUlpd2lZbTlzWkNJNk1Dd2lhWFJoYkdsaklqb3dMQ0oxYm1SbGNteHBibVVpT2pBc0ltRnNhV2R1SWpvaWJHVm1kQ0lzSW14bGRIUmxjbk53WVdOcGJtY2lPaUp1YjNKdFlXd2lMQ0ozYjNKa2MzQmhZMmx1WnlJNkltNXZjbTFoYkNJc0luUmxlSFIwY21GdWMyWnZjbTBpT2lKdWIyNWxJbjBzZXlKbGVIUnlZU0k2SWlJc0ltTnZiRzl5SWpvaU1UZzVNR1EzWm1ZaWZTeDdJbVY0ZEhKaElqb2lJbjFkZlE9PSJ9LCJzdHlsZSI6eyJzdHlsZSI6IiJ9fX0='
        ));
        array_push($styles, array(
            'id'    => 1004,
            'value' => 'eyJuYW1lIjoiTW9udHNlcnJhdCBEYXJrIExlZnQgQWxpZ25lZCIsImRhdGEiOnsiZm9udCI6eyJmb250IjoiZXlKdVlXMWxJam9pVTNSaGRHbGpJaXdpWkdGMFlTSTZXM3NpWlhoMGNtRWlPaUlpTENKamIyeHZjaUk2SWpNek16TXpNMlptSWl3aWMybDZaU0k2SWpFMGZIeHdlQ0lzSW5SemFHRmtiM2NpT2lJd2ZDcDhNSHdxZkRCOEtud3dNREF3TURCbVppSXNJbUZtYjI1MElqb2lUVzl1ZEhObGNuSmhkQ3dnUVhKcFlXd2lMQ0pzYVc1bGFHVnBaMmgwSWpvaU1TNDFJaXdpWW05c1pDSTZNQ3dpYVhSaGJHbGpJam93TENKMWJtUmxjbXhwYm1VaU9qQXNJbUZzYVdkdUlqb2liR1ZtZENJc0lteGxkSFJsY25Od1lXTnBibWNpT2lKdWIzSnRZV3dpTENKM2IzSmtjM0JoWTJsdVp5STZJbTV2Y20xaGJDSXNJblJsZUhSMGNtRnVjMlp2Y20waU9pSnViMjVsSW4wc2V5SmxlSFJ5WVNJNklpSXNJbU52Ykc5eUlqb2lNVGc1TUdRM1ptWWlmU3g3SW1WNGRISmhJam9pSW4xZGZRPT0ifSwic3R5bGUiOnsic3R5bGUiOiIifX19'
        ));
        array_push($styles, array(
            'id'    => 1005,
            'value' => 'eyJuYW1lIjoiTW9udHNlcnJhdCBMaWdodCBMZWZ0IEFsaWduZWQiLCJkYXRhIjp7ImZvbnQiOnsiZm9udCI6ImV5SnVZVzFsSWpvaVUzUmhkR2xqSWl3aVpHRjBZU0k2VzNzaVpYaDBjbUVpT2lJaUxDSmpiMnh2Y2lJNkltWm1abVptWm1abUlpd2ljMmw2WlNJNklqRTBmSHh3ZUNJc0luUnphR0ZrYjNjaU9pSXdmQ3A4TUh3cWZEQjhLbnd3TURBd01EQm1aaUlzSW1GbWIyNTBJam9pVFc5dWRITmxjbkpoZEN3Z1FYSnBZV3dpTENKc2FXNWxhR1ZwWjJoMElqb2lNUzQxSWl3aVltOXNaQ0k2TUN3aWFYUmhiR2xqSWpvd0xDSjFibVJsY214cGJtVWlPakFzSW1Gc2FXZHVJam9pYkdWbWRDSXNJbXhsZEhSbGNuTndZV05wYm1jaU9pSnViM0p0WVd3aUxDSjNiM0prYzNCaFkybHVaeUk2SW01dmNtMWhiQ0lzSW5SbGVIUjBjbUZ1YzJadmNtMGlPaUp1YjI1bEluMHNleUpsZUhSeVlTSTZJaUlzSW1OdmJHOXlJam9pTVRnNU1HUTNabVlpZlN4N0ltVjRkSEpoSWpvaUluMWRmUT09In0sInN0eWxlIjp7InN0eWxlIjoiIn19fQ=='
        ));
        array_push($styles, array(
            'id'    => 1006,
            'value' => 'eyJuYW1lIjoiTW9udHNlcnJhdCBEYXJrIENlbnRlciBBbGlnbmVkIiwiZGF0YSI6eyJmb250Ijp7ImZvbnQiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0pqYjJ4dmNpSTZJak16TXpNek0yWm1JaXdpYzJsNlpTSTZJakUwZkh4d2VDSXNJblJ6YUdGa2IzY2lPaUl3ZkNwOE1Id3FmREI4S253d01EQXdNREJtWmlJc0ltRm1iMjUwSWpvaVRXOXVkSE5sY25KaGRDd2dRWEpwWVd3aUxDSnNhVzVsYUdWcFoyaDBJam9pTVM0MUlpd2lZbTlzWkNJNk1Dd2lhWFJoYkdsaklqb3dMQ0oxYm1SbGNteHBibVVpT2pBc0ltRnNhV2R1SWpvaVkyVnVkR1Z5SWl3aWJHVjBkR1Z5YzNCaFkybHVaeUk2SW01dmNtMWhiQ0lzSW5kdmNtUnpjR0ZqYVc1bklqb2libTl5YldGc0lpd2lkR1Y0ZEhSeVlXNXpabTl5YlNJNkltNXZibVVpZlN4N0ltVjRkSEpoSWpvaUlpd2lZMjlzYjNJaU9pSXhPRGt3WkRkbVppSjlMSHNpWlhoMGNtRWlPaUlpZlYxOSJ9LCJzdHlsZSI6eyJzdHlsZSI6IiJ9fX0='
        ));
        array_push($styles, array(
            'id'    => 1007,
            'value' => 'eyJuYW1lIjoiTW9udHNlcnJhdCBMaWdodCBDZW50ZXIgQWxpZ25lZCIsImRhdGEiOnsiZm9udCI6eyJmb250IjoiZXlKdVlXMWxJam9pVTNSaGRHbGpJaXdpWkdGMFlTSTZXM3NpWlhoMGNtRWlPaUlpTENKamIyeHZjaUk2SW1abVptWm1abVptSWl3aWMybDZaU0k2SWpFMGZIeHdlQ0lzSW5SemFHRmtiM2NpT2lJd2ZDcDhNSHdxZkRCOEtud3dNREF3TURCbVppSXNJbUZtYjI1MElqb2lUVzl1ZEhObGNuSmhkQ3dnUVhKcFlXd2lMQ0pzYVc1bGFHVnBaMmgwSWpvaU1TNDFJaXdpWW05c1pDSTZNQ3dpYVhSaGJHbGpJam93TENKMWJtUmxjbXhwYm1VaU9qQXNJbUZzYVdkdUlqb2lZMlZ1ZEdWeUlpd2liR1YwZEdWeWMzQmhZMmx1WnlJNkltNXZjbTFoYkNJc0luZHZjbVJ6Y0dGamFXNW5Jam9pYm05eWJXRnNJaXdpZEdWNGRIUnlZVzV6Wm05eWJTSTZJbTV2Ym1VaWZTeDdJbVY0ZEhKaElqb2lJaXdpWTI5c2IzSWlPaUl4T0Rrd1pEZG1aaUo5TEhzaVpYaDBjbUVpT2lJaWZWMTkifSwic3R5bGUiOnsic3R5bGUiOiIifX19'
        ));
        //array_push($styles, );
    }

    public function itemiconStorage(&$styles) {

        array_push($styles, array(
            'id'    => 1000,
            'value' => 'eyJuYW1lIjoiUm91bmRlZCBXaGl0ZSBCYWNrZ3JvdW5kIiwiZGF0YSI6eyJmb250Ijp7fSwic3R5bGUiOnsic3R5bGUiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0ppWVdOclozSnZkVzVrWTI5c2IzSWlPaUptWm1abVptWm1aaUlzSW5CaFpHUnBibWNpT2lJeE1Id3FmREV3ZkNwOE1UQjhLbnd4TUh3cWZIQjRJaXdpWW05NGMyaGhaRzkzSWpvaU1Id3FmREI4S253d2ZDcDhNSHdxZkRBd01EQXdNR1ptSWl3aVltOXlaR1Z5SWpvaU1Id3FmSE52Ykdsa2ZDcDhNREF3TURBd1ptWWlMQ0ppYjNKa1pYSnlZV1JwZFhNaU9pSXpJbjBzZXlKbGVIUnlZU0k2SWlKOVhYMD0ifX19'
        ));
        array_push($styles, array(
            'id'    => 1001,
            'value' => 'eyJuYW1lIjoiQ2lyY2xlIFdoaXRlIEJhY2tncm91bmQiLCJkYXRhIjp7ImZvbnQiOnt9LCJzdHlsZSI6eyJzdHlsZSI6ImV5SnVZVzFsSWpvaVUzUmhkR2xqSWl3aVpHRjBZU0k2VzNzaVpYaDBjbUVpT2lJaUxDSmlZV05yWjNKdmRXNWtZMjlzYjNJaU9pSm1abVptWm1abVppSXNJbkJoWkdScGJtY2lPaUl4TUh3cWZERXdmQ3A4TVRCOEtud3hNSHdxZkhCNElpd2lZbTk0YzJoaFpHOTNJam9pTUh3cWZEQjhLbnd3ZkNwOE1Id3FmREF3TURBd01HWm1JaXdpWW05eVpHVnlJam9pTUh3cWZITnZiR2xrZkNwOE1EQXdNREF3Wm1ZaUxDSmliM0prWlhKeVlXUnBkWE1pT2lJNU9TSjlMSHNpWlhoMGNtRWlPaUlpZlYxOSJ9fX0='
        ));
        array_push($styles, array(
            'id'    => 1002,
            'value' => 'eyJuYW1lIjoiQ2lyY2xlIEdob3N0IFdoaXRlIiwiZGF0YSI6eyJmb250Ijp7fSwic3R5bGUiOnsic3R5bGUiOiJleUp1WVcxbElqb2lVM1JoZEdsaklpd2laR0YwWVNJNlczc2laWGgwY21FaU9pSWlMQ0ppWVdOclozSnZkVzVrWTI5c2IzSWlPaUl3TURBd01EQXdNQ0lzSW5CaFpHUnBibWNpT2lJeE1Id3FmREV3ZkNwOE1UQjhLbnd4TUh3cWZIQjRJaXdpWW05NGMyaGhaRzkzSWpvaU1Id3FmREI4S253d2ZDcDhNSHdxZkRBd01EQXdNR1ptSWl3aVltOXlaR1Z5SWpvaU1ud3FmSE52Ykdsa2ZDcDhabVptWm1abVptWWlMQ0ppYjNKa1pYSnlZV1JwZFhNaU9pSTVPU0o5TEhzaVpYaDBjbUVpT2lJaWZWMTkifX19'
        ));
    }
}