#!/usr/bin/env php
<?php
//
// Created on: <22-Aug-2006 12:05:27 ks>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ systems AS
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

// gives more information about changed xml (unless in quite mode)
$extraVerbosOutput = false;

if( !file_exists( 'update/common/scripts' ) || !is_dir( 'update/common/scripts' ) )
{
    echo "Please run this script from the root document directory!\n";
    exit;
}

require 'autoload.php';

$cli = eZCLI::instance();

$script = eZScript::instance( array( 'description' => "\nThis script performs tasks needed to upgrade to 4.1:\n" .
                                                       "\n- Converting custom:align attributes to align attiribute on supported tags" .
                                                       "\n- Setting missing align attribute on <embed> and <embed-inline> to align=right." .
                                                       "\nYou can optionally perform only some of these tasks.",
                                      'use-session' => false,
                                      'use-modules' => false,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[db-host:][db-user:][db-password:][db-database:][db-type:][skip-embed-align][skip-custom-align][custom-align-attribute]",
                                '',
                                array( 'db-host' => "Database host",
                                       'db-user' => "Database user",
                                       'db-password' => "Database password",
                                       'db-database' => "Database name",
                                       'db-type' => "Database type, e.g. mysql or postgresql",
                                       'skip-embed-align' => "Skip adding align='right' on <embed> and <embed-inline> on tags that don't have these.",
                                       'skip-custom-align' => "Skip converting custom:align attribute to align attribute on supported tags.",
                                       'custom-align-attribute' => "Lets you specify name of custom:align attribute, default is 'align'."
                                       ) );
$script->initialize();

$dbUser = $options['db-user'];
$dbPassword = $options['db-password'];
$dbHost = $options['db-host'];
$dbName = $options['db-database'];
$dbImpl = $options['db-type'];
$customAlignAttribute = 'align';

$skipEmbedAlign = $options['skip-embed-align'];
$skipCustomAlign = $options['skip-custom-align'];

if ( $options['custom-align-attribute'] )
    $customAlignAttribute = $options['custom-align-attribute'];

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

    $db = eZDB::instance( $dbImpl, $params, true );
    eZDB::setInstance( $db );
}
else
{
    $db = eZDB::instance();
}

if ( !$db->isConnected() )
{
    $cli->error( "Can't initialize database connection.\n" );
    $script->shutdown( 1 );
}

// add align=right attribute on embed and embed-inline tags that has default value
function convertEmbedAlign( $doc, &$modificationList )
{
    $embedNodes = $doc->getElementsByTagName( 'embed' );
    foreach ( $embedNodes as $embedNode )
    {
        if ( !$embedNode->hasAttribute( 'align' ) )
        {
            $embedNode->setAttribute( 'align', 'right' );
            $modificationList[] = 'Added align value on embed tag';
        }
    }

    $embedInlineNodes = $doc->getElementsByTagName( 'embed-inline' );
    foreach ( $embedInlineNodes as $embedNode )
    {
        if ( !$embedNode->hasAttribute( 'align' ) )
        {
            $embedNode->setAttribute( 'align', 'right' );
            $modificationList[] = 'Added align value on embed-inline';
        }
    }
}

// move custom align attributes to proper align attribute on supported tags
function convertCustomAlign( $doc, $xmlString, $customAlignAttribute, &$customAlignTagList, &$modificationList )
{
    $attribute = 'custom:' . $customAlignAttribute;

    // return if xml doesn't contain custom tag
    if ( strpos( $xmlString, $attribute ) === false )
        return false;

    $schema = eZXMLSchema::instance();
    foreach( $schema->Schema as $customAlignTag => $customAlignTagSchema )
    {
        // continue if current tag does not contain align attribute
        if ( !isset( $customAlignTagSchema['attributes'] )
          or !is_array($customAlignTagSchema['attributes'])
          or !in_array( 'align', $customAlignTagSchema['attributes'] ))
            continue;

        $customNodes = $doc->getElementsByTagName( $customAlignTag );
        foreach ( $customNodes as $customNode )
        {
            // MAke sure this is a tag that supports align
            if ( $customNode->hasAttribute( $attribute ) )
            {
                $customNode->setAttribute( 'align', $customNode->getAttribute( $attribute ) );
                $customNode->removeAttribute( $attribute );
                $modificationList[] = 'Converting ' . $attribute . ' to align on <' . $customNode->nodeName . '> tag.';
                if ( $customNode->nodeName === 'custom' )
                    $customAlignTagList[] = $customNode->getAttribute( 'name' ) . '(custom)';
                else
                    $customAlignTagList[] = $customNode->nodeName;
            }
        }
    }
}

