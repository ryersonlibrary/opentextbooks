<?php
/**
 * Project: opentextbooks
 * Project Sponsor: BCcampus <https://bccampus.ca>
 * Copyright 2012-2016 Brad Payne <https://bradpayne.ca>
 * Date: 2016-05-31
 * Licensed under GPLv3, or any later version
 *
 * @author Brad Payne
 * @package OPENTEXTBOOKS
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright (c) 2012-2016, Brad Payne
 *
 * Generic utility functions
 */

namespace BCcampus\Utility;

/**
 * helper function to sanitize user input. If it's not empty,
 * returns the trimmed, sanitized string.
 *
 * @param $anyString
 *
 * @return bool|mixed|string
 */
function sanitize( $anyString ) {
	$result = '';
	if ( ! empty( $anyString ) ) {
		$result = trim( $anyString );
		$result = str_replace( '"', '', $result );
		$result = filter_var( $result, FILTER_SANITIZE_STRING ); //strip tags

		return $result;
	} else {
		return false;
	}
}

/**
 * helper function that url encodes input (with + signs as spaces)
 *
 * @param item that needs encoding
 *
 * @param $anyString
 *
 * @return bool|string
 */
function url_encode( $anyString ) {
	$result = '';
	if ( ! empty( $anyString ) ) {
		$result = urlencode( $anyString );

		return $result;
	} else {
		return false;
	}
}

/**
 * helper function that rawURL encodes (with %20 as spaces)
 *
 * @param $anyString
 *
 * @return bool
 */
function raw_url_encode( $anyString ) {
	if ( ! empty( $anyString ) ) {
		return rawurlencode( $anyString );
	} else {
		return false;
	}
}

/**
 * Helper function to turn an array into a comma separated value. If it's passed
 * a key (mostly an author's name) it will strip out the Equella user name
 *
 * @param array $anyArray
 * @param string $key
 *
 * @return bool|string
 */
function array_to_csv( $anyArray = array(), $key = '' ) {
	$result = '';

	if ( is_array( $anyArray ) ) {
		//if it's not being passed a key from an associative array
		//NOTE adding a space to either side of the comma below will break the
		//integrity of the url given to get_file_contents above.
		if ( $key == '' ) {
			foreach ( $anyArray as $value ) {
				$result .= $value . ",";
			}
			//return the value at the key in the associative array
		} else {
			foreach ( $anyArray as $value ) {
				//names in db sometimes contain usernames [inbrackets], strip 'em out!
				$tmp    = ( ! strpos( $value[ $key ], '[' ) ) ? $value[ $key ] : rtrim( strstr( $value[ $key ], '[', true ) );
				$result .= $tmp . ", ";
			}
		}

		$result = rtrim( $result, ', ' );
	} else {
		return false;
	}

	return $result;
}

/**
 *
 * @param $any_array
 *
 * @return string
 */
function array_to_string( $any_array ) {
	$result = '';

	if ( is_array( $any_array ) ) {
		$result = implode(" ", $any_array);
	}

	return $result;
}

/**
 * @param $number
 *
 * @return float|string
 */
function determine_file_size( $number ) {
	$result = '';
	$num    = '';

	//bail if nothing is passed.
	if ( empty( $number ) ) {
		return $result;
	}

	//if it's a number
	if ( is_int( $number ) ) {
		$num = intval( $number );
	}
	//only process if it's bigger than zero
	if ( $num > 0 ) {
		//return in Megabytes
		$result = ( $num / 1000000 );
		//account for the fact that it might be less than 1MB
		( $result <= 1 ) ? $result = round( $result, 2 ) : $result = intval( $result );
		$result = "(" . $result . " MB)";
	}

	return $result;
}

/**
 * @TODO this needs to be generated dynamically
 * Needed to generate a link to the canadian version
 * on open.
 *
 * @param $uuid
 *
 * @return mixed (bool/array)
 */
function has_canadian_edition( $uuid ) {
	$list = array(
		'43cb3' => array( 'a2086095-a679-4e33-b0dd-f5b96192fba1', 'Concepts of Biology' ),
		'13c92' => array( '8390d51e-0efe-493c-881c-cf86852a612f', 'Introduction to Psychology' ),
		'43cd1' => array( 'debe8d05-dbdf-4cb8-80f9-87b547ea621c', 'Introduction to Sociology' ),
		'b98db' => array( '2903c1ea-7e71-4db4-9c02-071616a65f1f', 'Introductory Business Statistics' ),
		'2b774' => array( 'c7025f6b-f32b-4d0a-865e-f473d9f98fb6', 'Introductory Chemistry' ),
		'807b5' => array( '91cdcf18-273d-44cc-8432-865d09005fda', 'Mastering Strategic Management' ),
		'd0a98' => array( '66c0cf64-c485-442c-8183-de75151f13f5', 'Principles of Social Psychology' ),
		'497a7' => array( 'b58ffd04-ca71-4365-95e1-916f2105bd55', 'Research Methods in Psychology' ),
		'be97a' => array( '8d415c45-41da-4c9d-8c9d-ab66bbdd359c', 'Writing for Success' ),
	);
	if ( array_key_exists( $uuid, $list ) ) {
		return $list[ $uuid ][0];
	} else {
		return false;
	}
}

/**
 * @param $book
 * @param $data
 *
 * @return bool
 * @throws \Exception
 */
function ls_sanity_check( $book, $data ) {

	if ( false == array_key_exists( $book, $data ) ) {
		throw new \Exception( "The bookUid entered is not one that we have listed
        in our survey" );
	};

	return true;
}

function restrict_access() {
	$expected = array(
		OTB_DIR . 'cache/analytics/',
		OTB_DIR . 'cache/catalogue/',
		OTB_DIR . 'cache/reviews/',
		OTB_DIR . 'cache/webform/',
	);

	foreach ( $expected as $path ) {
		$path_to_htaccess = $path . '.htaccess';
		if ( ! file_exists( $path_to_htaccess ) && is_writable( $path ) ) {
			// Restrict access
			file_put_contents( $path_to_htaccess, "deny from all\n" );
		}
	}

	return true;
}
