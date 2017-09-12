<?php

require __DIR__ . '/vendor/autoload.php';

use MakinaCorpus\Bloom\BloomFilter;

$totalTime = -microtime( true );

$filter = new BloomFilter();

$serFile = 'output/MakinaCorpus.ser';
if ( !file_exists( $serFile ) ) {
	echo "Can't open $serFile. Have you run createBloomFilter.php first?\n";
	return;
}

$filter->unserialize( file_get_contents( $serFile ) );

$failCount = 0;
if ( $file = fopen("10_million_password_list_top_100000.txt", "r" ) ) {
	while( !feof( $file ) ) {
		$line = trim( fgets( $file ) );
		if ( !$line ) {
			continue;
		}
		if ( !$filter->check( $line ) ) {
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
	"\nTesting bloom filter for 100000 passwords %.1f seconds\n",
	$totalTime
);
