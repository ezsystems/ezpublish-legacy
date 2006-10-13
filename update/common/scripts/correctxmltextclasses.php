#!/usr/bin/env php
<?php
//
// Created on: <12-Oct-2006 12:05:27 ks>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
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

if( !file_exists( 'update/common/scripts' ) || !is_dir( 'update/common/scripts' ) )
{
    echo "Please run this script from the root document directory!\n";
    exit;
}

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();

$script =& eZScript::instance( array( 'description' => "\nThis script adds existing 'class' attibute values to 'AvailableClasses' settings of content.ini or just display needed changes in the output.",
                                      'use-session' => false,
                                      'use-modules' => false,
                                      'use-extensions' => false ) );

$script->startup();

$options = $script->getOptions( "[db-host:][db-user:][db-password:][db-database:][db-type:][global][dump-only]",
                                "",
                                array( 'db-host' => "Database host",
                                       'db-user' => "Database user",
                                       'db-password' => "Database password",
                                       'db-database' => "Database name",
                                       'db-type' => "Database type, e.g. mysql or postgresql",
                                       'global' => "Update global override content.ini.append instead of siteaccess",
                                       'dump-only' => "Check available classes lists, but do not update content.ini. Results will be displayed in the output."
                                       ) );
$script->initialize();

$dbUser = $options['db-user'];
$dbPassword = $options['db-password'];
$dbHost = $options['db-host'];
$dbName = $options['db-database'];
$dbImpl = $options['db-type'];

$global = $options['global'];
$classesDumpOnly = $options['dump-only'];

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
    $cli->error( "Can't initialize database connection.\n" );
    $script->shutdown( 1 );
}

include_once( 'lib/ezxml/classes/ezxml.php' );
include_once( 'kernel/classes/datatypes/ezxmltext/ezxmltexttype.php' );

include_once( 'lib/version.php' );

$eZPublishVersion = eZPublishSDK::majorVersion() + eZPublishSDK::minorVersion() * 0.1;
$siteaccess = $GLOBALS['eZCurrentAccess']['name'];

$xml = new eZXML();

$contentIni =& eZINI::instance( 'content.ini' );

if ( $global )
    $iniPath = "settings/override";
else
    $iniPath = "settings/siteaccess/$siteaccess";

$contentIniDirect =& eZINI::instance( 'content.ini.append', $iniPath, null, null, null, true, true );

include_once( 'kernel/classes/datatypes/ezxmltext/ezxmlschema.php' );
$XMLSchema =& eZXMLSchema::instance();

$GLOBALS['eZAddedClassesList'] = array();

// update AvailableClasses setting
function updateAvailableClasses( &$doc, &$element, &$isIniModified, &$contentIniDirect, &$XMLSchema, $dumpOnly )
{
    $children =& $element->Children;
    foreach( array_keys( $children ) as $key )
    {
        $child =& $children[$key];
        updateAvailableClasses( $doc, $child, $isIniModified, $contentIniDirect, $XMLSchema, $dumpOnly );
    }
    
    $class = $element->getAttribute( 'class' );
    if ( !$class )
        return;


    $addedClassesList =& $GLOBALS['eZAddedClassesList'];

    $classesList = $XMLSchema->getClassesList( $element->nodeName );
    if ( isset( $addedClassesList[$element->nodeName] ) )
        $classesList = array_merge( $classesList, $addedClassesList[$element->nodeName] );

    if ( !in_array( $class, $classesList ) )
    {
        if ( !$isQuiet )
        {
            $cli =& eZCLI::instance();
            if ( $dumpOnly )
                    $action = " is not defined.";
                else
                    $action = " is added to the list.";
            $cli->notice( "Element '$element->nodeName': class '$class'" . $action );
        }

        //$XMLSchema->addAvailableClass( $element->nodeName, $class );
        if ( !isset( $addedClassesList[$element->nodeName] ) )
            $addedClassesList[$element->nodeName] = array();

        $addedClassesList[$element->nodeName][] = $class;

        if ( !$dumpOnly )
        {
            $classesList[] = $class;
            $contentIniDirect->setVariable( $element->nodeName, 'AvailableClasses', $classesList );
            $isIniModified = true;
        }
    }
}

// Replacement for eZDOMNode::cleanup() for eZ publish versions <= 3.8.4 compatibility:
function cleanup( &$node )
{
    if ( $node->hasChildren() )
    {
        foreach( array_keys( $node->Children ) as $key )
        {
            $child =& $node->Children[$key];
            if ( $child->hasChildren() )
            {
                cleanup( $child );
            }
        }
        $node->removeChildren();
    }
}


$isIniModified = false;
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
$pass = 1;

while( count( $xmlFieldsArray ) )
{
    foreach ( $xmlFieldsArray as $xmlField )
    {
        $text = $xmlField['data_text'];
        $doc =& $xml->domTree( $text, array( "TrimWhiteSpace" => false ) );

        if ( $doc )
        {
            $isTextModified = false;
            //fixParagraph( $doc, $doc->Root, $isTextModified );

            updateAvailableClasses( $doc, $doc->Root, $isIniModified, $contentIniDirect, $XMLSchema, $classesDumpOnly );

            //
            //$doc->cleanup();
            //
            // Replacement for cleanup() for previous versions compatibility:
            cleanup( $doc->Root );
        }
        unset( $doc );
    }    
    $xmlFieldsArray = $db->arrayQuery( $xmlFieldsQuery, array( "limit" => QUERY_LIMIT, "offset" => $pass * QUERY_LIMIT ) );
    if ( !is_array( $xmlFieldsArray ) )
    {
        $cli->error( "SQL query error: $xmlFieldsQuery" );
        $script->shutdown( 1 );
    }
    $pass++;
}

if ( $isIniModified )
{
    $saved = $contentIniDirect->save();
    if ( !$saved && !$isQuiet )
        $cli->error( "\nCan't save ini file: $iniPath/content.ini.append(.php) !" );
    elseif ( !$isQuiet )
        $cli->notice( "\nSettings file $iniPath/content.ini.append has been updated." );
}
elseif ( !$classesDumpOnly )
{
    $cli->notice( "\ncontent.ini settings: OK" );
}

$cli->notice( "\nDone." );

$script->shutdown();

?>
