<?php
//
// Definition of openFts class
//
// Created on: <09-Aug-2002 10:07:15 sp>
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

/*! \file openfts.php
*/

/*!
  \class openFts openfts.php
  \brief The class openFts does

*/
//include_once( "lib/ezdb/classes/ezdb.php" );
//include_once( "lib/ezutils/classes/ezini.php" );
//include_once( 'lib/ezfile/classes/ezdir.php' );
require_once( "lib/ezutils/classes/ezdebug.php" );

class openFts
{
    /*!
     Constructor
    */
    function openFts()
    {

    }



    /*!
     Adds an object to the search database.
    */
    function addObject( $contentObject, $uri )
    {
        $db = eZDB::instance();

        $contentObjectID = $contentObject->attribute( 'id' );

        $currentVersion =& $contentObject->currentVersion();

        $allText = '';

        $sys = eZSys::instance();
        $storagePath = $sys->storageDirectory;

        $tmpFname = tempnam ( $storagePath, "txt$contentObjectID" );
        $fp = fopen( $tmpFname, "w" );
        chmod ( $tmpFname, 0755 );
        foreach ( $currentVersion->attributes() as $attribute )
        {
            $classAttribute =& $attribute->contentClassAttribute();
            if ( $classAttribute->attribute( "is_searchable" ) == 1 )
            {
                // strip tags
                $text = strip_tags( $attribute->metaData() );

                // Strip multiple whitespaces
                $text = str_replace(".", " ", $text );
                $text = str_replace(",", " ", $text );
                $text = str_replace("'", " ", $text );
                $text = str_replace("\"", " ", $text );

                $text = str_replace("\n", " ", $text );
                $text = str_replace("\r", " ", $text );
                $text = preg_replace("(\s+)", " ", $text );

                // Split text on whitespace
                //$wordArray =& split( " ", $text );
                fwrite( $fp, ' '.$text );
            }
        }

        fclose( $fp );
        $tmpFname = realpath( $tmpFname );
        eZDebugSetting::writeDebug( 'kernel-search-openfts', "/home/sp/projects/php/ezpublish3/bin/openfts/index.pl nextgen_test $contentObjectID $tmpFname","indexing command");
        $retStr = system( "/home/sp/projects/php/ezpublish3/bin/openfts/index.pl nextgen_test $contentObjectID $tmpFname", $foo );
        eZDebugSetting::writeDebug( 'kernel-search-openfts', $retStr.$foo, "error string" );
        //  unlink($tmpFname);


    }

    /*!
     \static
    */
    function removeObject( $contentObject )
    {
        $db = eZDB::instance();
        $contentObjectID = $contentObject->attribute( "id" );
        eZDebugSetting::writeDebug( 'kernel-search-openfts', "/home/sp/projects/php/ezpublish3/bin/openfts/delete.pl nextgen_test $contentObjectID " , "delete error string" );

        $retStr = system( "/home/sp/projects/php/ezpublish3/bin/openfts/delete.pl nextgen_test $contentObjectID " , $foo );
        eZDebugSetting::writeDebug( 'kernel-search-openfts', $retStr.$foo, "delete error string" );



    }

    /*!
     \static
     Runs a query to the search engine.
    */
    function search( $searchText, $params = array() )
    {
        $db = eZDB::instance();

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
        //  $searchText = preg_replace( "(\"+)", "", $searchText );

        // find the phrases

//        $sqlPartsString = system("/home/sp/projects/php/ezpublish3/bin/openfts/search.pl nextgen_test $searchText", &$foo);
        $sqlPartsString = `/home/sp/projects/php/ezpublish3/bin/openfts/search.pl -p nextgen_test -z $searchText`;
        eZDebugSetting::writeDebug( 'kernel-search-openfts', "/home/sp/projects/php/ezpublish3/bin/openfts/search.pl nextgen_test $searchText","indexing command");
        $sqlPartsArray = explode('|||', $sqlPartsString );
        $out = $sqlPartsArray[0];
        $tables = $sqlPartsArray[1];
        $condition = $sqlPartsArray[2];
        $order = $sqlPartsArray[3];
        $searchQuery = "SELECT
                                ezcontentobject.id,
                                ezcontentobject.main_node_id,
                                ezcontentobject.name,
                                txt.tid,
                                txt.txt,
                                $out
                        FROM
                             txt$tables,
                             ezcontentobject
                        WHERE
                             $condition AND
                             ezcontentobject.id = txt.tid
                        ORDER BY $order";

        $searchCountQuery = "SELECT
                               count(txt.tid) AS count
                        FROM
                             txt$tables,
                             ezcontentobject
                        WHERE
                             $condition AND
                             ezcontentobject.id = txt.tid ";

        $objectRes = array();

        $objectRes = $db->arrayQuery( $searchQuery );
        $objectCountRes = $db->arrayQuery( $searchCountQuery );
        $searchCount = $objectCountRes[0]['count'];

//        eZDebugSetting::writeDebug( 'kernel-search-openfts', $objectRes, 'search result' );

        return  array( "SearchResult" => $objectRes,
                       "SearchCount" => $searchCount );
    }

    /*!
     \private
     \return Returns an array of words created from the input string.
    */
    function splitString( $text )
    {
        // strip quotes
        $text = preg_replace("#'#", "", $text );
        $text = preg_replace( "#\"#", "", $text );

        // Strip multiple whitespace
        $text = trim( $text );
        $text = preg_replace("(\s+)", " ", $text );

        // Split text on whitespace
        $wordArray = split( " ", $text );

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
