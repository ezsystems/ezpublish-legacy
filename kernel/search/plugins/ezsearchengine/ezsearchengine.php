<?php
/**
 * File containing the eZSearchEngine class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZSearchEngine ezsearch.php

*/

class eZSearchEngine implements ezpSearchEngine
{
    public $GeneralFilter;
    public function __construct()
    {
        $generalFilter = array( 'subTreeTable' => '',
                                'searchDateQuery' => '',
                                'sectionQuery' => '',
                                'classQuery' => '',
                                'classAttributeQuery' => '',
                                'searchPartText' => '',
                                'subTreeSQL' => '',
                                'sqlPermissionChecking' => array( 'from' => '',
                                                                  'where' => '' ) );
        $this->GeneralFilter = $generalFilter;
    }


    public function needCommit()
    {
        //commits are NA
        return false;
    }

    public function needRemoveWithUpdate()
    {
        return true;
    }

    /**
     * Adds object $contentObject to the search database.
     *
     * @param eZContentObject $contentObject Object to add to search engine
     * @param bool $commit Whether to commit after adding the object
     * @return bool True if the operation succeed.
     */
    public function addObject( $contentObject, $commit = true )
    {
        $contentObjectID = $contentObject->attribute( 'id' );
        $currentVersion = $contentObject->currentVersion();

        if ( !$currentVersion )
        {
            $errCurrentVersion = $contentObject->attribute( 'current_version');
            eZDebug::writeError( "Failed to fetch \"current version\" ({$errCurrentVersion})" .
                                 " of content object (ID: {$contentObjectID})", 'eZSearchEngine' );
            return false;
        }

        $indexArray = array();
        $indexArrayOnlyWords = array();

        $wordCount = 0;
        $placement = 0;
        $previousWord = '';

        eZContentObject::recursionProtectionStart();
        foreach ( $currentVersion->contentObjectAttributes() as $attribute )
        {
            $metaData = array();
            $classAttribute = $attribute->contentClassAttribute();
            if ( $classAttribute->attribute( "is_searchable" ) == 1 )
            {
                // Fetch attribute translations
                $attributeTranslations = $attribute->fetchAttributeTranslations();

                foreach ( $attributeTranslations as $translation )
                {
                    $tmpMetaData = $translation->metaData();
                    if( ! is_array( $tmpMetaData ) )
                    {
                        $tmpMetaData = array( array( 'id' => $attribute->attribute( 'contentclass_attribute_identifier' ),
                                                     'text' => $tmpMetaData ) );
                    }
                    $metaData = array_merge( $metaData, $tmpMetaData );
                }

                foreach( $metaData as $metaDataPart )
                {
                    $text = eZSearchEngine::normalizeText( htmlspecialchars ($metaDataPart['text'], ENT_NOQUOTES, 'UTF-8' ) , true );

                    // Split text on whitespace
                    if ( is_numeric( trim( $text ) ) )
                    {
                        $integerValue = (int) $text;
                    }
                    else
                    {
                        $integerValue = 0;
                    }
                    $wordArray = explode( ' ', $text );

                    foreach ( $wordArray as $word )
                    {
                        if ( trim( $word ) != "" )
                        {
                            // words stored in search index are limited to 150 characters
                            if ( strlen( $word ) > 150 )
                            {
                                $word = substr( $word, 0, 150 );
                            }
                            $indexArray[] = array( 'Word' => $word,
                                                   'ContentClassAttributeID' => $attribute->attribute( 'contentclassattribute_id' ),
                                                   'identifier' => $metaDataPart['id'],
                                                   'integer_value' => $integerValue );
                            $indexArrayOnlyWords[$word] = 1;
                            $wordCount++;
                            //if we have "www." before word than
                            //treat it as url and add additional entry to the index
                            if ( substr( strtolower($word), 0, 4 ) == 'www.' )
                            {
                                $additionalUrlWord = substr( $word, 4 );
                                $indexArray[] = array( 'Word' => $additionalUrlWord,
                                                       'ContentClassAttributeID' => $attribute->attribute( 'contentclassattribute_id' ),
                                                       'identifier' => $metaDataPart['id'],
                                                       'integer_value' => $integerValue );
                                $indexArrayOnlyWords[$additionalUrlWord] = 1;
                                $wordCount++;
                            }
                        }
                    }
                }
            }
        }
        eZContentObject::recursionProtectionEnd();

        $wordIDArray = $this->buildWordIDArray( array_keys( $indexArrayOnlyWords ) );

        $db = eZDB::instance();
        $db->begin();
        for( $arrayCount = 0; $arrayCount < $wordCount; $arrayCount += 1000 )
        {
            $placement = $this->indexWords( $contentObject, array_slice( $indexArray, $arrayCount, 1000 ), $wordIDArray, $placement );
        }
        $db->commit();

        return true;
    }

    /*!
      \private

      Build WordIDArray and update ezsearch_word table

      \params words for object to add

      \return wordIDArray
    */
    function buildWordIDArray( $indexArrayOnlyWords )
    {
        $db = eZDB::instance();

        // Initialize transformation system
        $trans = eZCharTransform::instance();

        $wordCount = count( $indexArrayOnlyWords );
        $wordIDArray = array();
        $wordArray = array();
        // store the words in the index and remember the ID
        $dbName = $db->databaseName();
        if ( $dbName == 'mysql' )
        {
            $db->begin();
            for( $arrayCount = 0; $arrayCount < $wordCount; $arrayCount += 500 )
            {
                // Fetch already indexed words from database
                $wordArrayChuck = array_slice( $indexArrayOnlyWords, $arrayCount, 500 );
                $wordsString = implode( '\',\'',  $wordArrayChuck );
                $wordRes = $db->arrayQuery( "SELECT * FROM ezsearch_word WHERE word IN ( '$wordsString' ) " );

                // Build a has of the existing words
                $wordResCount = count( $wordRes );
                $existingWordArray = array();
                for ( $i = 0; $i < $wordResCount; $i++ )
                {
                    $wordIDArray[] = $wordRes[$i]['id'];
                    $existingWordArray[] = $wordRes[$i]['word'];
                    $wordArray[$wordRes[$i]['word']] = $wordRes[$i]['id'];
                }

                // Update the object count of existing words by one
                $wordIDString = implode( ',', $wordIDArray );
                if ( count( $wordIDArray ) > 0 )
                    $db->query( "UPDATE ezsearch_word SET object_count=( object_count + 1 ) WHERE id IN ( $wordIDString )" );

                // Insert if there is any news words
                $newWordArray = array_diff( $wordArrayChuck, $existingWordArray );
                if ( count ($newWordArray) > 0 )
                {
                    $newWordString = implode( "', '1' ), ('", $newWordArray );
                    $db->query( "INSERT INTO
                               ezsearch_word ( word, object_count )
                             VALUES ('$newWordString', '1' )" );
                    $newWordString = implode( "','", $newWordArray );
                    $newWordRes = $db->arrayQuery( "select id,word from ezsearch_word where word in ( '$newWordString' ) " );
                    $newWordCount = count( $newWordRes );
                    for ( $i=0;$i<$newWordCount;++$i )
                    {
                        $wordLowercase = $trans->transformByGroup( $newWordRes[$i]['word'], 'lowercase' );
                        $wordArray[$newWordRes[$i]['word']] = $newWordRes[$i]['id'];
                    }
                }
            }
            $db->commit();
        }
        else
        {
            $db->begin();
            foreach ( $indexArrayOnlyWords as $indexWord )
            {
                $indexWord = $trans->transformByGroup( $indexWord, 'lowercase' );
                $indexWord = $db->escapeString( $indexWord );

                // Store word if it does not exist.
                $wordRes = array();

                $wordRes = $db->arrayQuery( "SELECT * FROM ezsearch_word WHERE word='$indexWord'" );

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

                $wordArray[$indexWord] = $wordID;
            }
            $db->commit();
        }

        return $wordArray;
    }

    /*!
      \private

      \param contentObject
      \param indexArray
      \param wordIDArray
      \param placement

      \return last placement
      Index wordIndex
    */
    function indexWords( $contentObject, $indexArray, $wordIDArray, $placement = 0 )
    {
        $db = eZDB::instance();

        $contentObjectID = $contentObject->attribute( 'id' );

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

        // Initialize transformation system
        $trans = eZCharTransform::instance();

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
            $identifier = $indexArray[$i]['identifier'];
            $integerValue = min( $indexArray[$i]['integer_value'], $db->MAX_INT );
            $integerValue = max( $integerValue, $db->MIN_INT );
            $wordID = $wordIDArray[$indexWord];

            if ( isset( $indexArray[$i+1] ) )
            {
                $nextIndexWord = $indexArray[$i+1]['Word'];
                $nextWordID = $wordIDArray[$nextIndexWord];
            }
            else
                $nextWordID = 0;
//            print( "indexing word : $indexWord <br> " );

            // Calculate the relevans ranking for this word
//            $frequency = ( $wordCountArray[$indexWord] / $totalWordCount );
            $frequency = 0;
            $valuesStringList[] = " ( '$wordID', '$contentObjectID', '$frequency', '$placement', '$nextWordID', '$prevWordID', '$classID', '$contentClassAttributeID', '$published', '$sectionID', '$identifier', '$integerValue' ) ";

            $prevWordID = $wordID;
            $placement++;
        }
        $dbName = $db->databaseName();

        if ( $dbName == 'mysql' )
        {
            if ( count( $valuesStringList ) > 0 )
            {
                $valuesString = implode( ',', $valuesStringList );
                $db->query( "INSERT INTO
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
                          section_id,
                          identifier,
                          integer_value )
                          VALUES $valuesString" );
            }
        }
        else
        {
            $db->begin();
            foreach ( array_keys( $valuesStringList ) as $key )
            {
                $valuesString = $valuesStringList[$key];
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
                             section_id,
                             identifier,
                             integer_value )
                             VALUES $valuesString"  );
            }
            $db->commit();
        }

