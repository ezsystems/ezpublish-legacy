#!/usr/bin/env php
<?php
//
// Created on: <29-Sep-2006 15:47:14 dl>
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

/*! \file updateclasstranslations.php
*/

set_time_limit( 0 );

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$endl = $cli->endlineString();

$script =& eZScript::instance( array( 'description' => ( "eZ publish update of class/classattribute names translations.\n\n".
                                                         "Will go over class/classattributes and reinitialize their names for missing translations" .
                                                         "\n" .
                                                         "updateclasstranslations.php" ),
                                      'use-session' => true,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( '[sql][language:]',
                                '',
                                array( 'sql' => 'Display sql queries',
                                       'language' => 'The language of existing class/classattribute names. ex: eng-GB'
                                       ) );
$script->initialize();

$showSQL = $options['sql'] ? true : false;
$languageLocale = $options['language'];
$siteAccess = $options['siteaccess'] ? $options['siteaccess'] : false;

if ( $siteAccess )
{
    changeSiteAccessSetting( $siteaccess, $siteAccess );
}

function changeSiteAccessSetting( &$siteaccess, $optionData )
{
    global $isQuiet;
    $cli =& eZCLI::instance();
    if ( file_exists( 'settings/siteaccess/' . $optionData ) )
    {
        $siteaccess = $optionData;
        if ( !$isQuiet )
            $cli->notice( "Using siteaccess '" . $cli->stylize( 'emphasize', $siteaccess ) . "' for translation update" );
    }
    else
    {
        if ( !$isQuiet )
            $cli->notice( "Siteaccess '" . $cli->stylize( 'emphasize', $optionData ) . "' does not exist, using default siteaccess" );
    }
}

include_once( 'kernel/classes/ezcontentclassattribute.php' );
include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezcontentlanguage.php' );
include_once( 'lib/ezdb/classes/ezdb.php' );

$db =& eZDB::instance();
$db->setIsSQLOutputEnabled( $showSQL );

$language = eZContentLanguage::fetchByLocale( $languageLocale );

if ( !$language )
{
    $cli->error( "Language '$languageLocale' doesn't exist.\n" );
    $script->shutdown( 1 );
}


$classList = eZContentClass::fetchList( false, true );

$db =& eZDB::instance();
$db->begin();

foreach ( $classList as $class )
{
    $oldClassName = $class->attribute( 'serialized_name_list' );

    $cli->output( "Updating " . $cli->stylize( 'emphasize', $oldClassName ) . " class" );

    $attributeList = $class->fetchAttributes();
    foreach ( $attributeList as $attribute )
    {
        $oldAttributeName = $attribute->attribute( 'serialized_name_list' );
        $attribute->setName( $oldAttributeName, $languageLocale );
        $attribute->store();
    }

    $class->setAttribute( 'language_mask', $language->attribute( 'id' ) );
    $class->setName( $oldClassName, $languageLocale );
    $class->setAttribute( 'initial_language_id', $language->attribute( 'id' ) );
    $class->setAlwaysAvailableLanguageID( $language->attribute( 'id' ) );
    // setAlwaysAvailableLanguageID will do 'store'
    // $class->store();
}

$db->commit();

$cli->output( $cli->stylize( 'emphasize', 'Done' ) );
$script->shutdown();

?>
