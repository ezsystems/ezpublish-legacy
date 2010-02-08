<?php
//
// This is the eZWebDAVContentBackendAuth class. Manages WebDAV authentication.
// Based on the eZ Components Webdav component.
//
// Created on: <14-Jul-2008 15:15:15 as>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

/*!
  \class eZWebDAVContentBackendAuth ezwebdavcontentbackendauth.php
  \ingroup eZWebDAV
  \brief Manages WebDAV basic authentication.

*/

/**
 * Basic authentication for WebDAV.
 */
class eZWebDAVContentBackendAuth implements ezcWebdavAnonymousAuthenticator, ezcWebdavBasicAuthenticator, ezcWebdavAuthorizer, ezcWebdavLockAuthorizer
{
    public function authenticateAnonymous( ezcWebdavAnonymousAuth $data )
    {
        // added by @ds to fix problems with IE6 SP2
        $path = ezcWebdavServer::getInstance()->pathFactory->parseUriToPath( $_SERVER['REQUEST_URI'] );
    	return ( $path === '/' );
    }

    /**
     * Checks authentication for the given $user.
     *
     * This method checks the given user/password credentials encapsulated in
     * $data. Returns true if the user was succesfully recognized and the
     * password is valid for him, false otherwise. In case no username and/or
     * password was provided in the request, empty strings are provided as the
     * parameters of this method.
     * 
     * @param ezcWebdavBasicAuth $data
     * @return bool
     */
    public function authenticateBasic( ezcWebdavBasicAuth $data )
    {
        $loginHandler = 'standard';

        eZWebDAVContentBackend::appendLogEntry( "Got username: {$data->username}" );
        // added by @ds to fix problems with IE6 SP2
        if ( preg_match( '(^' . preg_quote( $_SERVER['SERVER_NAME'] ) . '(.+))', $data->username, $matches ) > 0 )
        {
            $data->username = $matches[1];
        }
        eZWebDAVContentBackend::appendLogEntry( "Processed to username: {$data->username}" );

        $userClass = eZUserLoginHandler::instance( $loginHandler );
        $user = $userClass->loginUser( $data->username, $data->password );

        if ( !( $user instanceof eZUser ) )
        {
            return false;
        }
        eZWebDAVContentBackend::appendLogEntry( "AuthenticatedBasic" );
        return true;
    }

    /**
     * Checks authorization of the given $user to a given $path.
     *
     * This method checks if the given $user has the permission $access to the
     * resource identified by $path. The $path is the result of a translation
     * by the servers {@link ezcWebdavPathFactory} from the request URI.
     *
     * The $access parameter can be one of
     * <ul>
     *    <li>{@link ezcWebdavAuthorizer::ACCESS_WRITE}</li>
     *    <li>{@link ezcWebdavAuthorizer::ACCESS_READ}</li>
     * </ul>
     *
     * The implementation of this method must only check the given $path, but
     * MUST not check descendant paths, since the back end will issue dedicated
     * calls for such paths. In contrast, the algoritm MUST ensure, that parent
     * permission constraints of the given $paths are met.
     *
     * Examples:
     * Permission is rejected for the paths "/a", "/b/beamme" and "/c/connect":
     *
     * <code>
     * <?php
     * var_dump( $auth->authorize( 'johndoe', '/a' ) ); // false
     * var_dump( $auth->authorize( 'johndoe', '/b' ) ); // true
     * var_dump( $auth->authorize( 'johndoe', '/b/beamme' ) ); // false
     * var_dump( $auth->authorize( 'johndoe', '/c/connect/some/deeper/path' ) ); // false
     * ?>
     * </code>
     * 
     * @param string $user 
     * @param string $path 
     * @param int $access 
     * @return bool
     */
    public function authorize( $user, $path, $access = self::ACCESS_READ )
    {
        $fullPath = $path;
        if ( $fullPath === '' )
        {
            $fullPath = '/';
        }

        if ( $access === self::ACCESS_READ )
        {
            // reading the root (/) is allowed
            return true;
        }

        $target = $this->splitFirstPathElement( $fullPath, $site );
        if ( $target === ''
             && $access === self::ACCESS_READ )
        {
            // reading the site list is allowed
            return true;
        }
        else
        {
            if ( $target !== '' )
            {
                $target = $this->splitFirstPathElement( $target, $element );
            }
        }

        if ( $target === '' && $access = self::ACCESS_WRITE )
        {
            // writing to second-level paths (/plain_site_user/) is not allowed
            return false;
        }

        $user = eZUser::currentUser();
        $result = $user->hasAccessTo( 'user', 'login' );
        $accessWord = $result['accessWord'];

        if ( $accessWord == 'limited' )
        {
            $hasAccess = false;
            $policyChecked = false;
            foreach ( array_keys( $result['policies'] ) as $key )
            {
                $policy =& $result['policies'][$key];
                if ( isset( $policy['SiteAccess'] ) )
                {
                    $policyChecked = true;
                    if ( in_array( eZSys::ezcrc32( $site ), $policy['SiteAccess'] ) )
                    {
                        $hasAccess = true;
                        break;
                    }
                }
                if ( $hasAccess )
                {
                    break;
                }
            }
            if ( !$policyChecked )
            {
                $hasAccess = true;
            }
        }
        else if ( $accessWord == 'yes' )
        {
            $hasAccess = true;
        }
        else if ( $accessWord == 'no' )
        {
            $hasAccess = false;
        }
        return $hasAccess;
    }

