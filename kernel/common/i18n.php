<?php

include_once( 'lib/ezutils/classes/ezini.php' );
include_once( 'lib/ezi18n/classes/eztranslatormanager.php' );
include_once( 'lib/ezi18n/classes/eztstranslator.php' );

function ezcurrentLanguage()
{
    $locale =& eZLocale::instance();
    $language =& $locale->localeCode();
    return $language;
}

/*!
 Translates the source \a $source with context \a $context and optional comment \a $comment
 and returns the translation.
 Uses eZTranslatorMananger::translate() to do the actual translation.
*/
function &ezi18n( $file, $context, $source, $comment = null )
{
    $man =& eZTranslatorManager::instance();
    $language =& ezcurrentLanguage();
    eZTSTranslator::initialize( $language . '/' . $file . '.ts' );
    $trans =& $man->translate( $context, $source, $comment );
    if ( $trans !== null )
        return $trans;
    eZDebug::writeWarning( "No translation for file($file) in context($context): '$source' with comment($comment)", "ezi18n" );
    return $source;
}

?>
