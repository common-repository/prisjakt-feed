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
class Columns {

	/**
	 * @var array
	 */
	protected $columns = [];

	/**
	 * @return array
	 */
	public function get_columns(): array {
		$columns       = [];
		$this->columns = array_merge( $this->get_default_column(), $this->columns );

		foreach ( $this->columns as $index => $column ) :
			$column['index'] = $index;
			$columns[]       = new Column( $column );
		endforeach;

		return $columns;
	}

	/**
	 * @param array $columns
	 */
	public function set_columns( array $columns ): void {
		$this->columns = $columns;
	}

	/**
	 * @param $columns
	 */
	public function __construct( $columns ) {
		$this->set_columns( $columns );
	}

	/**
	 * @return array
	 */
	protected function get_default_column(): array {
		return [
			[ 'class' => [ 'column-id', 'hidden', 'column-primary' ] ],
		];
	}
}
