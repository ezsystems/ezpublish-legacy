<?php
//
// Definition of eZSearchEngine class
//
// Created on: <25-Jun-2002 13:09:57 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
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
  \class eZSearchEngine ezsearch.php

*/

include_once( "lib/ezdb/classes/ezdb.php" );

class eZSearchEngine
{
    /*!
     */
    function eZSearchEngine()
    {

    }

    /*!
     Adds an object to the search database.
    */
    function addObject( &$contentObject, $uri )
    {
        $db =& eZDB::instance();

        $contentObjectID = $contentObject->attribute( 'id' );

        $currentVersion =& $contentObject->currentVersion();

        $indexArray = array();
        foreach ( $currentVersion->attributes() as $attribute )
        {
            $classAttribute =& $attribute->contentClassAttribute();
            if ( $classAttribute->attribute( "is_searchable" ) == 1 )
            {
                // strip tags
                $text = strip_tags( $attribute->metaData() );

                // Strip multiple whitespaces
                $text = str_replace(".", " ", $text );
                $text = str_replace(":", " ", $text );
                $text = str_replace(",", " ", $text );
                $text = str_replace(";", " ", $text );
                $text = str_replace("'", " ", $text );
                $text = str_replace("\"", " ", $text );
                $text = str_replace("(", " ", $text );
                $text = str_replace(")", " ", $text );
                $text = str_replace("-", " ", $text );
                $text = str_replace("!", " ", $text );
                $text = str_replace("?", " ", $text );
                $text = str_replace("$", " ", $text );

                $text = str_replace("\n", " ", $text );
                $text = str_replace("\r", " ", $text );
                $text = preg_replace("(\s+)", " ", $text );

                // Split text on whitespace
                $wordArray =& split( " ", $text );

                foreach ( $wordArray as $word )
                {
                    if ( trim( $word ) != "" )
                    {
                        $indexArray[] = array( "Word" => $word,
                                               "ContentClassAttributeID" => $attribute->attribute( 'contentclassattribute_id' ) );
                    }
                }
            }
        }

        // Count the total words in index text
        $totalWordCount = count( $indexArray );

        /* // Needs to be rewritten

        // Count the number of instances of each word
        $wordCountArray = array_count_values( $indexArray );

        // Strip double words
        $uniqueIndexArray = array_unique( $indexArray );

        // Count unique words
        $uniqueWordCount = count( $uniqueIndexArray );
        */

        $wordIDArray = array();
        // store the words in the index and remember the ID
        foreach ( $indexArray as $indexWord )
        {
            $indexWord = strToLower( $indexWord['Word'] );

            // Store word if it does not exist.
            $wordRes = array();

            $wordRes =& $db->arrayQuery( "SELECT * FROM ezsearch_word WHERE word='$indexWord'" );

            if ( count( $wordRes ) == 1 )
            {
                $wordID = $wordRes[0]["id"];
                $db->query( "UPDATE ezsearch_word SET object_count=( object_count + 1 ) WHERE id='$wordID'" );
            }
            else
            {
                $db->query( "INSERT INTO
                               ezsearch_word ( word, object_count )
                             VALUES ( '$indexWord', '1' )" );
                $wordID = $db->lastSerialID( "ezsearch_word", "id" );
            }

            $wordIDArray[$indexWord] = $wordID;
        }

        $placement = 0;
        $prevWordID = 0;
        $nextWordID = 0;
        $classID = $contentObject->attribute( 'contentclass_id' );
        for ( $i = 0; $i < count( $indexArray ); $i++ )
        {
            $indexWord = $indexArray[$i]['Word'];
            $contentClassAttributeID = $indexArray[$i]['ContentClassAttributeID'];
            $indexWord = strToLower( $indexWord );
            $wordID = $wordIDArray[$indexWord];

            if ( isset( $indexArray[$i+1] ) )
            {
                $nextIndexWord = strToLower( $indexArray[$i+1]['Word'] );
                $nextWordID = $wordIDArray[$nextIndexWord];
            }
            else
                $nextWordID = 0;

            print( "indexing word : $indexWord <br> " );

            // Calculate the relevans ranking for this word
//            $frequency = ( $wordCountArray[$indexWord] / $totalWordCount );
            $frequency = 0;

            $db->query( "INSERT INTO
                       ezsearch_object_word_link ( word_id, contentobject_id, frequency, placement, next_word_id, prev_word_id, contentclass_id, contentclass_attribute_id )
                     VALUES ( '$wordID', '$contentObjectID', '$frequency', '$placement', '$nextWordID', '$prevWordID', '$classID', '$contentClassAttributeID' )" );

            $prevWordID = $wordID;
            $placement++;
        }
    }

    /*!
     \static
    */
    function removeObject( $contentObject )
    {
        $db =& eZDB::instance();
        $objectID = $contentObject->attribute( "id" );

        // fetch all the words and decrease the object count on all the words
        $wordArray =& $db->arrayQuery( "SELECT word_id FROM ezsearch_object_word_link WHERE contentobject_id='$objectID'" );

        foreach ( $wordArray as $word )
        {
            $db->query( "UPDATE ezsearch_word SET object_count=( object_count - 1 ) WHERE id='" . $word["word_id"] . "'" );
        }

        $db->query( "DELETE FROM ezsearch_word WHERE object_count='0'" );
        $db->query( "DELETE FROM ezsearch_object_word_link WHERE contentobject_id='$objectID'" );
    }

    /*!
     \static
     Runs a query to the search engine.
    */
    function &search( $searchText, $params = array() )
    {
        if ( trim( $searchText ) != "" )
        {
            $db =& eZDB::instance();

            $nonExistingWordArray = array();

            if ( isset( $params['SearchContentClassID'] ) )
                $searchContentClassID = $params['SearchContentClassID'];
            else
                $searchContentClassID = -1;

            if ( isset( $params['SearchContentClassAttributeID'] ) )
                $searchContentClassAttributeID = $params['SearchContentClassAttributeID'];
            else
                $searchContentClassAttributeID = -1;

            // strip multiple spaces
            $searchText = preg_replace( "(\s+)", " ", $searchText );

            // find the phrases
            $numQuotes = substr_count( $searchText, "\"" );

            $phraseTextArray = array();
            $fullText = $searchText;
            $pos = 0;
            if ( ( $numQuotes > 0 ) and ( ( $numQuotes % 2 ) == 0 ) )
            {
                for ( $i = 0; $i < ( $numQuotes / 2 ); $i ++ )
                {
                    $quotePosStart = strpos( $searchText, '"',  $pos );
                    $quotePosEnd = strpos( $searchText, '"',  $quotePosStart + 1 );

                    $prePhraseText =& substr( $searchText, $pos, $quotePosStart - $pos );
                    $phraseText =& substr( $searchText, $quotePosStart + 1, $quotePosEnd - $quotePosStart - 1 );

                    $phraseTextArray[] = $phraseText;
                    $fullText .= $prePhraseText;
                    $pos = $quotePosEnd + 1;
                }
            }

            // Get the total number of objects
            $objectCount = array();
            $objectCount =& $db->arrayQuery( "SELECT COUNT(*) AS count FROM ezcontentobject" );
            $totalObjectCount = $objectCount[0]["count"];

            $searchWordArray = $this->splitString( $searchText );

            // fetch the word id
            $i = 0;
            $wordQueryString = '';
            foreach ( $searchWordArray as $searchWord )
            {
                if ( $i > 0 )
                    $wordQueryString .= " or ";

                $wordQueryString .= " word='$searchWord' ";
                $i++;
            }

            $wordIDArrayRes =& $db->arrayQuery( "SELECT id, word FROM ezsearch_word where $wordQueryString" );

            // get the words in the correct order
            $wordIDArray = array();
            $wordIDHash = array();
            foreach ( $wordIDArrayRes as $wordRes )
            {
                $wordIDArray[] = $wordRes['id'];
                $wordIDHash[$wordRes['word']] = $wordRes['id'];
            }

            // build an array of the word id's for each phrase
            $phraseIDArrayArray = array();
            foreach ( $phraseTextArray as $phraseText )
            {
                $wordArray =& $this->splitString( $phraseText );
                $wordIDArray = array();
                foreach ( $wordArray as $word )
                {
                    $wordIDArray[] = $wordIDHash[$word];
                }
                $phraseIDArrayArray[] = $wordIDArray;
            }

            // build phrase SQL query part(s)
            $phraseSearchSQLArray = array();
            $phraseSQL = "";
            foreach ( $phraseIDArrayArray as $phraseIDArray )
            {
                $phraseSearchSQL = '';
                $wordCount = count( $phraseIDArray );
                for ( $i = 0; $i < $wordCount; $i++ )
                {
                    $wordID = $phraseIDArray[$i];

                    if ( is_numeric( $wordID ) and ( $wordID > 0 ) )
                    {
                        $phraseSearchSQL .= " ( ezsearch_object_word_link.word_id='$wordID' ";
                        if ( $i < ( $wordCount - 1 ) )
                        {
                            $nextWordID = $phraseIDArray[$i+1];
                            $phraseSearchSQL .= " AND ezsearch_object_word_link.next_word_id='$nextWordID' ";
                        }

                        if ( $i > 0 )
                        {
                            $prevWordID = $phraseIDArray[$i-1];
                            $phraseSearchSQL .= " AND ezsearch_object_word_link.prev_word_id='$prevWordID' ";
                        }

                        $phraseSearchSQL .= "  ) ";

                        if ( $i < ( $wordCount - 1 ) )
                            $phraseSearchSQL .= " OR ";
                    }
                    else
                    {
                        $nonExistingWordArray[] = $searchWord;
                    }

                    $prevWord = $wordID;
                }
                $phraseSearchSQLArray[] = $phraseSearchSQL;
                $phraseSQL .= "( $phraseSearchSQL ) AND ";
            }

            // build fulltext search SQL part
            $searchWordArray =& $this->splitString( $fullText );
            $fullTextSQL = "";
            if ( count( $searchWordArray ) > 0 )
            {
                $i = 0;
                // Build the word query string
                foreach ( $searchWordArray as $searchWord )
                {
                    $wordID = $wordIDHash[$searchWord];

                    if ( is_numeric( $wordID ) and ( $wordID > 0 ) )
                    {
                        if ( $i == 0 )
                            $fullTextSQL .= "ezsearch_object_word_link.word_id='$wordID' ";
                        else
                            $fullTextSQL .= " OR ezsearch_object_word_link.word_id='$wordID' ";
                    }
                    else
                    {
                        $nonExistingWordArray[] = $searchWord;
                    }
                    $i++;
                }
                $fullTextSQL = " ( $fullTextSQL ) AND ";
            }

            $classQuery = "";
            if ( is_numeric( $searchContentClassID ) and  $searchContentClassID > 0 )
            {
                $classQuery = "ezsearch_object_word_link.contentclass_id = '$searchContentClassID' AND ";
            }

            $classAttributeQuery = "";
            if ( is_numeric( $searchContentClassAttributeID ) and  $searchContentClassAttributeID > 0 )
            {
                $classAttributeQuery = "ezsearch_object_word_link.contentclass_attribute_id = '$searchContentClassAttributeID' AND ";
            }

            $searchQuery = "SELECT DISTINCT ezcontentobject.id, ezcontentobject.*, ezsearch_object_word_link.frequency
                    FROM
                       ezcontentobject,
                       ezsearch_object_word_link
                    WHERE
                    $classQuery
                    $classAttributeQuery
                    $phraseSQL
                    $fullTextSQL
                    ezcontentobject.id=ezsearch_object_word_link.contentobject_id
                    ORDER BY ezsearch_object_word_link.frequency";

            $searchCountQuery = "SELECT count( DISTINCT ezcontentobject.id ) as count
                    FROM
                       ezcontentobject,
                       ezsearch_object_word_link
                    WHERE
                    $classQuery
                    $classAttributeQuery
                    $phraseSQL
                    $fullTextSQL
                    ezcontentobject.id=ezsearch_object_word_link.contentobject_id";

            $objectRes = array();

            $searchCount = 0;
            if ( count( $nonExistingWordArray ) == 0 )
            {
                // execute search query
                $objectResArray =& $db->arrayQuery( $searchQuery );
                // execute search count query
                $objectCountRes =& $db->arrayQuery( $searchCountQuery );

                foreach ( $objectResArray as $objectRow )
                {
                    $objectRes[] = new eZContentObject( $objectRow );
                }
                $searchCount = $objectCountRes[0]['count'];
            }
            else
                $objectRes = array();

            return array( "SearchResult" => $objectRes,
                          "SearchCount" => $searchCount );
        }
        else
        {
            return array( "SearchResult" => array(),
                          "SearchCount" => 0 );
        }
    }

    /*!
     \private
     \return Returns an array of words created from the input string.
    */
    function &splitString( $text )
    {
        // strip quotes
        $text = preg_replace("#'#", "", $text );
        $text = preg_replace( "#\"#", "", $text );

        // Strip multiple whitespace
        $text = trim( $text );
        $text = preg_replace("(\s+)", " ", $text );

        // Split text on whitespace
        $wordArray =& split( " ", $text );

        $retArray = array();
        foreach ( $wordArray as $word )
        {
            if ( trim( $word ) != "" )
            {
                $retArray[] = trim( $word );
            }
        }

        return $retArray;
    }
}

?>
