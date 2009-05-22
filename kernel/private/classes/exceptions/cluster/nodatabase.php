<?php
/**
 * File containing the eZClusterHandlerDBNoDatabaseException class.
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * Class representing a no database exception
 *
 * @version //autogentag//
 * @package kernel
 */

class eZClusterHandlerDBNoDatabaseException extends eZDBException
{
    /**
     * Constructs a new eZClusterHandlerDBNoDatabaseException
     *
     * @param string $dbname The database
     * @return void
     */
    function __construct( $dbname )
    {
	parent::__construct( "Unable to select the cluster database {$dbname}" );
    }
}
?>