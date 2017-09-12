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
				return new MakinaCorpus();

			case 'maxwilms':
				require_once 'filters/MaxWilms.php';
				return new MaxWilms();

			case 'mrspartak':
				require_once 'filters/MrSpartak.php';
				return new MrSpartak();

			case 'pleonasm':
				require_once 'filters/Pleonasm.php';
				return new Pleonasm();

			case 'rocketlabs':
				require_once 'filters/RocketLabs.php';
				return new RocketLabs();

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