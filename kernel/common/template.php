<?php
//
// Created on: <16-Apr-2002 12:37:51 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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


function templateInit( $name = false )
{
    if ( $name === false &&
         isset( $GLOBALS['eZPublishTemplate'] ) )
    {
        return $GLOBALS['eZPublishTemplate'];
    }
    if ( isset( $GLOBALS["eZPublishTemplate_$name"] ) )
    {
        return $GLOBALS["eZPublishTemplate_$name"];
    }
    //include_once( 'lib/eztemplate/classes/eztemplate.php' );
    //include_once( 'kernel/common/eztemplatedesignresource.php' );
    //include_once( 'lib/ezutils/classes/ezextension.php' );

    $tpl = eZTemplate::instance();

    //include_once( 'lib/ezutils/classes/ezini.php' );
    $ini = eZINI::instance();
    if ( $ini->variable( 'TemplateSettings', 'Debug' ) == 'enabled' )
        eZTemplate::setIsDebugEnabled( true );

    $compatAutoLoadPath = $ini->variableArray( 'TemplateSettings', 'AutoloadPath' );
    $autoLoadPathList = $ini->variable( 'TemplateSettings', 'AutoloadPathList' );

    $extensionAutoloadPath = $ini->variable( 'TemplateSettings', 'ExtensionAutoloadPath' );
    $extensionPathList = eZExtension::expandedPathList( $extensionAutoloadPath, 'autoloads' );

    $autoLoadPathList = array_unique( array_merge( $compatAutoLoadPath, $autoLoadPathList, $extensionPathList ) );

    $tpl->setAutoloadPathList( $autoLoadPathList );
    $tpl->autoload();

    $tpl->registerResource( eZTemplateDesignResource::instance() );

    if ( $name === false )
    {
        $GLOBALS['eZPublishTemplate'] = $tpl;
    }
    else
    {
        $GLOBALS["eZPublishTemplate_$name"] = $tpl;
    }

    return $tpl;
}


?>
