<?php
/**
 * File containing the eZDFSFileHandlerNFSMountPointNotWriteableException class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
