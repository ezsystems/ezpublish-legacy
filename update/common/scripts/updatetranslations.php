#!/usr/bin/env php
<?php
//
// Created on: <23-Sep-2003 15:47:14 amos>
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

/*! \file updatexmltext.php
*/

set_time_limit( 0 );

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$endl = $cli->endlineString();

$script =& eZScript::instance( array( 'description' => ( "eZ publish update of translations.\n\n".
                                                         "Will go over objects and reinitialize attributes for missing translations" .
                                                         "\n" .
                                                         "updatetranslations.php" ),
                                      'use-session' => true,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[sql]",
                                "",
                                array( 'sql' => "Display sql queries"
                                       ) );
$script->initialize();

$showSQL = $options['sql'] ? true : false;
$siteAccess = $options['siteaccess'] ? $options['siteaccess'] : false;

if ( $siteAccess )
{
    changeSiteAccessSetting( $siteaccess, $siteAccess );
}

function changeSiteAccessSetting( &$siteaccess, $optionData )
{
    global $isQuiet;
    $cli =& eZCLI::instance();
    if ( file_exists( 'settings/siteaccess/' . $optionData ) )
    {
        $siteaccess = $optionData;
        if ( !$isQuiet )
            $cli->notice( "Using siteaccess $siteaccess for translation update" );
    }
    else
    {
        if ( !$isQuiet )
            $cli->notice( "Siteaccess $optionData does not exist, using default siteaccess" );
    }
}

include_once( 'kernel/classes/ezcontentclassattribute.php' );
include_once( 'kernel/classes/ezcontentobjectattribute.php' );
include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezbinaryfilehandler.php' );
include_once( 'kernel/classes/datatypes/ezbinaryfile/ezbinaryfile.php' );

include_once( 'lib/ezdb/classes/ezdb.php' );

$db =& eZDB::instance();
$db->setIsSQLOutputEnabled( $showSQL );

$attributeList =& eZContentClassAttribute::fetchList( true, array( 'version' => 0 ) );

$classAttributeIDList = array();
$classDataTypeList = array();

$complexTypes = array();

$objectCount = eZContentObject::fetchListCount();

$cli->output( "Going to update " . $objectCount . " objects" );

$maxFetch = 30;
$index = 0;
$column = 0;

while ( $index < $objectCount )
{
    $objectList =& eZContentObject::fetchList( true, null, $index, $maxFetch );
    foreach ( array_keys( $objectList ) as $objectKey )
    {
        $object =& $objectList[$objectKey];
        $versions =& $object->versions();
        $updated = false;
        $allFixed = true;
        foreach ( array_keys( $versions ) as $versionKey )
        {
            $version =& $versions[$versionKey];
            $translations = $version->translationList( false, false );
            foreach ( $translations as $languageCode )
            {
                $attributes =& $version->contentObjectAttributes( $languageCode );
                foreach ( array_keys( $attributes ) as $attributeKey )
                {
                    $attribute =& $attributes[$attributeKey];
                    $dataType =& $attribute->dataType();
                    $status = $dataType->repairContentObjectAttribute( $attribute );
                    if ( $status === true )
                    {
                        $updated = true;
                        $attribute->store();
                    }
                    else if ( $status === false )
                    {
                        $updated = true;
                        $allFixed = false;
                    }
                }
            }
        }
        if ( $updated )
        {
            if ( $allFixed )
                $cli->output( '*', false );
            else
                $cli->output( '#', false );
        }
        else
        {
            $cli->output( '.', false );
        }
        flush();
        ++$index;
        ++$column;
        if ( $column > 70 or $index == $objectCount )
        {
            $percent = ( $index * 100 ) / ( $objectCount );
            $percentText = number_format( $percent, 2 );
            $cli->output( " $percentText%" );
            $column = 0;
        }
    }
}

$script->shutdown();

?>
