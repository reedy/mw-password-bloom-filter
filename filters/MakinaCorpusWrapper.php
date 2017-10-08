<?php

use MakinaCorpus\Bloom\BloomFilter;

class MakinaCorpusWrapper extends BloomFilterWrapper {

	/**
	 * @var BloomFilter
	 */
	private $filter;

	/**
	 * @param float $probability
	 */
	public function __construct( $probability = 0.001 ) {
		$this->filter = new BloomFilter( 100000, $probability );
		$this->serializedFilename = dirname( __DIR__ ) . "/output/MakinaCorpus-$probability.ser";
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function get( $name ) {
		return $this->filter->check( $name );
	}

	/**
	 * @param string $name
	 */
	public function set( $name ) {
		$this->filter->set( $name );
	}

	public function unserialize() {
		if ( !file_exists( $this->serializedFilename ) ) {
			echo "Can't open {$this->serializedFilename}. Have you run createBloomFilter.php first?\n";
			throw new Exception();
		}
		$this->filter->unserialize( file_get_contents( $this->serializedFilename ) );
	}

	public function serialize() {
		file_put_contents(
			$this->serializedFilename,
			$this->filter->serialize()
		);
	}
}