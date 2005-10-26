<?php
//
// Definition of eZExtension class
//
// Created on: <16-Mar-2002 14:23:45 amos>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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
     \return an array with extensions that has been activated.
     \param $extensionType Decides which extension to include in the list, the follow values are possible.
            - \c false - Means add both default and access extensions
            - 'default' - Add only default extensions
            - 'access' - Add only access extensions

     Default extensions are those who are loaded before a siteaccess are determined while access extensions
     are loaded after siteaccess is set.
    */
    function activeExtensions( $extensionType = false )
    {
        $ini =& eZINI::instance();
        $activeExtensions = array();
        if ( !$extensionType or
             $extensionType == 'default' )
            $activeExtensions = array_merge( $activeExtensions,
                                             $ini->variable( 'ExtensionSettings', 'ActiveExtensions' ) );
        if ( !$extensionType or
             $extensionType == 'access' )
            $activeExtensions = array_merge( $activeExtensions,
                                             $ini->variable( 'ExtensionSettings', 'ActiveAccessExtensions' ) );
        $globalActiveExtensions =& $GLOBALS['eZActiveExtensions'];
        if ( isset( $globalActiveExtensions ) )
        {
            $activeExtensions = array_merge( $activeExtensions,
                                             $globalActiveExtensions );
        }
        $activeExtensions = array_unique( $activeExtensions );
        return $activeExtensions;
    }

    /*!
     \static
     Will make sure that all extensions that has settings directories
     are added to the eZINI override list.
    */
    function activateExtensions( $extensionType = false )
    {
        $extensionDirectory = eZExtension::baseDirectory();
        $activeExtensions = eZExtension::activeExtensions( $extensionType );
        $hasExtensions = false;
        $ini =& eZINI::instance();
        foreach ( $activeExtensions as $activeExtension )
        {
            if ( !file_exists( $extensionDirectory . '/' . $activeExtension ) )
            {
                eZDebug::writeWarning( "Extension '$activeExtension' does not exist, looked for directory '" . $extensionDirectory . '/' . $activeExtension . "'" );
            }
            $extensionSettingsPath = $extensionDirectory . '/' . $activeExtension . '/settings';
            if ( file_exists( $extensionSettingsPath ) )
            {
                $ini->prependOverrideDir( $extensionSettingsPath, true );

                if ( isset( $GLOBALS['eZCurrentAccess'] ) )
                {
                    eZExtension::prependSiteAccess( $activeExtension );
                }
                $hasExtensions = true;
            }
        }
        if ( $hasExtensions )
            $ini->loadCache();
    }

    /*!
     \static

     Prepend extension siteaccesses

     \param siteaccess name ( default false )
    */
    function prependExtensionSiteAccesses( $accessName = false, $ini = false, $globalDir = true, $identifier = false, $order = true )
    {
        $extensionList = eZExtension::activeExtensions();

        if ( !$order )
        {
            $extensionList = array_reverse( $extensionList );
        }

        foreach( $extensionList as $extension )
        {
            eZExtension::prependSiteAccess( $extension, $accessName, $ini, $globalDir, $identifier );
        }
    }

    /*!
     \static

     Prepend siteaccess for specified extension.

     \param $extension name
    */
    function prependSiteAccess( $extension, $accessName = false, $ini = false, $globalDir = true, $identifier = false )
    {
        if ( !$accessName )
        {
            $accessName = $GLOBALS['eZCurrentAccess']['name'];
        }

        $extensionSettingsPath = eZExtension::baseDirectory() . '/' . $extension;

        if ( file_exists ( $extensionSettingsPath . '/settings/siteaccess/' . $accessName ) )
        {
            if ( !$ini )
            {
                $ini =& eZINI::instance();
            }
            $ini->prependOverrideDir( $extensionSettingsPath . '/settings/siteaccess/' . $accessName, $globalDir );
        }
    }

    /*!
     \static
     Generates a list with expanded paths and returns it.
     The paths are expanded to where the extensions are placed.
     Optionally a subdirectory of the extension may be set using \a $subdirectory.
    */
    function expandedPathList( $extensionList, $subdirectory = false )
    {
        $pathList = array();
        $extensionBase = eZExtension::baseDirectory();
        foreach ( $extensionList as $extensionName )
        {
            $path = $extensionBase . '/' . $extensionName;
            if ( $subdirectory )
                $path .= '/' . $subdirectory;
            $pathList[] = $path;
        }
        return $pathList;
    }

    /*!
     \static
     This is help function for searching for extension code. It will read ini variables
     defined in \a $parameters, search trough the specified directories for specific files
     and set the result in \a $out.

     The \a $parameters parameter must contain the following entries.
     - ini-name - The name of the ini file which has the settings, must include the .ini suffix.
     - repository-group - The INI group which has the basic repository settings.
     - repository-variable - The INI variable which has the basic repository settings.
     - extension-group - The INI group which has the extension settings.
     - extension-variable - The INI variable which has the extension settings.
     - subdir - A subdir which will be appended to all repositories searched for, can be left out.
     - extension-subdir - A subdir which will be appended to all extension repositories searched for, can be left out.
     - suffix-name - A suffix which will be appended after the file searched for.
     - type-directory - Whether the type has a directory for it's file or not. Default is true.
     - type - The type to look for, it will try to find a file named repository/subdir/type/type-suffix or
              if type-directory is false repository/subdir/type-suffix.
              If type is not specified the type-group and typ-variable may be used for fetching the current type.
     - type-group - The INI group which has the type setting.
     - type-variable - The INI variable which has the type setting.
     - alias-group - The INI group which defines type aliases, see below.
     - alias-variable - The INI variable which defines type aliases.

     Type aliases allows overriding a specific type to use another type handler,
     this is useful when extensions want to take control of some specific types
     or you want multiple names (aliases) for one type.

     On success the \a $out parameter will contain:
     - type - The current type used.
     - original-type - The original type, if aliasing was used it may differ from type.
     - found-file-dir - The directory where the type was found.
     - found-file-path - The full path to the type.
     - found-file-name - The filename of the type.

     \return true if the extension type was found.
    */
    function findExtensionType( $parameters, &$out )
    {
        $iniName = $parameters['ini-name'];
        $repositoryGroup = $parameters['repository-group'];
        $repositoryVariable = $parameters['repository-variable'];
        $extensionGroup = $parameters['extension-group'];
        $extensionVariable = $parameters['extension-variable'];
        $subdir = false;
        if ( isset( $parameters['subdir'] ) )
            $subdir = $parameters['subdir'];
        $extensionSubdir = false;
        if ( isset( $parameters['extension-subdir'] ) )
            $extensionSubdir = $parameters['extension-subdir'];
        $typeDirectory = true;
        if ( isset( $parameters['type-directory'] ) )
            $typeDirectory = $parameters['type-directory'];
        $suffixName = $parameters['suffix-name'];
        $ini =& eZINI::instance( $iniName );
        if ( isset( $parameters['type'] ) )
            $originalType = $parameters['type'];
        else if ( isset( $parameters['type-group'] ) and
                  isset( $parameters['type-variable'] ) )
            $originalType = $ini->variable( $parameters['type-group'], $parameters['type-variable'] );
        else
            return false;
        $type = $originalType;
        if ( isset( $parameters['alias-group'] ) and
             isset( $parameters['alias-variable'] ) )
        {
            if ( $ini->hasVariable( $parameters['alias-group'], $parameters['alias-variable'] ) )
            {
                $aliasMap = $ini->variable( $parameters['alias-group'], $parameters['alias-variable'] );
                if ( isset( $aliasMap[$type] ) )
                    $type = $aliasMap[$type];
            }
        }

        $baseDirectory = eZExtension::baseDirectory();
        $repositoryDirectoryList = array();
        $repositoryList = $ini->variable( $repositoryGroup, $repositoryVariable );
        $extensionDirectories = $ini->variable( $extensionGroup, $extensionVariable );
        foreach ( $repositoryList as $repository )
        {
            $repositoryDirectory = $repository;
            if ( $subdir != '' )
                $repositoryDirectory .= '/' . $subdir;
            $repositoryDirectoryList[] = $repositoryDirectory;
        }
        foreach ( $extensionDirectories as $extensionDirectory )
        {
            $extensionPath = $baseDirectory . '/' . $extensionDirectory;
            if ( $extensionSubdir != '' )
                $extensionPath .= '/' . $extensionSubdir;
            if ( file_exists( $extensionPath ) )
            {
                $repositoryDirectoryList[] = $extensionPath;
            }
            else if ( $extensionSubdir )
            {
                eZDebug::writeWarning( "Extension '$extensionDirectory' does not have the subdirectory $extensionSubdir, looked for directory '" . $extensionPath . "'" );
            }
        }
        $foundType = false;
        foreach ( $repositoryDirectoryList as $repositoryDirectory )
        {
            $fileDir = $repositoryDirectory;
            if ( $typeDirectory )
                $fileDir .= "/$type";
            $fileName = $type . $suffixName;
            $filePath = $fileDir . '/' . $fileName;
            if ( file_exists( $filePath ) )
            {
                $foundType = true;
                break;
            }
        }
        $out['repository-directory-list'] = $repositoryDirectoryList;
        if ( $foundType )
        {
            $out['type'] = $type;
            $out['original-type'] = $originalType;
            $out['found-file-dir'] = $fileDir;
            $out['found-file-path'] = $filePath;
            $out['found-file-name'] = $fileName;
        }
        $out['found-type'] = $foundType;
        return $foundType;
    }

}

function extension_path( $extension, $withWWWDir = false, $withHost = false, $withProtocol = false )
{
    $base = eZExtension::baseDirectory();
    $path = '';
    if ( $withProtocol )
    {
        if ( is_string( $withProtocol ) )
            $path .= $withProtocol . ':';
        else
            $path .= 'http:';
    }
    if ( $withHost )
    {
        $path .= '//';
        if ( is_string( $withHost ) )
            $path .= $withHost;
        else
            $path .= eZSys::hostname();
    }
    if ( $withWWWDir )
        $path .= eZSys::wwwDir();

    if ( $withWWWDir )
        $path .= '/' . $base . '/' . $extension;
    else
        $path .= $base . '/' . $extension;
    return $path;
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

/*!
*/
function lib_class( $libName, $name )
{
    $name = strtolower( $name );
    $include = "lib/$libName/classes/$name.php";
    return include_once( $include );
}

/*!
*/
function kernel_class( $name )
{
    $name = strtolower( $name );
    $include = "kernel/classes/$name.php";
    return include_once( $include );
}

?>
