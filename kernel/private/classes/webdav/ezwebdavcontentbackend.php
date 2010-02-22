<?php
//
// This is the eZWebDAVContentBackend class. Manages WebDAV sessions.
// Based on the eZ Components Webdav component.
//
// Created on: <14-Jul-2008 15:15:15 as>
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
  \class eZWebDAVContentBackend ezwebdavcontentbackend.php
  \ingroup eZWebDAV
  \brief Provides access to eZ Publish kernel using WebDAV.
         Based on the eZ Components Webdav component.

  @todo Replace direct path manipulation with path factory from ezcWebdav
  @todo Fix appendLogEntry to write in only one log file
  @todo Fix using [0] for content object attributes (could be another index in some classes)
  @todo Add lock/unlock calls in setProperty and removeProperty
  @todo Use PathPrefix, PathPrefixExclude (site.ini) and StartNode (webdav.ini) in all functions where necessary
  @todo Remove all todos.

*/

/**
 * WebDAV backend for eZ Publish, based on eZ Components Webdav component.
 */
class eZWebDAVContentBackend extends ezcWebdavSimpleBackend implements ezcWebdavLockBackend
{
    /**
     * The name of the content folder in eZ Publish.
     */
    const VIRTUAL_CONTENT_FOLDER_NAME = 'Content';

    /**
     * The name of the media folder in eZ Publish.
     */
    const VIRTUAL_MEDIA_FOLDER_NAME = 'Media';

    /**
     * The ini file which holds settings for WebDAV.
     */
    const WEBDAV_INI_FILE = "webdav.ini";

    /**
     * Mimetype for directories.
     */
    const DIRECTORY_MIMETYPE = 'httpd/unix-directory';

    /**
     * Mimetype for eZ Publish objects which don't have a mimetype.
     */
    const DEFAULT_MIMETYPE = "application/octet-stream";

    /**
     * Default size in bytes for eZ Publish objects which don't have a size.
     */
    const DEFAULT_SIZE = 0;

    /**
     * Names of live properties from the DAV: namespace which will be handled
     * live, and should not be stored like dead properties.
     *
     * @var array(string)
     */
    protected $handledLiveProperties = array(
        'getcontentlength',
        'getlastmodified',
        'creationdate',
        'displayname',
        'getetag',
        'getcontenttype',
        'resourcetype',
        'supportedlock',
        'lockdiscovery',
    );

    /**
     * Contains an array with classes that are considered folder.
     *
     * @var array(string)
     */
    protected $FolderClasses = null;

    /**
     * The list of available sites.
     *
     * @var array(string)
     */
    protected $availableSites = array();

    /**
     * Holds the retrieved nodes to allow for faster retrieval on subsequent requests.
     *
     * @var array(string=>array())
     */
    protected $cachedNodes = array();

    /**
     * Holds the retrieved properties to allow for faster retrieval on subsequent requests.
     *
     * @var array(string=>array())
     */
    protected $cachedProperties = array();

    /**
     * Specifies weather to log with appendLogEntry().
     *
     * Value defined in webdav.ini, read in appendLogEntry().
     *
     * @var bool
     */
    protected static $useLogging;

    /**
     * Creates a new backend instance.
     */
    public function __construct()
    {
        // @as @todo check how to make Article class to be handled as a resource (document) instead of a collection
        $webdavINI = eZINI::instance( self::WEBDAV_INI_FILE );

        $folderClasses = array();
        if ( $webdavINI->hasGroup( 'GeneralSettings' ) and
             $webdavINI->hasVariable( 'GeneralSettings', 'FolderClasses' ) )
        {
            $folderClasses = $webdavINI->variable( 'GeneralSettings', 'FolderClasses' );
        }
        $this->FolderClasses = $folderClasses;

        $ini = eZINI::instance();
        $this->availableSites = $ini->variable( 'SiteSettings', 'SiteList' );
    }

    /**
     * Locks the backend.
     *
     * Tries to lock the backend. If the lock is already owned by this process,
     * locking is successful. If $timeout is reached before a lock could be
     * acquired, an {@link ezcWebdavLockTimeoutException} is thrown. Waits
     * $waitTime microseconds between attempts to lock the backend.
     * 
     * @param int $waitTime 
     * @param int $timeout 
     * @return void
     */
    public function lock( $waitTime, $timeout )
    {
        // @as @todo implement locking with eZ Publish functionality (object states)
    }

    /**
     * Removes the lock.
     * 
     * @return void
     */
    public function unlock()
    {
        // @as @todo implement locking with eZ Publish functionality (object states)
    }

    /**
     * Wait and get lock for complete directory tree.
     *
     * Acquire lock for the complete tree for read or write operations. This
     * does not implement any priorities for operations, or check if several
     * read operation may run in parallel. The plain locking should / could be
     * extended by something more sophisticated.
     *
     * If the tree already has been locked, the method waits until the lock can
     * be acquired.
     *
     * The optional second parameter $readOnly indicates wheather a read only
     * lock should be acquired. This may be used by extended implementations,
     * but it is not used in this implementation.
     *
     * @param bool $readOnly
     */
    protected function acquireLock( $readOnly = false )
    {
        // @as @todo implement locking with eZ Publish functionality (object states)
    }

    /**
     * Free lock.
     *
     * Frees the lock after the operation has been finished.
     */
    protected function freeLock()
    {
        // @as @todo implement locking with eZ Publish functionality (object states)
    }

    /**
     * Returns all child nodes.
     *
     * Get all nodes from the resource identified by $source up to the given
     * depth. Reuses the method {@link getCollectionMembers()}, but you may
     * want to overwrite this implementation by somethings which fits better
     * with your backend.
     *
     * @param string $source
     * @param int $depth
     * @return array(ezcWebdavResource|ezcWebdavCollection)
     */
    protected function getNodes( $requestUri, $depth )
    {
        $source = $requestUri;
        $nodeInfo = $this->getNodeInfo( $requestUri );

        if ( !$nodeInfo['nodeExists'] )
        {
            return array();
        }

        // No special handling for plain resources
        if ( !$nodeInfo['isCollection'] )
        {
            return array( new ezcWebdavResource( $source, $this->getAllProperties( $source ) ) );
        }

        // For zero depth just return the collection
        if ( $depth === ezcWebdavRequest::DEPTH_ZERO )
        {
            return array( new ezcWebdavCollection( $source, $this->getAllProperties( $source ) ) );
        }

        $nodes = array( new ezcWebdavCollection( $source, $this->getAllProperties( $source ) ) );
        $recurseCollections = array( $source );

        // Collect children for all collections listed in $recurseCollections.
        for ( $i = 0; $i < count( $recurseCollections ); ++$i )
        {
            $source = $recurseCollections[$i];

            // add the slash at the end of the path if it is missing
            if ( $source{strlen( $source ) - 1} !== '/' )
            {
                $source .= '/';
            }
            $children = $this->getCollectionMembers( $source, $depth );

            foreach ( $children as $child )
            {
                $nodes[] = $child;

                // Check if we should recurse deeper, and add collections to
                // processing list in this case.
                if ( $child instanceof ezcWebdavCollection
                     && $depth === ezcWebdavRequest::DEPTH_INFINITY
                     && $child->path !== $source ) // @as added for recursive DEPTH_INFINITY
                {
                    $recurseCollections[] = $child->path;
                }
            }
        }

        return $nodes;
    }

    /**
     * Returns the contents of a resource.
     *
     * This method returns the content of the resource identified by $path as a
     * string.
     *
     * @param string $target
     * @return string
     */
    protected function getResourceContents( $target )
    {
        $result = array();
        $fullPath = $target;
        $target = $this->splitFirstPathElement( $fullPath, $currentSite );

        $data = $this->getVirtualFolderData( $result, $currentSite, $target, $fullPath );

        if ( isset( $data['file'] ) )
        {
            return file_get_contents( $data['file'] );
        }
        return false;
    }

    /**
     * Returns members of collection.
     *
     * Returns an array with the members of the collection identified by $path.
     * The returned array can contain {@link ezcWebdavCollection}, and {@link
     * ezcWebdavResource} instances and might also be empty, if the collection
     * has no members.
     *
     * Added $depth.
     *
     * @param string $path
     * @param int $depth Added by @as
     * @return array(ezcWebdavResource|ezcWebdavCollection)
     */
    protected function getCollectionMembers( $path, $depth = ezcWebdavRequest::DEPTH_INFINITY )
    {
        $properties = $this->handledLiveProperties;
        $fullPath = $path;
        $collection = $this->splitFirstPathElement( $fullPath, $currentSite );

        if ( !$currentSite )
        {
            // Display the root which contains a list of sites
            $entries = $this->fetchSiteListContent( $fullPath, $depth, $properties );
        }
        else
        {
            $entries = $this->getVirtualFolderCollection( $currentSite, $collection, $fullPath, $depth, $properties );
        }

        $contents = array();

        foreach ( $entries as $entry )
        {
            // prevent infinite recursion
            if ( $path === $entry['href'] )
            {
                continue;
            }

            if ( $entry['mimetype'] === self::DIRECTORY_MIMETYPE )
            {
                // Add collection without any children
                $contents[] = new ezcWebdavCollection( $entry['href'], $this->getAllProperties( $path ) );
            }
            else
            {
                // If this is not a collection, don't leave a trailing '/'
                // on the href. If you do, Goliath gets confused.
                $entry['href'] = rtrim( $entry['href'], '/' );

                // Add files without content
                $contents[] = new ezcWebdavResource( $entry['href'], $this->getAllProperties( $path ) );
            }
        }

        return $contents;
    }

    /**
     * Returns an array with information about the node with path $path.
     *
     * The returned array is of this form:
     * <code>
     * array( 'nodeExists' => boolean, 'isCollection' => boolean )
     * </code>
     *
     * @param string $path
     * @return array(string=>boolean)
     */
    protected function getNodeInfo( $requestUri, $source = null )
    {
        $path = ( $source === null ) ? $requestUri : $source;

        $fullPath = $path;
        $target = $this->splitFirstPathElement( $path, $currentSite );

        if ( !$currentSite )
        {
            $data = $this->fetchSiteListContent( $fullPath, 0, array() );
            $data = $data[0];
            $data['nodeExists'] = true;
            $data['isCollection'] = true;
        }
        else
        {
            if ( !in_array( $currentSite, $this->availableSites ) )
            {
                $data = array();
                $data['nodeExists'] = false;
                $data['isCollection'] = false;
            }
            else
            {
                if ( $target === "" )
                {
                    $data = $this->fetchVirtualSiteContent( $fullPath, $currentSite, 0, array() );
                }
                else if ( in_array( $target, array( self::virtualContentFolderName(), self::virtualMediaFolderName() ) ) )
                {
                    $data = $this->fetchContainerNodeInfo( $fullPath, $currentSite, $target );
                }
                else
                {
                    $data = $this->getCollectionContent( $fullPath, 0, array() );
                }

                if ( is_array( $data ) )
                {
                    $data = $data[0];
                    $data['nodeExists'] = true;
                    $data['isCollection'] = ( $data['mimetype'] === self::DIRECTORY_MIMETYPE );
                    $data['href'] = $fullPath; // @as @todo move this hack to correct function
                }
                else
                {
                    $data = array();
                    $data['nodeExists'] = false;
                    $data['isCollection'] = false;
                }
            }
        }
        return $data;
    }

