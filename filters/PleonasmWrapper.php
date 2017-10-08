<?php

use Pleo\BloomFilter\BloomFilter;

class PleonasmWrapper extends BloomFilterWrapper {

	/**
	 * @var BloomFilter
	 */
	private $filter;

	/**
	 * @param float $probability
	 */
	public function __construct( $probability = 0.001 ) {
		$this->filter = BloomFilter::init( 100000, $probability );
		$this->serializedFilename = dirname( __DIR__ ) . "/output/Pleonasm-$probability.json";
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
		if ( !file_exists( $this->serializedFilename ) ) {
			echo "Can't open {$this->serializedFilename}. Have you run createBloomFilter.php first?\n";
			throw new Exception();
		}
		$this->filter = BloomFilter::initFromJson(
			json_decode( file_get_contents( $this->serializedFilename ), true )
		);
	}

	public function serialize() {
		file_put_contents(
			$this->serializedFilename,
			json_encode( $this->filter )
		);
	}
}