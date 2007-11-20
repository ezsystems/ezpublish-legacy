#!/usr/bin/env php
<?php
//
// Created on: <23-Sep-2003 15:47:14 amos>
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

/*! \file updatetranslations.php
*/

set_time_limit( 0 );

//include_once( 'lib/ezutils/classes/ezcli.php' );
//include_once( 'kernel/classes/ezscript.php' );

$cli = eZCLI::instance();
$endl = $cli->endlineString();

$script = eZScript::instance( array( 'description' => ( "eZ Publish update of translations.\n\n".
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
    $cli = eZCLI::instance();
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

//include_once( 'kernel/classes/ezcontentclassattribute.php' );
//include_once( 'kernel/classes/ezcontentobjectattribute.php' );
//include_once( 'kernel/classes/ezcontentobject.php' );
//include_once( 'kernel/classes/ezbinaryfilehandler.php' );
//include_once( 'kernel/classes/datatypes/ezbinaryfile/ezbinaryfile.php' );

//include_once( 'lib/ezdb/classes/ezdb.php' );

$db = eZDB::instance();
$db->setIsSQLOutputEnabled( $showSQL );

$attributeList = eZContentClassAttribute::fetchList( true, array( 'version' => 0 ) );

$classAttributeIDList = array();
$classDataTypeList = array();

$complexTypes = array();

$objectCount = eZContentObject::fetchListCount();

$cli->output( "Going to update " . $cli->stylize( 'emphasize', $objectCount ) . " objects" );

$maxFetch = 30;
$index = 0;
$column = 0;

$script->setIterationData( '.', '~', array( '.', '~', '*', '#' ), false );
$script->resetIteration( $objectCount );

while ( $index < $objectCount )
{
    $objectList = eZContentObject::fetchList( true, null, $index, $maxFetch );
    foreach ( $objectList as $object )
    {
        $currentVersion = $object->currentVersion();

        if ( !is_object( $currentVersion ) )
        {
            $script->iterate( $cli, 0, "Object " . $object->attribute( 'id' ) . " does not have a current version, skipping" );
            ++$index;
            continue;
        }

        $versions = $object->versions();
        $classAttributes = eZContentClassAttribute::fetchListByClassID( $object->attribute( 'contentclass_id' ) );

        $updated = false;
        $allFixed = true;
        foreach ( $versions as $version )
        {
            $translations = $version->translationList( false, false );
            foreach ( $translations as $languageCode )
            {
                $attributes = $version->contentObjectAttributes( $languageCode );

                foreach ( $classAttributes as $classAttribute )
                {
                    $attributeExist = false;
                    $classAttributeID = $classAttribute->attribute( 'id' );

                    //check to see if this attribute is among the attributes for the translation
                    foreach ( $attributes as $attribute )
                    {
                        $attributeID = $attribute->attribute( "contentclassattribute_id" );
                        if ( $attributeID == $classAttributeID )
                        {
                            $attributeExist = true;
                        }
                    }

                    //if not, we create the missing attributes
                    if ( !$attributeExist )
                    {
                        $versionNumber = $version->attribute( 'version' );

                        //this initilizes the new translated attribute with values from the original attribute
                        //uncomment to create 'empty' attributes instead (also change the initialize command below)

                        $orgAttributes = $version->contentObjectAttributes();
                        foreach ( $orgAttributes as $orgAttribute )
                        {
                            if ( $classAttributeID == $orgAttribute->attribute( 'contentclassattribute_id' ) )
                                break;
                            $orgAttribute = null;
                        }

                        if ( !$classAttribute->attribute( 'can_translate' ) )
                            $orgAttribute = null;

                        $objectAttribute = eZContentObjectAttribute::create( $classAttributeID, $object->attribute( 'id' ), $versionNumber );
                        $objectAttribute->setAttribute( 'language_code', $languageCode );
                        $objectAttribute->initialize( $versionNumber, $orgAttribute );
                        $objectAttribute->store();
                    }
                }

                foreach ( $attributes as $attribute )
                {
                    $dataType = $attribute->dataType();
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
            $script->iterate( $cli, $allFixed ? 2 : 3, "Object " . $object->attribute( 'id' ) . " was updated" );
        }
        else
        {
            $script->iterate( $cli, 1, "Object " . $object->attribute( 'id' ) . " did not require an update" );
        }
        ++$index;
    }
}

$script->shutdown();

?>
