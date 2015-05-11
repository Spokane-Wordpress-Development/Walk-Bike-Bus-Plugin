<?php

namespace WalkBikeBus;

class User {

	public function extra_profile_fields()
	{
		include(dirname(__DIR__) . '/extra-user-profile-fields.php');
	}

	public function save_extra_profile_fields()
	{

		if ( !current_user_can( 'edit_user', $_POST['user_id'] ) ) {
			return false;
		}

		update_user_meta( $_POST['user_id'], 'neighborhood_id', $_POST['neighborhood_id'] );
	}
}