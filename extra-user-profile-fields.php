<?php

global $user_id;
$data = get_user_meta( $user_id, 'neighborhood_id' );
$neighborhood_id = ($data) ? $data[0] : 0;

$args = array(
	'post_type' => 'wbb_neighborhood',
	'post_status' => 'publish'
);
$query = new WP_Query( $args );

?>

<h3>Walk Bike Bus Information</h3>

<table class="form-table">

	<tr>
		<th><label for="neighborhood">Neighborhood</label></th>
		<td>
			<select name="neighborhood_id" id="neighborhood">
				<option value="0">N/A</option>
				<?php while ($query->have_posts()) { ?>
					<?php $query->the_post(); ?>
					<option value="<?php the_ID(); ?>"<?php if (get_the_ID() == $neighborhood_id) { ?> selected<?php } ?>>
						<?php the_title(); ?>
					</option>
				<?php } ?>
			</select>
		</td>
	</tr>

</table>