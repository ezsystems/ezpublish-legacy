<?php
/**
 * File containing the eZUserFunctionCollection class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZUserFunctionCollection ezuserfunctioncollection.php
  \brief The class eZUserFunctionCollection does

*/

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
        $isLoggedIn = eZUser::isUserLoggedIn( $userID );
        return array( 'result' => $isLoggedIn );
    }

    function fetchLoggedInCount()
    {
        $count = eZUser::fetchLoggedInCount();
        return array( 'result' => $count );
    }

    function fetchAnonymousCount()
    {
        $count = eZUser::fetchAnonymousCount();
        return array( 'result' => $count );
    }

    function fetchLoggedInList( $sortBy, $offset, $limit )
    {
        $list = eZUser::fetchLoggedInList( false, $offset, $limit, $sortBy );
        return array( 'result' => $list );
    }

    function fetchLoggedInUsers( $sortBy, $offset, $limit )
    {
        $list = eZUser::fetchLoggedInList( true, $offset, $limit, $sortBy );
        return array( 'result' => $list );
    }

    /**
     * Fetch policy list
     * Used by fetch( 'user', 'user_role', hash( 'user_id', $id ) ) template function.
     *
     * @param int $id User id or normal content object id in case of none user object (user group)
     * @return array(string=>array)
     */
    function fetchUserRole( $id )
    {
        $user = eZUser::fetch( $id );
        if ( $user instanceof eZUser )
            $roleList = $user->roles();
        else // user group or other non user classes:
            $roleList = eZRole::fetchByUser( array( $id ), true );

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
                                if ( $limitationKey !== '*' )
                                {
                                    $policyID = str_replace( 'p_', '', $limitationKey );
                                    $userRoleIdSeperator = strpos( $policyID, '_' );

                                    if ( $userRoleIdSeperator !== false )
                                    {
                                        $policyID = substr( $policyID, 0, $userRoleIdSeperator );
                                    }

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

    /**
     * Fetch role list
     * Used by fetch( 'user', 'member_of', hash( 'id', $id ) ) template function.
     *
     * @param int $id User id or normal content object id in case of none user object (user group)
     * @return array(string=>array)
     */
    function fetchMemberOf( $id )
    {
        $user = eZUser::fetch( $id );
        if ( $user instanceof eZUser )
            $roleList = $user->roles();
        else // user group or other non user classes:
            $roleList = eZRole::fetchByUser( array( $id ), true );

        return array( 'result' => $roleList );
    }

    function hasAccessTo( $module, $view, $userID )
    {
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
