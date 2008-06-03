<?php
//
// Definition of eZUserFunctionCollection class
//
// Created on: <06-Oct-2002 16:19:31 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
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

/*! \file ezuserfunctioncollection.php
*/

/*!
  \class eZUserFunctionCollection ezuserfunctioncollection.php
  \brief The class eZUserFunctionCollection does

*/

//include_once( 'kernel/error/errors.php' );

class eZUserFunctionCollection
{
    /*!
     Constructor
    */
    function eZUserFunctionCollection()
    {
    }

    function fetchCurrentUser()
    {
        //include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
        $user = eZUser::currentUser();
        if ( $user === null )
        {
            $result = array( 'error' => array( 'error_type' => 'kernel',
                                               'error_code' => eZError::KERNEL_NOT_FOUND ) );
        }
        else
        {
            $result = array( 'result' => $user );
        }
        return $result;
    }

    function fetchIsLoggedIn( $userID )
    {
        //include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
        $isLoggedIn = eZUser::isUserLoggedIn( $userID );
        return array( 'result' => $isLoggedIn );
    }

    function fetchLoggedInCount()
    {
        //include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
        $count = eZUser::fetchLoggedInCount();
        return array( 'result' => $count );
    }

    function fetchAnonymousCount()
    {
        //include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
        $count = eZUser::fetchAnonymousCount();
        return array( 'result' => $count );
    }

    function fetchLoggedInList( $sortBy, $offset, $limit )
    {
        //include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
        $list = eZUser::fetchLoggedInList( false, $offset, $limit, $sortBy );
        return array( 'result' => $list );
    }

    function fetchLoggedInUsers( $sortBy, $offset, $limit )
    {
        //include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
        $list = eZUser::fetchLoggedInList( true, $offset, $limit, $sortBy );
        return array( 'result' => $list );
    }

    function fetchUserRole( $userID )
    {
        //include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
        //include_once( 'kernel/classes/ezrole.php' );
        //include_once( "kernel/classes/ezpolicylimitation.php" );
        $user = eZUser::fetch( $userID );
        $userGroupObjects = $user ? $user->groups( true ) : array();
        $userGroupArray = array();
        foreach ( $userGroupObjects as $userGroupObject )
        {
            $userGroupArray[] = $userGroupObject->attribute( 'id' );
        }
        $userGroupArray[] = $userID;
        $roleList = eZRole::fetchByUser( $userGroupArray );

        $accessArray = array();
        foreach ( array_keys ( $roleList ) as $roleKey )
        {
            $role = $roleList[$roleKey];
            $accessArray = array_merge_recursive( $accessArray, $role->accessArray( true ) );
        }
        $resultArray = array();
        foreach ( $accessArray as $moduleKey => $module )
        {
            $moduleName = $moduleKey;
            if ( $moduleName != '*' )
            {
                foreach ( $module as $functionKey => $function )
                {
                    $functionName = $functionKey;
                    if ( $functionName != '*' )
                    {
                        $hasLimitation = true;
                        foreach ( $function as $limitationKey )
                        {
                            if ( $limitationKey == '*' )
                            {
                                $hasLimitation = false;
                                $limitationValue = '*';
                                $resultArray[] = array( 'moduleName' => $moduleName, 'functionName' => $functionName, 'limitation' =>  $limitationValue );
                            }
                        }
                        if ( $hasLimitation )
                        {
                            foreach ( $function as $limitationKey => $limitation )
                            {
                                if ( $limitationKey != '*' )
                                {
                                    $policyID = str_replace( 'p_', '', $limitationKey );
                                    $limitationValue = eZPolicyLimitation::fetchByPolicyID( $policyID );
                                    $resultArray[] = array( 'moduleName' => $moduleName, 'functionName' => $functionName, 'limitation' =>  $limitationValue );
                                }
                                else
                                {
                                    $limitationValue = '*';
                                    $resultArray[] = array( 'moduleName' => $moduleName, 'functionName' => $functionName, 'limitation' =>  $limitationValue );
                                    break;
                                }
                            }
                        }
                    }
                    else
                    {
                        $limitationValue = '*';
                        $resultArray[] = array( 'moduleName' => $moduleName, 'functionName' => $functionName, 'limitation' =>  $limitationValue );
                        break;
                    }
                }
            }
            else
            {
                $functionName = '*';
                $resultArray[] = array( 'moduleName' => '*', 'functionName' => $functionName, 'limitation' => '*' );
                break;
            }
        }
        return array( 'result' => $resultArray );
    }

    function fetchMemberOf( $id )
    {
        //include_once( 'kernel/classes/ezrole.php' );
        return array( 'result' => eZRole::fetchByUser( array( $id ), true ) );
    }

    function hasAccessTo( $module, $view, $userID )
    {
        //include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
        if ( $userID )
        {
            $user = eZUser::fetch( $userID );
        }
        else
        {
            $user = eZUser::currentUser();
        }
        if ( is_object( $user ) )
        {
            $result = $user->hasAccessTo( $module, $view );
            return array( 'result' => $result['accessWord'] != 'no' );
        }
        else
        {
            return array( 'result' => false );
        }
    }
}

?>
