<?php
//
// Definition of eZSys class
//
// Created on: <01-Mar-2002 13:48:53 amos>
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
// Portions are modifications on patches by Andreas B�ckler and Francis Nart
//

/*!
  \class eZSys ezsys.php
  \ingroup eZUtils
  \brief Easy access to various system settings

  The system is checked to see whether a virtualhost-less setup is used
  and sets the appropriate variables which can be fetched with
  siteDir(), wwwDir() and indexFile().
  It also detects file and enviroment separators, fetch them with
  fileSeparator() and envSeparator().

  Example:
\code
// Run the init in the index file
eZSys::init( eZINI::instance() );
print( eZSys::indexFile() );
print( eZSys::wwwDir() );
\endcode
*/

class eZSys
{
    const DEBUG_INTERNALS = false;

    /*!
     Initializes the object with settings taken from the current script run.
    */
    function eZSys()
    {
        $this->Attributes = array( "magickQuotes" => true,
                                   "hostname" => true );
        // Determine OS specific settings
        $uname = php_uname();
        if ( substr( $uname, 0, 7 ) == "Windows" )
        {
            $this->OSType = "win32";
            $this->OS = "windows";
            $this->FileSystemType = "win32";
            $this->FileSeparator = "\\";
            $this->LineSeparator= "\r\n";
            $this->EnvSeparator = ";";
            $this->ShellEscapeCharacter = '"';
            $this->BackupFilename = '.bak';
        }
        else if ( substr( $uname, 0, 3 ) == "Mac" )
        {
            $this->OSType = "mac";
            $this->OS = "mac";
            $this->FileSystemType = "unix";
            $this->FileSeparator = "/";
            $this->LineSeparator= "\r";
            $this->EnvSeparator = ":";
            $this->ShellEscapeCharacter = "'";
            $this->BackupFilename = '~';
        }
        else
        {
            $this->OSType = "unix";
            if ( strtolower( substr( $uname, 0, 5 ) ) == 'linux' )
            {
                $this->OS = 'linux';
            }
            else if ( strtolower( substr( $uname, 0, 0 ) ) == 'freebsd' )
            {
                $this->OS = 'freebsd';
            }
            else
            {
                $this->OS = false;
            }
            $this->FileSystemType = "unix";
            $this->FileSeparator = "/";
            $this->LineSeparator= "\n";
            $this->EnvSeparator = ":";
            $this->ShellEscapeCharacter = "'";
            $this->BackupFilename = '~';
        }

        $magicQuote = get_magic_quotes_gpc();

        if ( $magicQuote == 1 )
        {
            eZSys::removeMagicQuotes();
        }

        $this->AccessPath = array();
    }

    function removeMagicQuotes()
    {
        $globalVariables = array( '_SERVER', '_ENV' );
        foreach ( $globalVariables as $globalVariable )
        {
            foreach ( array_keys( $GLOBALS[$globalVariable] ) as $key )
            {
                if ( !is_array( $GLOBALS[$globalVariable][$key] ) )
                {
                    $GLOBALS[$globalVariable][$key] = stripslashes( $GLOBALS[$globalVariable][$key] );
                }
            }
        }
    }

    /*!
     \static
     \return the os type, either \c "win32", \c "unix" or \c "mac"
    */
    static function osType()
    {
        return eZSys::instance()->OSType;
    }

    /*!
     \static
     \return the name of the specific os or \c false if it could not be determined.
     Currently detects:
     - windows (win32)
     - mac (mac)
     - linux (unix)
     - freebsd (unix)
    */
    static function osName()
    {
        return eZSys::instance()->OS;
    }

    /*!
     \static
     \return the filesystem type, either \c "win32" or \c "unix"
    */
    static function filesystemType()
    {
        return eZSys::instance()->FileSystemType;
    }

    /*!
     Returns the string which is used for file separators on the current OS (server).
     \static
    */
    static function fileSeparator()
    {
        return eZSys::instance()->FileSeparator;
    }

