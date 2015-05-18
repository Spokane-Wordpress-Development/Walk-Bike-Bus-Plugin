<?php

$args = array(
	'post_type' => 'page',
	'post_status' => 'publish'
);
$query = new WP_Query($args);

?>

<div class="wrap">

	<h2>Walk Bike Bus Settings</h2>

	<div class="admin notice">
		<p>You must put the shortcode on a page and choose that page below in order for the address search to work.</p>
	</div>

	<form method="post" action="options.php">

		<?php settings_fields('wbb-settings'); ?>

		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label for="show-banners">Show Banners</label>
				</th>
				<td>
					<select name="wbb_show_banners" id="show-banners">
						<option value="0" <?php if (get_option('wbb_show_banners') == 0) { ?> selected<?php } ?>>No</option>
						<option value="1" <?php if (get_option('wbb_show_banners') == 1) { ?> selected<?php } ?>>Yes</option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="shortcode-page">Shortcode Page</label>
				</th>
				<td>
					<select name="wbb_shortcode_page" id="shortcode-page">
						<option value="0">None</option>
						<?php while ($query->have_posts()) { ?>
							<?php $query->the_post(); ?>
							<option value="<?php the_ID(); ?>"<?php if (get_the_ID() == get_option('wbb_shortcode_page')) { ?> selected<?php } ?>>
								<?php the_title(); ?>
							</option>
						<?php } ?>
					</select>
				</td>
			</tr>
		</table>

		<?php submit_button(); ?>

	</form>

	<h2>Shortcode</h2>
	<div class="admin notice">
		<p>Add this shortcode to a page to insert the login/registration/logging functionality.</p>
	</div>
	<pre>[walk_bike_bus]</pre>

</div>