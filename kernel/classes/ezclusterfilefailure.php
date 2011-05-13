<?php
/**
 * File containing the eZClusterFileFailure class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
 \class eZClusterFileHandler ezclusterfilefailure.php
 Special failure object which can be used by some of the cluster functions
 to inform about failures or special exceptions.

 Currently used by the *processCache* function to report that the retrieve callback
 failed to retrieve data because of expiration.
 */
class eZClusterFileFailure
{
    // Error codes:
    // 1 - file expired
    // 2 - file contents must be manually generated
    function eZClusterFileFailure( $errno, $message = false )
    {
        $this->Errno = $errno;
        $this->Message = $message;
    }

    /*!
     Returns the error number.
     */
    function errno()
    {
        return $this->Errno;
    }

    /*!
     Returns the error message if there is one.
     */
    function message()
    {
        return $this->Message;
    }
}
?>
