<?php
//
// Created on: <18-Nov-2003 10:00:08 amos>
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

include_once( "kernel/classes/ezcontentclass.php" );
include_once( "kernel/classes/ezcontentclassattribute.php" );
include_once( "kernel/classes/ezcontentclassclassgroup.php" );

$Module =& $Params["Module"];
$ClassID = null;
if ( isset( $Params["ClassID"] ) )
    $ClassID = $Params["ClassID"];
$ClassVersion = null;

if ( !is_numeric( $ClassID ) )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_FOUND, 'kernel' );

$class =& eZContentClass::fetch( $ClassID, true, EZ_CLASS_VERSION_STATUS_DEFINED );

if ( !$class )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

$attributes =& $class->fetchAttributes();
include_once( "kernel/classes/ezdatatype.php" );
$datatypes =& eZDataType::registeredDataTypes();

$Module->setTitle( "Edit class " . $class->attribute( "name" ) );

include_once( "kernel/common/template.php" );
$tpl =& templateInit();

$res =& eZTemplateDesignResource::instance();
$res->setKeys( array( array( "class", $class->attribute( "id" ) ),
                      array( 'class_identifier', $class->attribute( 'identifier' ) ) ) );
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "class", $class );
$tpl->setVariable( "attributes", $attributes );
$tpl->setVariable( "datatypes", $datatypes );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:class/view.tpl" );
$Result['path'] = array( array( 'url' => '/class/grouplist/',
                                'text' => ezi18n( 'kernel/class', 'Classes' ) ) );

?>
