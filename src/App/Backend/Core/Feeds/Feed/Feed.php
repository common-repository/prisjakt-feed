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

namespace PrisjaktFeed\App\Backend\Core\Feeds\Feed;

use PrisjaktFeed\App\Backend\Core\Feeds\Feeds;
use PrisjaktFeed\App\Pages\Feed\CategoryMapping;
use PrisjaktFeed\App\Pages\Feed\FieldMapping;
use PrisjaktFeed\App\Pages\Feed\Filters;
use PrisjaktFeed\App\Pages\Feed\GeneralSettings;
use PrisjaktFeed\Config\Plugin;

/**
 * Class Feed
 */
class Feed extends Steps {


	/**
	 * @var string
	 */
	private $body_class = Plugin::PLUGIN_PREFIX . 'edit_feed';

	/**
	 * @var GeneralSettings
	 */
	private $general_settings_instance;
	/**
	 * @var FieldMapping
	 */
	private $field_mapping;

	/**
	 * @var Filters
	 */
	private $filters;

	/**
	 * @var CategoryMapping
	 */
	private $category_mapping;


	/**
	 * Initialize the class.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function init() {
		/**
		 * This backend class is only being instantiated in the backend as requested in the Bootstrap class
		 *
		 * @see Requester::is_admin_backend()
		 * @see Bootstrap::__construct
		 *
		 * Add plugin code here for admin settings specific functions
		 */

		add_action( 'edit_form_after_editor', [ $this, 'register_meta_box' ] );
		add_action( 'init', [ $this, 'load_steps_instances' ] );
		add_filter( 'admin_body_class', [ $this, 'add_extra_body_class' ] );
	}

	/**
	 * @return bool
	 */
	public function is_feed_post_edit(): bool {
		$post_type = Feeds::POST_TYPE['id'];

		return get_post_type() === $post_type;
	}

	/**
	 * @param $classes
	 *
	 * @return string
	 */
	public function add_extra_body_class( $classes ): string {

		if ( $this->is_feed_post_edit() ) {
			return $classes . ' ' . $this->body_class . ' ';
		}

		return $classes;
	}


	/**
	 * Load steps __construct instances only in edit feed post
	 */
	public function load_steps_instances(): void {

		if ( $this->should_load_steps() ) {

			$this->general_settings_instance = new GeneralSettings();
			$this->field_mapping             = new FieldMapping();
			$this->filters                   = new Filters();
			$this->category_mapping          = new CategoryMapping();
		}
	}

	/**
	 * @return bool
	 */
	public function should_load_steps(): bool {
		global $pagenow;

		return ( ( in_array(
			$pagenow,
			[
				'post-new.php',
				'post.php',
			],
			true
		) ) || ( $this->is_feed_post_edit() ) ) || wp_doing_ajax();
	}

	/**
	 * Steps loader
	 */
	public function register_meta_box(): void {

		foreach ( self::get_steps() as $step_id ) :
			$id = Plugin::PLUGIN_PREFIX . $step_id;

			add_meta_box(
				$id,
				' ',
				[ $this, "render_{$step_id}" ],
				Feeds::POST_TYPE['id'],
				'normal',
				'high'
			);

		endforeach;
	}

	/**
	 * Render General Settings step
	 */
	public function render_general_settings(): void {
		$this->general_settings_instance->display();
	}

	/**
	 * Render Field Mapping step
	 */
	public function render_field_mapping(): void {
		$this->field_mapping->display();
	}

	/**
	 * Render Filters step
	 */
	public function render_filters(): void {
		$this->filters->display();
	}

	/**
	 * Render Category Mapping step
	 */
	public function render_category_mapping(): void {
		$this->category_mapping->display();
	}
}
