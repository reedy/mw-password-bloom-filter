<?php

use maxwilms\BloomFilter\BloomFilterGenerator;

class MaxWilmsWrapper extends BloomFilterWrapper {

	/**
	 * @var \maxwilms\BloomFilter\BloomFilter
	 */
	private $filter;

	/**
	 * @param float $probability
	 */
	public function __construct( $probability = 0.001 ) {
		$this->filter = BloomFilterGenerator::generate(100000, $probability );
		$this->serializedFilename = dirname( __DIR__ ) . "/output/MaxWilms-$probability.ser";
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
		if ( !file_exists( $this->serializedFilename ) ) {
			echo "Can't open {$this->serializedFilename}. Have you run createBloomFilter.php first?\n";
			throw new Exception();
		}
		$this->filter = unserialize( file_get_contents( $this->serializedFilename ) );
	}

	public function serialize() {
		file_put_contents(
			$this->serializedFilename,
			serialize( $this->filter )
		);
	}
}