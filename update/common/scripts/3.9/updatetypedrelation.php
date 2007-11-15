#!/usr/bin/env php
<?php
//
// Created on: <06-Oct-2006 17:06:01 vp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

define( "QUERY_LIMIT", 100 );

if( !file_exists( 'update/common/scripts/3.9' ) || !is_dir( 'update/common/scripts/3.9' ) )
{
    echo "Please run this script from the root document directory!\n";
    exit;
}

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );
include_once( 'kernel/classes/ezcontentobject.php' );

$cli =& eZCLI::instance();

$script =& eZScript::instance( array( 'description' => ( "\nThis script performs the task needed to upgrade to 3.9:\n" .
                                                         "\nAdds 'embed' & 'link' contentobject relations.\n"  ),
                                      'use-session' => false,
                                      'use-modules' => false,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[db-host:][db-user:][db-password:][db-database:][db-type:]",
                                "",
                                array( 'db-host' => "Database host",
                                       'db-user' => "Database user",
                                       'db-password' => "Database password",
                                       'db-database' => "Database name",
                                       'db-type' => "Database type, e.g. mysql or postgresql"
                                       ) );
$script->initialize();

$dbUser = $options['db-user'];
$dbPassword = $options['db-password'];
$dbHost = $options['db-host'];
$dbName = $options['db-database'];
$dbImpl = $options['db-type'];
$isQuiet = $script->isQuiet();

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

include_once( 'lib/ezxml/classes/ezxml.php' );
include_once( 'kernel/classes/datatypes/ezxmltext/ezxmltexttype.php' );

$xml = new eZXML();


function AddObjectRelation( $fromObjectID, $fromObjectVersion, $toObjectID, $relationType )
{
    $db =& eZDB::instance();
    $relationBaseType = EZ_CONTENT_OBJECT_RELATION_COMMON |
                        EZ_CONTENT_OBJECT_RELATION_EMBED |
                        EZ_CONTENT_OBJECT_RELATION_LINK;
    $bitAndSQL = $db->bitAnd( 'relation_type', $relationBaseType );
    $query = "SELECT count(*) AS count
              FROM   ezcontentobject_link
              WHERE  from_contentobject_id=$fromObjectID AND
                     from_contentobject_version=$fromObjectVersion AND
                     to_contentobject_id=$toObjectID AND
                     ( $bitAndSQL ) != 0  AND
                     contentclassattribute_id=0 AND
                     op_code='0'";
    $count = $db->arrayQuery( $query );
    // if current relation does not exists
    if ( !isset( $count[0]['count'] ) ||  $count[0]['count'] == '0'  )
    {
        $db->query( "INSERT INTO ezcontentobject_link ( from_contentobject_id, from_contentobject_version, to_contentobject_id, relation_type )
                     VALUES ( $fromObjectID, $fromObjectVersion, $toObjectID, $relationType )" );
    }
    else
    {
        $bitOrSQL =  $db->bitOr( 'relation_type', $relationType );
        $db->query( "UPDATE ezcontentobject_link
                     SET    relation_type = ( $bitOrSQL )
                     WHERE  from_contentobject_id=$fromObjectID AND
                            from_contentobject_version=$fromObjectVersion AND
                            to_contentobject_id=$toObjectID AND
                            contentclassattribute_id=0 AND
                            op_code='0'" );
    }
}

function AddNewRelations( $objectID, $version, $relatedObjectIDArray, &$cli )
{
    $relationCount = 0;
    foreach ( $relatedObjectIDArray as $relationType => $relatedObjectIDSubArray )
    {
        foreach ( $relatedObjectIDSubArray as $relatedObjectID )
        {
            AddObjectRelation( $objectID, $version, $relatedObjectID, $relationType );
            $cli->notice( implode( '', array( 'Added ', ( ( EZ_CONTENT_OBJECT_RELATION_EMBED === $relationType ) ? 'embed' : 'link' ) , ' relation. ',
                                   'Object ID ', $objectID,
                                   '( ver. ', $version,
                                   ' ) => ID ', $relatedObjectID ) ) );
            $relationCount++;
        }
    }
    return $relationCount;
}


function getRelatedObjectsID( &$domDocument, $tagName, &$objectIDArray )
{
    $xmlNodeList = $domDocument->get_elements_by_tagname( $tagName );
    if ( !is_array( $xmlNodeList ) )
    {
        return;
    }

    foreach ( $xmlNodeList as $xmlNode )
    {
        $objectID = ( int ) $xmlNode->getAttribute( 'object_id' );

        if ( $objectID && !in_array( $objectID, $objectIDArray ) )
        {
           $objectIDArray[] = $objectID;
        }
    }
}


$totalRelationCount = 0;
$totalObjectCount = 0;

$xmlFieldsQuery = "SELECT id, version, contentobject_id, data_text
                   FROM ezcontentobject_attribute
                   WHERE data_type_string = 'ezxmltext'
                   ORDER BY contentobject_id ASC";

// We process the table by parts of QUERY_LIMIT number of records
$relationActionLog = array();
$relatedObjectIDArray = array();
$objectID = null;
$version = null;
$objectWasModified =  false;

for ( $offset = 0; ; $offset += QUERY_LIMIT )
{
    $xmlFieldsArray = $db->arrayQuery( $xmlFieldsQuery, array( "limit" => QUERY_LIMIT, "offset" => $offset ) );

    if ( count( $xmlFieldsArray ) <= 0 )
    {
        break;
    }

    if ( !$isQuiet )
    {
        $toNumber = $offset + count( $xmlFieldsArray );
        $cli->notice( "Processing records #$offset-$toNumber ..." );
    }

    foreach ( $xmlFieldsArray as $xmlField )
    {
        if ( $objectID == null || $version != $xmlField['version'] || $objectID != $xmlField['contentobject_id'] )
        {
            $relationAddedCount = AddNewRelations( $objectID, $version, $relatedObjectIDArray, $cli );
            if ( $relationAddedCount )
            {
                $totalRelationCount += $relationAddedCount;
                $objectWasModified =  true;
            }

            $version = ( int ) $xmlField['version'];
            $relatedObjectIDArray[EZ_CONTENT_OBJECT_RELATION_EMBED] = array();
            $relatedObjectIDArray[EZ_CONTENT_OBJECT_RELATION_LINK] = array();

            if ( $objectID == null || $objectID != $xmlField['contentobject_id'] )
            {
                $objectID = ( int ) $xmlField['contentobject_id'];

                if ( $objectWasModified )
                {
                    $objectWasModified =  false;
                    $totalObjectCount++;
                }
            }
        }

        $text = $xmlField['data_text'];
        $doc =& $xml->domTree( $text, array( "TrimWhiteSpace" => false ) );

        if ( $doc )
        {
            getRelatedObjectsID( $doc, 'embed', $relatedObjectIDArray[EZ_CONTENT_OBJECT_RELATION_EMBED] );
            getRelatedObjectsID( $doc, 'link', $relatedObjectIDArray[EZ_CONTENT_OBJECT_RELATION_LINK] );
        }
    }
}

$relationAddedCount = AddNewRelations( $objectID, $version, $relatedObjectIDArray, $cli );
if ( $relationAddedCount )
{
    $totalRelationCount += $relationAddedCount;
    $objectWasModified =  true;
}

if ( !$isQuiet )
{
    if ( $totalRelationCount )
        $cli->notice( implode( '', array( 'Total: ', $totalRelationCount, ' relation(s) detected at ', $totalObjectCount, ' objects.' ) ) );
    else
        $cli->notice( 'Nothing to do.' );
}

$script->shutdown();

?>
