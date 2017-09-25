<?php
/**
 * File containing the eZSearch class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZSearch
  \ingroup eZKernel
  \brief eZSearch handles indexing of objects to the search engine

*/

class eZSearch
{
   /*!
     \static
     determine how to pass the commit argument, for deletes and updates
     maybe this needs to be further splitted
    */

    static function needCommit()
    {
        $searchEngine = eZSearch::getEngine();

        if ( $searchEngine instanceof ezpSearchEngine )
        {
            return $searchEngine->needCommit();
        }
        return true;
    }
    /*!
     \static
     See if a remove is needed in an update of content objects
    */

    static function needRemoveWithUpdate()
    {
        $searchEngine = eZSearch::getEngine();

        if ( $searchEngine instanceof ezpSearchEngine )
        {
            return $searchEngine->needRemoveWithUpdate();
        }
        return true;
    }

    /**
     * Removes object $contentObject from the search database.
     *
     * @deprecated Since 5.0, use removeObjectById()
     * @param eZContentObject $contentObject the content object to remove
     * @param bool $commit Whether to commit after removing the object
     * @return bool True if the operation succeed.
     */
    static function removeObject( $contentObject, $commit = null )
    {
        $searchEngine = eZSearch::getEngine();

        if ( $searchEngine instanceof ezpSearchEngine )
        {
            return $searchEngine->removeObjectById( $contentObject->attribute( "id" ), $commit );
        }

        return false;
    }

    /**
     * Removes a content object by Id from the search database.
     *
     * @since 5.0
     * @param int $contentObjectId the content object to remove by id
     * @param bool $commit Whether to commit after removing the object
     * @return bool True if the operation succeed.
     */
    static function removeObjectById( $contentObjectId, $commit = null )
    {
        $searchEngine = eZSearch::getEngine();
        if ( $searchEngine instanceof ezpSearchEngine )
        {
            return $searchEngine->removeObjectById( $contentObjectId, $commit );
        }

        return false;
    }

    /**
     * Adds object $contentObject to the search database.
     *
     * @param eZContentObject $contentObject Object to add to search engine
     * @param bool $commit Whether to commit after adding the object
     * @return bool True if the operation succeed.
     */
    static function addObject( $contentObject, $commit = true )
    {
        $searchEngine = eZSearch::getEngine();

        if ( $searchEngine instanceof ezpSearchEngine )
        {
            return $searchEngine->addObject( $contentObject, $commit );
        }

        return false;
    }

    /**
     * Runs a query to the search engine.
     *
     * @param string $searchText
     * @param array $params
     * @param array $searchTypes Deprecated
     * @return mixed
     */
    static function search( $searchText, $params, $searchTypes = array() )
    {
        $searchEngine = eZSearch::getEngine();

        if ( $searchEngine instanceof ezpSearchEngine )
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

        if ( $searchEngine instanceof ezpSearchEngine )
        {
            return $searchEngine->normalizeText( $text );
        }

        return '';
    }

    /*!
     \static
     Tells the current search engine to cleanup up all data.
    */
    static function cleanup()
    {
        $searchEngine = eZSearch::getEngine();

        if ( $searchEngine instanceof ezpSearchEngine && method_exists( $searchEngine, 'cleanup' ) )
        {
            $searchEngine->cleanup();
        }
    }

