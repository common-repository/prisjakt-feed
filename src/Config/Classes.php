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

namespace PrisjaktFeed\Config;

/**
 * This array is being used in ../Boostrap.php to instantiate the classes
 *
 * @package PrisjaktFeed\Config
 * @since 1.0.0
 */
final class Classes {


	/**
	 * Init the classes inside these folders based on type of request.
	 *
	 * @see Requester for all the type of requests or to add your own
	 */
	public static function get(): array {
        // phpcs:disable
        // ignore for readable array values one a single line
        return [
            ['init' => 'Integrations'],
            ['init' => 'App\\General'],
            ['init' => 'App\\Frontend', 'on_request' => 'frontend'],
            ['init' => 'App\\Backend', 'on_request' => 'backend'],
            ['init' => 'App\\Rest', 'on_request' => 'rest'],
            ['init' => 'App\\Cli', 'on_request' => 'cli'],
            ['init' => 'App\\Cron', 'on_request' => 'cron'],
        ];
        // phpcs:enable
	}
}
