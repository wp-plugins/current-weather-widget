<?php
/*
Plugin Name: Current weather widget
Plugin URI: http://wordpress.org/extend/plugins/my-weather/
Description: Sidebar widget that displays the current weather for a given city.
Version: 0.0.1
Author: Jeffrey Barke
Author URI: http://jeffreybarke.net/
License: MIT
*/

class Current_Weather_Widget extends WP_Widget {

	const NAME = 'Current weather widget';
	const SLUG = 'current-weather-widget';
	const LOCALE = 'current-weather-widget-locale';
	const VERSION = '0.0.1';

	public function __construct() {

		load_plugin_textdomain( self::LOCALE, false,
			basename( dirname( __FILE__ ) ) . '/languages' ); 

		$widget_opts = array (
			'classname' => self::SLUG,
			'description' => __( 'Display the weather!', self::LOCALE ),
		);

		$this->WP_Widget( self::SLUG, __( self::NAME, self::LOCALE ),
			$widget_opts );

		// Is the widget loaded on the page? If so, add the stylesheet to
		// the head element.
		// @todo: Fix. While this loads the CSS in the head, unfortunately,
		// it also loads it in the admin as well, which I don't wnat.
		if ( is_active_widget ( false, false, $this->id_base, true ) ) {
			wp_register_style( self::SLUG . '-style', plugins_url( 'css/' .
				self::SLUG . '.css', __FILE__), array(), self::VERSION );
			wp_enqueue_style( self::SLUG . '-style' );
		}

	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	function widget( $args, $instance ) {

		// Make sure we haz jQuery and then load our widget script.
		// This loads it in the footer, but that is good.
		wp_enqueue_script( 'jquery' );
		wp_register_script( self::SLUG . '-script', plugins_url( 'js/' .
		 	self::SLUG . '.js', __FILE__), array(), self::VERSION );
		wp_enqueue_script( self::SLUG . '-script' );

		extract( $args, EXTR_SKIP );

		echo $before_widget;

		$title = apply_filters( 'widget_title', $instance['title'] );
		$city = $instance['city'];
		$country = $instance['country'];
		$weather_date = date( get_option ( 'date_format' ) . ', ' .
			get_option ( 'time_format' ),
			time() + ( get_option ( 'gmt_offset' ) * 60 * 60 ) );
		$units = $instance['units'];
		$lang = $instance['lang'];

		// Display the widget
		include( plugin_dir_path( __FILE__ ) . 'views/widget.php' );

		echo $after_widget;

	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	function form( $instance ) {
		// Fold defaults into previously saved values
		$instance = wp_parse_args( (array) $instance, array(
			'title' => '',
			'city' => '',
			'country' => 'US',
			'units' => '',
			'lang' => 'en',
		) );
		$title = $instance['title'];
		$city = $instance['city'];
		$country = $instance['country'];
		// Include country list to keep this file more maintainable.
		include( plugin_dir_path( __FILE__ ) . 'includes/countries.php' );
		$units = array( '', '');
		if ( 'imperial' === $instance['units'] ) {
			$units[0] = 'checked ';
		} elseif ( 'metric' === $instance['units'] ) {
			$units[1] = 'checked ';
		}
		// So far, I think the service only supports English.
		// @todo: Keep an eye on this. Possible set from locale settings.
		$lang = 'en';
		// Display the admin form
		include( plugin_dir_path( __FILE__ ) . 'views/admin.php' );
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( trim( $new_instance['title'] ) );
		$instance['city'] = strip_tags( trim( $new_instance['city'] ) );
		$instance['country'] = $new_instance['country'];
		$instance['units'] = $new_instance['units'];
		$instance['lang'] = $new_instance['lang'];
		return $instance;
	}

}

add_action( 'widgets_init', create_function( '',
	'register_widget("Current_Weather_Widget");' ) );