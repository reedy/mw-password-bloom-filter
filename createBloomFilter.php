<?php

require_once 'BloomFilter.php';

$totalTime = -microtime( true );

$filter = BloomFilter::newFromName( 'MakinaCorpus' );

if ( $file = fopen("10_million_password_list_top_100000.txt", "r" ) ) {
	while( !feof( $file ) ) {
		$line = trim( fgets( $file ) );
		if ( !$line ) {
			continue;
		}
		$filter->set( $line );
	}
	fclose( $file );
} else {
	echo "Cannot open 10_million_password_list_top_100000.txt. Giving up :(\n";
	return;
}

$totalTime += microtime( true );

echo sprintf(
	"\nBuilding bloom filter for 100000 passwords in %.1f seconds\n",
	$totalTime
);

$totalTime = -microtime( true );

$filter->serialize();

$totalTime += microtime( true );

echo sprintf(
	"\nSerialized bloom filter for 100000 passwords in %.1f seconds\n",
	$totalTime
);