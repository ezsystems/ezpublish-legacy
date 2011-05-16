<?php
/**
 * File containing the eZDFSFileHandlerNFSMountPointNotWriteableException class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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
