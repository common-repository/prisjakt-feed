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

namespace PrisjaktFeed\App\Backend\Core\Product;

use PrisjaktFeed\App\DataStorage\Settings\ExtraFieldsData;
use PrisjaktFeed\Common\Abstracts\Base;
use PrisjaktFeed\Common\Utils\Fields\Fields;

/**
 * Class Product
 *
 * @since 1.0.0
 */
class Product extends Base {



	/**
	 * @var ExtraFieldsData
	 */
	public $extra_fields;


	public $fields;

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init(): void {
		/**
		 * This backend class is only being instantiated in the backend as requested in the Bootstrap class
		 *
		 * @see Requester::is_admin_backend()
		 * @see Bootstrap::__construct
		 *
		 * Add plugin code here for admin settings specific functions
		 */

		$this->extra_fields = new ExtraFieldsData();
		$this->fields       = new Fields();

		/*
		 * TODO save data to one post meta field
		 */

		add_action( 'woocommerce_product_options_general_product_data', [ $this, 'custom_general_fields' ] );
		add_action( 'woocommerce_product_after_variable_attributes', [ $this, 'custom_variable_fields' ], 10, 3 );
		add_action( 'woocommerce_process_product_meta', [ $this, 'save_custom_general_fields' ], 10, 1 );
		add_action( 'woocommerce_save_product_variation', [ $this, 'save_custom_variable_fields' ], 10, 2 );
	}


	/**
	 * @param $option
	 *
	 * @return mixed
	 */
	public function exclude_args( $option ) {

		$excluded_args = $this->extra_fields->get_option_extra_args();

		/*
		 * Remove not used args
		 */

		foreach ( $excluded_args as $excluded_arg ) :
			if ( ! isset( $option[ $excluded_arg ] ) ) {
				continue;
			}

			unset( $option[ $excluded_arg ] );
		endforeach;

		return $option;
	}

	/**
	 * @param $option_name
	 * @param $data
	 * @param $meta_id
	 * @param $attribute_name
	 *
	 * @return bool
	 */
	public function show_field( $option_name, $data, $meta_id, $attribute_name ): bool {
		return array_key_exists(
			$option_name,
			$data
		) || ( ! empty( get_post_meta( $meta_id, $attribute_name, true ) ) );
	}

	/**
	 * Show general settings product
	 */
	public function custom_general_fields(): void {
		global $post;

		/*
		 * Extra fields database data
		 */
		$extra_fields_data = get_option( $this->extra_fields->get_option_name() );

		echo "<div class='options_group show_if_simple'>";

		foreach ( $this->extra_fields->get_options() as $option ) :
			$id = $this->fields->get_prefixed_value( $option['id'] );

			if ( $this->show_field( $option['id'], $extra_fields_data, $post->ID, $id ) ) {

				$type            = $option['type'];
				$option          = $this->exclude_args( $option );
				$option['value'] = get_post_meta( $post->ID, $id, true );
				$option['id']    = $id;

				call_user_func( "woocommerce_wp_{$type}", $option );
			}

		endforeach;

		echo '</div>';
	}

	/**
	 * @param $loop
	 * @param $variation_data
	 * @param $variation
	 */
	public function custom_variable_fields( $loop, $variation_data, $variation ): void {
		/*
		 * Extra fields database data
		 */
		$extra_fields_data = get_option( $this->extra_fields->get_option_name() );

		foreach ( $this->extra_fields->get_options() as $option ) :
			$id = $this->fields->get_prefixed_value( 'variable_' . $option['id'] ) . '[' . $loop . ']';

			if ( $this->show_field( $option['id'], $extra_fields_data, $variation->ID, $id ) ) {

				$type                    = $option['type'];
				$option                  = $this->exclude_args( $option );
				$option['wrapper_class'] = 'form-row';
				$option['value']         = get_post_meta(
					$variation->ID,
					$this->fields->get_prefixed_value( $option['id'] ),
					true
				);
				$option['id']            = $id;

				call_user_func( "woocommerce_wp_{$type}", $option );
			}

		endforeach;

	}

