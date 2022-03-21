<?php
/**
 * Plugin Name: MariaDB Optimized Database Class
 *
 * *********************************************************************
 *
 * Ensure this file is symlinked or copied to your wp-content directory to provide
 * required database system variable configuration for MariaDB 10.4
 *
 * *********************************************************************
 *
 */

$dbuser      = defined( 'DB_USER' )     ? DB_USER : '';
$dbpassword  = defined( 'DB_PASSWORD' ) ? DB_PASSWORD : '';
$dbname      = defined( 'DB_NAME' )     ? DB_NAME : '';
$dbhost      = defined( 'DB_HOST' )     ? DB_HOST : '';
$selectivity = defined( 'DB_OPTIMIZER_USE_CONDITION_SELECTIVITY' ) ? DB_OPTIMIZER_USE_CONDITION_SELECTIVITY : 1;

// If selectivity is set to a value other than 1, 2, 3, or 4, overwrite to 1.
if ( ! is_int( $selectivity) || 1 > $selectivity || 4 < $selectivity  ) {
	$selectivity = 1;
}

$wpdb = new wpdb( $dbuser, $dbpassword, $dbname, $dbhost );
$wpdb->query( $wpdb->prepare( "SET optimizer_use_condition_selectivity=%d", $selectivity ) );
