<?php
//
// Definition of eZKeyword class
//
// Created on: <29-Apr-2003 15:18:15 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

    function hasAttribute( $name )
    {
        if ( $name == 'keywords' or
             $name == 'keyword_string' or
             $name == 'related_objects' )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function &attribute( $name )
    {
        switch ( $name )
        {
            case 'keywords' :
            {
                return $this->KeywordArray;
            }break;

            case 'keyword_string' :
            {
                return $this->keywordString();
            }break;

            case 'related_objects' :
            {
                return $this->relatedObjects();
            }break;
        }
    }

    /*!
     Initialze the keyword index
    */
    function initializeKeyword( $keywordString )
    {
	if ( !is_array( $keywordString ) )
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
        $wordsString = $db->escapeString( $wordsString );
        $existingWords =& $db->arrayQuery( "SELECT * FROM ezkeyword WHERE keyword IN ( '$wordsString' ) AND class_id='$classID' " );

        $newWordArray = array();
        $existingWordArray = array();
        // Find out which words to store
        foreach ( $this->KeywordArray as $keyword )
        {
            $wordExists = false;
            $wordID = false;
            foreach ( $existingWords as $existingKeyword )
            {
                if ( $keyword == $existingKeyword['keyword'] )
                {
                     $wordExists = true;
                     $wordID = $existingKeyword['id'];
                     break;
                }
            }

            if ( $wordExists == false )
            {
                $newWordArray[] = $keyword;
            }
            else
            {
                $existingWordArray[] = array( 'keyword' => $keyword, 'id' => $wordID );
            }
        }

        // Store every new keyword
        $addRelationWordArray = array();
        foreach ( $newWordArray as $keyword )
        {
            $keyword = trim( $keyword );
            $keyword = $db->escapeString( $keyword );
            $db->query( "INSERT INTO ezkeyword ( keyword, class_id ) VALUES ( '$keyword', '$classID' )" );

            $keywordID = $db->lastSerialID( 'ezkeyword' );
            $addRelationWordArray[] = array( 'keyword' => $keywordID, 'id' => $keywordID );
        }

        $attributeID = $attribute->attribute( 'id' );
        // Find the words which is new for this attribute
        $currentWordArray =& $db->arrayQuery( "SELECT ezkeyword.id, ezkeyword.keyword FROM ezkeyword, ezkeyword_attribute_link
                                               WHERE ezkeyword.id=ezkeyword_attribute_link.keyword_id
                                               AND ezkeyword_attribute_link.objectattribute_id='$attributeID'" );

        foreach ( $existingWordArray as $existingWord )
        {
            $newWord = true;
            foreach ( $currentWordArray as $currentWord )
            {
                if ( $existingWord['keyword']  == $currentWord['keyword'] )
                {
                    $newWord = false;
                }
            }

            if ( $newWord == true )
            {
                $addRelationWordArray[] = $existingWord;
            }
        }

        // Find the current words no longer used
        $removeWordRelationIDArray = array();
        foreach ( $currentWordArray as $currentWord )
        {
            $stillUsed = false;
            foreach ( $this->KeywordArray as $keyword )
            {
                if ( $keyword == $currentWord['keyword'] )
                    $stillUsed = true;
            }
            if ( !$stillUsed )
            {
                $removeWordRelationIDArray[] = $currentWord['id'];
            }
        }

        if ( count( $removeWordRelationIDArray ) > 0 )
        {
            $removeIDString = implode( ', ', $removeWordRelationIDArray );
            $db->query( "DELETE FROM ezkeyword_attribute_link WHERE keyword_id IN ( $removeIDString ) AND  ezkeyword_attribute_link.objectattribute_id='$attributeID'" );
        }

        // Only store relation to new keywords
        // Store relations to keyword for this content object
        foreach ( $addRelationWordArray as $keywordArray )
        {
            $db->query( "INSERT INTO ezkeyword_attribute_link ( keyword_id, objectattribute_id ) VALUES ( '" . $keywordArray['id'] ."', '" . $attribute->attribute( 'id' ) . "' )" );
        }

        // Clean up no longer used words
    }

    /*!
     Fetches the keywords for the given attribute.
    */
    function fetch( &$attribute )
    {
        $db =& eZDB::instance();
        $wordArray =& $db->arrayQuery( "SELECT ezkeyword.keyword FROM ezkeyword_attribute_link, ezkeyword
                                    WHERE ezkeyword_attribute_link.keyword_id=ezkeyword.id AND
                                    ezkeyword_attribute_link.objectattribute_id='" . $attribute->attribute( 'id' ) ."' " );

        $this->ObjectAttributeID = $attribute->attribute( 'id' );
        foreach ( array_keys( $wordArray ) as $wordKey )
        {
            $this->KeywordArray[] = $wordArray[$wordKey]['keyword'];
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

    /*!
     Returns the keywords as a string
    */
    function &keywordString()
    {
        return implode( ', ', $this->KeywordArray );
    }

    /*!
     Returns the objects which has atleast one of the same keywords
    */
    function &relatedObjects()
    {
        $return = false;
        if ( $this->ObjectAttributeID )
        {
            // Fetch words
            $db =& eZDB::instance();

            $wordArray =& $db->arrayQuery( "SELECT * FROM ezkeyword_attribute_link
                                    WHERE objectattribute_id='" . $this->ObjectAttributeID ."' " );

            $keywordIDArray = array();
            // Fetch the objects which have one of these words
            foreach ( $wordArray as $word )
            {
                $keywordIDArray[] = $word['keyword_id'];
            }

            $keywordString = implode( ", ", $keywordIDArray );

            if ( count( $keywordIDArray ) > 0 )
            {
                $objectArray =& $db->arrayQuery( "SELECT DISTINCT ezcontentobject_attribute.contentobject_id FROM ezkeyword_attribute_link, ezcontentobject_attribute
                                                  WHERE keyword_id IN ( $keywordString ) AND
                                                        ezcontentobject_attribute.id = ezkeyword_attribute_link.objectattribute_id
                                                        AND  objectattribute_id <> '" . $this->ObjectAttributeID ."' " );

                $objectIDArray = array();
                foreach ( $objectArray as $object )
                {
                    $objectIDArray[] = $object['contentobject_id'];
                }

		$aNodes =& eZContentObjectTreeNode::findMainNodeArray( $objectIDArray );		
		foreach ( $aNodes as $key => $node )
		{
		    $theObject = $node->object();
		    if ( $theObject->canRead() )
		    {
			$return[] = $node;
		    }
		}
	    }
        }
        return $return;
    }

    /// Contains the keywords
    var $KeywordArray = array();

    /// Contains the ID attribute if fetched
    var $ObjectAttributeID = false;
}

?>
