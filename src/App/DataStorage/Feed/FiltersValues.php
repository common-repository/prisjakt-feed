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

namespace PrisjaktFeed\App\DataStorage\Feed;

class FiltersValues {

	public function get_categories() {
		$args = [
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
		];

		$categories = [];

		foreach ( get_terms( $args ) as $category ) {
			$categories[ $category->slug ] = $category->name;
		}

		return $categories;
	}
}
