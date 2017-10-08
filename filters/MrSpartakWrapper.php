<?php

class MrSpartakWrapper extends BloomFilterWrapper {

	/**
	 * @var Bloom
	 */
	private $filter;

	/**
	 * @param float $probability
	 */
	public function __construct( $probability = 0.001 ) {
		$this->filter = new Bloom(
			[
				'entries_max' => 100000,
				'error_chance' => $probability,
			]
		);
		$this->serializedFilename = dirname( __DIR__ ) . "/output/MrSpartak-$probability.ser";
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
		$this->filter->set( $name );
	}

	public function unserialize() {
		$this->filter = unserialize( file_get_contents( $this->serializedFilename ) );
	}

	public function serialize() {
		file_put_contents(
			$this->serializedFilename,
			serialize( $this->filter )
		);
	}
}