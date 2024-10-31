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

namespace PrisjaktFeed\Common\Utils\Form;

use PrisjaktFeed\App\DataStorage\Feed\GeneralSettingsData;
use PrisjaktFeed\Common\Utils\Fields\Fields;
use PrisjaktFeed\Common\Utils\Table\Columns;
use PrisjaktFeed\Common\Utils\Table\Notices;
use PrisjaktFeed\Common\Utils\Table\Rows;
use PrisjaktFeed\Common\Utils\Table\TableActions;


/**
 * The Base class which can be extended by other classes to load in default methods
 *
 * @package PrisjaktFeed\Common\Utils\Form
 * @since 1.0.0
 */
class Form {



	/**
	 * @var string
	 */
	public $id = '';

	/**
	 * @var string
	 */
	public $title = '';

	/**
	 * @var string[][]
	 */
	protected $notices = [];

	/**
	 * @var string[][]
	 */
	protected $columns = [];

	/**
	 * @var \array[][]
	 */
	protected $rows = [];

	/**
	 * @var \array[][]
	 */
	protected $hidden_rows = [];

	/**
	 * @var array
	 */
	protected $table_actions = [];

	/**
	 * @var array
	 */
	protected $form_actions = [];

	/**
	 * @var string
	 */
	protected $form_template = 'rows-table';

	/**
	 * @var GeneralSettingsData
	 */
	public $data_provider;


