<?php

use \RocketLabs\BloomFilter\BloomFilter as RocketLabsBloomFilter;
use RocketLabs\BloomFilter\Persist\BitString;

class RocketLabs extends BloomFilter {

	/**
	 * @var RocketLabsBloomFilter
	 */
	private $filter;

	/**
	 * @var BitString
	 */
	private $bitString;

	public function __construct() {
		$this->bitString = new BitString();
		$this->filter = RocketLabsBloomFilter::createFromApproximateSize(
			$this->bitString,
			100000,
			0.001
		);
		$this->serializedFilename = dirname( __DIR__ ) . '/output/RocketLabs.ser';

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
		$this->bitString = BitString::createFromString( file_get_contents( $this->serializedFilename ) );
		$this->filter = RocketLabsBloomFilter::createFromApproximateSize(
			$this->bitString,
			100000,
			0.001
		);
	}

	public function serialize() {
		file_put_contents(
			$this->serializedFilename,
			$this->bitString->toString()
		);
	}
}
