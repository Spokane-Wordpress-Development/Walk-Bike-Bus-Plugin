<?php

$neighborhoods = [];
$args = array(
	'post_type' => 'wbb_neighborhood',
	'post_status' => 'publish'
);
$query = new WP_Query($args);

while ($query->have_posts())
{
	$query->the_post();
	$neighborhood = new \WalkBikeBus\Neighborhood;
	$neighborhood->post_id = get_the_ID();
	$neighborhood->title = get_the_title();
	$neighborhoods[get_the_ID()] = $neighborhood;
}

$users = get_users(array(
	'meta_query' => array(
		'relation' => 'AND',
		array(
			'key' => 'neighborhood_id',
			'value' => '',
			'compare' => '!='
		),
		array(
			'key' => 'neighborhood_id',
			'value' => '0',
			'compare' => '!='
		)
	)
));

?>

<div class="wrap">

	<h2>Walk Bike Bus Users</h2>

	<div class="admin notice">
		<p>
			If you do not see someone in this list, <a href="/wp-admin/users.php">click here</a> to find that person and then assign a neighborhood on the edit screen.
		</p>
	</div>

	<table class="wp-list-table widefat fixed striped users">
		<thead>
		<tr>
			<th>Name</th>
			<th>Email</th>
			<th>Neighborhood</th>
		</tr>
		</thead>
		<tbody>
		<?php

		/**
		 * @var WP_User $user
		 */

		?>
		<?php foreach ($users as $user) { ?>
			<tr>
				<td>
					<a href="/wp-admin/user-edit.php?user_id=<?php echo $user->ID; ?>">
						<?php echo $user->user_firstname . ' ' . $user->user_lastname; ?>
					</a>
				</td>
				<td>
					<?php echo $user->user_email; ?>
				</td>
				<td>
					<?php echo $neighborhoods[get_user_meta($user->ID, 'neighborhood_id', TRUE)]->title; ?>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>

</div>