<?php

namespace Ageno\Prisjakt\Model;

abstract class DataObject implements \ArrayAccess {



	/**
	 * Object attributes
	 *
	 * @var array
	 */
	protected $_data = [];

	/**
	 * Setter/Getter underscore transformation cache
	 *
	 * @var array
	 */
	protected static $_underscoreCache = [];

	/**
	 * Constructor
	 *
	 * By default is looking for first argument as array and assigns it as object attributes
	 * This behavior may change in child classes
	 *
	 * @param array $data
	 */
	public function __construct( array $data = [] ) {
		$this->_data = $data;
	}

	/**
	 * Add data to the object.
	 *
	 * Retains previous data in the object.
	 *
	 * @param array $arr
	 * @return DataObject
	 */
	public function addData( array $arr ) {
		foreach ( $arr as $index => $value ) {
			$this->setData( $index, $value );
		}
		return $this;
	}

	/**
	 * Overwrite data in the object.
	 *
	 * The $key parameter can be string or array.
	 * If $key is string, the attribute value will be overwritten by $value
	 *
	 * If $key is an array, it will overwrite all the data in the object.
	 *
	 * @param string|array $key
	 * @param mixed        $value
	 * @return $this
	 */
	public function setData( $key, $value = null ) {
		if ( $key === (array) $key ) {
			$this->_data = $key;
		} else {
			$this->_data[ $key ] = $value;
		}
		return $this;
	}

	/**
	 * Unset data from the object.
	 *
	 * @param null|string|array $key
	 * @return $this
	 */
	public function unsetData( $key = null ) {
		if ( null === $key ) {
			$this->setData( [] );
		} elseif ( is_string( $key ) ) {
			if ( isset( $this->_data[ $key ] ) || array_key_exists( $key, $this->_data ) ) {
				unset( $this->_data[ $key ] );
			}
		} elseif ( $key === (array) $key ) {
			foreach ( $key as $element ) {
				$this->unsetData( $element );
			}
		}
		return $this;
	}

	/**
	 * Object data getter
	 *
	 * If $key is not defined will return all the data as an array.
	 * Otherwise it will return value of the element specified by $key.
	 * It is possible to use keys like a/b/c for access nested array data
	 *
	 * If $index is specified it will assume that attribute data is an array
	 * and retrieve corresponding member. If data is the string - it will be explode
	 * by new line character and converted to array.
	 *
	 * @param string     $key
	 * @param string|int $index
	 * @return mixed
	 */
	public function getData( $key = '', $index = null ) {
		if ( '' === $key ) {
			return $this->_data;
		}

		if ( strpos( $key, '/' ) !== false ) {
			$data = $this->getDataByPath( $key );
		} else {
			$data = $this->_getData( $key );
		}

		if ( null !== $index ) {
			if ( $data === (array) $data ) {
				$data = isset( $data[ $index ] ) ? $data[ $index ] : null;
			} elseif ( is_string( $data ) ) {
				$data = explode( PHP_EOL, $data );
				$data = isset( $data[ $index ] ) ? $data[ $index ] : null;
			} elseif ( $data instanceof DataObject ) {
				$data = $data->getData( $index );
			} else {
				$data = null;
			}
		}
		return $data;
	}

	/**
	 * Get object data by path
	 *
	 * Method consider the path as chain of keys: a/b/c => ['a']['b']['c']
	 *
	 * @param string $path
	 * @return mixed
	 */
	public function getDataByPath( $path ) {
		$keys = explode( '/', $path );

		$data = $this->_data;
		foreach ( $keys as $key ) {
			if ( (array) $data === $data && isset( $data[ $key ] ) ) {
				$data = $data[ $key ];
			} elseif ( $data instanceof DataObject ) {
				$data = $data->getDataByKey( $key );
			} else {
				return null;
			}
		}
		return $data;
	}

	/**
	 * Get object data by particular key
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function getDataByKey( $key ) {
		return $this->_getData( $key );
	}

	/**
	 * Get value from _data array without parse key
	 *
	 * @param string $key
	 * @return  mixed
	 */
	protected function _getData( $key ) {
		if ( isset( $this->_data[ $key ] ) ) {
			return $this->_data[ $key ];
		}
		return null;
	}

	/**
	 * Set object data with calling setter method
	 *
	 * @param string $key
	 * @param mixed  $args
	 * @return $this
	 */
	public function setDataUsingMethod( $key, $args = [] ) {
		$method = 'set' . str_replace( '_', '', ucwords( $key, '_' ) );
		$this->{$method}( $args );
		return $this;
	}

	/**
	 * Get object data by key with calling getter method
	 *
	 * @param string $key
	 * @param mixed  $args
	 * @return mixed
	 */
	public function getDataUsingMethod( $key, $args = null ) {
		$method = 'get' . str_replace( '_', '', ucwords( $key, '_' ) );
		return $this->{$method}( $args );
	}

