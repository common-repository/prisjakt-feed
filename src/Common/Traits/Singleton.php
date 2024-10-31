<?php
/**
 * Prisjakt Feed
 *
 * @package   prisjakt-feed
 * @author    Prisjakt <support@prisjakt.nu>
 * @copyright 2022 Prisjakt Feed
 * @license   MIT
 * @link      https://prisjakt.nu
 */

declare( strict_types=1 );

namespace PrisjaktFeed\Common\Traits;

/**
 * The singleton skeleton trait to instantiate the class only once
 *
 * @package PrisjaktFeed\Common\Traits
 * @since 1.0.0
 */
trait Singleton {
	private static $instance;

	final private function __construct() {
	}

	private function __clone() {
	}

	final public function __wakeup() {
	}

	/**
	 * @return self
	 * @since 1.0.0
	 */
	final public static function init(): self {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}
