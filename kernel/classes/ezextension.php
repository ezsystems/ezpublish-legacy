<?php
//
// Definition of eZExtension class
//
// Created on: <16-äÅË-2002 14:23:45 amos>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezextension.php
*/

/*!
  \class eZExtension ezextension.php
  \brief The class eZExtension does

*/

include_once( 'lib/ezutils/classes/ezini.php' );

class eZExtension
{
    /*!
     Constructor
    */
    function eZExtension()
    {
    }

    /*!
     \static
     \return the base directory for extensions
    */
    function baseDirectory()
    {
        $ini =& eZINI::instance();
        $extensionDirectory = $ini->variable( 'ExtensionSettings', 'ExtensionDirectory' );
        return $extensionDirectory;
    }

    /*!
     \static
     \return an array with extensions that has been activated
    */
    function activeExtensions()
    {
        $ini =& eZINI::instance();
        $activeExtensions = $ini->variable( 'ExtensionSettings', 'ActiveExtensions' );
        return $activeExtensions;
    }

    /*!
     \static
     Will make sure that all extensions that has settings directories
     are added to the eZINI override list.
    */
    function activateExtensions()
    {
        $extensionDirectory = eZExtension::baseDirectory();
        $activeExtensions = eZExtension::activeExtensions();
//         eZDebug::writeDebug( "Activating extensions for $extensionDirectory", 'eZExtension' );
//         eZDebug::writeDebug( $activeExtensions, 'eZExtension' );
        $hasExtensions = false;
        $ini =& eZINI::instance();
        foreach ( $activeExtensions as $activeExtension )
        {
            $extensionPath = $extensionDirectory . '/' . $activeExtension;
            $extensionSettingsPath = $extensionPath . '/settings';
//             eZDebug::writeDebug( "Checking extension path $extensionSettingsPath", 'eZExtension' );
            if ( file_exists( $extensionPath ) and
                 file_exists( $extensionSettingsPath ) )
            {
//                 eZDebug::writeDebug( "Added extension path $extensionSettingsPath", 'eZExtension' );
                $ini->prependOverrideDir( $extensionSettingsPath, true );
                $hasExtensions = true;
            }
        }
        if ( $hasExtensions )
            $ini->loadCache();
    }

}

/*!
 Includes the file named \a $name in extension \a $extension
 \note This works similar to include() meaning that it always includes the file.
*/
function ext_include( $extension, $name )
{
    $base = eZExtension::baseDirectory();
    $include = "$base/$extension/$name";
    return include( $include );
}

/*!
 Activates the file named \a $name in extension \a $extension
 \note This works similar to include_once() meaning that it's included one time.
*/
function ext_activate( $extension, $name )
{
    $base = eZExtension::baseDirectory();
    $include = "$base/$extension/$name";
    return include_once( $include );
}

/*!
 Activates the file named \a $name in extension \a $extension
 \note This works similar to include_once() meaning that it's included one time.
*/
function ext_class( $extension, $name )
{
    $name = strtolower( $name );
    $base = eZExtension::baseDirectory();
    $include = "$base/$extension/classes/$name.php";
    return include_once( $include );
}

/*!
*/
function lib_include( $libName, $name )
{
    $include = "lib/$libName/classes/$name";
    return include_once( $include );
}


?>
