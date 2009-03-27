<?php
//
// Definition of eZDiffXMLTextEngine class
//
// <creation-tag>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

/*! \file
  eZDiffXMLTextEngine class
*/

/*!
  \class eZDiffXMLTextEngine ezdiffxmltextengine.php
  \ingroup eZDiff
  \brief This class creates a diff for xml text.
*/

class eZDiffXMLTextEngine extends eZDiffEngine
{
    function eZDiffXMLTextEngine()
    {
    }

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
