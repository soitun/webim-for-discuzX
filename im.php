<?php

include_once( 'common.php' );

$webim_actions = array("online", "offline", "message", "presence", "history", "status", "members", "join", "leave", "buddies", "rooms", "refresh", "clear_history", "setting", "notifications");
$webim_skip_login_actions = array("online");

$webim_action = webim_gp( "webim_action" );

if ( $webim_action && in_array( $webim_action, $webim_actions ) ) {
	if ( !$im_is_login && !in_array( $webim_action, $webim_skip_login_actions ) ) {
		exit( "Please login at first" );
	}
	call_user_func( "webim_action_" . $webim_action );
} else {
	header( "HTTP/1.0 400 Bad Request" );
	exit( $webim_action ? ( "Invalid action " . $webim_action ) : "Pleace provide param \$aciton" );
}

?>
