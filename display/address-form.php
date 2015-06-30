<h2>Are You Eligible?</h2>
<form method="post" class="wbb-form">
	<?php wp_nonce_field('wbb_address', 'wbb_nonce'); ?>
	<input type="hidden" name="wbb_action" value="address">
	<label for="address">
		Please enter your address to see if you are eligible (include city, state and zip)
	</label>
	<input type="text" id="address" name="address" value="<?php echo esc_html(get_query_var('wbb_data')); ?>">
	<button class="wbb-button wbb-button-default">
		Submit
	</button>
</form>