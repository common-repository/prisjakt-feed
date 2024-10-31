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

namespace PrisjaktFeed\Common;

use PrisjaktFeed\App\Backend\Templates;
use PrisjaktFeed\App\Backend\Core\Feeds\Feeds;
use PrisjaktFeed\Common\Abstracts\Base;

/**
 * Main function class for external uses
 *
 * @see prisjakt_feed()
 * @package PrisjaktFeed\Common
 */
class Functions extends Base {
	/**
	 * Get plugin data by using prisjakt_feed()->get_data()
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_data(): array {
		return $this->plugin->data();
	}

	/**
	 * Get the template instantiated class using prisjakt_feed()->templates()
	 *
	 * @return Templates
	 * @since 1.0.0
	 */
	public function templates(): Templates {
		return new Templates();
	}


	/**
	 * Get main menu page slug
	 *
	 * @return string
	 */
	public function get_menu_slug(): string {
		$post_type = Feeds::POST_TYPE;

		return sprintf( 'edit.php?post_type=%s', $post_type['id'] );
	}
}
