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

namespace PrisjaktFeed\Common\Utils\Form\Elements;

/**
 * The Base class which can be extended by other classes to load in default methods
 *
 * @package PrisjaktFeed\Common\Utils\Form
 * @since 1.0.0
 */
class Element {



	/**
	 * All available element types for load template
	 */
	public const FORM_ELEMENTS_TYPES = [
		'input_checkbox',
		'input_radio',
		'input_text',
		'input_hidden',
		'select',
		'select_optgroup',
		'label',
	];


	/**
	 * @var string
	 */
	protected $type = '';

	/**
	 * @var array
	 */
	public $data = [
		'id'    => '',
		'class' => [],
	];

	/**
	 * @return string
	 */
	public function get_id(): string {
		return (string) $this->data['id'];
	}

	/**
	 * @return string
	 */
	public function get_name(): string {
		return (string) $this->data['name'];
	}

	/**
	 * @return string
	 */
	public function get_class(): string {
		return implode( ' ', $this->data['class'] );
	}

	/**
	 * @return string
	 */
	public function get_type(): string {
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function set_type( string $type ): void {
		$this->type = $type;
	}

	/**
	 * @return array
	 */
	public function get_data(): array {
		return $this->data;
	}

	/**
	 * @param array $data
	 */
	public function set_data( array $data ): void {
		$this->data = $data;
	}


	/**
	 * @return string|void
	 */
	public function render() {
		$type = $this->type;

		if ( ! in_array( $type, $this::FORM_ELEMENTS_TYPES, true ) ) {
			esc_html_e( 'Wrong type', 'prisjakt-feed' );

			return;
		}

		return prisjakt_feed()->templates()->get(
			"backend/form/elements/{$type}",
			null,
			[
				'element' => $this,
			]
		);
	}

	/**
	 * @param $element
	 */
	public function __construct( $element ) {

		$args = wp_parse_args(
			$element,
			[
				'type' => $this->type,
				'data' => $this->data,
			]
		);

		$args['data'] = wp_parse_args( $args['data'], $this->data );

		$this->set_type( $args['type'] );
		$this->set_data( $args['data'] );
	}
}
