<?php
//
// Created on: <16-Apr-2002 12:37:51 amos>
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


function &templateInit( $name = false )
{
    if ( $name === false )
        $tpl =& $GLOBALS["eZPublishTemplate"];
    else
        $tpl =& $GLOBALS["eZPublishTemplate_$name"];
    if ( get_class( $tpl ) == "eztemplate" )
        return $tpl;
    include_once( "lib/eztemplate/classes/eztemplate.php" );

    include_once( 'kernel/common/eztemplatedesignresource.php' );

    $tpl = eZTemplate::instance();

    include_once( 'lib/ezutils/classes/ezini.php' );
    $ini =& eZINI::instance();
    if ( $ini->variable( 'TemplateSettings', 'Debug' ) == 'enabled' )
        eZTemplate::setIsDebugEnabled( true );

    $compatAutoLoadPath = $ini->variableArray( 'TemplateSettings', 'AutoloadPath' );
    $autoLoadPathList = $ini->variable( 'TemplateSettings', 'AutoloadPathList' );

    $extensionAutoloadPath = $ini->variable( 'TemplateSettings', 'ExtensionAutoloadPath' );
    $extensionPathList = eZExtension::expandedPathList( $extensionAutoloadPath, 'autoloads' );

    $autoLoadPathList = array_unique( array_merge( $compatAutoLoadPath, $autoLoadPathList, $extensionPathList ) );

    $a =& $autoLoadPathList;
    $tpl->setAutoloadPathList( $a );
    $tpl->autoload();

    $tpl->registerResource( eZTemplateDesignResource::instance() );
    $tpl->registerResource( eZTemplateDesignResource::standardInstance() );

    return $tpl;
}


?>
