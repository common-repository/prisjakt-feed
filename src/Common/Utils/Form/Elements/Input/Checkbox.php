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
class Checkbox extends Element {

	public function __construct( $element ) {
		$this->data['required_enabled'] = false;
		$this->data['value']            = false;
		$this->data['label']            = '';

		parent::__construct( $element );
	}


	/**
	 * @return bool
	 */
	public function get_required_enabled(): bool {
		return (bool) $this->data['required_enabled'];
	}

	/**
	 * @return bool
	 */
	public function get_value(): bool {
		return (bool) $this->data['value'];
	}

	/**
	 * @return string
	 */
	public function get_label(): string {
		return $this->data['label'];
	}
}

