<?php
/**
 * File containing the oauthadmin module definition.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

include_once 'kernel/private/rest/classes/lazy.php';

$Module = array( 'name' => 'Rest client authorization',
                 'variable_params' => true );

$ViewList = array();

$ViewList['authorize'] = array(
    'script' => 'authorize.php',
);

$FunctionList = array( );
?>
