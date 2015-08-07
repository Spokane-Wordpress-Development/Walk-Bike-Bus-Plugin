<?php

namespace WalkBikeBus;

class Neighborhood {

	/**
	 * For some reason my custom post type attributes keep disappearing.
	 * So I'm hard coding them here until I figure out why.
	 */
	const PERRY_NORTH = 47.64971;
	const PERRY_EAST = -117.38146;
	const PERRY_SOUTH = 47.64271;
	const PERRY_WEST = -117.39558;

	public $post_id = 0;
	public $title = '';
	public $north_boundary;
	public $east_boundary;
	public $south_boundary;
	public $west_boundary;
	public $expires_at;

	public function extra_neighborhood_meta()
	{
		add_meta_box( 'wbb-neighborhood-meta', 'Neighborhood Info', array( $this, 'extra_neighborhood_fields'), 'wbb_neighborhood' );
	}

	public function extra_neighborhood_fields()
	{
		include(dirname(__DIR__) . '/extra-neighborhood-fields.php');
	}

	public function save_neighborhood_post()
	{
		global $post;
		if ($post->post_type == 'wbb_neighborhood' && isset($_POST['north_boundary']))
		{
			//$is_active = $_POST['neighborhood_is_active'];
			$is_active = (strlen($_POST['north_boundary']) > 0) ? '1' : '0';

			$boundaries = array();
			$boundaries['n'] = $_POST['north_boundary'];
			$boundaries['e'] = $_POST['east_boundary'];
			$boundaries['s'] = $_POST['south_boundary'];
			$boundaries['w'] = $_POST['west_boundary'];

			foreach ($boundaries as $dir => $data)
			{
				$data = preg_replace( '/[^0-9\.-]/', '', $data );
				if (strlen($data) == 0)
				{
					$boundaries[$dir] = $data;
				}
			}

			update_post_meta( $post->ID, 'neighborhood_is_active', $is_active );
			if (strlen($boundaries['n']) > 0)
			{
				update_post_meta($post->ID, 'north_boundary', $boundaries['n']);
			}
			if (strlen($boundaries['e']) > 0)
			{
				update_post_meta($post->ID, 'east_boundary', $boundaries['e']);
			}
			if (strlen($boundaries['s']) > 0)
			{
				update_post_meta($post->ID, 'south_boundary', $boundaries['s']);
			}
			if (strlen($boundaries['w']) > 0)
			{
				update_post_meta($post->ID, 'west_boundary', $boundaries['w']);
			}
			update_post_meta($post->ID, 'expires_at', $_POST['expires_at']);
		}
	}

	public function add_new_columns( $columns )
	{
		$new = array(
			'is_active' => 'Active',
			'expires_at' => 'Registration Ends',
			'boundaries' => 'Boundaries'
		);
		$columns = array_slice( $columns, 0, 2, TRUE ) + $new + array_slice( $columns, 2, NULL, TRUE );
		return $columns;
	}

	public function custom_columns( $column )
	{
		global $post;

		switch ( $column )
		{
			case 'is_active':
				//echo (get_post_meta( $post->ID, 'neighborhood_is_active', TRUE) == '1') ? 'Yes' : 'No';
				$temp = get_post_meta( $post->ID, 'north_boundary', TRUE);
				echo (strlen($temp) > 0) ? 'Yes' : 'No';
				break;

			case 'expires_at':
				$temp = get_post_meta( $post->ID, 'expires_at', TRUE);
				echo (strlen($temp) == 0) ? 'Never' : date('F j, Y', strtotime($temp));
				break;

			case 'boundaries':
				$boundaries = array();
				foreach ( array( 'North', 'East', 'West', 'South' ) as $dir )
				{
					$temp = get_post_meta( $post->ID, strtolower($dir).'_boundary', TRUE);
					if (strlen($temp) > 0)
					{
						$boundaries[] = $dir . ': ' . $temp;
					}
				}
				if ( $boundaries )
				{
					echo implode( '<br>', $boundaries );
				}
				break;
		}
	}

	/**
	 * @param $lat
	 * @param $lng
	 *
	 * @return array
	 */
	public static function getNeighborhoodFromLatLng($lat, $lng)
	{
		$data = array(
			'id' => 0,
			'title' => ''
		);

		$args = array(
			'post_type' => 'wbb_neighborhood',
			'post_status' => 'publish'
		);
		$query = new \WP_Query($args);
		while ($query->have_posts())
		{
			$query->the_post();
			$custom = get_post_custom(get_the_ID());
			//if ($custom['neighborhood_is_active'][0] == '1')
			if (strlen($custom['north_boundary'][0]) > 0)
			{
				$west = $custom['west_boundary'][0];
				$east = $custom['east_boundary'][0];
				$north = $custom['north_boundary'][0];
				$south = $custom['south_boundary'][0];

				if (strlen($west) == 0 || strlen($east) == 0 || strlen($north) == 0 || strlen($south) == 0)
				{
					$title = get_the_title();
					$pos = strpos(strtoupper($title), 'PERRY');
					if ($pos !== FALSE)
					{
						$west = self::PERRY_WEST;
						$east = self::PERRY_EAST;
						$north = self::PERRY_NORTH;
						$south = self::PERRY_SOUTH;
					}
				}

				if ($lng >= $west && $lng <= $east && $lat <= $north && $lat >= $south)
				{
					$data['id'] = get_the_ID();
					$data['title'] = get_the_title();
					break;
				}
			}
		}

		return $data;
	}
}