<?php
//
// Definition of Translations class
//
// Created on: <23-ñÎ×-2003 12:52:42 sp>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
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
        $translation =& eZContentTranslation::createNew( $translationName, $translationLocale );
        $translation->store();
        $translation->updateObjectNames();
    }
}

if ( $Module->isCurrentAction( 'Confirm' ) )
{
    $tranlationList = $Module->actionParameter( 'ConfirmList' );
    foreach ( $tranlationList as $translationID )
    {
        $translation =& eZContentTranslation::fetch( $translationID );
        $translation->remove();
    }
}

if ( $Module->isCurrentAction( 'Remove' ) )
{
    $seletedIDList =& $Module->actionParameter( 'SelectedTranslationList' );
    $confirmTranslationList = array();
    $confirmTranslationIDList = array();
    $totalRemoveTranslation = 0;
    foreach ( $seletedIDList as $translationID )
    {
        $translation =& eZContentTranslation::fetch( $translationID );
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
    $translation =& eZContentTranslation::fetch( $currentTranslation->attribute( 'id' ) );
    $translatedObjectsCount = $translation->translatedObjectsCount();

    $availableTranslations[] = array( 'translation' => $translation, 'object_count' => $translatedObjectsCount );
}

$tpl->setVariable( 'existing_translations', $translations );
$tpl->setVariable( 'available_translations', $availableTranslations );
$tpl->setVariable( 'module', $Module );

//$tpl->setVariable( 'workflow_list', $workflowList );

$Result['content'] =& $tpl->fetch( 'design:content/translations.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/content', 'Content translations' ),
                                'url' => false ) );


?>
