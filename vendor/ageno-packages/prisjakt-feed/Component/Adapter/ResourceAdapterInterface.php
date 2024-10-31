<?php

namespace Ageno\Prisjakt\Component\Adapter;

use Ageno\Prisjakt\Model\Feed;
use Ageno\Prisjakt\Model\FeedItem;

interface ResourceAdapterInterface {


	public function saveFeedItem( FeedItem $feedItem);

	public function saveFeed( Feed $feed);

	public function saveFeedStatus( Feed $feed);

	public function saveError( Feed $feed);

	public function loadFeedData( int $id): array;

	public function getFeedItems( Feed $feed, $limit = 1000, $offset = 0);

	public function getFeedItemsXml( Feed $feed): array;

	public function getFeedProgress( Feed $feed): float;

	public function countItems( Feed $feed): int;

	public function getGallery( FeedItem $feedItem): array;

	public function getThumbnail( FeedItem $feedItem): string;

	public function saveFile( $path, $content): string;

}