    /**
     * Returns a property of a resource.
     * 
     * Returns the property with the given $propertyName, from the resource
     * identified by $path. You may optionally define a $namespace to receive
     * the property from.
     *
     * @param string $path 
     * @param string $propertyName 
     * @param string $namespace 
     * @return ezcWebdavProperty
     */
    public function getProperty( $path, $propertyName, $namespace = 'DAV:' )
    {
        $storage = $this->getPropertyStorage( $path );

        // Handle dead propreties
        if ( $namespace !== 'DAV:' )
        {
            $properties = $storage->getAllProperties();
            return $properties[$namespace][$name];
        }

        if ( !isset( $this->cachedProperties[$path] ) )
        {
            $this->cachedProperties[$path] = $this->getNodeInfo( $path );
        }

        $item = $this->cachedProperties[$path];

        // Handle live properties
        switch ( $propertyName )
        {
            case 'getcontentlength':
                $property = new ezcWebdavGetContentLengthProperty();
                $mimetype = isset( $item['mimetype'] ) ? $item['mimetype'] : self::DEFAULT_MIMETYPE;
                $size = isset( $item['size'] ) ? $item['size'] : self::DEFAULT_SIZE;
                $property->length = ( $mimetype === self::DIRECTORY_MIMETYPE ) ?
                    ezcWebdavGetContentLengthProperty::COLLECTION :
                    (string) $size;
                break;

            case 'getlastmodified':
                $property = new ezcWebdavGetLastModifiedProperty();
                $timestamp = isset( $item['mtime'] ) ? $item['mtime'] : time();
                $property->date = new ezcWebdavDateTime( '@' . $timestamp );
                break;

            case 'creationdate':
                $property = new ezcWebdavCreationDateProperty();
                $timestamp = isset( $item['ctime'] ) ? $item['ctime'] : time();
                $property->date = new ezcWebdavDateTime( '@' . $timestamp );
                break;

            case 'displayname':
                $property = new ezcWebdavDisplayNameProperty();
                $property->displayName = isset( $item['name'] ) ? $item['name'] : 'Unknown displayname';
                break;

            case 'getcontenttype':
                $property = new ezcWebdavGetContentTypeProperty();
                $property->mime = isset( $item['mimetype'] ) ? $item['mimetype'] : self::DEFAULT_MIMETYPE;
                break;

            case 'getetag':
                $property = new ezcWebdavGetEtagProperty();
                $mimetype = isset( $item['mimetype'] ) ? $item['mimetype'] : self::DEFAULT_MIMETYPE;
                $size = isset( $item['size'] ) ? $item['size'] : self::DEFAULT_SIZE;
                $size = ( $mimetype === self::DIRECTORY_MIMETYPE ) ?
                    ezcWebdavGetContentLengthProperty::COLLECTION :
                    (string) $size;
                $timestamp = isset( $item['mtime'] ) ? $item['mtime'] : time();
                $property->etag = md5( $path . $size . date( 'c', $timestamp ) );
                break;

            case 'resourcetype':
                $property = new ezcWebdavResourceTypeProperty();
                $mimetype = isset( $item['mimetype'] ) ? $item['mimetype'] : self::DEFAULT_MIMETYPE;
                $property->type = ( $mimetype === self::DIRECTORY_MIMETYPE ) ?
                    ezcWebdavResourceTypeProperty::TYPE_COLLECTION : 
                    ezcWebdavResourceTypeProperty::TYPE_RESOURCE;
                break;

            case 'supportedlock':
                $property = new ezcWebdavSupportedLockProperty();
                break;

            case 'lockdiscovery':
                $property = new ezcWebdavLockDiscoveryProperty();
                break;

            default:
                // Handle all other live properties like dead properties
                $properties = $storage->getAllProperties();
                $property = $properties['DAV:'][$name]; // @as (need to figure $namespace)
                break;
        }

        return $property;
    }

    /**
     * Returns all properties for a resource.
     * 
     * Returns all properties for the resource identified by $path as a {@link
     * ezcWebdavBasicPropertyStorage}.
     *
     * @param string $path 
     * @return ezcWebdavPropertyStorage
     */
    public function getAllProperties( $path )
    {
        $storage = $this->getPropertyStorage( $path );

        // Add all live properties to stored properties
        foreach ( $this->handledLiveProperties as $property )
        {
            $storage->attach(
                $this->getProperty( $path, $property )
            );
        }

        return $storage;
    }

    /**
     * Returns the property storage for a resource.
     *
     * Returns the {@link ezcWebdavPropertyStorage} instance containing the
     * properties for the resource identified by $path.
     * 
     * @param string $path 
     * @return ezcWebdavBasicPropertyStorage
     */
    protected function getPropertyStorage( $path )
    {
        $storage = new ezcWebdavBasicPropertyStorage();

        // @todo implement property storage
        return $storage;
    }

    /**
     * Returns if a resource exists.
     *
     * Returns if a the resource identified by $path exists.
     * 
     * @param string $path 
     * @return bool
     */
    protected function nodeExists( $path )
    {
        if ( !isset( $this->cachedNodes[$path] ) )
        {
            $this->cachedNodes[$path] = $this->getNodeInfo( $path );
        }

        return $this->cachedNodes[$path]['nodeExists'];
    }

    /**
     * Returns if resource is a collection.
     *
     * Returns if the resource identified by $path is a collection resource
     * (true) or a non-collection one (false).
     * 
     * @param string $path 
     * @return bool
     */
    protected function isCollection( $path )
    {
        if ( !isset( $this->cachedNodes[$path] ) )
        {
            $this->cachedNodes[$path] = $this->getNodeInfo( $path );
        }

        return $this->cachedNodes[$path]['isCollection'];
    }

    /**
     * Serves GET requests.
     *
     * The method receives a {@link ezcWebdavGetRequest} object containing all
     * relevant information obout the clients request and will return an {@link
     * ezcWebdavErrorResponse} instance on error or an instance of {@link
     * ezcWebdavGetResourceResponse} or {@link ezcWebdavGetCollectionResponse}
     * on success, depending on the type of resource that is referenced by the
     * request.
     *
     * This method acquires the internal lock of the backend, dispatches to
     * {@link ezcWebdavSimpleBackend} to perform the operation and releases the
     * lock afterwards.
     *
     * @param ezcWebdavGetRequest $request
     * @return ezcWebdavResponse
     */
    public function get( ezcWebdavGetRequest $request )
    {
        $this->acquireLock( true );
        $return = parent::get( $request );
        $this->freeLock();

        return $return;
    }

    /**
     * Serves HEAD requests.
     *
     * The method receives a {@link ezcWebdavHeadRequest} object containing all
     * relevant information obout the clients request and will return an {@link
     * ezcWebdavErrorResponse} instance on error or an instance of {@link
     * ezcWebdavHeadResponse} on success.
     *
     * This method acquires the internal lock of the backend, dispatches to
     * {@link ezcWebdavSimpleBackend} to perform the operation and releases the
     * lock afterwards.
     * 
     * @param ezcWebdavHeadRequest $request
     * @return ezcWebdavResponse
     */
    public function head( ezcWebdavHeadRequest $request )
    {
        $this->acquireLock( true );
        $return = parent::head( $request );
        $this->freeLock();

        return $return;
    }

    /**
     * Serves PROPFIND requests.
     * 
     * The method receives a {@link ezcWebdavPropFindRequest} object containing
     * all relevant information obout the clients request and will either
     * return an instance of {@link ezcWebdavErrorResponse} to indicate an error
     * or a {@link ezcWebdavPropFindResponse} on success. If the referenced
     * resource is a collection or if some properties produced errors, an
     * instance of {@link ezcWebdavMultistatusResponse} may be returned.
     *
     * The {@link ezcWebdavPropFindRequest} object contains a definition to
     * find one or more properties of a given collection or non-collection
     * resource.
     *
     * This method acquires the internal lock of the backend, dispatches to
     * {@link ezcWebdavSimpleBackend} to perform the operation and releases the
     * lock afterwards.
     *
     * This method is an overwrite of the propFind method from
     * ezcWebdavSimpleBackend, a hack necessary to permit correct
     * output of eZ Publish nodes. The array of ezcWebdavPropFindResponse
     * objects returned by ezcWebdavSimpleBackend::propFind is iterated and
     * the paths of the nodes in the ezcWebdavPropFindResponse objects
     * are encoded properly, in order to be displayed correctly in WebDAV
     * clients. The encoding is from the ini setting Charset in
     * [CharacterSettings] in i18n.ini.
     *
     * The code for coding is taken from eZWebDAVServer::outputCollectionContent().
     *
     * @param ezcWebdavPropFindRequest $request
     * @return ezcWebdavResponse
     */
    public function propFind( ezcWebdavPropFindRequest $request )
    {
        $ini = eZINI::instance( 'i18n.ini' );
        $dataCharset = $ini->variable( 'CharacterSettings', 'Charset' );
        $xmlCharset = 'utf-8';

        $this->acquireLock( true );
        $return = parent::propFind( $request );
        if ( isset( $return->responses ) && is_array( $return->responses ) )
        {
            foreach ( $return->responses as $response )
            {
                $href = $response->node->path;
                $pathArray = explode( '/', self::recode( $href, $dataCharset, $xmlCharset ) );
                $encodedPath = '/';

                foreach ( $pathArray as $pathElement )
                {
                    if ( $pathElement != '' )
                    {
                        $encodedPath .= rawurlencode( $pathElement );
                        $encodedPath .= '/';
                    }
                }
                $encodedPath = rtrim( $encodedPath, '/' );
                $response->node->path = $encodedPath;
            }
        }
        $this->freeLock();

        return $return;
    }

    /**
     * Serves PROPPATCH requests.
     *
     * The method receives a {@link ezcWebdavPropPatchRequest} object
     * containing all relevant information obout the clients request and will
     * return an instance of {@link ezcWebdavErrorResponse} on error or a
     * {@link ezcWebdavPropPatchResponse} response on success. If the
     * referenced resource is a collection or if only some properties produced
     * errors, an instance of {@link ezcWebdavMultistatusResponse} may be
     * returned.
     *
     * This method acquires the internal lock of the backend, dispatches to
     * {@link ezcWebdavSimpleBackend} to perform the operation and releases the
     * lock afterwards.
     *
     * @param ezcWebdavPropPatchRequest $request
     * @return ezcWebdavResponse
     */
    public function propPatch( ezcWebdavPropPatchRequest $request )
    {
        $this->acquireLock();
        $return = parent::propPatch( $request );
        $this->freeLock();

        return $return;
    }

    /**
     * Stores properties for a resource.
     *
     * Creates a new property storage file and stores the properties given for
     * the resource identified by $path.  This depends on the affected resource
     * and the actual properties in the property storage.
     *
     * @param string $path
     * @param ezcWebdavBasicPropertyStorage $storage
     */
    protected function storeProperties( $path, ezcWebdavBasicPropertyStorage $storage )
    {
        // @as @todo implement storing properties
        return true;
    }

    /**
     * Manually sets a property on a resource.
     *
     * Sets the given $propertyBackup for the resource identified by $path.
     *
     * @param string $path
     * @param ezcWebdavProperty $property
     * @return bool
     */
    public function setProperty( $path, ezcWebdavProperty $property )
    {
        if ( !in_array( $property->name, $this->handledLiveProperties, true ) )
        {
            return false;
        }

        // @as @todo implement setting properties
        // @todo implement locking and unlocking based on the code
        // lock:
        // replace 30607 with your object ID
        // $object = eZContentObject::fetch( 30607 );
        // $stateGroup = eZContentObjectStateGroup::fetchByIdentifier( 'ez_lock' );
        // $state = eZContentObjectState::fetchByIdentifier( 'locked', $stateGroup->attribute( 'id' ) );
        // $object->assignState( $state );

        // unlock:
        // $state = eZContentObjectState::fetchByIdentifier( 'not_locked', $stateGroup->attribute( 'id' ) );
        // $object->assignState( $state );

        // Get namespace property storage
        $storage = new ezcWebdavBasicPropertyStorage();

        // Attach property to store
        $storage->attach( $property );

        // Store document back
        $this->storeProperties( $path, $storage );

        return true;
    }

    /**
     * Manually removes a property from a resource.
     *
     * Removes the given $property form the resource identified by $path.
     *
     * @param string $path
     * @param ezcWebdavProperty $property
     * @return bool
     */
    public function removeProperty( $path, ezcWebdavProperty $property )
    {
        // @as @todo implement removing properties
        return true;
    }

    /**
     * Resets the property storage for a resource.
     *
     * Discards the current {@link ezcWebdavPropertyStorage} of the resource
     * identified by $path and replaces it with the given $properties.
     *
     * @param string $path
     * @param ezcWebdavPropertyStorage $storage
     * @return bool
     */
    public function resetProperties( $path, ezcWebdavPropertyStorage $storage )
    {
        $this->storeProperties( $path, $storage );
    }

    /**
     * Serves PUT requests.
     *
     * The method receives a {@link ezcWebdavPutRequest} objects containing all
     * relevant information obout the clients request and will return an
     * instance of {@link ezcWebdavErrorResponse} on error or {@link
     * ezcWebdavPutResponse} on success.
     *
     * This method acquires the internal lock of the backend, dispatches to
     * {@link ezcWebdavSimpleBackend} to perform the operation and releases the
     * lock afterwards.
     *
     * @param ezcWebdavPutRequest $request
     * @return ezcWebdavResponse
     */
    public function put( ezcWebdavPutRequest $request )
    {
        $this->acquireLock();
        $return = parent::put( $request );
        $this->freeLock();

        return $return;
    }

