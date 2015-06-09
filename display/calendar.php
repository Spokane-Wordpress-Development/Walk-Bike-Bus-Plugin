<?php global $wbb_user; ?>
<?php if (!$wbb_user->neighborhood) { ?>

	<p class="wbb-alert wbb-alert-danger">
		You are not currently assigned to a neighborhood.
		Please contact us if you believe this is an error.
	</p>

<?php } else { ?>

	<p>
		You are currently assigned to the <?php echo $wbb_user->neighborhood->title; ?> neighborhood!
		Click on a date below to start tracking your commute alternatives.
	</p>

	<?php

	$month = date('n');
	$year = date('Y');

	if ( get_query_var('wbb_data', '') != '' )
	{
		$parts = explode ( '|', get_query_var('wbb_data') );
		if ( count( $parts ) == 2)
		{
			if ( is_numeric( $parts[0] ) && $parts[0] >= 1 && $parts[0] <= 12 )
			{
				$month = round( $parts[0] );
			}

			if ( is_numeric( $parts[1] ) && $parts[1] > 999 && $parts[1] < 10000 )
			{
				$year = round( $parts[1] );
			}
		}
	}

	$next_month = $month + 1;
	$next_year = $year;
	if ($next_month == 13)
	{
		$next_month = 1;
		$next_year++;
	}

	$prev_month = $month - 1;
	$prev_year = $year;
	if ($prev_month == 0)
	{
		$prev_month = 12;
		$prev_year--;
	}

	$month_starts_at = strtotime( $month.'/1/'.$year );
	$days_in_month = date ( 't', $month_starts_at );

	$wbb_user->get_entries(0, $month, $year);
	$days = array();

	for ( $d=1; $d<=$days_in_month; $d++ )
	{
		$days[$d] = array(
			'miles' => 0,
			'entries' => array()
		);
	}

	/**
	 * @var \WalkBikeBus\Entry $entry
	 */
	foreach ($wbb_user->entries as $entry)
	{
		$day = date('j', strtotime($entry->entry_date));
		$days[$day]['miles'] += $entry->miles;
		$days[$day]['entries'][] = $entry;
	}

	?>

	<div id="wbb-calendar">

		<h3>
			<?php echo date('F Y', $month_starts_at); ?><br>
			<small>
				<a href="<?php echo $this->current_page; ?>?wbb_data=<?php echo $prev_month . '|' . $prev_year; ?>">Prev Month</a>
				|
				<a href="<?php echo $this->current_page; ?>?wbb_data=<?php echo $next_month . '|' . $next_year; ?>">Next Month</a>
			</small>
		</h3>

		<div id="wbb-boxes">

			<?php for ( $d=0; $d<date( 'w', $month_starts_at ); $d++ ) { ?>
				<div data-day="0" class="wbb-day wbb-day-blank"></div>
			<?php } ?>

			<?php foreach ($days as $d => $day) { ?>
				<div data-day="<?php echo $d; ?>" id="wbb-day-<?php echo $d; ?>" class="wbb-day<?php if ($year == date('Y') && $month == date('n') && $d == date('j')) { ?> wbb-day-today<?php } ?>">
					<div class="wbb-day-number">
						<?php echo $d; ?>
					</div>
					<div class="wbb-day-stats">
						<span class="entries">
							<?php echo count($day['entries']); ?>
							entr<?php echo (count($day['entries']) == 1) ? 'y' : 'ies'; ?>
						</span><br>
						<span class="miles">
							<?php echo number_format($day['miles'], 2); ?>
							miles
						</span><br>
					</div>
				</div>
			<?php } ?>

		</div>

		<div id="wbb-entry" data-day="0" data-month="<?php echo $month; ?>" data-year="<?php echo $year; ?>">

			<p>
				<strong>
					Entries for <?php echo date('F', $month_starts_at); ?>
					<span class="day-number"></span>,
					<?php echo date('Y', $month_starts_at); ?>
					-
					<a href="#" class="wbb-cancel-entry">Return to Calendar</a>
				</strong>
			</p>

			<div id="wbb-entries">

				<?php foreach ($wbb_user->entries as $entry) { ?>
					<div class="wbb-single-entry" data-day="<?php echo date('j', strtotime($entry->entry_date)); ?>">
						<?php echo number_format($entry->miles, 2); ?>
						miles to
						<?php echo $entry->location->title; ?>
					</div>
				<?php } ?>

			</div>

			<p>
				<strong>
					Add a New Entry
				</strong>
			</p>

			<form>

				<div>
					<label for="location-id">Location</label>
					<select id="location-id">
						<option value="0|0">
							Choose a location or enter a new one below:
						</option>
						<?php foreach ($wbb_user->locations as $location) { ?>
							<option value="<?php echo $location->id; ?>|<?php echo $location->miles; ?>">
								<?php echo $location->title; ?> (last mileage: <?php echo number_format($location->miles, 2); ?>)
							</option>
						<?php } ?>
					</select>
				</div>

				<div>
					<label for="title">New Location Name (ex: Work)</label>
					<input type="text" id="title">
				</div>

				<div>
					<label for="mode">Mode</label>
					<select id="mode">
						<option value="<?php echo \WalkBikeBus\Entry::MODE_WALK; ?>">Walk</option>
						<option value="<?php echo \WalkBikeBus\Entry::MODE_BIKE; ?>">Bike</option>
						<option value="<?php echo \WalkBikeBus\Entry::MODE_BUS; ?>">Bus</option>
					</select>
				</div>

				<div>
					<label for="miles">Miles</label>
					<input type="text" id="miles">
				</div>

				<button class="wbb-submit-entry">Submit</button>
				<button class="wbb-cancel-entry">Cancel</button>

			</form>

		</div>

	</div>

<?php } ?>
