<?php

require_once 'BloomFilterWrapper.php';

$passwords = BloomFilterWrapper::getPasswords();
foreach ( BloomFilterWrapper::getSerialisableFilterNames() as $filterName ) {
	$totalTime = -microtime( true );

	$filter = BloomFilterWrapper::newFromName( $filterName );

	foreach( $passwords as $password ) {
		$filter->set( $password );
	}

	$totalTime += microtime( true );

	echo sprintf( "\nBuilding $filterName bloom filter for 100000 passwords in %.1f seconds\n", $totalTime );

	$totalTime = -microtime( true );

	$filter->serialize();

	$totalTime += microtime( true );

	echo sprintf( "\nSerialized $filterName bloom filter for 100000 passwords in %.1f seconds\n", $totalTime );
}