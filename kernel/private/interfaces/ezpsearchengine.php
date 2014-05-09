<?php
/**
 * File containing the ezpSearchEngine interface
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 * @subpackage search
 */

/**
 * This interface is used as the basis for the different search engine implementation
 * @package kernel
 * @subpackage search
 */
interface ezpSearchEngine
{
    /**
     * Whether a commit operation is required after adding/removing objects.
     *
     * @see commit()
     * @return bool
     */
    public function needCommit();

    /**
     * Whether calling removeObject() is required when updating an object.
     *
     * @see removeObject()
     * @return bool
     */
    public function needRemoveWithUpdate();

    /**
     * Adds object $contentObject to the search database.
     *
     * @param eZContentObject $contentObject Object to add to search engine
     * @param bool $commit Whether to commit after adding the object
     * @return bool True if the operation succeed.
     */
    public function addObject( $contentObject, $commit = true );

    /**
     * Removes object $contentObject from the search database.
     *
     * @deprecated Since 5.0, use removeObjectById()
     * @param eZContentObject $contentObject the content object to remove
     * @param bool $commit Whether to commit after removing the object
     * @return bool True if the operation succeed.
     */
    public function removeObject( $contentObject, $commit = null );

    /**
     * Removes a content object by Id from the search database.
     *
     * @since 5.0
     * @param int $contentObjectId The content object to remove by id
     * @param bool $commit Whether to commit after removing the object
     * @return bool True if the operation succeed.
     */
    public function removeObjectById( $contentObjectId, $commit = null );

    /**
     * Searches $searchText in the search database.
     *
     * @see supportedSearchTypes()
     * @param string $searchText Search term
     * @param array $params Search parameters
     * @param array $searchTypes Search types
     */
    public function search( $searchText, $params = array(), $searchTypes = array() );

    /**
     * Returns an array describing the supported search types by the search engine.
     *
     * @see search()
     * @return array
     */
    public function supportedSearchTypes();

    /**
     * Commit the changes made to the search engine.
     *
     * @see needCommit()
     */
    public function commit();
}
?>
