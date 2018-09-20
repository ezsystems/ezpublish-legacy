<?php
/**
 * File containing the eZDiffXMLTextEngine class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZDiffXMLTextEngine ezdiffxmltextengine.php
  \ingroup eZDiff
  \brief This class creates a diff for xml text.
*/

class eZDiffXMLTextEngine extends eZDiffEngine
{
    /*!
      This function calculates changes in xml text and creates an object to hold
      overview of changes.
    */
    function createDifferenceObject( $fromData, $toData )
    {
        $changes = new eZXMLTextDiff();
        $contentINI = eZINI::instance( 'content.ini' );
        $useSimplifiedXML = $contentINI->variable( 'ContentVersionDiffSettings', 'UseSimplifiedXML' );
        $diffSimplifiedXML = ( $useSimplifiedXML == 'enabled' );

        $oldXMLTextObject = $fromData->content();
        $newXMLTextObject = $toData->content();

        $oldXML = $oldXMLTextObject->attribute( 'xml_data' );
        $newXML = $newXMLTextObject->attribute( 'xml_data' );

        $simplifiedXML = new eZSimplifiedXMLEditOutput();

        $domOld = new DOMDocument( '1.0', 'utf-8' );
        $domOld->preserveWhiteSpace = false;
        $domOld->loadXML( $oldXML );

        $domNew = new DOMDocument( '1.0', 'utf-8' );
        $domNew->preserveWhiteSpace = false;
        $domNew->loadXML( $newXML );

        $old = $simplifiedXML->performOutput( $domOld );
        $new = $simplifiedXML->performOutput( $domNew );

        if ( !$diffSimplifiedXML )
        {
            $old = trim( strip_tags( $old ) );
            $new = trim( strip_tags( $new ) );

            $pattern = array( '/[ ][ ]+/',
                              '/ \n( \n)+/',
                              '/^ /m',
                              '/(\n){3,}/' );
            $replace = array( ' ',
                              "\n",
                              '',
                              "\n\n" );

            $old = preg_replace( $pattern, $replace, $old );
            $new = preg_replace( $pattern, $replace, $new );
        }

        $oldArray = explode( "\n", $old );
        $newArray = explode( "\n", $new );

        $oldSums = array();
        foreach( $oldArray as $paragraph )
        {
            $oldSums[] = crc32( $paragraph );
        }

        $newSums = array();
        foreach( $newArray as $paragraph )
        {
            $newSums[] = crc32( $paragraph );
        }

        $textDiffer = new eZDiffTextEngine();

        $pre = $textDiffer->preProcess( $oldSums, $newSums );
        $out = $textDiffer->createOutput( $pre, $oldArray, $newArray );
        $changes->setChanges( $out );
        return $changes;
    }
}

?>
