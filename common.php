<?php

/**
 *
 */

// Die if PHP is not new enough
if ( version_compare( PHP_VERSION, '4.3', '<' ) ) {
	die( sprintf( 'Your server is running PHP version %s but webim requires at least 4.3', PHP_VERSION ) );
}

if ( !defined( 'WEBIM_PATH' ) ) 
	define( 'WEBIM_PATH', dirname( __FILE__ ) . '/' );

if ( !defined( 'WEBIMDB_DEBUG' ) )
	define( 'WEBIMDB_DEBUG', true );

if ( !defined( 'WEBIMDB_CHARSET' ) )
	define( 'WEBIMDB_CHARSET', 'utf8' );

// Modify error reporting levels to exclude PHP notices
error_reporting( E_ALL ^ E_NOTICE );

require_once( WEBIM_PATH . 'lib/functions.helper.php' );
require_once( WEBIM_PATH . 'lib/functions.json.php' );
require_once( WEBIM_PATH . 'lib/functions.actions.php' );
require_once( WEBIM_PATH . 'lib/class.webim_db.php' );

require_once( WEBIM_PATH . 'lib/http_client.php' );
require_once( WEBIM_PATH . 'lib/class.webim_client.php' );

/** 
 * Custom interface 
 *
 * Provide 
 *
 * array $_IMC
 * boolean $im_is_admin
 * boolean $im_is_login
 * object $imuser require when $im_is_login is true
 * function webim_get_buddies( $ids )
 * function webim_get_online_buddies()
 * function webim_get_rooms( $ids )
 * function webim_get_notifications()
 * function webim_login( $username, $password, $question, $answer ) require when $_IMC['allow_login'] is true
 *
 */

require_once( WEBIM_PATH . 'interface.php' );

/**
 * $im_params = array_merge( $_GET, $_POST );
 */

/** $imdb, $imuser, $imclient, $_IMC */
$imdb = new webim_db( $_IMC['dbuser'], $_IMC['dbpassword'], $_IMC['dbname'], $_IMC['dbhost'] );
$imdb->set_prefix( $_IMC['dbtable_prefix'] );
$imdb->add_tables( array( 'webim_settings', 'webim_histories' ) );
if ( $im_is_login ) {
	$imticket = webim_gp( 'ticket' );
	if( $imticket ) {
		$imticket = stripslashes($imticket);
	}
	$imclient = new webim_client( $imuser, $imticket, $_IMC['domain'], $_IMC['apikey'], $_IMC['host'], $_IMC['port'] );
	unset( $imticket );
}

?>
