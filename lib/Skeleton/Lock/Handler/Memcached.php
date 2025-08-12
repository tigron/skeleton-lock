<?php

declare(strict_types=1);

/**
 * Memcached lock class
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Lock\Handler;

class Memcached extends \Skeleton\Lock\Handler {

	/**
	 * Local Memcached instance
	 */
	private static ?\Memcached $instance = null;

	/**
	 * Get and/or connect the Memcache object
	 */
	private static function get_instance(): \Memcached {
		if (self::$instance === null) {
			self::$instance = new \Memcached();
			self::$instance->addServers([
				[
					\Skeleton\Lock\Config::$handler_config['hostname'],
					\Skeleton\Lock\Config::$handler_config['port'],
				]
			]);
		}

		return self::$instance;
	}

	/**
	 * Get lock
	 * 
	 * @access public
	 */
	public static function get_lock(string $name, int|bool|null $expiration = false, bool $retried = false): void {
		$name = 'lock.' . $name;

		$mc = self::get_instance();

		if ($expiration === false) {
			// default expiration
			$expiration = \Skeleton\Lock\Config::$expiration;
		} elseif (empty($expiration)) {
			// disable expiration
			$expiration = 0;
		}

		if ($mc->add($name, 1, $expiration) === false) {
			// memcached will disconnect after being idle, add one retry
			if ($mc->getResultCode() === 26 && $retried === false) {
				self::get_lock($name, $expiration, true);
				return;
			}

			throw new \Skeleton\Lock\Exception\Failed('could not get lock: memcached: error code ' . $mc->getResultCode() . ': ' . $mc->getResultMessage());
		}
	}

	/**
	 * Wait until a lock is acquired
	 *
	 * @access public
	 */
	public static function wait_lock(string $name, int|bool|null $expiration = false, float $wait = 10): void {
		$start = microtime(true);

		while ((microtime(true) - $start) < $wait) {
			try {
				self::get_lock($name, $expiration, $wait);
				return;
			} catch (\Skeleton\Lock\Exception\Failed $e) {}

			usleep(100000);
		}

		throw new \Skeleton\Lock\Exception\Failed();
	}

	/**
	 * Release lock
	 * 
	 * @access public
	 */
	public static function release_lock(string $name): void {
		$name = 'lock.' . $name;

		$mc = self::get_instance();
		$mc->delete($name);
	}
}