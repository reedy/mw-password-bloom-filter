<?php

require_once 'BloomFilterWrapper.php';

$passwords = BloomFilterWrapper::getPasswords();
foreach ( BloomFilterWrapper::getSerialisableFilterNames() as $filterName ) {
	$totalTime = -microtime( true );

	$filter = BloomFilterWrapper::newFromName( $filterName );

	try {
		$filter->unserialize();
	} catch ( Exception $ex ) {
		return;
	}

	$failCount = 0;
	shuffle( $passwords );
	foreach( $passwords as $password ) {
		if ( !$filter->get( $password ) ) {
			$failCount++;
			echo "'$password' isn't found by $filterName bloom filter.\n";
		}
	}

	echo "$failCount missing items from the bloom filter.\n";

	$totalTime += microtime( true );

	echo sprintf( "Testing $filterName bloom filter for 100000 passwords in %.1f seconds\n", $totalTime );
}
