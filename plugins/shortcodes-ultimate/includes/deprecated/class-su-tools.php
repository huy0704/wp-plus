<?php

if ( ! class_exists( 'Su_Tools' ) ) {

	/**
	 *
	 *
	 * @deprecated 5.0.5
	 */
	class Su_Tools {

		/**
		 *
		 *
		 * @deprecated 5.0.5
		 */
		public static function get_icon( $args ) {

			if ( is_string( $args ) ) {
				$args = array( 'icon' => $args );
			}

			$args = wp_parse_args( $args, array(
					'icon' => '',
					'size' => '',
					'color' => '',
					'style' => ''
				) );

			if ( ! $args['icon'] ) {
				return;
			}

			if ( $args['style'] ) {
				$args['style'] = rtrim( $args['style'], ';' ) . ';';
			}

			// Font Awesome icon
			if ( strpos( $args['icon'], 'icon:' ) !== false ) {

				if ( $args['size'] ) {
					$args['style'] .= 'font-size:' . $args['size'] . 'px;';
				}

				if ( $args['color'] ) {
					$args['style'] .= 'color:' . $args['color'] . ';';
				}

				su_query_asset( 'css', 'su-icons' );

				return '<i class="sui sui-' . trim( str_replace( 'icon:', '', $args['icon'] ) ) . '" style="' . $args['style'] . '"></i>';

			}

			// Image icon
			elseif ( strpos( $args['icon'], '/' ) !== false ) {

				if ( $args['size'] ) {
					$args['style'] .= 'width:' . $args['size'] . 'px;height:' . $args['size'] . 'px;';
				}

				return '<img src="' . $args['icon'] . '" alt="" style="' . $args['style'] . '" />';

			}

			return false;

		}

		/**
		 *
		 *
		 * @deprecated 5.0.5
		 */
		public static function do_attr( $value ) {
			return do_shortcode( str_replace( array( '{', '}' ), array( '[', ']' ), $value ) );
		}

	}

}
