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
class Rows {

	/**
	 * @var array
	 */
	protected $rows = [];

	/**
	 * @return array
	 */
	public function get_rows(): array {
		$rows = [];

		foreach ( $this->rows as $row ) :
			$rows[] = new Row( $row );
		endforeach;

		return $rows;
	}

	/**
	 * @param $rows
	 */
	public function set_rows( $rows ): void {
		$this->rows = $rows;
	}


	/**
	 * @param $rows
	 */
	public function __construct( $rows ) {
		$this->set_rows( $rows );
	}
}
