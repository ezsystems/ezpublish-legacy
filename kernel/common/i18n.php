<?php
//
// Created on: <06-Jul-2003 15:52:54 amos>
//
// Copyright (C) 1999-2006 eZ systems as. All rights reserved.
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

include_once( 'lib/ezutils/classes/ezini.php' );

/*!
 \return the current language used.
*/
function ezcurrentLanguage()
{
    include_once( 'lib/ezlocale/classes/ezlocale.php' );
    $locale =& eZLocale::instance();
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
$ini =& eZINI::instance();
$useTextTranslation = false;
$hasFallback = false;
if ( $ini->variable( 'RegionalSettings', 'TextTranslation' ) != 'disabled' )
{
    $language = ezcurrentLanguage();
    $iniI18N =& eZINI::instance( "i18n.ini" );
    $fallbacks = $iniI18N->variable( 'TranslationSettings', 'FallbackLanguages' );
                
    if ( array_key_exists( $language,  $fallbacks ) and $fallbacks[$language] )
    {
        if ( file_exists( 'share/translations/' . $fallbacks[$language] . '/translation.ts' ) )
            $hasFallback = true;
    }
    if ( file_exists( 'share/translations/' . $language . '/translation.ts' ) or $hasFallback )
    {
        $useTextTranslation = true;
    }
}

if ( $useTextTranslation )
{
    include_once( 'lib/ezutils/classes/ezextension.php' );
    include_once( 'lib/ezi18n/classes/eztranslatormanager.php' );
    include_once( 'lib/ezi18n/classes/eztstranslator.php' );

    function &ezi18n( $context, $source, $comment = null, $arguments = null )
    {
        $text = eZTranslateText( $context, $source, $comment, $arguments );
        return $text;
    }

    function &ezx18n( $extension, $context, $source, $comment = null, $arguments = null )
    {
        $text = eZTranslateText( $context, $source, $comment, $arguments );
        return $text;
    }

    function &eZTranslateText( $context, $source, $comment = null, $arguments = null )
    {
        $language = ezcurrentLanguage();

        $file = 'translation.ts';

        // translation.ts translation
        $ini =& eZINI::instance();
        $useCache = $ini->variable( 'RegionalSettings', 'TranslationCache' ) != 'disabled';
        eZTSTranslator::initialize( $context, $language, $file, $useCache );

        // Bork translation: Makes it easy to see what is not translated.
        // If no translation is found in the eZTSTranslator, a Bork translation will be returned.
        // Bork is different than, but similar to, eng-GB, and is enclosed in square brackets [].
        $developmentMode = $ini->variable( 'RegionalSettings', 'DevelopmentMode' ) != 'disabled';
        if ( $developmentMode )
        {
            include_once( 'lib/ezi18n/classes/ezborktranslator.php' );
            eZBorkTranslator::initialize();
        }

        $man =& eZTranslatorManager::instance();
        $trans = $man->translate( $context, $source, $comment );
        if ( $trans !== null ) {
            $text = ezinsertarguments( $trans, $arguments );
            return $text;
        }

        eZDebug::writeWarning( "No translation for file(translation.ts) in context($context): '$source' with comment($comment)", "ezi18n" );
        $text = ezinsertarguments( $source, $arguments );
        return $text;
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
