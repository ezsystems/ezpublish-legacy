<?php
//
// Definition of eZSearch class
//
// Created on: <25-Jun-2002 10:56:09 bf>
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
  \class eZSearch
  \ingroup eZKernel
  \brief eZSearch handles indexing of objects to the search engine

*/

include_once( 'lib/ezutils/classes/ezini.php' );

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

        $searchEngineString = 'ezsearch';
        if ( $ini->hasVariable( 'SearchSettings', 'SearchEngine' ) == true )
        {
            $searchEngineString = $ini->variable( 'SearchSettings', 'SearchEngine' );
        }

        // fetch the correct search engine implementation
        include_once( 'kernel/search/plugins/' . strToLower( $searchEngineString ) . '/' . strToLower( $searchEngineString ) . '.php' );
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

        $searchEngineString = 'ezsearch';
        if ( $ini->hasVariable( 'SearchSettings', 'SearchEngine' ) == true )
        {
            $searchEngineString = $ini->variable( 'SearchSettings', 'SearchEngine' );
        }

        // fetch the correct search engine implementation
        include_once( 'kernel/search/plugins/' . strToLower( $searchEngineString ) . '/' . strToLower( $searchEngineString ) . '.php' );
        $searchEngine = new $searchEngineString;

        $searchEngine->addObject( $contentObject, '/content/view/' /*, $metaData*/ );
    }

    /*!
     \static
     Runs a query to the search engine.
    */
    function &search( $searchText, $params, $searchTypes = array() )
    {
        $ini =& eZINI::instance();

        $searchEngineString = 'ezsearch';
        if ( $ini->hasVariable( 'SearchSettings', 'SearchEngine' ) == true )
        {
            $searchEngineString = $ini->variable( 'SearchSettings', 'SearchEngine' );
        }

        include_once( 'kernel/search/plugins/' . strToLower( $searchEngineString ) . '/' . strToLower( $searchEngineString ) . '.php' );
        $searchEngine = new $searchEngineString;

        return $searchEngine->search( $searchText, $params, $searchTypes );
    }

    /*!
     \static
    */
    function &normalizeText( $text )
    {
        $ini =& eZINI::instance();

        $searchEngineString = 'ezsearch';
        if ( $ini->hasVariable( 'SearchSettings', 'SearchEngine' ) == true )
        {
            $searchEngineString = $ini->variable( 'SearchSettings', 'SearchEngine' );
        }

        include_once( 'kernel/search/plugins/' . strToLower( $searchEngineString ) . '/' . strToLower( $searchEngineString ) . '.php' );
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

        $searchEngineString = 'ezsearch';
        if ( $ini->hasVariable( 'SearchSettings', 'SearchEngine' ) == true )
        {
            $searchEngineString = $ini->variable( 'SearchSettings', 'SearchEngine' );
        }

        include_once( 'kernel/search/plugins/' . strToLower( $searchEngineString ) . '/' . strToLower( $searchEngineString ) . '.php' );
        $searchEngine = new $searchEngineString;

        // This method was renamed in pre 3.5 trunk
        if ( method_exists( $searchEngine, 'supportedSearchTypes' ) )
        {
            $searchTypesDefinition =& $searchEngine->supportedSearchTypes();  // new and correct
        }
        else
        {
            $searchTypesDefinition =& $searchEngine->suportedSearchTypes();  // deprecated
        }

        $searchArray = array();
        $andSearchParts = array();

        $http =& eZHTTPTool::instance();

        foreach ( $searchTypesDefinition['types'] as $searchType )
        {
            $postVariablePrefix = 'Content_search_' . $searchType['type'] . '_' . $searchType['subtype'] . '_';
            //print $postVariablePrefix . "\n";
            //print_r( $searchType['params'] );
            $searchArrayPartForType = array();

            $searchPart = array();
            $valuesFetched = false;
            $valuesMissing = false;
            foreach ( $searchType['params'] as $parameter )
            {
                eZDebugSetting::writeDebug( 'kernel-search-ezsearch', $postVariablePrefix . $parameter,
                                            'post variable to check' );

                if ( $http->hasVariable( $postVariablePrefix . $parameter ) )
                {
                    $values = $http->variable( $postVariablePrefix . $parameter );
                    eZDebugSetting::writeDebug( 'kernel-search-ezsearch', $values, 'fetched values' );

                    foreach ( $values as $i => $value )
                    {
                        $searchArrayPartForType[$i][$parameter] = $values[$i];
                        $valuesFetched = true;
                    }
                }
                else
                {
                    eZDebugSetting::writeDebug( 'kernel-search-ezsearch', $postVariablePrefix . $parameter,
                                                'post variable does not exist' );
                    $valuesMissing = true;
                    break;
                }
            }

            if ( $valuesFetched == true && $valuesMissing == false )
            {
                eZDebugSetting::writeDebug( 'kernel-search-ezsearch', 'adding values to search' );
                foreach ( array_keys( $searchArrayPartForType ) as $key )
                {
                    $part =& $searchArrayPartForType[$key];
                    $part['type'] = $searchType['type'];
                    $part['subtype'] = $searchType['subtype'];

                    if ( $part['type'] == 'attribute' )
                    {
                        // Remove incomplete search parts from the search.
                        // An incomplete search part is for instance an empty text field,
                        // or a select box with no selected values.
                        $removePart = false;
                        switch ( $part['subtype'] )
                        {
                            case 'fulltext':
                            {
                                if ( !isSet( $part['value'] ) || $part['value'] == '' )
                                    $removePart = true;
                            }
                            break;

                            case 'patterntext':
                            {
                                if ( !isSet( $part['value'] ) || $part['value'] == '' )
                                    $removePart = true;
                            }
                            break;

                            case 'integer':
                            {
                                if ( !isSet( $part['value'] ) || $part['value'] == '' )
                                    $removePart = true;
                            }
                            break;

                            case 'integers':
                            {
                                if ( !isSet( $part['values'] ) || count( $part['values'] ) == 0 )
                                    $removePart = true;
                            }
                            break;

                            case 'byrange':
                            {
                                if ( !isSet( $part['from'] ) || $part['from'] == '' ||
                                     !isSet( $part['to'] ) || $part['to'] == '' )
                                    $removePart = true;
                            }
                            break;

                            case 'byidentifier':
                            {
                                if ( !isSet( $part['value'] ) || $part['value'] == '' )
                                    $removePart = true;
                            }
                            break;

                            case 'byidentifierrange':
                            {
                                if ( !isSet( $part['from'] ) || $part['from'] == '' ||
                                     !isSet( $part['to'] ) || $part['to'] == '' )
                                    $removePart = true;
                            }
                            break;

                            case 'integersbyidentifier':
                            {
                                if ( !isSet( $part['values'] ) || count( $part['values'] ) == 0 )
                                    $removePart = true;
                            }
                            break;

                            case 'byarea':
                            {
                                if ( !isSet( $part['from'] ) || $part['from'] == '' ||
                                     !isSet( $part['to'] ) || $part['to'] == '' ||
                                     !isSet( $part['minvalue'] ) || $part['minvalue'] == '' ||
                                     !isSet( $part['maxvalue'] ) || $part['maxvalue'] == '' )
                                {
                                    $removePart = true;
                                }
                            }
                        }

                        if ( $removePart )
                        {
                            eZDebugSetting::writeDebug( 'kernel-search-ezsearch', $searchArrayPartForType[$key],
                                                        'removing incomplete search part' );
                            unSet( $searchArrayPartForType[$key] );
                        }
                    }
                }
                $andSearchParts = array_merge( $andSearchParts, $searchArrayPartForType );
            }
        }
        $generalFilter = array();
        foreach ( $searchTypesDefinition['general_filter'] as $searchType )
        {

            $postVariablePrefix = 'Content_search_' . $searchType['type'] . '_' . $searchType['subtype'] . '_';

            $searchArrayPartForType = array();

            $searchPart = array();
            $valuesFetched = false;
            $valuesMissing = false;

            foreach ( $searchType['params'] as $parameter )
            {
                $varName = '';
                $paramName = '';
                if ( is_array( $parameter ) )
                {
                    $varName = $postVariablePrefix . $parameter['value'];
                    $paramName = $parameter['value'];
                }
                else
                {
                    $varName = $postVariablePrefix . $parameter;
                    $paramName = $parameter;
                }

                eZDebugSetting::writeDebug( 'kernel-search-ezsearch', $varName,
                                            'post variable to check' );

                if ( $http->hasVariable( $varName ) )
                {
                    $values = $http->variable( $varName );
                    eZDebugSetting::writeDebug( 'kernel-search-ezsearch', $values, 'fetched values' );
                    $searchArrayPartForType[$paramName] = $values;
                    $valuesFetched = true;

                }
                else
                {
                    eZDebugSetting::writeDebug( 'kernel-search-ezsearch', $varName,
                                                'post variable does not exist' );
                    $valuesMissing = true;
                    break;
                }
            }

            if ( $valuesFetched == true && $valuesMissing == false )
            {
                eZDebugSetting::writeDebug( 'kernel-search-ezsearch', 'adding values to search' );

                $part =& $searchArrayPartForType;

                $part['type'] = $searchType['type'];
                $part['subtype'] = $searchType['subtype'];

                if ( $part['type'] == 'general' )
                {
                        // Remove incomplete search parts from the search.
                        // An incomplete search part is for instance an empty text field,
                        // or a select box with no selected values.
                    $removePart = false;
                    switch ( $part['subtype'] )
                    {
                        case 'class':
                        {
                            if ( !isSet( $part['value'] ) ||
                                 ( is_array( $part['value'] ) && count( $part['value'] ) == 0 ) ||
                                 ( !is_array( $part['value'] ) && $part['value'] == '' ) )
                                $removePart = true;
                        }
                        break;
                        case 'publishdate':
                        {
                            if ( !isSet( $part['value'] ) ||
                                 ( is_array( $part['value'] ) && count( $part['value'] ) == 0 ) ||
                                 ( !is_array( $part['value'] ) && $part['value'] == '' ) )
                                $removePart = true;
                        }
                        break;
                        case 'subtree':
                        {
                            if ( !isSet( $part['value'] ) ||
                                 ( is_array( $part['value'] ) && count( $part['value'] ) == 0 ) ||
                                 ( !is_array( $part['value'] ) && $part['value'] == '' ) )

                                $removePart = true;
                        }
                        break;
                    }

                    if ( $removePart )
                    {
                        eZDebugSetting::writeDebug( 'kernel-search-ezsearch', $searchArrayPartForType[$key],
                                                    'removing incomplete search part' );
                        unSet( $searchArrayPartForType[$key] );
                        continue;
                    }
                }

                $generalFilter = array_merge( $generalFilter, array( $searchArrayPartForType ) );
            }


        }

        if ( $andSearchParts != null )
            $searchArray['and'] =& $andSearchParts;
        if ( $generalFilter != null )
            $searchArray['general'] =& $generalFilter;

        eZDebugSetting::writeDebug( 'kernel-search-ezsearch', $searchArray, 'search array' );
        return $searchArray;
    }

    /*!
     \static
     Tells the current search engine to cleanup up all data.
    */
    function cleanup()
    {
        $ini =& eZINI::instance();

        $searchEngineString = 'ezsearch';
        if ( $ini->hasVariable( 'SearchSettings', 'SearchEngine' ) == true )
        {
            $searchEngineString = $ini->variable( 'SearchSettings', 'SearchEngine' );
        }

        // fetch the correct search engine implementation
        include_once( 'kernel/search/plugins/' . strToLower( $searchEngineString ) . '/' . strToLower( $searchEngineString ) . '.php' );
        $searchEngine = new $searchEngineString;

        if ( method_exists( $searchEngine, 'cleanup' ) )
        {
            $searchEngine->cleanup();
        }
    }

}

?>
