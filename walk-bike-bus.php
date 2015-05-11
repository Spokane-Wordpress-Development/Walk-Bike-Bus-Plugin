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
require_once( 'classes/Commute.php' );

$wbb = new \WalkBikeBus\Controller;
$wbb_user = new \WalkBikeBus\User;

add_action( 'init', array( $wbb, 'init' ) );

if ( is_admin() )
{
	add_action( 'init', array( $wbb, 'create_post_types' ) );
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
}