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
class Row {

	/**
	 * @var array
	 */
	protected $cells = [];

	/**
	 * @return array
	 */
	public function get_cells(): array {
		$cells = [];

		foreach ( $this->cells as $index => $cell ) :
			$cell['index'] = $index;

			$cells[] = new Cell( $cell );
		endforeach;

		return $cells;
	}

	/**
	 * @param $cells
	 */
	public function set_cells( $cells ): void {
		$this->cells = $cells;
	}

	/**
	 * @param $cells
	 */
	public function __construct( $cells ) {
		$this->set_cells( $cells );
	}
}
