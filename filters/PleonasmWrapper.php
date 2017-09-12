<?php

use Pleo\BloomFilter\BloomFilter;

class PleonasmWrapper extends BloomFilterWrapper {

	/**
	 * @var BloomFilter
	 */
	private $filter;

	public function __construct() {
		$this->filter = BloomFilter::create( 100000, 0.001 );
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function get( $name ) {
		return $this->filter->exists( $name );
	}

	/**
	 * @param string $name
	 */
	public function set( $name ) {
		$this->filter->add( $name );
	}

	public function unserialize() {
		// TODO: Implement unserialize() method.
	}

	public function serialize() {
		// TODO: Implement serialize() method.
	}
}