    /**
     * Creates a new resource.
     *
     * Creates a new resource at the given $path, optionally with the given
     * content. If $content is empty, an empty resource will be created.
     *
     * @param string $path
     * @param string $content
     */
    protected function createResource( $path, $content = null )
    {
        // the creation of the resource is done in setResourceContents()
    }

    /**
     * Sets the contents of a resource.
     *
     * This method replaces the content of the resource identified by $path
     * with the submitted $content.
     *
     * @param string $path
     * @param string $content
     */
    protected function setResourceContents( $path, $content )
    {
        // Attempt to get file/resource sent from client/browser.
        $tempFile = $this->storeUploadedFile( $path, $content );
        eZWebDAVContentBackend::appendLogEntry( 'SetResourceContents:' . $path . ';' . $tempFile );

        // If there was an actual file:
        if ( !$tempFile )
        {
            return false; // @as self::FAILED_FORBIDDEN;
        }

        // Attempt to do something with it (copy/whatever).
        $fullPath = $path;
        $target = $this->splitFirstPathElement( $path, $currentSite );

        if ( !$currentSite )
        {
            return false; // @as self::FAILED_FORBIDDEN;
        }

        $result = $this->putVirtualFolderData( $currentSite, $target, $tempFile, $fullPath );

        unlink( $tempFile );
        eZDir::cleanupEmptyDirectories( dirname( $tempFile ) );

        return $result;
    }

    /**
     * Serves DELETE requests.
     *
     * The method receives a {@link ezcWebdavDeleteRequest} objects containing
     * all relevant information obout the clients request and will return an
     * instance of {@link ezcWebdavErrorResponse} on error or {@link
     * ezcWebdavDeleteResponse} on success.
     *
     * This method acquires the internal lock of the backend, dispatches to
     * {@link ezcWebdavSimpleBackend} to perform the operation and releases the
     * lock afterwards.
     *
     * @param ezcWebdavDeleteRequest $request
     * @return ezcWebdavResponse
     */
    public function delete( ezcWebdavDeleteRequest $request )
    {
        $this->acquireLock();
        $return = parent::delete( $request );
        $this->freeLock();

        return $return;
    }

    /**
     * Returns if everything below a path can be deleted recursively.
     *
     * Checks files and directories recursively and returns if everything can
     * be deleted.  Returns an empty array if no errors occured, and an array
     * with the files which caused errors otherwise.
     *
     * @param string $source
     * @return array
     */
    public function checkDeleteRecursive( $target )
    {
        $errors = array();

        $fullPath = $target;
        $target = $this->splitFirstPathElement( $target, $currentSite );

        if ( !$currentSite )
        {
            // Cannot delete root folder
            return array(
                new ezcWebdavErrorResponse(
                    ezcWebdavResponse::STATUS_403,
                    $fullPath
                ),
            );
        }

        if ( $target === "" )
        {
            // Cannot delete entries in site list
            return array(
                new ezcWebdavErrorResponse(
                    ezcWebdavResponse::STATUS_403,
                    $fullPath
                ),
            );
        }

        return $errors;
    }

    /**
     * Deletes everything below a path.
     *
     * Deletes the resource identified by $path recursively. Returns an
     * instance of {@link ezcWebdavErrorResponse} if the deletion failed, and
     * null on success.
     *
     * In case performDelete() was called from a MOVE operation, it does not
     * delete anything, because the move() function in ezcWebdavSimpleBackend
     * first calls performDelete(), which deletes the destination if the source
     * and destination are the same in URL alias terms.
     *
     * @param string $path
     * @return ezcWebdavErrorResponse
     */
    protected function performDelete( $target )
    {
        switch ( $_SERVER['REQUEST_METHOD'] )
        {
            case 'MOVE':
                // in case performDelete() was called from a MOVE operation,
                // do not delete anything, because the move() function in
                // ezcWebdavSimpleBackend first calls performDelete(), which
                // deletes the destination if the source and destination
                // are the same in URL alias terms
                return null;

            default:
                $errors = $this->checkDeleteRecursive( $target );
                break;
        }

        // If an error will occur return the proper status. We return
        // multistatus in any case.
        if ( count( $errors ) > 0 )
        {
            return new ezcWebdavMultistatusResponse(
                $errors
            );
        }

        $fullPath = $target;
        $target = $this->splitFirstPathElement( $target, $currentSite );

        $status = $this->deleteVirtualFolder( $currentSite, $target, $fullPath );
        // @as @todo return based on $status

        // Return success
        return null;
    }

    /**
     * Serves COPY requests.
     *
     * The method receives a {@link ezcWebdavCopyRequest} objects containing
     * all relevant information obout the clients request and will return an
     * instance of {@link ezcWebdavErrorResponse} on error or {@link
     * ezcWebdavCopyResponse} on success. If only some operations failed, this
     * method may return an instance of {@link ezcWebdavMultistatusResponse}.
     *
     * This method acquires the internal lock of the backend, dispatches to
     * {@link ezcWebdavSimpleBackend} to perform the operation and releases the
     * lock afterwards.
     *
     * @param ezcWebdavCopyRequest $request
     * @return ezcWebdavResponse
     */
    public function copy( ezcWebdavCopyRequest $request )
    {
        $this->acquireLock();
        $return = parent::copy( $request );
        $this->freeLock();

        return $return;
    }

    /**
     * Copies resources recursively from one path to another.
     *
     * Copies the resourced identified by $fromPath recursively to $toPath with
     * the given $depth, where $depth is one of {@link
     * ezcWebdavRequest::DEPTH_ZERO}, {@link ezcWebdavRequest::DEPTH_ONE},
     * {@link ezcWebdavRequest::DEPTH_INFINITY}.
     *
     * Returns an array with {@link ezcWebdavErrorResponse}s for all subtrees,
     * where the copy operation failed. Errors for subsequent resources in a
     * subtree should be ommitted.
     *
     * If an empty array is return, the operation has been completed
     * successfully.
     *
     * In case performCopy() was called from a MOVE operation, do a real move
     * operation, because the move() function from ezcWebdavSimpleBackend
     * calls performCopy() and performDelete().
     *
     * @param string $fromPath
     * @param string $toPath
     * @param int $depth
     * @return array(ezcWebdavErrorResponse)
     */
    protected function performCopy( $source, $destination, $depth = ezcWebdavRequest::DEPTH_INFINITY )
    {
        switch ( $_SERVER['REQUEST_METHOD'] )
        {
            case 'MOVE':
                // in case performCopy() was called from a MOVE operation,
                // do a real move operation, because the move() function
                // from ezcWebdavSimpleBackend calls performCopy() and
                // performDelete()
                $errors = $this->moveRecursive( $source, $destination, $depth );
                break;

            case 'COPY':
                $errors = $this->copyRecursive( $source, $destination, $depth );
                break;

            default:
                $errors = $this->moveRecursive( $source, $destination, $depth );
                break;
        }

        // Transform errors
        foreach ( $errors as $nr => $error )
        {
            $errors[$nr] = new ezcWebdavErrorResponse(
                ezcWebdavResponse::STATUS_423,
                $error
            );
        }

        // Copy dead properties
        $storage = $this->getPropertyStorage( $source );
        $this->storeProperties( $destination, $storage );

        // Updateable live properties are updated automagically, because they
        // are regenerated on request on base of the file they affect. So there
        // is no reason to keep them "alive".

        return $errors;
    }

    /**
     * Recursively copy a file or directory.
     *
     * Recursively copy a file or directory in $source to the given
     * $destination. If a $depth is given, the operation will stop as soon as
     * the given recursion depth is reached. A depth of -1 means no limit,
     * while a depth of 0 means, that only the current file or directory will
     * be copied, without any recursion.
     *
     * Returns an empty array if no errors occured, and an array with the files
     * which caused errors otherwise.
     *
     * @param string $source
     * @param string $destination
     * @param int $depth
     * @return array
     */
    public function copyRecursive( $source, $destination, $depth = ezcWebdavRequest::DEPTH_INFINITY )
    {
        $errors = array();
        $fullSource = $source;
        $fullDestination = $destination;
        $source = $this->splitFirstPathElement( $source, $sourceSite );
        $destination = $this->splitFirstPathElement( $destination, $destinationSite );

        if ( $sourceSite !== $destinationSite )
        {
            // We do not support copying from one site to another yet
            // TODO: Check if the sites are using the same db,
            //       if so allow the copy as a simple object copy
            //       If not we will have to do an object export from
            //       $sourceSite and import it in $destinationSite
            return array(); // @as self::FAILED_FORBIDDEN;
        }

        if ( !$sourceSite or
             !$destinationSite )
        {
            // Cannot copy entries in site list
            return array( $fullSource ); // @as self::FAILED_FORBIDDEN;
        }

        $this->copyVirtualFolder( $sourceSite, $destinationSite,
                                  $source, $destination );

        if ( $depth === ezcWebdavRequest::DEPTH_ZERO || !$this->isCollection( $fullSource ) )
        {
            // Do not recurse (any more)
            return array();
        }

        // Recurse
        $nodes = $this->getCollectionMembers( $fullSource );
        foreach ( $nodes as $node )
        {
            if ( $node->path === $fullSource . '/' )
            {
                // skip the current node which was copied with copyVirtualFolder() above
                continue;
            }

            $newDepth = ( $depth !== ezcWebdavRequest::DEPTH_ONE ) ? $depth : ezcWebdavRequest::DEPTH_ZERO;
            $errors = array_merge(
                $errors,
                $this->copyRecursive( $node->path,
                                      $fullDestination . '/' . $this->getProperty( $node->path, 'displayname' )->displayName,
                                      $newDepth ) );
        }

        return $errors;
    }

    /**
     * Recursively move a file or directory.
     *
     * Recursively move a file or directory in $source to the given
     * $destination. If a $depth is given, the operation will stop as soon as
     * the given recursion depth is reached. A depth of -1 means no limit,
     * while a depth of 0 means, that only the current file or directory will
     * be copied, without any recursion.
     *
     * Returns an empty array if no errors occured, and an array with the files
     * which caused errors otherwise.
     *
     * @param string $source
     * @param string $destination
     * @param int $depth
     * @return array
     */
    public function moveRecursive( $source, $destination, $depth = ezcWebdavRequest::DEPTH_INFINITY )
    {
        $errors = array();
        $fullSource = $source;
        $fullDestination = $destination;
        $source = $this->splitFirstPathElement( $source, $sourceSite );
        $destination = $this->splitFirstPathElement( $destination, $destinationSite );

        if ( $sourceSite !== $destinationSite )
        {
            // We do not support copying from one site to another yet
            // TODO: Check if the sites are using the same db,
            //       if so allow the copy as a simple object copy
            //       If not we will have to do an object export from
            //       $sourceSite and import it in $destinationSite
            return array(); // @as self::FAILED_FORBIDDEN;
        }

        if ( !$sourceSite or
             !$destinationSite )
        {
            // Cannot copy entries in site list
            return array( $fullSource ); // @as self::FAILED_FORBIDDEN;
        }

        $this->moveVirtualFolder( $sourceSite, $destinationSite,
                                  $source, $destination,
                                  $fullSource, $fullDestination );

        if ( $depth === ezcWebdavRequest::DEPTH_ZERO || !$this->isCollection( $fullSource ) )
        {
            // Do not recurse (any more)
            return array();
        }

        // Recurse
        $nodes = $this->getCollectionMembers( $fullSource );
        foreach ( $nodes as $node )
        {
            if ( $node->path === $fullSource . '/' )
            {
                // skip the current node which was copied with copyVirtualFolder() above
                continue;
            }

            $newDepth = ( $depth !== ezcWebdavRequest::DEPTH_ONE ) ? $depth : ezcWebdavRequest::DEPTH_ZERO;
            $errors = array_merge(
                $errors,
                $this->moveRecursive( $node->path,
                                      $fullDestination . '/' . $this->getProperty( $node->path, 'displayname' )->displayName,
                                      $newDepth ) );
        }

        return $errors;
    }

    /**
     * Serves MOVE requests.
     *
     * The method receives a {@link ezcWebdavMoveRequest} objects containing
     * all relevant information obout the clients request and will return an
     * instance of {@link ezcWebdavErrorResponse} on error or {@link
     * ezcWebdavMoveResponse} on success. If only some operations failed, this
     * method may return an instance of {@link ezcWebdavMultistatusResponse}.
     *
     * This method acquires the internal lock of the backend, dispatches to
     * {@link ezcWebdavSimpleBackend} to perform the operation and releases the
     * lock afterwards.
     *
     * @param ezcWebdavMoveRequest $request
     * @return ezcWebdavResponse
     */
    public function move( ezcWebdavMoveRequest $request )
    {
        $this->acquireLock();
        $return = parent::move( $request );
        $this->freeLock();

        return $return;
    }

