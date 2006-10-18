<?php
//
// Definition of eZSearchLog class
//
// Created on: <08-Aug-2002 10:27:21 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
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

/*!
  \class eZSearchLog ezsearchlog.php
  \brief eZSearchLog handles logging of search phrases

*/

include_once( 'lib/ezdb/classes/ezdb.php' );

class eZSearchLog
{
    /*!
    */
    function eZSearchLog()
    {

    }

    /*!
     Logs a search query so that we can retreive statistics afterwords.
    */
    function addPhrase( $phrase, $returnCount )
    {
        $db =& eZDB::instance();

        $phrase = trim( $phrase );
        $phrase = $db->escapeString( $phrase );

        // find or store the phrase
        $phraseRes = $db->arrayQuery( "SELECT * FROM ezsearch_search_phrase WHERE phrase='$phrase'" );

        if ( count( $phraseRes ) == 1 )
        {
            $phraseID = $phraseRes[0]['id'];
        }
        else
        {
            $db->query( "INSERT INTO
                              ezsearch_search_phrase ( phrase )
                         VALUES ( '$phrase' )" );

            $phraseID = $db->lastSerialID( 'ezsearch_search_phrase', 'id' );
        }

        $time = mktime();
        // store the search result
        $db->query( "INSERT INTO
                           ezsearch_return_count ( phrase_id, count, time )
                     VALUES ( '$phraseID', '$returnCount', '$time' )" );
    }

    /*!
     Returns the most frequent search phrases, which did not get hits.
    */
    function &mostFrequentPhraseArray( $parameters = array( ) )
    {
        $db =& eZDB::instance();

        $query = 'SELECT count(*) as phrase_count, AVG( ezsearch_return_count.count ) AS result_count, ezsearch_search_phrase.* FROM
                    ezsearch_search_phrase,
                    ezsearch_return_count
                  WHERE
                    ezsearch_search_phrase.id = ezsearch_return_count.phrase_id
                  GROUP BY
                    ezsearch_search_phrase.id, ezsearch_search_phrase.phrase
                  ORDER BY phrase_count DESC';

        $phraseArray = $db->arrayQuery( $query, $parameters );

        return $phraseArray;
    }

    /*!
     \static
     Removes all stored phrases and search match counts from the database.
    */
    function removeStatistics()
    {
        $db =& eZDB::instance();
        $query = "DELETE FROM ezsearch_search_phrase";
        $db->query( $query );
        $query = "DELETE FROM ezsearch_return_count";
        $db->query( $query );
    }
}

?>
