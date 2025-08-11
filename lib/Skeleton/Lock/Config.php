<?php

declare(strict_types=1);

/**
 * Configuration for Skeleton\Lock
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Lock;

class Config {

	/**
	 * Locking handler
	 *
	 * Defaults to 'database'
	 *
	 * @access public
	 */
	public static string $handler = 'database';

	/**
	 * Configuration for the given locking handler
	 *
	 * @access public
	 */
	public static array $handler_config = [];

	/**
	 * Expiration
	 * 
	 * Ignore existing locks older than $expiration
	 * Setting this to 0 or null disables timeouts
	 *
	 * @access public
	 */
	public static ?int $expiration = 10;
}
