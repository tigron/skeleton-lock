<?php

declare(strict_types=1);

/**
 * Memcache lock handler
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Lock\Handler;

class Memcache extends \Skeleton\Lock\Handler {

	/**
	 * Local Memcache instance
	 */
	private static ?\Memcache $instance = null;

	/**
	 * Get and/or connect the Memcache object
	 */
	private static function get_instance(): self {
		if (self::$instance === null) {
			self::$instance = new \Memcache();
			self::$instance->connect(
				\Skeleton\Lock\Config::$handler_config['hostname'],
				\Skeleton\Lock\Config::$handler_config['port']
			);
		}

		return self::$instance;
	}

	/**
	 * Get lock
	 * 
	 * @access public
	 */
	public static function get_lock(string $name, $expiration = false): void {
		$mc = self::get_instance();

		if ($expiration === false) {
			// default expiration
			$expiration = \Skeleton\Lock\Config::$expiration;
		} elseif (empty($expiration)) {
			// disable expiration
			$expiration = 0;
		}

		if ($mc->add($name, 1, false, \Skeleton\Lock\Config::$expiration) === false) {
			throw new \Skeleton\Lock\Exception\Failed();
		}
	}

	/**s
	 * Release lock
	 * 
	 * @access public
	 */
	public static function release_lock(string $name): void {
		$mc = self::get_instance();
		$mc->delete($name);
	}
}