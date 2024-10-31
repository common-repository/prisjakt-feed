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
class Messages extends Feeds {



	/**
	 * @var Feed
	 */
	protected $feed;

	/**
	 * @var array
	 */
	protected $messages;


	/**
	 * Feed messages names
	 */
	public const FEED_MESSAGES_NAMES = [
		'feed_created',
		'feed_updated',
	];


	/**
	 * @return array
	 */
	public static function feed_messages(): array {
		return [
			self::FEED_MESSAGES_NAMES[0] => __( 'Feed saved.', 'prisjakt-feed' ),
			self::FEED_MESSAGES_NAMES[1] => __( 'Feed updated.', 'prisjakt-feed' ),

		];
	}

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

		$this->feed = new Feed();

		$this->messages = self::feed_messages();

		add_filter( 'post_updated_messages', [ $this, 'set_messages' ] );
		add_filter( 'redirect_post_location', [ $this, 'redirect_after_save_feed' ] );
		add_action( 'admin_notices', [ $this, 'custom_admin_notice' ] );
		add_filter( 'bulk_post_updated_messages', [ $this, 'bulk_post_updated_messages_filter' ], 10, 2 );

	}

	/**
	 * @param $location
	 *
	 * @return string
	 */
	public function redirect_after_save_feed( $location ): string {

		if ( $this->feed->is_feed_post_edit() ) {

			return add_query_arg(
				'message',
				self::FEED_MESSAGES_NAMES[0],
				sprintf( 'edit.php?post_type=%s', Feeds::POST_TYPE['id'] )
			);
		}

		return $location;
	}

	/**
	 * Added notices to feeds list page
	 */
	public function custom_admin_notice(): void {

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$feed_created = isset( $_GET['message'] ) && in_array(
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			sanitize_text_field( wp_unslash( $_GET['message'] ) ),
			self::FEED_MESSAGES_NAMES,
			true
		);

		if ( $feed_created && $this->feed->is_feed_post_edit() ) {
			$class = 'updated notice is-dismissible';
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$message_name = sanitize_text_field( wp_unslash( $_GET['message'] ) );

			printf(
				'<div class="%1$s"><p>%2$s</p></div>',
				esc_attr( $class ),
				esc_html( $this->messages[ $message_name ] )
			);
		}
	}


	/**
	 * Set messages for feed post update
	 *
	 * @param $messages
	 *
	 * @return array
	 */
	public function set_messages( $messages ): array {

		if ( $this->feed->is_feed_post_edit() ) {
			$default_message = $this->messages[ self::FEED_MESSAGES_NAMES[1] ];

			$messages['post'][1] = $default_message;
			$messages['post'][4] = $default_message;
			$messages['post'][6] = $default_message;
			$messages['post'][7] = $default_message;
		}

		return $messages;
	}


	public function bulk_post_updated_messages_filter( $bulk_messages, $bulk_counts ) {

		$post_type_id = Feeds::POST_TYPE['id'];

		$bulk_messages[ $post_type_id ] = [
			/* translators: %s: updated */
			'updated'   => _n( '%s Feed updated.', '%s Feeds updated.', $bulk_counts['updated'] ),
			/* translators: %s: locked */
			'locked'    => _n( '%s Feed not updated, somebody is editing it.', '%s Feeds not updated, somebody is editing them.', $bulk_counts['locked'] ),
			/* translators: %s: deleted */
			'deleted'   => _n( '%s Feed permanently deleted.', '%s Feeds permanently deleted.', $bulk_counts['deleted'] ),
			/* translators: %s: trashed */
			'trashed'   => _n( '%s Feed moved to the Trash.', '%s Feeds moved to the Trash.', $bulk_counts['trashed'] ),
			/* translators: %s: untrashed */
			'untrashed' => _n( '%s Feed restored from the Trash.', '%s Feeds restored from the Trash.', $bulk_counts['untrashed'] ),
		];

		return $bulk_messages;

	}
}
