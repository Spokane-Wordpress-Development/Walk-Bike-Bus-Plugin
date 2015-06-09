<?php

namespace WalkBikeBus;

class Location {

	public $id = 0;
	public $user_id = 0;
	public $title = '';
	public $miles = 0;
	public $created_at;

	public function create()
	{
		/**
		 * @var \wpdb $wpdb
		 */
		global $wpdb;

		$wpdb->insert(
			$wpdb->prefix.'wbb_locations',
			array (
				'user_id' => $this->user_id,
				'title' => $this->title,
				'miles' => $this->miles,
				'created_at' => date('Y-m-d H:i:s')
			),
			array(
				'%d',
				'%s',
				'%f',
				'%s'
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
					" . $wpdb->prefix . "wbb_locations
				WHERE
					id = %d",
				$this->id
			);
			if ($obj = $wpdb->get_row($sql))
			{
				$this->user_id = $obj->user_id;
				$this->title = $obj->title;
				$this->miles = $obj->miles;
				$this->created_at = strtotime($obj->created_at);
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
			$wpdb->prefix.'wbb_locations',
			array (
				'user_id' => $this->user_id,
				'title' => $this->title,
				'miles' => $this->miles,
				'created_at' => date('Y-m-d H:i:s')
			),
			array(
				'id' => $this->id
			),
			array(
				'%d',
				'%s',
				'%f',
				'%d'
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
			$wpdb->prefix.'wbb_locations',
			array(
				'id' => $this->id
			),
			array(
				'%d'
			)
		);

		$this->id = 0;
	}

	public static function getLocationsByUserId($user_id)
	{
		/**
		 * @var \wpdb $wpdb
		 */
		global $wpdb;

		$sql = $wpdb->prepare("
			SELECT
				*
			FROM
				" . $wpdb->prefix . "wbb_locations
			WHERE
				user_id = %d",
			$user_id
		);

		if ($results = $wpdb->get_results($sql))
		{
			return $results;
		}

		return array();
	}

	public static function checkTitle($user_id, $title)
	{
		/**
		 * @var \wpdb $wpdb
		 */
		global $wpdb;

		$sql = $wpdb->prepare("
			SELECT
				id
			FROM
				" . $wpdb->prefix . "wbb_locations
			WHERE
				user_id = %d
				AND title LIKE '%s'",
			$user_id,
			$title
		);
		if ($row = $wpdb->get_row($sql))
		{
			return $row->id;
		}

		return 0;
	}
}