$totalAttrCount = 0;
$xmlFieldsQuery = "SELECT id, version, contentobject_id, data_text
                   FROM ezcontentobject_attribute
                   WHERE data_type_string = 'ezxmltext'";

$xmlFieldsArray = $db->arrayQuery( $xmlFieldsQuery, array( "limit" => QUERY_LIMIT ) );
if ( !is_array( $xmlFieldsArray ) )
{
    $cli->error( "SQL query error: $xmlFieldsQuery" );
    $script->shutdown( 1 );
}

// We process the table by parts of QUERY_LIMIT number of records, $pass is the iteration number.
$pass = 0;
$customAlignTagList = array();

while( count( $xmlFieldsArray ) )
{
    if ( !$isQuiet )
    {
        $fromNumber = $pass * QUERY_LIMIT;
        $toNumber = $fromNumber + count( $xmlFieldsArray );
        $cli->notice( "Processing records #$fromNumber-$toNumber ..." );
    }

    foreach ( $xmlFieldsArray as $xmlField )
    {
        $xmlString = $xmlField['data_text'];
        if ( trim( $xmlString ) === ''  )
            continue;

        $doc = new DOMDocument( '1.0', 'utf-8' );
        $success = $doc->loadXML( $xmlString );

        if ( $success )
        {
            $modificationList = array();

            if ( !$skipEmbedAlign )
                convertEmbedAlign( $doc, $modificationList );

            if ( !$skipCustomAlign )
                convertCustomAlign( $doc, $xmlString, $customAlignAttribute, $customAlignTagList, $modificationList );

            if ( $modificationList )
            {
                $xmlText = eZXMLTextType::domString( $doc );
                $xmlText = $db->escapeString( $xmlText );
                $sql = "UPDATE ezcontentobject_attribute SET data_text='" . $xmlText .
                   "' WHERE id=" . $xmlField['id'] . " AND version=" . $xmlField['version'];
                $db->query( $sql );

                if ( !$isQuiet )
                {
                    if ( $extraVerbosOutput )
                        $cli->notice( 'Tag(s) have been converted on object id: ' . $xmlField['contentobject_id'] . ', version: '. $xmlField['version'] .
                                  ', attribute id:' . $xmlField['id'] . ', changes: ' . var_export( $modificationList, true ) );
                    else
                        $cli->notice( 'Tag(s) have been converted on object id: ' . $xmlField['contentobject_id'] . ', version: '. $xmlField['version'] .
                                  ', attribute id:' . $xmlField['id'] );
                }
                $totalAttrCount++;
            }
        }
        unset( $doc );
    }

    $pass++;
    $xmlFieldsArray = $db->arrayQuery( $xmlFieldsQuery, array( "limit" => QUERY_LIMIT, "offset" => $pass * QUERY_LIMIT ) );
    if ( !is_array( $xmlFieldsArray ) )
    {
        $cli->error( "SQL query error: $xmlFieldsQuery" );
        $script->shutdown( 1 );
    }
}

if ( !$isQuiet )
{
    if ( $totalAttrCount )
        $cli->notice( "\nTotal: " . $totalAttrCount . " attribute(s) have been converted." );
    
    else
        $cli->notice( "\nXML text blocks: OK" );

    if ( $customAlignTagList )
    {
        $customAlignTagList = array_unique( $customAlignTagList, SORT_STRING );
        $cli->notice( "\nNOTICE: You now need to remove custom '$customAlignAttribute' attribute from the following tags in content.ini: " . implode(', ', $customAlignTagList) );
    }
}

$cli->notice( "\nDone." );

$script->shutdown();

?>
