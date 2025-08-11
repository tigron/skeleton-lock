<?php

declare(strict_types=1);

/**
 * Exception Lock_Failed
 */

namespace Skeleton\Lock\Exception;

class Failed extends \Skeleton\Lock\Exception {

	/**
	 * Constructor
	 *
	 * @access public
	 * @param string $message
	 */
	public function __construct($message = 'can not get lock') {
		$this->message = $message;
	}
}