<?php

require_once __DIR__ . '/vendor/autoload.php';

abstract class BloomFilterWrapper {

	protected $serializedFilename;

	/**
	 * @param $name
	 * @return BloomFilterWrapper
	 */
	public static function newFromName( $name ) {
		switch ( strtolower( $name ) ) {

			case 'dsx724':
				require_once 'filters/dsx724Wrapper.php';
				return new dsx724Wrapper();

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
	public static function getSerialisableFilterNames() {
		return [ 'MakinaCorpus', 'MrSpartak', 'RocketLabs' ];
	}

	/**
	 * @return array
	 */
	public static function getNonSerialisableFilterNames() {
		return [ 'dsx724', 'MaxWilms', 'Pleonasm' ];
	}

	/**
	 * @return array
	 */
	public static function getFilterNames() {
		$names = array_merge( self::getSerialisableFilterNames(), self::getNonSerialisableFilterNames() );
		sort( $names );
		return $names;
	}

	/**
	 * @return array
	 */
	public static function getPasswords() {
		static $ret = [];

		if ( count( $ret ) ) {
			return $ret;
		}

		if ( $file = fopen( "10_million_password_list_top_100000.txt", "r" ) ) {
			while ( !feof( $file ) ) {
				$line = trim( fgets( $file ) );
				if ( !$line ) {
					continue;
				}
				$ret[] = $line;
			}
			fclose( $file );
		} else {
			echo "Cannot open 10_million_password_list_top_100000.txt. Giving up :(\n";
		}
		return $ret;
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