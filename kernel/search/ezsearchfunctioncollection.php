<?php
//
// Definition of eZSearchFunctionCollection class
//
// Created on: <06-Oct-2002 16:19:31 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*! \file ezcontentfunctioncollection.php
*/

/*!
  \class eZSearchFunctionCollection ezsearchfunctioncollection.php
  \brief The class eZSearchFunctionCollection does

*/

//include_once( 'kernel/error/errors.php' );

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
        //include_once( "kernel/classes/ezsearchlog.php" );

        $db = eZDB::instance();
        $query = "SELECT count(*) as count FROM ezsearch_search_phrase";
        $searchListCount = $db->arrayQuery( $query );

        return array( 'result' => $searchListCount[0]['count'] );
    }

    function fetchSearchList( $offset, $limit )
    {
        //include_once( "kernel/classes/ezsearchlog.php" );

        $parameters = array( 'offset' => $offset, 'limit'  => $limit );
        $mostFrequentPhraseArray = eZSearchLog::mostFrequentPhraseArray( $parameters );

        return array( 'result' => $mostFrequentPhraseArray );
    }

}

?>
