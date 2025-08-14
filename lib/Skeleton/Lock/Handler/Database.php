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
	public static function obtain(string $name, int|bool|null $expiration = false, float $wait = 0.001): void {
		if ($expiration !== false) {
			throw new \Skeleton\Lock\Exception\Unsupported('expiration is not supported with this handler');
		}

		if (!empty(\Skeleton\Lock\Config::$handler_config['dsn'])) {
			$db = \Skeleton\Database\Database::get(\Skeleton\Lock\Config::$handler_config['dsn']);
		} else {
			$db = \Skeleton\Database\Database::get();
		}

		try {
			$db->get_lock($name, $wait);
		} catch (\Exception $e) {
			throw new \Skeleton\Lock\Exception\Failed('failed to get database lock');
		}
	}

	/**
	 * Wait until a lock is acquired
	 *
	 * @access public
	 */
	public static function wait(string $name, int|bool|null $expiration = false, float $wait = 10): void {
		self::obtain($name, $expiration, $wait);
	}

	/**
	 * Release lock
	 * 
	 * @access public
	 */
	public static function release(string $name): void {
		$db = \Skeleton\Database\Database::get();
		$db->release_lock($name);
	}
}