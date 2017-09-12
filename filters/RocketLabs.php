<?php

use \RocketLabs\BloomFilter\BloomFilter as RocketLabsBloomFilter;

class RocketLabs extends BloomFilter {

	private $filter;

	public function __construct() {
		$this->filter = RocketLabsBloomFilter::createFromApproximateSize(
			null,
			100000,
			0.001
		);
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function get( $name ) {
		return $this->filter->has( $name );
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
