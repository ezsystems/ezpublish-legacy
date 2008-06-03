<?php
//
// Created on: <06-Jul-2003 15:52:54 amos>
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

//include_once( 'lib/ezutils/classes/ezini.php' );

/*!
 \return the current language used.
*/
function ezcurrentLanguage()
{
    //include_once( 'lib/ezlocale/classes/ezlocale.php' );
    $locale = eZLocale::instance();
    return $locale->translationCode();
}

/*!
 Replaces keys found in \a $text with values in \a $arguments.
 If \a $arguments is an associative array it will use the argument
 keys as replacement keys. If not it will convert the index to
 a key looking like %n, where n is a number between 1 and 9.
 Returns the new string.
*/
function ezinsertarguments( $text, $arguments )
{
    if ( is_array( $arguments ) )
    {
        $replaceList = array();
        foreach ( $arguments as $argumentKey => $argumentItem )
        {
            if ( is_int( $argumentKey ) )
                $replaceList['%' . ( ($argumentKey%9) + 1 )] = $argumentItem;
            else
                $replaceList[$argumentKey] = $argumentItem;
        }
        $text = strtr( $text, $replaceList );
    }
    return $text;
}

/*!
 Translates the source \a $source with context \a $context and optional comment \a $comment
 and returns the translation.
 Uses eZTranslatorMananger::translate() to do the actual translation.

 If the site.ini settings RegionalSettings/TextTranslation is set to disabled this function
 will only return the source text.
*/
$ini = eZINI::instance();
$useTextTranslation = false;
$hasFallback = false;
if ( $ini->variable( 'RegionalSettings', 'TextTranslation' ) != 'disabled' )
{
    $language = ezcurrentLanguage();
    $iniI18N = eZINI::instance( "i18n.ini" );
    $fallbacks = $iniI18N->variable( 'TranslationSettings', 'FallbackLanguages' );

    //include_once( 'lib/ezutils/classes/ezextension.php' );
    $extensionBase = eZExtension::baseDirectory();
    $translationExtensions = $ini->variable( 'RegionalSettings', 'TranslationExtensions' );

    if ( array_key_exists( $language,  $fallbacks ) and $fallbacks[$language] )
    {
        if ( file_exists( 'share/translations/' . $fallbacks[$language] . '/translation.ts' ) )
        {
            $hasFallback = true;
        }
        else
        {
            foreach ( $translationExtensions as $translationExtension )
            {
                $extensionPath = $extensionBase . '/' . $translationExtension . '/translations/' . $fallbacks[$language] . '/translation.ts';
                if ( file_exists( $extensionPath ) )
                {
                    $hasFallback = true;
                    break;
                }
            }
        }
    }
    if ( file_exists( 'share/translations/' . $language . '/translation.ts' ) || $hasFallback )
    {
        $useTextTranslation = true;
    }
    else
    {
        foreach ( $translationExtensions as $translationExtension )
        {
            $extensionPath = $extensionBase . '/' . $translationExtension . '/translations/' . $language . '/translation.ts';
            if ( file_exists( $extensionPath ) )
            {
                $useTextTranslation = true;
                break;
            }
        }
    }
}

//include_once( 'lib/ezi18n/classes/eztranslatormanager.php' );

if ( $useTextTranslation || eZTranslatorManager::dynamicTranslationsEnabled() )
{
    //include_once( 'lib/ezi18n/classes/eztstranslator.php' );

    function ezi18n( $context, $source, $comment = null, $arguments = null )
    {
        return eZTranslateText( $context, $source, $comment, $arguments );
    }

    function ezx18n( $extension, $context, $source, $comment = null, $arguments = null )
    {
        return eZTranslateText( $context, $source, $comment, $arguments );
    }

    function eZTranslateText( $context, $source, $comment = null, $arguments = null )
    {
        $ini = eZINI::instance();
        if ( $ini->variable( 'RegionalSettings', 'Locale' ) == 'eng-GB' )
        {
            // we don't have ts-file for 'eng-GB'.
            // NOTE: don't remove this 'if'. it's needed to support dynamic switch between translations.
            return ezinsertarguments( $source, $arguments );
        }

        $language = ezcurrentLanguage();

        $file = 'translation.ts';

        // translation.ts translation
        $useCache = $ini->variable( 'RegionalSettings', 'TranslationCache' ) != 'disabled';
        eZTSTranslator::initialize( $context, $language, $file, $useCache );

        // Bork translation: Makes it easy to see what is not translated.
        // If no translation is found in the eZTSTranslator, a Bork translation will be returned.
        // Bork is different than, but similar to, eng-GB, and is enclosed in square brackets [].
        $developmentMode = $ini->variable( 'RegionalSettings', 'DevelopmentMode' ) != 'disabled';
        if ( $developmentMode )
        {
            //include_once( 'lib/ezi18n/classes/ezborktranslator.php' );
            eZBorkTranslator::initialize();
        }

        $man = eZTranslatorManager::instance();
        $trans = $man->translate( $context, $source, $comment );
        if ( $trans !== null ) {
            return ezinsertarguments( $trans, $arguments );
        }

        eZDebug::writeWarning( "No translation for file(translation.ts) in context($context): '$source' with comment($comment)", "ezi18n" );
        return ezinsertarguments( $source, $arguments );
    }
}
else
{
    function ezi18n( $context, $source, $comment = null, $arguments = null )
    {
        return ezinsertarguments( $source, $arguments );
    }

    function ezx18n( $extension, $context, $source, $comment = null, $arguments = null )
    {
        return ezinsertarguments( $source, $arguments );
    }
}

?>
