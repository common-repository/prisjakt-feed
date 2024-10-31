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

namespace PrisjaktFeed\App\DataStorage\Settings;

use PrisjaktFeed\App\DataStorage\DataStorage;
use PrisjaktFeed\App\Backend\Core\Settings\Settings;
use PrisjaktFeed\Config\Plugin;

/**
 * Class ExtraFields
 *
 * @since 1.0.0
 */
class ExtraFieldsData extends DataStorage {


	/**
	 * Data storage for options
	 *
	 * @return string
	 */
	public function get_option_name(): string {
		return Plugin::PLUGIN_PREFIX . Settings::get_tab_id_by_index( 1 );
	}

	/**
	 * @var string[]
	 */
	public $option_extra_args = [
		'default_enabled',
		'type',
		'wrapper_class',
	];

	/**
	 * @return string[]
	 */
	public function get_option_extra_args(): array {
		return $this->option_extra_args;
	}

	/**
	 * @var array
	 */
	public $option = [
		'id'              => '',
		'default_enabled' => false,
		'type'            => 'text_input',
	];

	/**
	 * @return array[]
	 */
	public function get_columns(): array {
		return [
			[
				'label' => __( 'Attribute name', 'prisjakt-feed' ),
			],
			[
				'label' => __( 'Off / On', 'prisjakt-feed' ),
			],
		];
	}

