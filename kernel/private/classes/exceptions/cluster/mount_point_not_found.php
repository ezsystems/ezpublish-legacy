<?php
/**
 * File containing the eZDFSFileHandlerNFSMountPointNotFoundException class.
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * Class representing a cluster mount point not found exception
 *
 * @version //autogentag//
 * @package kernel
 */

class eZDFSFileHandlerNFSMountPointNotFoundException extends ezcBaseException
{
    /**
     * Constructs a new eZDFSFileHandlerNFSMountPointNotFoundException
     *
     * @param string $host The hostname
     * @param string $user The username
     * @param string $pass The password (will be displayed as *)
     * @return void
     */
    function __construct( $path )
    {
    	parent::__construct( "Local DFS mount point '{$path}' does not exist" );
    }
}
?>