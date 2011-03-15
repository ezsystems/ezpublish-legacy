<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

$Module = array( "name" => "eZInfo",
                 "variable_params" => true );

$ViewList = array();
$ViewList["copyright"] = array(
    "functions" => array( 'read' ),
    "script" => "copyright.php",
    "params" => array() );

$ViewList["about"] = array(
    "functions" => array( 'read' ),
    "script" => "about.php",
    "params" => array() );

$ViewList["is_alive"] = array(
    "functions" => array( 'read' ),
    "script" => "isalive.php",
    "params" => array() );

$FunctionList = array();
$FunctionList['read'] = array();

?>
