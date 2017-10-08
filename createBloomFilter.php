<?php

require_once 'BloomFilterWrapper.php';

$passwords = BloomFilterWrapper::getPasswords();
foreach ( BloomFilterWrapper::getSerialisableFilterNames() as $filterName ) {
	foreach ( [ 0.01, 0.001, 0.0001 ] as $probability ) {
		$totalTime = -microtime( true );
		$filter = BloomFilterWrapper::newFromName( $filterName, $probability );

		foreach ( $passwords as $password ) {
			$filter->set( $password );
		}

		$totalTime += microtime( true );

		echo sprintf(
			"\nBuilding $filterName bloom filter for 100000 passwords with %.4f probability in %.1f seconds\n",
			$probability,
			$totalTime
		);

		$totalTime = -microtime( true );

		$filter->serialize();

		$totalTime += microtime( true );

		echo sprintf(
			"\nSerialized $filterName bloom filter for 100000 passwords with %.4f probability in %.1f seconds\n",
			$probability,
			$totalTime
		);
	}
}