	/**
	 * If $key is empty, checks whether there's any data in the object
	 *
	 * Otherwise checks if the specified attribute is set.
	 *
	 * @param string $key
	 * @return bool
	 */
	public function hasData( $key = '' ) {
		if ( empty( $key ) || ! is_string( $key ) ) {
			return ! empty( $this->_data );
		}
		return array_key_exists( $key, $this->_data );
	}

	/**
	 * Convert array of object data with to array with keys requested in $keys array
	 *
	 * @param array $keys array of required keys
	 * @return array
	 */
	public function toArray( array $keys = [] ) {
		if ( empty( $keys ) ) {
			return $this->_data;
		}

		$result = [];
		foreach ( $keys as $key ) {
			if ( isset( $this->_data[ $key ] ) ) {
				$result[ $key ] = $this->_data[ $key ];
			} else {
				$result[ $key ] = null;
			}
		}
		return $result;
	}

	/**
	 * The "__" style wrapper for toArray method
	 *
	 * @param array $keys
	 * @return array
	 */
	public function convertToArray( array $keys = [] ) {
		return $this->toArray( $keys );
	}

	/**
	 * The "__" style wrapper for toXml method
	 *
	 * @param array  $arrAttributes array of keys that must be represented
	 * @param string $rootName root node name
	 * @param bool   $addOpenTag flag that allow to add initial xml node
	 * @param bool   $addCdata flag that require wrap all values in CDATA
	 * @return string
	 */
	public function convertToXml(
		array $arrAttributes = [],
			  $rootName = 'item',
			  $addOpenTag = false,
			  $addCdata = true
	) {
		return $this->toXml( $arrAttributes, $rootName, $addOpenTag, $addCdata );
	}

	/**
	 * Set/Get attribute wrapper
	 *
	 * @param string $method
	 * @param array  $args
	 * @return  mixed
	 */
	public function __call( $method, $args ) {
		switch ( substr( $method, 0, 3 ) ) {
			case 'get':
				$key   = $this->_underscore( substr( $method, 3 ) );
				$index = isset( $args[0] ) ? $args[0] : null;
				return $this->getData( $key, $index );
			case 'set':
				$key   = $this->_underscore( substr( $method, 3 ) );
				$value = isset( $args[0] ) ? $args[0] : null;
				return $this->setData( $key, $value );
			case 'uns':
				$key = $this->_underscore( substr( $method, 3 ) );
				return $this->unsetData( $key );
			case 'has':
				$key = $this->_underscore( substr( $method, 3 ) );
				return isset( $this->_data[ $key ] );
		}
	}

	/**
	 * Checks whether the object is empty
	 *
	 * @return bool
	 */
	public function isEmpty() {
		if ( empty( $this->_data ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Converts field names for setters and getters
	 *
	 * $this->setMyField($value) === $this->setData('my_field', $value)
	 * Uses cache to eliminate unnecessary preg_replace
	 *
	 * @param string $name
	 * @return string
	 */
	protected function _underscore( $name ) {
		if ( isset( self::$_underscoreCache[ $name ] ) ) {
			return self::$_underscoreCache[ $name ];
		}
		$result                          = strtolower( trim( preg_replace( '/([A-Z]|[0-9]+)/', '_$1', $name ), '_' ) );
		self::$_underscoreCache[ $name ] = $result;
		return $result;
	}

	/**
	 * Implementation of \ArrayAccess::offsetSet()
	 *
	 * @param string $offset
	 * @param mixed  $value
	 * @return void
	 * @link http://www.php.net/manual/en/arrayaccess.offsetset.php
	 */
	public function offsetSet( $offset, $value ) {
		$this->_data[ $offset ] = $value;
	}

	/**
	 * Implementation of \ArrayAccess::offsetExists()
	 *
	 * @param string $offset
	 * @return bool
	 * @link http://www.php.net/manual/en/arrayaccess.offsetexists.php
	 */
	public function offsetExists( $offset ) {
		return isset( $this->_data[ $offset ] ) || array_key_exists( $offset, $this->_data );
	}

	/**
	 * Implementation of \ArrayAccess::offsetUnset()
	 *
	 * @param string $offset
	 * @return void
	 * @link http://www.php.net/manual/en/arrayaccess.offsetunset.php
	 */
	public function offsetUnset( $offset ) {
		unset( $this->_data[ $offset ] );
	}

	/**
	 * Implementation of \ArrayAccess::offsetGet()
	 *
	 * @param string $offset
	 * @return mixed
	 * @link http://www.php.net/manual/en/arrayaccess.offsetget.php
	 */
	public function offsetGet( $offset ) {
		if ( isset( $this->_data[ $offset ] ) ) {
			return $this->_data[ $offset ];
		}
		return null;
	}
}
