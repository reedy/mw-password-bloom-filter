<?php

class MrSpartak extends BloomFilter {

	private $filter;

	public function __construct() {
		$this->filter = new Bloom(
			[
				'entries_max' => 100000,
				'error_chance' => 0.001,
			]
		);
		$this->serializedFilename = dirname( __DIR__ ) . '/output/MrSpartak.ser';
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