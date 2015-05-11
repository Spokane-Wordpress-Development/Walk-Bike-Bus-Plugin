<?php

namespace WalkBikeBus;

class Controller {

	private static $initiated = FALSE;
	private static $admin_initiated = FALSE;

	public static function init()
	{
		if ( ! self::$initiated )
		{
			self::$initiated = TRUE;
		}
	}

	public static function admin_init()
	{
		if ( ! self::$admin_initiated )
		{
			self::$admin_initiated = TRUE;
		}
	}
}