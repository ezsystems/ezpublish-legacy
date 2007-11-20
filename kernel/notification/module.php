<?php
//
// Definition of Module class
//
// Created on: <14-May-2003 16:29:33 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file module.php
*/

$Module = array( "name" => "eZNotification",
                 "variable_params" => true );


$ViewList = array();
$ViewList["settings"] = array(
    "functions" => array( 'use' ),
    "script" => "settings.php",
    'ui_context' => 'administration',
    "default_navigation_part" => 'ezmynavigationpart',
    "params" => array( ),
    'unordered_params' => array( 'offset' => 'Offset' ) );

$ViewList["runfilter"] = array(
    "functions" => array( 'administrate' ),
    "script" => "runfilter.php",
    'ui_context' => 'administration',
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( ) );

$ViewList["addtonotification"] = array(
    "functions" => array( 'use' ),
    "script" => "addtonotification.php",
    'ui_context' => 'administration',
    "default_navigation_part" => 'ezcontentnavigationpart',
    "params" => array( 'ContentNodeID' ) );

$FunctionList['use'] = array( );
$FunctionList['administrate'] = array( );


?>
