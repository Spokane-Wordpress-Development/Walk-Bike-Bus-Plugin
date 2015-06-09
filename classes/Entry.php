<?php

namespace WalkBikeBus;

class Entry {

	const MODE_WALK = 'walk';
	const MODE_BIKE = 'bike';
	const MODE_BUS = 'bus';

	public $id = 0;
	public $user_id = 0;
	public $entry_date = '';
	public $mode = '';
	public $miles = 0;
	public $created_at;
	public $updated_at;

	/**
	 * var Location $location;
	 */
	public $location;

	public function create()
	{
		/**
		 * @var \wpdb $wpdb
		 */
		global $wpdb;

		$wpdb->insert(
			$wpdb->prefix.'wbb_entries',
			array (
				'user_id' => $this->user_id,
				'entry_date' => date('Y-m-d', strtotime($this->entry_date)),
				'mode_type' => $this->mode,
				'miles' => $this->miles,
				'location_id' => $this->location->id,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
			),
			array(
				'%d',
				'%s',
				'%s',
				'%f',
				'%d',
				'%s',
				'%s',
			)
		);
		$this->id = $wpdb->insert_id;
	}

	public function read()
	{
		/**
		 * @var \wpdb $wpdb
		 */
		global $wpdb;

		if ($this->id > 0)
		{
			$sql = $wpdb->prepare("
				SELECT
					*
				FROM
					" . $wpdb->prefix . "wbb_entries
				WHERE
					id = %d",
				$this->id
			);
			if ($obj = $wpdb->get_row($sql))
			{
				$this->user_id = $obj->user_id;
				$this->entry_date = $obj->entry_date;
				$this->mode = $obj->mode;
				$this->miles = $obj->miles;
				$this->created_at = strtotime($obj->created_at);
				$this->updated_at = strtotime($obj->updated_at);

				$this->location = new Location;
				$this->location->id = $obj->location_id;
				$this->location->read();
			}
		}
	}

	public function update()
	{
		/**
		 * @var \wpdb $wpdb
		 */
		global $wpdb;

		$wpdb->update(
			$wpdb->prefix.'wbb_entries',
			array (
				'user_id' => $this->user_id,
				'entry_date' => date('Y-m-d', strtotime($this->entry_date)),
				'mode' => $this->mode,
				'miles' => $this->miles,
				'location_id' => $this->location->id,
				'updated_at' => date('Y-m-d H:i:s')
			),
			array(
				'id' => $this->id
			),
			array(
				'%d',
				'%s',
				'%s',
				'%f',
				'%d',
				'%s',
			),
			array(
				'%d'
			)
		);
	}

	public function delete()
	{
		/**
		 * @var \wpdb $wpdb
		 */
		global $wpdb;

		$wpdb->delete(
			$wpdb->prefix.'wbb_entries',
			array(
				'id' => $this->id
			),
			array(
				'%d'
			)
		);

		$this->id = 0;
	}

	public static function getEntriesByUserId($user_id, $day=0, $month=0, $year=0)
	{
		/**
		 * @var \wpdb $wpdb
		 */
		global $wpdb;

		$sql = $wpdb->prepare("
			SELECT
				we.*,
				wl.title
			FROM
				" . $wpdb->prefix . "wbb_entries we
			JOIN
				" . $wpdb->prefix . "wbb_locations wl
				ON we.location_id = wl.id
			WHERE
				we.user_id = %d
				AND
				(
					0 = %d
					OR DAY(we.entry_date) = %d
				)
				AND
				(
					0 = %d
					OR MONTH(we.entry_date) = %d
				)
				AND
				(
					0 = %d
					OR YEAR(we.entry_date) = %d
				)
			ORDER BY
				we.entry_date ASC",
			$user_id,
			$day,
			$day,
			$month,
			$month,
			$year,
			$year
		);

		if ($results = $wpdb->get_results($sql))
		{
			return $results;
		}

		return array();
	}
}