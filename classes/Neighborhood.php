<?php

namespace WalkBikeBus;

class Neighborhood {

	public $post_id = 0;
	public $title = '';

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
		if ($post->post_type == 'wbb_neighborhood')
		{
			$is_active = $_POST['is_active'];

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
					$is_active = 0;
					$boundaries[$dir] = $data;
				}
			}

			update_post_meta( $post->ID, 'is_active', $is_active );
			update_post_meta( $post->ID, 'north_boundary', $boundaries['n'] );
			update_post_meta( $post->ID, 'east_boundary', $boundaries['e'] );
			update_post_meta( $post->ID, 'south_boundary', $boundaries['s'] );
			update_post_meta( $post->ID, 'west_boundary', $boundaries['w'] );
		}
	}

	public function add_new_columns( $columns )
	{
		$new = array(
			'is_active' => 'Active',
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
				echo (get_post_meta( $post->ID, 'is_active', TRUE) == 1) ? 'Yes' : 'No';
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
}