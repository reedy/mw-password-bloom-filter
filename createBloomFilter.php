<?php

require __DIR__ . '/vendor/autoload.php';

use MakinaCorpus\Bloom\BloomFilter;

$totalTime = -microtime( true );

$filter = new BloomFilter( 100000, 0.001 );

if ( $file = fopen("10_million_password_list_top_100000.txt", "r" ) ) {
	while( !feof( $file ) ) {
		$line = trim( fgets( $file ) );
		if ( !$line ) {
			continue;
		}
		$filter->set( $line);
	}
	fclose( $file );
} else {
	echo "Cannot open 10_million_password_list_top_100000.txt. Giving up :(\n";
	return;
}

$totalTime += microtime( true );

echo sprintf(
	"\nBuilding bloom filter for 100000 passwords %.1f seconds\n",
	$totalTime
);

file_put_contents( '10_million_password_list_top_100000.ser', $filter->serialize() );