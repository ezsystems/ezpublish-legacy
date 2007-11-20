#!/usr/bin/env php
<?php
//
// Created on: <31-Mar-2005 12:24:01 ks>
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

/*! \file convertxmllinks.php
  Database converter for eZ Publish 3.6.
  Don't forget to update database structute before running this script.

  Updates 'ezcontentobject_link' table by adding related objects links made with
  attributes of 'ezobjectrelation' and 'ezobjectrelationlist' types.

  You should run this script before using a database created with eZ Publish
  version 3.5.* or lower.
*/

define( "QUERY_LIMIT", 100 );

if( !file_exists( 'update/common/scripts' ) || !is_dir( 'update/common/scripts' ) )
{
    echo "Please run this script from the root document directory!\n";
    exit;
}

//include_once( 'lib/ezutils/classes/ezcli.php' );
//include_once( 'kernel/classes/ezscript.php' );

$cli = eZCLI::instance();

$script = eZScript::instance( array( 'description' => ( "\nDatabase converter for eZ Publish 3.6.\n" .
                                                        "Updates 'ezcontentobject_link' table by adding related objects links made with\n" .
                                                        "attributes of 'ezobjectrelation' and 'ezobjectrelationlist' types.\n\n" .
                                                        "Run this script before using a database created with eZ Publish version 3.5.* or lower.\n" .
                                                        "Don't forget to update database's structure before running this script." ),
                                     'use-session' => false,
                                     'use-modules' => false,
                                     'use-extensions' => false ) );

$script->startup();

$options = $script->getOptions( "[db-host:][db-user:][db-password:][db-database:][db-driver:]",
                                "",
                                array( 'db-host' => "Database host",
                                       'db-user' => "Database user",
                                       'db-password' => "Database password",
                                       'db-database' => "Database name",
                                       'db-driver' => "Database driver"
                                       ) );
$script->initialize();

$dbUser = $options['db-user'] ? $options['db-user'] : false;
$dbPassword = $options['db-password'] ? $options['db-password'] : false;
$dbHost = $options['db-host'] ? $options['db-host'] : false;
$dbName = $options['db-database'] ? $options['db-database'] : false;
$dbImpl = $options['db-driver'] ? $options['db-driver'] : false;
$siteAccess = $options['siteaccess'] ? $options['siteaccess'] : false;
$isQuiet = $script->isQuiet();

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
            $cli->notice( "Using siteaccess $siteaccess." );
    }
    else
    {
        if ( !$isQuiet )
            $cli->notice( "Siteaccess $optionData does not exist, using default siteaccess" );
    }
}

if ( $dbHost or $dbName or $dbUser or $dbImpl )
{
    $params = array();
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
    if ( !$db )
    {
        $cli->notice( "Can't initialize database connection.\n" );
        $script->shutdown( 1 );
    }
    eZDB::setInstance( $db );
}
else
{
    $db = eZDB::instance();
    if ( !$db )
    {
        $cli->notice( "Can't initialize database connection.\n" );
        $script->shutdown( 1 );
    }
}

//include_once( 'kernel/classes/ezcontentobjectattribute.php' );
//include_once( 'lib/ezxml/classes/ezxml.php' );

$links = array();
$added_links_count = 0;
$links_total = 0;

function updateLinks()
{
    global $added_links_count, $links, $db, $isQuiet, $cli;

    foreach( $links as $link )
    {
        $fromObjectID = $link['fromObjectID'];
        $fromObjectVersion = $link['fromObjectVersion'];
        $toObjectID = $link['toObjectID'];
        $attributeID = $link['attributeID'];

        $result = $db->arrayQuery( "SELECT ( COUNT( ezcontentobject_link.id ) ) AS count
                               FROM ezcontentobject_link
                               WHERE from_contentobject_id=$fromObjectID AND
                                     from_contentobject_version=$fromObjectVersion AND
                                     to_contentobject_id=$toObjectID AND
                                     contentclassattribute_id=$attributeID" );

        $count = $result[0]['count'];
        if ( !$count )
        {
            $db->query( "INSERT INTO ezcontentobject_link ( from_contentobject_id, from_contentobject_version, to_contentobject_id, contentclassattribute_id )
                         VALUES ( $fromObjectID, $fromObjectVersion, $toObjectID, $attributeID )" );
            $added_links_count++;
            if ( !$isQuiet )
                $cli->notice( "Added link from object $fromObjectID, version $fromObjectVersion with attribute $attributeID to object $toObjectID" );
        }
    }
}

$query = "SELECT attr.*
                 FROM ezcontentobject_attribute attr
                 WHERE attr.data_type_string='ezobjectrelation'";

$result = $db->arrayQuery( $query, array( "limit" => QUERY_LIMIT ) );
$pass = 1;

while( count( $result ) )
{
    $links = array();

    foreach( $result as $row )
    {
        $attr = new eZContentObjectAttribute( $row );
        if ( $attr->attribute( 'data_int' ) )
        {
            $links[] = array( 'fromObjectID' => $attr->attribute( 'contentobject_id' ),
                              'fromObjectVersion' => $attr->attribute( 'version' ),
                              'toObjectID' => $attr->attribute( 'data_int' ),
                              'attributeID' => $attr->attribute( 'contentclassattribute_id' ) );
        }
    }
    if ( count( $links ) )
    {
        $links_total += count( $links );
        updateLinks();
    }

    $result = $db->arrayQuery( $query, array( "limit" => QUERY_LIMIT, "offset" => $pass * QUERY_LIMIT ) );
    $pass++;
}

$query = "SELECT attr.*
          FROM ezcontentobject_attribute attr
          WHERE attr.data_type_string='ezobjectrelationlist'";

$result = $db->arrayQuery( $query, array( "limit" => QUERY_LIMIT ) );
$pass = 1;

while( count( $result ) )
{
    $links = array();

    foreach( $result as $row )
    {
        $attr = new eZContentObjectAttribute( $row );
        if ( $attr->attribute( 'data_text' ) )
        {
            $xml = new eZXML();
            $dom = $xml->domTree( $attr->attribute( 'data_text' ) );

            $root =& $dom->root();
            $relationList =& $root->elementByName( 'relation-list' );
            if ( $relationList )
            {
                $relationItems = $relationList->elementsByName( 'relation-item' );
                if ( count( $relationItems ) )
                {
                    foreach( $relationItems as $relationItem )
                    {
                        $links[] = array( 'fromObjectID' => $attr->attribute( 'contentobject_id' ),
                                          'fromObjectVersion' => $attr->attribute( 'version' ),
                                          'toObjectID' => $relationItem->attributeValue( 'contentobject-id' ),
                                          'attributeID' => $attr->attribute( 'contentclassattribute_id' ) );
                    }
                }
            }
        }
    }
    if ( count( $links ) )
    {
        $links_total += count( $links );
        updateLinks();
    }

    $result = $db->arrayQuery( $query, array( "limit" => QUERY_LIMIT, "offset" => $pass * QUERY_LIMIT ) );
    $pass++;
}

if ( !$isQuiet )
    $cli->notice( "\nFound $links_total references to related objects in attributes." );

if ( !$isQuiet )
    if ( $added_links_count )
        $cli->notice( "$added_links_count links were added to 'ezcontentobject_link' table.\n" );
    else
        $cli->notice( "No links were added.\n" );

$script->shutdown();
?>
