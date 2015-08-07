<?php

global $post;
$custom = get_post_custom($post->ID);

/**
 * $is_active keeps getting reset to an empty string and I don't know why
 * So I'm going to use the boundaries to determine if it's active (until I figure it out)
 */
$is_active = $custom['neighborhood_is_active'][0];

$north_boundary = (array_key_exists('north_boundary', $custom)) ? $custom['north_boundary'][0] : '';
$east_boundary = (array_key_exists('east_boundary', $custom)) ? $custom['east_boundary'][0] : '';
$south_boundary = (array_key_exists('south_boundary', $custom)) ? $custom['south_boundary'][0] : '';
$west_boundary = (array_key_exists('west_boundary', $custom)) ? $custom['west_boundary'][0] : '';
$expires_at = (array_key_exists('expires_at', $custom)) ? $custom['expires_at'][0] : '';

?>

<table class="form-table">
	<!--
	<tr>
		<th>
			<label for="wbb-neighborhood-is-active">
				Is Active:
			</label>
		</th>
		<td>
			<select name="neighborhood_is_active" id="wbb-neighborhood-is-active">
				<option value="0"<?php if ($is_active == '0') { ?> selected<?php } ?>>No</option>
				<option value="1"<?php if ($is_active == '1') { ?> selected<?php } ?>>Yes</option>
			</select>
		</td>
	</tr>
	-->
	<tr>
		<th>
			<label for="wbb-neighborhood-expires-at">
				Last Day to Register:
			</label>
		</th>
		<td>
			<input name="expires_at" id="wbb-neighborhood-expires-at" value="<?php echo esc_html($xpires_at); ?>" placeholder="ex: 10/15/2015">
		</td>
	</tr>
	<tr>
		<th>
			<label for="wbb-neighborhood-north-boundary">
				North Boundary:
			</label>
		</th>
		<td>
			<input name="north_boundary" id="wbb-neighborhood-north-boundary" value="<?php echo esc_html($north_boundary); ?>" placeholder="ex: 47.1234">
		</td>
	</tr>
	<tr>
		<th>
			<label for="wbb-neighborhood-east-boundary">
				East Boundary:
			</label>
		</th>
		<td>
			<input name="east_boundary" id="wbb-neighborhood-east-boundary" value="<?php echo esc_html($east_boundary); ?>" placeholder="ex: -117.1234">
		</td>
	</tr>
	<tr>
		<th>
			<label for="wbb-neighborhood-south-boundary">
				South Boundary:
			</label>
		</th>
		<td>
			<input name="south_boundary" id="wbb-neighborhood-south-boundary" value="<?php echo esc_html($south_boundary); ?>" placeholder="ex: 47.1234">
		</td>
	</tr>
	<tr>
		<th>
			<label for="wbb-neighborhood-west-boundary">
				West Boundary:
			</label>
		</th>
		<td>
			<input name="west_boundary" id="wbb-neighborhood-west-boundary" value="<?php echo esc_html($west_boundary); ?>" placeholder="ex: -117.1234">
		</td>
	</tr>
</table>
