<?php
//
// Definition of eZSearchLog class
//
// Created on: <08-Aug-2002 10:27:21 bf>
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
        $phraseRes =& $db->arrayQuery( "SELECT * FROM ezsearch_search_phrase WHERE phrase='$phrase'" );

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
    function &mostFrequentPhraseArray( )
    {
        $db =& eZDB::instance();

        $query = 'SELECT count(*) as phrase_count, AVG( ezsearch_return_count.count ) AS result_count, ezsearch_search_phrase.* FROM
                    ezsearch_search_phrase,
                    ezsearch_return_count
                  WHERE
                    ezsearch_search_phrase.id = ezsearch_return_count.phrase_id
                  GROUP BY
                    ezsearch_search_phrase.id, ezsearch_search_phrase.phrase
                  ORDER BY phrase_count DESC LIMIT 50';

        $phraseArray =& $db->arrayQuery( $query );

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
