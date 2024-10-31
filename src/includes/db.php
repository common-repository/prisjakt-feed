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

/** WordPress Schema API */
require_once ABSPATH . 'wp-admin/includes/schema.php';

if ( ! function_exists( 'insert_on_duplicate' ) ) :

	/**
	 * @param string       $table Table name.
	 * @param array        $data Data to insert (in column => value pairs).
	 *                                             Both $data columns and $data values should be "raw" (neither should be SQL escaped).
	 *                                             Sending a null value will cause the column to be set to NULL - the corresponding
	 *                                             format is ignored in this case.
	 * @param array        $update_fields Data to insert, when record already exists
	 * @param array|string $format Optional. An array of formats to be mapped to each of the value in $data.
	 *                                      If string, that format will be used for all of the values in $data.
	 *                                      A format is one of '%d', '%f', '%s' (integer, float, string).
	 *                                      If omitted, all values in $data will be treated as strings unless otherwise
	 *                                      specified in wpdb::$field_types.
	 * @param $format
	 *
	 * @return mixed
	 */
	function insert_on_duplicate( $table, $data, $update_fields, $format = null ) {
		global $wpdb;

		if ( empty( $update_fields ) ) {
			return $wpdb->insert( $table, $data, $format );
		}

		$wpdb->insert_id = 0;

		$keys          = array_keys( $data );
		$fields        = '`' . implode( '`, `', $keys ) . '`';
		$values        = implode(
			', ',
			array_map(
				function ( $value ) use ( $wpdb ) {
					$value = $wpdb->_escape( $value );
					if ( is_numeric( $value ) ) {
						return $wpdb->prepare( '%d', $value );
					} else {
						return $wpdb->prepare( '%s', $value );
					}
				},
				array_values( $data )
			)
		);
		$update_fields = implode(
			', ',
			array_map(
				function ( $field ) {
					return "`$field` = VALUES(`$field`)";
				},
				$update_fields
			)
		);

		$wpdb->check_current_query = false;

		// Query parts already fully covered by prepare method.
		// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		return $wpdb->query(
			$wpdb->prepare(
				'INSERT INTO `%1s` (%1s) VALUES (' . $values . ') ON DUPLICATE KEY UPDATE %1s;',
				[
					$table,
					$fields,
					$update_fields,
				]
			)
		);
		// phpcs:enable
	}

	/**
	 * @param string       $table Table name.
	 * @param array        $data Data to insert (in column => value pairs).
	 *                                             Both $data columns and $data values should be "raw" (neither should be SQL escaped).
	 *                                             Sending a null value will cause the column to be set to NULL - the corresponding
	 *                                             format is ignored in this case.
	 * @param array        $update_fields Array of Data to insert, when record already exists
	 * @param array|string $format Optional. An array of formats to be mapped to each of the value in $data.
	 *                                      If string, that format will be used for all of the values in $data.
	 *                                      A format is one of '%d', '%f', '%s' (integer, float, string).
	 *                                      If omitted, all values in $data will be treated as strings unless otherwise
	 *                                      specified in wpdb::$field_types.
	 * @param $format
	 *
	 * @return mixed
	 */
	function multiple_insert_on_duplicate( $table, $data, $update_fields, $format = null ) {
		global $wpdb;

		if ( empty( $update_fields ) ) {
			return $wpdb->insert( $table, $data, $format );
		}

		$wpdb->insert_id = 0;

		$keys   = array_keys( $data[0] );
		$fields = '`' . implode( '`, `', $keys ) . '`';

		foreach ( $data as $item ) {
			$values = '(' . implode(
				', ',
				array_map(
					function ( $value ) {
						global $wpdb;

						return '"' . $wpdb->_escape( $value ) . '"';
					},
					array_values( $item )
				)
			) . ')';

			$multiData[] = $values;
		}

		$update_fields = implode(
			', ',
			array_map(
				function ( $field ) {
					return "`$field` = VALUES(`$field`)";
				},
				$update_fields
			)
		);

		$wpdb->check_current_query = false;

		$values = implode( ",\n", $multiData );

		return $wpdb->query(
			$wpdb->prepare(
				'INSERT INTO `%1s` (%s) VALUES %s ON DUPLICATE KEY UPDATE %s;',
				[
					$table,
					$fields,
					$values,
					$update_fields,
				]
			)
		);
	}
endif;
