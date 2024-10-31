<?php

namespace Ageno\Prisjakt\Model\FeedItem;

class FeedItem {
	protected $id;
	protected $condition;
	protected $gtin;
	protected $ean;
	protected $mpn;
	protected $optimizedTitle;
	protected $upc;
	protected $supplierProductId;

	/**
	 * @param $id
	 * @param $condition
	 * @param $gtin
	 * @param $ean
	 * @param $mpn
	 * @param $optimizedTitle
	 * @param $upc
	 * @param $supplierProductId
	 * @param $sizeSystem
	 * @param $prisjaktId
	 * @param $marketplaceRetailer
	 * @param $brand
	 * @param $ageGroup
	 * @param $color
	 * @param $costOfGoodSold
	 * @param $customField0
	 * @param $customField1
	 * @param $customField2
	 * @param $customField3
	 * @param $customField4
	 * @param $energyEfficiencyClass
	 * @param $excludeProduct
	 * @param $gender
	 * @param $installmentAmount
	 * @param $installmentMonths
	 * @param $isBundle
	 * @param $isPromotion
	 * @param $material
	 * @param $maxEnergyEfficiencyClass
	 * @param $minEnergyEfficiencyClass
	 * @param $multipack
	 * @param $pattern
	 * @param $size
	 * @param $unitPricingBaseMeasure
	 * @param $unitPricingMeasure
	 * @param $extraFields
	 */
	public function __construct(
		$id,
		$condition,
		$gtin,
		$ean,
		$mpn,
		$optimizedTitle,
		$upc,
		$supplierProductId,
		$sizeSystem,
		$prisjaktId,
		$marketplaceRetailer,
		$brand,
		$ageGroup,
		$color,
		$costOfGoodSold,
		$customField0,
		$customField1,
		$customField2,
		$customField3,
		$customField4,
		$energyEfficiencyClass,
		$excludeProduct,
		$gender,
		$installmentAmount,
		$installmentMonths,
		$isBundle,
		$isPromotion,
		$material,
		$maxEnergyEfficiencyClass,
		$minEnergyEfficiencyClass,
		$multipack,
		$pattern,
		$size,
		$unitPricingBaseMeasure,
		$unitPricingMeasure,
		$extraFields
	) {
		$this->id                       = $id;
		$this->condition                = $condition;
		$this->gtin                     = $gtin;
		$this->ean                      = $ean;
		$this->mpn                      = $mpn;
		$this->optimizedTitle           = $optimizedTitle;
		$this->upc                      = $upc;
		$this->supplierProductId        = $supplierProductId;
		$this->sizeSystem               = $sizeSystem;
		$this->prisjaktId               = $prisjaktId;
		$this->marketplaceRetailer      = $marketplaceRetailer;
		$this->brand                    = $brand;
		$this->ageGroup                 = $ageGroup;
		$this->color                    = $color;
		$this->costOfGoodSold           = $costOfGoodSold;
		$this->customField0             = $customField0;
		$this->customField1             = $customField1;
		$this->customField2             = $customField2;
		$this->customField3             = $customField3;
		$this->customField4             = $customField4;
		$this->energyEfficiencyClass    = $energyEfficiencyClass;
		$this->excludeProduct           = $excludeProduct;
		$this->gender                   = $gender;
		$this->installmentAmount        = $installmentAmount;
		$this->installmentMonths        = $installmentMonths;
		$this->isBundle                 = $isBundle;
		$this->isPromotion              = $isPromotion;
		$this->material                 = $material;
		$this->maxEnergyEfficiencyClass = $maxEnergyEfficiencyClass;
		$this->minEnergyEfficiencyClass = $minEnergyEfficiencyClass;
		$this->multipack                = $multipack;
		$this->pattern                  = $pattern;
		$this->size                     = $size;
		$this->unitPricingBaseMeasure   = $unitPricingBaseMeasure;
		$this->unitPricingMeasure       = $unitPricingMeasure;
		$this->extraFields              = $extraFields;
	}
}
