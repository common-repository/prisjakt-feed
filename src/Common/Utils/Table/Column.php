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

declare(strict_types=1);

namespace PrisjaktFeed\Common\Utils\Table;

/**
 * The Base class which can be extended by other classes to load in default methods
 *
 * @package PrisjaktFeed\Common\Utils\Table
 * @since 1.0.0
 */
class Column {



	/**
	 * @var int
	 */
	protected $index = 0;
	/**
	 * @var string
	 */
	protected $label = '';

	/**
	 * @var string[]
	 */
	protected $class = [ 'manage-column' ];

	/**
	 * @return int
	 */
	public function get_index(): int {
		return $this->index;
	}

	/**
	 * @param int $index
	 */
	public function set_index( int $index ): void {
		$this->index = $index;
	}

	/**
	 * @return string
	 */
	public function get_label(): string {
		return $this->label;
	}


	/**
	 * @param $label
	 */
	public function set_label( $label ): void {
		$this->label = $label ? $label : $this->label;
	}


	/**
	 * @return string
	 */
	public function get_class(): string {
		return implode( ' ', $this->class );
	}

	/**
	 * @param $class
	 */
	public function set_class( $class ): void {

		$index       = $this->index;
		$this->class = array_merge( $this->class, $class, [ "index-{$index}" ] );
	}


	/**
	 * @param $column
	 */
	public function __construct( $column ) {

		$args = wp_parse_args(
			$column,
			[
				'index' => $this->index,
				'label' => $this->label,
				'class' => $this->class,
			]
		);

		$this->set_index( $args['index'] );
		$this->set_label( $args['label'] );
		$this->set_class( $args['class'] );
	}
}
