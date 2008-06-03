<?php
//
// Definition of eZURLFunctionCollection class
//
// Created on: <06-Oct-2002 16:19:31 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file ezcontentfunctioncollection.php
*/

/*!
  \class eZURLFunctionCollection ezurlfunctioncollection.php
  \brief The class eZURLFunctionCollection does

*/

//include_once( 'kernel/error/errors.php' );

class eZURLFunctionCollection
{
    /*!
     Constructor
    */
    function eZURLFunctionCollection()
    {
    }

    function fetchList( $isValid, $offset, $limit, $onlyPublished )
    {
        //include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );
        $parameters = array( 'is_valid' => $isValid,
                             'offset' => $offset,
                             'limit' => $limit,
                             'only_published' => $onlyPublished );
        $list = eZURL::fetchList( $parameters );
        if ( $list === null )
            $result = array( 'error' => array( 'error_type' => 'kernel',
                                               'error_code' => eZError::KERNEL_NOT_FOUND ) );
        else
            $result = array( 'result' => $list );
        return $result;
    }

    function fetchListCount( $isValid, $onlyPublished )
    {
        //include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );
        $parameters = array( 'is_valid' => $isValid,
                             'only_published' => $onlyPublished );
        $listCount = eZURL::fetchListCount( $parameters );
        if ( $listCount === null )
            $result = array( 'error' => array( 'error_type' => 'kernel',
                                               'error_code' => eZError::KERNEL_NOT_FOUND ) );
        else
            $result = array( 'result' => $listCount );
        return $result;
    }

}

?>
