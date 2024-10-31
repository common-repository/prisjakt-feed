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

namespace PrisjaktFeed\Common\Utils;

use PrisjaktFeed\Config\Plugin;

/**
 * Utility to show prettified wp_die errors, write debug logs as
 * string or array and to deactivate plugin and print a notice
 *
 * @package PrisjaktFeed\Config
 * @since 1.0.0
 */
class Errors {



	/**
	 * Get the plugin data in static form
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function get_plugin_data(): array {
		return Plugin::init()->data();
	}

	/**
	 * Prettified wp_die error function
	 *
	 * @param $message : The error message
	 * @param string $subtitle : Specified title of the error
	 * @param string $source : File source of the error
	 * @param string $title : General title of the error
	 * @param string $exception
	 *
	 * @since 1.0.0
	 */
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	public static function wp_die( $message = '', $subtitle = '', $source = '', $exception = '', $title = '' ) {
		if ( $message ) {
			$plugin = self::get_plugin_data();
			$title  = $title ? $title : $plugin['name'] . ' ' . $plugin['version'] . ' ' . __( '&rsaquo; Fatal Error', 'prisjakt-feed' );
			self::write_log(
				[
					'title'     => $title . ' - ' . $subtitle,
					'message'   => $message,
					'source'    => $source,
					'exception' => $exception,
				]
			);
			$source   = $source ? '<code>' .
				sprintf(  /* translators: %s: file path */
					__( 'Error source: %s', 'prisjakt-feed' ),
					$source
				) . '</code><BR><BR>' : '';
			$footer   = $source . '<a href="' . $plugin['uri'] . '">' . $plugin['uri'] . '</a>';
			$message  = '<p>' . $message . '</p>';
			$message .= $exception ? '<p><strong>Exception: </strong><BR>' . $exception . '</p>' : '';
			$message  = "<h1>{$title}<br><small>{$subtitle}</small></h1>{$message}<hr><p>{$footer}</p>";
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			wp_die( $message, $title );
		} else {
			wp_die();
		}
	}

	/**
	 * De-activates the plugin and shows notice error in back-end
	 *
	 * @param $message : The error message
	 * @param string $subtitle : Specified title of the error
	 * @param string $source : File source of the error
	 * @param string $title : General title of the error
	 * @param string $exception
	 *
	 * @since 1.0.0
	 */
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	public static function plugin_die( $message = '', $subtitle = '', $source = '', $exception = '', $title = '' ) {
		if ( $message ) {
			$plugin = self::get_plugin_data();
			$title  = $title ? $title : $plugin['name'] . ' ' . $plugin['version'] . ' ' . __( '&rsaquo; Plugin Disabled', 'prisjakt-feed' );
			self::write_log(
				[
					'title'     => $title . ' - ' . $subtitle,
					'message'   => $message,
					'source'    => $source,
					'exception' => $exception,
				]
			);
			$source = $source ? '<small>' .
				sprintf( /* translators: %s: file path */
					__( 'Error source: %s', 'prisjakt-feed' ),
					$source
				) . '</small> - ' : '';
			$footer = $source . '<a href="' . $plugin['uri'] . '"><small>' . $plugin['uri'] . '</small></a>';
			$error  = "<strong><h3>{$title}</h3>{$subtitle}</strong><p>{$message}</p><hr><p>{$footer}</p>";
			global $prisjakt_feed_die_notice;
			$prisjakt_feed_die_notice = $error;
			add_action(
				'admin_notices',
				static function () {
					global $prisjakt_feed_die_notice;
					echo wp_kses_post(
						sprintf(
							'<div class="notice notice-error"><p>%s</p></div>',
							$prisjakt_feed_die_notice
						)
					);
				}
			);
		}
		add_action(
			'admin_init',
			static function () {
				deactivate_plugins( plugin_basename( PRISJAKT_FEED_PLUGIN_FILE ) ); // phpcs:disable ImportDetection.Imports.RequireImports.Symbol -- this constant is global
			}
		);
	}

	/**
	 * Writes a log if wp_debug is enables
	 *
	 * @param $log
	 *
	 * @since 1.0.0
	 */
	public static function write_log( $log ) {
		if ( true === WP_DEBUG ) {
			if ( is_array( $log ) || is_object( $log ) ) {
                // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log, WordPress.PHP.DevelopmentFunctions.error_log_print_r
				error_log( print_r( $log, true ) );
			} else {
                // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
				error_log( $log );
			}
		}
	}
}
