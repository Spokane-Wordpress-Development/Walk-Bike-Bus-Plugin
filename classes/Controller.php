<?php

namespace WalkBikeBus;

class Controller {

	public function init()
	{
		/**
		 * load banners
		 */
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

	public function create_post_types()
	{
		$labels = array (
			'name' => __( 'Neighborhoods' ),
			'singular_name' => __( 'Neighborhood' ),
			'add_new_item' => __( 'Add New Neighborhood' ),
			'edit_item' => __( 'Edit Neighborhood' ),
			'new_item' => __( 'New Neighborhood' ),
			'view_item' => __( 'View Neighborhood' ),
			'search_items' => __( 'Search Neighborhoods' ),
			'not_found' => __( 'No neighborhoods found.' )
		);

		$args = array (
			'labels' => $labels,
			'hierarchical' => FALSE,
			'description' => 'Neighborhoods',
			'supports' => array('title', 'editor'),
			'public' => FALSE,
			'show_ui' => TRUE,
			'show_in_menu' => FALSE,
			'show_in_nav_menus' => FALSE,
			'publicly_queryable' => FALSE,
			'exclude_from_search' => FALSE,
			'has_archive' => TRUE
		);

		register_post_type('wbb_neighborhood', $args);
	}

	public function add_menus()
	{
		add_menu_page('Walk Bike Bus Settings', 'Walk Bike Bus', 'manage_options', 'walk_bike_bus', array($this, 'plugin_settings_page'), '', 5);
		add_submenu_page('walk_bike_bus', 'Walk Bike Bus Settings', 'Settings', 'manage_options', 'walk_bike_bus', array($this, 'plugin_settings_page'));
		add_submenu_page('walk_bike_bus', 'Walk Bike Bus Users', 'Users', 'manage_options', 'walk_bike_bus_users', array($this, 'users_page'));
		add_submenu_page('walk_bike_bus', 'Walk Bike Bus Neighborhoods', 'Neighborhoods', 'manage_options', 'edit.php?post_type=wbb_neighborhood');
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

	public function query_vars( $vars )
	{
		$vars[] = 'wbb_action';
		$vars[] = 'wbb_data';
		return $vars;
	}
}