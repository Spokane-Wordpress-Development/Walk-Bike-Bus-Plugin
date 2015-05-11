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

require_once( plugin_dir_path( __FILE__ ) . 'classes/Controller.php' );
require_once( plugin_dir_path( __FILE__ ) . 'classes/User.php' );
require_once( plugin_dir_path( __FILE__ ) . 'classes/Commute.php' );

add_action( 'init', array( '\WalkBikeBus\Controller', 'init' ) );

if ( is_admin() )
{
	add_action( 'init', array( '\WalkBikeBus\Controller', 'admin_init' ) );
}