<?php
//
// Definition of eZKeyword class
//
// Created on: <29-Apr-2003 15:18:15 bf>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \class eZKeyword ezkeyword.php
  \ingroup eZKernel
  \brief A content datatype which handles keyword index instances

*/

class eZKeyword
{
    /*!
     Construct a new keyword instance
    */
    function eZKeyword( )
    {
    }

    /*!
     Initialze the keyword index
    */
    function initializeKeyword( $keywordString )
    {
        $keywordArray =& explode( ",", $keywordString );
        foreach ( array_keys( $keywordArray ) as $key )
        {
            if ( trim( $keywordArray[$key] ) != "" )
                $this->KeywordArray[$key] = trim( $keywordArray[$key] );
        }
    }

    /*!
     Stores the keyword index to database
    */
    function store( &$attribute )
    {
        $db =& eZDB::instance();

        $object =& $attribute->attribute( 'object' );
        $classID = $object->attribute( 'contentclass_id' );
        // Get already existing keywords
        $wordArray = array();
        $wordsString = implode( '\',\'', $this->KeywordArray );
        $existingWords =& $db->arrayQuery( "SELECT * FROM ezkeyword WHERE keyword IN ( '$wordsString' ) AND class_id='$classID' " );

        $keywordDiff = array();
        // Find out which words to store
        foreach ( $this->KeywordArray as $keyword )
        {
            $wordExists = false;
            foreach ( $existingWords as $existingKeyword )
            {
                if ( $keyword == $existingKeyword['keyword'] )
                {
                     $wordExists = true;
                     break;
                }
            }

            if ( $wordExists == false )
            {
                $keywordDiff[] = $keyword;
            }
        }

        // Store every new keyword
        foreach ( $keywordDiff as $keyword )
        {
            $keyword = trim( $keyword );
            $keyword = $db->escapeString( $keyword );
            $db->query( "INSERT INTO ezkeyword ( keyword, class_id ) VALUES ( '$keyword', '$classID' )" );

            $keywordID = $db->lastSerialID();
            $existingWords[] = array( 'keyword' => $keywordID, 'id' => $keywordID );
        }

        // Only store relation to new keywords
        // Store relations to keyword for this content object
        foreach ( $existingWords as $keywordArray )
        {
            $db->query( "DELETE from ezkeyword_attribute_link WHERE keyword_id='" . $keywordArray['id'] . "' AND objectattribute_id='" . $attribute->attribute( 'id' ) ."' " );
            $db->query( "INSERT INTO ezkeyword_attribute_link ( keyword_id, objectattribute_id ) VALUES ( '" . $keywordArray['id'] ."', '" . $attribute->attribute( 'id' ) . "' )" );
        }
    }

    /*!
     Sets the keyword index
    */
    function setKeywordArray( $keywords )
    {
        $this->KeywordArray =& $keywords;
    }

    /*!
     Returns the keyword index
    */
    function &keywordArray( )
    {
        return $this->KeywordArray;
    }

    /// Contains the keywords
    var $KeywordArray = array();
}

?>

