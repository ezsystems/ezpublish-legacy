<?php
//
// Definition of eZSearchEngine class
//
// Created on: <25-Jun-2002 13:09:57 bf>
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
include_once( "kernel/classes/ezcontentobject.php" );
include_once( "lib/ezlocale/classes/ezdatetime.php" );
include_once( "kernel/classes/ezcontentobject.php" );

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
        $indexArrayOnlyWords = array();

        foreach ( $currentVersion->contentObjectAttributes() as $attribute )
        {
            $classAttribute =& $attribute->contentClassAttribute();
            if ( $classAttribute->attribute( "is_searchable" ) == 1 )
            {
                // strip tags
                $text =& strip_tags( $attribute->metaData() );

                // Strip multiple whitespaces
                $text =& str_replace(".", " ", $text );
                $text =& str_replace(":", " ", $text );
                $text =& str_replace(",", " ", $text );
                $text =& str_replace(";", " ", $text );
                $text =& str_replace("'", " ", $text );
                $text =& str_replace("\"", " ", $text );
                $text =& str_replace("(", " ", $text );
                $text =& str_replace(")", " ", $text );
                $text =& str_replace("-", " ", $text );
                $text =& str_replace("!", " ", $text );
                $text =& str_replace("?", " ", $text );
                $text =& str_replace("$", " ", $text );

                $text =& str_replace("\n", " ", $text );
                $text =& str_replace("\r", " ", $text );
                $text =& preg_replace("(\s+)", " ", $text );
                $text =& strtolower( $text );

                // Split text on whitespace
                $wordArray =& split( " ", $text );

                foreach ( $wordArray as $word )
                {
                    if ( trim( $word ) != "" )
                    {
                        $indexArray[] = array( "Word" => $word,
                                               "ContentClassAttributeID" => $attribute->attribute( 'contentclassattribute_id' ) );
                        $indexArrayOnlyWords[] = $word;
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

        // Create an unique array
        $indexArrayOnlyWords = array_unique( $indexArrayOnlyWords );

        $wordIDArray = array();
        // store the words in the index and remember the ID
        $dbName = $db->databaseName();
        if ( $dbName == 'mysql' )
        {
            $wordArray = array();
            $wordsString = implode( '\',\'', $indexArrayOnlyWords );
            $wordRes =& $db->arrayQuery( "SELECT * FROM ezsearch_word WHERE word IN ( '$wordsString' ) " );
            $wordResCount = count( $wordRes );
            $wordIDArray = array();
            $existingWordArray = array();
            for ( $i = 0; $i < $wordResCount; $i++ )
            {
                $wordIDArray[] = $wordRes[$i]['id'];
                $existingWordArray[] = $wordRes[$i]['word'];
                $wordLowercase = strtolower( $wordRes[$i]['word'] );
                $wordArray[$wordLowercase] = $wordRes[$i]['id'];
            }

            $wordIDString = implode( ',', $wordIDArray );
            if ( count( $wordIDArray ) > 0 )
                $db->query( " UPDATE ezsearch_word SET object_count=( object_count + 1 ) WHERE id IN ( $wordIDString )" );

            if ( count( $indexArrayOnlyWords ) > $wordResCount )
            {
                eZDebugSetting::writeDebug( 'kernel-search-ezsearch', $indexArrayOnlyWords, 'indexArrayOnlyWords' );
                eZDebugSetting::writeDebug( 'kernel-search-ezsearch', $existingWordArray, "existingWordArray" );
                $newWordArray = array_diff( $indexArrayOnlyWords, $existingWordArray );
                $newWordString = implode( "', '1' ), ('", $newWordArray );
                $db->query( "INSERT INTO
                               ezsearch_word ( word, object_count )
                             VALUES ('$newWordString', '1' )" );
                $newWordString = implode( "','", $newWordArray );
                $newWordRes =& $db->arrayQuery( "select id,word from ezsearch_word where word in ( '$newWordString' ) " );
                $newWordCount = count( $newWordRes );
                for ( $i=0;$i<$newWordCount;++$i )
                {
                    $wordLowercase = strtolower( $newWordRes[$i]['word'] );
                    $wordArray[$wordLowercase] = $newWordRes[$i]['id'];
                }
            }
            $wordIDArray = $wordArray;
        }
        else
        {
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
        }
        $placement = 0;
        $prevWordID = 0;
        $nextWordID = 0;
        $classID = $contentObject->attribute( 'contentclass_id' );
        $sectionID = $contentObject->attribute( 'section_id' );
        $published = $contentObject->attribute( 'published' );
        $valuesStringList = array();
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
//            print( "indexing word : $indexWord <br> " );

            // Calculate the relevans ranking for this word
//            $frequency = ( $wordCountArray[$indexWord] / $totalWordCount );
            $frequency = 0;
            $valuesStringList[] = " ( '$wordID', '$contentObjectID', '$frequency', '$placement', '$nextWordID', '$prevWordID', '$classID', '$contentClassAttributeID', '$published', '$sectionID' ) ";

            $prevWordID = $wordID;
            $placement++;
        }

        if ( $dbName == 'mysql' )
        {
            if ( count( $valuesStringList ) > 0 )
            {
                $valuesString = implode( ',', $valuesStringList );
                $db->query( "INSERT INTO
                           ezsearch_object_word_link
                        ( word_id, contentobject_id, frequency, placement, next_word_id, prev_word_id, contentclass_id, contentclass_attribute_id, published, section_id )
                     VALUES $valuesString" );
            }
        }
        else
        {
            $db->begin();
            foreach( array_keys( $valuesStringList ) as $key )
            {
                $valuesString =& $valuesStringList[$key];
                $db->query("INSERT INTO
                           ezsearch_object_word_link
                           ( word_id,
                             contentobject_id,
                             frequency,
                             placement,
                             next_word_id,
                             prev_word_id,
                             contentclass_id,
                             contentclass_attribute_id,
                             published,
                             section_id )
                             VALUES $valuesString"  );
            }
            $db->commit();
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
        $wordIDList = array();
        foreach ( $wordArray as $word )
        {
            $wordIDList[] = $word["word_id"];
        }
        if ( count( $wordIDList ) > 0 )
        {
            $wordIDString = implode( ',', $wordIDList );
            if ( count( $wordIDList ) > 0 )
                $db->query( "UPDATE ezsearch_word SET object_count=( object_count - 1 ) WHERE id in ( $wordIDString )" );
            $db->query( "DELETE FROM ezsearch_word WHERE object_count='0'" );
            $db->query( "DELETE FROM ezsearch_object_word_link WHERE contentobject_id='$objectID'" );
        }
    }

    /*!
     \static
     Runs a query to the search engine.
    */
    function &search( $searchText, $params = array()  )
    {
        if ( trim( $searchText ) != "" )
        {
            $searchText =& $this->normalizeText( $searchText );
            $db =& eZDB::instance();

            $nonExistingWordArray = array();

            if ( isset( $params['SearchOffset'] ) )
                $searchOffset = $params['SearchOffset'];
            else
                $searchOffset = 0;

            if ( isset( $params['SearchLimit'] ) )
                $searchLimit = $params['SearchLimit'];
            else
                $searchLimit = 10;

            if ( isset( $params['SearchContentClassID'] ) )
                $searchContentClassID = $params['SearchContentClassID'];
            else
                $searchContentClassID = -1;

            if ( isset( $params['SearchSectionID'] ) )
                $searchSectionID = $params['SearchSectionID'];
            else
                $searchSectionID = -1;

            eZDebugSetting::writeDebug( 'kernel-search-ezsearch', 'searchSectionID=' . $searchSectionID, 'eZSearchEngine::search' );
            if ( isset( $params['SearchDate'] ) )
                $searchDate = $params['SearchDate'];
            else
                $searchDate = -1;

            if ( isset( $params['SearchContentClassAttributeID'] ) )
                $searchContentClassAttributeID = $params['SearchContentClassAttributeID'];
            else
                $searchContentClassAttributeID = -1;

            if ( isset( $params['SearchSubTreeArray'] ) )
                $subTreeArray = $params['SearchSubTreeArray'];
            else
                $subTreeArray = array();

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

            $sectionQuery = '';
            if ( is_numeric( $searchSectionID ) and  $searchSectionID > 0 )
            {
                $sectionQuery = "ezsearch_object_word_link.section_id = '$searchSectionID' AND ";
            }

            $searchDateQuery = '';
            if ( is_numeric( $searchDate ) and  $searchDate > 0 )
            {
                $date = new eZDateTime();
                $timestamp = $date->timeStamp();
                $day = $date->attribute('day');
                $month = $date->attribute('month');
                $year = $date->attribute('year');
                switch ( $searchDate )
                {
                    case 1:
                    {
                        $adjustment = 24*60*60; //seconds for one day
                        $publishedDate = $timestamp - $adjustment;
                    } break;
                    case 2:
                    {
                        $adjustment = 7*24*60*60; //seconds for one week
                        $publishedDate = $timestamp - $adjustment;
                    } break;
                    case 3:
                    {
                        $adjustment = 31*24*60*60; //seconds for one month
                        $publishedDate = $timestamp - $adjustment;
                    } break;
                    case 4:
                    {
                        $adjustment = 3*31*24*60*60; //seconds for three months
                        $publishedDate = $timestamp - $adjustment;
                    } break;
                    case 5:
                    {
                        $adjustment = 365*24*60*60;; //seconds for one year
                        $publishedDate = $timestamp - $adjustment;
                    } break;
                    default:
                    {
                        $publishedDate = $date->timeStamp();
                    }
                }
                $searchDateQuery = "ezsearch_object_word_link.published >= '$publishedDate' AND ";
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

            $searchWordArray = $this->splitString( $searchText );
            $tempTableCount = 1;
            if ( ( count( $searchWordArray ) > 1 ) and ( count( $phraseTextArray ) == 0 ) )
            {
                foreach ( $searchWordArray as $searchWord )
                {
                    $tableName = "temporary_table_".$tempTableCount;
                    $db->query( "CREATE TEMPORARY TABLE $tableName SELECT DISTINCT ezcontentobject.*,
                                                                 ezsearch_object_word_link.frequency
                                                 FROM ezcontentobject,ezsearch_object_word_link, ezsearch_word
                                                 WHERE $searchDateQuery $sectionQuery $classQuery $classAttributeQuery
                                                       word='$searchWord'
                                                      AND ezcontentobject.id=ezsearch_object_word_link.contentobject_id
                                                      AND ezsearch_word.id=ezsearch_object_word_link.word_id
                                                 ORDER BY ezsearch_object_word_link.frequency" );
                    $tempTableCount++;
                }
                $table = "temporary_table_1, ";
                $condition = "";
                for ( $i = 2; $i < $tempTableCount; $i++ )
                {
                    if ( $i == ( $tempTableCount - 1 ) )
                    {
                        $table .= "temporary_table_". $i ." ";
                        $condition .= " temporary_table_". $i .".id = temporary_table_1.id";
                    }else
                    {
                        $table .= "temporary_table_". $i .", ";
                        $condition .= "temporary_table_". $i .".id = temporary_table_1.id AND";
                    }
                }
                $searchCount = 0;
                $finalRes =& $db->arrayQuery( "SELECT * FROM $table WHERE $condition" );
                $countRes =& $db->arrayQuery( "SELECT count( DISTINCT temporary_table_1.id ) as count FROM $table WHERE $condition" );
                $searchCount = $countRes[0]['count'];
                for ( $i=1;$i<$tempTableCount;$i++ )
                {
                    $tableName = "temporary_table_".$i;
                    $db->query ("DROP TABLE $tableName " );
                }
                return array( "SearchResult" => $finalRes,
                              "SearchCount" => $searchCount );
            }

            // Get the total number of objects
            $objectCount = array();
            $objectCount =& $db->arrayQuery( "SELECT COUNT(*) AS count FROM ezcontentobject" );
            $totalObjectCount = $objectCount[0]["count"];

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
                    $wordID = null;
                    if ( isset( $wordIDHash[$searchWord] ) )
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

            // Search only in specific sub trees
            $subTreeSQL = "";
            $subTreeTable = "";
            if ( count( $subTreeArray ) > 0 )
            {
                // Fetch path_string value to use when searching subtrees
                $i = 0;
                $doSubTreeSearch = false;
                $subTreeNodeSQL = '';
                foreach ( $subTreeArray as $nodeID )
                {
                    if ( is_numeric( $nodeID ) and ( $nodeID > 0 ) )
                    {
                        $subTreeNodeSQL .= " $nodeID";

                        if ( isset( $subTreeArray[$i + 1] ) and
                             is_numeric( $subTreeArray[$i + 1] ) )
                            $subTreeNodeSQL .= ", ";

                        $doSubTreeSearch = true;
                    }
                    $i++;
                }

                if ( $doSubTreeSearch == true )
                {

                    $subTreeNodeSQL = "( " . $subTreeNodeSQL;
//                    $subTreeTable = ", ezcontentobject_tree ";
                    $subTreeTable = '';
                    $subTreeNodeSQL .= " ) ";
                    $nodeQuery = "SELECT node_id, path_string FROM ezcontentobject_tree WHERE node_id IN $subTreeNodeSQL";

                    // Build SQL subtre search query
                    $subTreeSQL = " ( ";

                    $nodeArray =& $db->arrayQuery( $nodeQuery );
                    $i = 0;
                    foreach ( $nodeArray as $node )
                    {
                        $pathString = $node['path_string'];

                        $subStringString = $db->subString( 'ezcontentobject_tree.path_string', 1, strlen( $pathString ) );

                        $subTreeSQL .= " $subStringString = '$pathString' ";

                        if ( $i < ( count( $nodeArray ) -1 ) )
                            $subTreeSQL .= " OR ";
                        $i++;
                    }
                    $subTreeSQL .= " ) AND ";
                }
            }

            $limitationList = array();
            if ( isset( $params['Limitation'] ) )
            {
                $limitationList =& $params['Limitation'];
            }
            else if ( isset( $GLOBALS['ezpolicylimitation_list'] ) )
            {
                $policyList =& $GLOBALS['ezpolicylimitation_list'];
                $limitationList = array();
                foreach( array_keys( $policyList ) as $key )
                {
                    $policy =& $policyList[$key];
                    $limitationList[] =& $policy->attribute( 'limitations' );
                }
            }

            $sqlPermissionCheckingString = '';
            if ( count( $limitationList ) > 0 )
            {
                $sqlParts = array();
                foreach( $limitationList as $limitationArray )
                {
                    $sqlPartPart = array();
                    foreach ( $limitationArray as $limitation )
                    {
                        if ( $limitation->attribute( 'identifier' ) == 'Class' )
                        {
                            $sqlPartPart[] = 'ezcontentobject.contentclass_id in (' . $limitation->attribute( 'values_as_string' ) . ')';
                        }
                        elseif ( $limitation->attribute( 'identifier' ) == 'Section' )
                        {
                            $sqlPartPart[] = 'ezcontentobject.section_id in (' . $limitation->attribute( 'values_as_string' ) . ')';
                        }
                        elseif( $limitation->attribute( 'name' ) == 'Owner' )
                        {
                            eZDebug::writeWarning( $limitation, 'System is not configured to check Assigned in search objects' );
                        }
                    }
                    $sqlParts[] = implode( ' AND ', $sqlPartPart );
                }
                $sqlPermissionCheckingString = ' AND ((' . implode( ') or (', $sqlParts ) . ')) ';
            }


            $searchQuery = "SELECT DISTINCT ezcontentobject.*, ezsearch_object_word_link.frequency, ezcontentclass.name as class_name, ezcontentobject_tree.*
                    FROM
                       ezcontentobject,
                       ezsearch_object_word_link
                       $subTreeTable,
                       ezcontentclass,
                       ezcontentobject_tree
                    WHERE
                    $searchDateQuery
                    $sectionQuery
                    $classQuery
                    $classAttributeQuery
                    $phraseSQL
                    $fullTextSQL
                    $subTreeSQL
                    ezcontentobject.id=ezsearch_object_word_link.contentobject_id and
                    ezcontentobject.contentclass_id = ezcontentclass.id and
                    ezcontentclass.version = '0' and
                    ezcontentobject.id = ezcontentobject_tree.contentobject_id and
                    ezcontentobject_tree.node_id = ezcontentobject_tree.main_node_id
                    $sqlPermissionCheckingString
                    ORDER BY ezsearch_object_word_link.frequency";

            $searchCountQuery = "SELECT count( DISTINCT ezcontentobject.id ) as count
                    FROM
                       ezcontentobject,
                       ezsearch_object_word_link
                       $subTreeTable,
                       ezcontentobject_tree
                    WHERE
                    $searchDateQuery
                    $sectionQuery
                    $classQuery
                    $classAttributeQuery
                    $phraseSQL
                    $fullTextSQL
                    $subTreeSQL
                    ezcontentobject.id=ezsearch_object_word_link.contentobject_id
                    and
                    ezcontentobject.id = ezcontentobject_tree.contentobject_id and
                    ezcontentobject_tree.node_id = ezcontentobject_tree.main_node_id
                    $sqlPermissionCheckingString";

            $objectRes = array();

            $searchCount = 0;
            if ( count( $nonExistingWordArray ) == 0 )
            {
                // execute search query
                $objectResArray =& $db->arrayQuery( $searchQuery, array( "limit" => $searchLimit, "offset" => $searchOffset ) );
                // execute search count query
                $objectCountRes =& $db->arrayQuery( $searchCountQuery );
                $objectRes = eZContentObjectTreeNode::makeObjectsArray( $objectResArray );
/*                foreach ( $objectResArray as $objectRow )
                {
                    /// \todo optimize to one query
                    $obj = new eZContentObject( $objectRow );
                    $obj->setClassName( $objectRow['class_name'] );
                    unset( $node );
                    $node = eZContentObjectTreeNode::fetch( $obj->attribute( 'main_node_id' ) );
                    $objectRes[] =& $node;
                }
*/
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

    function &normalizeText( $text )
    {
        $text =& strToLower( $text );

        // Strip multiple whitespaces
        $text = str_replace(".", " ", $text );
        $text = str_replace(":", " ", $text );
        $text = str_replace(",", " ", $text );
        $text = str_replace(";", " ", $text );
        $text = str_replace("'", " ", $text );
        $text = str_replace("(", " ", $text );
        $text = str_replace(")", " ", $text );
        $text = str_replace("-", " ", $text );
        $text = str_replace("!", " ", $text );
        $text = str_replace("?", " ", $text );
        $text = str_replace("$", " ", $text );

        $text = str_replace("\n", " ", $text );
        $text = str_replace("\r", " ", $text );
        $text = preg_replace("(\s+)", " ", $text );

        return $text;
    }
}

?>
