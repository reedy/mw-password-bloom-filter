<?php

require_once 'BloomFilterWrapper.php';

echo sprintf( "Running PHP version %s (%s) on %s %s %s\n",
	phpversion(),
	php_uname( 'm' ),
	php_uname( 's' ),
	php_uname( 'r' ),
	php_uname( 'v' )
);

$passwords = BloomFilterWrapper::getPasswords();
foreach ( BloomFilterWrapper::getFilterNames() as $filterName ) {
	$totalTime = -microtime( true );

	$filter = BloomFilterWrapper::newFromName( $filterName );

	foreach( $passwords as $password ) {
		$filter->set( $password );
	}

	$totalTime += microtime( true );

	echo sprintf( "\nBuilding $filterName bloom filter for 100000 passwords in %.1f seconds\n", $totalTime );

	$totalTime = -microtime( true );

	$failCount = 0;
	shuffle( $passwords );

	$times = [];

	foreach( $passwords as $password ) {
		$time = -microtime( true );
		$res = $filter->get( $password );
		$time += microtime( true );
		$times[] = $time;
		if ( !$res ) {
			$failCount++;
			echo "'$password' isn't found by $filterName bloom filter.\n";
		}
	}

	echo "$failCount missing items from the bloom filter.\n";

	$totalTime += microtime( true );

	echo sprintf( "Testing $filterName bloom filter for 100000 passwords in %.1f seconds\n", $totalTime );

	$count = count( $times );
	sort( $times, SORT_NUMERIC );
	$min = $times[0];
	$max = end( $times );
	if ( $count % 2 ) {
		$median = $times[ ( $count - 1 ) / 2 ];
	} else {
		$median = ( $times[$count / 2] + $times[$count / 2 - 1] ) / 2;
	}
	$total = array_sum( $times );
	$mean = $total / $count;

	addResult( [
		'name' => $filterName,
		'count' => $count,
		'total' => $total,
		'min' => $min,
		'median' => $median,
		'mean' => $mean,
		'max' => $max,
		'usage' => [
			'mem' => memory_get_usage( true ),
			'mempeak' => memory_get_peak_usage( true ),
		],
	] );
}

function addResult( $res ) {
	$ret = sprintf( "%s\n  %' 6s: %d\n", $res['name'], 'times', $res['count'] );
	foreach ( [ 'total', 'min', 'median', 'mean', 'max' ] as $metric ) {
		$ret .= sprintf( "  %' 6s: %6.2fms\n", $metric, $res[$metric] );
	}
	foreach ( [ 'mem' => 'Current memory usage', 'mempeak' => 'Peak memory usage' ] as $key => $label ) {
		$ret .= sprintf( "%' 20s: %s\n", $label, formatComputingNumbers( $res['usage'][$key] ) );
	}
	echo "$ret\n";
}

function formatComputingNumbers( $size, $boundary = 1024 ) {
	$sizes = [ '', 'kilo', 'mega', 'giga', 'tera', 'peta', 'exa', 'zeta', 'yotta' ];
	$index = 0;
	$maxIndex = count( $sizes ) - 1;
	while ( $size >= $boundary && $index < $maxIndex ) {
		$index++;
		$size /= $boundary;
	}
	return "$size {$sizes[$index]}bytes";
}