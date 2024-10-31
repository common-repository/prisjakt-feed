<?php

namespace Ageno\Prisjakt\Model;

use Ageno\Prisjakt\Api\SettingsProviderInterface;
use Ageno\Prisjakt\Component\Adapter\ResourceAdapterInterface;
use Ageno\Prisjakt\View\FeedTemplate;

/**
 * Methods stubs:
 *
 * @method getId();
 * @method setId($id);
 * @method getFeedId();
 * @method setFeedId($feedId);
 * @method setFileName($filename);
 * @method string|null getName();
 * @method setName($name);
 * @method setRows($rows);
 * @method setError($error);
 * @method getError();
 * @method getCompletedRows();
 * @method setCompletedRows($rows);
 */
class Feed extends DataObject {



	const FEED_STATUS_NEW        = 'new';
	const FEED_STATUS_PENDING    = 'pending';
	const FEED_STATUS_PROCESSING = 'processing';
	const FEED_STATUS_FINISHED   = 'finished';
	const FEED_STATUS_FAILED     = 'failed';

	const FILE_EXTENSION     = '.xml';
	const DEFAULT_BATCH_SIZE = 2000;
	const FEED_DIR           = 'prisjakt';

	/**
	 * @var SettingsProviderInterface
	 */
	protected $settingsProvider;
	/**
	 * @var ResourceAdapterInterface
	 */
	protected $resourceAdapter;

	protected $_itemsPrepared = false;

	protected $xml;

	protected $count;

	public function __construct(
		SettingsProviderInterface $settingsProvider,
		ResourceAdapterInterface $resourceAdapter,
		array $data = []
	) {
		$this->settingsProvider = $settingsProvider;
		$this->resourceAdapter  = $resourceAdapter;
		parent::__construct( $data );
	}

	public function countItems( $recalculate = false ): int {
		if ( $recalculate || null === $this->count ) {
			$this->count = $this->resourceAdapter->countItems( $this );
		}

		return $this->count;
	}

	public function getStatus() {
		if ( ! isset( $this->_data['status'] ) ) {
			$this->_data['status'] = self::FEED_STATUS_NEW;
		}

		return $this->_data['status'];
	}

	public function updateStatus( $status ) {
		$this->checkProgress( true );
		$this->setStatus( $status );
		$this->resourceAdapter->saveFeedStatus( $this );

	}

	public function setStatus( $status ) {
		if ( ! in_array( $status, [ self::FEED_STATUS_NEW, self::FEED_STATUS_PENDING, self::FEED_STATUS_PROCESSING, self::FEED_STATUS_FINISHED, self::FEED_STATUS_FAILED ], true ) ) {
			$this->_data['status'] = self::FEED_STATUS_NEW;
		} else {
			$this->_data['status'] = $status;
		}
	}

	public function updateError( $error ) {
		$this->setError( $error );
		$this->resourceAdapter->saveError( $this );

	}

	public function getCurrentBatch() {

	}

	public function getBatchSize(): int {
		return $this->settingsProvider->getBatchSize() ?? self::DEFAULT_BATCH_SIZE;
	}

	public function load( $id ) {
		$data = $this->resourceAdapter->loadFeedData( $id );

		if ( ! $data ) {
			throw new \Exception( 'Feed does not exists.' );
		}

		$this->setData( $data );

		return $this;
	}

	public function generate(): string {
		$this->setStatus( self::FEED_STATUS_PROCESSING );
		$this->save();

		try {
			$this->generateItemsBatch( $this->settingsProvider->getBatchSize() );
			if ( $this->checkProgress( true ) >= 1 ) {
				if ( ! $this->xml ) {
					$template  = new FeedTemplate( $this );
					$this->xml = $template->getXml();
					$url       = $this->resourceAdapter->saveFile( self::FEED_DIR . DIRECTORY_SEPARATOR . $this->getFileName(), $this->xml );
					$this->setStatus( self::FEED_STATUS_FINISHED );
					$this->setGeneratedAt( current_time( 'Y-m-d H:i:s' ) );
					$this->setScheduledAt( null );
					$this->setUrl( $url );
					$this->cleanFeedItems();
					$this->save();
				}
			} else {
				$this->setStatus( self::FEED_STATUS_PENDING );
				$this->save();
			}
		} catch ( \Exception $e ) {
			$this->setStatus( self::FEED_STATUS_FAILED );
			$this->setError( $e->getMessage() );
			$this->cleanFeedItems();
			$this->save();
		}

		return (string) $this->xml;
	}

	public function generateItemsBatch( $batchSize ) {
		$id = $this->getId();

		if ( ! $id ) {
			$this->save();
		}

		$this->getFeedItems( $batchSize );
	}

	public function save() {
		$this->resourceAdapter->saveFeed( $this );
	}

	/**
	 * @param $limit
	 * @param $offset
	 * @return FeedItem[]
	 */
	public function getFeedItems( $limit = 1000, $offset = 0 ) {
		if ( ! $this->hasItemsPrepared() ) {
			$this->prepareItems();
		}

		return $this->resourceAdapter->getFeedItems( $this, $limit, $offset );
	}

	protected function hasItemsPrepared(): bool {
		return $this->_itemsPrepared;
	}

	public function cleanFeedItems() {
		$this->resourceAdapter->cleanFeedItems( $this );
		$this->_itemsPrepared = false;
	}

	public function prepareItems( $reset = false ) {
		$this->resourceAdapter->prepareFeedItems( $this, $reset );
		if ( $reset ) {
			$this->setProgress( 0 );
		}
		$this->_itemsPrepared = true;
	}

	public function checkProgress( $recalculate = false ): float {
		if ( $recalculate ) {
			$this->unsetData( 'progress' );
		}

		if ( ! $this->getProgress() ) {
			$progress = $this->resourceAdapter->getFeedProgress( $this );
			$this->setProgress( $progress );
		}

		return $this->getProgress();
	}

	public function getFileName() {
		if ( ! isset( $this->_data['file_name'] ) || ! $this->_data['file_name'] ) {
			$this->_data['file_name'] = $this->prepareFilename();
		}

		return $this->_data['file_name'];
	}

	protected function prepareFilename() {
		$input = $this->getName();

		if ( function_exists( 'iconv' ) ) {
			$input = iconv( 'utf-8', 'ASCII//TRANSLIT//IGNORE', $input );
		}

		$input = str_replace( ' ', '_', $input );
		$input = preg_replace( '/[^a-zA-Z0-9_]/', '', $input ) . '-' . $this->getId();

		if ( substr( $input, -4 ) !== self::FILE_EXTENSION ) {
			$input .= self::FILE_EXTENSION;
		}

		return strtolower( $input );
	}

	public function getFeedItemsXml() {
		return $this->resourceAdapter->getFeedItemsXml( $this );
	}
}
