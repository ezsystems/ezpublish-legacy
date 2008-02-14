#!/usr/bin/env php
<?php
//
// Created on: <01-Feb-2005 12:05:27 ks>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
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

/*! \file convertxmllinks.php
  Database converter for eZ publish 3.6.
  Updates <link> tags in 'ezxmltext' type attributes: replaces 'id' attribute
  with 'url_id'.
  You should run this script before using database created with eZ publish
  version 3.5.* or lower.
*/

define( "QUERY_LIMIT", 100 );

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

$totalCount = 0;

/*!
  Finds all link tags in text \a $text and replaces the attribute \c id with \c url_id.
  \param $pos The current position to start looking for tags in \a $text.
  \param $isTextModified The global flag which tells if a link was modified or not
  \return \c true if it has found a link tag, \c false otherwise.
*/
function findLinkTags( &$text, &$pos, &$isTextModified )
{
    $linkPos = strpos( $text, "<link", $pos );
    if ( $linkPos )
    {
        $linkTagBegin = $linkPos + 5;
        $linkTagEnd = strpos( $text, ">", $linkTagBegin );
        if ( !$linkTagEnd )
        {
            return false;
        }

        $linkTag = substr( $text, $linkTagBegin, $linkTagEnd - $linkTagBegin );

        if ( strpos( $linkTag, " id=\"" ) !== false )
        {
            $linkTag = str_replace( " id=\"", " url_id=\"", $linkTag );
            $text = substr_replace( $text, $linkTag, $linkTagBegin, $linkTagEnd - $linkTagBegin );

            $isTextModified = true;
        }

        $pos = $linkTagEnd;
        return true;
    }
    return false;
}

$xmlFieldsQuery = "SELECT id, version, contentobject_id, data_text
                   FROM ezcontentobject_attribute
                   WHERE data_type_string = 'ezxmltext'";

$xmlFieldsArray = $db->arrayQuery( $xmlFieldsQuery, array( "limit" => QUERY_LIMIT ) );

// We process the table by parts of QUERY_LIMIT number of records, $pass is the iteration number.
$pass = 1;

while( count( $xmlFieldsArray ) )
{
    foreach ( $xmlFieldsArray as $xmlField )
    {
        $text = $xmlField['data_text'];
        $textLen = strlen ( $text );

        $isTextModified = false;
        $pos = 1;

        if ( $textLen == 0 )
        {
            continue;
        }

        $oldPos = false;
        do
        {
            // Avoid infinite loop
            if ( $oldPos === $pos or $oldPos > $pos )
            {
                break;
            }
            $oldPos = $pos;

            $literalTagBegin = strpos( $text, "<literal", $pos );
            if ( $literalTagBegin )
            {

                $preLiteralText = substr( $text, $pos, $literalTagBegin - $pos );
                $preLiteralLen = strlen( $preLiteralText );
                $tmpPos = 0;
                // We need to check the text before the literal tag for link tags
                if ( findLinkTags( $preLiteralText, $tmpPos, $isTextModified ) )
                {
                    // We found some link tags, now replace the text and adjust position
                    $diff = strlen( $preLiteralText ) - $preLiteralLen;
                    $text = substr_replace( $text, $preLiteralText, $pos, $literalTagBegin - $pos );

                    // Adjust begin position with the changes in text
                    $literalTagBegin += $diff;
                }

                $literalTagEnd = strpos( $text, "</literal>", $literalTagBegin );
                if ( !$literalTagEnd )
                {
                    break;
                }
                $pos = $literalTagEnd + 9;
            }
            else
            {
                if ( !findLinkTags( $text, $pos, $isTextModified ) )
                    break;
            }

        } while ( $pos < $textLen );

        if ( $isTextModified )
        {
            $sql = "UPDATE ezcontentobject_attribute SET data_text='" . $text .
               "' WHERE id=" . $xmlField['id'] . " AND version=" . $xmlField['version'];
            $db->query( $sql );

            if ( !$isQuiet )
                $cli->notice( "Attribute converted. Object ID: " . $xmlField['contentobject_id'] . ", version: ". $xmlField['version'] .
                              ", attribute ID :" . $xmlField['id'] );
            $totalCount++;
        }
    }
    $xmlFieldsArray = $db->arrayQuery( $xmlFieldsQuery, array( "limit" => QUERY_LIMIT, "offset" => $pass * QUERY_LIMIT ) );
    $pass++;
}

if ( !$isQuiet )
{
    if ( $totalCount )
        $cli->notice( "Total: " . $totalCount . " attribute(s) converted." );
    else
        $cli->notice( "No old-style <link> tags found." );
}

$script->shutdown();

?>
