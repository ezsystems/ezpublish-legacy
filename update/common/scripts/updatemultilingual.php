#!/usr/bin/env php
<?php
//
// Created on: <22-Mar-2006 09:23:12 jk>
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

//include_once( 'lib/ezutils/classes/ezcli.php' );
//include_once( 'kernel/classes/ezscript.php' );
//include_once( 'kernel/classes/ezcontentlanguage.php' );
//include_once( 'kernel/classes/ezcontentobjectversion.php' );
//include_once( 'lib/ezutils/classes/ezextension.php' );

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

$cli = eZCLI::instance();

$script = eZScript::instance( array( 'description' => "Update database for the multilingual suport.",
                                      'use-session' => true,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "",
                                "" );

$extensionBaseDir = eZExtension::baseDirectory();
$extensionNameArray = eZExtension::activeExtensions();
$siteAccessPath = '/settings/siteaccess/';
$siteAccessExists = false;

if ( !$options['siteaccess'] )
{
    $cli->error( "Siteaccess was not given. Exiting..." );
    $script->shutdown( -1 );
}

$siteAccessExists = file_exists( 'settings/siteaccess/' . $options['siteaccess'] );
if ( !$siteAccessExists )
{
    // check extensions.
    foreach ( $extensionNameArray as $extensionName )
    {
        $extensionSiteaccessPath = $extensionBaseDir . '/' . $extensionName . $siteAccessPath;
        if ( file_exists( $extensionSiteaccessPath . $options['siteaccess'] ) )
        {
            $siteAccessExists = true;
            break;
        }
    }
}

if ( !$siteAccessExists )
{
    $cli->error( "Siteaccess '" . $options['siteaccess'] . "' does not exist. Exiting..." );
    $script->shutdown( -1 );
}

$script->setUseSiteAccess( $options['siteaccess'] );

$script->initialize();

$cli->warning( "Have you backed up your database? If not, press Ctrl-C and back up your data!" );

$db = eZDB::instance();
$ini = eZINI::instance();

$defaultLanguageCode = $ini->variable( 'RegionalSettings', 'ContentObjectLocale' );

$cli->notice( "The default language for your siteaccess is '$defaultLanguageCode' and will be used as the initial language for all your objects." );
$cli->notice( "The script will use the following database settings:" );
$cli->notice( "  user: <" . $db->User . ">, " . ( strlen( $db->Password ) > 0 ? "password: <***>, " : "password: none, " ) . "server: <" . $db->Server . ">, socket: <" . $db->SocketPath . ">, name: <" . $db->DB . ">, charset: <" . $db->Charset . ">" );

$draftCount = $db->arrayQuery( "SELECT count(*) AS count
                                FROM ezcontentobject_version
                                WHERE status=0" );
$draftCount = $draftCount[0]['count'];

if ( $draftCount )
{
    $cli->warning( "You have $draftCount draft(s). These drafts will be removed." );
}

$cli->warning( "You have now 10 seconds to break this script (press Ctrl-C) if the settings are incorrect." );
sleep( 10 );

// ------------------------------------------

$cli->notice( 'Step 1/6: Removing the drafts:' );

$count = 0;
if ( $draftCount )
{
    $rows = $db->arrayQuery( "SELECT *
                              FROM ezcontentobject_version
                              WHERE status=0" );
    foreach( $rows as $row )
    {
        ++$count;
        if ( ( $count % 100 ) == 0 )
            $cli->warning( "Processed: $count of $draftCount " );

        // Check object consistensy
        $object = $db->arrayQuery( 'SELECT *
                                    FROM ezcontentobject
                                    WHERE id=' . $row[ 'contentobject_id' ] );
        $object = $object[0];

        if ( $object[ 'current_version' ] == $row[ 'version' ] && $object[ 'status' ] == 1 )
        {
            $db->query( 'UPDATE ezcontentobject_version SET status = 1 WHERE id=' . $row['id'] );
            continue;
        }

        $draft = new eZContentObjectVersion( $row );
        $draft->remove();
        eZContentObject::clearCache();
        unset( $draft );
    }
}

if ( $count > 0 )
{
    // last message
    $cli->warning( "Processed: $count of $draftCount " );
}
// ------------------------------------------

$cli->notice( 'Step 2/6: Identifying languages used on the site:' );

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
            $script->shutdown( -1 );
        }
        $languageID = $language->attribute( 'id' );
        $cli->notice( "  language: $languageCode (id $languageID)" );
        $languages[$languageCode] = $languageID;
    }
}
unset( $rows );

// ------------------------------------------

$cli->notice( 'Step 3/6: Fixing the ezcontentclass table.' );

$db->query( "UPDATE ezcontentclass
             SET always_available='1'
             WHERE remote_id IN ( 'a3d405b81be900468eb153d774f4f0d2',
                                  '25b4268cdcd01921b808a0d854b877ef',
                                  '40faa822edc579b02c25f6bb7beec3ad',
                                  'f6df12aa74e36230eb675f364fccd25a',
                                  '637d58bfddf164627bdfd265733280a0',
                                  'ffedf2e73b1ea0c3e630e42e2db9c900',
                                  '59b43cd9feaaf0e45ac974fb4bbd3f92' ) " );

$alwaysAvailableClasses = array();
$rows = $db->arrayQuery( "SELECT id FROM ezcontentclass WHERE always_available='1'" );
foreach( $rows as $row )
{
    $alwaysAvailableClasses[] = $row['id'];
}
unset( $rows );

// ------------------------------------------

$cli->notice( 'Step 4/6: Fixing the ezcontentobject_name table.' );

$db->query( "DELETE FROM ezcontentobject_name WHERE content_translation<>real_translation" );
foreach( $languages as $languageCode => $languageID )
{
    $db->query( "UPDATE ezcontentobject_name SET language_id='$languageID' WHERE content_translation='$languageCode'" );
}
// Fixing inconsistencies
$db->query( "UPDATE ezcontentobject_name SET language_id='$defaultLanguage', content_translation='$defaultLanguageCode'" );


// ------------------------------------------

$cli->notice( 'Step 5/6: Fixing content object versions and attributes. Please be patient, this might take a while...' );

$db->query( "UPDATE ezcontentobject_attribute SET language_id='0'" );
foreach( $languages as $languageCode => $languageID )
{
    $db->query( "UPDATE ezcontentobject_attribute SET language_id='$languageID' WHERE language_code='$languageCode'" );
}
// Fixing inconsistencies:
$db->query( "UPDATE ezcontentobject_attribute SET language_id='$defaultLanguage', language_code='$defaultLanguageCode' WHERE language_id='0'" );

$db->query( "CREATE TEMPORARY TABLE version_languages ( contentobject_id int, version int, language_id int )" );
$db->query( "CREATE TEMPORARY TABLE version_language_masks ( contentobject_id int, version int, language_mask int )" );
$db->query( "CREATE TEMPORARY TABLE object_language_masks ( contentobject_id int, language_mask int, PRIMARY KEY( contentobject_id ) )" );

$db->query( "INSERT INTO version_languages( contentobject_id, version, language_id )
             SELECT DISTINCT contentobject_id, version, language_id FROM ezcontentobject_attribute" );
$db->query( "INSERT INTO version_language_masks( contentobject_id, version, language_mask )
             SELECT contentobject_id, version, sum( language_id ) as language_mask
             FROM version_languages
             GROUP BY contentobject_id, version" );
$db->query( "INSERT INTO object_language_masks( contentobject_id, language_mask )
             SELECT contentobject_id, version_language_masks.language_mask
             FROM version_language_masks, ezcontentobject
             WHERE version_language_masks.contentobject_id = ezcontentobject.id
               AND version_language_masks.version = ezcontentobject.current_version" );

$count = $db->arrayQuery( "SELECT count(*) as count FROM version_language_masks" );
$count = $count[0]['count'];

$limit = 100;
$offset = 0;

while ( $rows = $db->arrayQuery( "SELECT a.*, b.language_mask as object_language_mask
                                  FROM version_language_masks a, object_language_masks b
                                  WHERE a.contentobject_id = b.contentobject_id", array( 'limit' => $limit, 'offset' => $offset ) ) )
{
    foreach( $rows as $row )
    {
        $objectID = $row['contentobject_id'];
        $version = $row['version'];
        $languageMask = (int) $row['language_mask'] & (int) $row['object_language_mask'];

        // removing attributes which exists in languages which do not exist in published version
        $originalLanguageMask = $languageMask;
        $maskArray = array();
        $candidate = 1;
        while ( $originalLanguageMask > 0 )
        {
            if ( $originalLanguageMask & 1 > 0 )
            {
                $maskArray[] = $candidate;
                $maskArray[] = $candidate+1;
            }
            $originalLanguageMask = (int) ( $originalLanguageMask / 2 );
            $candidate *= 2;
        }

        $attributes = $db->arrayQuery( "SELECT *
                                        FROM ezcontentobject_attribute
                                        WHERE contentobject_id = '$objectID'
                                          AND version = '$version'".
                                       ( ( $maskArray )? " AND language_id NOT IN ( " . implode( ', ', $maskArray ) ." )": '' ) );


        foreach ( $attributes as $attribute )
        {
            $attributeObject = new eZContentObjectAttribute( $attribute );
            $attributeObject->remove( $attributeObject->attribute( 'id' ), $attributeObject->attribute( 'version' ) );
            unset( $attributeObject );
        }
        if ( $attributes )
        {
            // removing the rows in the name table which exists in languages which do not exist in published version
            $db->query( "DELETE FROM ezcontentobject_name
                         WHERE contentobject_id = '$objectID' AND version = '$version'".
                         ( ( $maskArray )? " AND language_id NOT IN ( " . implode( ', ', $maskArray ) ." )": '' ) );
        }
        unset( $attributes );

        if ( $languageMask == 0 )
        {
            // This version does not contain any language, we will remove it
            $db->query( "DELETE FROM ezcontentobject_version
                         WHERE contentobject_id='$objectID'
                           AND version='$version'" );
        }
        else
        {
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

$db->query( "DROP TEMPORARY TABLE object_language_masks" );
$db->query( "DROP TEMPORARY TABLE version_language_masks" );
$db->query( "DROP TEMPORARY TABLE version_languages" );

// Fixing inconsistencies
$defaultMask = $defaultLanguage + 1;
$db->query( "UPDATE ezcontentobject_version SET initial_language_id='$defaultLanguage', language_mask='$defaultMask' WHERE initial_language_id='0'" );

// ------------------------------------------

$cli->notice( 'Step 6/6: Fixing content objects. Please be patient, this might take a while...' );

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
        $originalLanguageMask = (int) $languageMask;
        $initialLanguage = $object['initial_language_id'];

        // if the object is a folder, a user, a user group etc. or if it is a top-leve object, make it always available
        if ( in_array( $object['contentclass_id'], $alwaysAvailableClasses ) ||
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