    /*!
     \static
     \return the PHP version as text.
     \note Calls phpversion().
    */
    static function phpVersionText()
    {
        return phpversion();
    }

    /*!
     \static
     \return the PHP version as an array with the version elements.
     \example
     array( 4, 3, 4 )
     \endexample
    */
    static function phpVersion()
    {
        $text = eZSys::phpVersionText();
        $elements = explode( '.', $text );
        return $elements;
    }

    /*!
     \return \c true if the PHP version is equal or higher than \a $requiredVersion.
     \param $requiredVersion must be an array with version number.

     \code
     eZSys::isPHPVersionSufficient( array( 4, 1, 0 ) );
     \endcode
    */
    static function isPHPVersionSufficient( $requiredVersion )
    {
        if ( !is_array( $requiredVersion ) )
            return false;
        $phpVersion = eZSys::phpVersion();
        $len = min( count( $phpVersion ), count( $requiredVersion ) );
         for ( $i = 0; $i < $len; ++$i )
        {
            if ( $phpVersion[$i] > $requiredVersion[$i] )
                return true;
            if ( $phpVersion[$i] < $requiredVersion[$i] )
                return false;
        }
        return true;
    }

    /*!
     \static
     Determins if the script got executed over the web or the shell/commandoline.
    */
    static function isShellExecution()
    {
        $sapiType = php_sapi_name();

        if ( $sapiType == 'cli' )
            return true;

        // For CGI we have to check, if the script has been executed over shell.
        // Currently it looks like the HTTP_HOST variable is the most reasonable to check.
        if ( substr( $sapiType, 0, 3 ) == 'cgi' )
        {
            if ( !eZSys::serverVariable( 'HTTP_HOST', true ) )
                return true;
            else
                return false;
        }
        return false;
    }

    /*!
     \static
     Escape a string to be used as a shell argument and return it.
    */
    static function escapeShellArgument( $argument )
    {
        $escapeChar = eZSys::instance()->ShellEscapeCharacter;
        $argument = str_replace( "\\", "\\\\", $argument );
        $argument = str_replace( $escapeChar, "\\" . $escapeChar, $argument );
        $argument = $escapeChar . $argument . $escapeChar;
        return $argument;
    }

    /*!
     \static
     Replaces % elements in the argument text \a $argumentText using the replace list \a $replaceList.
     It will also properly escape the argument.
     \sa splitArgumentIntoElements, mergeArgumentElements
    */
    static function createShellArgument( $argumentText, $replaceList )
    {
        $instance = eZSys::instance();
        $elements = $instance->splitArgumentIntoElements( $argumentText );
        $replacedElements = array();
        foreach ( $elements as $element )
        {
            if ( is_string( $element ) )
            {
                $replacedElements[] = strtr( $element, $replaceList );
                continue;
            }
            $replacedElements[] = $element;
        }
        $text = $instance->mergeArgumentElements( $replacedElements );
        return $text;
    }

