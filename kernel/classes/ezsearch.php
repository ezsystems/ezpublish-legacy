<?php
//
// Definition of eZSearch class
//
// Created on: <25-Jun-2002 10:56:09 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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
  \class eZSearch
  \ingroup eZKernel
  \brief eZSearch handles indexing of objects to the search engine

*/

class eZSearch
{
    function eZSearch()
    {

    }

   /*!
     \static
     determine how to pass the commit argument, for deletes and updates
     maybe this needs to be further splitted
    */

    static function needCommit()
    {
        $searchEngine = eZSearch::getEngine();

        if ( is_object( $searchEngine ) && method_exists( $searchEngine, 'needCommit'))
        {
            return $searchEngine->needCommit();
        }
        else
        {
            return true;
        }
    }
    /*!
     \static
     See if a remove is needed in an update of content objects
    */

    static function needRemoveWithUpdate()
    {
        $searchEngine = eZSearch::getEngine();

        if ( is_object( $searchEngine ) && method_exists( $searchEngine, 'needRemoveWithUpdate'))
        {
            return $searchEngine->needRemoveWithUpdate();
        }
        else
        {
            return true;
        }
    }

     /*!
     \static
     Will remove the index from the given object from the search engine
     A commit parameter is added since 4.1 to accomodate requirements of several search plugins
    */
    static function removeObject( $contentObject, $commit = true )
    {
        $searchEngine = eZSearch::getEngine();

        if ( is_object( $searchEngine ) )
        {
            $searchEngine->removeObject( $contentObject, $commit );
        }
    }

    /*!
     \static
     Will index the content object to the search engine.
     A commit parameter is added since 4.1 to accomodate requirements of several search plugins
    */
    static function addObject( $contentObject, $commit = true )
    {
        $searchEngine = eZSearch::getEngine();

        if ( is_object( $searchEngine ) )
        {
            $searchEngine->addObject( $contentObject, $commit );
        }
    }

    /*!
     \static
     Runs a query to the search engine.
    */
    static function search( $searchText, $params, $searchTypes = array() )
    {
        $searchEngine = eZSearch::getEngine();

        if ( is_object( $searchEngine ) )
        {
            return $searchEngine->search( $searchText, $params, $searchTypes );
        }
    }

    /*!
     \static
    */
    static function normalizeText( $text )
    {
        $searchEngine = eZSearch::getEngine();

        if ( is_object( $searchEngine ) )
        {
            return $searchEngine->normalizeText( $text );
        }

        return '';
    }

