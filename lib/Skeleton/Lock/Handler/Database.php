<?php

declare(strict_types=1);

/**
 * Database lock handler
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Lock\Handler;

class Database extends \Skeleton\Lock\Handler {

	/**
	 * Get lock
	 * 
	 * @access public
	 */
	public static function get_lock(string $name, $expiration = false): void {
		if ($expiration === false) {
			// default expiration
			$expiration = \Skeleton\Lock\Config::$expiration;
		} elseif (empty($expiration)) {
			// disable expiration
			$expiration = 0;
		}

		$db = \Skeleton\Database\Database::get();
		//$db->get_lock($name, $expiration); // this is not yet supported
		$db->get_lock($name);
	}

	/**
	 * Release lock
	 * 
	 * @access public
	 */
	public static function release_lock(string $name): void {
		$db = \Skeleton\Database\Database::get();
		$db->release_lock($name);
	}
}