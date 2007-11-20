#!/usr/bin/env php
<?php
//
// Created on: <30-Jul-2007 15:20:59 sp>
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

/*! \file updatemultioption.php
*/
//include_once( 'kernel/classes/ezpersistentobject.php' );
//include_once( 'kernel/classes/ezcontentobjectattribute.php' );

require 'autoload.php';



function updateAtttributes( $conditions )
{
    $limit = 1000;
    $offset = 0;
    if ( $offset or $limit )
        $limits = array( 'offset' => $offset,
                         'length' => $limit );

    while ( true )
    {
        $attributes = eZPersistentObject::fetchObjectList( eZContentObjectAttribute::definition(),
                                                           null,
                                                           $conditions, null, $limits );
        if ( count( $attributes ) < 1 )
            break;

        foreach ( $attributes as $attribute )
        {
            $classAttribute = $attribute->contentClassAttribute();
            if ( $classAttribute->attribute( 'data_type_string' ) == 'ezmultioption' )
            {
                $classAttribute->setAttribute( 'data_type_string', 'ezmultioption2' );
                $classAttribute->store();
            }

            $attribute->setAttribute( 'data_type_string', 'ezmultioption2' );
            $attribute->DataTypeString = 'ezmultioption2';
            $dataType = $attribute->dataType();
            $attributeContent = $attribute->attribute( 'content' );
            $attribute->setAttribute( "data_text", $attributeContent->xmlString() );
            $attribute->setContent( $attributeContent );
            $attribute->store();
        }
    }

}
if( !file_exists( 'update/common/scripts' ) || !is_dir( 'update/common/scripts' ) )
{
    echo "Please run this script from the root document directory!\n";
    exit;
}

//include_once( 'lib/ezutils/classes/ezcli.php' );
//include_once( 'kernel/classes/ezscript.php' );

$cli = eZCLI::instance();

$script = eZScript::instance( array( 'description' => ( "\nDatabase converter for eZ Publish 3.6.\n" .
                                                        "Converts old ezmultioption attributes to new ezmultioption2 datatype" ),
                                     'use-session' => false,
                                     'use-modules' => false,
                                     'use-extensions' => false ) );

$script->startup();

$options = $script->getOptions( "[db-host:][db-user:][db-password:][db-database:][db-type:][all-classes][contentclass:]",
                                "",
                                array( 'db-host' => "Database host",
                                       'db-user' => "Database user",
                                       'db-password' => "Database password",
                                       'db-database' => "Database name",
                                       'db-type' => "Database type, e.g. mysql or postgresql",
                                       'all-classes' => "Update all classes that contain attribute of multioption type",
                                       'contentclass' => "Update only objects of [contentclass] class"
                                       ) );
$script->initialize();

$updateAllClasses = $options['all-classes'] ? true : false;

$dbUser = $options['db-user'];
$dbPassword = $options['db-password'];
$dbHost = $options['db-host'];
$dbName = $options['db-database'];
$dbImpl = $options['db-type'];
$isQuiet = $script->isQuiet();
$contentclass = $options['contentclass'];

if ( $dbHost or $dbName or $dbUser or $dbImpl )
{
    $params = array( 'use_defaults' => false );
    if ( $dbHost !== false )
        $params['server'] = $dbHost;
    if ( $dbUser !== false )
    {
        $params['user'] = $dbUser;
        $params['password'] = '';
    }
    if ( $dbPassword !== false )
        $params['password'] = $dbPassword;
    if ( $dbName !== false )
        $params['database'] = $dbName;

    $db = eZDB::instance( $dbImpl, $params, true );
    eZDB::setInstance( $db );
}
else
{
    $db = eZDB::instance();
}

if ( !$db->isConnected() )
{
    $cli->notice( "Can't initialize database connection.\n" );
    $script->shutdown( 1 );
}
$db->setIsSQLOutputEnabled( true );






if ( $updateAllClasses )
{
    $conditions = array( "data_type_string" => 'ezmultioption' );
    $db->begin();
    updateAtttributes( $conditions );
    $db->commit();
}

if ( $contentclass != '' )
{
    $class = eZContentClass::fetchByIdentifier( $contentclass );
    foreach ( $class->attribute( 'data_map') as $classAttribute )
    {
        if ( $classAttribute->attribute( 'data_type_string' ) == 'ezmultioption' )
        {
            $db->begin();
            $conditions = array( "data_type_string" => 'ezmultioption', "contentclassattribute_id" => $classAttribute->attribute( 'id' )  );
            updateAtttributes( $conditions );
            $db->commit();
        }
    }
}

$script->shutdown();


?>