	/**
	 * @param string $id
	 */
	public function set_id( string $id ): void {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function get_id(): string {
		$id = $this->id;

		return ( new Fields() )->get_un_prefix_value( $id );
	}

	/**
	 * @return string
	 */
	public function get_action_name(): string {
		$id = $this->get_id();

		return "{$id}-action-update-form";
	}

	/**
	 * @return string
	 */
	public function get_nonce_field_name(): string {
		$id = $this->get_id();

		return "{$id}_nonce_field";
	}

	/**
	 * @param string $title
	 */
	public function set_title( string $title ): void {
		$this->title = $title;
	}

	/**
	 * @param string[][] $notices
	 */
	public function set_notices( array $notices ): void {
		$this->notices = $notices;
	}

	/**
	 * @param string[][] $columns
	 */
	public function set_columns( array $columns ): void {
		$this->columns = $columns;
	}

	/**
	 * @param array $rows
	 */
	public function set_rows( array $rows ): void {
		$this->rows = $rows;
	}

	/**
	 * @param array $hidden_rows
	 */
	public function set_hidden_rows( array $hidden_rows ): void {
		$this->hidden_rows = $hidden_rows;
	}

	/**
	 * @param array $table_actions
	 */
	public function set_table_actions( array $table_actions ): void {
		$this->table_actions = $table_actions;
	}

	/**
	 * @param array $form_actions
	 */
	public function set_form_actions( array $form_actions ): void {
		$this->form_actions = $form_actions;
	}

	/**
	 * @return string
	 */
	public function get_title(): string {
		return $this->title;
	}

	/**
	 * @return array
	 */
	public function get_notices(): array {
		return ( new Notices( $this->notices ) )->get_notices();
	}

	/**
	 * @return array
	 */
	public function get_columns(): array {
		return ( new Columns( $this->columns ) )->get_columns();
	}

	/**
	 * @param $columns
	 *
	 * @return int|void
	 */
	public function get_columns_count( $columns ) {
		return count( $columns ) - 1;
	}

	/**
	 * @return array
	 */
	public function get_rows(): array {
		return ( new Rows( $this->rows ) )->get_rows();
	}


	/**
	 * @return array
	 */
	public function get_hidden_rows(): array {
		return ( new Rows( $this->hidden_rows ) )->get_rows();
	}

	/**
	 * @return array
	 */
	public function get_table_actions(): array {
		return ( new TableActions( $this->table_actions ) )->get_actions();
	}


	/**
	 * @return array
	 */
	public function get_form_actions(): array {
		return ( new FormActions( $this->form_actions ) )->get_actions();
	}


	/**
	 *
	 */
	public function set_notices_data(): void {
		/**
		 * Set notices data
		 */
	}

	/**
	 *
	 */
	public function set_columns_data(): void {
		/**
		 * Set columns data
		 */
	}


	/**
	 *
	 */
	public function set_rows_data(): void {
		/**
		 * Set rows data
		 */
	}

	/**
	 *
	 */
	public function set_hidden_rows_data(): void {
		/**
		 * Set rows data
		 */
	}

	/**
	 *
	 */
	public function set_table_actions_data(): void {
		/**
		 * Set table actions data
		 */
	}


	/**
	 *
	 */
	public function set_form_actions_data(): void {
		/**
		 * Set form actions data
		 */
	}


	/**
	 *
	 */
	public function init(): void {

	}

	/**
	 *
	 */
	public function __construct() {
		$this->init();
		$action_name = $this->get_action_name();

		add_action( "wp_ajax_nopriv_{$action_name}", [ $this, 'update_form_callback' ] );
		add_action( "wp_ajax_{$action_name}", [ $this, 'update_form_callback' ] );

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}


	/**
	 * Default setter before generate form
	 */
	public function prepare_items(): void {

		$this->set_notices_data();
		$this->set_columns_data();
		$this->set_rows_data();
		$this->set_table_actions_data();
		$this->set_form_actions_data();
		$this->set_hidden_rows_data();

		/**
		 * Type your updates' data here
		 */
	}


	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts(): void {

		if ( apply_filters( 'prisjakt_feed_form_localize_script_load', true ) ) :

			foreach (
				[
					[
						'deps'      => [],
						'handle'    => 'form-feed-form-js',
						'in_footer' => true,
						'source'    => plugins_url( '/assets/public/js/form.js', PRISJAKT_FEED_PLUGIN_FILE ),
                        // phpcs:disable ImportDetection.Imports.RequireImports.Symbol -- this constant is global
						'version'   => prisjakt_feed()->get_data()['version'],
					],
				] as $js
			) {

				if ( wp_script_is( $js['handle'] ) ) {
					return;
				}

				$script_data = apply_filters(
					'prisjakt_feed_form_localize_script_data',
					[
						'form_id'     => $this->get_id(),
						'ajax_url'    => admin_url( 'admin-ajax.php' ),
						'ajax_action' => $this->get_action_name(),
						'ajax_nonce'  => wp_create_nonce( $this->get_nonce_field_name() ),
						'extra_data'  => [
							'action' => $this->get_action_name(),
						],
					]
				);

				wp_enqueue_script( $js['handle'], $js['source'], $js['deps'], $js['version'], $js['in_footer'] );
				wp_localize_script(
					$js['handle'],
					'prisjakt_ajax_object',
					$script_data
				);
			}
		endif;
	}


	/**
	 * @param $form_data
	 * @param $option_name
	 */
	public function update_option( $form_data, $option_name ): void {

		/**
		 * Check option structure
		 */

		$form_options = [];

		wp_parse_str( $form_data['form_elements'], $form_options );

		if ( ( ! $form_options[ $option_name ] ) ||
			( ! is_array( get_option( $option_name ) ) ) ) {
			update_option( $option_name, [] );
		}

		$new_options         = [];
		$defined_options_ids = array_flip( array_column( $this->data_provider->get_options(), 'id' ) );

		foreach ( $form_options[ $option_name ] as $form_option_name => $form_option_value ) :

			if ( ! isset( $defined_options_ids[ $form_option_name ] ) ) {
				continue;
			}

			$new_options[ $form_option_name ] = true;

		endforeach;

		update_option( $option_name, $new_options );
	}

	/**
	 * @param $form_data
	 * @param $meta_name
	 */
	public function update_meta( $form_data, $meta_name ): void {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$feed_id = isset( $_REQUEST['post_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['post_id'] ) ) : '';

		if ( empty( $feed_id ) ) {
			return;
		}

		$form_options = [];

		wp_parse_str( $form_data['form_elements'], $form_options );

		if ( ( ! $form_options[ $meta_name ] ) ) {
			update_post_meta( $feed_id, $meta_name, maybe_serialize( [] ) );
		}

		$new_options = [];

		foreach ( $form_options[ $meta_name ] as $form_field_name => $form_field_value ) :
			$new_options[ $form_field_name ] = $form_field_value;
		endforeach;

		/*
		 *  Format new options $_POST data for more readable
		 */

		$formatted_new_options = [];

		foreach ( $new_options as $option_key => $option_values ) {

			if ( is_array( $option_values ) || is_object( $option_values ) ) {
				foreach ( $option_values as $meta_value_index => $meta_value ) {
					$formatted_new_options[ $meta_value_index ][ $option_key ] = $meta_value;
				}
			}
		}

		$formatted_new_options = $this->filter_new_options( $formatted_new_options );
		$formatted_new_options = $this->validate_new_options( $formatted_new_options );
		$formatted_new_options = $this->strip_values_new_options( $formatted_new_options );

		$this->data_provider->set_post_meta( $feed_id, $formatted_new_options );

		$this->update_post( $feed_id, $form_options );
	}
	/**
	 * Strip all html tags form all values
	 */

	/**
	 * @param $new_options
	 *
	 * @return array
	 */
	public function filter_new_options( $new_options ): array {
		return $new_options;
	}

	/**
	 * @param $new_options
	 *
	 * @return array
	 */
	public function validate_new_options( $new_options ): array {

		$required_fields = $this->data_provider->get_required_fields_ids();

		foreach ( $new_options as $row_index => $row ) :
			foreach ( $row as $field_key => $field_value ) :

				/**
				 * Remove row if not have required fields values
				 */
				if ( empty( $field_value ) && in_array( $field_key, $required_fields, true ) ) {
					unset( $new_options[ $row_index ] );
				}

			endforeach;
		endforeach;

		return array_values( $new_options );
	}

	/**
	 * @param $new_options
	 *
	 * @return array
	 */
	public function strip_values_new_options( $new_options ): array {

		foreach ( $new_options as $row_index => $row ) :
			foreach ( $row as $field_key => $field_value ) :

				$new_options[ $row_index ][ $field_key ] = wp_strip_all_tags( $new_options[ $row_index ][ $field_key ] );

			endforeach;
		endforeach;

		return $new_options;
	}

	/**
	 * @param $feed_id
	 * @param $form_options
	 */
	public function update_post( $feed_id, $form_options ): void {

	}

	/**
	 * Settings updater
	 */
	public function update_form_data_process(): void {
        // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$form_data = isset( $_POST['data'] ) ? json_decode( wp_unslash( $_POST['data'] ), true ) : false;

		/**
		 *  For settings page
		 */
		if ( $option_name = $this->data_provider->get_option_name() ) {
			$this->update_option( $form_data, $option_name );
		}

		/**
		 *  For feed post page
		 */
		if ( $meta_name = $this->data_provider->get_meta_name() ) {
			$this->update_meta( $form_data, $meta_name );
		}
	}

	/**
	 * Ajax callback
	 */
	public function update_form_callback(): void {

		check_ajax_referer( $this->get_nonce_field_name(), 'security' );

		if ( ! current_user_can( 'edit_pages' ) ) {
			wp_die( -1 );
		}

		$this->update_form_data_process();

		wp_send_json_success( 'success' );
		die();
	}


	/**
	 * Add extra form wrapper
	 */
	public function load_form_wrapper(): void {

		$form_id = $this->get_id();

		echo "<form id='" . esc_attr( $form_id ) . "'>";

		wp_nonce_field( $this->get_action_name(), $this->get_nonce_field_name() );
		wp_referer_field();
		$this->load_content();

		echo '</form>';
	}

	/**
	 * Render only table content
	 */
	public function load_content(): void {
		$form_template = $this->form_template;

		$columns = $this->get_columns();
		prisjakt_feed()->templates()->get(
			"backend/table/{$form_template}",
			null,
			[
				'id'            => $this->get_id(),
				'title'         => $this->get_title(),
				'notices'       => $this->get_notices(),
				'columns'       => $columns,
				'columns_count' => $this->get_columns_count( $columns ),
				'rows'          => $this->get_rows(),
				'hidden_rows'   => $this->get_hidden_rows(),
				'table_actions' => $this->get_table_actions(),
				'form_actions'  => $this->get_form_actions(),
			]
		);
	}


	/**
	 * Render Table or Form with Table
	 */
	public function display(): void {
		$this->prepare_items();

		if ( apply_filters( 'prisjakt_feed_load_form_wrapper', true ) ) {
			$this->load_form_wrapper();
		} else {
			$this->load_content();
		}
	}
}
