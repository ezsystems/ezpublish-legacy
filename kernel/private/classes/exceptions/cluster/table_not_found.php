<?php
/**
 * File containing the eZDFSFileHandlerTableNotFoundException class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * Class handling a cluster table not found exception
 *
 * @version //autogentag//
 * @package kernel
 */

class eZDFSFileHandlerTableNotFoundException extends ezcBaseException
{
    /**
     * Constructs a new eZDFSFileHandlerTableNotFoundException
     *
     * @param string $sql The SQL query
     * @param string $error The SQL error message
     * @return void
     */
    function __construct( $sql, $error )
    {
        $message = "Table not found when executing SQL '$sql' ".
                   "(error message: $error).\n" .
                   "Please review the cluster tables installation or configuration";
        parent::__construct( $message );
    }
}
?>
