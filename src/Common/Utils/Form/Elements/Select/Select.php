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

namespace PrisjaktFeed\Common\Utils\Form\Elements\Select;

use PrisjaktFeed\Common\Utils\Form\Elements\Element;

/**
 * The Base class which can be extended by other classes to load in default methods
 *
 * @since 1.0.0
 */
class Select extends Element {

	/**
	 * @param $element
	 */
	public function __construct( $element ) {
		$this->data['required'] = false;
		$this->data['value']    = '';
		$this->data['options']  = [];

		parent::__construct( $element );
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
	public function get_value(): string {
		return $this->data['value'];
	}

	/**
	 * @return array
	 */
	public function get_options(): array {
		return $this->data['options'];
	}
}