	/**
	 * @param $options
	 *
	 * @return array
	 */
	public function get_checkboxes_fields( $options ): array {
		return array_filter(
			$options,
			static function ( $option ) {
				return 'checkbox' === $option['type'];
			}
		);
	}

	/**
	 * Uncheck checkbox if $_POST do not have key with checkbox name
	 *
	 * @param $post_id
	 * @param $options
	 */
	public function filter_checkboxes_fields( $post_id, $options ): void {
		$checkbox_fields = $this->get_checkboxes_fields( $options );

		foreach ( $checkbox_fields as $checkbox_field ) :
			$id = $checkbox_field['id'];

            // phpcs:ignore WordPress.Security.NonceVerification.Missing
			if ( ! isset( $_POST[ $id ] ) ) {
				$prefix_key = $this->fields->get_prefixed_value( $id );
				update_post_meta( $post_id, $prefix_key, '' );
			}

		endforeach;
	}

	/**
	 * Save extra fields data (Simple Product)
	 *
	 * @param $post_id
	 */
	public function save_custom_general_fields( $post_id ): void {
		$options = $this->extra_fields->get_options();

		$this->filter_checkboxes_fields( $post_id, $options );
		$options_ids = array_flip( array_column( $options, 'id' ) );

        // phpcs:ignore WordPress.Security.NonceVerification.Missing
		foreach ( $_POST as $key => $value ) :
			$un_prefix_key = $this->fields->get_un_prefix_value( $key );

			if ( ! isset( $options_ids[ $un_prefix_key ] ) ) {
				continue;
			}

			update_post_meta( $post_id, $key, wc_clean( wp_unslash( $value ) ) );

		endforeach;
	}

	/**
	 * Save extra fields data (Variable Product)
	 *
	 * @param $variation_id
	 * @param $i
	 */
	public function save_custom_variable_fields( $variation_id, $i ): void {
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$variation_sku = isset( $_POST['variable_sku'] ) && sanitize_text_field( wp_unslash( $_POST['variable_sku'] ) );

		if ( $variation_sku ) {
			$options     = $this->extra_fields->get_options();
			$options_ids = array_flip( array_column( $options, 'id' ) );

            // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$variable_post_id = isset( $_POST['variable_post_id'] ) ? sanitize_text_field( wp_unslash( $_POST['variable_post_id'] ) ) : [];

			/**
			 * Filter checkboxes values
			 */
			$checkbox_fields = $this->get_checkboxes_fields( $options );

			foreach ( $checkbox_fields as $checkbox_field ) :
				$id = $checkbox_field['id'];

				/**
				 * Uncheck checkbox if $_POST do not have key with checkbox name
				 */

                // phpcs:ignore WordPress.Security.NonceVerification.Missing
				if ( ! isset( $_POST[ $id ][ $i ] ) ) {
					$prefix_key = $this->fields->get_prefixed_value( $id );
					update_post_meta( $variation_id, $prefix_key, '' );
				}

			endforeach;

			/**
			 *  Filter $_POST values
			 */
            // phpcs:ignore WordPress.Security.NonceVerification.Missing
			foreach ( $_POST as $key => $value ) :

				$un_prefix_key = $this->fields->get_un_prefix_value(
					str_replace(
						'variable_',
						'',
						$key
					)
				);

				if ( isset( $options_ids[ $un_prefix_key ] ) ) {

					if ( ! isset( $variable_post_id[ $i ] ) ) {
						continue;
					}

					update_post_meta(
						$variation_id,
						$this->fields->get_prefixed_value( $un_prefix_key ),
						stripslashes( sanitize_text_field( $value[ $i ] ) )
					);
				}

			endforeach;
		}
	}
}
