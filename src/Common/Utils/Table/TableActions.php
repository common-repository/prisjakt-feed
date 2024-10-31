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

use PrisjaktFeed\Common\Utils\Form\FormActions;

/**
 *
 * @since 1.0.0
 */
class TableActions extends FormActions {

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
			$actions[] = new TableAction( $action );
		endforeach;

		return $actions;
	}
}
