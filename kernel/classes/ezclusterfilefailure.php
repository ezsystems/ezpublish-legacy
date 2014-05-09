<?php
/**
 * File containing the eZClusterFileFailure class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
    const FILE_EXPIRED = 1,
          FILE_CONTENT_GENERATE = 2,
          FILE_RETRIEVAL_FAILED = 3;

    // Error codes:
    // 1 - file expired
    // 2 - file contents must be manually generated
    // 3 - Failed to retrieve file from DFS
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
