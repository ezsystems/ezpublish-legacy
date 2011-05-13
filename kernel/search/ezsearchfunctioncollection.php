<?php
/**
 * File containing the eZSearchFunctionCollection class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZSearchFunctionCollection ezsearchfunctioncollection.php
  \brief The class eZSearchFunctionCollection does

*/

class eZSearchFunctionCollection
{
    /*!
     Constructor
    */
    function eZSearchFunctionCollection()
    {
    }

    function fetchSearchListCount()
    {
        $db = eZDB::instance();
        $query = "SELECT count(*) as count FROM ezsearch_search_phrase";
        $searchListCount = $db->arrayQuery( $query );

        return array( 'result' => $searchListCount[0]['count'] );
    }

    function fetchSearchList( $offset, $limit )
    {
        $parameters = array( 'offset' => $offset, 'limit'  => $limit );
        $mostFrequentPhraseArray = eZSearchLog::mostFrequentPhraseArray( $parameters );

        return array( 'result' => $mostFrequentPhraseArray );
    }

}

?>
