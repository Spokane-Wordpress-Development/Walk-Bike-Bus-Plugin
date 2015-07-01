<?php if (empty($_SESSION['wbb_register_neighborhood_id'])) { ?>

	<p class="wbb-alert wbb-alert-danger">
		Your session has timed out. Please try again.
	</p>

<?php } else { ?>

	<h2>Set Up an Account</h2>
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

		<h2>Order Form</h2>
		<p>
			Please check all that interest you.<br><br>
			If you do not wish to receive any info, you can skip this section and press "Submit" at the bottom.
		</p>
		<div>

			<p>WALK INFO</p>
			<label>
				<input type="checkbox" name="order[]" value="Walking/Hiking Map">
				Spokane Area Walking &amp; Hiking Map (<em>Fun destinations to improve your fitness</em>)
			</label>
			<br>
			<label>
				<input type="checkbox" name="order[]" value="Foot Guide">
				Spokane Foot Guide (<em>Get the most out of your walking commute</em>)
			</label>
			<br>
			<label>
				<input type="checkbox" name="order[]" value="Pedestrian Safety Card">
				Pedestrian Safety Card
			</label>
			<br>
			<label>
				<input type="checkbox" name="order[]" value="Safe Routes to School">
				Safe Routes to School Brochure
			</label>

			<p>BIKE INFO</p>
			<label>
				<input type="checkbox" name="order[]" value="Bicycle Map">
				Spokane County Bicycle Map (<em>Comprehensive bike corridor map</em>)
			</label>
			<br>
			<label>
				<input type="checkbox" name="order[]" value="Guide to Your Ride">
				Guide to Your Ride (<em>How to get around Spokane by bike</em>)
			</label>
			<br>
			<label>
				<input type="checkbox" name="order[]" value="Bicycle Club Brochure">
				Spokane Bicycle Club Brochure
			</label>
			<br>
			<label>
				<input type="checkbox" name="order[]" value="Safety Card/Helmet Fitting">
				Bicycle Safety Card &amp; Helmet Fitting
			</label>
			<br>
			<label>
				<input type="checkbox" name="order[]" value="Family Biking Guide">
				Family Biking Guide
			</label>

			<p>BUS INFO</p>
			<label>
				<input type="checkbox" name="order[]" value="Bus Routes Map">
				STA Regional Bus Routes Map
			</label>
			<br>
			<label>
				<input type="checkbox" name="order[]" value="Bus Fact Sheet">
				STA Bus Rider Fact Sheet
			</label>
			<br>
			<label>
				<input type="checkbox" name="order[]" value="Route #45 Schedule">
				STA Bus Route #45 Schedule (<em>Commute to 29th Ave, South Regal and back</em>)
			</label>
			<br>
			<label>
				<input type="checkbox" name="order[]" value="Bikes on Buses Brochure">
				Bikes on Buses Brochure
			</label>

			<p>CLAIM YOUR FREE GIFT</p>
			<label>
				<input type="radio" name="gift" value="" checked> No thanks<br>
				<input type="radio" name="gift" value="band"> Illuminated High-Visibility Adjustable Band<br>
				<input type="radio" name="gift" value="bike light"> Bike Light<br>
				<input type="radio" name="gift" value="tote bag"> Tote Bag
			</label>

			<p>TELL US WHERE TO DELIVER YOUR FREE GIFT PACKET</p>
			<div>
				<label for="full-name">Full Name</label>
				<input type="text" id="full-name" name="full_name" value="<?php echo (isset($_POST['full_name'])) ? esc_html($_POST['full_name']) : ''; ?>">
			</div>
			<div>
				<label for="address">Home Address</label>
				<input type="text" id="address" name="address" value="<?php echo (isset($_POST['address'])) ? esc_html($_POST['address']) : $_SESSION['wbb_address']; ?>">
			</div>

			<p>PREFERRED DELIVERY TIMES</p>
			<p>
				Our friendly Walk Bike Bus representatives can answer questions and help you reach your walking, biking and busing goals.
				Please indicate the best ti mes† you are available at your home for delivery of your gift packet.
			</p>
			<div>
				<label for="date1">First Date/Time Preference</label>
				<input type="text" id="date1" name="date1" value="<?php echo (isset($_POST['date1'])) ? esc_html($_POST['date1']) : ''; ?>">
			</div>
			<div>
				<label for="date2">Second Date/Time Preference</label>
				<input type="text" id="date2" name="date2" value="<?php echo (isset($_POST['date2'])) ? esc_html($_POST['date2']) : ''; ?>">
			</div>
			<div>
				<label for="date3">Third Date/Time Preference</label>
				<input type="text" id="date3" name="date3" value="<?php echo (isset($_POST['date3'])) ? esc_html($_POST['date3']) : ''; ?>">
			</div>

		</div>

		<button class="wbb-button wbb-button-default">
			Submit
		</button>

	</form>

<?php } ?>