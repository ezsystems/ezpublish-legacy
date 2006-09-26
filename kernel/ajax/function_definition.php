<?php
//
// Created on: <20-Oct-2005 23:10:24 ymc-dabe>
//
// Copyright (C) 2005 Young Media Concepts GmbH. All rights reserved.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// If you wish to use this extension under conditions others than the
// GPL you need to contact Young MediaConcepts firtst (licence@ymc.ch).
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ymc.ch if any conditions of this licencing isn't clear to
// you.
//

$FunctionList = array();

$FunctionList = array();
$FunctionList['fetch_open_nodes'] = array( 'name' => 'dynamic_content_struktur_menu_open_nodes',
                                           'operation_types' => array(),
                                           'call_method' => array( 'include_file' => 'kernel/ajax/xajax_app/treemenu/ezajaxtreemenu.php',
                                           'class' => 'eZOdcsmFunctionCollection',
                                           'method' => 'dynamicContentStrukturMenuOpenNodes' ),
                                           'parameter_type' => 'standard',
                                           'parameters' => array( array( 'name' => 'node_id',
                                           'type' => 'integer',
                                           'required' => false) ) );
                                           
?>