    /*!
     \static
     Splits the argument text into argument array elements.
     It will split text on spaces and set them as strings in the array,
     spaces will be counted and inserted as integers with the space count.
     Text placed in quotes will also be parsed, this allows for spaces in the text.
     \code
     $list = splitArgumentIntoElements( "-geometry 100x100" );

     var_dump( $list ); // will give: array( "-geometry", 1, "100x100" );
     \endcode

     You can then easily modify the elements separately and create the argument text with mergeArgumentElements().
    */
    static function splitArgumentIntoElements( $argumentText )
    {
        $argumentElements = array();
        $pos = 0;

        while ( $pos < strlen( $argumentText ) )
        {
            if ( $argumentText[$pos] == '"' || $argumentText[$pos] == "'" )
            {
                $quoteStartPos = $pos + 1;
                $quoteEndPos = $pos + 1;
                while ( $quoteEndPos < strlen( $argumentText ) )
                {
                    $tmpPos = strpos( $argumentText, $argumentText[$pos], $quoteEndPos );
                    if ( $tmpPos !== false and
                         $argumentText[$tmpPos - 1] != "\\" );
                    {
                        $quoteEndPos = $tmpPos;
                        break;
                    }
                    if ( $tmpPos === false )
                    {
                        $quoteEndPos = strlen( $argumentText );
                        break;
                    }
                    $quoteEndPos = $tmpPos + 1;
                }
                $argumentElements[] = substr( $argumentText, $quoteStartPos, $quoteEndPos - $quoteStartPos );
                $pos = $quoteEndPos + 1;
            }
            else if ( $argumentText[$pos] == ' ' )
            {
                $spacePos = $pos;
                $spaceEndPos = $pos;
                while ( $spaceEndPos < strlen( $argumentText ) )
                {
                    if ( $argumentText[$spaceEndPos] != ' ' )
                        break;
                    ++$spaceEndPos;
                }
                $spaceText = substr( $argumentText, $spacePos, $spaceEndPos - $spacePos );
                $spaceCount = strlen( $spaceText );
                if ( $spaceCount > 0 )
                    $argumentElements[] = $spaceCount;
                $pos = $spaceEndPos;
            }
            else
            {
                $spacePos = strpos( $argumentText, ' ', $pos );
                if ( $spacePos !== false )
                {
                    $argumentElements[] = substr( $argumentText, $pos, $spacePos - $pos );
                    $spaceEndPos = $spacePos + 1;
                    while ( $spaceEndPos < strlen( $argumentText ) )
                    {
                        if ( $argumentText[$spaceEndPos] != ' ' )
                            break;
                        ++$spaceEndPos;
                    }
                    $spaceText = substr( $argumentText, $spacePos, $spaceEndPos - $spacePos );
                    $spaceCount = strlen( $spaceText );
                    if ( $spaceCount > 0 )
                        $argumentElements[] = $spaceCount;
                    $pos = $spaceEndPos;
                }
                else
                {
                    $argumentElements[] = substr( $argumentText, $pos );
                    $pos = strlen( $argumentText );
                }
            }
        }
        return $argumentElements;
    }

    /*!
     \static
     Merges an argument list created by splitArgumentIntoElements() back into a text string.
     The argument text will be properly quoted.
    */
    static function mergeArgumentElements( $argumentElements )
    {
        $instance = eZSys::instance();
        $argumentText = '';
        foreach ( $argumentElements as $element )
        {
            if ( is_int( $element ) )
            {
                $argumentText .= str_repeat( ' ', $element );
            }
            else if ( is_string( $element ) )
            {
                $argumentText .= $instance->escapeShellArgument( $element );
            }
        }
        return $argumentText;
    }

    /*!
     \static
     \return the backup filename for this platform, returns .bak for win32 and ~ for unix and mac.
    */
    static function backupFilename()
    {
        return eZSys::instance()->BackupFilename;
    }

    /*!
     Returns the string which is used for line separators on the current OS (server).
     \static
    */
    static function lineSeparator()
    {
        return eZSys::instance()->LineSeparator;
    }

    /*!
     Returns the string which is used for enviroment separators on the current OS (server).
     \static
    */
    static function envSeparator()
    {
        return eZSys::instance()->EnvSeparator;
    }

    /*!
     \static
     \return the directory used for storing various kinds of files like cache, temporary files and logs.
    */
    static function varDirectory()
    {
        //include_once( 'lib/ezutils/classes/ezini.php' );
        $ini = eZINI::instance();
        //include_once( 'lib/ezfile/classes/ezdir.php' );
        return eZDir::path( array( $ini->variable( 'FileSettings', 'VarDir' ) ) );
    }

