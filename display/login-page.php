<?php

$args = array (
	'echo'           => true,
	'redirect'       => site_url($this->current_page),
	'form_id'        => 'loginform',
	'label_username' => __( 'Username' ),
	'label_password' => __( 'Password' ),
	'label_remember' => __( 'Remember Me' ),
	'label_log_in'   => __( 'Log In' ),
	'id_username'    => 'user_login',
	'id_password'    => 'user_pass',
	'id_remember'    => 'rememberme',
	'id_submit'      => 'wp-submit',
	'remember'       => true,
	'value_username' => '',
	'value_remember' => false
);

?>

<p>
	If you have a login and password, enter them here to start tracking your commutes.
	Otherwise, you can enter your address below to see if you live in a qualifying area.
</p>

<?php wp_login_form( $args ); ?>