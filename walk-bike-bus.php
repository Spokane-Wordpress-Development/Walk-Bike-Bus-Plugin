<?php

/**
 * Plugin Name: Walk Bike Bus
 * Plugin URI: http://www.walkbikebus.org
 * Description: Custom plugin for the Walk Bike Bus website.
 * Version: 1.0.0
 * Author: Tony DeStefano
 * Author URI: http://www.walkbikebus.org
 * Text Domain:
 * Domain Path:
 * Network:
 * License: GPL2
 */

require_once( 'classes/Controller.php' );
require_once( 'classes/User.php' );
require_once( 'classes/Neighborhood.php' );
require_once( 'classes/Trip.php' );

$wbb = new \WalkBikeBus\Controller;
$wbb_user = new \WalkBikeBus\User;
$wbb_neighborhood = new \WalkBikeBus\Neighborhood;

add_action( 'init', array( $wbb, 'init' ) );
add_action( 'init', array( $wbb, 'create_post_types' ) );

if ( is_admin() )
{
	add_action( 'admin_init', array( $wbb, 'admin_init' ) );
	add_action( 'admin_init', array( $wbb, 'register_settings' ) );
	add_action( 'admin_menu', array( $wbb, 'add_menus') );

	/**
	 * Custom user meta
	 */
	add_action( 'show_user_profile', array( $wbb_user, 'extra_profile_fields' ) );
	add_action( 'edit_user_profile', array( $wbb_user, 'extra_profile_fields' ) );
	add_action( 'personal_options_update', array( $wbb_user, 'save_extra_profile_fields' ) );
	add_action( 'edit_user_profile_update', array( $wbb_user, 'save_extra_profile_fields' ) );

	/**
	 * Neighborhood additions
	 */
	add_action( 'admin_init', array( $wbb_neighborhood, 'extra_neighborhood_meta' ) );
	add_action( 'save_post', array( $wbb_neighborhood, 'save_neighborhood_post' ) );
	add_filter( 'manage_wbb_neighborhood_posts_columns', array( $wbb_neighborhood, 'add_new_columns' ) );
	add_action( 'manage_posts_custom_column' , array( $wbb_neighborhood, 'custom_columns' ) );

	/**
	 * Public 'fake' page
	 */
	add_filter( 'query_vars', array( $wbb, 'query_vars') );
}