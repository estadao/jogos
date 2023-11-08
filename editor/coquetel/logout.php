<?php

session_start();
session_unset();
session_destroy();

if ( isset( $_SERVER[ 'HTTP_COOKIE' ] ) ) {
  $cookies = explode( ';', $_SERVER[ 'HTTP_COOKIE' ] );

  foreach( $cookies as $cookie ) {
    $parts = explode( '=', $cookie );
    $key = trim( $parts[0] );

    setcookie( $key, '', time() - 1000 );
    setcookie( $key, '', time() - 1000, '/' );
  }
}

// // Initialize the session.
// // If you are using session_name("something"), don't forget it now!
// session_start();
//
// // Unset all of the session variables.
// $_SESSION = array();
//
// // If it's desired to kill the session, also delete the session cookie.
// // Note: This will destroy the session, and not just the session data!
// if (ini_get("session.use_cookies")) {
//     $params = session_get_cookie_params();
//     setcookie(session_name(), '', time() - 42000,
//         $params["path"], $params["domain"],
//         $params["secure"], $params["httponly"]
//     );
// }
//
// // Finally, destroy the session.
// session_destroy();

// print_r( '<pre>' );
// print_r( $_SERVER[ 'HTTP_COOKIE' ] );
// print_r( '</pre>' );

header( 'location:./' );
