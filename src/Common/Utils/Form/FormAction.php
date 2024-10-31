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

use PrisjaktFeed\Config\Plugin;

/**
 *
 * @since 1.0.0
 */
class FormAction {

	/**
	 * @var array
	 */
	protected $action_types = [
		'save_continue',
		'continue',
		'back',
		'generate',
	];

	/**
	 * @var array|object
	 */
	protected $action = [
		'id'    => '',
		'name'  => '',
		'type'  => 'button',
		'label' => '',
		'class' => [],
		'data'  => [
			'action'      => '',
			'ajax_action' => '',
			'prefix'      => Plugin::PLUGIN_PREFIX,
		],
	];

	/**
	 * @return string
	 */
	public function get_data(): string {

		return implode(
			' ',
			array_map(
				static function ( $v, $k ) {
					return sprintf( "data-%s='%s'", $k, $v );
				},
				$this->action['data'],
				array_keys( $this->action['data'] )
			)
		);
	}


	/**
	 * @return string
	 */
	public function get_type(): string {
		return $this->action['type'];
	}

	/**
	 * @return string
	 */
	public function get_label(): string {
		return $this->action['label'];
	}

	/**
	 * @return string
	 */
	public function get_id(): string {
		return $this->action['id'];
	}

	/**
	 * @return string
	 */
	public function get_name(): string {
		return $this->action['name'];
	}


	/**
	 * @return string
	 */
	public function get_class(): string {
		return implode( ' ', array_merge( [ 'button' ], $this->action['class'] ) );
	}

	/**
	 * @param $action
	 */
	public function __construct( $action ) {

		$args = wp_parse_args(
			$action,
			$this->action
		);

		if ( in_array( $args['data']['action'], $this->action_types, true ) ) {
			$this->action = $args;
		}
	}
}
