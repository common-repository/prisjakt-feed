<?php

namespace PrisjaktFeed\Common\Utils\Fields;

use PrisjaktFeed\Config\Plugin;


/**
 *
 */
class Fields {

	/**
	 * @var string
	 */
	public $prefix = Plugin::PLUGIN_PREFIX;

	/**
	 * @param $value
	 *
	 * @return string
	 */
	public function get_prefixed_value( $value ): string {
		return $this->prefix . $value;
	}

	/**
	 * @param $value
	 *
	 * @return string
	 */
	public function get_un_prefix_value( $value ): string {
		return str_replace( $this->prefix, '', $value );
	}
}
