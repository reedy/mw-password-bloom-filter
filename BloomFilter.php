<?php

require __DIR__ . '/vendor/autoload.php';

abstract class BloomFilter {

	protected $serializedFilename;

	/**
	 * @param $name
	 * @return BloomFilter
	 */
	public static function newFromName( $name ) {
		switch ( strtolower( $name ) ) {

			case 'makinacorpus':
				require_once 'filters/MakinaCorpus.php';
				return new MakinaCorpus;

			default:
				// fail
				throw new InvalidArgumentException( "$name isn't known Bloom Filter" );
		}
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public abstract function get( $name );

	/**
	 * @param string $name
	 */
	public abstract function set( $name );

	public abstract function unserialize();

	public abstract function serialize();
}