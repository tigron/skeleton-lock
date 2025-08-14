<?php

declare(strict_types=1);

/**
 * Abstrack lock handler class
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Lock;

abstract class Handler {
	/**
	 * Get lock
	 *
	 * @access public
	 */
	abstract public static function obtain(string $name, int|bool|null $expiration = false): void;

	/**
	 * Wait until a lock is acquired
	 *
	 * @access public
	 */
	abstract public static function wait(string $name, int|bool|null $expiration = false, float $wait = 10): void;

	/**
	 * Release lock
	 *
	 * @access public
	 */
	abstract public static function release(string $name): void;

	/**
	 * Get class
	 * 
	 * @access public
	 */
	public static function get(): string {
		switch (strtolower(Config::$handler)) {
			case 'database':
				return Handler\Database::class;
			case 'memcache':
				return Handler\Memcache::class;
			case 'memcached':
				return Handler\Memcached::class;
			case 'file':
				return Handler\File::class;
			default:
				throw new Exception('Unsupported handler');
		}
	}
}