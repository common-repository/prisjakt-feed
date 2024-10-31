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

namespace PrisjaktFeed\Common\Utils\Form\Elements;

/**
 * The Base class which can be extended by other classes to load in default methods
 *
 * @package PrisjaktFeed\Common\Utils\Input
 * @since 1.0.0
 */
class Label extends Element {

	public function __construct( $element ) {
		$this->data['text'] = '';

		parent::__construct( $element );
	}

	/**
	 * @return string
	 */
	public function get_text(): string {
		return $this->data['text'];
	}
}

