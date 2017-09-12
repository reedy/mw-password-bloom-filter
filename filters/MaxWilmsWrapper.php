<?php

use maxwilms\BloomFilter\BloomFilterGenerator;

class MaxWilmsWrapper extends BloomFilterWrapper {

	/**
	 * @var \maxwilms\BloomFilter\BloomFilter
	 */
	private $filter;

	public function __construct() {
		$this->filter = BloomFilterGenerator::generate(100000, 0.001 );
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