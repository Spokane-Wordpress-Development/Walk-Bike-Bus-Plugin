<?php

namespace WalkBikeBus;

class Controller {

	public $action = '';
	public $data = '';
	public $return = '';
	public $lat;
	public $lng;
	public $error;
	public $current_page = '';
	public $errors;

	public function init()
	{
		if ( !session_id() )
		{
			session_start();
		}

		$temp = explode('?', $_SERVER['REQUEST_URI']);
		$this->current_page = $temp[0];

		/**
		 * load banners
		 */
		if (get_option('wbb_show_banners', 0) == 1 && get_option('wbb_shortcode_page', 0) != 0)
		{
			if ( ! is_admin() )
			{
				wp_enqueue_style( 'wbb-styles', plugin_dir_url( dirname( __FILE__ ) ) . 'css/wbb.css', '', time() );
				wp_enqueue_style( 'wbb-banners', plugin_dir_url( dirname( __FILE__ ) ) . 'css/banners.css', '', time() );

				wp_enqueue_script( 'wbb-banners', plugin_dir_url( dirname( __FILE__ ) ) . 'js/banners.js', '', time(), TRUE );
				wp_enqueue_script( 'wbb-calendar', plugin_dir_url( dirname( __FILE__ ) ) . 'js/calendar.js', '', time(), TRUE );
				wp_localize_script( 'wbb-calendar', 'WbbAjax', array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'entry_nonce' => wp_create_nonce( 'entry-nonce' )
				) );
				wp_enqueue_script( 'wbb-variables', plugin_dir_url( dirname( __FILE__ ) ) . 'js/variables.js', '', time(), TRUE );
				wp_localize_script( 'wbb-variables', 'wbb', array(
					'shortcode_page_id' => get_option('wbb_shortcode_page')
				) );
			}
		}
	}

	public function admin_init()
	{

	}

	public function activate()
	{
		require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
		global $wpdb;

		$table_name = $wpdb->prefix . 'wbb_locations';
		if( $wpdb->get_var( "SHOW TABLES LIKE '$db_table_name'" ) != $db_table_name )
		{
			if ( ! empty( $wpdb->charset ) )
			{
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			}
			if ( ! empty( $wpdb->collate ) )
			{
				$charset_collate .= " COLLATE $wpdb->collate";
			}

			$sql = "
				CREATE TABLE " . $table_name . "
				(
					`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					`user_id` int(11) NOT NULL,
					`title` varchar(50) NOT NULL DEFAULT '',
					`miles` decimal(11,2) NOT NULL,
					`created_at` datetime DEFAULT NULL,
					PRIMARY KEY (`id`),
					KEY `user_id` (`user_id`)
				) " . $charset_collate . ";";
			dbDelta( $sql );
		}

		$table_name = $wpdb->prefix . 'wbb_entries';
		if( $wpdb->get_var( "SHOW TABLES LIKE '$db_table_name'" ) != $db_table_name )
		{
			if ( ! empty( $wpdb->charset ) )
			{
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			}
			if ( ! empty( $wpdb->collate ) )
			{
				$charset_collate .= " COLLATE $wpdb->collate";
			}

			$sql = "
				CREATE TABLE " . $table_name . "
				(
					`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					`user_id` int(11) NOT NULL,
					`location_id` int(11) NOT NULL,
					`entry_date` date NOT NULL,
					`mode_type` enum('walk','bike','bus') DEFAULT NULL,
					`miles` decimal(11,2) NOT NULL,
					`created_at` datetime NOT NULL,
					`updated_at` datetime NOT NULL,
					PRIMARY KEY (`id`),
					KEY `user_id` (`user_id`),
					KEY `location_id` (`location_id`),
					KEY `mode_type` (`mode_type`)
				) " . $charset_collate . ";";
			dbDelta( $sql );
		}
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
		register_setting( 'wbb-settings', 'wbb_shortcode_page', 'intval' );
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

	public function short_code()
	{
		$this->action = get_query_var('wbb_action');
		$this->data = get_query_var('wbb_data');
		$this->return = "<h1>Walk Bike Bus</h1>";

		switch ( $this->action )
		{
			case 'address':

				return $this->showAddressPage();
				break;

			case 'register':

				return $this->showRegisterForm();
				break;

			default:

				return $this->showMainPage();
				break;
		}
	}

	public function showAddressPage()
	{
		if ( strlen($this->data) == 0 )
		{
			return $this->showAddressForm();
		}
		else
		{
			$this->getLatLon();
			if (strlen($this->error) > 0)
			{
				$this->return .= '<p class="wbb-alert wbb-alert-danger">' . $this->error . '</p>';
				return $this->showAddressForm();
			}
			else
			{
				$data = Neighborhood::getNeighborhoodFromLatLng($this->lat, $this->lng);
				if ($data['id'] == 0)
				{
					$this->return .= '<p class="wbb-alert wbb-alert-danger">The address you entered does not lie within one of our approved areas.</p>';
					return $this->showAddressForm();
				}
				else
				{
					$this->return .= '<p class="wbb-alert wbb-alert-success">Congrats! You are eligible to register for the ' . $data['title'] . ' neighborhood!</p>';
					$_SESSION['wbb_register_neighborhood_id'] = $data['id'];
					$_SESSION['wbb_register_neighborhood_title'] = $data['title'];
					return $this->showRegisterForm();
				}
			}
		}
	}

	public function showMainPage()
	{
		if ( is_user_logged_in() )
		{
			return $this->return . $this->returnOutputFromPage('/display/calendar.php');
		}
		else
		{
			return $this->return . $this->returnOutputFromPage('/display/login-page.php') . $this->returnOutputFromPage('/display/address-form.php');
		}
	}

	public function showAddressForm()
	{
		return $this->return . $this->returnOutputFromPage('/display/address-form.php');
	}

	public function showRegisterForm()
	{
		return $this->return . $this->returnOutputFromPage('/display/register-form.php');
	}

	private function returnOutputFromPage($page)
	{
		ob_start();
		include(dirname(__DIR__) . $page);
		return ob_get_clean();
	}

	private function getLatLon()
	{
		$address = $this->data;
		$pos = (strpos(strtoupper($address), 'SPOKANE'));
		if ($pos === FALSE)
		{
			$address .= ' Spokane, WA';
		}

		$url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address);
		$url = str_replace('#', 'STE+', $url);

		$options = array(
			CURLOPT_RETURNTRANSFER => TRUE,     // return web page
			CURLOPT_HEADER         => FALSE,    // don't return headers
			CURLOPT_FOLLOWLOCATION => TRUE,     // follow redirects
			CURLOPT_ENCODING       => '',       // handle all encodings
			CURLOPT_USERAGENT      => '', 		// who am i
			CURLOPT_AUTOREFERER    => TRUE,     // set referer on redirect
			CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
			CURLOPT_TIMEOUT        => 120,      // timeout on response
			CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
		);

		$ch = curl_init($url);
		curl_setopt_array ($ch, $options);
		$content = curl_exec($ch);
		curl_close($ch);

		$result = json_decode($content, TRUE);
		if ($result['status'] != 'OK')
		{
			$this->error = 'We could not locate that address. Make sure you enter your complete address, including city, state and zip code.';
		}
		else
		{
			$this->lat = $result['results'][0]['geometry']['location']['lat'];
			$this->lng = $result['results'][0]['geometry']['location']['lng'];
		}
	}

	public function form_capture()
	{
		if ( isset( $_POST['wbb_action'] ) )
		{
			if ( isset($_POST['wbb_nonce']) && wp_verify_nonce( $_POST['wbb_nonce'], 'wbb_' . $_POST['wbb_action'] ) )
			{
				switch ( $_POST['wbb_action'] )
				{
					case 'address':

						header('Location:'.$this->current_page.'?wbb_action=address&wbb_data='.urlencode($_POST['address']));
						exit;
						break;

					case 'register':

						$this->errors = new \WP_Error;

						if ( empty( $_POST['username'] ) || empty( $_POST['password'] ) || empty( $_POST['email'] ) || empty( $_POST['fname'] ) || empty( $_POST['lname'] ) )
						{
							$this->errors->add('field', 'Required form field is missing');
						}

						elseif ( 4 > strlen( $_POST['username'] ) )
						{
							$this->errors->add( 'username_length', 'Username too short. At least 4 characters is required' );
						}

						elseif ( username_exists( $_POST['username'] ) )
						{
							$this->errors->add( 'user_name', 'Sorry, that username already exists!' );
						}

						elseif ( ! validate_username( $_POST['username'] ) )
						{
							$this->errors->add( 'username_invalid', 'Sorry, the username you entered is not valid' );
						}

						elseif ( 5 > strlen( $_POST['password'] ) )
						{
							$this->errors->add( 'password', 'Password length must be greater than 5' );
						}

						elseif ( !is_email( $_POST['email'] ) )
						{
							$this->errors->add( 'email_invalid', 'Email is not valid' );
						}

						elseif ( email_exists( $_POST['email'] ) )
						{
							$this->errors->add( 'email', 'Email Already in use' );
						}

						if (count($this->errors->get_error_messages()) == 0)
						{
							$userdata = array(
								'user_login' => $_POST['username'],
								'user_email' => $_POST['email'],
								'user_pass' => $_POST['password'],
								'first_name' => $_POST['fname'],
								'last_name' => $_POST['lname']
							);
							$user_id = wp_insert_user( $userdata );
							update_user_meta( $user_id, 'neighborhood_id', $_SESSION['wbb_register_neighborhood_id'] );
							update_user_meta( $user_id, 'address', $_POST['address'] );
							update_user_meta( $user_id, 'mailing_list', isset($_POST['mailing_list']) ? '1' : '0' );

							header('Location:'.$this->current_page.'?wbb_action=login&wbb_data=registration_complete');
							exit;
						}

						break;
				}
			}
		}
	}

	public function ajax_add_entry()
	{
		$response = array(
			'success' => 0,
			'error' => 'Something went wrong. Please try again.'
		);

		if ( wp_verify_nonce($_POST['entry_nonce'], 'entry-nonce'))
		{
			$miles = (strlen(trim($_POST['miles'])) > 0 && is_numeric($_POST['miles'])) ? abs(trim($_POST['miles'])) : 1;

			$location = new Location;
			if ($_POST['location_id'] > 0)
			{
				$location->id = $_POST['location_id'];
				$location->read();
				if (!$location->user_id == get_current_user_id())
				{
					$location->id = 0;
				}
			}

			if ($location->id == 0)
			{
				$location->user_id = get_current_user_id();
				$location->title = (strlen(trim($_POST['title'])) > 0) ? trim($_POST['title']) : 'New Location';
				$location->miles = $miles;

				$location_id = Location::checkTitle($location->user_id, $location->title);
				if ($location_id > 0)
				{
					$location->id = $location_id;
					$location->update();
				}
				else
				{
					$location->create();
				}
			}

			$entry = new Entry;
			$entry->user_id = get_current_user_id();
			$entry->entry_date = $_POST['year'].'-'.$_POST['month'].'-'.$_POST['day'];
			$entry->mode = $_POST['mode'];
			$entry->miles = $miles;
			$entry->location = $location;
			$entry->create();

			if ($entry->id > 0)
			{
				$response['success'] = 1;
				$response['id'] = $entry->id;
				$response['title'] = $entry->location->title;
				$response['miles'] = number_format($entry->miles, 2);
				$response['day'] = $_POST['day'];
			}
		}

		header( 'Content-Type: application/json' );
		echo json_encode( $response );
		exit;
	}
}