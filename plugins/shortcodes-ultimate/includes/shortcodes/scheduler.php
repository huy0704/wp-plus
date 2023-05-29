<?php

su_add_shortcode(
	array(
		'id'       => 'scheduler',
		'callback' => 'su_shortcode_scheduler',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/scheduler.svg',
		'name'     => __( 'Scheduler', 'shortcodes-ultimate' ),
		'type'     => 'wrap',
		'group'    => 'other',
		'atts'     => array(
			'time'       => array(
				'default' => '',
				'name'    => __( 'Time', 'shortcodes-ultimate' ),
				'desc'    => sprintf( __( 'In this field you can specify one or more time ranges. Every day at this time the content of shortcode will be visible. %1$s %2$s %3$s - show content from 9:00 to 18:00 %4$s - show content from 9:00 to 13:00 and from 14:00 to 18:00 %5$s - example with minutes (content will be visible each day, 45 minutes) %6$s - example with seconds', 'shortcodes-ultimate' ), '<br><br>', __( 'Examples (click to set)', 'shortcodes-ultimate' ), '<br><b%value>9-18</b>', '<br><b%value>9-13, 14-18</b>', '<br><b%value>9:30-10:15</b>', '<br><b%value>9:00:00-17:59:59</b>' ),
			),
			'days_week'  => array(
				'default' => '',
				'name'    => __( 'Days of the week', 'shortcodes-ultimate' ),
				'desc'    => sprintf( __( 'In this field you can specify one or more days of the week. Every week at these days the content of shortcode will be visible. %1$s 0 - Sunday %2$s 1 - Monday %3$s 2 - Tuesday %4$s 3 - Wednesday %5$s 4 - Thursday %6$s 5 - Friday %7$s 6 - Saturday %8$s %9$s %10$s - show content from Monday to Friday %11$s - show content only at Sunday %12$s - show content at Sunday and from Wednesday to Friday', 'shortcodes-ultimate' ), '<br><br>', '<br>', '<br>', '<br>', '<br>', '<br>', '<br>', '<br><br>', __( 'Examples (click to set)', 'shortcodes-ultimate' ), '<br><b%value>1-5</b>', '<br><b%value>0</b>', '<br><b%value>0, 3-5</b>' ),
			),
			'days_month' => array(
				'default' => '',
				'name'    => __( 'Days of the month', 'shortcodes-ultimate' ),
				'desc'    => sprintf( __( 'In this field you can specify one or more days of the month. Every month at these days the content of shortcode will be visible. %1$s %2$s %3$s - show content only at first day of month %4$s - show content from 1th to 5th %5$s - show content from 10th to 15th and from 20th to 25th', 'shortcodes-ultimate' ), '<br><br>', __( 'Examples (click to set)', 'shortcodes-ultimate' ), '<br><b%value>1</b>', '<br><b%value>1-5</b>', '<br><b%value>10-15, 20-25</b>' ),
			),
			'months'     => array(
				'default' => '',
				'name'    => __( 'Months', 'shortcodes-ultimate' ),
				'desc'    => sprintf( __( 'In this field you can specify the month or months in which the content will be visible. %1$s %2$s %3$s - show content only in January %4$s - show content from February to June %5$s - show content in January, March and from May to July', 'shortcodes-ultimate' ), '<br><br>', __( 'Examples (click to set)', 'shortcodes-ultimate' ), '<br><b%value>1</b>', '<br><b%value>2-6</b>', '<br><b%value>1, 3, 5-7</b>' ),
			),
			'years'      => array(
				'default' => '',
				'name'    => __( 'Years', 'shortcodes-ultimate' ),
				'desc'    => sprintf( __( 'In this field you can specify the year or years in which the content will be visible. %1$s %2$s %3$s - show content only in 2014 %4$s - show content from 2014 to 2016 %5$s - show content in 2014, 2018 and from 2020 to 2022', 'shortcodes-ultimate' ), '<br><br>', __( 'Examples (click to set)', 'shortcodes-ultimate' ), '<br><b%value>2014</b>', '<br><b%value>2014-2016</b>', '<br><b%value>2014, 2018, 2020-2022</b>' ),
			),
			'alt'        => array(
				'default' => '',
				'name'    => __( 'Alternative text', 'shortcodes-ultimate' ),
				'desc'    => __( 'In this field you can type the text which will be shown if content is not visible at the current moment', 'shortcodes-ultimate' ),
			),
		),
		'content'  => __( 'Scheduled content', 'shortcodes-ultimate' ),
		'desc'     => __( 'Allows to show the content only at the specified time period', 'shortcodes-ultimate' ),
		'note'     => __( 'This shortcode allows you to show content only at the specified time.', 'shortcodes-ultimate' ) . '<br><br>' . __( 'Please pay special attention to the descriptions, which are located below each text field. It will save you a lot of time', 'shortcodes-ultimate' ) . '<br><br>' . __( 'By default, the content of this shortcode will be visible all the time. By using fields below, you can add some limitations. For example, if you type 1-5 in the Days of the week field, content will be only shown from Monday to Friday. Using the same principles, you can limit content visibility from years to seconds.', 'shortcodes-ultimate' ),
		'icon'     => 'clock-o',
	)
);

