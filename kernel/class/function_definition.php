<?php
//
// Created on: <06-Oct-2002 16:01:10 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file function_definition.php
*/

$FunctionList = array();
$FunctionList['list'] = array( 'name' => 'list',
                               'operation_types' => array( 'read' ),
                               'call_method' => array( 'include_file' => 'kernel/class/ezclassfunctioncollection.php',
                                                       'class' => 'eZClassFunctionCollection',
                                                       'method' => 'fetchClassList' ),
                               'parameter_type' => 'standard',
                               'parameters' => array( array( 'name' => 'class_filter',
                                                             'type' => 'array',
                                                             'required' => false,
                                                             'default' => false ) ) );

$FunctionList['attribute_list'] = array( 'name' => 'attribute_list',
                                         'operation_types' => array( 'read' ),
                                         'call_method' => array( 'include_file' => 'kernel/class/ezclassfunctioncollection.php',
                                                                 'class' => 'eZClassFunctionCollection',
                                                                 'method' => 'fetchClassAttributeList' ),
                                         'parameter_type' => 'standard',
                                         'parameters' => array( array( 'name' => 'class_id',
                                                                       'type' => 'integer',
                                                                       'required' => true ) ) );

?>
