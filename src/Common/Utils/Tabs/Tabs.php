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

namespace PrisjaktFeed\Common\Utils\Tabs;

/**
 * The Base class which can be extended by other classes to load in default methods
 *
 * @package PrisjaktFeed\Common\Utils\Tabs
 * @since 1.0.0
 */
class Tabs {

	private $tabs = [];

	/**
	 * @return array
	 */
	public function get_tabs(): array {
		$tabs = [];
		foreach ( $this->tabs as $index => $tab ) :
			$tab['index'] = $index;
			$tabs[]       = new Tab( $tab );
		endforeach;

		return $tabs;
	}

	/**
	 * @param array $tabs
	 */
	public function set_tabs( array $tabs ): void {
		$this->tabs = $tabs;
	}


	/**
	 * Tabs constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct( array $tabs ) {

		if ( ! empty( $tabs ) ) {
			$this->set_tabs( $tabs );
		}
	}
}
