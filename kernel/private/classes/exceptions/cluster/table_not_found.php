<?php
/**
 * File containing the eZDFSFileHandlerTableNotFoundException class.
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
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