    /*!
     \static
     \ return the directory used for storing various kinds of files like images, audio and more.
     \Note This will include the varDirectory().
    */
    static function storageDirectory()
    {
        //include_once( 'lib/ezutils/classes/ezini.php' );
        //include_once( 'lib/ezfile/classes/ezdir.php' );
        $ini = eZINI::instance();
        $varDir = eZSys::varDirectory();
        $storageDir = $ini->variable( 'FileSettings', 'StorageDir' );
        return eZDir::path( array( $varDir, $storageDir ) );
    }

    /*!
     \static
     \return the directory used for storing cache files.
     \note This will include the varDirectory().
    */
    static function cacheDirectory()
    {
        //include_once( 'lib/ezutils/classes/ezini.php' );
        $ini = eZINI::instance();
        $cacheDir = $ini->variable( 'FileSettings', 'CacheDir' );

        //include_once( 'lib/ezfile/classes/ezdir.php' );
        if ( $cacheDir[0] == "/" )
        {
            return eZDir::path( array( $cacheDir ) );
        }
        else
        {
            return eZDir::path( array( eZSys::varDirectory(), $cacheDir ) );
        }
    }

    /*!
     The absolute path to the root directory.
     \static
    */
    static function rootDir()
    {
        $instance = eZSys::instance();
        if ( !$instance->RootDir )
        {
            $cwd  = getcwd();
            $self  = $instance->serverVariable( 'PHP_SELF' );
            if ( file_exists( $cwd . $instance->FileSeparator . $self ) or
                 file_exists( $cwd . $instance->FileSeparator . $instance->IndexFile ) )
            {
                $instance->RootDir = $cwd;
            }
            else
            {
                $instance->RootDir = null;
            }
        }
        return $instance->RootDir;
    }

    /*!
     The path to where all the code resides.
     \static
    */
    static function siteDir()
    {
        return eZSys::instance()->SiteDir;
    }

    /*!
     The relative directory path of the vhless setup.
     \static
    */
    static function wwwDir()
    {
        return eZSys::instance()->WWWDir;
    }

    /*!
     The filepath for the index file.
     \static
    */
    static function indexDir( $withAccessList = true )
    {
        $instance = eZSys::instance();
        return $instance->wwwDir() . $instance->indexFile( $withAccessList );
    }

    /*!
     The filepath for the index file with the access path appended.
     \static
     \sa indexFileName
    */
    static function indexFile( $withAccessList = true )
    {
        $instance = eZSys::instance();
        $text = $instance->IndexFile;

        $ini = eZINI::instance();
        if ( $ini->variable( 'SiteAccessSettings', 'RemoveSiteAccessIfDefaultAccess' ) == 'enabled' )
        {
            $defaultAccess = $ini->variable( 'SiteSettings', 'DefaultAccess' );
            if ( count( $instance->AccessPath ) > 0 and $instance->AccessPath[0] == $defaultAccess ){
                $accessPathArray = $instance->AccessPath;
                array_shift( $accessPathArray ); //remove first element from accessPath as this is siteaccess name.
                $accessPath = implode( '/', $accessPathArray );
                $text .= '/' . $accessPath;
                return $text;
            }
        }

        if ( $withAccessList and count( $instance->AccessPath ) > 0 )
        {
            $accessPath = implode( '/', $instance->AccessPath );

            require_once( 'access.php' );
            if ( isset( $GLOBALS['eZCurrentAccess'] ) &&
                 isset( $GLOBALS['eZCurrentAccess']['type'] ) &&
                 $GLOBALS['eZCurrentAccess']['type'] == EZ_ACCESS_TYPE_URI &&
                 isset( $GLOBALS['eZCurrentAccess']['access_alias'] ) )
            {
                $accessPathArray = $instance->AccessPath;
                $accessPathArray[0] = $GLOBALS['eZCurrentAccess']['access_alias'];
                $accessPath = implode( '/', $accessPathArray );
            }
            $text .= '/' . $accessPath;
        }
        return $text;
    }

    /*!
     The filepath for the index file.
     \static
    */
    static function indexFileName()
    {
        return eZSys::instance()->IndexFile;
    }

