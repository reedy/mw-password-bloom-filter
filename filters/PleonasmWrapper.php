<?php

use Pleo\BloomFilter\BloomFilter;

class PleonasmWrapper extends BloomFilterWrapper {

	/**
	 * @var BloomFilter
	 */
	private $filter;

	public function __construct() {
		$this->filter = BloomFilter::init( 100000, 0.001 );
		$this->serializedFilename = dirname( __DIR__ ) . '/output/Pleonasm.json';
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