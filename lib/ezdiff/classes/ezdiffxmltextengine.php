<?php
//
// Definition of eZDiffXMLTextEngine class
//
// <creation-tag>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

/*! \file ezdiffxmltextengine.php
  eZDiffXMLTextEngine class
*/

/*!
  \class eZDiffXMLTextEngine ezdiffxmltextengine.php
  \ingroup eZDiff
  \brief This class creates a diff for xml text.
*/

include_once( 'lib/ezdiff/classes/ezdiffengine.php' );

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
        include_once( 'lib/ezdiff/classes/ezxmltextdiff.php' );
        include_once( 'lib/ezdiff/classes/ezdifftextengine.php' );

        $changes = new eZXMLTextDiff();

        $oldXMLTextObject = $fromData->content();
        $newXMLTextObject = $toData->content();
        
        $oldXML = $oldXMLTextObject->attribute( 'xml_data' );
        $newXML = $newXMLTextObject->attribute( 'xml_data' );

        $old = trim( strip_tags( $oldXML ) );
        $new = trim( strip_tags( $newXML ) );

        $pattern = array( '/[ ][ ]+/',
                          '/ \n( \n)+/',
                          '/^ /m' );
        $replace = array( ' ',
                          "\n",
                          '' );

        $old = preg_replace( $pattern, $replace, $old );
        $new = preg_replace( $pattern, $replace, $new );

        $oldArray = explode( " ", $old );
        $newArray = explode( " ", $new );

        $oldArray = $this->trimEmptyArrayElements( $oldArray );
        $newArray = $this->trimEmptyArrayElements( $newArray );

        $textDiffer = new eZDiffTextEngine();
        $stats = $textDiffer->createStatisticsArray( $old, $oldArray, $new, $newArray );
        $output = $textDiffer->buildDiff( $stats, $oldArray, $newArray );
        $changes->setChanges( $output );
        return $changes;
    }

    /*!
      \private
      Removes empty elements from array
      \return array without empty elements
    */
    function trimEmptyArrayElements( $array )
    {
        foreach( $array as $key => $value )
        {
            if ( empty( $value ) )
            {
                unset( $array[$key] );
            }
        }
        //Calls array_merge to reset the array keys.
        return array_merge( $array );
    }
}

?>