    /*!
     Returns the current hostname.
     \static
    */
    static function hostname()
    {
        $retVal = eZSys::serverVariable( 'HTTP_HOST' );
        return  $retVal;
    }

    /*!
     Determines if SSL is enabled and protocol HTTPS is used.
     \return true if current access mode is HTTPS.
     \static
    */
    static function isSSLNow()
    {
        $ini = eZINI::instance();
        $sslPort = $ini->variable( 'SiteSettings', 'SSLPort' );
        if ( !$sslPort )
            $sslPort = eZSSLZone::DEFAULT_SSL_PORT;
        // $nowSSl is true if current access mode is HTTPS.
        $nowSSL = ( eZSys::serverPort() == $sslPort );

        //Check if this request might be driven through a ssl proxy
        if ( isset ( $_SERVER['HTTP_X_FORWARDED_SERVER'] ) and !$nowSSL )
        {
            $sslProxyServerName = $ini->variable( 'SiteSettings', 'SSLProxyServerName' );
            $nowSSL = ( $sslProxyServerName == $_SERVER['HTTP_X_FORWARDED_SERVER'] );
        }
        return $nowSSL;
    }

    /*!
     \static
    */
    static function serverProtocol()
    {
        if ( eZSys::isSSLNow() )
            return 'https';
        else
            return 'http';
    }

    /*!
     Returns the server URL. (protocol with hostname and port)
     \static
    */
    static function serverURL()
    {
        $host = eZSys::hostname();
        $url = '';
        if ( $host )
        {
            if ( eZSys::isSSLNow() )
            {
                // https case
                $host = preg_replace( '/:\d+$/', '', $host );

                $ini = eZINI::instance();
                $sslPort = $ini->variable( 'SiteSettings', 'SSLPort' );

                $sslPortString = ( $sslPort == eZSSLZone::DEFAULT_SSL_PORT ) ? '' : ":$sslPort";
                $url = "https://" . $host  . $sslPortString;
            }
            else
            {
                $url = "http://" . $host;
            }
        }
        return $url;
    }
    /*!
      \static
      \return the port of the server.
    */
    static function serverPort()
    {
        if ( empty( $GLOBALS['eZSysServerPort'] ) )
        {
            $hostname = eZSys::hostname();
            if ( preg_match( "/.*:([0-9]+)/", $hostname, $regs ) )
            {
                $port = $regs[1];
            }
            else
            {
                $port = eZSys::serverVariable( 'SERVER_PORT' );
            }
            $GLOBALS['eZSysServerPort'] = $port;
        }
        return $GLOBALS['eZSysServerPort'];
    }

    /*!
     Returns true if magick quotes is enabled.
     \static
    */
    static function magickQuotes()
    {
        return null;
    }

    /*!
     \return the variable named \a $variableName in the global \c $_SERVER variable.
             If the variable is not present an error is shown and \c null is returned.
    */
    static function serverVariable( $variableName, $quiet = false )
    {
        if ( !isset( $_SERVER[$variableName] ) )
        {
            if ( !$quiet )
            {
                eZDebug::writeError( "Server variable '$variableName' does not exist", 'eZSys::serverVariable' );
            }
            $retVal = null;
            return $retVal;
        }
        return $_SERVER[$variableName];
    }

    /*!
     Sets the server variable named \a $variableName to \a $variableValue.
     \note Variables are only set for the current page view.
    */
    static function setServerVariable( $variableName, $variableValue )
    {
        $_SERVER;
        $_SERVER[$variableName] = $variableValue;
    }

    /*!
     \return the path string for the server.
    */
    static function path( $quiet = false )
    {
        return eZSys::serverVariable( 'PATH', $quiet );
    }

    /*!
     \return the variable named \a $variableName in the global \c $_ENV variable.
             If the variable is not present an error is shown and \c null is returned.
    */
    static function &environmentVariable( $variableName, $quiet = false )
    {
        $_ENV;
        if ( !isset( $_ENV[$variableName] ) )
        {
            if ( !$quiet )
            {
                eZDebug::writeError( "Environment variable '$variableName' does not exist", 'eZSys::environmentVariable' );
            }
            $retValue = null;
            return $retValue;
        }
        return $_ENV[$variableName];
    }

