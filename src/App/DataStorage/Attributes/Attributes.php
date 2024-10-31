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

declare(strict_types=1);

namespace PrisjaktFeed\App\DataStorage\Attributes;

use PrisjaktFeed\App\DataStorage\Settings\ExtraFieldsData;
use PrisjaktFeed\Config\Plugin;

/**
 * Class Attributes
 *
 * @since 1.0.0
 */
class Attributes {



	/**
	 * @return array
	 */
	public function get_prisjakt_attributes(): array {

		$prefix = Plugin::PLUGIN_PREFIX;

		return [
			[
				'name'    => 'id',
				'label'   => __( 'Id', 'prisjakt-feed' ),
				'format'  => 'required',
				'suggest' => 'id',
			],
			[
				'name'    => 'availability',
				'label'   => __( 'Availability', 'prisjakt-feed' ),
				'format'  => 'required',
				'suggest' => '_stock_status',
			],
			[
				'name'    => 'condition',
				'label'   => __( 'Condition', 'prisjakt-feed' ),
				'format'  => 'required',
				'suggest' => $prefix . 'condition',
			],
			[
				'name'    => 'link',
				'label'   => __( 'Link', 'prisjakt-feed' ),
				'format'  => 'required',
				'suggest' => '_link',
			],
			[
				'name'    => 'price',
				'label'   => __( 'Price', 'prisjakt-feed' ),
				'format'  => 'required',
				'suggest' => '_price',
			],
			[
				'name'    => 'title',
				'label'   => __( 'Title', 'prisjakt-feed' ),
				'format'  => 'required',
				'suggest' => 'post_title',
			],
			[
				'name'    => 'additional_fees',
				'label'   => __( 'Additional fees', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => '',
			],
			[
				'name'    => 'adult',
				'label'   => __( 'Adult', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => '',
			],
			[
				'name'    => 'affiliate_link',
				'label'   => __( 'Affiliate link', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => '',
			],
			[
				'name'    => 'energy_efficiency_class',
				'label'   => __( 'Energy efficiency class', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => $prefix . 'energy_efficiency_class',
			],
			[
				'name'    => 'shipping_height',
				'label'   => __( 'Shipping height', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => '_height',
			],
			[
				'name'    => 'shipping_length',
				'label'   => __( 'Shipping length', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => '_length',
			],
			[
				'name'    => 'shipping_weight',
				'label'   => __( 'Shipping weight', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => '_weight',
			],
			[
				'name'    => 'shipping_width',
				'label'   => __( 'Shipping width', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => '_width',
			],
			[
				'name'    => 'size',
				'label'   => __( 'Size', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => $prefix . 'size',
			],
			[
				'name'    => 'mpn',
				'label'   => __( 'Mpn', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => $prefix . 'mpn',
			],
			[
				'name'    => 'pattern',
				'label'   => __( 'Pattern', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => $prefix . 'pattern',
			],
			[
				'name'    => 'material',
				'label'   => __( 'Material', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => $prefix . 'material',
			],
			[
				'name'    => 'prisjakt_id',
				'label'   => __( 'Prisjakt id', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => $prefix . 'prisjakt_id',
			],
			[
				'name'    => 'gtin',
				'label'   => __( 'Gtin', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => $prefix . 'gtin',
			],
			[
				'name'    => 'product_detail',
				'label'   => __( 'Product detail', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => 'attribute_taxonomy',
			],
			[
				'name'    => 'age_group',
				'label'   => __( 'Age group', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => $prefix . 'age_group',
			],
			[
				'name'    => 'gender',
				'label'   => __( 'Gender', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => $prefix . 'gender',
			],
			[
				'name'    => 'promotion',
				'label'   => __( 'Promotion', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => '',
			],
			[
				'name'    => 'color',
				'label'   => __( 'Color', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => $prefix . 'color',
			],
			[
				'name'    => 'description',
				'label'   => __( 'Description', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => 'post_content',
			],
			[
				'name'    => 'is_bundle',
				'label'   => __( 'Is bundle', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => $prefix . 'is_bundle',
			],
			[
				'name'    => 'brand',
				'label'   => __( 'Brand', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => $prefix . 'brand',
			],
			[
				'name'    => 'item_group_id',
				'label'   => __( 'Item group id', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => 'item_group_id',
			],
			[
				'name'    => 'size_system',
				'label'   => __( 'Size system', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => $prefix . 'size_system',
			],
			[
				'name'    => 'sale_price_effective_date',
				'label'   => __( 'Sale price effective date', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => 'sale_price_dates_from',
			],
			[
				'name'    => 'sale_price',
				'label'   => __( 'Sale price', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => 'sale_price',
			],
			[
				'name'    => 'shipping',
				'label'   => __( 'Shipping', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => '',
			],
			[
				'name'    => 'availability_date',
				'label'   => __( 'Availability date', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => '',
			],
			[
				'name'    => 'shipping_label',
				'label'   => __( 'Shipping label', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => '',
			],
			[
				'name'    => 'promotion_id',
				'label'   => __( 'Promotion id', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => '',
			],
			[
				'name'    => 'member_price',
				'label'   => __( 'Member price', 'prisjakt-feed' ),
				'format'  => 'optional',
				'suggest' => '',
			],
		];
	}

	/**
	 * @return string[]
	 */
	public function attributes_to_options(): array {
		$attributes = $this->get_prisjakt_attributes();

		/**
		 * Empty first value
		 */
		$options = [
			'' => '',
		];

		foreach ( $attributes as $attribute ) :
			$options[ $attribute['name'] ] = $attribute['label'] ?? $attribute['name'];
		endforeach;

		return $options;
	}


	public function get_attributes_by_format( $format = 'required' ): array {
		$attributes          = $this->get_prisjakt_attributes();
		$required_attributes = [];

		foreach ( $attributes as $attribute ) :

			if ( $attribute['format'] !== $format ) {
				continue;
			}

			$required_attributes[] = [
				'name'    => $attribute['name'],
				'suggest' => $attribute['suggest'],
			];

		endforeach;

		return $required_attributes;
	}

	/**
	 * @return array
	 */
	public function get_filters_product_attributes(): array {

		$attributes = [
			[
				'label'   => __( 'General attributes', 'prisjakt-feed' ),
				'options' => [

					'_categories'   => 'Category',
					'_stock_status' => 'Stock Status WooCommerce',
					'_stock'        => 'Quantity [Stock]',
				],
			],
		];

		$attributes[] = [
			'label'   => __( 'Price attributes', 'prisjakt-feed' ),
			'options' => [
				'_price'         => 'Price',
				'_regular_price' => 'Regular price',
				'_sale_price'    => 'Sale price',
			],
		];

		/**
		 * Additional fields for developers
		 */

		return $attributes;
	}


	/**
	 * @return array
	 */
	public function get_field_mapping_product_attributes(): array {

		$attributes = [
			[
				'label'   => __( 'General attributes', 'prisjakt-feed' ),
				'options' => [
					'id'                     => 'Product Id',
					'_sku'                   => 'SKU',
					'post_title'             => 'Product name',
					'post_content'           => 'Product description',
					'post_excerpt'           => 'Product short description',
					'_link'                  => 'Link',
					'_tax_status'            => 'Tax status',
					'_tax_class'             => 'Tax class',
					'_categories'            => 'Category',
					'_purchase_note'         => 'Purchase note',
					'_region_id'             => 'Region Id',
					'_stock_status'          => 'Stock Status WooCommerce',
					'_stock'                 => 'Quantity [Stock]',
					'item_group_id'          => 'Item group ID',
					'_weight'                => 'Weight',
					'_width'                 => 'Width',
					'_height'                => 'Height',
					'_length'                => 'Length',
					'product_visibility'     => 'Visibility',
					'_product_creation_date' => 'Product creation date',
					'_featured'              => 'Featured',
				],
			],
		];

		$attributes[] = [
			'label'   => __( 'Images attributes', 'prisjakt-feed' ),
			'options' => [
				'_image'         => 'Main image',
				'_image_all'     => 'Main image simple and variations',
				'_feature_image' => 'Feature image',
			],
		];

		$attributes[] = [
			'label'   => __( 'Price attributes', 'prisjakt-feed' ),
			'options' => [
				'_price'                     => 'Price',
				'_regular_price'             => 'Regular price',
				'_sale_price'                => 'Sale price',
				'_sale_price_effective_date' => 'Sale effective date (start - end)',
				'_sale_price_start_date'     => 'Sale start date',
				'_sale_price_end_date'       => 'Sale end date',
			],
		];

		$attributes[] = [
			'label'   => __( 'Shipping attributes', 'prisjakt-feed' ),
			'options' => [
				'_shipping'            => 'Shipping',
				'_shipping_price'      => 'Shipping cost',
				'_shipping_label'      => 'Shipping class slug',
				'_shipping_label_name' => 'Shipping class name',
			],
		];

		/**
		 * Extra fields
		 */
		$attributes[] = [
			'label'   => __( 'Extra fields', 'prisjakt-feed' ),
			'options' => ( new ExtraFieldsData() )->extra_options_to_attributes(),
		];

		$attributes[] = [
			'label'   => __( 'Product Attributes', 'prisjakt-feed' ),
			'options' => ( new CustomAttributes() )->get_custom_attributes(),
		];

		/**
		 * Additional fields for developers
		 */

		if ( $additional_options = apply_filters( 'prisjakt_feed_additional_select_options', [] ) ) {

			$attributes[] = [
				'label'   => __( 'Additional fields', 'prisjakt-feed' ),
				'options' => $additional_options,
			];
		}

		return $attributes;
	}

	/**
	 * wp-admin/edit.php?post_type=product&page=product_attributes
	 *
	 * @return array
	 */
	public function get_products_attributes(): array {

		$attributes = [];
		foreach ( wc_get_attribute_taxonomies() as $term ) {
			$attributes[ $term->attribute_name ] = $term->attribute_label;
		}

		return $attributes;
	}
}
