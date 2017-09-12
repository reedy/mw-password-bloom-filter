<?php

use MakinaCorpus\Bloom\BloomFilter as MakinaCorpusBloomFilter;

class MakinaCorpus extends BloomFilter {

	/**
	 * @var MakinaCorpusBloomFilter
	 */
	private $filter;

	public function __construct() {
		$this->filter = new MakinaCorpusBloomFilter( 100000, 0.001 );
		$this->serializedFilename = dirname( __DIR__ ) . '/output/MakinaCorpus.ser';
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