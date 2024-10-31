<?php

namespace Ageno\Prisjakt\Model\FeedItem;

use Ageno\Prisjakt\Component\Adapter\ResourceAdapterInterface;
use Ageno\Prisjakt\Model\Feed;

class Collection {

	/**
	 * @var ResourceAdapterInterface
	 */
	protected $resourceAdapter;

	public function __construct(
		ResourceAdapterInterface $resourceAdapter
	) {
		$this->resourceAdapter = $resourceAdapter;
	}

	public function getBatchByFeed( Feed $feed, $batchSize ) {
		$offset = $feed->getCompletedRows();

		$this->resourceAdapter->getFeedItemsByFeed( $feed, $batchSize );
	}
}