    /**
     * Serves MKCOL (make collection) requests.
     *
     * The method receives a {@link ezcWebdavMakeCollectionRequest} objects
     * containing all relevant information obout the clients request and will
     * return an instance of {@link ezcWebdavErrorResponse} on error or {@link
     * ezcWebdavMakeCollectionResponse} on success.
     *
     * This method acquires the internal lock of the backend, dispatches to
     * {@link ezcWebdavSimpleBackend} to perform the operation and releases the
     * lock afterwards.
     *
     * @param ezcWebdavMakeCollectionRequest $request
     * @return ezcWebdavResponse
     */
    public function makeCollection( ezcWebdavMakeCollectionRequest $request )
    {
        $this->acquireLock();
        $return = parent::makeCollection( $request );
        $this->freeLock();

        return $return;
    }


    /**
     * Required method to serve OPTIONS requests.
     * 
     * The method receives a {@link ezcWebdavOptionsRequest} object containing all
     * relevant information obout the clients request and should either return
     * an error by returning an {@link ezcWebdavErrorResponse} object, or any
     * other {@link ezcWebdavResponse} objects.
     *
     * @param ezcWebdavOptionsRequest $request
     * @return ezcWebdavResponse
     */
    public function options( ezcWebdavOptionsRequest $request )
    {
        $response = new ezcWebdavOptionsResponse( '1' );

        // Always allowed
        $allowed = 'GET, HEAD, PROPFIND, PROPPATCH, OPTIONS, ';

        // Check if modifications are allowed
        if ( $this instanceof ezcWebdavBackendChange )
        {
            $allowed .= 'DELETE, COPY, MOVE, ';
        }

        // Check if MKCOL is allowed
        if ( $this instanceof ezcWebdavBackendMakeCollection )
        {
            $allowed .= 'MKCOL, ';
        }

        // Check if PUT is allowed
        if ( $this instanceof ezcWebdavBackendPut )
        {
            $allowed .= 'PUT, ';
        }

        // Check if LOCK and UNLOCK are allowed
        if ( $this instanceof ezcWebdavLockBackend )
        {
            $allowed .= 'LOCK, UNLOCK, ';
        }

        $response->setHeader( 'Allow', substr( $allowed, 0, -2 ) );

        return $response;
    }


    // eZ Publish functionality -----------------------------------------


    /**
     * Sets the current site.
     *
     * From eZ Publish.
     *
     * @param string $site Eg. 'plain_site_user'
     * @todo remove or move in another class?
     */
    public function setCurrentSite( $site )
    {
        eZWebDAVContentBackend::appendLogEntry( __FUNCTION__ . '1:' . $site );
        $access = array( 'name' => $site,
                         'type' => EZ_ACCESS_TYPE_STATIC );

        $access = changeAccess( $access );
        eZWebDAVContentBackend::appendLogEntry( __FUNCTION__ . '2:' . $site );

        eZDebugSetting::writeDebug( 'kernel-siteaccess', $access, 'current siteaccess' );

        // Clear/flush global database instance.
        $nullVar = null;
        eZDB::setInstance( $nullVar );
    }

    /**
     * Detects a possible/valid site-name in start of a path.
     *
     * From eZ Publish.
     *
     * @param string $path Eg. '/plain_site_user/Content/Folder1/file1.txt'
     * @return string The name of the site that was detected (eg. 'plain_site_user')
     *                or false if not site could be detected
     * @todo remove or move in another class?
     */
    public function currentSiteFromPath( $path )
    {
        $indexDir = eZSys::indexDir();

        // Remove indexDir if used in non-virtualhost mode.
        if ( preg_match( "#^" . preg_quote( $indexDir ) . "(.+)$#", $path, $matches ) )
        {
            $path = $matches[1];
        }

        foreach ( $this->availableSites as $site )
        {
            // Check if given path starts with this site-name, if so: return it.
            if ( preg_match( "#^/" . preg_quote( $site ) . "(.*)$#", $path, $matches ) )
            {
                return $site;
            }
        }

        return false;
    }

    /**
     * Gathers information about a given node specified as parameter.
     *
     * The format of the returned array is:
     * <code>
     * array( 'name' => node name (eg. 'Group picture'),
     *        'size' => storage size of the_node in bytes (eg. 57123),
     *        'mimetype' => mime type of the node (eg. 'image/jpeg'),
     *        'ctime' => creation time as timestamp,
     *        'mtime' => latest modification time as timestamp,
     *        'href' => the path to the node (eg. '/plain_site_user/Content/Folder1/file1.jpg')
     * </code>
     *
     * @param string $target Eg. '/plain_site_user/Content/Folder1/file1.jpg
     * @param eZContentObject &$node The node corresponding to $target
     * @return array(string=>mixed)
     * @todo remove/replace .ini calls, eZContentUpload, eZMimeType, eZSys RequestURI
     * @todo handle articles as files
     */
    protected function fetchNodeInfo( $target, &$node )
    {
        // When finished, we'll return an array of attributes/properties.
        $entry = array();

        $classIdentifier = $node->attribute( 'class_identifier' );

        $object = $node->attribute( 'object' );

        // By default, everything is displayed as a folder:
        // Trim the name of the node, it is in some cases whitespace in eZ Publish
        $name = trim( $node->attribute( 'name' ) );

        // @as 2009-03-09: return node_id as displayname in case name is missing
        // displayname is not actually used by WebDAV clients
        $entry["name"] = ( $name !== '' && $name !== NULL ) ? $name : $node->attribute( 'node_id' );
        $entry["size"] = 0;
        $entry["mimetype"] = self::DIRECTORY_MIMETYPE;
        eZWebDAVContentBackend::appendLogEntry( 'FetchNodeInfo:' . $node->attribute( 'name' ) . '/' . $node->urlAlias() );

        // @todo handle articles as files
        // if ( $classIdentifier === 'article' )
        // {
        //     $entry["mimetype"] = 'application/ms-word';
        // }

        $entry["ctime"] = $object->attribute( 'published' );
        $entry["mtime"] = $object->attribute( 'modified' );

        $upload = new eZContentUpload();
        $info = $upload->objectFileInfo( $object );

        $suffix = '';
        $class = $object->contentClass();
        $isObjectFolder = $this->isObjectFolder( $object, $class );

        if ( $isObjectFolder )
        {
            // We do nothing, the default is to see it as a folder
        }
        else if ( $info )
        {
            $filePath = $info['filepath'];
            $entry['filepath'] = $filePath;
            $entry["mimetype"] = false;
            $entry["size"] = false;
            if ( isset( $info['filesize'] ) )
            {
                $entry['size'] = $info['filesize'];
            }
            if ( isset( $info['mime_type'] ) )
            {
                $entry['mimetype'] = $info['mime_type'];
            }

            // Fill in information from the actual file if they are missing.
            $file = eZClusterFileHandler::instance( $filePath );
            if ( !$entry['size'] and $file->exists() )
            {
                $entry["size"] = $file->size();
            }
            if ( !$entry['mimetype']  )
            {
                $mimeInfo = eZMimeType::findByURL( $filePath );
                $entry["mimetype"] = $mimeInfo['name'];

                $suffix = $mimeInfo['suffix'];

                if ( strlen( $suffix ) > 0 )
                {
                    $entry["name"] .= '.' . $suffix;
                }
            }
            else
            {
                // eZMimeType returns first suffix in its list
                // this could be another one than the original file extension
                // so let's try to get the suffix from the file path first
                $suffix = eZFile::suffix( $filePath );
                if ( !$suffix )
                {
                    $mimeInfo = eZMimeType::findByName( $entry['mimetype'] );
                    $suffix = $mimeInfo['suffix'];
                }

                if ( strlen( $suffix ) > 0 )
                {
                    $entry["name"] .= '.' . $suffix;
                }
            }

            if ( $file->exists() )
            {
                $entry["ctime"] = $file->mtime();
                $entry["mtime"] = $file->mtime();
            }
        }
        else
        {
            // Here we only show items as folders if they have
            // is_container set to true, otherwise it's an unknown binary file
            if ( !$class->attribute( 'is_container' ) )
            {
                $entry['mimetype'] = 'application/octet-stream';
            }
        }

        $scriptURL = $target;
        if ( strlen( $scriptURL ) > 0 and $scriptURL[ strlen( $scriptURL ) - 1 ] !== "/" )
        {
            $scriptURL .= "/";
        }

        $trimmedScriptURL = trim( $scriptURL, '/' );
        $scriptURLParts = explode( '/', $trimmedScriptURL );

        $urlPartCount = count( $scriptURLParts );

        if ( $urlPartCount >= 2 )
        {
            // one of the virtual folders
            // or inside one of the virtual folders
            $siteAccess = $scriptURLParts[0];
            $virtualFolder = $scriptURLParts[1];

            // only when the virtual folder is Content we need to add its path to the start URL
            // the paths of other top level folders (like Media) are included in URL aliases of their descending nodes
            if ( $virtualFolder === self::virtualContentFolderName() )
            {
                $startURL = '/' . $siteAccess . '/' . $virtualFolder . '/';
            }
            else
            {
                $startURL = '/' . $siteAccess . '/';
            }
        }
        else
        {
            // site access level
            $startURL = $scriptURL;
        }

        // Set the href attribute (note that it doesn't just equal the name).
        if ( !isset( $entry['href'] ) )
        {
            if ( strlen( $suffix ) > 0 )
            {
                $suffix = '.' . $suffix;
            }

            $entry["href"] = $startURL . $node->urlAlias() . $suffix;
        }

        // Return array of attributes/properties (name, size, mime, times, etc.).
        return $entry;
    }

    /**
     * Handles data retrival on the virtual folder level.
     *
     * The format of the returned array is the same as $result:
     * <code>
     * array( 'isFile' => bool,
     *        'isCollection' => bool,
     *        'file' => path to the storage file which contain the contents );
     * </code>
     *
     * @param array(string=>mixed) $result
     * @param string $currentSite Eg. 'plain_site_user'
     * @param string $target Eg. 'Content/Folder1/file1.txt'
     * @param string $fullPath Eg. '/plain_site_user/Content/Folder1/file1.txt'
     * @return array(string=>mixed) Or false if an error appeared
     */
    protected function getVirtualFolderData( $result, $currentSite, $target, $fullPath )
    {
        $target = $this->splitFirstPathElement( $target, $virtualFolder );
        if ( !$target )
        {
            // The rest in the virtual folder does not have any data
            return false; // self::FAILED_NOT_FOUND;
        }

        if ( !in_array( $virtualFolder, array( self::virtualContentFolderName(), self::virtualMediaFolderName() ) ) )
        {
            return false; // self::FAILED_NOT_FOUND;
        }

        if ( $virtualFolder === self::virtualContentFolderName() or
             $virtualFolder === self::virtualMediaFolderName() )
        {
            return $this->getContentNodeData( $result, $currentSite, $virtualFolder, $target, $fullPath );
        }
        return false; // self::FAILED_NOT_FOUND;
    }

    /**
     * Handles data retrival on the content tree level.
     *
     * The format of the returned array is the same as $result:
     * <code>
     * array( 'isFile' => bool,
     *        'isCollection' => bool,
     *        'file' => path to the storage file which contain the contents );
     * </code>
     *
     * @param array(string=>mixed) $result
     * @param string $currentSite Eg. 'plain_site_user'
     * @param string $virtualFolder Eg. 'Content'
     * @param string $target Eg. 'Folder1/file1.txt'
     * @param string $fullPath Eg. '/plain_site_user/Content/Folder1/file1.txt' 
     * @return array(string=>mixed) Or false if an error appeared
     * @todo remove/replace eZContentUpload
     */
    protected function getContentNodeData( $result, $currentSite, $virtualFolder, $target, $fullPath )
    {
        // Attempt to fetch the node the client wants to get.
        $nodePath = $this->internalNodePath( $virtualFolder, $target );
        eZWebDAVContentBackend::appendLogEntry( __FUNCTION__ . " Path:" . $nodePath );
        $node = $this->fetchNodeByTranslation( $nodePath );
        $info = $this->fetchNodeInfo( $fullPath, $node );

        // Proceed only if the node is valid:
        if ( $node === null )
        {
            return $result;
        }

        // Can we fetch the contents of the node
        if ( !$node->canRead() )
        {
            return false; // @as self::FAILED_FORBIDDEN;
        }

        $object = $node->attribute( 'object' );

        foreach ( $info as $key => $value )
        {
            $result[$key] = $value;
        }

        $upload = new eZContentUpload();
        $info = $upload->objectFileInfo( $object );

        if ( $info )
        {
            $result['file'] = $info['filepath'];
        }
        else
        {
            $class = $object->contentClass();
            if ( $this->isObjectFolder( $object, $class ) )
            {
                $result['isCollection'] = true;
            }

        }
        return $result;
    }

