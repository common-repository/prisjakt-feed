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

namespace PrisjaktFeed\App\DataStorage;

use PrisjaktFeed\App\DataStorage\Attributes\Attributes;

/**
 * Class SettingsData
 *
 * @since 1.0.0
 */
class DataStorage {


	/**
	 * @var Attributes
	 */
	public $attributes_instance;

	public function __construct() {
		$this->attributes_instance = new Attributes();
	}

	/**
	 * @return string
	 */
	public function get_option_name(): string {
		return '';
	}

	/**
	 * @return string
	 */
	public function get_meta_name(): string {
		return '';
	}


	/**
	 * @param $option_field_name
	 * @param $value
	 */
	public function set_option_value( $option_field_name, $value ): void {
		$option_name                  = $this->get_option_name();
		$option                       = get_option( $option_name );
		$option[ $option_field_name ] = $value;

		update_option( $option_name, $option );
	}


	public function get_option_value() {
		$option_name = $this->get_option_name();

		return get_option( $option_name );
	}

	/**
	 * @param $post_id
	 *
	 * @return array|string
	 */
	public function get_post_meta( $post_id ) {

		$meta_name = $this->get_meta_name();
		$value     = maybe_unserialize( get_post_meta( $post_id, $meta_name, true ) );

		if ( is_array( $value ) ) {
			return $value;
		}

		return [];
	}


	/**
	 * @param $post_id
	 * @param array   $options
	 */
	public function set_post_meta( $post_id, array $options = [] ): void {
		$meta_name = $this->get_meta_name();
		update_post_meta( $post_id, $meta_name, ( $options ) );
	}

	/**
	 * @return array
	 */
	public function get_fields(): array {
		return [];
	}

	/**
	 * @return array
	 */
	public function get_options(): array {
		return [];
	}

	/**
	 * Get required fields ids
	 */
	public function get_required_fields_ids(): array {
		$fields          = $this->get_fields();
		$required_fields = [];

		foreach ( $fields as $row ) :
			foreach ( $row as $cell ) :
				if ( isset( $cell['required'] ) && $cell['required'] ) {
					$required_fields[] = $cell['id'];
				}
			endforeach;
		endforeach;

		return array_unique( $required_fields );
	}


	/**
	 * Get required fields ids for plugin activation
	 *
	 * @return array
	 */
	public function get_required_enabled_options(): array {
		$options = $this->get_options();

		if ( $options ) {
			return array_map(
				static function ( $default_enabled_option ) {
					return $default_enabled_option['id'];
				},
				array_values(
					array_filter(
						$options,
						static function ( $option ) {
							return $option['required_enabled'] ?? false;
						}
					)
				)
			);
		}

		return [];
	}
}
