<?php
//
// Created on: <06-Jul-2003 15:52:54 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
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

/*!
 \return the current language used.
*/
function ezcurrentLanguage()
{
    return eZLocale::instance()->localeFullCode();
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
$useTextTranslation = $ini->variable( 'RegionalSettings', 'TextTranslation' ) != 'disabled';

if ( $useTextTranslation || eZTranslatorManager::dynamicTranslationsEnabled() )
{
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
        $localeCode = eZLocale::instance()->localeFullCode();
        if ( $localeCode == 'eng-GB' )
        {
            // we don't have ts-file for 'eng-GB'.
            return ezinsertarguments( $source, $arguments );
        }

        $ini = eZINI::instance();
        $useCache = $ini->variable( 'RegionalSettings', 'TranslationCache' ) != 'disabled';
        eZTSTranslator::initialize( $context, $localeCode, 'translation.ts', $useCache );

        // Bork translation: Makes it easy to see what is not translated.
        // If no translation is found in the eZTSTranslator, a Bork translation will be returned.
        // Bork is different than, but similar to, eng-GB, and is enclosed in square brackets [].
        $developmentMode = $ini->variable( 'RegionalSettings', 'DevelopmentMode' ) != 'disabled';
        if ( $developmentMode )
        {
            eZBorkTranslator::initialize();
        }

        $man = eZTranslatorManager::instance();
        $trans = $man->translate( $context, $source, $comment );
        if ( $trans !== null ) {
            return ezinsertarguments( $trans, $arguments );
        }

        eZDebug::writeDebug( "No translation for file(translation.ts) in context($context): '$source' with comment($comment)", "ezi18n" );
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