    /*!
     Sets the environment variable named \a $variableName to \a $variableValue.
     \note Variables are only set for the current page view.
    */
    static function setEnvironmentVariable( $variableName, $variableValue )
    {
        $_ENV;
        $_ENV[$variableName] = $variableValue;
    }

    function attributes()
    {
        return array_merge( array( 'wwwdir',
                                   'sitedir',
                                   'indexfile',
                                   'indexdir' ),
                            array_keys( $this->Attributes ) );

    }

    /*!
     Return true if the attribute $attr is set. Available attributes are
     wwwdir, sitedir or indexfile
    */
    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    /*!
     Returns the attribute value for $attr or null if the attribute does not exist.
    */
    function attribute( $attr )
    {
        if ( isset( $this->Attributes[$attr] ) )
        {
            return $this->$attr();
        }
        else if ( $attr == 'wwwdir' )
        {
            return $this->wwwDir();
        }
        else if ( $attr == 'sitedir' )
        {
            return $this->siteDir();
        }
        else if ( $attr == 'indexfile' )
        {
            return $this->indexFile();
        }
        else if ( $attr == 'indexdir' )
        {
            return $this->indexDir();
        }

        eZDebug::writeError( "Attribute '$attr' does not exist", 'eZSys::attribute' );
        return null;
    }

    /*!
     \static
     Sets the access path which is appended to the index file.
     \sa indexFile
    */
    static function addAccessPath( $path )
    {
        $instance = eZSys::instance();
        if ( !is_array( $path ) )
            $path = array( $path );
        $instance->AccessPath = array_merge( $instance->AccessPath, $path );
    }

    /*!
     \static
     Empties the access path.
    */
    static function clearAccessPath()
    {
        eZSys::instance()->AccessPath = array();
    }

    /*!
     \static
     \return true if debugging of internals is enabled, this will display
     which server variables are read.
      Set the option with setIsDebugEnabled().
    */
    static function isDebugEnabled()
    {
        if ( !isset( $GLOBALS['eZSysDebugInternalsEnabled'] ) )
             $GLOBALS['eZSysDebugInternalsEnabled'] = eZSys::DEBUG_INTERNALS;
        return $GLOBALS['eZSysDebugInternalsEnabled'];
    }

    /*!
     \static
     Sets whether internal debugging is enabled or not.
    */
    static function setIsDebugEnabled( $debug )
    {
        $GLOBALS['eZSysDebugInternalsEnabled'] = $debug;
    }

