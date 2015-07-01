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
	<tr>
		<th>Subscribed</th>
		<td><?php echo ( get_user_meta( $user_id, 'mailing_list', TRUE ) == 1) ? 'Yes' : 'No'; ?></td>
	</tr>
	<tr>
		<th>Order</th>
		<td>
			<?php

			$order = get_user_meta( $user_id, 'order', TRUE );
			if (empty($order))
			{
				echo 'N/A';
			}
			else
			{
				echo implode('<br>', json_decode($order, TRUE));
			}

			?>
		</td>
	</tr>
	<tr>
		<th>Gift</th>
		<td>
			<?php

			$gift = get_user_meta( $user_id, 'gift', TRUE );
			if (empty($gift))
			{
				echo 'N/A';
			}
			else
			{
				echo $gift;
			}

			?>
		</td>
	</tr>
	<tr>
		<th>Delivery</th>
		<td>
			<?php

			$full_name = get_user_meta( $user_id, 'full_name', TRUE );
			if (!empty($full_name))
			{
				echo $full_name . '<br>';
			}
			$address = get_user_meta( $user_id, 'address', TRUE );
			if (!empty($address))
			{
				echo $address . '<br>';
			}
			echo '<ol>';
			for ($x=1; $x<=3; $x++)
			{
				$date = get_user_meta( $user_id, 'date'.$x, TRUE );
				if (!empty($date))
				{
					echo '<li>' . $date . '</li>';
				}
			}
			echo '</ol>';

			?>
		</td>
	</tr>

</table>