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
class Notices {

	/**
	 * @var array
	 */
	protected $notices = [];

	/**
	 * @return array
	 */
	public function get_notices(): array {
		$notices = [];

		foreach ( $this->notices as $notice ) :
			$notices[] = new Notice( $notice );
		endforeach;

		return $notices;
	}


	public function set_notices( array $notices ): void {
		$this->notices = $notices;
	}


	public function __construct( $notices ) {
		$this->set_notices( $notices );
	}
}
