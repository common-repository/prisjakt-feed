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

namespace PrisjaktFeed\Common\Utils\Form\Elements\Input;

use PrisjaktFeed\Common\Utils\Form\Elements\Element;

/**
 * The Base class which can be extended by other classes to load in default methods
 *
 * @since 1.0.0
 */
class Text extends Element {

	/**
	 * @param $element
	 */
	public function __construct( $element ) {
		$this->data['min_input_length'] = 1;
		$this->data['max_input_length'] = 200;
		$this->data['required']         = false;
		$this->data['label']            = '';
		$this->data['value']            = '';

		parent::__construct( $element );
	}


	/**
	 * @return string
	 */
	public function get_min_input_length(): string {
		return (string) $this->data['min_input_length'];
	}

	/**
	 * @return string
	 */
	public function get_max_input_length(): string {
		return (string) $this->data['max_input_length'];
	}

	/**
	 * @return bool
	 */
	public function get_required(): bool {
		return (bool) $this->data['required'];
	}

	/**
	 * @return string
	 */
	public function get_label(): string {
		return $this->data['label'];
	}

	/**
	 * @return string
	 */
	public function get_value(): string {
		return $this->data['value'];
	}
}

