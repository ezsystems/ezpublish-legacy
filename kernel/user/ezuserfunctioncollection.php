<?php
//
// Definition of eZUserFunctionCollection class
//
// Created on: <06-Oct-2002 16:19:31 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezuserfunctioncollection.php
*/

/*!
  \class eZUserFunctionCollection ezuserfunctioncollection.php
  \brief The class eZUserFunctionCollection does

*/

include_once( 'kernel/error/errors.php' );

class eZUserFunctionCollection
{
    /*!
     Constructor
    */
    function eZUserFunctionCollection()
    {
    }

    function &fetchCurrentUser()
    {
        include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
        $user =& eZUser::currentUser();
        if ( $user === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => $user );
    }

    function &fetchIsLoggedIn( $userID )
    {
        include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
        $isLoggedIn =& eZUser::isUserLoggedIn( $userID );
        return array( 'result' => $isLoggedIn );
    }

    function &fetchLoggedInCount()
    {
        include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
        $count =& eZUser::fetchLoggedInCount();
        return array( 'result' => $count );
    }

    function &fetchAnonymousCount()
    {
        include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
        $count =& eZUser::fetchAnonymousCount();
        return array( 'result' => $count );
    }

    function fetchLoggedInList( $sortBy, $offset, $limit )
    {
        include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
        $list =& eZUser::fetchLoggedInList( false, $offset, $limit, $sortBy );
        return array( 'result' => $list );
    }

    function fetchLoggedInUsers( $sortBy, $offset, $limit )
    {
        include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
        $list =& eZUser::fetchLoggedInList( true, $offset, $limit, $sortBy );
        return array( 'result' => $list );
    }

    function &fetchMemberOf( $id )
    {
        include_once( 'kernel/classes/ezrole.php' );
        return array( 'result' => eZRole::fetchByUser( array( $id ) ) );
    }
}

?>
