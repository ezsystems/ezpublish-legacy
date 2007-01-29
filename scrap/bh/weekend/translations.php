<?php
//
// Definition of Translations class
//
// Created on: <23-ñÎ×-2003 12:52:42 sp>
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

/*! \file translations.php
*/

include_once( 'kernel/common/template.php' );
include_once( 'kernel/classes/ezcontenttranslation.php' );
$tpl =& templateInit();
$http =& eZHTTPTool::instance();
$Module =& $Params['Module'];

$tpl->setVariable( 'module', $Module );


if ( $Module->isCurrentAction( 'New' ) /*or
     $Module->isCurrentAction( 'Edit' )*/ )
{
    $tpl->setVariable( 'is_edit', $Module->isCurrentAction( 'Edit' ) );
    $Result['content'] =& $tpl->fetch( 'design:content/translationnew.tpl' );
    $Result['path'] = array( array( 'text' => ezi18n( 'kernel/content', 'Translation' ),
                                    'url' => false ),
                             array( 'text' => 'New',
                                    'url' => false ) );
    return;
}

if ( $Module->isCurrentAction( 'StoreNew' ) /* || $http->hasPostVariable( 'StoreButton' ) */ )
{
    $localeID = $Module->actionParameter( 'LocaleID' );
    $translationName = '';
    $translationLocale = '';
    eZDebug::writeDebug( $localeID, 'localeID' );
    if ( $localeID != '' and
         $localeID != -1 )
    {
        $translationLocale = $localeID;
    }
    else
    {
        $translationName = $Module->actionParameter( 'TranslationName' );
        $translationLocale = $Module->actionParameter( 'TranslationLocale' );
        eZDebug::writeDebug( $translationName, 'translationName' );
        eZDebug::writeDebug( $translationLocale, 'translationLocale' );
    }
    if ( !eZContentTranslation::hasTranslation( $translationLocale ) )
    {
        $translation = eZContentTranslation::createNew( $translationName, $translationLocale );
        $translation->store();
        $translation->updateObjectNames();
    }
}

if ( $Module->isCurrentAction( 'Confirm' ) )
{
    $tranlationList = $Module->actionParameter( 'ConfirmList' );
    foreach ( $tranlationList as $translationID )
    {
        $translation = eZContentTranslation::fetch( $translationID );
        $translation->remove();
    }
}

if ( $Module->isCurrentAction( 'Remove' ) )
{
    $seletedIDList = $Module->actionParameter( 'SelectedTranslationList' );
    $confirmTranslationList = array();
    $confirmTranslationIDList = array();
    $totalRemoveTranslation = 0;
    foreach ( $seletedIDList as $translationID )
    {
        $translation = eZContentTranslation::fetch( $translationID );
        $translatedObjectsCount = $translation->translatedObjectsCount();
        if ( $translatedObjectsCount == 0 )
        {
            $translation->remove();
        }
        else
        {
            $item = array();
            $item['translation'] =& $translation;
            $item['count'] = $translatedObjectsCount;
            $confirmTranslationList[] = $item;
        }

    }
    if ( count( $confirmTranslationList ) > 0 )
    {
        $tpl->setVariable( 'confirm_list', $confirmTranslationList );
        $Result['content'] =& $tpl->fetch( 'design:content/confirmtranslationremove.tpl' );
        $Result['path'] = array( array( 'text' => ezi18n( 'kernel/content', 'Translation' ),
                                        'url' => false ),
                                 array( 'text' => 'Confirm remove',
                                        'url' => false ) );
        return;
    }
}



if ( $Params['TranslationID'] )
{

$translation = eZContentTranslation::fetch( $Params['TranslationID'] );

 if( $translation )
 {
     $translatedObjectsCount = $translation->translatedObjectsCount();

     $tpl->setVariable( 'translation',  $translation );
     $tpl->setVariable( 'object_count', $translatedObjectsCount );
 }

 $Result['content'] =& $tpl->fetch( 'design:content/translationview.tpl' );
 $Result['path'] = array( array( 'text' => ezi18n( 'kernel/content', 'Content translations' ),
                                'url' => false ) );
 return;
}



$translations = eZContentTranslation::fetchList();
foreach ( array_keys( $translations ) as $translationKey )
{
    $translation =& $translations[$translationKey];
    if ( $translation->attribute( 'id' ) === null )
        $translation->store();
}

$availableTranslations = array();

foreach( $translations as $currentTranslation )
{
    $translation = eZContentTranslation::fetch( $currentTranslation->attribute( 'id' ) );
    $translatedObjectsCount = $translation->translatedObjectsCount();

    $availableTranslations[] = array( 'translation' => $translation, 'object_count' => $translatedObjectsCount );
}

$tpl->setVariable( 'existing_translations', $translations );
$tpl->setVariable( 'available_translations', $availableTranslations );

//$tpl->setVariable( 'workflow_list', $workflowList );

$Result['content'] =& $tpl->fetch( 'design:content/translations.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/content', 'Content translations' ),
                                'url' => false ) );


?>
