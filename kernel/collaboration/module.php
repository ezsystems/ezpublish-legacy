<?php
//
// Created on: <17-Apr-2002 11:05:08 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.

//

$Module = array( 'name' => 'eZCollaboration' );

$ViewList = array();
$ViewList['action'] = array(
    'script' => 'action.php',
    "default_navigation_part" => 'ezmynavigationpart',
    'default_action' => array( array( 'name' => 'Custom',
                                      'type' => 'post',
                                      'parameters' => array( 'CollaborationActionCustom',
                                                             'CollaborationTypeIdentifier',
                                                             'CollaborationItemID' ) ) ),
    'post_action_parameters' => array( 'Custom' => array( 'TypeIdentifer' => 'CollaborationTypeIdentifier',
                                                          'ItemID' => 'CollaborationItemID' ) ),
    'params' => array() );
$ViewList['view'] = array(
    'script' => 'view.php',
    "default_navigation_part" => 'ezmynavigationpart',
    'params' => array( 'ViewMode' ),
    "unordered_params" => array( "language" => "Language",
                                 "offset" => "Offset" ) );
$ViewList['item'] = array(
    'script' => 'item.php',
    "default_navigation_part" => 'ezmynavigationpart',
    'params' => array( 'ViewMode', 'ItemID' ),
    "unordered_params" => array( "language" => "Language",
                                 "offset" => "Offset" ) );
$ViewList['group'] = array(
    'script' => 'group.php',
    "default_navigation_part" => 'ezmynavigationpart',
    'params' => array( 'ViewMode', 'GroupID' ),
    "unordered_params" => array( "language" => "Language",
                                 "offset" => "Offset" ) );

?>
