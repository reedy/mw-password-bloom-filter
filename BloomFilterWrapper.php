<?php

require_once __DIR__ . '/vendor/autoload.php';

abstract class BloomFilterWrapper {

	protected $serializedFilename;

	/**
	 * @param string $name
	 * @param float $probability
	 * @return BloomFilterWrapper
	 */
	public static function newFromName( $name, $probability = 0.001 ) {
		switch ( strtolower( $name ) ) {

			case 'dsx724':
				require_once 'filters/dsx724Wrapper.php';
				return new dsx724Wrapper( $probability );

			case 'makinacorpus':
				require_once 'filters/MakinaCorpusWrapper.php';
				return new MakinaCorpusWrapper( $probability );

			case 'maxwilms':
				require_once 'filters/MaxWilmsWrapper.php';
				return new MaxWilmsWrapper( $probability );

			case 'mrspartak':
				require_once 'filters/MrSpartakWrapper.php';
				return new MrSpartakWrapper( $probability );

			case 'pleonasm':
				require_once 'filters/PleonasmWrapper.php';
				return new PleonasmWrapper( $probability );

			case 'rocketlabs':
				require_once 'filters/RocketLabsWrapper.php';
				return new RocketLabsWrapper( $probability );

			default:
				// fail
				throw new InvalidArgumentException( "$name isn't known Bloom Filter" );
		}
	}

	/**
	 * Some filters don't make reproducible builds. CBA with that
	 *
	 * @return array
	 */
	public static function getNonStableBuildFilterNames() {
		return [ 'MrSpartak', 'RocketLabs' ];
	}

	/**
	 * @return array
	 */
	public static function getSerialisableFilterNames() {
		return [ 'MakinaCorpus', 'MaxWilms', 'Pleonasm' ];
	}

	/**
	 * @return array
	 */
	public static function getNonSerialisableFilterNames() {
		return [ 'dsx724' ];
	}

	/**
	 * @param bool $includeUnstable
	 * @return array
	 */
	public static function getFilterNames( $includeUnstable = false ) {
		$names = array_merge( self::getSerialisableFilterNames(), self::getNonSerialisableFilterNames() );
		if ( $includeUnstable ) {
			$names = array_merge( $names, self::getNonStableBuildFilterNames() );
		}
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