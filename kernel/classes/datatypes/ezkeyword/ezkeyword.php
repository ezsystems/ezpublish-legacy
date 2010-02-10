<?php
//
// Definition of eZKeyword class
//
// Created on: <29-Apr-2003 15:18:15 bf>
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

/*!
  \class eZKeyword ezkeyword.php
  \ingroup eZDatatype
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

    function attributes()
    {
        return array( 'keywords',
                      'keyword_string',
                      'related_objects',
                      'related_nodes' );
    }

    function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

    function attribute( $name )
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
            case 'related_nodes' :
            {
                return $this->relatedObjects();
            }break;
            default:
            {
                eZDebug::writeError( "Attribute '$name' does not exist", 'eZKeyword::attribute' );
                return null;
            }break;
        }
    }

    /*!
     Initialze the keyword index
    */
    function initializeKeyword( $keywordString )
    {
        if ( !is_array( $keywordString ) )
        {
            $keywordArray = explode( ',', $keywordString );
            $keywordArray = array_unique ( $keywordArray );
        }
        foreach ( array_keys( $keywordArray ) as $key )
        {
            if ( trim( $keywordArray[$key] ) != '' )
            {
                $this->KeywordArray[$key] = trim( $keywordArray[$key] );
            }
        }
    }

    /*!
     Stores the keyword index to database
    */
    function store( $attribute )
    {
        $db = eZDB::instance();

        $object = $attribute->attribute( 'object' );
        $classID = $object->attribute( 'contentclass_id' );

        // Get already existing keywords
        if ( count( $this->KeywordArray ) > 0 )
        {
            $escapedKeywordArray = array();
            foreach( $this->KeywordArray as $keyword )
            {
                $keyword = $db->escapeString( $keyword );
                $escapedKeywordArray[] = $keyword;
            }
            $wordsString = implode( '\',\'', $escapedKeywordArray );
            $existingWords = $db->arrayQuery( "SELECT * FROM ezkeyword WHERE keyword IN ( '$wordsString' ) AND class_id='$classID' " );
        }
        else
        {
            $existingWords = array();
        }

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

            $keywordID = $db->lastSerialID( 'ezkeyword', 'id' );
            $addRelationWordArray[] = array( 'keyword' => $keywordID, 'id' => $keywordID );
        }

        $attributeID = $attribute->attribute( 'id' );
        // Find the words which is new for this attribute
        if ( $attributeID !== null )
        {
            $currentWordArray = $db->arrayQuery( "SELECT ezkeyword.id, ezkeyword.keyword FROM ezkeyword, ezkeyword_attribute_link
                                                   WHERE ezkeyword.id=ezkeyword_attribute_link.keyword_id
                                                   AND ezkeyword_attribute_link.objectattribute_id='$attributeID'" );
        }
        else
            $currentWordArray = array();

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

        /* Clean up no longer used words:
         * 1. Select words having no links.
         * 2. Delete them.
         * We cannot do this in one cross-table DELETE since older MySQL versions do not support this.
         */
        if ( $db->databaseName() == 'oracle' )
        {
            $query =
                'SELECT ezkeyword.id FROM ezkeyword, ezkeyword_attribute_link ' .
                'WHERE ezkeyword.id=ezkeyword_attribute_link.keyword_id(+) AND ' .
                'ezkeyword_attribute_link.keyword_id IS NULL';
        }
        else
        {
            $query =
                'SELECT ezkeyword.id FROM ezkeyword LEFT JOIN ezkeyword_attribute_link ' .
                ' ON ezkeyword.id=ezkeyword_attribute_link.keyword_id' .
                ' WHERE ezkeyword_attribute_link.keyword_id IS NULL';
        }
        $unusedWordsIDs = $db->arrayQuery( $query );
        foreach ( $unusedWordsIDs as $wordID )
            $db->query( 'DELETE FROM ezkeyword WHERE id=' . $wordID['id'] );
    }

    /*!
     Fetches the keywords for the given attribute.
    */
    function fetch( &$attribute )
    {
        if ( $attribute->attribute( 'id' ) === null )
            return;

        $db = eZDB::instance();
        $wordArray = $db->arrayQuery( "SELECT ezkeyword.keyword FROM ezkeyword_attribute_link, ezkeyword
                                    WHERE ezkeyword_attribute_link.keyword_id=ezkeyword.id AND
                                    ezkeyword_attribute_link.objectattribute_id='" . $attribute->attribute( 'id' ) ."' " );

        $this->ObjectAttributeID = $attribute->attribute( 'id' );
        foreach ( array_keys( $wordArray ) as $wordKey )
        {
            $this->KeywordArray[] = $wordArray[$wordKey]['keyword'];
        }
        $this->KeywordArray = array_unique ( $this->KeywordArray );
    }

    /*!
     Sets the keyword index
    */
    function setKeywordArray( $keywords )
    {
        $this->KeywordArray = $keywords;
    }

    /*!
     Returns the keyword index
    */
    function keywordArray( )
    {
        return $this->KeywordArray;
    }

    /*!
     Returns the keywords as a string
    */
    function keywordString()
    {
        return implode( ', ', $this->KeywordArray );
    }

    /*!
     Returns the objects which have at least one keyword in common

     \return an array of eZContentObjectTreeNode instances, or null if the attribute is not stored yet
    */
    function relatedObjects()
    {
        $return = false;
        if ( $this->ObjectAttributeID )
        {
            $return = array();

            // Fetch words
            $db = eZDB::instance();

            $wordArray = $db->arrayQuery( "SELECT * FROM ezkeyword_attribute_link
                                           WHERE objectattribute_id='" . $this->ObjectAttributeID ."' " );

            $keywordIDArray = array();
            // Fetch the objects which have one of these words
            foreach ( $wordArray as $word )
            {
                $keywordIDArray[] = $word['keyword_id'];
            }

            $keywordCondition = $db->generateSQLINStatement( $keywordIDArray, 'keyword_id' );

            if ( count( $keywordIDArray ) > 0 )
            {
                $objectArray = $db->arrayQuery( "SELECT DISTINCT ezcontentobject_attribute.contentobject_id FROM ezkeyword_attribute_link, ezcontentobject_attribute
                                                  WHERE $keywordCondition AND
                                                        ezcontentobject_attribute.id = ezkeyword_attribute_link.objectattribute_id
                                                        AND  objectattribute_id <> '" . $this->ObjectAttributeID ."' " );

                $objectIDArray = array();
                foreach ( $objectArray as $object )
                {
                    $objectIDArray[] = $object['contentobject_id'];
                }

                if ( count( $objectIDArray ) > 0 )
                {
                    $aNodes = eZContentObjectTreeNode::findMainNodeArray( $objectIDArray );

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
        }
        return $return;
    }

    /// Contains the keywords
    public $KeywordArray = array();

    /// Contains the ID attribute if fetched
    public $ObjectAttributeID = false;
}

?>
