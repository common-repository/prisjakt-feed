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

namespace PrisjaktFeed\Common\Utils\Form;

/**
 *
 * @since 1.0.0
 */
class FormActions {

	/**
	 * @var array
	 */
	protected $actions = [];

	/**
	 * @return array
	 */
	public function get_actions(): array {
		$actions = [];

		foreach ( $this->actions as $action ) :
			$actions[] = new FormAction( $action );
		endforeach;

		return $actions;
	}

	/**
	 * @param $actions
	 */
	public function __construct( $actions ) {
		$this->actions = $actions;
	}
}
