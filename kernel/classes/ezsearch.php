<?php
//
// Definition of eZSearch class
//
// Created on: <25-Jun-2002 10:56:09 bf>
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
// http://ez.no/products/licences/professional/. For pricing of this licence
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
  \class eZSearch
  \ingroup eZKernel
  \brief eZSearch handles indexing of objects to the search engine

*/

include_once( "lib/ezutils/classes/ezini.php" );

class eZSearch
{
    /*!
    */
    function eZSearch()
    {

    }

    /*!
     \static
     Will remove the index from the given object from the search engine
    */
    function removeObject( $contentObject )
    {
        $ini =& eZINI::instance();

        $searchEngineString = "ezsearch";
        if ( $ini->hasVariable( "SearchSettings", "SearchEngine" ) == true )
        {
            $searchEngineString = $ini->variable( "SearchSettings", "SearchEngine" );
        }

        // fetch the correct search engine implementation
        include_once( "kernel/search/plugins/" . strToLower( $searchEngineString ) . "/" . strToLower( $searchEngineString ) . ".php" );
        $searchEngine = new $searchEngineString;

        $searchEngine->removeObject( $contentObject );
    }

    /*!
     \static
     Will index the content object to the search engine.
    */
    function addObject( $contentObject )
    {
        $ini =& eZINI::instance();

        $searchEngineString = "ezsearch";
        if ( $ini->hasVariable( "SearchSettings", "SearchEngine" ) == true )
        {
            $searchEngineString = $ini->variable( "SearchSettings", "SearchEngine" );
        }

        // fetch the correct search engine implementation
        include_once( "kernel/search/plugins/" . strToLower( $searchEngineString ) . "/" . strToLower( $searchEngineString ) . ".php" );
        $searchEngine = new $searchEngineString;

        $searchEngine->addObject( $contentObject, "/content/view/" /*, $metaData*/ );
    }

    /*!
     \static
     Runs a query to the search engine.
    */
    function &search( $searchText, $params, $searchTypes = array() )
    {
        $ini =& eZINI::instance();

        $searchEngineString = "ezsearch";
        if ( $ini->hasVariable( "SearchSettings", "SearchEngine" ) == true )
        {
            $searchEngineString = $ini->variable( "SearchSettings", "SearchEngine" );
        }

        include_once( "kernel/search/plugins/" . strToLower( $searchEngineString ) . "/" . strToLower( $searchEngineString ) . ".php" );
        $searchEngine = new $searchEngineString;

        return $searchEngine->search( $searchText, $params, $searchTypes );
    }

    /*!
     \static
    */
    function &normalizeText( $text )
    {
        $ini =& eZINI::instance();

        $searchEngineString = "ezsearch";
        if ( $ini->hasVariable( "SearchSettings", "SearchEngine" ) == true )
        {
            $searchEngineString = $ini->variable( "SearchSettings", "SearchEngine" );
        }

        include_once( "kernel/search/plugins/" . strToLower( $searchEngineString ) . "/" . strToLower( $searchEngineString ) . ".php" );
        $searchEngine = new $searchEngineString;

        return $searchEngine->normalizeText( $text );
    }

    /*!
     \static
      returns search parameters in array based on supported search types and post variables
     */
    function &buildSearchArray()
    {
        $ini =& eZINI::instance();

        $searchEngineString = "ezsearch";
        if ( $ini->hasVariable( "SearchSettings", "SearchEngine" ) == true )
        {
            $searchEngineString = $ini->variable( "SearchSettings", "SearchEngine" );
        }

        include_once( "kernel/search/plugins/" . strToLower( $searchEngineString ) . "/" . strToLower( $searchEngineString ) . ".php" );
        $searchEngine = new $searchEngineString;

        $searchTypesDefinition =& $searchEngine->suportedSearchTypes();

        $searchArray = array();
        $andSearchParts = array();

        $http =& eZHTTPTool::instance();

        foreach ( $searchTypesDefinition['types'] as $searchType )
        {
            $postVariablePrefix = 'Content_search_' . $searchType['type'] . '_' . $searchType['subtype'] . '_';

            $searchArrayPartForType = array();

            $searchPart = array();
            $valuesFetched = false;
            $valuesMissing = false;
            foreach ( $searchType['params'] as $parameter )
            {
                eZDebugSetting::writeDebug( 'kernel-search-ezsearch', $postVariablePrefix . $parameter, "post var to check" );

                if ( $http->hasVariable( $postVariablePrefix . $parameter ) )
                {
                    $values = $http->variable( $postVariablePrefix . $parameter );
                    eZDebugSetting::writeDebug( 'kernel-search-ezsearch', $values, "fetched values" );
                    $valuesCount = count( $values );

                    for ( $i = 0; $i < $valuesCount; $i++ )
                    {
                        // Empty values are excluded from the search
                        // Need to add more cases here
                        if ( ( $searchType['subtype'] == 'byrange' && $values[$i] == '' ) ||
                             ( $searchType['subtype'] == 'byidentifierrange' && $values[$i] == '' ) )
                        {
                            eZDebugSetting::writeDebug( 'kernel-search-ezsearch', $values, "empty value" );
                            $valuesMissing = true;
                            break;
                        }
                        $searchArrayPartForType[$i][$parameter] = $values[$i] ;
                        $valuesFetched = true;
                    }
                }
                else
                {
                    eZDebugSetting::writeDebug( 'kernel-search-ezsearch', "variable does not exist" );
                    $valuesMissing = true;
                    break;
                }
            }
            if ( $valuesFetched == true && $valuesMissing == false )
            {
                eZDebugSetting::writeDebug( 'kernel-search-ezsearch', "adding values to search" );
                foreach ( array_keys( $searchArrayPartForType ) as $key )
                {
                    $part =& $searchArrayPartForType[$key];
                    $part['type'] = $searchType['type'];
                    $part['subtype'] = $searchType['subtype'];
                }
                $andSearchParts = array_merge( $andSearchParts, $searchArrayPartForType );
            }
        }

        $searchArray['and'] =& $andSearchParts;
        eZDebugSetting::writeDebug( 'kernel-search-ezsearch', $searchArray, "search array" );
        return $searchArray;
    }

}

?>
