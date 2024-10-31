<?php

namespace Ageno\Prisjakt\View;

use Ageno\Prisjakt\Api\TemplateInterface;
use Ageno\Prisjakt\Model\Feed;

class FeedTemplate extends Template implements TemplateInterface {
	public const FEED_TEMPLATE = '<?xml version="1.0"?>' . PHP_EOL .
	'<rss xmlns:g="http://base.google.com/ns/1.0" xmlns:pj="https://storage.googleapis.com/prisjakt-namespace/ns" version="2.0">' . PHP_EOL .
	'    <channel>' . PHP_EOL .
	'        <title>{{%title%}}</title>' . PHP_EOL .
	'{{%items%}}' . PHP_EOL .
	'    </channel>' . PHP_EOL .
	'</rss>';

	/**
	 * @var Feed
	 */
	protected $feed;

	public function __construct(
		Feed $feed
	) {
		$this->feed = $feed;
	}

	public function getXml() {
		$variables = [
			'title'       => $this->feed->getName(),
			'link'        => $this->feed->getUrl(),
			'description' => $this->feed->getDescription(),
			'items'       => $this->feed->getFeedItemsXml(),
		];

		return $this->fillTemplate( $variables, self::FEED_TEMPLATE );
	}
}
