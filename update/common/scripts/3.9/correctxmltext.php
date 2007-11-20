#!/usr/bin/env php
<?php
//
// Created on: <22-Aug-2006 12:05:27 ks>
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

define( "QUERY_LIMIT", 100 );

if( !file_exists( 'update/common/scripts' ) || !is_dir( 'update/common/scripts' ) )
{
    echo "Please run this script from the root document directory!\n";
    exit;
}

//include_once( 'lib/ezutils/classes/ezcli.php' );
//include_once( 'kernel/classes/ezscript.php' );

$cli = eZCLI::instance();

$script = eZScript::instance( array( 'description' => "\nThis script performs tasks needed to upgrade to 3.9:\n" .
                                                       "\n- Converting <object> tags to <embed> tags" .
                                                       "\n- Adding existing 'class' attibute values to AvailableClasses arrays of content.ini" .
                                                       "\n- Adding existing custom attibutes to CustomAttributes arrays of content.ini\n" .
                                                       "\nYou can optionally perform only some of these tasks.",
                                      'use-session' => false,
                                      'use-modules' => false,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[db-host:][db-user:][db-password:][db-database:][db-type:][skip-objects][skip-classes][classes-dump-only][skip-custom][custom-dump-only][global]",
                                "",
                                array( 'db-host' => "Database host",
                                       'db-user' => "Database user",
                                       'db-password' => "Database password",
                                       'db-database' => "Database name",
                                       'db-type' => "Database type, e.g. mysql or postgresql",
                                       'skip-objects' => "Skip converting <object> tags.",
                                       'skip-classes' => "Skip checking and updating AvailableClasses settings of content.ini.",
                                       'classes-dump-only' => "Check available classes lists, but do not update content.ini. Results will be displayed in the output.",
                                       'skip-custom' => "Skip checking and updating CustomAttributes settings of content.ini.",
                                       'custom-dump-only' => "Check available custom attributes lists, but do not update content.ini. Results will be displayed in the output.",
                                       'global' => "Update global override content.ini.append instead of siteaccess"
                                       ) );
$script->initialize();

$dbUser = $options['db-user'];
$dbPassword = $options['db-password'];
$dbHost = $options['db-host'];
$dbName = $options['db-database'];
$dbImpl = $options['db-type'];

$skipClasses = $options['skip-classes'];
$classesDumpOnly = $options['classes-dump-only'];
$skipCustom = $options['skip-custom'];
$customDumpOnly = $options['custom-dump-only'];
$skipObjects = $options['skip-objects'];

$global = $options['global'];

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

//include_once( 'kernel/classes/datatypes/ezxmltext/ezxmltexttype.php' );

//include_once( 'lib/version.php' );

$eZPublishVersion = eZPublishSDK::majorVersion() + eZPublishSDK::minorVersion() * 0.1;
$siteaccess = $GLOBALS['eZCurrentAccess']['name'];

$domDocument = new DomDocument( '1.0' );

if ( !$skipClasses || !$skipCustom )
{
    $contentIni = eZINI::instance( 'content.ini' );
    if ( $global )
        $iniPath = "settings/override";
    else
        $iniPath = "settings/siteaccess/$siteaccess";
    $contentIniDirect = eZINI::instance( 'content.ini.append', $iniPath, null, null, null, true, true );

    //include_once( 'kernel/classes/datatypes/ezxmltext/ezxmlschema.php' );
    $XMLSchema = eZXMLSchema::instance();
}

// update AvailableClasses setting
function updateAvailableClasses( $doc, $element, &$isIniModified, &$contentIniDirect, $XMLSchema, $dumpOnly, $isQuiet = false )
{
    if ( $children = $element->childNodes )
    {
        foreach( $children as $child )
        {
            updateAvailableClasses( $doc, $child, $isIniModified, $contentIniDirect, $XMLSchema, $dumpOnly );
        }
    }
    if ( !( $element instanceof DOMElement ) )
    {
        return;
    }
    $class = $element->getAttribute( 'class' );
    if ( !$class )
        return;

    $classesList = $XMLSchema->getClassesList( $element->nodeName );
    if ( !in_array( $class, $classesList ) )
    {
        if ( !$isQuiet )
        {
            $cli = eZCLI::instance();
            if ( $dumpOnly )
                    $action = " is not defined.";
                else
                    $action = " is added to the list.";
            $cli->notice( "Element '$element->nodeName': class '$class'" . $action );
        }

        $XMLSchema->addAvailableClass( $element->nodeName, $class );
        if ( !$dumpOnly )
        {
            $classesList[] = $class;
            $contentIniDirect->setVariable( $element->nodeName, 'AvailableClasses', $classesList );
            $isIniModified = true;
        }
    }
}

// update CustomAttributes setting
function updateCustomAttributes( $doc, $element, &$isIniModified, &$contentIniDirect, $XMLSchema, $dumpOnly, $isQuiet = false )
{
    if ( $children = $element->childNodes )
    {
        foreach( $children as $child )
        {
            updateCustomAttributes( $doc, $child, $isIniModified, $contentIniDirect, $XMLSchema, $dumpOnly );
        }
    }

    if ( !$element->hasAttributes() )
        return;

    $customAttrs = $XMLSchema->customAttributes( $element );
    $attrs = $element->attributes;
    $newAttrFound = false;
    foreach( $attrs as $name => $attr )
    {
        if ( $attr->prefix == 'custom' &&
             !in_array( $name, $customAttrs ) )
        {
            $newAttrFound = true;
            $XMLSchema->addCustomAttribute( $element, $name );
            $customAttrs[] = $name;
            if ( !$isQuiet )
            {
                $cli = eZCLI::instance();
                if ( $dumpOnly )
                    $action = " is not defined.";
                else
                    $action = " is added to the list.";

                $cli->notice( "Element '$element->nodeName': custom attribute '$name'" . $action );
            }
        }
    }

    if ( $newAttrFound && !$dumpOnly )
    {
        if ( $element->nodeName == 'custom' )
        {
            $contentIniDirect->setVariable( $element->getAttribute( 'name' ), 'CustomAttributes', $customAttrs );
        }
        else
            $contentIniDirect->setVariable( $element->nodeName, 'CustomAttributes', $customAttrs );
        $isIniModified = true;
    }
}


function convertObjects( $doc, $element, &$isTextModified )
{
    if ( $children = $element->childNodes )
    {
        foreach( $children as $child )
        {
            convertObjects( $doc, $child, $isTextModified );
        }
    }

    // Convert 'objects' to 'embed' and 'embed-inline'
    if ( $element->nodeName == 'object' )
    {
        $objectID = $element->getAttribute( 'id' );
        $class = $element->getAttribute( 'class' );
        $size = $element->getAttribute( 'size' );
        $align = $element->getAttribute( 'align' );
        $view = $element->getAttribute( 'view' );

        $urlID = $element->getAttributeNS( 'http://ez.no/namespaces/ezpublish3/image/', 'ezurl_id' );
        $urlHref = $element->getAttributeNS( 'http://ez.no/namespaces/ezpublish3/image/', 'ezurl_href' );
        $urlTarget = $element->getAttributeNS( 'http://ez.no/namespaces/ezpublish3/image/', 'ezurl_target' );

        if ( $objectID )
        {
            $embed = $doc->createElement( 'embed' );
            $embed->setAttribute( 'object_id', $objectID );
            if ( $class )
                $embed->setAttribute( 'class', $class );
            if ( $size )
                $embed->setAttribute( 'size', $size );
            if ( $align )
                $embed->setAttribute( 'align', $align );
            if ( $view )
                $embed->setAttribute( 'view', $view );

            if ( $urlID || $urlHref )
            {
                $link = $doc->createElement( 'link' );
                if ( $urlID )
                    $link->setAttribute( 'url_id', $urlID );
                elseif ( $urlHref )
                {
                    //include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );
                    $urlHref = str_replace("&amp;", "&", $urlHref );
                    $urlID = eZURL::registerURL( $urlHref );
                    $link->setAttribute( 'url_id', $urlID );
                }
                if ( $urlTarget )
                    $link->setAttribute( 'target', $urlTarget );

                $link->appendChild( $embed );
                $element->parentNode->insertBefore( $link, $element );
            }
            else
            {
                $element->parentNode->insertBefore( $embed, $element );
            }
        }
        $element->parentNode->removeChild( $element );

        $isTextModified = true;
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
$pass = 0;

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
        $text = $xmlField['data_text'];
        if ( empty( $text ) )
        {
            continue;
        }
        $doc = DomDocument::loadXML( $text );

        if ( $doc )
        {
            $isTextModified = false;
            //fixParagraph( $doc, $doc->Root, $isTextModified );

            if ( !$skipObjects )
                convertObjects( $doc, $doc->documentElement, $isTextModified );

            if ( !$skipClasses )
                updateAvailableClasses( $doc, $doc->documentElement, $isIniModified, $contentIniDirect, $XMLSchema, $classesDumpOnly, $isQuiet );

            if ( !$skipCustom && $eZPublishVersion >= 3.9 )
                updateCustomAttributes( $doc, $doc->documentElement, $isIniModified, $contentIniDirect, $XMLSchema, $customDumpOnly, $isQuiet );

            if ( $isTextModified )
            {
                $result = $doc->saveXML();
                $sql = "UPDATE ezcontentobject_attribute SET data_text='" . $result .
                   "' WHERE id=" . $xmlField['id'] . " AND version=" . $xmlField['version'];
                $db->query( $sql );

                if ( !$isQuiet )
                    $cli->notice( "<object> tag(s) have been converted. Object ID: " . $xmlField['contentobject_id'] . ", version: ". $xmlField['version'] .
                                  ", attribute ID :" . $xmlField['id'] );
                $totalAttrCount++;
            }
        }
    }

    $pass++;
    $xmlFieldsArray = $db->arrayQuery( $xmlFieldsQuery, array( "limit" => QUERY_LIMIT, "offset" => $pass * QUERY_LIMIT ) );
    if ( !is_array( $xmlFieldsArray ) )
    {
        $cli->error( "SQL query error: $xmlFieldsQuery" );
        $script->shutdown( 1 );
    }
}

if ( $isIniModified )
{
    $saved = $contentIniDirect->save();
    if ( !$saved && !$isQuiet )
        $cli->error( "\nCan't save ini file: '$iniPath/content.ini.append(.php)' !" );
    elseif ( !$isQuiet )
        $cli->notice( "\nSettings file '$iniPath/content.ini.append' has been updated." );
}
elseif( ( !$skipClasses && !$classesDumpOnly ) || ( !$skipCustom && !$customDumpOnly ) )
{
    $cli->notice( "\ncontent.ini settings: OK" );
}

if ( !$skipObjects && !$isQuiet )
{
    if ( $totalAttrCount )
        $cli->notice( "\nTotal: " . $totalAttrCount . " attribute(s) have been converted." );
    else
        $cli->notice( "\nXML text blocks: OK" );
}

$cli->notice( "\nDone." );

$script->shutdown();

?>
