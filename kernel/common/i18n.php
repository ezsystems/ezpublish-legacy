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

 If the site.ini settings RegionalSettings/TextTranslation is set to disabled this function
 will only return the source text.
*/
$ini =& eZINI::instance();
if ( $ini->variable( 'RegionalSettings', 'TextTranslation' ) != 'disabled' )
{
    function &ezi18n( $context, $source, $comment = null )
        {
            $language =& ezcurrentLanguage();
            if ( $language == "eng-GB" ) // eng-GB does not need translation
                return $source;

            $man =& eZTranslatorManager::instance();

            // Bork translation: use this to verify that translation works.
            // Will output something different than, but similar to, eng-GB.
            // BEGIN bork translation
//             include_once( 'lib/ezi18n/classes/ezborktranslator.php' );
//             $borktr = new eZBorkTranslator();
//             $man->registerHandler( $borktr );
            // END bork translation

            eZTSTranslator::initialize( $language . '/translation.ts' );
            $trans =& $man->translate( $context, $source, $comment );
            if ( $trans !== null )
                return $trans;
            eZDebug::writeWarning( "No translation for file(translation.ts) in context($context): '$source' with comment($comment)", "ezi18n" );
            return $source;
        }
}
else
{
    function &ezi18n( $context, $source, $comment = null )
        {
            return $source;
        }
}

?>
