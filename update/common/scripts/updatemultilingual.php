#!/usr/bin/env php
<?php
//
// Created on: <22-Mar-2006 09:23:12 jk>
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

/* 

-- The following SQLs must be run before running this script:

CREATE TABLE ezcontent_language
(
    id int(11) NOT NULL default '0',
    disabled int(11) NOT NULL default '0',
    locale varchar(20) NOT NULL default '',
    name varchar(255) NOT NULL default '',
    PRIMARY KEY (id)
);

DROP TABLE ezcontent_translation;

ALTER TABLE ezcontentobject ADD COLUMN language_mask int NOT NULL DEFAULT 0;
ALTER TABLE ezcontentobject ADD COLUMN initial_language_id int NOT NULL DEFAULT 0;

ALTER TABLE ezcontentobject_name ADD COLUMN language_id int NOT NULL DEFAULT 0;

ALTER TABLE ezcontentobject_attribute ADD COLUMN language_id int NOT NULL DEFAULT 0;

ALTER TABLE ezcontentobject_version ADD COLUMN language_mask int NOT NULL DEFAULT 0;
ALTER TABLE ezcontentobject_version ADD COLUMN initial_language_id int NOT NULL DEFAULT 0;

ALTER TABLE ezcontentclass ADD COLUMN always_available int NOT NULL DEFAULT 0;

ALTER TABLE ezcontentobject_link ADD COLUMN op_code int NOT NULL DEFAULT 0;

ALTER TABLE eznode_assignment ADD COLUMN op_code int NOT NULL DEFAULT 0;

*/

// TODO: check if the sql update script was already run...

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );
include_once( 'kernel/classes/ezcontentlanguage.php' );

function minBit( $value )
{
    $value = (int) $value;
    $minBit = 1;

    while ( $value > 0 )
    {
        if ( $value & 1 )
        {
            return $minBit;
        }
        $minBit *= 2;
        $value = (int) ( $value / 2 );
    }

    return 0;
}

set_time_limit( 0 );

$cli =& eZCLI::instance();