    /**
     * Produces the collection content.
     *
     * Builds either the virtual start folder with the virtual content folder
     * in it (and additional files). OR: if we're browsing within the content
     * folder: it gets the content of the target/given folder.
     *
     * @param string $collection Eg. '/plain_site_user/Content/Folder1'
     * @param int $depth One of -1 (infinite), 0, 1
     * @param array(string) $properties Currently not used
     * @return array(string=>array())
     */
    protected function getCollectionContent( $collection, $depth = false, $properties = false )
    {
        $fullPath = $collection;
        $collection = $this->splitFirstPathElement( $collection, $currentSite );

        if ( !$currentSite )
        {
            // Display the root which contains a list of sites
            $entries = $this->fetchSiteListContent( $fullPath, $depth, $properties );
            return $entries;
        }

        return $this->getVirtualFolderCollection( $currentSite, $collection, $fullPath, $depth, $properties );
    }

    /**
     * Same as fetchVirtualSiteContent(), but only one entry is returned
     * (Content or Media).
     *
     * An entry in the the returned array is of this form:
     * <code>
     * array( 'name' => node name (eg. 'Group picture'),
     *        'size' => storage size of the_node in bytes (eg. 57123),
     *        'mimetype' => mime type of the node (eg. 'image/jpeg'),
     *        'ctime' => creation time as timestamp,
     *        'mtime' => latest modification time as timestamp,
     *        'href' => the path to the node (eg. '/plain_site_user/Content/Folder1/file1.jpg')
     * </code>
     *
     * @param string $fullPath Eg. '/plain_site_user/Content/Folder1'
     * @param string $site Eg. 'plain_site_user'
     * @param string $nodeName Eg. 'Folder1'
     * @return array(array(string=>mixed))
     */
    protected function fetchContainerNodeInfo( $fullPath, $site, $nodeName )
    {
        $contentEntry             = array();
        $contentEntry["name"]     = $nodeName;
        $contentEntry["size"]     = 0;
        $contentEntry["mimetype"] = self::DIRECTORY_MIMETYPE;
        $contentEntry["ctime"]    = filectime( 'settings/siteaccess/' . $site );
        $contentEntry["mtime"]    = filemtime( 'settings/siteaccess/' . $site );
        $contentEntry["href"]     = $fullPath;

        $entries[] = $contentEntry;

        return $entries;
    }

    /**
     * Builds and returns the content of the virtual start folder for a site.
     *
     * The virtual startfolder is an intermediate step between the site-list
     * and actual content. This directory contains the "content" folder which
     * leads to the site's actual content.
     *
     * An entry in the the returned array is of this form:
     * <code>
     * array( 'name' => node name (eg. 'Group picture'),
     *        'size' => storage size of the_node in bytes (eg. 57123),
     *        'mimetype' => mime type of the node (eg. 'image/jpeg'),
     *        'ctime' => creation time as timestamp,
     *        'mtime' => latest modification time as timestamp,
     *        'href' => the path to the node (eg. '/plain_site_user/Content/Folder1/file1.jpg')
     * </code>
     *
     * @param string $target Eg. '/plain_site_user/Content/Folder1'
     * @param string $site Eg. 'plain_site_user
     * @param string $depth One of -1 (infinite), 0, 1
     * @param array(string) $properties Currently not used
     * @return array(array(string=>mixed))
     */
    protected function fetchVirtualSiteContent( $target, $site, $depth, $properties )
    {
        $requestUri = $target;

        // Always add the current collection
        $contentEntry = array();
        $scriptURL = $requestUri;
        if ( $scriptURL{strlen( $scriptURL ) - 1} !== '/' )
        {
            $scriptURL .= "/";
        }
        $contentEntry["name"]     = $scriptURL;
        $contentEntry["size"]     = 0;
        $contentEntry["mimetype"] = self::DIRECTORY_MIMETYPE;
        $contentEntry["ctime"]    = filectime( 'settings/siteaccess/' . $site );
        $contentEntry["mtime"]    = filemtime( 'settings/siteaccess/' . $site );
        $contentEntry["href"]     = $requestUri;
        $entries[] = $contentEntry;

        $defctime = $contentEntry['ctime'];
        $defmtime = $contentEntry['mtime'];

        if ( $depth > 0 )
        {
            $scriptURL = $requestUri;
            if ( $scriptURL{strlen( $scriptURL ) - 1} !== '/' )
            {
                $scriptURL .= "/";
            }

            // Set up attributes for the virtual content folder:
            foreach ( array( self::virtualContentFolderName(), self::virtualMediaFolderName() ) as $name )
            {
                $entry             = array();
                $entry["name"]     = $name;
                $entry["size"]     = 0;
                $entry["mimetype"] = self::DIRECTORY_MIMETYPE;
                $entry["ctime"]    = $defctime;
                $entry["mtime"]    = $defmtime;
                $entry["href"]     = $scriptURL . $name;

                $entries[]         = $entry;
            }
        }

        return $entries;
    }

    /**
     * Handles collections on the virtual folder level, if no virtual folder
     * elements are accessed it lists the virtual folders.
     *
     * An entry in the the returned array is of this form:
     * <code>
     * array( 'name' => node name (eg. 'Group picture'),
     *        'size' => storage size of the_node in bytes (eg. 57123),
     *        'mimetype' => mime type of the node (eg. 'image/jpeg'),
     *        'ctime' => creation time as timestamp,
     *        'mtime' => latest modification time as timestamp,
     *        'href' => the path to the node (eg. '/plain_site_user/Content/Folder1/file1.jpg')
     * </code>
     *
     * @param string $site Eg. 'plain_site_user
     * @param string $collection Eg. 'Folder1'
     * @param string $fullPath Eg. '/plain_site_user/Content/Folder1'
     * @param string $depth One of -1 (infinite), 0, 1
     * @param array(string) $properties Currently not used
     * @return array(array(string=>mixed))
     */
    protected function getVirtualFolderCollection( $currentSite, $collection, $fullPath, $depth, $properties )
    {
        if ( !$collection )
        {
            // We are inside a site so we display the virtual folder for the site
            $entries = $this->fetchVirtualSiteContent( $fullPath, $currentSite, $depth, $properties );
            return $entries;
        }

        $collection = $this->splitFirstPathElement( $collection, $virtualFolder );

        if ( !in_array( $virtualFolder, array( self::virtualContentFolderName(), self::virtualMediaFolderName() ) ) )
        {
            return false; // self::FAILED_NOT_FOUND;
        }

        // added by @ds 2008-12-07 to fix problems with IE6 SP2
        $ini = eZIni::instance();
        $prefixAdded = false;
        $prefix = $ini->hasVariable( 'SiteAccessSettings', 'PathPrefix' ) &&
                      $ini->variable( 'SiteAccessSettings', 'PathPrefix' ) != '' ? eZURLAliasML::cleanURL( $ini->variable( 'SiteAccessSettings', 'PathPrefix' ) ) : false;

        if ( $prefix )
        {
            $escapedPrefix = preg_quote( $prefix, '#' );
            // Only prepend the path prefix if it's not already the first element of the url.
            if ( !preg_match( "#^$escapedPrefix(/.*)?$#i", $collection )  )
            {
                $exclude = $ini->hasVariable( 'SiteAccessSettings', 'PathPrefixExclude' )
                           ? $ini->variable( 'SiteAccessSettings', 'PathPrefixExclude' )
                           : false;
                $breakInternalURI = false;
                foreach ( $exclude as $item )
                {
                    $escapedItem = preg_quote( $item, '#' );
                    if ( preg_match( "#^$escapedItem(/.*)?$#i", $collection )  )
                    {
                        $breakInternalURI = true;
                        break;
                    }
                }

                if ( !$breakInternalURI )
                {
                    $collection = $prefix . '/' . $collection;
                    $prefixAdded = true;
                }
            }
        }

        return $this->getContentTreeCollection( $currentSite, $virtualFolder, $collection, $fullPath, $depth, $properties );
    }

    /**
     * Handles collections on the content tree level.
     *
     * Depending on the virtual folder we will generate a node path url and fetch
     * the nodes for that path.
     *
     * An entry in the the returned array is of this form:
     * <code>
     * array( 'name' => node name (eg. 'Folder1'),
     *        'size' => storage size of the_node in bytes (eg. 4096 for collections),
     *        'mimetype' => mime type of the node (eg. 'httpd/unix-directory'),
     *        'ctime' => creation time as timestamp,
     *        'mtime' => latest modification time as timestamp,
     *        'href' => the path to the node (eg. '/plain_site_user/Content/Folder1/')
     * </code>
     *
     * @param string $site Eg. 'plain_site_user
     * @param string $virtualFolder Eg. 'Content'
     * @param string $collection Eg. 'Folder1'
     * @param string $fullPath Eg. '/plain_site_user/Content/Folder1/'
     * @param string $depth One of -1 (infinite), 0, 1
     * @param array(string) $properties Currently not used
     * @return array(array(string=>mixed))
     */
    protected function getContentTreeCollection( $currentSite, $virtualFolder, $collection, $fullPath, $depth, $properties )
    {
        $nodePath = $this->internalNodePath( $virtualFolder, $collection );
        $node = $this->fetchNodeByTranslation( $nodePath );

        if ( !$node )
        {
            return false; // self::FAILED_NOT_FOUND;
        }

        // Can we list the children of the node?
        if ( !$node->canRead() )
        {
            return false; // self::FAILED_FORBIDDEN;
        }

        $entries = $this->fetchContentList( $fullPath, $node, $nodePath, $depth, $properties );
        return $entries;
    }

    /**
     * Gets and returns the content of an actual node.
     *
     * List of other nodes belonging to the target node (one level below it)
     * will be returned as well.
     *
     * An entry in the the returned array is of this form:
     * <code>
     * array( 'name' => node name (eg. 'Content'),
     *        'size' => storage size of the_node in bytes (eg. 4096 for collections),
     *        'mimetype' => mime type of the node (eg. 'httpd/unix-directory'),
     *        'ctime' => creation time as timestamp,
     *        'mtime' => latest modification time as timestamp,
     *        'href' => the path to the node (eg. '/plain_site_user/Content/')
     * </code>
     *
     * @param string $fullPath Eg. '/plain_site_user/Content/'
     * @param eZContentObject &$node The note corresponding to $fullPath
     * @param string $target Eg. 'Content'
     * @param string $depth One of -1 (infinite), 0, 1
     * @param array(string) $properties Currently not used
     * @return array(array(string=>mixed))
     */
    protected function fetchContentList( $fullPath, &$node, $target, $depth, $properties )
    {
        // We'll return an array of entries (which is an array of attributes).
        $entries = array();

        if ( $depth === ezcWebdavRequest::DEPTH_ONE || $depth === ezcWebdavRequest::DEPTH_INFINITY )
            // @as added || $depth === ezcWebdavRequest::DEPTH_INFINITY
        {
            // Get all the children of the target node.
            $subTree = $node->subTree( array ( 'Depth' => 1 ) );

            // Build the entries array by going through all the
            // nodes in the subtree and getting their attributes:
            foreach ( $subTree as $someNode )
            {
                $entries[] = $this->fetchNodeInfo( $fullPath, $someNode );
            }
        }

        // Always include the information about the current level node
        $thisNodeInfo = $this->fetchNodeInfo( $fullPath, $node );

        $scriptURL = $fullPath;
        if ( $scriptURL{strlen( $scriptURL ) - 1} !== '/' )
        {
            $scriptURL .= "/";
        }

        $thisNodeInfo["href"] = $scriptURL;
        $entries[] = $thisNodeInfo;

        // Return the content of the target.
        return $entries;
    }

