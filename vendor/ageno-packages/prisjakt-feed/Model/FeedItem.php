<?php

namespace Ageno\Prisjakt\Model;

use Ageno\Prisjakt\Api\FeedItemInterface;
use Ageno\Prisjakt\Api\SettingsProviderInterface;
use Ageno\Prisjakt\Component\Adapter\ResourceAdapterInterface;
use Ageno\Prisjakt\View\FeedItemTemplate;

/**
 * Methods stubs:
 *
 * @method getCondition();
 * @method setCondition($condition);
 * @method getGtin();
 * @method setGtin($gtin);
 * @method getEan();
 * @method setEan($ean);
 * @method getMpn();
 * @method setMpn($mpn);
 * @method getOptimizedTitle();
 * @method setOptimizedTitle($optimizedTitle);
 * @method getUpc();
 * @method setUpc($upc);
 * @method getSupplierProductId();
 * @method setSupplierProductId($supplierProductId);
 * @method getSizeSystem();
 * @method setSizeSystem($sizeSystem);
 * @method getPrisjaktId();
 * @method setPrisjaktId($prisjaktId);
 * @method getMarketplaceRetailer();
 * @method setMarketplaceRetailer($marketplaceRetailer);
 * @method getBrand();
 * @method setBrand($brand);
 * @method getAgeGroup();
 * @method setAgeGroup($ageGroup);
 * @method getColor();
 * @method setColor($color);
 * @method getCostOfGoodSold();
 * @method setCostOfGoodSold($costOfGoodSold);
 * @method getCustomField0();
 * @method setCustomField0($customField0);
 * @method getCustomField1();
 * @method setCustomField1($customField1);
 * @method getCustomField2();
 * @method setCustomField2($customField2);
 * @method getCustomField3();
 * @method setCustomField3($customField3);
 * @method getCustomField4();
 * @method setCustomField4($customField4);
 * @method getEnergyEfficiencyClass();
 * @method setEnergyEfficiencyClass($energyEfficiencyClass);
 * @method getExcludeProduct();
 * @method setExcludeProduct($excludeProduct);
 * @method getGender();
 * @method setGender($gender);
 * @method getInstallmentAmount();
 * @method setInstallmentAmount($installmentAmount);
 * @method getInstallmentMonths();
 * @method setInstallmentMonths($installmentMonths);
 * @method getIsBundle();
 * @method setIsBundle($isBundle);
 * @method getIsPromotion();
 * @method setIsPromotion($isPromotion);
 * @method getMaterial();
 * @method setMaterial($material);
 * @method getMaxEnergyEfficiencyClass();
 * @method setMaxEnergyEfficiencyClass($maxEnergyEfficiencyClass);
 * @method getMinEnergyEfficiencyClass();
 * @method setMinEnergyEfficiencyClass($minEnergyEfficiencyClass);
 * @method getMultipack();
 * @method setMultipack($multipack);
 * @method getPattern();
 * @method setPattern($pattern);
 * @method getSize();
 * @method setSize($size);
 * @method getUnitPricingBaseMeasure();
 * @method setUnitPricingBaseMeasure($unitPricingBaseMeasure);
 * @method getUnitPricingMeasure();
 * @method setUnitPricingMeasure($unitPricingMeasure);
 */
class FeedItem extends DataObject implements FeedItemInterface {

	const FEED_ITEM_STATUS_NEW        = 'new';
	const FEED_ITEM_STATUS_PENDING    = 'pending';
	const FEED_ITEM_STATUS_PROCESSING = 'processing';
	const FEED_ITEM_STATUS_FINISHED   = 'finished';

	/**
	 * @var string
	 */
	protected $xml;
	/**
	 * @var ResourceAdapterInterface
	 */
	protected $resourceAdapter;

	public function __construct(
		ResourceAdapterInterface $resourceAdapter,
		array $data = []
	) {
		$this->resourceAdapter = $resourceAdapter;
		parent::__construct( $data );
	}

	public function save(): bool {
		$this->resourceAdapter->saveFeedItem( $this );

		return true;
	}

	public function generate(): string {
		if ( ! $this->hasData( 'xml' ) ) {
			$template = new FeedItemTemplate( $this );
			$this->setXml( $template->getXml() );
		}

		return $this->getXml();
	}

	public function getGallery(): array {
		return $this->resourceAdapter->getGallery( $this );
	}

	public function getThumbnail(): string {
		return $this->resourceAdapter->getThumbnail( $this );
	}
}
