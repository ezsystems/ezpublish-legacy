<?php
/**
 * File containing the eZDMySQLBackendError class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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

    public $errorValue;
    public $errorText;
}