    /**
     * Builds a content-list of available sites and returns it.
     *
     * An entry in the the returned array is of this form:
     * <code>
     * array( 'name' => node name (eg. 'plain_site_user'),
     *        'size' => storage size of the_node in bytes (eg. 4096 for collections),
     *        'mimetype' => mime type of the node (eg. 'httpd/unix-directory'),
     *        'ctime' => creation time as timestamp,
     *        'mtime' => latest modification time as timestamp,
     *        'href' => the path to the node (eg. '/plain_site_user/')
     * </code>
     *
     * @param string $target Eg. '/'
     * @param string $depth One of -1 (infinite), 0, 1
     * @param array(string) $properties Currently not used
     * @return array(array(string=>mixed))
     */
    protected function fetchSiteListContent( $target, $depth, $properties )
    {
        // At the end: we'll return an array of entry-arrays.
        $entries = array();

        // An entry consists of several attributes (name, size, etc).
        $contentEntry = array();
        $entries = array();

        // add a slash at the end of the path, if it is missing
        $scriptURL = $target;
        if ( $scriptURL{strlen( $scriptURL ) - 1} !== '/' )
        {
            $scriptURL .= "/";
        }

        $thisNodeInfo["href"] = $scriptURL;

        // Set up attributes for the virtual site-list folder:
        $contentEntry["name"]     = '/';
        $contentEntry["href"]     = $scriptURL;
        $contentEntry["size"]     = 0;
        $contentEntry["mimetype"] = self::DIRECTORY_MIMETYPE;
        $contentEntry["ctime"]    = filectime( 'var' );
        $contentEntry["mtime"]    = filemtime( 'var' );

        $entries[] = $contentEntry;

        if ( $depth > 0 )
        {
            // For all available sites:
            foreach ( $this->availableSites as $site )
            {
                // Set up attributes for the virtual site-list folder:
                $contentEntry["name"]     = $scriptURL . $site . '/'; // @as added '/'
                $contentEntry["size"]     = 0;
                $contentEntry["mimetype"] = self::DIRECTORY_MIMETYPE;
                $contentEntry["ctime"]    = filectime( 'settings/siteaccess/' . $site );
                $contentEntry["mtime"]    = filemtime( 'settings/siteaccess/' . $site );

                if ( $target === '/' )
                {
                    $contentEntry["href"] = $contentEntry["name"];
                }
                else
                {
                    $contentEntry["href"] = $scriptURL . $contentEntry["name"];
                }

                $entries[] = $contentEntry;
            }
        }

        return $entries;
    }

    /**
     * Attempts to fetch a possible node by translating the provided
     * string/path to a node-number.
     *
     * @param string $nodePathString Eg. 'Folder1/file1.txt'
     * @return eZContentObject Eg. the node of 'Folder1/file1.txt'
     */
    protected function fetchNodeByTranslation( $nodePathString )
    {
        // Get rid of possible extensions, remove .jpeg .txt .html etc..
        $nodePathString = $this->fileBasename( $nodePathString );

        // Strip away last slash
        if ( strlen( $nodePathString ) > 0 and
             $nodePathString[strlen( $nodePathString ) - 1] === '/' )
        {
            $nodePathString = substr( $nodePathString, 0, strlen( $nodePathString ) - 1 );
        }

        if ( strlen( $nodePathString ) > 0 )
        {
            $nodePathString = eZURLAliasML::convertPathToAlias( $nodePathString );
        }

        $nodeID = eZURLAliasML::fetchNodeIDByPath( $nodePathString );
        if ( $nodeID == 2 )
        {
            // added by @ds 2008-12-07 to fix problems with IE6 SP2
            $ini = eZINI::instance( 'webdav.ini' );
            if ( $ini->hasVariable( 'GeneralSettings', 'StartNode' ) )
            {
                $nodeID = $ini->variable( 'GeneralSettings', 'StartNode' );
            }
        }
        elseif ( $nodeID )
        {
        }
        else
        {
            return false;
        }

        // Attempt to fetch the node.
        $node = eZContentObjectTreeNode::fetch( $nodeID );

        // Return the node.
        return $node;
    }

    /**
     * Attempts to fetch a possible node by translating the provided
     * string/path to a node-number.
     *
     * The last section of the path is removed before the actual
     * translation: hence, the PARENT node is returned.
     *
     * @param string $nodePathString Eg. 'Folder1/file1.txt'
     * @return eZContentObject Eg. the node of 'Folder1'
     */
    protected function fetchParentNodeByTranslation( $nodePathString )
    {
        // Strip extensions. E.g. .jpg
        $nodePathString = $this->fileBasename( $nodePathString );

        // Strip away last slash
        if ( strlen( $nodePathString ) > 0 and
             $nodePathString[strlen( $nodePathString ) - 1] === '/' )
        {
            $nodePathString = substr( $nodePathString, 0, strlen( $nodePathString ) - 1 );
        }

        $nodePathString = $this->splitLastPathElement( $nodePathString, $element );

        if ( strlen( $nodePathString ) === 0 )
        {
            $nodePathString = '/';
        }

        $nodePathString = eZURLAliasML::convertPathToAlias( $nodePathString );

        // Attempt to translate the URL to something like "/content/view/full/84".
        $translateResult = eZURLAliasML::translate( $nodePathString );

        // handle redirects
        while ( $nodePathString === 'error/301' )
        {
            $nodePathString = $translateResult;

            $translateResult = eZURLAliasML::translate( $nodePathString );
        }

        // Get the ID of the node (which is the last part of the translated path).
        if ( preg_match( "#^content/view/full/([0-9]+)$#", $nodePathString, $matches ) )
        {
            $nodeID = $matches[1];
        }
        else
        {
            $ini = eZINI::instance( 'webdav.ini' );
            if ( $ini->hasVariable( 'GeneralSettings', 'StartNode' ) )
            {
                $nodeID = $ini->variable( 'GeneralSettings', 'StartNode' );
            }
        }

        // Attempt to fetch the node.
        $node = eZContentObjectTreeNode::fetch( $nodeID );

        // Return the node.
        return $node;
    }

    /**
     * Creates a new collection (folder) at the given path $target.
     *
     * @param string $target Eg. '/plain_site_user/Content/Folder1'
     * @return bool
     */
    protected function createCollection( $target )
    {
        $target = $this->splitFirstPathElement( $target, $currentSite );

        if ( !$currentSite )
        {
            // Site list cannot get new entries
            return false; // @as self::FAILED_FORBIDDEN;
        }

        return $this->mkcolVirtualFolder( $currentSite, $target );
    }

    /**
     * Handles collection creation on the virtual folder level.
     *
     * It will check if the target is below a content folder in which it
     * calls mkcolContent().
     *
     * @param string $currentSite Eg. 'plain_site_user'
     * @param string $target Eg. 'Content/Folder1'
     * @return bool
     */
    protected function mkcolVirtualFolder( $currentSite, $target )
    {
        $target = $this->splitFirstPathElement( $target, $virtualFolder );

        if ( !in_array( $virtualFolder, array( self::virtualContentFolderName(), self::virtualMediaFolderName() ) ) )
        {
            return false; // @as self::FAILED_NOT_FOUND;
        }

        if ( !$target )
        {
            // We have reached the end of the path
            // We do not allow 'mkcol' operations for the virtual folder.
            return false; // @as self::FAILED_FORBIDDEN;
        }

        if ( $virtualFolder === self::virtualContentFolderName() or
             $virtualFolder === self::virtualMediaFolderName() )
        {
            return $this->mkcolContent( $currentSite, $virtualFolder, $target );
        }

        return false; // @as self::FAILED_FORBIDDEN;
    }

    /**
     * Handles collection creation on the content tree level.
     *
     * It will try to find the parent node of the wanted placement and
     * create a new collection (folder etc.) as a child.
     *
     * @param string $currentSite Eg. 'plain_site_user'
     * @param string $virtualFolder Eg. 'Content'
     * @param string $target Eg. 'Folder1'
     * @return bool
     */
    protected function mkcolContent( $currentSite, $virtualFolder, $target )
    {
        $nodePath = $this->internalNodePath( $virtualFolder, $target );
        $node = $this->fetchNodeByTranslation( $nodePath );
        if ( $node )
        {
            return false; // @as self::FAILED_EXISTS;
        }

        $parentNode = $this->fetchParentNodeByTranslation( $nodePath );

        if ( !$parentNode )
        {
            return false; // @as self::FAILED_NOT_FOUND;
        }

        // Can we create a collection in the parent node
        if ( !$parentNode->canRead() )
        {
            return false; // @as self::FAILED_FORBIDDEN;
        }

        return $this->createFolder( $parentNode, $nodePath );
    }

