<?php

declare(strict_types=1);

/**
 * File lock class
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Lock\Handler;

class File extends \Skeleton\Lock\Handler {

	/**
	 * Get lock
	 * 
	 * @access public
	 */
	public static function obtain(string $name, int|bool|null $expiration = false): void {
		$filename = self::get_filepath($name);

		$handle = @fopen($filename, 'x');

		if ($handle === false) {
			// if we didn't get a handle, check if the file maybe expired
			if ($expiration === false) {
				// default expiration
				$expiration = \Skeleton\Lock\Config::$expiration;
			}

			// if we have a set expiration and the file expired, unlink and ignore
			if (!empty($expiration) && is_int($expiration) && (time() - filemtime($filename)) >= $expiration) {
				unlink($filename);
				return;
			}

			throw new \Skeleton\Lock\Exception\Failed('can not get lock: lockfile exists');
		}

		fclose($handle);		
	}

	/**
	 * Wait until a lock is acquired
	 *
	 * @access public
	 */
	public static function wait(string $name, int|bool|null $expiration = false, float $wait = 10): void {
		$start = microtime(true);

		while ((microtime(true) - $start) < $wait) {
			try {
				self::obtain($name, $expiration, $wait);
				return;
			} catch (\Skeleton\Lock\Exception\Failed $e) {}

			usleep(100000);
		}

		throw new \Skeleton\Lock\Exception\Failed('can not get lock: lockfile exists');
	}

	/**
	 * Release lock
	 * 
	 * @access public
	 */
	public static function release(string $name): void {
		$filename = self::get_filepath($name);

		if (is_file($filename)) {
			unlink($filename);
		}
	}

	/**
	 * Get the full path of the lockfile
	 * 
	 * @access private
	 */
	private static function get_filepath($filename): string {
		// maybe add sanitisation at some point
		return realpath(\Skeleton\Lock\Config::$handler_config['path'] . '/') . '/' . $filename;
	}
}