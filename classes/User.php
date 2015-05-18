<?php

namespace WalkBikeBus;

class User {

	public $id = 0;
	public $first_name = '';
	public $last_name = '';
	public $email = '';
	public $address = '';
	public $is_on_mailing_list = FALSE;

	/**
	 * @var \WalkBikeBus\Neighborhood
	 */
	public $neighborhood;

	public function extra_profile_fields()
	{
		include(dirname(__DIR__) . '/extra-user-profile-fields.php');
	}

	public function save_extra_profile_fields()
	{

		if ( !current_user_can( 'edit_user', $_POST['user_id'] ) )
		{
			return false;
		}

		update_user_meta( $_POST['user_id'], 'neighborhood_id', $_POST['neighborhood_id'] );
	}

	public function load_user()
	{
		if ( is_user_logged_in() )
		{
			$current_user = wp_get_current_user();
			$this->id = $current_user->ID;
			$this->first_name = $current_user->user_firstname;
			$this->last_name = $current_user->user_lastname;
			$this->email = $current_user->user_email;
			$this->address = get_user_meta ( $current_user->ID, 'address', TRUE );
			$this->is_on_mailing_list = get_user_meta ( $current_user->ID, 'mailing_list', TRUE );
			$this->is_on_mailing_list = ($this->is_on_mailing_list == 1) ? TRUE : FALSE;

			$neighborhood_id = get_user_meta ( $current_user->ID, 'neighborhood_id', TRUE );
			if ( !empty( $neighborhood_id ) && $neighborhood_id > 0 )
			{
				$args = array(
					'post_type' => 'wbb_neighborhood',
					'post_status' => 'publish'
				);
				$query = new \WP_Query( $args );

				while ( $query->have_posts() )
				{
					$query->the_post();
					if ( get_the_ID() == $neighborhood_id )
					{
						$this->neighborhood = new Neighborhood;
						$this->neighborhood->post_id = get_the_ID();
						$this->neighborhood->title = get_the_title();
						break;
					}
				}
			}
		}
	}
}