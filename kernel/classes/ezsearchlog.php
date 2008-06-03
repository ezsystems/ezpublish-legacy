<?php
//
// Definition of eZSearchLog class
//
// Created on: <08-Aug-2002 10:27:21 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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
  \class eZSearchLog ezsearchlog.php
  \brief eZSearchLog handles logging of search phrases

*/

//include_once( 'lib/ezdb/classes/ezdb.php' );

class eZSearchLog
{
    /*!
     Logs a search query so that we can retrieve statistics afterwords.
    */
    static function addPhrase( $phrase, $returnCount )
    {
        $db = eZDB::instance();
        $db->begin();

        //include_once( 'lib/ezi18n/classes/ezchartransform.php' );
        $trans = eZCharTransform::instance();
        $phrase = $trans->transformByGroup( trim( $phrase ), 'lowercase' );

        $phrase = $db->escapeString( $phrase );

        // find or store the phrase
        $phraseRes = $db->arrayQuery( "SELECT id FROM ezsearch_search_phrase WHERE phrase='$phrase'" );

        if ( count( $phraseRes ) == 1 )
        {
            $phraseID = $phraseRes[0]['id'];
            $db->query( "UPDATE ezsearch_search_phrase
                         SET    phrase_count = phrase_count + 1,
                                result_count = result_count + $returnCount
                         WHERE  id = $phraseID" );
        }
        else
        {
            $db->query( "INSERT INTO
                              ezsearch_search_phrase ( phrase, phrase_count, result_count )
                         VALUES ( '$phrase', 1, $returnCount )" );

            /* when breaking BC: delete next line */
            $phraseID = $db->lastSerialID( 'ezsearch_search_phrase', 'id' );
        }

        /* when breaking BC: delete next lines */
        /* ezsearch_return_count is not used any more by eZ Publish
           but perhaps someone else added some functionality... */
        $time = time();
        // store the search result
        $db->query( "INSERT INTO
                           ezsearch_return_count ( phrase_id, count, time )
                     VALUES ( '$phraseID', '$returnCount', '$time' )" );
        /* end of BC breaking delete*/

        $db->commit();
    }

    /*!
     Returns the most frequent search phrases, which did not get hits.
    */
    static function mostFrequentPhraseArray( $parameters = array( ) )
    {
        $db = eZDB::instance();

        $query = 'SELECT phrase_count, result_count / phrase_count AS result_count, id, phrase
                  FROM   ezsearch_search_phrase
                  ORDER BY phrase_count DESC';

        return $db->arrayQuery( $query, $parameters );
    }

    /*!
     \static
     Removes all stored phrases and search match counts from the database.
    */
    static function removeStatistics()
    {
        $db = eZDB::instance();
        $query = "DELETE FROM ezsearch_search_phrase";
        $db->query( $query );
        /* when breaking BC: delete those two lines */
        $query = "DELETE FROM ezsearch_return_count";
        $db->query( $query );
    }
}

?>