	/**
	 * @return array
	 */
	public function get_options(): array {

		$options = [
			[
				'id'               => 'condition',
				'required_enabled' => true,
				'type'             => 'select',
				'label'            => __( 'Product condition', 'prisjakt-feed' ),
				'placeholder'      => __( 'Product condition', 'prisjakt-feed' ),
				'desc_tip'         => 'true',
				'description'      => __( 'Select the product condition.', 'prisjakt-feed' ),
				'options'          => [
					''            => '',
					'new'         => __( 'new', 'prisjakt-feed' ),
					'refurbished' => __( 'refurbished', 'prisjakt-feed' ),
					'used'        => __( 'used', 'prisjakt-feed' ),
					'damaged'     => __( 'damaged', 'prisjakt-feed' ),
				],
			],
			[
				'id'          => 'gtin',
				'label'       => __( 'GTIN', 'prisjakt-feed' ),
				'placeholder' => __( 'GTIN', 'prisjakt-feed' ),
				'desc_tip'    => 'true',
				'description' => __( 'Enter the product GTIN here.', 'prisjakt-feed' ),
			],
			[
				'id'          => 'ean',
				'label'       => __( 'EAN', 'prisjakt-feed' ),
				'placeholder' => __( 'European Article Number', 'prisjakt-feed' ),
				'desc_tip'    => 'true',
				'description' => __( 'Enter the EAN here.', 'prisjakt-feed' ),
			],
			[
				'id'          => 'mpn',
				'label'       => __( 'MPN', 'prisjakt-feed' ),
				'placeholder' => __( 'Manufacturer Product Number', 'prisjakt-feed' ),
				'desc_tip'    => 'true',
				'description' => __( 'Enter the MPN here.', 'prisjakt-feed' ),
			],
			[
				'id'          => 'upc',
				'label'       => __( 'UPC', 'prisjakt-feed' ),
				'placeholder' => __( 'UPC', 'prisjakt-feed' ),
				'desc_tip'    => 'true',
				'description' => __( 'Enter the product UPC here.', 'prisjakt-feed' ),
			],
			[
				'id'          => 'optimized_title',
				'label'       => __( 'Optimized title', 'prisjakt-feed' ),
				'placeholder' => __( 'Optimized title', 'prisjakt-feed' ),
				'desc_tip'    => 'true',
				'description' => __( 'Enter a optimized product title here.', 'prisjakt-feed' ),
			],
			[
				'id'          => 'size_system',
				'type'        => 'select',
				'label'       => __( 'Size system', 'prisjakt-feed' ),
				'desc_tip'    => 'true',
				'description' => __( 'Select the product size system', 'prisjakt-feed' ),
				'options'     => $this->get_size_system_options(),
			],
			[
				'id'          => 'prisjakt_id',
				'label'       => __( 'Prisjakt ID', 'prisjakt-feed' ),
				'desc_tip'    => 'true',
				'description' => __( 'If the product has the URL https://www.prisjakt.nu/produkt.php?p=4858585 then the ID would be 4858585.', 'prisjakt-feed' ),
			],
			[
				'id'          => 'brand',
				'label'       => __( 'Brand', 'prisjakt-feed' ),
				'desc_tip'    => 'true',
				'description' => __( 'Enter the product Brand here.', 'prisjakt-feed' ),
			],
			[
				'id'          => 'age_group',
				'type'        => 'select',
				'label'       => __( 'Product age group', 'prisjakt-feed' ),
				'placeholder' => __( 'Product age group', 'prisjakt-feed' ),
				'desc_tip'    => 'true',
				'description' => __( 'Select the product age group.', 'prisjakt-feed' ),
				'options'     => [
					''        => '',
					'newborn' => __( 'newborn', 'prisjakt-feed' ),
					'infant'  => __( 'infant', 'prisjakt-feed' ),
					'toddler' => __( 'toddler', 'prisjakt-feed' ),
					'kids'    => __( 'kids', 'prisjakt-feed' ),
					'adult'   => __( 'adult', 'prisjakt-feed' ),
				],
			],
			[
				'id'          => 'color',
				'label'       => __( 'Color', 'prisjakt-feed' ),
				'placeholder' => __( 'Color', 'prisjakt-feed' ),
				'desc_tip'    => 'true',
				'description' => __( 'Enter the product Color here.', 'prisjakt-feed' ),
			],
			[
				'id'          => 'custom_field_0',
				'label'       => __( 'Custom field 0', 'prisjakt-feed' ),
				'placeholder' => __( 'Custom field 0', 'prisjakt-feed' ),
				'desc_tip'    => 'true',
				'description' => __( 'Enter your custom field 0', 'prisjakt-feed' ),
			],
			[
				'id'          => 'custom_field_1',
				'label'       => __( 'Custom field 1', 'prisjakt-feed' ),
				'placeholder' => __( 'Custom field 1', 'prisjakt-feed' ),
				'desc_tip'    => 'true',
				'description' => __( 'Enter your custom field 1', 'prisjakt-feed' ),
			],
			[
				'id'          => 'custom_field_2',
				'label'       => __( 'Custom field 2', 'prisjakt-feed' ),
				'placeholder' => __( 'Custom field 2', 'prisjakt-feed' ),
				'desc_tip'    => 'true',
				'description' => __( 'Enter your custom field 2', 'prisjakt-feed' ),
			],
			[
				'id'          => 'custom_field_3',
				'label'       => __( 'Custom field 3', 'prisjakt-feed' ),
				'placeholder' => __( 'Custom field 3', 'prisjakt-feed' ),
				'desc_tip'    => 'true',
				'description' => __( 'Enter your custom field 3', 'prisjakt-feed' ),
			],
			[
				'id'          => 'custom_field_4',
				'label'       => __( 'Custom field 4', 'prisjakt-feed' ),
				'placeholder' => __( 'Custom field 4', 'prisjakt-feed' ),
				'desc_tip'    => 'true',
				'description' => __( 'Enter your custom field 4', 'prisjakt-feed' ),
			],
			[
				'id'          => 'energy_efficiency_class',
				'type'        => 'select',
				'label'       => __( 'Energy efficiency class', 'prisjakt-feed' ),
				'placeholder' => __( 'Energy efficiency class', 'prisjakt-feed' ),
				'desc_tip'    => 'true',
				'description' => __( 'Select the energy efficiency class.', 'prisjakt-feed' ),
				'options'     => $this->get_energy_efficiency_class_options(),
			],
			[
				'id'          => 'exclude_product',
				'type'        => 'checkbox',
				'label'       => __( 'Exclude from feeds', 'prisjakt-feed' ),
				'placeholder' => __( 'Exclude from feeds', 'prisjakt-feed' ),
				'desc_tip'    => 'true',
				'description' => __( 'Check this box if you want this product to be excluded from product feeds.', 'woocommerce' ),
			],
			[
				'id'          => 'gender',
				'type'        => 'select',
				'label'       => __( 'Gender', 'prisjakt-feed' ),
				'placeholder' => __( 'Gender', 'prisjakt-feed' ),
				'desc_tip'    => 'true',
				'description' => __( 'Select the gender.', 'prisjakt-feed' ),
				'options'     => [
					''       => '',
					'female' => __( 'female', 'prisjakt-feed' ),
					'male'   => __( 'male', 'prisjakt-feed' ),
					'unisex' => __( 'unisex', 'prisjakt-feed' ),
				],
			],
			[
				'id'          => 'is_bundle',
				'type'        => 'select',
				'label'       => __( 'Is bundle', 'prisjakt-feed' ),
				'placeholder' => __( 'Is bundle', 'prisjakt-feed' ),
				'desc_tip'    => 'true',
				'description' => __( 'Select the is bundle value.', 'prisjakt-feed' ),
				'options'     => [
					''    => '',
					'yes' => __( 'yes', 'prisjakt-feed' ),
					'no'  => __( 'no', 'prisjakt-feed' ),
				],
			],
			[
				'id'          => 'is_promotion',
				'label'       => __( 'Is promotion', 'prisjakt-feed' ),
				'placeholder' => __( 'Is promotion', 'prisjakt-feed' ),
				'desc_tip'    => 'true',
				'description' => __( 'Enter your promotion ID', 'prisjakt-feed' ),
			],
			[
				'id'          => 'material',
				'label'       => __( 'Material', 'prisjakt-feed' ),
				'placeholder' => __( 'Material', 'prisjakt-feed' ),
				'desc_tip'    => 'true',
				'description' => __( 'Enter the product Material here.', 'prisjakt-feed' ),
			],
			[
				'id'          => 'max_energy_efficiency_class',
				'type'        => 'select',
				'label'       => __( 'Maximum energy efficiency class', 'prisjakt-feed' ),
				'placeholder' => __( 'Maximum energy efficiency class', 'prisjakt-feed' ),
				'desc_tip'    => 'true',
				'description' => __( 'Select the maximum energy efficiency class.', 'prisjakt-feed' ),
				'options'     => $this->get_energy_efficiency_class_options(),
			],
			[
				'id'          => 'min_energy_efficiency_class',
				'type'        => 'select',
				'label'       => __( 'Minimum energy efficiency class', 'prisjakt-feed' ),
				'placeholder' => 'Minimum energy efficiency class',
				'desc_tip'    => 'true',
				'description' => __( 'Select the minimum energy efficiency class.', 'prisjakt-feed' ),
				'options'     => $this->get_energy_efficiency_class_options(),
			],
			[
				'id'          => 'pattern',
				'label'       => __( 'Pattern', 'prisjakt-feed' ),
				'placeholder' => __( 'Pattern', 'prisjakt-feed' ),
				'desc_tip'    => 'true',
				'description' => __( 'Enter the product Pattern here.', 'prisjakt-feed' ),
			],
			[
				'id'          => 'size',
				'label'       => __( 'Size', 'prisjakt-feed' ),
				'placeholder' => __( 'Size', 'prisjakt-feed' ),
				'desc_tip'    => 'true',
				'description' => __( 'Enter the product Size here.', 'prisjakt-feed' ),
			],
		];

		array_walk(
			$options,
			function ( &$a ) {
				$a = array_merge( $this->option, $a );
			}
		);

		return $options;
	}

