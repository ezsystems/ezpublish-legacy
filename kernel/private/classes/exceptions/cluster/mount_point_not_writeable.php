<?php
/**
 * File containing the eZDFSFileHandlerNFSMountPointNotWriteableException class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * Class representing a cluster mount point not writeable exception
 *
 * @version //autogentag//
 * @package kernel
 */

class eZDFSFileHandlerNFSMountPointNotWriteableException extends ezcBaseException
{
    /**
     * Constructs a new eZDFSFileHandlerNFSMountPointNotFoundException
     *
     * @param string $path the mount point path
     * @return void
     */
    function __construct( $path )
    {
    	parent::__construct( "Local DFS mount point '{$path}' is not writeable" );
    }
}
?>