    /**
     * Assign a $lockToken to a given $user.
     *
     * The authorization backend needs to save an arbitrary number of lock
     * tokens per user. A lock token is a of maximum length 255
     * containing:
     *
     * <ul>
     *  <li>characters</li>
     *  <li>numbers</li>
     *  <li>dashes (-)</li>
     * </ul>
     * 
     * @param string $user 
     * @param string $lockToken 
     * @return void
     */
    public function assignLock( $user, $lockToken )
    {
        // @as @todo implement
    }

    /**
     * Returns if the given $lockToken is owned by the given $user.
     *
     * Returns true, if the $lockToken is owned by $user, false otherwise.
     * 
     * @param string $user 
     * @param string $lockToken 
     * @return bool
     */
    public function ownsLock( $user, $lockToken )
    {
        // @as @todo implement
    }
    
    /**
     * Removes the assignement of $lockToken from $user.
     *
     * After a $lockToken has been released from the $user, the {@link
     * ownsLock()} method must return false for the given combination. It might
     * happen, that a lock is to be released, which already has been removed.
     * This case must be ignored by the method.
     * 
     * @param string $user 
     * @param string $lockToken 
     */
    public function releaseLock( $user, $lockToken )
    {
        // @as @todo implement
    }

    /**
     * Takes the first path element from \a $path and removes it from
     * the path, the extracted part will be placed in \a $name.
     *
     * <code>
     * $path = '/path/to/item/';
     * $newPath = self::splitFirstPathElement( $path, $root );
     * print( $root ); // prints 'path', $newPath is now 'to/item/'
     * $newPath = self::splitFirstPathElement( $newPath, $second );
     * print( $second ); // prints 'to', $newPath is now 'item/'
     * $newPath = self::splitFirstPathElement( $newPath, $third );
     * print( $third ); // prints 'item', $newPath is now ''
     * </code>
     * @param string $path A path of elements delimited by a slash, if the path ends with a slash it will be removed
     * @param string &$element The name of the first path element without any slashes
     * @return string The rest of the path without the ending slash
     * @todo remove or replace
     */
    protected function splitFirstPathElement( $path, &$element )
    {
        if ( $path[0] == '/' )
        {
            $path = substr( $path, 1 );
        }
        $pos = strpos( $path, '/' );
        if ( $pos === false )
        {
            $element = $path;
            $path = '';
        }
        else
        {
            $element = substr( $path, 0, $pos );
            $path = substr( $path, $pos + 1 );
        }
        return $path;
    }
}
?>