	/**
	 * @return array
	 */
	public function get_energy_efficiency_class_options(): array {
		return [
			''     => '',
			'A+++' => __( 'A+++', 'prisjakt-feed' ),
			'A++'  => __( 'A++', 'prisjakt-feed' ),
			'A+'   => __( 'A+', 'prisjakt-feed' ),
			'A'    => __( 'A', 'prisjakt-feed' ),
			'B'    => __( 'B', 'prisjakt-feed' ),
			'C'    => __( 'C', 'prisjakt-feed' ),
			'D'    => __( 'D', 'prisjakt-feed' ),
			'E'    => __( 'E', 'prisjakt-feed' ),
			'F'    => __( 'F', 'prisjakt-feed' ),
			'G'    => __( 'G', 'prisjakt-feed' ),
		];
	}

	/**
	 * @return array
	 */
	public function get_size_system_options(): array {
		return [
			''    => '',
			'au'  => __( 'AU', 'prisjakt-feed' ),
			'br'  => __( 'BR', 'prisjakt-feed' ),
			'cn'  => __( 'CN', 'prisjakt-feed' ),
			'de'  => __( 'DE', 'prisjakt-feed' ),
			'eu'  => __( 'EU', 'prisjakt-feed' ),
			'fr'  => __( 'FR', 'prisjakt-feed' ),
			'it'  => __( 'IT', 'prisjakt-feed' ),
			'jp'  => __( 'JP', 'prisjakt-feed' ),
			'mex' => __( 'MEX', 'prisjakt-feed' ),
			'uk'  => __( 'UK', 'prisjakt-feed' ),
			'us'  => __( 'US', 'prisjakt-feed' ),
		];
	}

	/**
	 * @return array
	 */
	public function extra_options_to_attributes(): array {
		$options    = $this->get_options();
		$attributes = [];

		foreach ( $options as $option ) :
			$key                = Plugin::PLUGIN_PREFIX . $option['id'];
			$attributes[ $key ] = $option['label'] ?? $option['id'];
		endforeach;

		return $attributes;

	}
}

