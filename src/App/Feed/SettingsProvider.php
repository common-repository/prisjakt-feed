<?php

namespace PrisjaktFeed\App\Feed;

use Ageno\Prisjakt\Api\SettingsProviderInterface;

class SettingsProvider implements SettingsProviderInterface {

	/**
	 * @var int
	 */
	protected $limit;
	/**
	 * @var int
	 */
	protected $offset;
	/**
	 * @var int
	 */
	protected $batchSize;

	public function __construct( $batchSize = 1000 ) {
		$this->batchSize = $batchSize;
	}

	public function getBatchSize(): int {
		return $this->batchSize;
	}

	public function setBatchSize( int $batchSize ): void {
		$this->batchSize = $batchSize;
	}
}
