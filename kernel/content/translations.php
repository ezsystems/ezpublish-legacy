<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$tpl = eZTemplate::factory();
$http = eZHTTPTool::instance();
$Module = $Params['Module'];

$tpl->setVariable( 'module', $Module );


if ( $Module->isCurrentAction( 'New' ) /*or
     $Module->isCurrentAction( 'Edit' )*/ )
{
    $tpl->setVariable( 'is_edit', $Module->isCurrentAction( 'Edit' ) );
    $Result['content'] = $tpl->fetch( 'design:content/translationnew.tpl' );
    $Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/content', 'Translation' ),
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
        $localeInstance = eZLocale::instance( $translationLocale );
        $translationName = $localeInstance->internationalLanguageName();
    }
    else
    {
        $translationName = $Module->actionParameter( 'TranslationName' );
        $translationLocale = $Module->actionParameter( 'TranslationLocale' );
        eZDebug::writeDebug( $translationName, 'translationName' );
        eZDebug::writeDebug( $translationLocale, 'translationLocale' );
    }

    // Make sure the locale string is valid, if not we try to extract a valid part of it
    if ( !preg_match( "/^" . eZLocale::localeRegexp( false, false ) . "$/", $translationLocale ) )
    {
        if ( preg_match( "/(" . eZLocale::localeRegexp( false, false ) . ")/", $translationLocale, $matches ) )
        {
            $translationLocale = $matches[1];
        }
        else
        {
            // The locale cannot be used so we show the edit page again.
            $tpl->setVariable( 'is_edit', $Module->isCurrentAction( 'Edit' ) );
            $Result['content'] = $tpl->fetch( 'design:content/translationnew.tpl' );
            $Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/content', 'Translation' ),
                                            'url' => false ),
                                     array( 'text' => 'New',
                                            'url' => false ) );
            return;
        }
    }

    if ( !eZContentLanguage::fetchByLocale( $translationLocale ) )
    {
        $locale = eZLocale::instance( $translationLocale );
        if ( $locale->isValid() )
        {
            $translation = eZContentLanguage::addLanguage( $locale->localeCode(), $translationName );
        }
        else
        {
            // The locale cannot be used so we show the edit page again.
            $tpl->setVariable( 'is_edit', $Module->isCurrentAction( 'Edit' ) );
            $Result['content'] = $tpl->fetch( 'design:content/translationnew.tpl' );
            $Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/content', 'Translation' ),
                                            'url' => false ),
                                     array( 'text' => 'New',
                                            'url' => false ) );
            return;
        }
    }
}

if ( $Module->isCurrentAction( 'Remove' ) )
{
    $seletedIDList = $Module->actionParameter( 'SelectedTranslationList' );

    $db = eZDB::instance();

    $db->begin();
    foreach ( $seletedIDList as $translationID )
    {
        eZContentLanguage::removeLanguage( $translationID );
    }
    $db->commit();
}


if ( $Params['TranslationID'] )
{
    $translation = eZContentLanguage::fetch( $Params['TranslationID'] );

    if( !$translation )
    {
        return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    }

    $tpl->setVariable( 'translation',  $translation );

    $Result['content'] = $tpl->fetch( 'design:content/translationview.tpl' );
    $Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/content', 'Content translations' ),
                                    'url' => 'content/translations' ),
                             array( 'text' => $translation->attribute( 'name' ),
                                    'url' => false ) );
    return;
}

$availableTranslations = eZContentLanguage::fetchList();

$tpl->setVariable( 'available_translations', $availableTranslations );

$Result['content'] = $tpl->fetch( 'design:content/translations.tpl' );
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/content', 'Languages' ),
                                'url' => false ) );

?>