function su_shortcode_scheduler( $atts = null, $content = null ) {

	$atts = shortcode_atts(
		array(
			'time'       => 'all',
			'days_week'  => 'all',
			'days_month' => 'all',
			'months'     => 'all',
			'years'      => 'all',
			'alt'        => '',
		),
		$atts,
		'scheduler'
	);

	$timestamp = current_time( 'timestamp', 0 );
	$now       = array(
		'time'      => $timestamp,
		'day_week'  => (int) date( 'w', $timestamp ),
		'day_month' => (int) date( 'j', $timestamp ),
		'month'     => (int) date( 'n', $timestamp ),
		'year'      => (int) date( 'Y', $timestamp ),
	);

	if ( 'all' !== $atts['years'] ) {

		$atts['years'] = preg_replace( '/[^0-9-,]/', '', $atts['years'] );

		if ( ! in_array( $now['year'], su_parse_range( $atts['years'] ), true ) ) {
			return su_do_attribute( $atts['alt'] );
		}

	}

	if ( 'all' !== $atts['months'] ) {

		$atts['months'] = preg_replace( '/[^0-9-,]/', '', $atts['months'] );

		if ( ! in_array( $now['month'], su_parse_range( $atts['months'] ), true ) ) {
			return su_do_attribute( $atts['alt'] );
		}

	}

	if ( 'all' !== $atts['days_month'] ) {

		$atts['days_month'] = preg_replace( '/[^0-9-,]/', '', $atts['days_month'] );

		if ( ! in_array( $now['day_month'], su_parse_range( $atts['days_month'] ), true ) ) {
			return su_do_attribute( $atts['alt'] );
		}

	}

	if ( 'all' !== $atts['days_week'] ) {

		$atts['days_week'] = preg_replace( '/[^0-9-,]/', '', $atts['days_week'] );

		if ( ! in_array( $now['day_week'], su_parse_range( $atts['days_week'] ), true ) ) {
			return su_do_attribute( $atts['alt'] );
		}

	}

	if ( 'all' !== $atts['time'] ) {

		$valid_time   = false;
		$atts['time'] = preg_replace( '/[^0-9-,:]/', '', $atts['time'] );

		foreach ( explode( ',', $atts['time'] ) as $range ) {

			$range = explode( '-', $range );

			if ( ! isset( $range[1] ) ) {
				$range[1] = $range[0] . ':59:59';
			}

			if ( strpos( $range[0], ':' ) === false ) {
				$range[0] .= ':00:00';
			}
			if ( strpos( $range[1], ':' ) === false ) {
				$range[1] .= ':00:00';
			}

			if (
				$now['time'] >= strtotime( $range[0], $now['time'] ) &&
				$now['time'] <= strtotime( $range[1], $now['time'] )
			) {
				$valid_time = true;
				break;
			}

		}

		if ( ! $valid_time ) {
			return su_do_attribute( $atts['alt'] );
		}

	}

	return do_shortcode( $content );

}
