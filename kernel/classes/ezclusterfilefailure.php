<?php
//
// Definition of eZClusterFileFailure class
//
// Created on: <16-May-2007 09:04:53 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file ezclusterfilefailure.php
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
