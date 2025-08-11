<?php

declare(strict_types=1);

/**
 * Exception
 */

namespace Skeleton\Lock;

class Exception extends \Exception {

	/**
	 * Constructor
	 *
	 * @access public
	 * @param ?string $message
	 */
	public function __construct(?string $message = null) {
		if (!empty($message)) {
			$this->message = $message;
		}
	}
}