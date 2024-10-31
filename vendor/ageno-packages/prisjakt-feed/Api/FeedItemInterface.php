<?php

namespace Ageno\Prisjakt\Api;

interface FeedItemInterface {


	const REQUIRED_FIELDS = [
		'id',
		'gtin',
		'ean',
		'mpn',
		'link',
		'condition',
		'upc',
		'product_type',
	];

	public function generate(): string;

	public function save(): bool;
}
