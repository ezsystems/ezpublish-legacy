<?php
//
// Created on: <15-Aug-2002 14:36:10 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

$Module = array( "name" => "eZRole" );

$ViewList = array();
$ViewList["list"] = array(
    "script" => "list.php",
    "params" => array(  ) );
$ViewList["edit"] = array(
    "script" => "edit.php",
    "params" => array( "RoleID" ) );
$ViewList["view"] = array(
    "script" => "view.php",
    "params" => array( "RoleID" ) );

$ViewList["assign"] = array(
    "script" => "assign.php",
    "params" => array( "RoleID" ) );





$ClassID = array(
    'name'=> 'ClassID',
    'values'=> array(),
    "path" => "classes/",
    "file" => "ezcontentclass.php",
    "class" => 'eZContentClass',
    "function" => "fetchAll",
    "parameter" => array( 0, false )
    );

$SectionID = array(
    'name'=> 'SectionID',
    'values'=> array(
        array(
            'Name' => 'Frontpage',
            'value' => '1'),
        array(
            'Name' => 'Sports',
            'value' => '2'),
        array(
            'Name' => 'Music',
            'value' => '3')
        )
    );
$Assigned = array(
    'name'=> 'Assigned',
    'values'=> array(
        array(
            'Name' => 'Self',
            'value' => '1')
        )
    );


$FunctionList['read'] = array( 'ClassID' => $ClassID,
                                 'SectionID' => $SectionID,
                                 'Assigned' => $Assigned );
$FunctionList['create'] = array( 'ClassID' => $ClassID,
                                 'SectionID' => $SectionID
                                );
$FunctionList['edit'] = array( 'ClassID' => $ClassID,
                                 'SectionID' => $SectionID,
                                 'Assigned' => $Assigned );
$FunctionList['remove'] = array( 'ClassID' => $ClassID,
                                 'SectionID' => $SectionID,
                                 'Assigned' => $Assigned );



?>
