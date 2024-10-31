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

namespace PrisjaktFeed\App\Backend\Core\Feeds;

use PrisjaktFeed\App\Backend\Core\Feeds\Feed\Feed;

/**
 * Class PostType
 */
class PostType extends Feeds {


	/**
	 * @var Feed
	 */
	protected $feed;

	/**
	 *
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

		add_action( 'init', [ $this, 'register_feed_post_type' ] );
		add_action( 'add_meta_boxes', [ $this, 'remove_meta_boxes' ], 99, 2 );
		add_filter( 'screen_options_show_screen', [ $this, 'remove_screen_options' ], 10, 2 );
		add_filter(
			"get_user_option_screen_layout_{$this::POST_TYPE['id']}",
			[
				$this,
				'user_option_screen_layout',
			],
			10,
			3
		);

		add_action(
			'admin_init',
			function () {
				wp_deregister_script( 'autosave' );
			}
		);

		$this->feed = new Feed();

	}

	/**
	 * Register post type
	 *
	 * @since  1.0.0
	 * @retrun void
	 */
	public function register_feed_post_type(): void {
		register_post_type(
			$this::POST_TYPE['id'],
			[
				'labels'              => [
					'name'           => $this::POST_TYPE['title'],
					'singular_name'  => $this::POST_TYPE['singular'],
					'menu_name'      => $this::POST_TYPE['title'],
					'name_admin_bar' => $this::POST_TYPE['singular'],
					/* translators: add_new */
					'add_new'        => sprintf( __( 'New %s', 'prisjakt-feed' ), $this::POST_TYPE['singular'] ),
					/* translators: add_new_item */
					'add_new_item'   => sprintf( __( 'Add New %s', 'prisjakt-feed' ), $this::POST_TYPE['singular'] ),
					/* translators: new_item */
					'new_item'       => sprintf( __( 'New %s', 'prisjakt-feed' ), $this::POST_TYPE['singular'] ),
					/* translators: edit_item */
					'edit_item'      => sprintf( __( 'Edit %s', 'prisjakt-feed' ), $this::POST_TYPE['singular'] ),
					/* translators: view_item */
					'view_item'      => sprintf( __( 'View %s', 'prisjakt-feed' ), $this::POST_TYPE['singular'] ),
					/* translators: all_items */
					'all_items'      => sprintf( __( 'All %s', 'prisjakt-feed' ), $this::POST_TYPE['title'] ),
					/* translators: search_items */
					'search_items'   => sprintf( __( 'Search %s', 'prisjakt-feed' ), $this::POST_TYPE['title'] ),
				],
				'public'              => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'show_ui'             => true,
				'rewrite'             => [
					'slug'       => $this::POST_TYPE['archive'],
					'with_front' => true,
				],
				'show_in_menu'        => false,
				'query_var'           => true,
				'capability_type'     => 'post',
				'capabilities'        => [
					'edit_post'          => 'activate_plugins',
					'read_post'          => 'activate_plugins',
					'delete_post'        => 'activate_plugins',
					'edit_posts'         => 'activate_plugins',
					'edit_others_posts'  => 'activate_plugins',
					'delete_posts'       => 'activate_plugins',
					'publish_posts'      => 'activate_plugins',
					'read_private_posts' => 'activate_plugins',
				],
				'menu_icon'           => $this::POST_TYPE['icon'],
				'supports'            => [ '' ],
			]
		);
	}

	/**
	 * @param $post_type
	 * @param $post
	 */
	public function remove_meta_boxes( $post_type, $post ): void {
		global $wp_meta_boxes;

		if ( $this->feed->is_feed_post_edit() ) {
			unset( $wp_meta_boxes[ $post_type ] );
		}
	}

	/**
	 * @param $show_screen
	 * @param $_this
	 *
	 * @return false|mixed
	 */
	public function remove_screen_options( $show_screen, $_this ) {

		if ( $this->feed->is_feed_post_edit() ) {
			$show_screen = false;
		}

		return $show_screen;
	}

	/**
	 * @param $result
	 * @param $option
	 * @param $user
	 *
	 * @return string
	 */
	public function user_option_screen_layout( $result, $option, $user ): string {

		if ( $this->feed->is_feed_post_edit() ) {
			return '1';
		}

		return (string) $result;
	}
}
