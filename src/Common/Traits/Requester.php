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

namespace PrisjaktFeed\Common\Traits;

use PrisjaktFeed\Common\Utils\Errors;

/**
 * The requester trait to determine what we request; used to determine
 * which classes we instantiate in the Bootstrap class
 *
 * @see Bootstrap
 *
 * @package PrisjaktFeed\Common\Traits
 * @since 1.0.0
 */
trait Requester {



	/**
	 * What type of request is this?
	 *
	 * @param string $type admin, cron, cli, amp or frontend.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function request( string $type ): bool {
		switch ( $type ) {
			case 'installing_wp':
				return $this->is_installing_wp();
			case 'frontend':
				return $this->is_frontend();
			case 'backend':
				return $this->is_admin_backend();
			case 'rest':
				return $this->is_rest();
			case 'cron':
				return $this->is_cron();
			case 'cli':
				return $this->is_cli();
			default:
				Errors::wp_die(
					sprintf( /* translators: %s: request function */
						esc_html__( 'Unknown request type: %s', 'prisjakt-feed' ),
						esc_html( $type )
					),
					esc_html__( 'Classes are not being correctly requested', 'prisjakt-feed' ),
					__FILE__
				);

				return false;
		}
	}

	/**
	 * Is installing WP
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function is_installing_wp(): bool {
		return defined( 'WP_INSTALLING' );
	}

	/**
	 * Is frontend
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function is_frontend(): bool {
		return ! $this->is_admin_backend() && ! $this->is_cron() && ! $this->is_rest();
	}

	/**
	 * Is admin
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function is_admin_backend(): bool {
		return is_user_logged_in() && is_admin();
	}

	/**
	 * Is rest
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function is_rest(): bool {
		return defined( 'REST_REQUEST' );
	}

	/**
	 * Is cron
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function is_cron(): bool {
		return ( function_exists( 'wp_doing_cron' ) && wp_doing_cron() ) || defined( 'DOING_CRON' );
	}

	/**
	 * Is cli
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function is_cli(): bool {
		return defined( 'WP_CLI' ) && WP_CLI; // phpcs:disable ImportDetection.Imports.RequireImports.Symbol -- this constant is global
	}
}
