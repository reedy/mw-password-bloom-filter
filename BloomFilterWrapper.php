<?php

require __DIR__ . '/vendor/autoload.php';

abstract class BloomFilterWrapper {

	protected $serializedFilename;

	/**
	 * @param $name
	 * @return BloomFilterWrapper
	 */
	public static function newFromName( $name ) {
		switch ( strtolower( $name ) ) {

			case 'makinacorpus':
				require_once 'filters/MakinaCorpusWrapper.php';
				return new MakinaCorpusWrapper();

			case 'maxwilms':
				require_once 'filters/MaxWilmsWrapper.php';
				return new MaxWilmsWrapper();

			case 'mrspartak':
				require_once 'filters/MrSpartakWrapper.php';
				return new MrSpartakWrapper();

			case 'pleonasm':
				require_once 'filters/PleonasmWrapper.php';
				return new PleonasmWrapper();

			case 'rocketlabs':
				require_once 'filters/RocketLabsWrapper.php';
				return new RocketLabsWrapper();

			default:
				// fail
				throw new InvalidArgumentException( "$name isn't known Bloom Filter" );
		}
	}

	/**
	 * @return array
	 */
	public static function getFilterNames() {
		return [ 'MakinaCorpus', 'MrSpartak', 'RocketLabs' ];
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