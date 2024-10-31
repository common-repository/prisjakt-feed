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

namespace PrisjaktFeed\App\Pages\Settings;

use PrisjaktFeed\App\Backend\Core\Settings\Settings;
use PrisjaktFeed\App\DataStorage\Settings\SystemsCheckData;
use PrisjaktFeed\Common\Utils\Form\Form;
use PrisjaktFeed\Config\Requirements;


/**
 * Class Settings
 *
 * @package PrisjaktFeed\App\Backend
 * @since 1.0.0
 */
class SystemsCheckPage extends Form {



	/**
	 * @var string
	 */
	public $id = Settings::TABS[2]['id'];


	public $data_provider;

	/**
	 * @var Requirements
	 */
	private $requirements;

	/**
	 * Init
	 */
	public function init(): void {
		parent::init();
		$this->data_provider = new SystemsCheckData();
		$this->requirements  = new Requirements();

		add_filter(
			'prisjakt_feed_form_localize_script_load',
			function () {
				return false;
			}
		);

		add_filter(
			'prisjakt_feed_load_form_wrapper',
			function () {
				return false;
			}
		);
	}

	/**
	 * Set columns data
	 */
	public function set_columns_data(): void {
		$this->set_columns( $this->data_provider->get_columns() );
	}

	/**
	 * @param $status_name
	 * @return string
	 */
	public function get_status_value( $status_name ): string {

		switch ( $status_name ) {
			case 'wp_cron':
				return $this->requirements->is_enabled_wp_cron() ?

					sprintf(
					/* translators: %s: wp cron */
						'<span class="correct-settings">
                            <span class="dashicons dashicons-yes"></span>
								%s
							</span>',
						__( 'Enabled', 'prisjakt-feed' )
					) :
					sprintf(
					/* translators: %s: wp cron */
						'<span class="bad-settings">%s</span>',
						__( 'Disabled (if you want to use automatic feed generation, please enable WP-Cron)', 'prisjakt-feed' )
					);

			case 'php_version':
				return $this->requirements->get_php_version();

			case 'max_input_vars':
				$max_input_vars = $this->requirements->get_php_max_input_vars();

				if ( $max_input_vars < 5000 ) {
					return sprintf(
					/* translators: %s: max input vars */
						'<span class="bad-settings">
								%s %s.
							</span>',
						__( 'Increase the value max_input_vars in php.ini file, suggested value is 5000 your value', 'prisjakt-feed' ),
						$max_input_vars
					);
				}

				return sprintf(
				/* translators: %s: max input vars */
					'<span class="correct-settings">
                            <span class="dashicons dashicons-yes"></span>
								%s
							</span>',
					$this->requirements->get_php_max_input_vars()
				);

			case 'product_feed_directory_writable':
				$upload_dir          = $this->requirements->get_upload_dir();
				$upload_dir_writable = $this->requirements->is_upload_dir_writable();

				if ( ! $upload_dir_writable ) {
					return sprintf(
					/* translators: %s: writable directory */
						'<span class="bad-settings">
								To allow feed save, make directory <code>%s</code> writable.
							</span>',
						$upload_dir
					);
				}

				return sprintf(
				/* translators: %s: writable directory */
					__(
						'<span class="correct-settings">
                            <span class="dashicons dashicons-yes"></span>
								<code>%s</code>
							</span>',
						'prisjakt-feed'
					),
					$upload_dir
				);

			default:
				return '';
		}
	}

	/**
	 * Set rows data
	 */
	public function set_rows_data(): void {
		$rows = [];

		foreach ( $this->data_provider->get_statuses() as $status_name => $status_label ) :

			$rows[] = [
				[
					'type' => 'label',
					'data' => [
						'text' => $status_label,
					],
				],
				[
					'type' => 'label',
					'data' => [
						'text' => $this->get_status_value( $status_name ),
					],
				],
			];

		endforeach;

		$this->set_rows( $rows );
	}
}
