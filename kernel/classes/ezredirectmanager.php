<?php
//
// Definition of eZRedirectManager class
//
// Created on: <24-Nov-2004 15:03:51 jb>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezredirectmanager.php
*/

/*!
  \class eZRedirectManager ezredirectmanager.php
  \brief Handles generation of redirection URIs and redirection

*/

class eZRedirectManager
{

    /*!
     Generates a URI which can be used to redirect with, the uri is based on:
     - The last accessed view/non-view page if any (see \a $view parameter)
     - The uri is not the currently running module, if so use default
     - The default uri \a $default

     \return The new URI string or \c false if no uri could be made.

     \param $module The current module object
     \param $default The default URI to redirect to if all else fails.
                     If set to \c false then it will return false.
     \param $view If true it will try to redirect to last accessed view URI.
     \param $disallowed An array with urls not allowed to redirect to or \c false to allow all

     \note All URLs must start with a slash \c /

     \sa redirectTo()
    */
    function redirectURI( &$module, $default, $view = true, $disallowed = false )
    {
        $uri = false;
        $http =& eZHTTPTool::instance();

        if ( $view )
        {
            if ( $http->hasSessionVariable( "LastAccessesURI" ) )
            {
                $uri = $http->sessionVariable( "LastAccessesURI" );
                if ( $http->hasSessionVariable( 'LastAccessesParameters' ) )
                {
                    $parameters = $http->sessionVariable( 'LastAccessesParameters' );
                    foreach ( $parameters as $name => $value )
                    {
                        $uri .= '/(' . $name . ')/' . $value;
                    }
                }
            }
        }
        else
        {
            if ( $http->hasSessionVariable( "LastAccessedModifyingURI" ) )
            {
                $uri = $http->sessionVariable( "LastAccessedModifyingURI" );
                if ( $http->hasSessionVariable( 'LastAccessedModifyingParameters' ) )
                {
                    $parameters = $http->sessionVariable( 'LastAccessedModifyingParameters' );
                    foreach ( $parameters as $name => $value )
                    {
                        $uri .= '/(' . $name . ')/' . $value;
                    }
                }
            }
        }

        if ( $uri !== false )
        {
            $moduleURI = $module->functionURI( $module->currentView() );
            // Check for correct module/view
            if ( substr( $uri, 0, strlen( $moduleURI ) ) == $moduleURI )
            {
                // Check parameters
                $moduleURI = $module->currentRedirectionURI();
                if ( $moduleURI == $uri )
                    $uri = false;
            }
        }

        // Check for disallowed urls
        if ( $uri !== false and
             is_array( $disallowed ) )
        {
            if ( in_array( $uri, $disallowed ) )
                $uri = false;
        }

        if ( $uri === false )
        {
            // If no default is set we should return false.
            if ( $default === false )
                return false;
            $uri = $default;
        }

        return $uri;
    }

    /*!
     Generates a URI which can be used to redirect with, the uri is based on:
     - The last accessed view/non-view page if any (see \a $view parameter)
     - The uri is not the currently running module, if so use default
     - The default uri \a $default

     \param $module The current module object
     \param $default The default URI to redirect to if all else fails.
                     If set to \c false then it will not redirect if there is no url found
                     but instead it will return false.
     \param $view If true it will try to redirect to last accessed view URI.
     \param $disallowed An array with urls not allowed to redirect to or \c false to allow all

     \return \c true if the module was redirected or \c false if not.

     \note All URLs must start with a slash \c /
     \sa redirectURI()
    */
    function redirectTo( &$module, $default, $view = true, $disallowed = false )
    {
        $uri = eZRedirectManager::redirectURI( $module, $default, $view, $disallowed );
        if ( $uri === false )
            return false;
        $module->redirectTo( $uri );
        return true;
    }
}

?>
