<?php
//
// Created on: <30-Jul-2007 15:20:59 sp>
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

/*! \file updatemultioption.php
*/
include_once( 'kernel/classes/ezpersistentobject.php' );
include_once( 'kernel/classes/ezcontentobjectattribute.php' );
define( "QUERY_LIMIT", 100 );




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
            $classAttribute =& $attribute->contentClassAttribute();
            if ( $classAttribute->attribute( 'data_type_string' ) == 'ezmultioption' )
            {
                $classAttribute->setAttribute( 'data_type_string', 'ezmultioptiongroup' );
                $classAttribute->store();
            }

            $attribute->setAttribute( 'data_type_string', 'ezmultioptiongroup' );
            $attribute->DataTypeString = 'ezmultioptiongroup';
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

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();

$script =& eZScript::instance( array( 'description' => ( "\nDatabase converter for eZ publish 3.6.\n" .
                                                         "Updates <link> tags in 'ezxmltext' type attributes.\n" .
                                                         "Run this script before using database created with eZ publish version 3.5.* or lower." ),
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

    $db =& eZDB::instance( $dbImpl, $params, true );
    eZDB::setInstance( $db );
}
else
{
    $db =& eZDB::instance();
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
