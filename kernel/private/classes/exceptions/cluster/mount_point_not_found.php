<?php
/**
 * File containing the eZDFSFileHandlerNFSMountPointNotFoundException class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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
