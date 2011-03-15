<?php
/**
 * File containing the eZDMySQLBackendError class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

/*!
 \class eZMySQLBackendError mysqlbackenderror.php
 \brief Error class for the MySQL backend (cluster code).
 */

class eZDFSMySQLBackendError
{
    function eZDFSMySQLBackendError( $value, $text )
    {
        $this->errorValue = $value;
        $this->errorText  = $text;
    }
}

?>