    /*!
     \static
      returns search parameters in array based on supported search types and post variables
     */
    static function buildSearchArray()
    {
        $searchEngine = eZSearch::getEngine();

        $searchArray = array();
        $andSearchParts = array();
        $searchTypesDefinition = array( 'types' => array(), 'general_filter' => array() );

        if ( is_object( $searchEngine ) )
        {
            // This method was renamed in pre 3.5 trunk
            if ( method_exists( $searchEngine, 'supportedSearchTypes' ) )
            {
                $searchTypesDefinition = $searchEngine->supportedSearchTypes();  // new and correct
            }
            else
            {
                $searchTypesDefinition = $searchEngine->suportedSearchTypes();  // deprecated
            }
        }

        $http = eZHTTPTool::instance();

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

                        // This functionality has been moved to the search engine.
                        // Checking if it is defined in the search engine
                        if ( method_exists( $searchEngine, 'isSearchPartIncomplete' ) )
                        {
                            $removePart = $searchEngine->isSearchPartIncomplete( $part );
                        }
                        else // for backwards compatibility
                        {
                            $removePart = false;
                            switch ( $part['subtype'] )
                            {
                                case 'fulltext':
                                {
                                    if ( !isset( $part['value'] ) || $part['value'] == '' )
                                        $removePart = true;
                                }
                                break;

                                case 'patterntext':
                                {
                                    if ( !isset( $part['value'] ) || $part['value'] == '' )
                                        $removePart = true;
                                }
                                break;

                                case 'integer':
                                {
                                    if ( !isset( $part['value'] ) || $part['value'] == '' )
                                        $removePart = true;
                                }
                                break;

                                case 'integers':
                                {
                                    if ( !isset( $part['values'] ) || count( $part['values'] ) == 0 )
                                        $removePart = true;
                                }
                                break;

                                case 'byrange':
                                {
                                    if ( !isset( $part['from'] ) || $part['from'] == '' ||
                                         !isset( $part['to'] ) || $part['to'] == '' )
                                        $removePart = true;
                                }
                                break;

                                case 'byidentifier':
                                {
                                    if ( !isset( $part['value'] ) || $part['value'] == '' )
                                        $removePart = true;
                                }
                                break;

                                case 'byidentifierrange':
                                {
                                    if ( !isset( $part['from'] ) || $part['from'] == '' ||
                                         !isset( $part['to'] ) || $part['to'] == '' )
                                        $removePart = true;
                                }
                                break;

                                case 'integersbyidentifier':
                                {
                                    if ( !isset( $part['values'] ) || count( $part['values'] ) == 0 )
                                        $removePart = true;
                                }
                                break;

                                case 'byarea':
                                {
                                    if ( !isset( $part['from'] ) || $part['from'] == '' ||
                                         !isset( $part['to'] ) || $part['to'] == '' ||
                                         !isset( $part['minvalue'] ) || $part['minvalue'] == '' ||
                                         !isset( $part['maxvalue'] ) || $part['maxvalue'] == '' )
                                    {
                                        $removePart = true;
                                    }
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
                            if ( !isset( $part['value'] ) ||
                                 ( is_array( $part['value'] ) && count( $part['value'] ) == 0 ) ||
                                 ( !is_array( $part['value'] ) && $part['value'] == '' ) )
                                $removePart = true;
                        }
                        break;
                        case 'publishdate':
                        {
                            if ( !isset( $part['value'] ) ||
                                 ( is_array( $part['value'] ) && count( $part['value'] ) == 0 ) ||
                                 ( !is_array( $part['value'] ) && $part['value'] == '' ) )
                                $removePart = true;
                        }
                        break;
                        case 'subtree':
                        {
                            if ( !isset( $part['value'] ) ||
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
        {
            $searchArray['and'] = $andSearchParts;
        }
        if ( $generalFilter != null )
        {
            $searchArray['general'] = $generalFilter;
        }

        eZDebugSetting::writeDebug( 'kernel-search-ezsearch', $searchArray, 'search array' );
        return $searchArray;
    }

    /*!
     \static
     Tells the current search engine to cleanup up all data.
    */
    static function cleanup()
    {
        $searchEngine = eZSearch::getEngine();

        if ( is_object( $searchEngine ) && method_exists( $searchEngine, 'cleanup' ) )
        {
            $searchEngine->cleanup();
        }
    }

    /*!
     \static
     Get object instance of eZSearch engine to use.

     \return instance of eZSearch class.
    */
    static function getEngine()
    {
        // Get instance if already created.
        $instanceName = 'eZSearchPlugin_' . $GLOBALS['eZCurrentAccess'];
        if ( isset( $GLOBALS[$instanceName] ) )
        {
            return $GLOBALS[$instanceName];
        }

        $ini = eZINI::instance();

        $searchEngineString = 'ezsearch';
        if ( $ini->hasVariable( 'SearchSettings', 'SearchEngine' ) == true )
        {
            $searchEngineString = $ini->variable( 'SearchSettings', 'SearchEngine' );
        }

        $directoryList = array();
        if ( $ini->hasVariable( 'SearchSettings', 'ExtensionDirectories' ) )
        {
            $extensionDirectories = $ini->variable( 'SearchSettings', 'ExtensionDirectories' );
            if ( is_array( $extensionDirectories ) )
            {
                $directoryList = eZExtension::expandedPathList( $extensionDirectories, 'search/plugins' );
            }
        }

        $kernelDir = array( 'kernel/search/plugins' );
        $directoryList = array_merge( $kernelDir, $directoryList );

        foreach( $directoryList as $directory )
        {
            $searchEngineFile = implode( '/', array( $directory, strtolower( $searchEngineString ), strtolower( $searchEngineString ) ) ) . '.php';

            if ( file_exists( $searchEngineFile ) )
            {
                eZDebugSetting::writeDebug( 'kernel-search-ezsearch', 'Loading search engine from ' . $searchEngineFile, 'eZSearch::getEngine' );

                include_once( $searchEngineFile );
                $GLOBALS[$instanceName] = new $searchEngineString();
                return $GLOBALS[$instanceName];
            }
        }

        eZDebug::writeDebug( 'Unable to find the search engine:' . $searchEngineString, 'eZSearch' );
        eZDebug::writeDebug( 'Tried paths: ' . implode( ', ', $directoryList ), 'eZSearch' );
        return false;
    }

    /*
     * @since eZ Publish 4.1
     * @description new methods that search plugins can implement when meta data is updated (outside publish operations)
     *
     */

    public static function updateNodeSection( $nodeID, $sectionID )
    {
        $searchEngine = eZSearch::getEngine();

        if ( is_object( $searchEngine ) && method_exists( $searchEngine, 'updateNodeSection'))
        {
            return $searchEngine->updateNodeSection( $nodeID, $sectionID );
        }

        return false;
    }

    public static function updateNodeVisibility( $nodeID, $action )
    {
        $searchEngine = eZSearch::getEngine();

        if ( is_object( $searchEngine ) && method_exists( $searchEngine, 'updateNodeVisibility'))
        {
            return $searchEngine->updateNodeVisibility( $nodeID, $action );
        }

        return false;
    }

    public static function addNodeAssignment( $mainNodeID, $objectID, $nodeAssignmentIDList )
    {
        $searchEngine = eZSearch::getEngine();

        if ( is_object( $searchEngine ) && method_exists( $searchEngine, 'addNodeAssignment'))
        {
            return $searchEngine->addNodeAssignment( $mainNodeID, $objectID, $nodeAssignmentIDList );
        }

        return false;
    }

    public static function removeNodeAssignment( $mainNodeID, $newMainNodeID, $objectID, $nodeAssigmentIDList )
    {
        $searchEngine = eZSearch::getEngine();

        if ( is_object( $searchEngine ) && method_exists( $searchEngine, 'removeNodeAssignment'))
        {
            return $searchEngine->removeNodeAssignment( $mainNodeID, $newMainNodeID, $objectID, $nodeAssigmentIDList );
        }

        return false;
    }

    public static function updateObjectState( $objectID, $objectStateList )
    {
        $searchEngine = eZSearch::getEngine();

        if ( is_object( $searchEngine ) && method_exists( $searchEngine, 'updateObjectState'))
        {
            return $searchEngine->updateObjectState( $objectID, $objectStateList );
        }

        return false;
    }
    
    public static function swapNode( $nodeID, $selectedNodeID, $nodeIdList = array() )
    {
        $searchEngine = eZSearch::getEngine();

        if ( is_object( $searchEngine ) && method_exists( $searchEngine, 'swapNode'))
        {
            return $searchEngine->swapNode( $nodeID, $selectedNodeID, $nodeIdList = array() );
        }

        return false;
    }

    
}

?>