    /*!
     Initializes some variables according to some global PHP values.
     This function should be called once in the index file with the parameters
     stated in the parameter list.
     \static
    */
    static function init( $def_index = "index.php", $force_VirtualHost = false )
    {
        $isCGI = ( substr( php_sapi_name(), 0, 3 ) == 'cgi' );

        $instance = eZSys::instance();

        if ( eZSys::isDebugEnabled() )
        {
            eZDebug::writeNotice( eZSys::serverVariable( 'PHP_SELF' ), 'PHP_SELF' );
            eZDebug::writeNotice( eZSys::serverVariable( 'SCRIPT_FILENAME' ), 'SCRIPT_FILENAME' );
            eZDebug::writeNotice( eZSys::serverVariable( 'DOCUMENT_ROOT' ), 'DOCUMENT_ROOT' );
            eZDebug::writeNotice( eZSys::serverVariable( 'REQUEST_URI' ), 'REQUEST_URI' );
            eZDebug::writeNotice( eZSys::serverVariable( 'QUERY_STRING' ), 'QUERY_STRING' );
            eZDebug::writeNotice( ini_get( 'include_path' ), 'include_path' );
        }

        $phpSelf = eZSys::serverVariable( 'PHP_SELF' );

        // Find out, where our files are.
        if ( ereg( "(.*/)([^\/]+\.php)$", eZSys::serverVariable( 'SCRIPT_FILENAME' ), $regs ) )
        {
            $siteDir = $regs[1];
            $index = "/" . $regs[2];
        }
        elseif ( ereg( "(.*/)([^\/]+\.php)/?", $phpSelf, $regs ) )
        {
            // Some people using CGI have their $_SERVER['SCRIPT_FILENAME'] not right... so we are trying this.
            $siteDir = eZSys::serverVariable( 'DOCUMENT_ROOT' ) . $regs[1];
            $index = "/" . $regs[2];
        }
        else
        {
            // Fallback... doesn't work with virtual-hosts, but better than nothing
            $siteDir = "./";
            $index = "/$def_index";
        }
        if ( $isCGI and !$force_VirtualHost )
        {
            $index .= '?';
        }

        // Setting the right include_path
        $includePath = ini_get( "include_path" );
        if ( trim( $includePath ) != "" )
        {
            $includePath = $includePath . $instance->envSeparator() . $siteDir;
        }
        else
        {
            $includePath = $siteDir;
        }
        ini_set( "include_path", $includePath );

        $scriptName = eZSys::serverVariable( 'SCRIPT_NAME' );
        // Get the webdir.

        $wwwDir = "";

        if ( $force_VirtualHost )
        {
            $wwwDir = "";
        }
        else
        {
            if ( ereg( "(.*)/([^\/]+\.php)$", $scriptName, $regs ) )
                $wwwDir = $regs[1];
            else if ( ereg( "(.*)/([^\/]+\.php)$", $phpSelf, $regs ) )
                $wwwDir = $regs[1];
        }

        if ( ! $isCGI || $force_VirtualHost )
        {
            $requestURI = eZSys::serverVariable( 'REQUEST_URI' );
        }
        else
        {
            $requestURI = eZSys::serverVariable( 'QUERY_STRING' );

            /* take out PHPSESSID, if url-encoded */
            if ( preg_match( "/(.*)&PHPSESSID=[^&]+(.*)/", $requestURI, $matches ) )
            {
                $requestURI = $matches[1].$matches[2];
            }
        }

        // Fallback... Finding the paths above failed, so $_SERVER['PHP_SELF'] is not set right.
        if ( $siteDir == "./" )
            $phpSelf = $requestURI;

        if ( ! $isCGI )
        {
            $def_index_reg = str_replace( ".", "\\.", $def_index );
            // Trick: Rewrite setup doesn't have index.php in $_SERVER['PHP_SELF'], so we don't want an $index
            if ( ! ereg( ".*$def_index_reg.*", $phpSelf ) || $force_VirtualHost )
            {
                $index = "";
            }
            else
            {
                if ( eZSys::isDebugEnabled() )
                {
                    eZDebug::writeNotice( "$wwwDir$index", '$wwwDir$index' );
                }
                // Get the right $_SERVER['REQUEST_URI'], when using nVH setup.
                if ( ereg( "^$wwwDir$index(.*)", $phpSelf, $req ) )
                {
                    if (! $req[1] )
                    {
                        if ( $phpSelf != "$wwwDir$index" and ereg( "^$wwwDir(.*)", $requestURI, $req ) )
                        {
                            $requestURI = $req[1];
                            $index = '';
                        }
                        elseif ( $phpSelf == "$wwwDir$index" and ereg( "^$wwwDir$index(.*)", $requestURI, $req ) or
                                 ereg( "^$wwwDir(.*)", $requestURI, $req ) )
                        {
                            $requestURI = $req[1];
                        }
                    }
                    else
                    {
                        $requestURI = $req[1];
                    }
                }
            }
        }
        if ( $isCGI and $force_VirtualHost )
            $index = '';
        // Remove url parameters
        if ( $isCGI and !$force_VirtualHost )
        {
            $pattern = "(\/[^&]+)";
        }
        else
        {
            $pattern = "([^?]+)";
        }
        if ( ereg( $pattern, $requestURI, $regs ) )
        {
            $requestURI = $regs[1];
        }

        // Remove internal links
        if ( ereg( "([^#]+)", $requestURI, $regs ) )
        {
            $requestURI = $regs[1];
        }

        if ( !$isCGI )
        {
            $currentPath = substr( eZSys::serverVariable( 'SCRIPT_FILENAME' ), 0, -strlen( 'index.php' ) );
            if ( strpos( $currentPath, eZSys::serverVariable( 'DOCUMENT_ROOT' )  ) === 0 )
            {
                $prependRequest = substr( $currentPath, strlen( eZSys::serverVariable( 'DOCUMENT_ROOT' ) ) );

                if ( strpos( $requestURI, $prependRequest ) === 0 )
                {
                    $requestURI = substr( $requestURI, strlen( $prependRequest ) - 1 );
                    $wwwDir = substr( $prependRequest, 0, -1 );
                }
            }
        }

        $instance->AccessPath = array();
        $instance->SiteDir = $siteDir;
        $instance->WWWDir = $wwwDir;
        $instance->IndexFile = $index;
        $instance->RequestURI = $requestURI;

        if ( eZSys::isDebugEnabled() )
        {
            eZDebug::writeNotice( $instance->SiteDir, 'SiteDir' );
            eZDebug::writeNotice( $instance->WWWDir, 'WWWDir' );
            eZDebug::writeNotice( $instance->IndexFile, 'IndexFile' );
            eZDebug::writeNotice( eZSys::requestURI(), 'eZSys::requestURI()' );
        }

    }