        return $placement;
    }

    /**
     * Removes object $contentObject from the search database.
     *
     * @deprecated Since 5.0, use removeObjectById()
     * @param eZContentObject $contentObject the content object to remove
     * @param bool $commit Whether to commit after removing the object
     * @return bool True if the operation succeed.
     */
    public function removeObject( $contentObject, $commit = null )
    {
        return $this->removeObjectById( $contentObject->attribute( "id" ), $commit );
    }

    /**
     *  Removes a content object by Id from the search database.
     *
     * @since 5.0
     * @param int $contentObjectId The content object to remove by id
     * @param bool $commit Whether to commit after removing the object
     * @return bool True if the operation succeed.
     */
    public function removeObjectById( $contentObjectId, $commit = null )
    {
        $db = eZDB::instance();
        $doDelete = false;

        $db->begin();
        if ( $db->databaseName() === 'mysql' )
        {
            // fetch all the words and decrease the object count on all the words
            $wordArray = $db->arrayQuery( "SELECT word_id FROM ezsearch_object_word_link WHERE contentobject_id='$contentObjectId'" );
            $wordIDList = array();
            foreach ( $wordArray as $word )
                $wordIDList[] = $word["word_id"];
            if ( count( $wordIDList ) > 0 )
            {
                $wordIDString = implode( ',', $wordIDList );
                $db->query( "UPDATE ezsearch_word SET object_count=( object_count - 1 ) WHERE id in ( $wordIDString )" );
                $doDelete = true;
            }
        }
        else
        {
            $cnt = $db->arrayQuery( "SELECT COUNT( word_id ) AS cnt FROM ezsearch_object_word_link WHERE contentobject_id='$contentObjectId'" );
            if ( $cnt[0]['cnt'] > 0 )
            {
                $db->query( "UPDATE ezsearch_word SET object_count=( object_count - 1 ) WHERE id in ( SELECT word_id FROM ezsearch_object_word_link WHERE contentobject_id='$contentObjectId' )" );
                $doDelete = true;
            }
        }

        if ( $doDelete )
        {
            $db->query( "DELETE FROM ezsearch_word WHERE object_count='0'" );
            $db->query( "DELETE FROM ezsearch_object_word_link WHERE contentobject_id='$contentObjectId'" );
        }
        $db->commit();

        return true;
    }

    /*!
     Saves name of a temporary that has just been created,
     for us to know its name when it's time to drop the table.
    */
    function saveCreatedTempTableName( $index, $tableName )
    {
        if ( isset( $this->CreatedTempTablesNames[$index] ) )
        {
            eZDebug::writeWarning( "CreatedTempTablesNames[\$index] already exists " .
                                   "and contains '" . $this->CreatedTempTablesNames[$index] . "'", __METHOD__ );
        }
        $this->CreatedTempTablesNames[$index] = $tableName;
    }

    /*!
     \return Given table name from the list of saved temporary tables names by its index.
     \see saveCreatedTempTableName()
    */
    function getSavedTempTableName( $index )
    {
        return $this->CreatedTempTablesNames[$index];
    }

    /*!
    \return List of saved temporary tables names.
    \see saveCreatedTempTableName()
    */
    function getSavedTempTablesList()
    {
        return $this->CreatedTempTablesNames;
    }

    /**
     * Runs a query to the search engine.
     *
     * @param string $searchText Search term
     * @param array $params Search parameters
     * @param array $searchTypes Search types. Deprecated!
     */
    public function search( $searchText, $params = array(), $searchTypes = array() )
    {
        $allowSearch = true;
        if ( trim( $searchText ) == '' )
        {
            $ini = eZINI::instance();
            if ( $ini->variable( 'SearchSettings', 'AllowEmptySearch' ) != 'enabled' )
                $allowSearch = false;
            if ( isset( $params['AllowEmptySearch'] ) )
                $allowSearch = $params['AllowEmptySearch'];
        }
        if ( $allowSearch )
        {
            $searchText = $this->normalizeText( $searchText, false );
            $db = eZDB::instance();

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

            if ( isset( $params['SearchDate'] ) )
                $searchDate = $params['SearchDate'];
            else
                $searchDate = -1;

            if ( isset( $params['SearchTimestamp'] ) )
                $searchTimestamp = $params['SearchTimestamp'];
            else
                $searchTimestamp = false;

            if ( isset( $params['SearchContentClassAttributeID'] ) )
                $searchContentClassAttributeID = $params['SearchContentClassAttributeID'];
            else
                $searchContentClassAttributeID = -1;

            if ( isset( $params['SearchSubTreeArray'] ) )
                $subTreeArray = $params['SearchSubTreeArray'];
            else
                $subTreeArray = array();

            if ( isset( $params['SortArray'] ) )
                $sortArray = $params['SortArray'];
            else
                $sortArray = array();

            $ignoreVisibility = isset( $params['IgnoreVisibility'] ) ? $params['IgnoreVisibility'] : false;

            // strip multiple spaces
            $searchText = preg_replace( "(\s+)", " ", $searchText );

            // find the phrases
/*            $numQuotes = substr_count( $searchText, "\"" );

            $phraseTextArray = array();
            $fullText = $searchText;
            $nonPhraseText ='';
//            $fullText = '';
            $postPhraseText = $fullText;
            $pos = 0;
            if ( ( $numQuotes > 0 ) and ( ( $numQuotes % 2 ) == 0 ) )
            {
                for ( $i = 0; $i < ( $numQuotes / 2 ); $i ++ )
                {
                    $quotePosStart = strpos( $searchText, '"',  $pos );
                    $quotePosEnd = strpos( $searchText, '"',  $quotePosStart + 1 );

                    $prePhraseText = substr( $searchText, $pos, $quotePosStart - $pos );
                    $postPhraseText = substr( $searchText, $quotePosEnd +1 );
                    $phraseText = substr( $searchText, $quotePosStart + 1, $quotePosEnd - $quotePosStart - 1 );

                    $phraseTextArray[] = $phraseText;
//                    $fullText .= $prePhraseText;
                    $nonPhraseText .= $prePhraseText;
                    $pos = $quotePosEnd + 1;
                }
            }
            $nonPhraseText .= $postPhraseText;
*/
            $phrasesResult = $this->getPhrases( $searchText );
            $phraseTextArray = $phrasesResult['phrases'];
            $nonPhraseText = $phrasesResult['nonPhraseText'];
            $fullText = $phrasesResult['fullText'];

            $sectionQuery = '';
            if ( is_numeric( $searchSectionID ) and  $searchSectionID > 0 )
            {
                $sectionQuery = "ezsearch_object_word_link.section_id = '" . (int)$searchSectionID . "' AND ";
            }
            else if ( is_array( $searchSectionID ) )
            {
                // Build query for searching in an array of sections
                $sectionQuery = $db->generateSQLINStatement( array_map( 'intval', $searchSectionID ), 'ezsearch_object_word_link.section_id', false, false, 'int' ) . " AND ";
            }

            $searchDateQuery = '';
            if ( ( is_numeric( $searchDate ) and  $searchDate > 0 ) or
                 $searchTimestamp )
            {
                $date = new eZDateTime();
                $timestamp = $date->timeStamp();
                $day = $date->attribute('day');
                $month = $date->attribute('month');
                $year = $date->attribute('year');
                $publishedDateStop = false;
                if ( $searchTimestamp )
                {
                    if ( is_array( $searchTimestamp ) )
                    {
                        $publishedDate = (int) $searchTimestamp[0];
                        $publishedDateStop = (int) $searchTimestamp[1];
                    }
                    else
                        $publishedDate = (int) $searchTimestamp;
                }
                else
                {
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
                            $adjustment = 365*24*60*60; //seconds for one year
                            $publishedDate = $timestamp - $adjustment;
                        } break;
                        default:
                        {
                            $publishedDate = $date->timeStamp();
                        }
                    }
                }
                $searchDateQuery = "ezsearch_object_word_link.published >= '$publishedDate' AND ";
                if ( $publishedDateStop )
                    $searchDateQuery .= "ezsearch_object_word_link.published <= '$publishedDateStop' AND ";
                $this->GeneralFilter['searchDateQuery'] = $searchDateQuery;
            }

            $classQuery = "";
            if ( is_numeric( $searchContentClassID ) and $searchContentClassID > 0 )
            {
                // Build query for searching in one class
                $classQuery = "ezsearch_object_word_link.contentclass_id = '" . (int)$searchContentClassID . "' AND ";
                $this->GeneralFilter['classAttributeQuery'] = $classQuery;
            }
            else if ( is_array( $searchContentClassID ) )
            {
                // Build query for searching in a number of classes
                $classString = $db->generateSQLINStatement( array_map( 'intval', $searchContentClassID ), 'ezsearch_object_word_link.contentclass_id', false, false, 'int' );
                $classQuery = "$classString AND ";
                $this->GeneralFilter['classAttributeQuery'] = $classQuery;
            }

            $classAttributeQuery = "";
            if ( is_numeric( $searchContentClassAttributeID ) and  $searchContentClassAttributeID > 0 )
            {
                $classAttributeQuery = "ezsearch_object_word_link.contentclass_attribute_id = '$searchContentClassAttributeID' AND ";
            }
            else if ( is_array( $searchContentClassAttributeID ) )
            {
                // Build query for searching in a number of attributes
                $classAttributeQuery = $db->generateSQLINStatement( array_map( 'intval', $searchContentClassAttributeID ), 'ezsearch_object_word_link.contentclass_attribute_id', false, false, 'int' ) . ' AND ';
            }

            // Get the total number of objects
            $totalObjectCount = $this->fetchTotalObjectCount();

            $searchPartsArray = array();
            $wordIDHash = array();
            $wildCardCount = 0;
            if ( trim( $searchText ) != '' )
            {
                $wordIDArrays = $this->prepareWordIDArrays( $searchText );
                $wordIDArray = $wordIDArrays['wordIDArray'];
                $wordIDHash = $wordIDArrays['wordIDHash'];
                $wildIDArray = $wordIDArrays['wildIDArray'];
                $wildCardCount = $wordIDArrays['wildCardCount'];
                $searchPartsArray = $this->buildSearchPartArray( $phraseTextArray, $nonPhraseText, $wordIDHash, $wildIDArray );
            }

            /// OR search, not used in this version
            $doOrSearch = false;

            if ( $doOrSearch == true )
            {
                // build fulltext search SQL part
                $searchWordArray = $this->splitString( $fullText );
                $fullTextSQL = "";
                if ( count( $searchWordArray ) > 0 )
                {
                    $i = 0;
                    // Build the word query string
                    foreach ( $searchWordArray as $searchWord )
                    {
                        $wordID = null;
                        if ( isset( $wordIDHash[$searchWord] ) )
                            $wordID = $wordIDHash[$searchWord]['id'];

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

                    $nodeArray = $db->arrayQuery( $nodeQuery );
                    $i = 0;
                    foreach ( $nodeArray as $node )
                    {
                        $pathString = $node['path_string'];
                        $subTreeSQL .= " ezcontentobject_tree.path_string like '$pathString%' ";

                        if ( $i < ( count( $nodeArray ) -1 ) )
                            $subTreeSQL .= " OR ";
                        $i++;
                    }
                    $subTreeSQL .= " ) AND ";
                    $this->GeneralFilter['subTreeTable'] = $subTreeTable;
                    $this->GeneralFilter['subTreeSQL'] = $subTreeSQL;

                }
            }

            $limitation = false;
            if ( isset( $params['Limitation'] ) )
            {
                $limitation = $params['Limitation'];
            }

            $limitationList = eZContentObjectTreeNode::getLimitationList( $limitation );
            $sqlPermissionChecking = eZContentObjectTreeNode::createPermissionCheckingSQL( $limitationList );
            $this->GeneralFilter['sqlPermissionChecking'] = $sqlPermissionChecking;

            $versionNameJoins = " AND " . eZContentLanguage::sqlFilter( 'ezcontentobject_name', 'ezcontentobject' );

            /// Only support AND search at this time
            // build fulltext search SQL part
            $searchWordArray = $this->splitString( $fullText );
            $searchWordCount = count( $searchWordArray );
            $fullTextSQL = "";
            $stopWordArray = array( );
            $ini = eZINI::instance();

            $tmpTableCount = 0;

            // Do not execute search if site.ini:[SearchSettings]->AllowEmptySearch is enabled, but no conditions are set.
            if ( !$searchDateQuery &&
                 !$sectionQuery &&
                 !$classQuery &&
                 !$classAttributeQuery &&
                 !$searchPartsArray &&
                 !$subTreeSQL )
            {
                // cleanup temp tables
                $db->dropTempTableList( $sqlPermissionChecking['temp_tables'] );

                return array( "SearchResult" => array(),
                              "SearchCount" => 0,
                              "StopWordArray" => array() );
            }

            $i = $this->TempTablesCount;

            // Loop every word and insert result in temporary table

            // Determine whether we should search invisible nodes.
            $showInvisibleNodesCond = eZContentObjectTreeNode::createShowInvisibleSQLString( !$ignoreVisibility );

            foreach ( $searchPartsArray as $searchPart )
            {
                $stopWordThresholdValue = 100;
                if ( $ini->hasVariable( 'SearchSettings', 'StopWordThresholdValue' ) )
                    $stopWordThresholdValue = $ini->variable( 'SearchSettings', 'StopWordThresholdValue' );

                $stopWordThresholdPercent = 60;
                if ( $ini->hasVariable( 'SearchSettings', 'StopWordThresholdPercent' ) )
                    $stopWordThresholdPercent = $ini->variable( 'SearchSettings', 'StopWordThresholdPercent' );

                $searchThresholdValue = $totalObjectCount;
                if ( $totalObjectCount > $stopWordThresholdValue )
                {
                    $searchThresholdValue = (int)( $totalObjectCount * ( $stopWordThresholdPercent / 100 ) );
                }

                // do not search words that are too frequent
                if ( $searchPart['object_count'] < $searchThresholdValue )
                {
                    $tmpTableCount++;
                    $searchPartText = $searchPart['sql_part'];
                    if ( $i == 0 )
                    {
                        $table = $db->generateUniqueTempTableName( 'ezsearch_tmp_%', 0 );
                        $this->saveCreatedTempTableName( 0, $table );
                        $db->createTempTable( "CREATE TEMPORARY TABLE $table ( contentobject_id int primary key not null, published int )" );
                        $db->query( "INSERT INTO $table SELECT DISTINCT ezsearch_object_word_link.contentobject_id, ezsearch_object_word_link.published
                                         FROM ezcontentobject
                                              INNER JOIN ezsearch_object_word_link ON (ezsearch_object_word_link.contentobject_id = ezcontentobject.id)
                                              $subTreeTable
                                              INNER JOIN ezcontentclass ON (ezcontentclass.id = ezcontentobject.contentclass_id)
                                              INNER JOIN ezcontentobject_tree ON (ezcontentobject_tree.contentobject_id = ezcontentobject.id)
                                              {$sqlPermissionChecking['from']}
                                         WHERE
                                               $searchDateQuery
                                               $sectionQuery
                                               $classQuery
                                               $classAttributeQuery
                                               $searchPartText
                                               $subTreeSQL
                                         ezcontentclass.version = '0' AND
                                         ezcontentobject_tree.node_id = ezcontentobject_tree.main_node_id
                                         $showInvisibleNodesCond
                                         {$sqlPermissionChecking['where']}",
                                    eZDBInterface::SERVER_SLAVE );
                    }
                    else
                    {
                        $table = $db->generateUniqueTempTableName( 'ezsearch_tmp_%', $i );
                        $this->saveCreatedTempTableName( $i, $table );

                        $tmpTable0 = $this->getSavedTempTableName( 0 );
                        $db->createTempTable( "CREATE TEMPORARY TABLE $table ( contentobject_id int primary key not null, published int )" );
                        $db->query( "INSERT INTO $table SELECT DISTINCT ezsearch_object_word_link.contentobject_id, ezsearch_object_word_link.published
                                         FROM
                                             ezcontentobject
                                             INNER JOIN ezsearch_object_word_link ON (ezsearch_object_word_link.contentobject_id = ezcontentobject.id)
                                             $subTreeTable
                                             INNER JOIN ezcontentclass ON (ezcontentclass.id = ezcontentobject.contentclass_id)
                                             INNER JOIN ezcontentobject_tree ON (ezcontentobject_tree.contentobject_id = ezcontentobject.id)
                                             INNER JOIN $tmpTable0 ON ($tmpTable0.contentobject_id = ezsearch_object_word_link.contentobject_id)
                                             {$sqlPermissionChecking['from']}
                                          WHERE
                                          $searchDateQuery
                                          $sectionQuery
                                          $classQuery
                                          $classAttributeQuery
                                          $searchPartText
                                          $subTreeSQL
                                          ezcontentclass.version = '0' AND
                                          ezcontentobject_tree.node_id = ezcontentobject_tree.main_node_id
                                          $showInvisibleNodesCond
                                          {$sqlPermissionChecking['where']}",
                                    eZDBInterface::SERVER_SLAVE );
                    }
                    $i++;
                }
                else
                {
                    $stopWordArray[] = array( 'word' => $searchPart['text'] );
                }
            }

            if ( count( $searchPartsArray ) === 0 && $this->TempTablesCount == 0 )
            {
                 $table = $db->generateUniqueTempTableName( 'ezsearch_tmp_%', 0 );
                 $this->saveCreatedTempTableName( 0, $table );
                 $db->createTempTable( "CREATE TEMPORARY TABLE $table ( contentobject_id int primary key not null, published int )" );
                 $db->query( "INSERT INTO $table SELECT DISTINCT ezsearch_object_word_link.contentobject_id, ezsearch_object_word_link.published
                                     FROM ezcontentobject
                                          INNER JOIN ezsearch_object_word_link ON (ezsearch_object_word_link.contentobject_id = ezcontentobject.id)
                                          $subTreeTable
                                          INNER JOIN ezcontentclass ON (ezcontentclass.id = ezcontentobject.contentclass_id)
                                          INNER JOIN ezcontentobject_tree ON (ezcontentobject_tree.contentobject_id = ezcontentobject.id)
                                          {$sqlPermissionChecking['from']}
                                     WHERE
                                          $searchDateQuery
                                          $sectionQuery
                                          $classQuery
                                          $classAttributeQuery
                                          $subTreeSQL
                                          ezcontentclass.version = '0' AND
                                          ezcontentobject_tree.node_id = ezcontentobject_tree.main_node_id
                                          $showInvisibleNodesCond
                                          {$sqlPermissionChecking['where']}",
                             eZDBInterface::SERVER_SLAVE );
                 $this->TempTablesCount = 1;
                 $i = $this->TempTablesCount;
            }

            $nonExistingWordCount = count( array_unique( $searchWordArray ) ) - count( $wordIDHash ) - $wildCardCount;
            $excludeWordCount = $searchWordCount - count( $stopWordArray );

            if ( ( count( $stopWordArray ) + $nonExistingWordCount ) == $searchWordCount && $this->TempTablesCount == 0 )
            {
                // No words to search for, return empty result

                // cleanup temp tables
                $db->dropTempTableList( $sqlPermissionChecking['temp_tables'] );
                $db->dropTempTableList( $this->getSavedTempTablesList() );

                return array( "SearchResult" => array(),
                          "SearchCount" => 0,
                          "StopWordArray" => $stopWordArray );
            }
            $tmpTablesFrom = "";
            $tmpTablesWhere = "";
            /// tmp tables
            $tmpTableCount = $i;
            for ( $i = 0; $i < $tmpTableCount; $i++ )
            {
                $tmpTablesFrom .= $this->getSavedTempTableName( $i );
                if ( $i < ( $tmpTableCount - 1 ) )
                    $tmpTablesFrom .= ", ";

            }
            $tmpTablesSeparator = '';
            if ( $tmpTableCount > 0 )
            {
                $tmpTablesSeparator = ', ';
            }

            $tmpTable0 = $this->getSavedTempTableName( 0 );
            for ( $i = 1; $i < $tmpTableCount; $i++ )
            {
                $tmpTableI = $this->getSavedTempTableName( $i );
                $tmpTablesWhere .= " $tmpTable0.contentobject_id=$tmpTableI.contentobject_id  ";
                if ( $i < ( $tmpTableCount - 1 ) )
                    $tmpTablesWhere .= " AND ";
            }
            $tmpTablesWhereExtra = '';
            if ( $tmpTableCount > 0 )
            {
                $tmpTablesWhereExtra = "ezcontentobject.id=$tmpTable0.contentobject_id AND";
            }

            $and = "";
            if ( $tmpTableCount > 1 )
                $and = " AND ";

            // Generate ORDER BY SQL
            $orderBySQLArray = $this->buildSortSQL( $sortArray );
            $orderByFieldsSQL = $orderBySQLArray['sortingFields'];
            $sortWhereSQL = $orderBySQLArray['whereSQL'];
            $sortFromSQL = $orderBySQLArray['fromSQL'];
            $sortSelectSQL = $orderBySQLArray['selectSQL'];

            // Fetch data from table
            $searchQuery = "SELECT DISTINCT " .
            "ezcontentobject.contentclass_id, ezcontentobject.current_version, ezcontentobject.id, ezcontentobject.initial_language_id, ezcontentobject.language_mask, " .
            "ezcontentobject.modified, ezcontentobject.name, ezcontentobject.owner_id, ezcontentobject.published, ezcontentobject.remote_id AS object_remote_id, ezcontentobject.section_id, " .
            "ezcontentobject.status, ezcontentobject_tree.contentobject_is_published, ezcontentobject_tree.contentobject_version, ezcontentobject_tree.depth, ezcontentobject_tree.is_hidden, " .
            "ezcontentobject_tree.is_invisible, ezcontentobject_tree.main_node_id, ezcontentobject_tree.modified_subnode, ezcontentobject_tree.node_id, ezcontentobject_tree.parent_node_id, " .
            "ezcontentobject_tree.path_identification_string, ezcontentobject_tree.path_string, ezcontentobject_tree.priority, ezcontentobject_tree.remote_id, ezcontentobject_tree.sort_field, " .
            "ezcontentobject_tree.sort_order, ezcontentclass.serialized_name_list as class_serialized_name_list, ezcontentobject_name.name as name, " .
            "ezcontentobject_name.real_translation $sortSelectSQL " .
            "FROM " .
            "$tmpTablesFrom $tmpTablesSeparator " .
            "ezcontentobject " .
            "INNER JOIN ezcontentclass ON (ezcontentclass.id = ezcontentobject.contentclass_id ) " .
            "INNER JOIN ezcontentobject_tree ON (ezcontentobject_tree.contentobject_id = ezcontentobject.id) " .
            "INNER JOIN ezcontentobject_name ON ( " .
            "    ezcontentobject_name.contentobject_id = ezcontentobject_tree.contentobject_id AND " .
            "    ezcontentobject_name.content_version = ezcontentobject_tree.contentobject_version " .
            ") " .
            $sortFromSQL . " " .
            "WHERE " .
            "$tmpTablesWhere $and " .
            $tmpTablesWhereExtra . " " .
            "ezcontentclass.version = '0' AND " .
            "ezcontentobject_tree.node_id = ezcontentobject_tree.main_node_id AND " .
            "" . eZContentLanguage::sqlFilter( 'ezcontentobject_name', 'ezcontentobject' ) . " " .
            $showInvisibleNodesCond . " " .
            $sortWhereSQL . " " .
            "ORDER BY $orderByFieldsSQL";

            // Count query
            $languageCond = eZContentLanguage::languagesSQLFilter( 'ezcontentobject' );
            if ( $tmpTableCount == 0 )
            {
                $searchCountQuery = "SELECT count( DISTINCT ezcontentobject.id ) AS count
                        FROM
                           ezcontentobject,
                           ezcontentobject_tree
                        WHERE
                        ezcontentobject.id = ezcontentobject_tree.contentobject_id and
                        ezcontentobject_tree.node_id = ezcontentobject_tree.main_node_id
                        AND $languageCond
                        $showInvisibleNodesCond";
            }
            else
            {
                $searchCountQuery = "SELECT count( DISTINCT ezcontentobject.id ) AS count
                        FROM $tmpTablesFrom $tmpTablesSeparator
                             ezcontentobject
                        WHERE $tmpTablesWhere $and
                            $tmpTablesWhereExtra
                            $languageCond";
            }

            $objectRes = array();
            $searchCount = 0;

            if ( $nonExistingWordCount <= 0 )
            {
                // execute search query
                $objectResArray = $db->arrayQuery( $searchQuery, array( "limit" => $searchLimit, "offset" => $searchOffset ), eZDBInterface::SERVER_SLAVE );
                // execute search count query
                $objectCountRes = $db->arrayQuery( $searchCountQuery, array(), eZDBInterface::SERVER_SLAVE );
                $objectRes = eZContentObjectTreeNode::makeObjectsArray( $objectResArray );
                $searchCount = $objectCountRes[0]['count'];
            }
            else
                $objectRes = array();

            // Drop tmp tables
            $db->dropTempTableList( $sqlPermissionChecking['temp_tables'] );
            $db->dropTempTableList( $this->getSavedTempTablesList() );

            return array( "SearchResult" => $objectRes,
                          "SearchCount" => $searchCount,
                          "StopWordArray" => $stopWordArray );
        }
        else
        {
            return array( "SearchResult" => array(),
                          "SearchCount" => 0,
                          "StopWordArray" => array() );
        }
    }

    /*!
     \private
     \return an array of ORDER BY SQL
    */
    function buildSortSQL( $sortArray )
    {
        $sortCount = 0;
        $sortList = false;
        if ( isset( $sortArray ) and
             is_array( $sortArray ) and
             count( $sortArray ) > 0 )
        {
            $sortList = $sortArray;
            if ( count( $sortList ) > 1 and
                 !is_array( $sortList[0] ) )
            {
                $sortList = array( $sortList );
            }
        }
        $attributeJoinCount = 0;
        $attributeFromSQL = "";
        $attributeWereSQL = "";
        $selectSQL = '';
        if ( $sortList !== false )
        {
            $sortingFields = '';
            foreach ( $sortList as $sortBy )
            {
                if ( is_array( $sortBy ) and count( $sortBy ) > 0 )
                {
                    if ( $sortCount > 0 )
                        $sortingFields .= ', ';
                    $sortField = $sortBy[0];
                    switch ( $sortField )
                    {
                        case 'path':
                        {
                            $sortingFields .= 'path_string';
                        } break;
                        case 'published':
                        {
                            $sortingFields .= 'ezcontentobject.published';
                        } break;
                        case 'modified':
                        {
                            $sortingFields .= 'ezcontentobject.modified';
                        } break;
                        case 'section':
                        {
                            $sortingFields .= 'ezcontentobject.section_id';
                        } break;
                        case 'depth':
                        {
                            $sortingFields .= 'depth';
                        } break;
                        case 'class_identifier':
                        {
                            $sortingFields .= 'ezcontentclass.identifier';
                            $selectSQL .= ', ezcontentclass.identifier';
                        } break;
                        case 'class_name':
                        {
                            $classNameFilter = eZContentClassName::sqlFilter();
                            $selectSQL .= ", " . $classNameFilter['nameField'] . " AS class_name";
                            $sortingFields .= "class_name";
                            $attributeFromSQL .= " INNER JOIN {$classNameFilter['from']} ON ({$classNameFilter['where']})";
                        } break;
                        case 'priority':
                        {
                            $sortingFields .= 'ezcontentobject_tree.priority';
                        } break;
                        case 'name':
                        {
                            $sortingFields .= 'ezcontentobject_name.name';
                        } break;
                        case 'attribute':
                        {
                            $sortClassID = $sortBy[2];
                            // Look up datatype for sorting
                            if ( !is_numeric( $sortClassID ) )
                            {
                                $sortClassID = eZContentObjectTreeNode::classAttributeIDByIdentifier( $sortClassID );
                            }

                            $sortDataType = $sortClassID === false ? false : eZContentObjectTreeNode::sortKeyByClassAttributeID( $sortClassID );

                            $sortKey = false;
                            if ( $sortDataType == 'string' )
                            {
                                $sortKey = 'sort_key_string';
                            }
                            else
                            {
                                $sortKey = 'sort_key_int';
                            }

                            $sortingFields .= "a$attributeJoinCount.$sortKey";
                            $attributeFromSQL .= " INNER JOIN ezcontentobject_attribute as a$attributeJoinCount ON (a$attributeJoinCount.contentobject_id = ezcontentobject.id AND a$attributeJoinCount.version = ezcontentobject_name.content_version)";
                            $attributeWereSQL .= " AND a$attributeJoinCount.contentclassattribute_id = $sortClassID";
                            $selectSQL .= ", a$attributeJoinCount.$sortKey";

                            $attributeJoinCount++;
                        }break;

                        default:
                        {
                            eZDebug::writeWarning( 'Unknown sort field: ' . $sortField, __METHOD__ );
                            continue 2;
                        }
                    }
                    $sortOrder = true; // true is ascending
                    if ( isset( $sortBy[1] ) )
                        $sortOrder = $sortBy[1];
                    $sortingFields .= $sortOrder ? " ASC" : " DESC";
                    ++$sortCount;
                }
            }
        }

        // Should we sort?
        if ( $sortCount == 0 )
        {
            $sortingFields = " ezcontentobject.published ASC";
        }

        return array( 'sortingFields' => $sortingFields,
                      'selectSQL' => $selectSQL,
                      'fromSQL' => $attributeFromSQL,
                      'whereSQL' => $attributeWereSQL );
    }

    /*!
     \private
     \return Returns an sql query part for one word
    */
    function buildSqlPartForWord( $wordID, $identifier = false )
    {
        $fullTextSQL = "ezsearch_object_word_link.word_id='$wordID' AND ";
        if ( $identifier )
        {
            $fullTextSQL .= "ezsearch_object_word_link.identifier='$identifier' AND ";
        }

        return $fullTextSQL;
    }

    /*!
     \private
     \return Returns an sql query part for a phrase
    */

    function buildPhraseSqlQueryPart( $phraseIDArray, $identifier = false )
    {
        $phraseSearchSQLArray = array();
        $wordCount = count( $phraseIDArray );
        for ( $i = 0; $i < $wordCount; $i++ )
        {
            $wordID = $phraseIDArray[$i];
            $phraseSearchSQL = "";
            if ( is_numeric( $wordID ) and ( $wordID > 0 ) )
            {
                $phraseSearchSQL = " ( ezsearch_object_word_link.word_id='$wordID' ";
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
                if ( $identifier )
                {
                    $phraseSearchSQL .= " AND ezsearch_object_word_link.identifier='$identifier' ";
                }
                $phraseSearchSQL .= "  ) ";
            }
            else
            {
                $nonExistingWordArray[] = $searchWord;
            }
            $prevWord = $wordID;
            $phraseSearchSQLArray[] = $phraseSearchSQL;
        }

        return $phraseSearchSQLArray;
    }

    /*!
     \private
     \return Returns an array of words created from the input string.
    */
    function splitString( $text )
    {
        $text = self::removeDuplicatedSpaces( trim( self::removeAllQuotes( $text ) ) );

        return empty( $text ) ? array() : explode( " ", $text );
    }

    /**
     * Remove duplicated spaces
     *
     * @param string $text
     *
     * @return string
     */
    static protected function removeDuplicatedSpaces( $text )
    {
        return preg_replace( "/\s{2,}/", " ", $text );
    }

    /**
     * Remove single and double quotes, including UTF-8 variations
     *
     * @param string $text
     *
     * @return string
     */
    static protected function removeAllQuotes( $text )
    {
        return preg_replace( "/([\x{2018}-\x{201f}]|'|\")/u", " ", $text );
    }

    /*!
     Normalizes the text \a $text so that it is easily parsable
     \param $isMetaData If \c true then it expects the text to be meta data from objects,
                        if not it is the search text and needs special handling.
    */
    function normalizeText( $text, $isMetaData = false )
    {
        $text = self::removeDuplicatedSpaces(
            trim(
                self::removeAllQuotes(
                    eZCharTransform::instance()->transformByGroup( $text, 'search' )
                )
            )
        );

        // Remove quotes and asterix when not handling search text by end-user
        if ( $isMetaData )
        {
            $text = str_replace( "*", " ", $text );
        }

        return $text;
    }

    /*!
     \static
     \return Returns an array describing the supported search types in thie search engine.
     \note It has been renamed. In eZ Publish 3.4 and older it was (wrongly) named suportedSearchTypes().
    */
    public function supportedSearchTypes()
    {
        $searchTypes = array( array( 'type' => 'attribute',
                                     'subtype' => 'fulltext',
                                     'params' => array( 'classattribute_id', 'value' ) ),
                              array( 'type' => 'attribute',
                                     'subtype' => 'patterntext',
                                     'params' => array( 'classattribute_id', 'value' ) ),
                              array( 'type' => 'attribute',
                                     'subtype' => 'integer',
                                     'params' => array( 'classattribute_id', 'value' ) ),
                              array( 'type' => 'attribute',
                                     'subtype' => 'integers',
                                     'params' => array( 'classattribute_id', 'values' ) ),
                              array( 'type' => 'attribute',
                                     'subtype' => 'byrange',
                                     'params' => array( 'classattribute_id', 'from' , 'to' ) ),
                              array( 'type' => 'attribute',
                                     'subtype' => 'byidentifier',
                                     'params' => array( 'classattribute_id', 'identifier', 'value' ) ),
                              array( 'type' => 'attribute',
                                     'subtype' => 'byidentifierrange',
                                     'params' => array( 'classattribute_id', 'identifier', 'from', 'to' ) ),
                              array( 'type' => 'attribute',
                                     'subtype' => 'integersbyidentifier',
                                     'params' => array( 'classattribute_id', 'identifier', 'values' ) ),
                              array( 'type' => 'fulltext',
                                     'subtype' => 'text',
                                     'params' => array( 'value' ) ) );
        $generalSearchFilter = array( array( 'type' => 'general',
                                             'subtype' => 'class',
                                             'params' => array( array( 'type' => 'array',
                                                                       'value' => 'value'),
                                                                'operator' ) ),
                                      array( 'type' => 'general',
                                             'subtype' => 'publishdate',
                                             'params'  => array( 'value', 'operator' ) ),
                                      array( 'type' => 'general',
                                             'subtype' => 'subtree',
                                             'params'  => array( array( 'type' => 'array',
                                                                        'value' => 'value'),
                                                                 'operator' ) ) );
        return array( 'types' => $searchTypes,
                      'general_filter' => $generalSearchFilter );
    }

    function createTemporaryTable( $searchPartText  )
    {

        $subTreeTable = $this->GeneralFilter['subTreeTable'];
        $searchDateQuery = $this->GeneralFilter['searchDateQuery'];
        $sectionQuery = $this->GeneralFilter['sectionQuery'];
        $classQuery = $this->GeneralFilter['classQuery'];
        $classAttributeQuery = $this->GeneralFilter['classAttributeQuery'];
//        $searchPartText = $this->GeneralFilter['searchPartText'];
        $subTreeSQL = $this->GeneralFilter['subTreeSQL'];
        $sqlPermissionChecking = $this->GeneralFilter['sqlPermissionChecking'];
        $db = eZDB::instance();
        $i = $this->TempTablesCount;
        if ( $i == 0 )
        {
            $table = $db->generateUniqueTempTableName( 'ezsearch_tmp_%', 0 );
            $this->saveCreatedTempTableName( 0, $table );
            $db->createTempTable( "CREATE TEMPORARY TABLE $table ( contentobject_id int primary key not null, published int )" );
            $db->query( "INSERT INTO $table SELECT DISTINCT ezsearch_object_word_link.contentobject_id, ezsearch_object_word_link.published
                    FROM
                       ezcontentobject
                       INNER JOIN ezsearch_object_word_link ON (ezsearch_object_word_link.contentobject_id = ezcontentobject.id)
                       $subTreeTable
                       INNER JOIN ezcontentclass ON (ezcontentclass.id = ezcontentobject.contentclass_id)
                       INNER JOIN ezcontentobject_tree ON (ezcontentobject_tree.contentobject_id = ezcontentobject.id)
                       {$sqlPermissionChecking['from']}
                    WHERE
                    $searchDateQuery
                    $sectionQuery
                    $classQuery
                    $classAttributeQuery
                    $searchPartText
                    $subTreeSQL
                    ezcontentclass.version = '0' AND
                    ezcontentobject_tree.node_id = ezcontentobject_tree.main_node_id
                    {$sqlPermissionChecking['where']}",
                    eZDBInterface::SERVER_SLAVE );
        }
        else
        {
            $table = $db->generateUniqueTempTableName( 'ezsearch_tmp_%_0', $i );
            $this->saveCreatedTempTableName( $i, $table );
            $tmpTable0 = $this->getSavedTempTableName( 0 );
            $db->createTempTable( "CREATE TEMPORARY TABLE $table ( contentobject_id int primary key not null, published int )" );
            $db->query( "INSERT INTO $table SELECT DISTINCT ezsearch_object_word_link.contentobject_id, ezsearch_object_word_link.published
                    FROM
                       ezcontentobject
                       INNER JOIN ezsearch_object_word_link ON (ezsearch_object_word_link.contentobject_id = ezcontentobject.id)
                       $subTreeTable
                       INNER JOIN ezcontentclass ON (ezcontentclass.id = ezcontentobject.contentclass_id)
                       INNER JOIN ezcontentobject_tree ON (ezcontentobject_tree.contentobject_id = ezcontentobject.id)
                       INNER JOIN $tmpTable0 ON ($tmpTable0.contentobject_id = ezsearch_object_word_link.contentobject_id)
                       {$sqlPermissionChecking['from']}
                    WHERE
                    $searchDateQuery
                    $sectionQuery
                    $classQuery
                    $classAttributeQuery
                    $searchPartText
                    $subTreeSQL
                    ezcontentclass.version = '0' AND
                    ezcontentobject_tree.node_id = ezcontentobject_tree.main_node_id
                    {$sqlPermissionChecking['where']}",
                    eZDBInterface::SERVER_SLAVE );
        }

        $tmpTableI = $this->getSavedTempTableName( $i );
        $insertedCountArray = $db->arrayQuery( "SELECT count(*) as count from $tmpTableI " );
        $i++;
        $this->TempTablesCount++;
        if ( $insertedCountArray[0]['count'] == 0 )
        {
            return false;
        }
        else
        {
            return $insertedCountArray[0]['count'];
        }
    }

    function buildTempTablesForFullTextSearch( $searchPartsArray, $generalFilterList = array() )
    {
        $ini = eZINI::instance();
        $db = eZDB::instance();

        $i = $this->TempTablesCount;
        $generalFilterList = $this->GeneralFilter;

        if ( isset( $generalFilterList['searchDateQuery'] ) and
             isset( $generalFilterList['publish_date'] ) )
            $searchDateQuery = $generalFilterList['publish_date'];
        else
            $searchDateQuery = '';

        if ( isset( $generalFilterList['sectionQuery'] ) )
            $sectionQuery = $generalFilterList['sectionQuery'];
        else
            $sectionQuery = '';

        if ( isset( $generalFilterList['classQuery'] ) )
            $classQuery = $generalFilterList['classQuery'];
        else
            $classQuery = '';

        if ( isset( $generalFilterList['classAttributeQuery'] ) )
            $classAttributeQuery = $generalFilterList[ 'classAttributeQuery'];
        else
            $classAttributeQuery = '';

        if ( isset( $generalFilterList['sqlPermissionChecking'] ) )
            $sqlPermissionChecking = $generalFilterList['sqlPermissionChecking'];
        else
            $sqlPermissionChecking = array( 'from' => '',
                                            'where' => '' );

        if ( isset( $generalFilterList['subTreeSQL'] ) )
        {
            $subTreeTable = $generalFilterList['subTreeTable'];
            $subTreeSQL = $generalFilterList['subTreeSQL'];
        }
        else
        {
            $subTreeTable = '';
            $subTreeSQL = '';
        }

        $totalObjectCount = $this->fetchTotalObjectCount();

        $stopWordThresholdValue = 100;
        if ( $ini->hasVariable( 'SearchSettings', 'StopWordThresholdValue' ) )
            $stopWordThresholdValue = $ini->variable( 'SearchSettings', 'StopWordThresholdValue' );

        $stopWordThresholdPercent = 60;
        if ( $ini->hasVariable( 'SearchSettings', 'StopWordThresholdPercent' ) )
            $stopWordThresholdPercent = $ini->variable( 'SearchSettings', 'StopWordThresholdPercent' );

        $searchThresholdValue = $totalObjectCount;
        if ( $totalObjectCount > $stopWordThresholdValue )
        {
            $searchThresholdValue = (int)( $totalObjectCount * ( $stopWordThresholdPercent / 100 ) );
        }

        foreach ( $searchPartsArray as $searchPart )
        {
//                $wordID = $searchWord['id'];


            // do not search words that are too frequent
            if ( $searchPart['object_count'] < $searchThresholdValue )
            {
//                $tmpTableCount++;
                $searchPartText = $searchPart['sql_part'];
                if ( $i == 0 )
                {
                    $table = $db->generateUniqueTempTableName( 'ezsearch_tmp_%', 0 );
                    $this->saveCreatedTempTableName( 0, $table );
                    $db->createTempTable( "CREATE TEMPORARY TABLE $table ( contentobject_id int primary key not null, published int )" );
                    $db->query( "INSERT INTO $table SELECT DISTINCT ezsearch_object_word_link.contentobject_id, ezsearch_object_word_link.published
                    FROM
                       ezcontentobject
                       INNER JOIN ezsearch_object_word_link ON (ezsearch_object_word_link.contentobject_id = ezcontentobject.id)
                       $subTreeTable
                       INNER JOIN ezcontentclass ON (ezcontentclass.id = ezcontentobject.contentclass_id)
                       INNER JOIN ezcontentobject_tree ON (ezcontentobject_tree.contentobject_id = ezcontentobject.id)
                       {$sqlPermissionChecking['from']}
                    WHERE
                    $searchDateQuery
                    $sectionQuery
                    $classQuery
                    $classAttributeQuery
                    $searchPartText
                    $subTreeSQL
                    ezcontentclass.version = '0' AND
                    ezcontentobject_tree.node_id = ezcontentobject_tree.main_node_id
                    {$sqlPermissionChecking['where']}",
                    eZDBInterface::SERVER_SLAVE );
                }
                else
                {
                    $table = $db->generateUniqueTempTableName( 'ezsearch_tmp_%', $i );
                    $this->saveCreatedTempTableName( $i, $table );
                    $tmpTable0 = $this->getSavedTempTableName( 0 );
                    $db->createTempTable( "CREATE TEMPORARY TABLE $table ( contentobject_id int primary key not null, published int )" );
                    $db->query( "INSERT INTO $table SELECT DISTINCT ezsearch_object_word_link.contentobject_id, ezsearch_object_word_link.published
                    FROM
                       ezcontentobject
                       INNER JOIN ezsearch_object_word_link ON (ezsearch_object_word_link.contentobject_id = ezcontentobject.id)
                       $subTreeTable
                       INNER JOIN ezcontentclass ON (ezcontentclass.id = ezcontentobject.contentclass_id)
                       INNER JOIN ezcontentobject_tree ON (ezcontentobject_tree.contentobject_id = ezcontentobject.id)
                       INNER JOIN $tmpTable0 ON ($tmpTable0.contentobject_id = ezsearch_object_word_link.contentobject_id)
                       {$sqlPermissionChecking['from']}
                    WHERE
                    $searchDateQuery
                    $sectionQuery
                    $classQuery
                    $classAttributeQuery
                    $searchPartText
                    $subTreeSQL
                    ezcontentclass.version = '0' AND
                    ezcontentobject_tree.node_id = ezcontentobject_tree.main_node_id
                    {$sqlPermissionChecking['where']}",
                    eZDBInterface::SERVER_SLAVE );
                }
                $i++;
            }
            else
            {
                $stopWordArray[] = array( 'word' => $searchPart['word'] );
            }
        }
        $this->TempTablesCount = $i;
    }


    function getPhrases( $searchText )
    {
        $numQuotes = substr_count( $searchText, "\"" );

        $phraseTextArray = array();
        $fullText = $searchText;
        $nonPhraseText ='';

        $postPhraseText = $fullText;
        $pos = 0;
        if ( ( $numQuotes > 0 ) and ( ( $numQuotes % 2 ) == 0 ) )
        {
            for ( $i = 0; $i < ( $numQuotes / 2 ); $i ++ )
            {
                $quotePosStart = strpos( $searchText, '"',  $pos );
                $quotePosEnd = strpos( $searchText, '"',  $quotePosStart + 1 );

                $prePhraseText = substr( $searchText, $pos, $quotePosStart - $pos );
                $postPhraseText = substr( $searchText, $quotePosEnd +1 );
                $phraseText = substr( $searchText, $quotePosStart + 1, $quotePosEnd - $quotePosStart - 1 );

                $phraseTextArray[] = $phraseText;
                $nonPhraseText .= $prePhraseText;
                $pos = $quotePosEnd + 1;
            }
        }
        $nonPhraseText .= $postPhraseText;
        return array( 'phrases' => $phraseTextArray,
                      'nonPhraseText' => $nonPhraseText,
                      'fullText' => $fullText );
    }

    function buildSearchPartArray( $phraseTextArray, $nonPhraseText, $wordIDHash, $wildIDArray,
                                   $identifier = false )
    {
        $searchPartsArrayForPhrases = $this->buildSearchPartArrayForPhrases( $phraseTextArray, $wordIDHash,
                                                                             $identifier );
        $searchPartsArrayForWords = $this->buildSearchPartArrayForWords( $nonPhraseText, $wordIDHash,
                                                                         $wildIDArray, $identifier );
        $searchPartsArray = array_merge( $searchPartsArrayForPhrases, $searchPartsArrayForWords );
        return $searchPartsArray;
    }

    function buildSearchPartArrayForWords( $nonPhraseText, $wordIDHash, $wildIDArray, $identifier = false )
    {
        $searchPartsArray = array();
        $nonPhraseWordArray = $this->splitString( $nonPhraseText );
        $uniqueWordArray = array();

        $searchPart = array();
        if ( isset( $wildIDArray ) && count( $wildIDArray ) > 0 )
        {
            $searchPart['sql_part'] = '( ';
            $i = 0;
            $objectCount = -1;

            foreach( $wildIDArray as $wordInfo )
            {

                if ( $i > 0 )
                    $searchPart['sql_part'] .= ' or ';
                $searchPart['sql_part'] .= "ezsearch_object_word_link.word_id='". $wordInfo['id'] ."'";
                if ( $objectCount < intval($wordInfo['object_count']) )
                    $objectCount = intval($wordInfo['object_count']);
                $i++;
            }
            $searchPart['sql_part'] .= ' ) AND ';
            $searchPart['object_count'] = $objectCount;
            $searchPart['is_phrase'] = 0;
            $searchPartsArray[] = $searchPart;
            unset ( $searchPart );
        }

        foreach( array_keys( $wordIDHash ) as $word )
        {
            $searchPart = array();
            $searchPart['text'] = $word;
            $wordID = $wordIDHash[$word]['id'];
            $searchPart['sql_part'] = $this->buildSqlPartForWord( $wordID, $identifier );
            $searchPart['is_phrase'] = 0;
            $searchPart['object_count'] = $wordIDHash[$word]['object_count'];
            $searchPartsArray[] = $searchPart;
            unset ( $searchPart );
        }
        return $searchPartsArray;
    }

    function buildSearchPartArrayForPhrases( $phraseTextArray, $wordIDHash, $identifier = false )
    {
        // build an array of the word id's for each phrase
        $phraseIDArrayArray = array();
        foreach ( $phraseTextArray as $phraseText )
        {
            $wordArray = $this->splitString( $phraseText );
            $wordIDArray = array();
            foreach ( $wordArray as $word )
            {
                if ( !isset( $wordIDHash[$word] ) ) return array();
                $wordIDArray[] = $wordIDHash[$word]['id'];
            }
            $phraseIDArrayArray[] = $wordIDArray;
        }

        // build phrase SQL query part(s)
        $phraseSearchSQLArray = array();
        foreach ( $phraseIDArrayArray as $phraseIDArray )
        {
            $phraseSearchSQL = $this->buildPhraseSqlQueryPart( $phraseIDArray, $identifier );
            $phraseSearchSQLArray[] = $phraseSearchSQL;
        }

        ///Build search parts array for phrases and normal words
        $searchPartsArray = array();
        $i = 0;
        foreach ( $phraseTextArray as $phraseText )
        {
            foreach ( $phraseSearchSQLArray[$i] as $phraseSearchSubSQL )
            {
                $searchPart = array();
                $searchPart['text'] = $phraseText;
                $searchPart['sql_part'] = ' ( ' . $phraseSearchSubSQL . ' ) AND';
                $searchPart['is_phrase'] = 1;
                $searchPart['object_count'] = 0;
                $searchPartsArray[] = $searchPart;
            }
            unset( $searchPart );
            $i++;
        }

        return $searchPartsArray;
    }

    function prepareWordIDArraysForPattern( $searchText )
    {
        $db = eZDB::instance();
        $searchWordArray = $this->splitString( $searchText );


        // fetch the word id
        $wordQueryString = '';
        $patternWordsCount = 0;
        $patternWordArray = array();
        $wordsCount = 0;
        $patternWordQueryString = '';
        foreach ( $searchWordArray as $searchWord )
        {
            if ( preg_match ( "/(\w+)(\*)/", $searchWord, $matches ) )
            {
                if ( $patternWordsCount > 0 )
                    $patternWordQueryString .= " or ";
                $patternWordArray[] = $matches[1];
                $patternWordsCount++;
            }
            else
            {
                if ( $wordsCount > 0 )
                    $wordQueryString .= " or ";
                $wordQueryString .= " word='" . $db->escapeString( $searchWord ) . "' ";
                $wordsCount++;
            }
        }

        // create the word hash
        $wordIDArray = array();
        $wordIDHash = array();
        if ( $wordsCount > 0 )
        {
            $wordIDArrayRes = $db->arrayQuery( "SELECT id, word, object_count FROM ezsearch_word where $wordQueryString ORDER BY object_count" );

            foreach ( $wordIDArrayRes as $wordRes )
            {
                $wordIDArray[] = $wordRes['id'];
                $wordIDHash[$wordRes['word']] = array( 'id' => $wordRes['id'], 'word' => $wordRes['word'], 'object_count' => $wordRes['object_count'] );
            }
        }

        $patternWordIDHash = array();
        foreach ( $patternWordArray as $word )
        {
            $patternWordIDRes = $db->arrayQuery( "SELECT id, word, object_count FROM ezsearch_word where  word like '" . $db->escapeString( $word ) . "%'  order by object_count" );
            $matchedWords = array();
            foreach ( $patternWordIDRes as $wordRes )
            {
                $matchedWords[] = array( 'id' => $wordRes['id'], 'word' => $wordRes['word'], 'object_count' => $wordRes['object_count'] );
            }
            $patternWordIDHash[$word] = $matchedWords;
        }


        return array( 'wordIDArray' => $wordIDArray,
                      'wordIDHash' => $wordIDHash,
                      'patternWordIDHash' => $patternWordIDHash );
    }

    function prepareWordIDArrays( $searchText )
    {
        if ( trim( $searchText ) == "" )
        {
            $ini = eZINI::instance();
            if ( $ini->hasVariable( 'SearchSettings', 'AllowEmptySearch' ) and
                 $ini->variable( 'SearchSettings', 'AllowEmptySearch' ) != 'enabled' )
                return array();
        }

        $db = eZDB::instance();

        //extend search words for urls, by extracting parts without www. and add to the end of search text
        $matches = array();
        if ( preg_match_all("/www\.([^\.]+\.[^\s]+)\s?/i", $searchText, $matches ) );
        {
            if (isset($matches[1]) )
            {
                $searchText.= ' '.join(' ', $matches[1] );
            }
        }

        $searchWordArray = $this->splitString( $searchText );

        $wildCardWordArray = array();
        $i = 0;
        $wildCardQueryString = array();
        $wordQueryString = '';

        $ini = eZINI::instance();
        $wildSearchEnabled = ( $ini->variable( 'SearchSettings', 'EnableWildcard' ) == 'true' );
        if ( $wildSearchEnabled )
        {
            $minCharacters = $ini->variable( 'SearchSettings', 'MinCharacterWildcard' );
        }

        foreach ( $searchWordArray as $searchWord )
        {
            $wordLength = strlen( $searchWord ) - 1;

            if ( $wildSearchEnabled && ( $wordLength >= $minCharacters ) )
            {
                if ( $searchWord[$wordLength] == '*' )
                {
                    $baseWord = substr( $searchWord, 0, $wordLength );
                    $wildCardQueryString[] = " word LIKE '". $db->escapeString( $baseWord ) ."%' ";
                    continue;
                }
                else if ( $searchWord[0] == '*' ) /* Change this to allow searching for shorter/longer words using wildcard */
                {
                    $baseWord = substr( $searchWord, 1, $wordLength );
                    $wildCardQueryString[] = " word LIKE '%". $db->escapeString( $baseWord ) ."' ";
                    continue;
                }
            }
            if ( $i > 0 )
                $wordQueryString .= " or ";

            $wordQueryString .= " word='" . $db->escapeString( $searchWord ) . "' ";
            $i++;
        }

        if ( strlen( $wordQueryString ) > 0 )
            $wordIDArrayRes = $db->arrayQuery( "SELECT id, word, object_count FROM ezsearch_word where $wordQueryString ORDER BY object_count" );
        foreach ( $wildCardQueryString as $wildCardQuery )
        {
            $wildCardWordArray[] = $db->arrayQuery( "SELECT id, word, object_count FROM ezsearch_word where $wildCardQuery order by object_count" );
        }

        // create the word hash
        $wordIDArray = array();
        $wordIDHash = array();
        if ( isset( $wordIDArrayRes ) && is_array( $wordIDArrayRes ) )
        {
            foreach ( $wordIDArrayRes as $wordRes )
            {
                $wordIDArray[] = $wordRes['id'];
                $wordIDHash[$wordRes['word']] = array( 'id' => $wordRes['id'], 'word' => $wordRes['word'], 'object_count' => $wordRes['object_count'] );
            }
        }

        $wildIDArray = array();
        $wildCardCount = 0;
        foreach ( array_keys( $wildCardWordArray ) as $key )
        {
            if ( is_array( $wildCardWordArray[$key] ) && count( $wildCardWordArray[$key] ) > 0 )
            {
                $wildCardCount++;
                foreach ( $wildCardWordArray[$key] as $wordRes )
                {
                    $wildIDArray[] = array( 'id' => $wordRes['id'], 'object_count' => $wordRes['object_count'] );
                }
            }
        }
        return array( 'wordIDArray' => $wordIDArray,
                      'wordIDHash' => $wordIDHash,
                      'wildIDArray' => $wildIDArray,
                      'wildCardCount' => $wildCardCount );
    }

    function fetchTotalObjectCount()
    {
        // Get the total number of objects
        $db = eZDB::instance();
        $objectCount = array();
        $objectCount = $db->arrayQuery( "SELECT COUNT(*) AS count FROM ezcontentobject" );
        $totalObjectCount = $objectCount[0]["count"];
        return $totalObjectCount;
    }

    /*!
     Will remove all search words and object/word relations.
    */
    function cleanup()
    {
        $db = eZDB::instance();
        $db->begin();
        $db->query( "DELETE FROM ezsearch_word" );
        $db->query( "DELETE FROM ezsearch_object_word_link" );
        $db->commit();
    }

    /*!
     \return true if the search part is incomplete.
    */
    function isSearchPartIncomplete( $part )
    {
        switch ( $part['subtype'] )
        {
            case 'fulltext':
            {
                if ( !isset( $part['value'] ) || $part['value'] == '' )
                    return true;
            }
            break;

            case 'patterntext':
            {
                if ( !isset( $part['value'] ) || $part['value'] == '' )
                    return true;
            }
            break;

            case 'integer':
            {
                if ( !isset( $part['value'] ) || $part['value'] == '' )
                    return true;
            }
            break;

            case 'integers':
            {
                if ( !isset( $part['values'] ) || count( $part['values'] ) == 0 )
                    return true;
            }
            break;

            case 'byrange':
            {
                if ( !isset( $part['from'] ) || $part['from'] == '' ||
                     !isset( $part['to'] ) || $part['to'] == '' )
                    return true;
            }
            break;

            case 'byidentifier':
            {
                if ( !isset( $part['value'] ) || $part['value'] == '' )
                    return true;
            }
            break;

            case 'byidentifierrange':
            {
                if ( !isset( $part['from'] ) || $part['from'] == '' ||
                     !isset( $part['to'] ) || $part['to'] == '' )
                    return true;
            }
            break;

            case 'integersbyidentifier':
            {
                if ( !isset( $part['values'] ) || count( $part['values'] ) == 0 )
                    return true;
            }
            break;

            case 'byarea':
            {
                if ( !isset( $part['from'] ) || $part['from'] == '' ||
                     !isset( $part['to'] ) || $part['to'] == '' ||
                     !isset( $part['minvalue'] ) || $part['minvalue'] == '' ||
                     !isset( $part['maxvalue'] ) || $part['maxvalue'] == '' )
                {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Commit the changes to the search engine
     */
    public function commit()
    {
    }

    /**
     * Update the section in the search engine
     *
     * @param array $objectID
     * @param int $sectionID
     * @return void
     * @see eZSearch::updateObjectsSection()
     */
    public function updateObjectsSection( array $objectIDs, $sectionID )
    {
        $db = eZDB::instance();
        $db->query( "UPDATE ezsearch_object_word_link SET section_id='$sectionID' WHERE " .
            $db->generateSQLINStatement( $objectIDs, 'contentobject_id', false, true, 'int' )
        );
    }


    public $TempTablesCount = 0;
    public $CreatedTempTablesNames = array();
}

?>
