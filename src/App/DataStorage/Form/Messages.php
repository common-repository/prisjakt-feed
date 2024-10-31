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

namespace PrisjaktFeed\App\DataStorage\Form;

/**
 * Class Messages
 *
 * @since 1.0.0
 */
class Messages {

	/**
	 * Messages for input validation
	 */
	public function get_messages(): array {
		return [
			'text_value_missing'   => __( 'Please fill in this field.', 'prisjakt-feed' ),
			'select_value_missing' => __( 'Select an item from the list.', 'prisjakt-feed' ),
			'max_text_length'      => __( 'Maximum text length', 'prisjakt-feed' ),
			'min_text_length'      => __( 'Minimum text length', 'prisjakt-feed' ),
		];
	}

}