    /*!
     \return the URI used for parsing modules, views and parameters, may differ from $_SERVER['REQUEST_URI'].
    */
    static function requestURI()
    {
        return eZSys::instance()->RequestURI;
    }

    /*!
     Returns the only legal instance of the eZSys class.
     \static
    */
    static function instance()
    {
        if ( empty( $GLOBALS['eZSysInstance'] ) )
        {
            $GLOBALS['eZSysInstance'] = new eZSys();
        }
        return $GLOBALS['eZSysInstance'];
    }

    /*!
     A wrapper for php's crc32 function.
     \return the crc32 polynomial as unsigned int
    */
    static function ezcrc32( $string )
    {
        //include_once( 'lib/ezutils/classes/ezini.php' );
        $ini = eZINI::instance();

        if ( $ini->variable( 'SiteSettings', '64bitCompatibilityMode' ) === 'enabled' )
            $checksum = sprintf( '%u', crc32( $string ) );
        else
            $checksum = crc32( $string );

        return $checksum;
    }

    /*!
     Returns the schema of the request.
     */
    static function protocolSchema()
    {
        $schema = '';
        if( preg_match( "#^([a-zA-Z]+)/.*$#", eZSys::serverVariable( 'SERVER_PROTOCOL' ), $schemaMatches ) )
        {
            $schema = strtolower( $schemaMatches[1] ) . '://';
        }

        return $schema;
    }

    /// The line separator used in files
    public $LineSeparator;
    /// The directory separator used for files
    public $FileSeparator;
    /// The list separator used for env variables
    public $EnvSeparator;
    /// The absolute path to the root directory.
    public $RootDir;
    /// The path to where all the code resides
    public $SiteDir;
    /// The access path of the current site view
    public $AccessPath;
    /// The relative directory path of the vhless setup
    public $WWWDir;
    /// The filepath for the index
    public $IndexFile;
    /// The uri which is used for parsing module/view information from, may differ from $_SERVER['REQUEST_URI']
    public $RequestURI;
    /// The type of filesystem, is either win32 or unix. This often used to determine os specific paths.
    public $FileSystemType;
    /// The character to be used in shell escaping, this character is OS specific
    public $ShellEscapeCharacter;
    public $OSType;
}

?>
