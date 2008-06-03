<?php
//
// Created on: <02-Oct-2006 13:37:23 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

$module = $Params['Module'];


if ( !$module->hasActionParameter( 'ClassID' ) )
{
    eZDebug::writeError( 'Missing ClassID parameter for action ' . $module->currentAction(),
                         'class/translation' );
    return $module->redirectToView( 'grouplist' );
}

$classID = $module->actionParameter( 'ClassID' );

if ( !$module->hasActionParameter( 'LanguageCode' ) )
{
    eZDebug::writeError( 'Missing LanguageCode parameter for action ' . $module->currentAction(),
                         'class/translation' );
    return $module->redirectToView( 'view', array( $classID ) );
}

$languageCode = $module->actionParameter( 'LanguageCode' );

if ( $module->isCurrentAction( 'Cancel' ) )
{
    return $module->redirectToView( 'view', array( $classID ), array( 'Language' => $languageCode ) );
}

$class = eZContentClass::fetch( $classID );

if ( !$class )
{
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

if ( $module->isCurrentAction( 'UpdateInitialLanguage' ) )
{
    if ( $module->hasActionParameter( 'InitialLanguageID' ) )
    {
        $newInitialLanguageID = $module->actionParameter( 'InitialLanguageID' );

        //include_once( 'kernel/classes/ezcontentlanguage.php' );
        $language = eZContentLanguage::fetch( $newInitialLanguageID );
        if ( $language )
        {
            $class->setAttribute( 'initial_language_id', $newInitialLanguageID );
            $class->setAlwaysAvailableLanguageID( $newInitialLanguageID );
        }
    }

    return $module->redirectToView( 'view', array( $classID ), array( 'Language' => $languageCode ) );
}
else if ( $module->isCurrentAction( 'RemoveTranslation' ) )
{
    if ( !$module->hasActionParameter( 'LanguageID' ) )
    {
        return $module->redirectToView( 'view', array( $classID ), array( 'Language' => $languageCode ) );
    }

    $languageIDArray = $module->actionParameter( 'LanguageID' );

    if ( $module->hasActionParameter( 'ConfirmRemoval' ) && $module->actionParameter( 'ConfirmRemoval' ) )
    {
        foreach( $languageIDArray as $languageID )
        {
            if ( !$class->removeTranslation( $languageID ) )
            {
                eZDebug::writeError( "Class with id " . $class->attribute( 'id' ) . ": cannot remove the translation with language id $languageID!", 'class/translation' );
            }
        }

        //probably we've just removed translation we were viewing.
        if ( !$class->hasNameInLanguage( $languageCode ) )
            $languageCode = $class->alwaysAvailableLanguageLocale();

        return $module->redirectToView( 'view', array( $classID ), array( 'Language' => $languageCode ) );
    }

    //include_once( 'kernel/classes/ezcontentlanguage.php' );

    $languages = array();
    foreach( $languageIDArray as $languageID )
    {
        $language = eZContentLanguage::fetch( $languageID );
        if ( $language )
        {
            $languages[] = $language;
        }
    }

    if ( !$languages )
    {
        return $module->redirectToView( 'view', array( $classID ), array( $languageCode ) );
    }

    require_once( "kernel/common/template.php" );

    $tpl = templateInit();

    $tpl->setVariable( 'class_id', $classID );
    $tpl->setVariable( 'class', $class );
    $tpl->setVariable( 'language_code', $languageCode );
    $tpl->setVariable( 'languages', $languages );

    $Result = array();
    $Result['content'] = $tpl->fetch( 'design:class/removetranslation.tpl' );
    $Result['path'] = array( array( 'url' => false,
                                    'text' => ezi18n( 'kernel/class', 'Remove translation' ) ) );

    return;
}





?>
