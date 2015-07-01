<?php

namespace WalkBikeBus;

class Subscriber {

	public $post_id = 0;
	public $email = '';

	public function custom_enter_title( $input )
	{
		global $post_type;

		if( is_admin() && 'Enter title here' == $input && 'wbb_subscriber' == $post_type )
		{
			return 'Enter Email';
		}

		return $input;
	}
}