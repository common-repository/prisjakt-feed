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

namespace PrisjaktFeed\Common\Utils\Table;

/**
 * The Base class which can be extended by other classes to load in default methods
 *
 * @package PrisjaktFeed\Common\Utils\Table
 * @since 1.0.0
 */
class Notice {

	/**
	 * @var array|object
	 */
	protected $notice = [
		'class'   => [],
		'message' => '',
	];

	/**
	 * @return string
	 */
	public function get_message(): string {
		return $this->notice['message'];
	}

	/**
	 * @return string|void
	 */
	public function get_class() {
		return esc_attr( ( implode( ' ', $this->notice['class'] ) ) );
	}

	/**
	 * @param $notice
	 */
	public function __construct( $notice ) {

		$args = wp_parse_args(
			$notice,
			$this->notice
		);

		$this->notice = $args;
	}
}