    /**
     * Creates a new folder under the given $target path.
     *
     * @param eZContentObject $parentNode
     * @param string $target Eg. 'Folder1'
     * @return bool
     */
    protected function createFolder( $parentNode, $target )
    {
        // Grab settings from the ini file:
        $webdavINI = eZINI::instance( self::WEBDAV_INI_FILE );
        $folderClassID = $webdavINI->variable( 'FolderSettings', 'FolderClass' );
        $languageCode = eZContentObject::defaultLanguage();

        $contentObject = eZContentObject::createWithNodeAssignment( $parentNode, $folderClassID, $languageCode );
        if ( $contentObject )
        {
            $db = eZDB::instance();
            $db->begin();
            $version = $contentObject->version( 1 );
            $version->setAttribute( 'status', eZContentObjectVersion::STATUS_DRAFT );
            $version->store();

            $contentObjectID = $contentObject->attribute( 'id' );
            $contentObjectAttributes = $version->contentObjectAttributes();

            // @todo @as avoid using [0] (could be another index in some classes)
            $contentObjectAttributes[0]->setAttribute( 'data_text', basename( $target ) );
            $contentObjectAttributes[0]->store();
            $db->commit();

            $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObjectID,
                                                                                         'version' => 1 ) );
            return true; // @as self::OK_CREATED;
        }
        else
        {
            return false; // @as self::FAILED_FORBIDDEN;
        }
    }

    /**
     * Handles deletion on the virtual folder level.
     *
     * It will check if the target is below a content folder in which it calls
     * deleteContent().
     *
     * @param string $currentSite Eg. 'plain_site_user'
     * @param string $target Eg. 'Content/Folder1/file1.txt'
     * @return bool
     */
    protected function deleteVirtualFolder( $currentSite, $target )
    {
        $target = $this->splitFirstPathElement( $target, $virtualFolder );

        if ( !in_array( $virtualFolder, array( self::virtualContentFolderName(), self::virtualMediaFolderName() ) ) )
        {
            return false; // @as self::FAILED_NOT_FOUND;
        }

        if ( !$target )
        {
            // We have reached the end of the path
            // We do not allow 'delete' operations for the virtual folder.
            return false; // @as self::FAILED_FORBIDDEN;
        }

        if ( $virtualFolder === self::virtualContentFolderName() or
             $virtualFolder === self::virtualMediaFolderName() )
        {
            return $this->deleteContent( $currentSite, $virtualFolder, $target );
        }

        return false; // @as self::FAILED_FORBIDDEN;
    }

    /**
     * Handles deletion on the content tree level.
     *
     * It will try to find the node with the path $target and then try to
     * remove it (ie. move to trash) if the user is allowed.
     *
     * @param string $currentSite Eg. 'plain_site_user'
     * @param string $virtualFolder Eg. 'Content'
     * @param string $target Eg. 'Folder1/file1.txt'
     * @return bool
     */
    protected function deleteContent( $currentSite, $virtualFolder, $target )
    {
        $nodePath = $this->internalNodePath( $virtualFolder, $target );
        $node = $this->fetchNodeByTranslation( $nodePath );

        if ( $node === null )
        {
            return false; // @as self::FAILED_NOT_FOUND;
        }

        // Can we delete the node?
        if ( !$node->canRead() or
             !$node->canRemove() )
        {
            return false; // @as self::FAILED_FORBIDDEN;
        }

        // added by @as: use the content.ini setting for handling delete: 'delete' or 'trash'
        // default is 'trash'
        $contentINI = eZINI::instance( 'content.ini' );
        $removeAction = $contentINI->hasVariable( 'RemoveSettings', 'DefaultRemoveAction' ) ?
                        $contentINI->variable( 'RemoveSettings', 'DefaultRemoveAction' ) : 'trash';

        if ( $removeAction !== 'trash' && $removeAction !== 'delete' )
        {
            // default remove action is to trash
            $removeAction = 'trash';
        }

        $moveToTrash = ( $removeAction === 'trash' ) ? true : false;

        $node->removeNodeFromTree( $moveToTrash );
        return true; // @as self::OK;
    }

    /**
     * Handles data storage on the content tree level.
     *
     * It will check if the target is below a content folder in which it calls
     * putContentData().
     *
     * @param string $currentSite Eg. 'plain_site_user'
     * @param string $target Eg. 'Content/Folder1/file1.txt'
     * @param string $tempFile The temporary file holding the contents
     * @return bool
     */
    protected function putVirtualFolderData( $currentSite, $target, $tempFile )
    {
        $target = $this->splitFirstPathElement( $target, $virtualFolder );

        if ( !$target )
        {
            // We have reached the end of the path
            // We do not allow 'put' operations for the virtual folder.
            return false; // @as self::FAILED_FORBIDDEN;
        }

        if ( !in_array( $virtualFolder, array( self::virtualContentFolderName(), self::virtualMediaFolderName() ) ) )
        {
            return false; // @as self::FAILED_CONFLICT;
        }

        if ( $virtualFolder === self::virtualContentFolderName() or
             $virtualFolder === self::virtualMediaFolderName() )
        {
            $result = $this->putContentData( $currentSite, $virtualFolder, $target, $tempFile );
            return $result;
        }

        return false; // @as self::FAILED_FORBIDDEN;
    }

    /**
     * Handles data storage on the content tree level.
     *
     * It will try to find the parent node of the wanted placement and
     * create a new object with data from $tempFile.
     *
     * @param string $currentSite Eg. 'plain_site_user'
     * @param string $virtualFolder Eg. 'Content'
     * @param string $target Eg. 'Folder1/file1.txt'
     * @param string $tempFile The temporary file holding the contents
     * @return bool
     * @todo remove/replace eZContentUpload
     */
    protected function putContentData( $currentSite, $virtualFolder, $target, $tempFile )
    {
        $nodePath = $this->internalNodePath( $virtualFolder, $target );

        $parentNode = $this->fetchParentNodeByTranslation( $nodePath );
        if ( $parentNode === null )
        {
            // The node does not exist, so we cannot put the file
            return false; // @as self::FAILED_CONFLICT;
        }

        // Can we put content in the parent node
        if ( !$parentNode->canRead() )
        {
            return false; // @as self::FAILED_FORBIDDEN;
        }

        $parentNodeID = $parentNode->attribute( 'node_id' );

        $existingNode = $this->fetchNodeByTranslation( $nodePath );

        $upload = new eZContentUpload();

        if ( !$upload->handleLocalFile( $result, $tempFile, $parentNodeID, $existingNode ) )
        {
            if ( $result['status'] === eZContentUpload::STATUS_PERMISSION_DENIED )
            {
                return false; // @as self::FAILED_FORBIDDEN;
            }
            else
            {
                return false; // @as self::FAILED_UNSUPPORTED;
            }
        }

        return true; // @as self::OK_CREATED;
    }

    /**
     * Handles copying on the virtual folder level.
     *
     * It will check if the target is below a content folder in which it
     * calls copyContent().
     *
     * @param string $sourceSite Eg. 'plain_site_user'
     * @param string $destinationSite Eg. 'plain_site_user'
     * @param string $source Eg. 'Content/Folder1/file1.txt'
     * @param string $destination Eg. 'Content/Folder2/file1.txt'
     * @return bool
     */
    protected function copyVirtualFolder( $sourceSite, $destinationSite,
                                          $source, $destination )
    {
        $source = $this->splitFirstPathElement( $source, $sourceVFolder );
        $destination = $this->splitFirstPathElement( $destination, $destinationVFolder );

        if ( !in_array( $sourceVFolder, array( self::virtualContentFolderName(), self::virtualMediaFolderName() ) ) )
        {
            return false; // @as self::FAILED_NOT_FOUND;
        }

        if ( !in_array( $destinationVFolder, array( self::virtualContentFolderName(), self::virtualMediaFolderName() ) ) )
        {
            return false; // @as self::FAILED_NOT_FOUND;
        }

        if ( !$source or
             !$destination )
        {
            // We have reached the end of the path for source or destination
            // We do not allow 'copy' operations for the virtual folder (from or to)
            return false; // @as self::FAILED_FORBIDDEN;
        }

        if ( ( $sourceVFolder === self::virtualContentFolderName() or
               $sourceVFolder === self::virtualMediaFolderName() ) and
             ( $destinationVFolder === self::virtualContentFolderName() or
               $destinationVFolder === self::virtualMediaFolderName() ) )
        {
            return $this->copyContent( $sourceSite, $destinationSite,
                                       $sourceVFolder, $destinationVFolder,
                                       $source, $destination );
        }

        return false; // @as self::FAILED_FORBIDDEN;
    }

    /**
     * Copies the node specified by the path $source to $destination.
     *
     * @param string $sourceSite Eg. 'plain_site_user'
     * @param string $destinationSite Eg. 'plain_site_user'
     * @param string $sourceVFolder Eg. 'Content'
     * @param string $destinationVFolder Eg. 'Content'
     * @param string $source Eg. 'Folder1/file1.txt'
     * @param string $destination Eg. 'Folder2/file1.txt'
     * @return bool
     */
    protected function copyContent( $sourceSite, $destinationSite,
                                    $sourceVFolder, $destinationVFolder,
                                    $source, $destination )
    {
        $nodePath = $this->internalNodePath( $sourceVFolder, $source );
        $destinationNodePath = $this->internalNodePath( $destinationVFolder, $destination );

        // Get rid of possible extensions, remove .jpeg .txt .html etc..
        $source = $this->fileBasename( $source );

        $sourceNode = $this->fetchNodeByTranslation( $nodePath );

        if ( !$sourceNode )
        {
            return false; // @as self::FAILED_NOT_FOUND;
        }

        $object = $sourceNode->attribute( 'object' );
        $classID = $object->attribute( 'contentclass_id' );

        // Get rid of possible extensions, remove .jpeg .txt .html etc..
        $destination = $this->fileBasename( $destination );

        $destinationNode = $this->fetchNodeByTranslation( $destinationNodePath );

        // @as @todo check if this is needed
        // if ( $destinationNode )
        // {
        //     return false; // @as self::FAILED_EXISTS;
        // }

        $destinationNode = $this->fetchParentNodeByTranslation( $destinationNodePath );

        if ( !$destinationNode )
        {
            return false; // @as self::FAILED_NOT_FOUND;
        }

        // Can we move the node to $destinationNode
        if ( !$destinationNode->canMoveTo( $classID ) )
        {
            return false; // @as self::FAILED_FORBIDDEN;
        }

        $srcParentPath = $this->splitLastPathElement( $nodePath, $srcNodeName );
        $dstParentPath = $this->splitLastPathElement( $destinationNodePath, $dstNodeName );
        if ( $srcParentPath === $dstParentPath )
        {
            // @todo check if copy in the same folder is correct
            return $this->copyObjectSameDirectory( $sourceNode, $destinationNode, $this->fileBasename( $dstNodeName ) );
        }
        else
        {
            // @todo check if copy in different folders is correct
            return $this->copyObject( $sourceNode, $destinationNode );
        }

        /*
        // Todo: add lookup of the name setting for the current object
        $contentObjectID = $object->attribute( 'id' );
        $contentObjectAttributes =& $object->contentObjectAttributes();
        // @todo @as avoid using [0] (could be another index in some classes)
        $contentObjectAttributes[0]->setAttribute( 'data_text', basename( $destination ) );
        $contentObjectAttributes[0]->store();

        $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObjectID, 'version' => 1 ) );
        $object->store();
        */

        // @todo Unreachable code?
        return true; // @as self::OK_CREATED;
    }

    /**
     * Copies the specified object as a child to the node $newParentNode.
     *
     * @param eZContentObject $object
     * @param eZContentObject $newParentNode
     * @return bool
     */
    protected function copyObject( $object, $newParentNode )
    {
        $object = $object->ContentObject;
        $newParentNodeID = $newParentNode->attribute( 'node_id' );
        $classID = $object->attribute( 'contentclass_id' );

        if ( !$newParentNode->checkAccess( 'create', $classID ) )
        {
            $objectID = $object->attribute( 'id' );
            return false;
        }

        $db = eZDB::instance();
        $db->begin();
        $newObject = $object->copy( true );

        // We should reset section that will be updated in updateSectionID().
        // If sectionID is 0 than the object has been newly created
        $newObject->setAttribute( 'section_id', 0 );
        $newObject->store();

        $curVersion        = $newObject->attribute( 'current_version' );
        $curVersionObject  = $newObject->attribute( 'current' );
        $newObjAssignments = $curVersionObject->attribute( 'node_assignments' );
        unset( $curVersionObject );

        // remove old node assignments
        foreach ( $newObjAssignments as $assignment )
        {
            $assignment->purge();
        }

        // and create a new one
        $nodeAssignment = eZNodeAssignment::create( array(
                                                         'contentobject_id' => $newObject->attribute( 'id' ),
                                                         'contentobject_version' => $curVersion,
                                                         'parent_node' => $newParentNodeID,
                                                         'is_main' => 1
                                                         ) );
        $nodeAssignment->store();

        // publish the newly created object
        eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $newObject->attribute( 'id' ),
                                                                  'version'   => $curVersion ) );
        // Update "is_invisible" attribute for the newly created node.
        $newNode = $newObject->attribute( 'main_node' );
        eZContentObjectTreeNode::updateNodeVisibility( $newNode, $newParentNode );

        $db->commit();
        return true;
    }

    /**
     * Copies the specified object to the same folder, with optional $destinationName.
     *
     * @param eZContentObject $object
     * @param eZContentObject $newParentNode
     * @param string $destinationName
     * @return bool
     */
    protected function copyObjectSameDirectory( $object, $newParentNode, $destinationName = null )
    {
        $object = $object->ContentObject;
        $newParentNodeID = $newParentNode->attribute( 'node_id' );
        $classID = $object->attribute( 'contentclass_id' );

        if ( !$newParentNode->checkAccess( 'create', $classID ) )
        {
            $objectID = $object->attribute( 'id' );
            return false;
        }

        $db = eZDB::instance();
        $db->begin();
        $newObject = $object->copy( true );

        // We should reset section that will be updated in updateSectionID().
        // If sectionID is 0 than the object has been newly created
        $newObject->setAttribute( 'section_id', 0 );

        // @as 2009-01-08 - Added check for destination name the same as the source name
        // (this was causing problems with nodes disappearing after renaming)
        $newName = $destinationName;
        if ( $destinationName === null
             || strtolower( $destinationName ) === strtolower( $object->attribute( 'name' ) ) )
        {
            $newName = 'Copy of ' . $object->attribute( 'name' );
        }

        // @todo @as avoid using [0] (could be another index in some classes)
        $contentObjectAttributes = $newObject->contentObjectAttributes();
        $contentObjectAttributes[0]->setAttribute( 'data_text', $newName );
        $contentObjectAttributes[0]->store();

        $newObject->store();

        $curVersion        = $newObject->attribute( 'current_version' );
        $curVersionObject  = $newObject->attribute( 'current' );
        $newObjAssignments = $curVersionObject->attribute( 'node_assignments' );
        unset( $curVersionObject );

        // remove old node assignments
        foreach ( $newObjAssignments as $assignment )
        {
            $assignment->purge();
        }

        // and create a new one
        $nodeAssignment = eZNodeAssignment::create( array(
                                                         'contentobject_id' => $newObject->attribute( 'id' ),
                                                         'contentobject_version' => $curVersion,
                                                         'parent_node' => $newParentNodeID,
                                                         'is_main' => 1
                                                         ) );
        $nodeAssignment->store();

        // publish the newly created object
        eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $newObject->attribute( 'id' ),
                                                                  'version'   => $curVersion ) );
        // Update "is_invisible" attribute for the newly created node.
        $newNode = $newObject->attribute( 'main_node' );
        eZContentObjectTreeNode::updateNodeVisibility( $newNode, $newParentNode );

        $db->commit();
        return true;
    }

    /**
     * Handles moving on the virtual folder level.
     *
     * It will check if the target is below a content folder in which it
     * calls moveContent().
     *
     * @param string $sourceSite Eg. 'plain_site_user'
     * @param string $destinationSite Eg. 'plain_site_user'
     * @param string $source Eg. 'Content/Folder1/file1.txt'
     * @param string $destination Eg. 'Content/Folder2/file1.txt'
     * @return bool
     */
    protected function moveVirtualFolder( $sourceSite, $destinationSite,
                                $source, $destination,
                                $fullSource, $fullDestination )
    {
        $this->setCurrentSite( $sourceSite );

        $source = $this->splitFirstPathElement( $source, $sourceVFolder );
        $destination = $this->splitFirstPathElement( $destination, $destinationVFolder );

        if ( !$source or
             !$destination )
        {
            // We have reached the end of the path for source or destination
            // We do not allow 'move' operations for the virtual folder (from or to)
            return eZWebDAVServer::FAILED_FORBIDDEN;
        }

        if ( ( $sourceVFolder === self::virtualContentFolderName() or
               $sourceVFolder === self::virtualMediaFolderName() ) and
             ( $destinationVFolder === self::virtualContentFolderName() or
               $destinationVFolder === self::virtualMediaFolderName() ) )
        {
            return $this->moveContent( $sourceSite, $destinationSite,
                                       $sourceVFolder, $destinationVFolder,
                                       $source, $destination,
                                       $fullSource, $fullDestination );
        }

        return eZWebDAVServer::FAILED_FORBIDDEN;
    }

    /**
     * Moves the node specified by the path $source to $destination.
     *
     * @param string $sourceSite Eg. 'plain_site_user'
     * @param string $destinationSite Eg. 'plain_site_user'
     * @param string $sourceVFolder Eg. 'Content'
     * @param string $destinationVFolder Eg. 'Content'
     * @param string $source Eg. 'Folder1/file1.txt'
     * @param string $destination Eg. 'Folder2/file1.txt'
     * @return bool
     */
    protected function moveContent( $sourceSite, $destinationSite,
                          $sourceVFolder, $destinationVFolder,
                          $source, $destination,
                          $fullSource, $fullDestination )
    {
        $nodePath = $this->internalNodePath( $sourceVFolder, $source );
        $destinationNodePath = $this->internalNodePath( $destinationVFolder, $destination );

        // Get rid of possible extensions, remove .jpeg .txt .html etc..
        $source = $this->fileBasename( $source );

        $sourceNode = $this->fetchNodeByTranslation( $nodePath );

        if ( !$sourceNode )
        {
            return false; // @as self::FAILED_NOT_FOUND;
        }

        // Can we move the node from $sourceNode
        if ( !$sourceNode->canMoveFrom() )
        {
            $this->appendLogEntry( "No access to move the node '$sourceSite':'$nodePath'", 'CS:moveContent' );
            return false; // @as self::FAILED_FORBIDDEN;
        }

        $object = $sourceNode->attribute( 'object' );
        $classID = $object->attribute( 'contentclass_id' );

        // Get rid of possible extensions, remove .jpeg .txt .html etc..
        $destination = $this->fileBasename( $destination );

        $destinationNode = $this->fetchNodeByTranslation( $destinationNodePath );
        $this->appendLogEntry( "Destination: $destinationNodePath", 'CS:moveContent' );

        if ( $destinationNode )
        {
            return false; // @as self::FAILED_EXISTS;
        }

        $destinationNode = $this->fetchParentNodeByTranslation( $destinationNodePath );

        // Can we move the node to $destinationNode
        if ( !$destinationNode->canMoveTo( $classID ) )
        {
            $this->appendLogEntry( "No access to move the node '$sourceSite':'$nodePath' to '$destinationSite':'$destinationNodePath'", 'CS:moveContent' );
            return false; // @as self::FAILED_FORBIDDEN;
        }

        $srcParentPath = $this->splitLastPathElement( $nodePath, $srcNodeName );
        $dstParentPath = $this->splitLastPathElement( $destinationNodePath, $dstNodeName );
        if ( $srcParentPath == $dstParentPath )
        {
            // @as 2009-03-02 - removed urldecode of name before renaming
            $dstNodeName = $this->fileBasename( $dstNodeName );
            if( !$object->rename( $dstNodeName ) )
            {
                $this->appendLogEntry( "Unable to rename the node '$sourceSite':'$nodePath' to '$destinationSite':'$destinationNodePath'", 'CS:moveContent' );
                return false; // @as self::FAILED_FORBIDDEN;
            }
        }
        else
        {
            //include_once( 'kernel/classes/ezcontentobjecttreenodeoperations.php' );
            if( !eZContentObjectTreeNodeOperations::move( $sourceNode->attribute( 'node_id' ), $destinationNode->attribute( 'node_id' ) ) )
            {
                $this->appendLogEntry( "Unable to move the node '$sourceSite':'$nodePath' to '$destinationSite':'$destinationNodePath'", 'CS:moveContent' );
                return false; // @as self::FAILED_FORBIDDEN;
            }
        }

        /*

        // Todo: add lookup of the name setting for the current object
                    $contentObjectID = $object->attribute( 'id' );
                    $contentObjectAttributes =& $object->contentObjectAttributes();
                    $contentObjectAttributes[0]->setAttribute( 'data_text', basename( $destination ) );
                    $contentObjectAttributes[0]->store();

                    //include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
                    $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObjectID, 'version' => 1 ) );
                    $object->store();
        */

        return true; // @as self::OK_CREATED;
    }

    /**
     * Returns whether $class is an folder class.
     *
     * @param resource $object
     * @param resource $class
     */
    protected function isObjectFolder( $object, $class )
    {
        $classIdentifier = $class->attribute( 'identifier' );

        return in_array( $classIdentifier, $this->FolderClasses );
    }

    /**
     * Stores $contents to a temporary location under the file name $target.
     *
     * @param string $target
     * @param string $contents
     * @return string The name of the temp file or false if it failed.
     * @todo remove or replace with eZ Components functionality
     */
    protected function storeUploadedFile( $target, $contents )
    {
        $dir = self::tempDirectory() . '/' . md5( microtime() . '-' . $target );
        $filePath = $dir . '/' . basename( $target );

        if ( !file_exists( $dir ) )
        {
            eZDir::mkdir( $dir, false, true );
        }

        $result = file_put_contents( $filePath, $contents );
        if ( !$result )
        {
            $result = file_exists( $filePath );
        }

        if ( $result )
        {
            return $filePath;
        }
        return false;
    }

    /**
     * Returns the path to the WebDAV temporary directory.
     *
     * If the directory does not exist yet, it will be created first.
     *
     * @return string
     * @todo remove or replace with eZ Components functionality
     */
    protected static function tempDirectory()
    {
        $tempDir = eZSys::varDirectory() . '/webdav/tmp';
        if ( !file_exists( $tempDir ) )
        {
            eZDir::mkdir( $tempDir, eZDir::directoryPermission(), true );
        }
        return $tempDir;
    }

    /**
     * Returns $name without the final suffix (.jpg, .gif etc.).
     *
     * @param string $name
     * @return string
     * @todo remove or replace
     */
    protected function fileBasename( $name )
    {
        $pos = strrpos( $name, '.' );
        if ( $pos !== false )
        {
            $name = substr( $name, 0, $pos );
        }
        return $name;
    }

    /**
     * Returns a path that corresponds to the internal path of nodes.
     *
     * @param string $virtualFolder
     * @param string $collection
     * @return string
     * @todo remove or replace
     */
    protected function internalNodePath( $virtualFolder, $collection )
    {
        // All root nodes needs to prepend their name to get the correct path
        // except for the content root which uses the path directly.
        if ( $virtualFolder === self::virtualMediaFolderName() )
        {
            $nodePath = 'media';
            if ( strlen( $collection ) > 0 )
            {
                $nodePath .= '/' . $collection;
            }
        }
        else
        {
            $nodePath = $collection;
        }
        return $nodePath;
    }

    /**
     * Takes the first path element from \a $path and removes it from
     * the path, the extracted part will be placed in \a $name.
     *
     * <code>
     * $path = '/path/to/item/';
     * $newPath = self::splitFirstPathElement( $path, $root );
     * print( $root ); // prints 'path', $newPath is now 'to/item/'
     * $newPath = self::splitFirstPathElement( $newPath, $second );
     * print( $second ); // prints 'to', $newPath is now 'item/'
     * $newPath = self::splitFirstPathElement( $newPath, $third );
     * print( $third ); // prints 'item', $newPath is now ''
     * </code>
     * @param string $path A path of elements delimited by a slash, if the path ends with a slash it will be removed
     * @param string &$element The name of the first path element without any slashes
     * @return string The rest of the path without the ending slash
     * @todo remove or replace
     */
    protected function splitFirstPathElement( $path, &$element )
    {
        if ( $path[0] === '/' )
        {
            $path = substr( $path, 1 );
        }
        $pos = strpos( $path, '/' );
        if ( $pos === false )
        {
            $element = $path;
            $path = '';
        }
        else
        {
            $element = substr( $path, 0, $pos );
            $path = substr( $path, $pos + 1 );
        }
        return $path;
    }

    /**
     * Takes the last path element from \a $path and removes it from
     * the path, the extracted part will be placed in \a $name.
     *
     * <code>
     * $path = '/path/to/item/';
     * $newPath = self::splitLastPathElement( $path, $root );
     * print( $root ); // prints 'item', $newPath is now '/path/to'
     * $newPath = self::splitLastPathElement( $newPath, $second );
     * print( $second ); // prints 'to', $newPath is now '/path'
     * $newPath = self::splitLastPathElement( $newPath, $third );
     * print( $third ); // prints 'path', $newPath is now ''
     * </code>
     * @param string $path A path of elements delimited by a slash, if the path ends with a slash it will be removed
     * @param string &$element The name of the first path element without any slashes
     * @return string The rest of the path without the ending slash
     * @todo remove or replace
     */
    protected function splitLastPathElement( $path, &$element )
    {
        $len = strlen( $path );
        if ( $len > 0 and $path[$len - 1] === '/' )
        {
            $path = substr( $path, 0, $len - 1 );
        }
        $pos = strrpos( $path, '/' );
        if ( $pos === false )
        {
            $element = $path;
            $path = '';
        }
        else
        {
            $element = substr( $path, $pos + 1 );
            $path = substr( $path, 0, $pos );
        }
        return $path;
    }

    /**
     * Logs to var/log/webdav.log AND var/<site_name>/log/webdav.log.
     *
     * From eZ Publish.
     *
     * @param string $logString String to record
     * @param string $label Label to put in front of $logString
     */
    public static function appendLogEntry( $logString, $label = false )
    {
        if ( !isset( self::$useLogging ) )
        {
            $webdavINI = eZINI::instance( self::WEBDAV_INI_FILE );
            self::$useLogging = $webdavINI->variable( 'GeneralSettings', 'Logging' ) === 'enabled';
        }

        if ( self::$useLogging )
        {
            if ( PHP_SAPI === 'cli' )
            {
                // var_dump( $logString );
            }
            else
            {
                $varDir = realpath( eZSys::varDirectory() );
                $logDir = 'log';
                $logName = 'webdav.log';
                $fileName = $varDir . DIRECTORY_SEPARATOR . $logDir . DIRECTORY_SEPARATOR . $logName;
                if ( !file_exists( $varDir . DIRECTORY_SEPARATOR . $logDir ) )
                {
                    eZDir::mkdir( $varDir . DIRECTORY_SEPARATOR . $logDir, 0775, true );
                }

                $logFile = fopen( $fileName, 'a' );
                $nowTime = date( "Y-m-d H:i:s : " );
                $text = $nowTime . $logString;
                if ( $label )
                {
                    $text .= ' [' . $label . ']';
                }
                fwrite( $logFile, $text . "\n" );
                fclose( $logFile );
            }
        }
    }

    /**
     * Recodes $string from charset $fromCharset to charset $toCharset.
     *
     * Method from eZWebDAVServer.
     *
     * @param string $string
     * @param string $fromCharset
     * @param string $toCharset
     * @param bool $stop
     * @return string
     */
    protected static function recode( $string, $fromCharset, $toCharset, $stop = false )
    {
        $codec = eZTextCodec::instance( $fromCharset, $toCharset, false );
        if ( $codec )
        {
            $string = $codec->convertString( $string );
        }

        return $string;
    }

    /**
     * Encodes the path stored in $response in order to be displayed properly
     * in WebDAV clients.
     *
     * Code from eZWebDAVServer::outputCollectionContent.
     *
     * @param ezcWebdavResponse $response
     * @return ezcWebdavResponse
     */
    protected function encodeResponse( ezcWebdavResponse $response )
    {

        return $response;
    }

    /**
     * The name of the content folder in eZ Publish, translated.
     *
     * @return string
     */
    public static function virtualContentFolderName()
    {
        return ezpI18n::tr( 'kernel/content', 'Content' );
    }

    /**
     * The name of the media folder in eZ Publish, translated.
     *
     * @return string
     */
    public static function virtualMediaFolderName()
    {
        return ezpI18n::tr( 'kernel/content', 'Media' );
    }
}
?>
