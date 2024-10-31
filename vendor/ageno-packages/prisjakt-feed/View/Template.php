<?php

namespace Ageno\Prisjakt\View;

abstract class Template {

	const XML_INDENTS         = '    ';
	const XML_DOUBLE_INDENTS  = '        ';
	public const CDATA_FIELDS = [ 'link', 'description', 'image_link', 'additional_image_link', 'description', 'title', 'google_product_category', 'product_type' ];

	protected function fillTemplate( array $variables, string $template ) {
		$xml   = $template;
		$cdata = false;

		foreach ( $variables as $key => $value ) {
			if ( is_array( $value ) ) {
				if ( 'items' === $key ) {
					$value = implode( PHP_EOL . self::XML_DOUBLE_INDENTS, $value );
					$value = self::XML_DOUBLE_INDENTS . str_replace( '\n', PHP_EOL . self::XML_DOUBLE_INDENTS, $value );
				} elseif ( 'fields' === $key ) {
					$value = implode( PHP_EOL . self::XML_INDENTS, $value );
					$value = self::XML_INDENTS . str_replace( '\n', PHP_EOL . self::XML_INDENTS, $value );
				} else {
					$value = implode( PHP_EOL, $value );
				}
			}

			if ( 'key' === $key && in_array( $value, self::CDATA_FIELDS, true ) ) {
				$cdata = true;
			} elseif ( 'value' === $key && ( $cdata || $this->needToEscape( $value ) ) ) {
				$value = '<![CDATA[' . $value . ']]>';
			}

			$xml = str_replace( "{{%{$key}%}}", $value, $xml );
		}

		return $xml;
	}

	protected function needToEscape( $string ) {
		$chars = [ '?', '$', '%', '&', '<', '>', '/', "\n", "\r\n", '\\', '[', ']', '~', '@' ];
		if ( count(
			array_filter(
				$chars,
				function ( $needle ) use ( $string ) {
					return strpos( $string, $needle ) !== false;
				}
			)
		) > 0 ) {
			return true;
		} else {
			return false;
		}
	}
}
