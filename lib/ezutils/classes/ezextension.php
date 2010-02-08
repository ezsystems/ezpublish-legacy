<?php
//
// Definition of eZExtension class
//
// Created on: <16-Mar-2002 14:23:45 amos>
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

/*! \file
*/

/*!
  \class eZExtension ezextension.php
  \brief The class eZExtension does

*/

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
    static function baseDirectory()
    {
        $ini = eZINI::instance();
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
    static function activeExtensions( $extensionType = false )
    {
        $ini = eZINI::instance();
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
    static function activateExtensions( $extensionType = false )
    {
        $extensionDirectory = eZExtension::baseDirectory();
        $activeExtensions = eZExtension::activeExtensions( $extensionType );
        $hasExtensions = false;
        $ini = eZINI::instance();
        foreach ( $activeExtensions as $activeExtension )
        {
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
            else if ( !file_exists( $extensionDirectory . '/' . $activeExtension ) )
            {
                eZDebug::writeWarning( "Extension '$activeExtension' does not exist, looked for directory '" . $extensionDirectory . '/' . $activeExtension . "'" );
            }
        }
        if ( $hasExtensions )
            $ini->loadCache();
    }

    /*!
     \static

     Prepend extension siteaccesses

     \param accessName siteaccess name ( default false )
    */
    static function prependExtensionSiteAccesses( $accessName = false, $ini = false, $globalDir = true, $identifier = false, $order = true )
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
    static function prependSiteAccess( $extension, $accessName = false, $ini = false, $globalDir = true, $identifier = false )
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
                $ini = eZINI::instance();
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
    static function expandedPathList( $extensionList, $subdirectory = false )
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
              If type is not specified the type-group and type-variable may be used for fetching the current type.
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
    static function findExtensionType( $parameters, &$out )
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
        $ini = eZINI::instance( $iniName );
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

    /*!
     \static
     Read extension information. Returns extension information array
     specified in feature request 9371. ( http://issues.ez.no/9371 )

     \param extension name

     \return Extension information array. null if extension is not found,
             or does not contain extension information.
    */
    static function extensionInfo( $extension )
    {
        $infoFileName = eZDir::path( array( eZExtension::baseDirectory(), $extension, 'ezinfo.php' ) );
        if ( file_exists( $infoFileName ) )
        {
            include_once( $infoFileName );
            $className = $extension . 'Info';
            if ( is_callable( array( $className, 'info' ) ) )
            {
                $result = call_user_func_array( array( $className, 'info' ), array() );
                if ( is_array( $result ) )
                {
                    return $result;
                }
            }
        }

        return null;
    }

    /*!
     \static
     eZExtension::nameFromPath( __FILE__ ) executed in any file of an extension
     can help you to find the path to additional resources
     \return Name of the extension a path belongs to.
     \param $path Path to check.
    */
    static function nameFromPath( $path )
    {
        $path = eZDir::cleanPath( $path );
        $base = eZExtension::baseDirectory() . '/';
        $base = preg_quote( $base, '/' );
        $pattern = '/'.$base.'([^\/]+)/';
        if ( preg_match( $pattern, $path, $matches ) )
            return $matches[1];
        else
            false;
    }

    /*!
     \static
     \return true if this path is related to some extension.
     \param $path Path to check.
     \note The root of an extension is considered to be in this path too.
    */
    static function isExtension( $path )
    {
        if ( eZExtension::nameFromPath( $path ) )
            return true;
        else
            return false;
    }

    /**
     * Returns the correct handler defined in $iniFile configuration file
     * A correct class name for the handler needs to be specified in the 
     * ini settings, and the class needs to be present for the autoload system.
     *
     * @static
     * @param object $options, and ezpExtensionOptions object
     * @return null|false|object Returns a valid handler object, null if setting did not exists and false if no handler was found
     */
    public static function getHandlerClass( ezpExtensionOptions $options )
    {
        $iniFile       = $options->iniFile;
        $iniSection    = $options->iniSection;
        $iniVariable   = $options->iniVariable;
        $handlerIndex  = $options->handlerIndex;
        $callMethod    = $options->callMethod;
        $handlerParams = $options->handlerParams;
        $aliasSection  = $options->aliasSection;
        $aliasVariable = $options->aliasVariable;

        $ini = eZINI::instance( $iniFile );

        if ( !$ini->hasVariable( $iniSection, $iniVariable ) )
        {
            eZDebug::writeError( 'Unable to find variable ' . $iniVariable . ' in section ' . $iniSection . ' in file ' . $iniFile, __METHOD__ );
            return null;
        }

        $handlers = $ini->variable( $iniSection, $iniVariable );

        if ( $handlerIndex !== null )
        {
            if ( is_array( $handlers ) && isset( $handlers[ $handlerIndex  ] ) )
                $handlers = $handlers[ $handlerIndex  ];
            else
                return null;
        }

        // prepend alias settings if defined
        if ( $aliasVariable !== null && is_string( $handlers ) )
        {
            if ( $aliasSection === null )
            {
                $aliasSection = $iniSection;
            }
            $aliasHandlers = $ini->variable( $aliasSection, $aliasVariable );
            if ( is_array( $aliasHandlers ) && isset( $aliasHandlers[ $handlers ] ) )
            {
                $handlers = array( $aliasHandlers[ $handlers ], $handlers );
            }
            else
            {
                $handlers = array( $handlers );
            }
        }
        else if ( !is_array( $handlers ) )
        {
            $handlers = array( $handlers );
        }

        foreach( $handlers as $handler )
        {
            // we rely on the autoload system here
            if ( class_exists( $handler ) )
            {
                // only use reflection if we have params to avoid exception on objects withouth constructor
                if ( $handlerParams !== null && is_array( $handlerParams ) && count( $handlerParams ) > 0 )
                {
                    $reflection = new ReflectionClass( $handler );
                    $object = $reflection->newInstanceArgs( $handlerParams );
                }
                else
                {
                    $object = new $handler();
                }
                // if callMethod is set, then call it so handler can decide if it is a valid handler
                if ( $callMethod !== null )
                {
                    if ( !is_callable( array( $object, $callMethod ) ) )
                    {
                        eZDebug::writeNotice( 'Method ' . $callMethod . ' is not callable on class ' . $handler . " as defined in setting $iniFile [$iniSection] $iniVariable", __METHOD__ );
                        continue;
                    }

                    if ( !call_user_func(array( $object, $callMethod ) ) )
                        continue;
                }
                return $object;
            }
            else
            {
                eZDebug::writeError( "Class '$handler' as defined in setting $iniFile [$iniSection] $iniVariable could not be autoloaded. Did you remember to run bin/php/ezpgenerateautoloads.php after you added extension(s)?", __METHOD__ );
            }
        }

        return false;
    }
}

?>
