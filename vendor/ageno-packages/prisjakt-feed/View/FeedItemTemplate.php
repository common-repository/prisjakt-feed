<?php

namespace Ageno\Prisjakt\View;

use Ageno\Prisjakt\Api\TemplateInterface;
use Ageno\Prisjakt\Model\FeedItem;

class FeedItemTemplate extends Template implements TemplateInterface {



	const DEFAULT_NAMESPACE  = 'g';
	const PRISJAKT_NAMESPACE = 'pj';

	const PRISJAKT_NAMESPACE_FIELDS = [
		'member_price',
		'prisjakt_id',
	];

	const FEED_ITEM_FIELD_TEMPLATE = '<{{%namespace%}}:{{%key%}}>{{%value%}}</{{%namespace%}}:{{%key%}}>';
	const FEED_ITEM_TEMPLATE       = '<item>' . PHP_EOL . '{{%fields%}}' . PHP_EOL . '</item>';

	/**
	 * @var FeedItem
	 */
	protected $feedItem;

	public function __construct(
		FeedItem $feedItem
	) {
		$this->feedItem = $feedItem;
	}

	public function getXml() {
		$variables = [
			'fields' => $this->getFieldsXml(),
		];

		return $this->fillTemplate( $variables, self::FEED_ITEM_TEMPLATE );
	}

	protected function getFieldsXml(): array {
		$data      = $this->feedItem->getData();
		$fields    = $this->feedItem->getFields();
		$xmlFields = [];

		foreach ( $fields as $field ) {
			if ( isset( $data[ $field['source'] ] ) ) {
				if ( $value = $data[ $field['source'] ] ) {
					if ( is_array( $value ) ) {
						$value = $value['value'];
					}

					$value = $field['prefix'] . $value . $field['suffix'];
				}

				$xmlFields[] = $this->fillFieldTemplate( $field['field'], $value );
			}
		}

		if ( $thumbnail = $this->feedItem->getThumbnail() ) {
			$xmlFields[] = $this->fillFieldTemplate( 'image_link', $thumbnail );
		}

		$gallery = $this->feedItem->getGallery();

		foreach ( $gallery as $image ) {
			$xmlFields[] = $this->fillFieldTemplate( 'additional_image_link', $image );
		}

		if ( $category = $this->feedItem->getGoogleProductCategory() ) {
			$xmlFields[] = $this->fillFieldTemplate( 'google_product_category', $category );
		}

		return $xmlFields;
	}

	protected function fillFieldTemplate( $key, $value ): string {

		$variables = [
			'key'       => $key,
			'value'     => $value,
			'namespace' => $this->getNamespace( $key ),
		];

		return $this->fillTemplate( $variables, self::FEED_ITEM_FIELD_TEMPLATE );
	}

	protected function getNamespace( $key ) {
		if ( in_array( $key, self::PRISJAKT_NAMESPACE_FIELDS, true ) ) {
			$namespace = self::PRISJAKT_NAMESPACE;
		} else {
			$namespace = self::DEFAULT_NAMESPACE;
		}

		return $namespace;
	}
}
