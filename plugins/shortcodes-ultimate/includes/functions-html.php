<?php

/**
 * Simple helper to create icon markup.
 *
 * @since  5.0.5
 * @param string  $src FA icon name or image URL.
 * @return string      HTML markup, <i> or <img> tag.
 */
function su_html_icon( $args ) {

	if ( is_string( $args ) ) {
		$args = array( 'icon' => $args );
	}

	$args = wp_parse_args(
		$args,
		array(
			'icon'       => '',
			'size'       => '',
			'color'      => '',
			'style'      => '',
			'alt'        => '',
			'enqueue-fa' => false,
		)
	);

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

		if ( $args['enqueue-fa'] ) {
			su_query_asset( 'css', 'su-icons' );
		}

		return '<i class="sui sui-' . trim( str_replace( 'icon:', '', $args['icon'] ) ) . '" style="' . $args['style'] . '" aria-label="' . $args['alt'] . '"></i>';

	}

	// Image icon
	if ( strpos( $args['icon'], '/' ) !== false ) {

		if ( $args['size'] ) {
			$args['style'] .= 'width:' . $args['size'] . 'px;height:' . $args['size'] . 'px;';
		}

		return '<img src="' . $args['icon'] . '" alt="' . $args['alt'] . '" style="' . $args['style'] . '" />';

	}

	return false;

}

/**
 * Create HTML dropdown.
 *
 * @since  5.0.5
 * @param array   $args Args.
 * @return string       Dropdown markup.
 */
function su_html_dropdown( $args ) {

	$args = wp_parse_args(
		$args,
		array(
			'id'       => '',
			'name'     => '',
			'class'    => '',
			'multiple' => '',
			'size'     => '',
			'disabled' => '',
			'selected' => '',
			'none'     => '',
			'options'  => array(),
			'style'    => '',
			'noselect' => '',
		)
	);

	$options = array();

	if ( ! is_array( $args['options'] ) ) {
		$args['options'] = array();
	}
	if ( $args['id'] ) {
		$args['id'] = ' id="' . $args['id'] . '"';
	}
	if ( $args['name'] ) {
		$args['name'] = ' name="' . $args['name'] . '"';
	}
	if ( $args['class'] ) {
		$args['class'] = ' class="' . $args['class'] . '"';
	}
	if ( $args['style'] ) {
		$args['style'] = ' style="' . esc_attr( $args['style'] ) . '"';
	}
	if ( $args['multiple'] ) {
		$args['multiple'] = ' multiple="multiple"';
	}
	if ( $args['disabled'] ) {
		$args['disabled'] = ' disabled="disabled"';
	}
	if ( $args['size'] ) {
		$args['size'] = ' size="' . $args['size'] . '"';
	}
	if ( $args['none'] ) {
		$args['options'][0] = $args['none'];
	}

	foreach ( $args['options'] as $id => $text ) {
		$options[] = '<option value="' . (string) $id . '">' . (string) $text . '</option>';
	}

	$options = implode( '', $options );
	$options = str_replace(
		'value="' . $args['selected'] . '"',
		'value="' . $args['selected'] . '" selected="selected"',
		$options
	);

	return $args['noselect']
		? $options :
		'<select' . $args['id'] . $args['name'] . $args['class'] . $args['multiple'] . $args['size'] . $args['disabled'] . $args['style'] . '>' . $options . '</select>';

}

/**
 * Create a HTML table from a CSV string.
 *
 * @since 5.3.0
 * @param  string $csv       CSV input.
 * @param  string $delimiter Column delimiter.
 * @return string            HTML output.
 */
function su_csv_to_html( $csv, $delimiter = ',', $header = false ) {

	if ( ! is_string( $csv ) ) {
		return '';
	}

	if ( ! function_exists( 'str_getcsv' ) ) {
		return $csv;
	}

	$html = '';
	$rows = explode( PHP_EOL, $csv );

	foreach ( $rows as $row ) {

		$html .= '<tr>';

		foreach ( str_getcsv( $row, $delimiter ) as $cell ) {

			$cell = trim( $cell );

			$html .= $header
				? '<th>' . $cell . '</th>'
				: '<td>' . $cell . '</td>';

		}

		$html .= '</tr>';

		$header = false;

	}

	return '<table>' . $html . '</table>';

}

/**
 * Convert array with CSS rules into a style attribute (escaped).
 *
 * Expected input:
 *
 *   [ 'margin: 5px', 'color: red' ]
 *
 * Output:
 *
 *   ' style="margin: 5px;color: red"'
 *
 * @since 5.8.1
 * @param  array $rules Array with CSS rules.
 * @return string        Formatted style attribute.
 */
function su_html_style( $rules ) {

	return ! empty( $rules )
		? ' style="' . esc_attr( implode( ';', $rules ) ) . '"'
		: '';

}
