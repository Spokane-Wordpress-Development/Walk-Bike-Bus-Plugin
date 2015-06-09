<?php if (empty($_SESSION['wbb_register_neighborhood_id'])) { ?>

	<p class="wbb-alert wbb-alert-danger">
		Your session has timed out. Please try again.
	</p>

<?php } else { ?>

	<p>
		Please fill out the below fields to sign up for the <?php echo $_SESSION['wbb_register_neighborhood_title']; ?> neighborhood.
	</p>

	<?php if ( is_wp_error( $this->errors ) ) { ?>

		<?php foreach ( $this->errors->get_error_messages() as $error ) { ?>

			<p class="wbb-alert wbb-alert-danger">
				<?php echo $error; ?>
			</p>

		<?php } ?>

	<?php } ?>

	<form action="<?php echo $this->current_page; ?>?wbb_action=register" method="post" class="wbb-form">

		<?php wp_nonce_field('wbb_register', 'wbb_nonce'); ?>
		<input type="hidden" name="wbb_action" value="register">
		<input type="hidden" name="address" value="<?php echo (isset($_POST['address']) ? esc_html($_POST['address']) : esc_html($_GET['wbb_data'])); ?>">

		<div>
			<label for="username">Username <strong>*</strong></label>
			<input type="text" id="username" name="username" value="<?php echo (isset($_POST['username'])) ? esc_html($_POST['username']) : ''; ?>">
		</div>

		<div>
			<label for="password">Password <strong>*</strong></label>
			<input type="password" id="password" name="password" value="<?php echo (isset($_POST['password'])) ? esc_html($_POST['password']) : ''; ?>">
		</div>

		<div>
			<label for="email">Email <strong>*</strong></label>
			<input type="text" id="email" name="email" value="<?php echo (isset($_POST['email'])) ? esc_html($_POST['email']) : ''; ?>">
		</div>

		<div>
			<label for="fname">First Name <strong>*</strong></label>
			<input type="text" id="fname" name="fname" value="<?php echo (isset($_POST['fname'])) ? esc_html($_POST['fname']) : ''; ?>">
		</div>

		<div>
			<label for="lname">Last Name <strong>*</strong></label>
			<input type="text" id="lname" name="lname" value="<?php echo (isset($_POST['lname'])) ? esc_html($_POST['lname']) : ''; ?>">
		</div>

		<div>
			<label>
				<input type="checkbox" name="mailing_list"<?php if (isset($_POST['mailing_list']) || !isset($_POST['wbb_action'])) { ?> checked<?php } ?>>
				Join our mailing list?
			</label>
		</div>

		<button class="wbb-button wbb-button-default">
			Submit
		</button>

	</form>

<?php } ?>