$script =& eZScript::instance( array( 'description' => "Update database for the multilingual suport.",
                                      'use-session' => true,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "",
                                "" );

if ( !$options['siteaccess'] || !file_exists( 'settings/siteaccess/' . $options['siteaccess'] ) )
{
    $cli->error( "Siteaccess was not given or does not exist. Exiting..." );
    exit( -1 );
}

$script->setUseSiteAccess( $options['siteaccess'] );

$script->initialize();

$cli->warning( "Have you backed up your database? If not, press Ctrl-C and back up your data!" );

$db =& eZDB::instance();
$ini =& eZINI::instance();

$draftCount = $db->arrayQuery( "SELECT count(*) AS count
                                FROM ezcontentobject_version
                                WHERE status=0" );
$draftCount = $draftCount[0]['count'];

if ( $draftCount )
{
    $cli->error( "You have $draftCount draft(s). You have either to publish or to remove them. Exiting..." );
    $script->shutdown();
    exit( -1 );
}

$defaultLanguageCode = $ini->variable( 'RegionalSettings', 'ContentObjectLocale' );

$cli->notice( "The default language for your siteaccess is '$defaultLanguageCode' and will be used as the initial language for all your objects." );
$cli->notice( "The script will use the following database settings:" );
$cli->notice( "User: <" . $db->User . ">, " . ( strlen( $db->Password ) > 0 ? "Password: <***>, " : "Password: none, " ) . "Server: <" . $db->Server . ">, Socket: <" . $db->SocketPath . ">, Name: <" . $db->DB . ">, Charset: <" . $db->Charset . ">" );
$cli->warning( "You have now 10 seconds to break this script (press Ctrl-C) if the setting is incorrect." );
sleep( 10 );

$cli->notice( 'Step 1/5: Identifying languages used on the site:' );

$language = eZContentLanguage::addLanguage( $defaultLanguageCode );
$defaultLanguage = $language->attribute( 'id' );
$cli->notice( "  language: $defaultLanguageCode (id $defaultLanguage)" );
$languages = array( $defaultLanguageCode => $defaultLanguage );

$rows = $db->arrayQuery( "SELECT DISTINCT content_translation FROM ezcontentobject_name" );
foreach ( $rows as $row )
{
    $languageCode = $row['content_translation'];
    if ( $languageCode != $defaultLanguageCode )
    {
        $language = eZContentLanguage::addLanguage( $languageCode );
        if ( !$language )
        {
            $cli->error( "Cannot add language $languageCode! Too many languages used on the site?" );
            exit( -1 );
        }
        $languageID = $language->attribute( 'id' );
        $cli->notice( "  language: $languageCode (id $languageID)" );
        $languages[$languageCode] = $languageID;
    }
}
unset( $rows );

// ------------------------------------------

$cli->notice( 'Step 2/5: Fixing the ezcontentclass table.' );

// TODO: use remote id
$db->query( "UPDATE ezcontentclass
             SET always_available='1'
             WHERE id IN ( '1', '3', '4' ) " );

// ------------------------------------------

$cli->notice( 'Step 3/5: Fixing content object attributes and versions. Please be patient, this might take a while...' );

$db->query( "UPDATE ezcontentobject_attribute SET language_id='0'" );
foreach( $languages as $languageCode => $languageID )
{
    $db->query( "UPDATE ezcontentobject_attribute SET language_id='$languageID' WHERE language_code='$languageCode'" );
}
// Fixing inconsistencies:
$db->query( "UPDATE ezcontentobject_attribute SET language_id='$defaultLanguage', language_code='$defaultLanguageCode' WHERE language_id='0'" );

$db->query( "CREATE TEMPORARY TABLE version_languages ( contentobject_id int, version int, language_id int )" );
$db->query( "CREATE TEMPORARY TABLE version_language_masks ( contentobject_id int, version int, language_mask int )" );

$db->query( "INSERT INTO version_languages( contentobject_id, version, language_id )
             SELECT DISTINCT contentobject_id, version, language_id FROM ezcontentobject_attribute" );
$db->query( "INSERT INTO version_language_masks( contentobject_id, version, language_mask )
             SELECT contentobject_id, version, sum( language_id ) as language_mask
             FROM version_languages
             GROUP BY contentobject_id, version" );

$count = $db->arrayQuery( "SELECT count(*) as count FROM version_language_masks" );
$count = $count[0]['count'];

$limit = 100;
$offset = 0;

while ( $rows = $db->arrayQuery( "SELECT * FROM version_language_masks", array( 'limit' => $limit, 'offset' => $offset ) ) )
{
    foreach( $rows as $row )
    {
        $objectID = $row['contentobject_id'];
        $version = $row['version'];
        $languageMask = $row['language_mask'];
        if ( $languageMask & $defaultLanguage )
        {
            $initialLanguage = $defaultLanguage;
        }
        else
        {
            $initialLanguage = minBit( $languageMask );
        }

        $languageMask++;

        $db->query( "UPDATE ezcontentobject_version
                     SET initial_language_id='$initialLanguage',
                         language_mask='$languageMask'
                     WHERE contentobject_id='$objectID'
                     AND version='$version'" );
    }

    unset( $rows );
    $offset += $limit;

    $percentage = floor( 100 * $offset / $count );
    if ( $percentage > 100 )
    {
        $percentage = 100;
    }

    $cli->notice( "\r  $percentage%", false );
}

$cli->notice( "\r  done" );

$db->query( "DROP TEMPORARY TABLE version_language_masks" );
$db->query( "DROP TEMPORARY TABLE version_languages" );

// Fixing inconsistencies
$defaultMask = $defaultLanguage + 1;
$db->query( "UPDATE ezcontentobject_version SET initial_language_id='$defaultLanguage', language_mask='$defaultMask' WHERE initial_language_id='0'" );

// ------------------------------------------

$cli->notice( 'Step 4/5: Fixing the ezcontentobject_name table.' );

$db->query( "DELETE FROM ezcontentobject_name WHERE content_translation<>real_translation" );
foreach( $languages as $languageCode => $languageID )
{
    $db->query( "UPDATE ezcontentobject_name SET language_id='$languageID' WHERE real_translation='$languageCode'" );
}
// Fixing inconsistencies
$db->query( "UPDATE ezcontentobject_name SET language_id='$defaultLanguage', content_translation='$defaultLanguageCode', real_translation='$defaultLanguageCode' WHERE language_id='0'" );


// ------------------------------------------

$cli->notice( 'Step 5/5: Fixing content objects. Please be patient, this might take a while...' );

$topLevelObjects = array();
$rows = $db->arrayQuery( "SELECT contentobject_id FROM ezcontentobject_tree WHERE parent_node_id=1 AND node_id<>1" );
foreach( $rows as $row )
{
    $topLevelObjects[] = $row['contentobject_id'];
}
unset( $rows );

$count = $db->arrayQuery( "SELECT count(*) as count FROM ezcontentobject" );
$count = $count[0]['count'];

$limit = 100;
$offset = 0;
$lastID = 0;

while ( $objects = $db->arrayQuery( "SELECT ezcontentobject.id, ezcontentobject.current_version, ezcontentobject.contentclass_id, 
                                            ezcontentobject_version.language_mask, ezcontentobject_version.initial_language_id
                                     FROM ezcontentobject, ezcontentobject_version
                                     WHERE ezcontentobject.id > '$lastID'
                                       AND ezcontentobject_version.contentobject_id=ezcontentobject.id
                                       AND ezcontentobject_version.version=ezcontentobject.current_version
                                     ORDER BY ezcontentobject.id", array( 'limit' => $limit ) ) )
{
    foreach ( $objects as $object )
    {
        $objectID = $object['id'];
        $version = $object['current_version'];
        $languageMask = $object['language_mask'];
        $languageMask--;
        $initialLanguage = $object['initial_language_id'];
        
        // if the object is a folder, a user, a user group or if it is a top-leve object, make it always available
        if ( in_array( $object['contentclass_id'], array( 1, 3, 4 ) ) ||
             in_array( $objectID, $topLevelObjects ) )
        {
            $languageMask++;
            $newLanguageID = $initialLanguage + 1;
            $db->query( "UPDATE ezcontentobject_attribute
                         SET language_id='$newLanguageID'
                         WHERE contentobject_id='$objectID' AND version='$version' AND language_id='$initialLanguage'" );
            $db->query( "UPDATE ezcontentobject_name
                         SET language_id='$newLanguageID' 
                         WHERE contentobject_id='$objectID' AND content_version='$version' AND language_id='$initialLanguage'" );
        }

        $db->query( "UPDATE ezcontentobject
                     SET language_mask='$languageMask',
                         initial_language_id='$initialLanguage'
                     WHERE id='$objectID'" );

        $lastID = $object['id'];
    }

    unset( $objects );
    $offset += $limit;

    $percentage = floor( 100 * $offset / $count );
    if ( $percentage > 100 )
    {
        $percentage = 100;
    }

    $cli->notice( "\r  $percentage%", false );
}

$cli->notice( "\r  done" );

// Fixing inconsistencies
$db->query( "UPDATE ezcontentobject SET initial_language_id='$defaultLanguage', language_mask='$defaultMask' WHERE initial_language_id='0'" );

$cli->notice( 'Done.' );

$script->shutdown();

?>