#!/usr/bin/php -d memory_limit=20480M
<?php
/**
 * posts & coments import API
 *
 * PHP version 5
 *
 * @category   WordPress_Tools
 * @package    WordPress
 * @subpackage Import
 * @author     Marcin Pietrzak <marci@iworks.pl>
 * @license    http://iworks.pl/ commercial
 * @version    SVN: $Id: import_posts.php 4872 2012-07-24 13:53:34Z illi $
 * @link       http://iworks.pl/
 *
 */

require 'functions.php';

$persons = array();



$persons = array();

if ( ( $handle = fopen( 'registry.csv', 'r' ) ) !== false ) {
	while ( ( $data = fgetcsv( $handle, 0, ',' ) ) !== false ) {
		if ( 1 > intval( $data[0] ) ) {
			continue;
		}
		/**
		 * owners
		 */
		foreach ( array( 4, 5, 6 ) as $index ) {
			if ( empty( $data[ $index ] ) ) {
				continue;
			}
			$data[ $index ] = trim( $data[ $index ] );
			if ( empty( $data[ $index ] ) ) {
				continue;
			}
			$is_person = check_is_person( $data[ $index ] );



			foreach ( preg_split( '/[;\/\t]/', $data[ $index ] ) as $person_name ) {
				$person_name = person_clear_name( $person_name );
				$p           = trim( $person_name );
				if ( ! in_array( $p, $persons ) ) {
					$persons[] = $p;
				}
			}
		}
	}
	fclose( $handle );
}

$compared = array();
foreach ( $persons as $p1 ) {
	foreach ( $persons as $p2 ) {
		if ( $p1 === $p2 ) {
			continue;
		}
		if (
			isset( $compared[ $p1 . $p2 ] )
			|| isset( $compared[ $p2 . $p1 ] )
		) {
			continue;
		}
		$compared[ $p1 . $p2 ] = true;

		$sim = similar_text( $p1, $p2, $perc );
		if ( 90 < $perc ) {

			echo $p1,';',$p2,PHP_EOL;
			// echo $perc;
			// echo PHP_EOL;
			// echo PHP_EOL;
		}
	}
}

// print_r( $persons);