    /**
     * Get object instance of eZSearch engine to use.
     *
     * @return \ezpSearchEngine|bool Returns false (+ writes debug) if no engine was found
    */
    static public function getEngine()
    {
        // Get instance if already created.
        $instanceName = "eZSearchPlugin_" . $GLOBALS["eZCurrentAccess"]["name"];
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

    /**
     * Notifies search engine about the change of section of a set of objects
     *
     * @since 4.6
     * @param array $objectIDs
     * @param int $sectionID
     * @return false|mixed false in case method is undefined, otherwise return the result of the search engine call
     */
    public static function updateObjectsSection( array $objectIDs, $sectionID )
    {
        $searchEngine = eZSearch::getEngine();
        if ( $searchEngine instanceof ezpSearchEngine && method_exists( $searchEngine, 'updateObjectsSection' ) )
        {
            return $searchEngine->updateObjectsSection( $objectIDs, $sectionID );
        }
        return false;
    }

    /**
     * Notifies search engine about section changes
     *
     * @since 4.1
     * @param int $nodeID
     * @param int $sectionID
     * @return false|mixed False in case method is undefined, otherwise return the result of the search engine call
     */
    public static function updateNodeSection( $nodeID, $sectionID )
    {
        $searchEngine = eZSearch::getEngine();

        if ( $searchEngine instanceof ezpSearchEngine && method_exists( $searchEngine, 'updateNodeSection' ) )
        {
            return $searchEngine->updateNodeSection( $nodeID, $sectionID );
        }

        return false;
    }

    /**
     * Notifies search engine about node visibility changes
     *
     * @since 4.1
     * @param int $nodeID
     * @param string $action "hide" or "show"
     * @return false|mixed False in case method is undefined, otherwise return the result of the search engine call
     */
    public static function updateNodeVisibility( $nodeID, $action )
    {
        $searchEngine = eZSearch::getEngine();

        if ( $searchEngine instanceof ezpSearchEngine && method_exists( $searchEngine, 'updateNodeVisibility' ) )
        {
            return $searchEngine->updateNodeVisibility( $nodeID, $action );
        }

        return false;
    }

    /**
     * Notifies search engine about new node assignments added
     *
     * @since 4.1
     * @param int $mainNodeID
     * @param int $objectID
     * @param array $nodeAssignmentIDList
     * @param bool $isMoved true if node is being moved
     * @return false|mixed False in case method is undefined, otherwise return the result of the search engine call
     */
    public static function addNodeAssignment( $mainNodeID, $objectID, $nodeAssignmentIDList, $isMoved = false )
    {
        $searchEngine = eZSearch::getEngine();

        if ( $searchEngine instanceof ezpSearchEngine && method_exists( $searchEngine, 'addNodeAssignment' ) )
        {
            return $searchEngine->addNodeAssignment( $mainNodeID, $objectID, $nodeAssignmentIDList, $isMoved );
        }

        return false;
    }

    /**
     * Notifies search engine about removed node assignments and what the new main node is (same if not changed)
     *
     * @since 4.1
     * @param int $mainNodeID
     * @param int $newMainNodeID
     * @param int $objectID
     * @param array $nodeAssigmentIDList
     * @return false|mixed False in case method is undefined, otherwise return the result of the search engine call
     */
    public static function removeNodeAssignment( $mainNodeID, $newMainNodeID, $objectID, $nodeAssigmentIDList )
    {
        $searchEngine = eZSearch::getEngine();

        if ( $searchEngine instanceof ezpSearchEngine && method_exists( $searchEngine, 'removeNodeAssignment' ) )
        {
            return $searchEngine->removeNodeAssignment( $mainNodeID, $newMainNodeID, $objectID, $nodeAssigmentIDList );
        }

        return false;
    }

    /**
     * Notifies search engine about nodes being removed
     *
     * @since 4.1
     * @param array $nodeIdList Array of node ID to remove.
     * @return false|mixed False in case method is undefined, otherwise return the result of the search engine call
     */
    public static function removeNodes( array $nodeIdList )
    {
        $searchEngine = self::getEngine();

        if ( $searchEngine instanceof ezpSearchEngine && method_exists( $searchEngine, 'removeNodes' ) )
        {
            return $searchEngine->removeNodes( $nodeIdList );
        }

        return false;
    }

    /**
     * Notifies search engine about updates to object states
     *
     * @since 4.1
     * @param int $objectID
     * @param array $objectStateList
     * @return false|mixed False in case method is undefined, otherwise return the result of the search engine call
     */
    public static function updateObjectState( $objectID, $objectStateList )
    {
        $searchEngine = eZSearch::getEngine();

        if ( $searchEngine instanceof ezpSearchEngine && method_exists( $searchEngine, 'updateObjectState' ) )
        {
            return $searchEngine->updateObjectState( $objectID, $objectStateList );
        }

        return false;
    }

    /**
     * Notifies search engine about an swap node operation
     *
     * @since 4.1
     * @param int $nodeID
     * @param int $selectedNodeID
     * @param array $nodeIdList
     * @return false|mixed False in case method is undefined, otherwise return the result of the search engine call
     */
    public static function swapNode( $nodeID, $selectedNodeID, $nodeIdList = array() )
    {
        $searchEngine = eZSearch::getEngine();

        if ( $searchEngine instanceof ezpSearchEngine && method_exists( $searchEngine, 'swapNode' ) )
        {
            return $searchEngine->swapNode( $nodeID, $selectedNodeID, $nodeIdList = array() );
        }

        return false;
    }
}

?>
