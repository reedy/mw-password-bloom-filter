<?php

require_once dirname( __DIR__ ) . '/dsx724/bloomfilter.php';

class dsx724Wrapper extends BloomFilterWrapper {

	/**
	 * @var BloomFilter
	 */
	private $filter;

	public function __construct() {
		$this->filter = BloomFilter::createFromProbability( 100000, 0.001 );
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function get( $name ) {
		return $this->filter->contains( $name );
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