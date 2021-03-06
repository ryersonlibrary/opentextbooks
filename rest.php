<?php
/**
 * Project: opentextbooks
 * Project Sponsor: BCcampus <https://bccampus.ca>
 * Copyright 2012-2017 Brad Payne <https://bradpayne.ca>
 * Date: 2017-04-13
 * Licensed under GPLv3, or any later version
 *
 * @author Brad Payne
 * @package OPENTEXTBOOKS
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright (c) 2012-2017, Brad Payne
 */
include_once 'autoloader.php';
use BCcampus\OpenTextBooks\Controllers\Webform;

$args = $_GET;

if ( ! isset( $_GET['type_of'] ) ) {
	$args['type_of'] = 'rest_stats';
}

new Webform\AdoptionController( $args );

header( 'Content-Type: text/json' );

