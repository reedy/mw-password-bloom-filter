<?php

require_once 'BloomFilterWrapper.php';

foreach ( BloomFilterWrapper::getFilterNames() as $filterName ) {
	$totalTime = -microtime( true );

	$filter = BloomFilterWrapper::newFromName( 'MakinaCorpus' );

	try {
		$filter->unserialize();
	} catch ( Exception $ex ) {
		return;
	}

	$failCount = 0;
	if ( $file = fopen( "10_million_password_list_top_100000.txt", "r" ) ) {
		while ( !feof( $file ) ) {
			$line = trim( fgets( $file ) );
			if ( !$line ) {
				continue;
			}
			if ( !$filter->get( $line ) ) {
				$failCount++;
				echo "'$line' isn't found by $filterName bloom filter.\n";
			}
		}
		fclose( $file );
	} else {
		echo "Cannot open 10_million_password_list_top_100000.txt. Giving up :(\n";
		return;
	}

	echo "$failCount missing items from the bloom filter.\n";

	$totalTime += microtime( true );

	echo sprintf( "\nTesting $filterName bloom filter for 100000 passwords in %.1f seconds\n", $totalTime );
}
