<?php

/******************************************************************************/
//   ____             __ _
//  / ___|___  _ __  / _(_) __ _
// | |   / _ \| '_ \| |_| |/ _` |
// | |__| (_) | | | |  _| | (_| |
//  \____\___/|_| |_|_| |_|\__, |
//                         |___/
//
// The core, application specific config stuff.
/******************************************************************************/

/******************************************************************************/
// Load the local config and bootstrap items.
require_once('bootstrap.php');
require_once('local.inc.php');

/******************************************************************************/
// TODO: Generate a nonce if we are using metatag based Content-Security-Policy.
// $NONCE = base64_encode(random_bytes(20));
$NONCE = bin2hex(openssl_random_pseudo_bytes(32));

/******************************************************************************/
// Set the HTML templating options.
// $TEMPLATE_FRAMEWORK = 'bootstrap-4.6';
$TEMPLATE_FRAMEWORK = 'bootstrap-5.3';

/**************************************************************************************************/
// Define the defaults.
$VALID_CONTENT_TYPES = array('application/vnd.api+json', 'application/json','text/plain','text/html');
$VALID_CHARSETS = array('utf-8','iso-8859-1','cp-1252');

?>
