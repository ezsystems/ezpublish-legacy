<?php
/**
 * File containing test data generator for HTTP values.
 *
 * Make this file file available to web server, and start sending requests to
 * it, to capture raw server side request data.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

$server_output = var_export( $_SERVER, true );
$get_output = var_export( $_GET, true );
$post_output = var_export ( $_POST, true );
$files_output = var_export( $_FILES, true );
$request_output = var_export( $_REQUEST, true );
$cookie_output = var_export( $_COOKIE, true );

$output = <<<END
<?php
\$server =
{$server_output};

\$get =
{$get_output};

\$post =
{$post_output};

\$files =
{$files_output};

\$request =
{$request_output};

\$cookies =
{$cookie_output};

?>
END;

echo $output;

?>
