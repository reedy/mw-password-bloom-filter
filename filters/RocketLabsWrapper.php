<?php

use \RocketLabs\BloomFilter\BloomFilter as RocketLabsBloomFilter;
use RocketLabs\BloomFilter\Persist\BitString;

class RocketLabsWrapper extends BloomFilterWrapper {

	/**
	 * @var RocketLabsBloomFilter
	 */
	private $filter;

	/**
	 * @var BitString
	 */
	private $bitString;

	/**
	 * @var float
	 */
	private $probability;

	/**
	 * @param float $probability
	 */
	public function __construct( $probability = 0.001 ) {
		$this->bitString = new BitString();
		$this->filter = RocketLabsBloomFilter::createFromApproximateSize(
			$this->bitString,
			100000,
			$probability
		);
		$this->probability = $probability;
		$this->serializedFilename = dirname( __DIR__ ) . "/output/RocketLabs-$probability.ser";

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
			$this->probability
		);
	}

	public function serialize() {
		file_put_contents(
			$this->serializedFilename,
			$this->bitString->toString()
		);
	}
}
