<div class="wrap">

	<h2>Walk Bike Bus Settings</h2>

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
		</table>

		<?php submit_button(); ?>

	</form>

</div>