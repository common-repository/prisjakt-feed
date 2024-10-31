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

use PrisjaktFeed\Common\Utils\Form\FormAction;

/**
 *
 * @since 1.0.0
 */
class TableAction extends FormAction {

	/**
	 * @var array
	 */
	protected $action_types = [
		'add_field_mapping',
		'add_custom_field',
		'add_filter',
	];
}
