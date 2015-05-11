<?php

namespace WalkBikeBus;

class Controller {

	public function init()
	{
		if (get_option('wbb_show_banners') == 1)
		{
			if ( ! is_admin() )
			{
				wp_enqueue_style( 'wbb-banners', plugin_dir_url( dirname( __FILE__ ) ) . 'css/banners.css', '', time() );
				wp_enqueue_script( 'wbb-banners', plugin_dir_url( dirname( __FILE__ ) ) . 'js/banners.js', '', time(), TRUE );
			}
		}
	}

	public function admin_init()
	{

	}

	public function add_menus()
	{
		add_menu_page('Walk Bike Bus Settings', 'Walk Bike Bus', 'manage_options', 'walk_bike_bus', array($this, 'plugin_settings_page'), '', 5);
		add_submenu_page('walk_bike_bus', 'Walk Bike Bus Settings', 'Settings', 'manage_options', 'walk_bike_bus', array($this, 'plugin_settings_page'));
		add_submenu_page('walk_bike_bus', 'Walk Bike Bus Users', 'Users', 'manage_options', 'walk_bike_bus_users', array($this, 'users_page'));
		add_submenu_page('walk_bike_bus', 'Walk Bike Bus Neighborhoods', 'Neighborhoods', 'manage_options', 'walk_bike_bus_neighborhoods', array($this, 'neighborhoods_page'));
	}

	public function register_settings()
	{
		register_setting( 'wbb-settings', 'wbb_show_banners', 'intval');
	}

	public function plugin_settings_page()
	{
		include(dirname(__DIR__) . '/walk-bike-bus-settings.php');
	}

	public function users_page()
	{
		include(dirname(__DIR__) . '/walk-bike-bus-users.php');
	}

	public function neighborhoods_page()
	{
		include(dirname(__DIR__) . '/walk-bike-bus-neighborhoods.php');
	}
}