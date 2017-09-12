<?php

require_once 'BloomFilter.php';

$totalTime = -microtime( true );

$filter = BloomFilter::newFromName( 'MakinaCorpus' );

try {
	$filter->unserialize();
} catch (Exception $ex) {
}

$failCount = 0;
if ( $file = fopen("10_million_password_list_top_100000.txt", "r" ) ) {
	while( !feof( $file ) ) {
		$line = trim( fgets( $file ) );
		if ( !$line ) {
			continue;
		}
		if ( !$filter->get( $line ) ) {
			$failCount++;
			echo "'$line' isn't found by bloom filter.\n";
		}
	}
	fclose( $file );
} else {
	echo "Cannot open 10_million_password_list_top_100000.txt. Giving up :(\n";
	return;
}

$totalTime += microtime( true );

echo sprintf(
	"\nTesting bloom filter for 100000 passwords in %.1f seconds\n",
	$totalTime
);
