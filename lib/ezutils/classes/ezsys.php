<?php
/**
 * File containing the eZSys class.
 *
 * Portions are modifications of patches by Andreas Böckler and Francis Nart
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/**
 * Easy access to various system settings
 *
 * The system is checked to see whether a virtualhost-less setup is used and
 * sets the appropriate variables which can be fetched with siteDir(), wwwDir()
 * and indexFile().
 * It also detects file and environment separators, fetch them with
 * fileSeparator() and envSeparator().
 *
 * <code>
 * // Run the init in the index file
 * eZSys::init( 'index.php', $ini->variable( 'SiteAccessSettings', 'ForceVirtualHost' ) === 'true' );
 * echo eZSys::indexFile();
 * echo eZSys::wwwDir();
 * </code>
 *
 * @package lib
 * @subpackage ezutils
 */
class eZSys
{
    /**
     * The line separator used in files, "\n" / "\n\r" / "\r"
     *
     * @var string
     */
    public $LineSeparator;

    /**
     * The directory separator used for files, '/' or '\'
     *
     * @var string
     */
    public $FileSeparator;

    /**
     * The list separator used for env variables (':' or ';')
     *
     * @var string
     */
    public $EnvSeparator;

    /**
     * The absolute path to the root directory.
     *
     * @var string
     */
    public $RootDir;

    /**
     * The system path to where all the code resides
     *
     * @var string
     */
    public $SiteDir;

    /**
     * The access path of the current site view, associated array of associated arrays.
     *
     * On first level key is 'siteaccess' and 'path' to distinguish between siteaccess
     * and general path. On second level you have (string)'name' and (array)'url',
     * where url is the path and name is the name of the source (used to match siteaccess
     * in {@link eZSys::indexFile()} for RemoveSiteAccessIfDefaultAccess matching) .
     *
     * @var array
     */
    protected $AccessPath;

    /**
     * The relative directory path of the vhless setup
     *
     * @var string
     */
    public $WWWDir;

    /**
     * The index file name (eg: 'index.php')
     *
     * @var string
     */
    public $IndexFile;

    /**
     * The uri which is used for parsing module/view information from, may differ from $_SERVER['REQUEST_URI']
     *
     * @var string
     */
    public $RequestURI;

    /**
     * The type of filesystem, is either win32 or unix. This often used to determine OS specific paths.
     *
     * @var string
     */
    public $FileSystemType;

    /**
     * The character to be used in shell escaping, this character is OS specific
     *
     * @var stringt
     */
    public $ShellEscapeCharacter;

    /**
     * The type of OS, is either win32, mac or unix.
     *
     * @var string
     */
    public $OSType;

    /**
     * Holds server variables as read automatically or provided by unit tests
     * Only used by init functionality as other calls will need to use live data direclty from globals.
     *
     * @var array
     */
    protected $Params;

    /**
     * Holds eZSys instance
     *
     * @var eZSys|null
     */
    protected static $instance = null;

    /**
     * Query string for the current request
     * In the form of "?param1=value1&param2=value2
     * 
     * @var string
     */
    protected $QueryString;

    /**
     * Initialize the object with settings taken from the current script run.
     *
     * @param array $serverParams For unit testing use, see first few lines for content
     */
    public function __construct( array $serverParams = array() )
    {
        $this->Params = array_merge( array( 'PHP_OS' => PHP_OS,
                                            'DIRECTORY_SEPARATOR' => DIRECTORY_SEPARATOR,
                                            'PATH_SEPARATOR' => PATH_SEPARATOR,
                                            '_SERVER' => $_SERVER, ),
                                     $serverParams );
        
        $this->Attributes = array( 'magickQuotes' => true,
                                   'hostname'     => true );
        $this->FileSeparator = $this->Params['DIRECTORY_SEPARATOR'];
        $this->EnvSeparator  = $this->Params['PATH_SEPARATOR'];

        // Determine OS specific settings
        if ( $this->Params['PHP_OS'] === 'WINNT' )
        {
            $this->OSType = "win32";
            $this->OS = "windows";
            $this->FileSystemType = "win32";
            $this->LineSeparator = "\r\n";
            $this->ShellEscapeCharacter = '"';
            $this->BackupFilename = '.bak';
        }
        else
        {
            $this->OSType = 'unix';
            if ( $this->Params['PHP_OS'] === 'Linux' )
            {
                $this->OS = 'linux';
            }
            else if ( $this->Params['PHP_OS'] === 'FreeBSD' )
            {
                $this->OS = 'freebsd';
            }
            else if ( $this->Params['PHP_OS'] === 'Darwin' )
            {
                $this->OS = 'darwin';
            }
            else
            {
                $this->OS = false;
            }
            $this->FileSystemType = "unix";
            $this->LineSeparator = "\n";
            $this->ShellEscapeCharacter = "'";
            $this->BackupFilename = '~';
        }

        if ( get_magic_quotes_gpc() == 1 )
        {
            self::removeMagicQuotes();
        }

        $this->AccessPath = array( 'siteaccess' => array( 'name' => '', 'url' => array() ),
                                   'path'       => array( 'name' => '', 'url' => array() ) );
    }

    /**
     * Removes magic quotes
     *
     * @deprecated Since 4.5, magic quotes setting has been deprecated in PHP 5.3
     *
     * @return void
     */
    public static function removeMagicQuotes()
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

    /**
     * Returns the OS type
     *
     * Possible values: win32, unix
     *
     * @return string
     */
    public static function osType()
    {
        return self::instance()->OSType;
    }

    /**
     * Returns the current OS name or false if it can not be determined.
     *
     * Possible values: windows, linux, freebsd, darwin
     *
     * @return string|bool
     */
    public static function osName()
    {
        return self::instance()->OS;
    }

    /**
     * Returns the filesystem type
     *
     * Possible values: win32, unix
     *
     * @return string
     */
    public static function filesystemType()
    {
        return self::instance()->FileSystemType;
    }

    /**
     * Returns the string used as the file separator on the current system
     *
     * @return string
     */
    public static function fileSeparator()
    {
        return self::instance()->FileSeparator;
    }

    /**
     * The PHP version as text.
     *
     * @deprecated Since 4.5, use PHP_VERSION
     *
     * @return string
     */
    public static function phpVersionText()
    {
        return PHP_VERSION;
    }

    /**
     * Returns the PHP version as an array with the version elements.
     *
     * @deprecated Since 4.5
     *
     * @return array
     */
    public static function phpVersion()
    {
        $elements = explode( '.', PHP_VERSION );
        return $elements;
    }

    /**
     * Checks if the given version is greater than or equal to the current PHP version
     *
     * Usage:
     * <code>
     * eZSys::isPHPVersionSufficient( array( 4, 1, 0 ) );
     * </code>
     *
     * @deprecated Since 4.5
     * @param array $requiredVersion Must be an array with version number
     * @return bool
    */
    static function isPHPVersionSufficient( $requiredVersion )
    {
        if ( !is_array( $requiredVersion ) )
            return false;
        $phpVersion = self::phpVersion();
        $len = min( count( $phpVersion ), count( $requiredVersion ) );

        for ( $i = 0; $i < $len; ++$i )
        {
            if ( (int) $phpVersion[$i] > (int) $requiredVersion[$i] )
                return true;
            if ( (int) $phpVersion[$i] < (int) $requiredVersion[$i] )
                return false;
        }
        
        return true;
    }

    /**
     * Determines if the current process has been started from the web or the shell
     *
     * @return bool
     */
    public static function isShellExecution()
    {
        $sapiType = php_sapi_name();

        if ( $sapiType == 'cli' )
            return true;

        // For CGI we have to check, if the script has been executed over shell.
        // Currently it looks like the HTTP_HOST variable is the most reasonable to check.
        if ( substr( $sapiType, 0, 3 ) == 'cgi' )
        {
            if ( !self::serverVariable( 'HTTP_HOST', true ) )
                return true;
            else
                return false;
        }
        return false;
    }

    /**
     * Returns an escaped string to be used as a shell argument
     *
     * @param string $argument
     * @return string
     */
    public static function escapeShellArgument( $argument )
    {
        $escapeChar = self::instance()->ShellEscapeCharacter;
        $argument = str_replace( "\\", "\\\\", $argument );
        if ( $escapeChar == "'" )
        {
            $argument = str_replace( $escapeChar, $escapeChar . "\\" . $escapeChar . $escapeChar, $argument );
        }
        else
        {
            $argument = str_replace( $escapeChar, "\\" . $escapeChar, $argument );
        }
        $argument = $escapeChar . $argument . $escapeChar;
        return $argument;
    }

    /**
     * Replaces % elements in $argumentText using $replaceList, and also
     * properly escape the argument
     *
     * @param string $argumentText
     * @param array $replaceList
     * @return string
     */
    public static function createShellArgument( $argumentText, array $replaceList )
    {
        $instance = self::instance();
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

    /**
     * Splits $argumentText on boundaries formed by one or more spaces and save
     * them into an array of separate arguments.
     *
     * The number of spaces between to arguments is inserted as an integer value
     * between two argument values.
     *
     * Example:
     * <code>
     * $list = splitArgumentIntoElements( "-geometry 100x100" );
     * var_dump( $list ); // Output: array( "-geometry", 1, "100x100" );
     * </code>
     *
     * You can then easily modify the elements separately and create the argument
     * text with eZSys::mergeArgumentElements()
     *
     * @param string $argumentText
     * @return array
     */
    public static function splitArgumentIntoElements( $argumentText )
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

                    if ( $tmpPos !== false && $argumentText[$tmpPos - 1] != "\\" )
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
                    {
                        break;
                    }
                    ++$spaceEndPos;
                }
                $spaceText = substr( $argumentText, $spacePos, $spaceEndPos - $spacePos );
                $spaceCount = strlen( $spaceText );
                if ( $spaceCount > 0 )
                {
                    $argumentElements[] = $spaceCount;
                }

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
                        {
                            break;
                        }
                        ++$spaceEndPos;
                    }

                    $spaceText = substr( $argumentText, $spacePos, $spaceEndPos - $spacePos );
                    $spaceCount = strlen( $spaceText );

                    if ( $spaceCount > 0 )
                    {
                        $argumentElements[] = $spaceCount;
                    }
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

    /**
     * Merges an argument list created by eZSys::splitArgumentIntoElements()
     * back into a text string
     *
     * @param array $argumentElements
     * @return string
     */
    public static function mergeArgumentElements( array $argumentElements )
    {
        $instance = self::instance();
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

    /**
     * Returns the backup filename for this platform
     *
     * Possible values: .bak (win32), ~ (unix, mac)
     *
     * @return string
     */
    public static function backupFilename()
    {
        return self::instance()->BackupFilename;
    }

    /**
     * Returns the string used as line separator on the current system
     *
     * @return string
     */
    public static function lineSeparator()
    {
        return self::instance()->LineSeparator;
    }

    /**
     * Returns the string used as environment separator on the current system
     *
     * @return string
     */
    public static function envSeparator()
    {
        return self::instance()->EnvSeparator;
    }

    /**
     * Returns the path of the current var directory
     *
     * @return string
     */
    public static function varDirectory()
    {
        $ini = eZINI::instance();
        return eZDir::path( array( $ini->variable( 'FileSettings', 'VarDir' ) ) );
    }
    
    /**
     * Returns the current storage directory
     *
     * @return string
     */
    public static function storageDirectory()
    {
        $ini = eZINI::instance();
        $varDir = self::varDirectory();
        $storageDir = $ini->variable( 'FileSettings', 'StorageDir' );
        return eZDir::path( array( $varDir, $storageDir ) );
    }

    /**
     * Returns the current cache directory.
     *
     * @return string
     */
    public static function cacheDirectory()
    {
        $ini = eZINI::instance();
        $cacheDir = $ini->variable( 'FileSettings', 'CacheDir' );

        if ( $cacheDir[0] == "/" )
        {
            return eZDir::path( array( $cacheDir ) );
        }
        else
        {
            return eZDir::path( array( self::varDirectory(), $cacheDir ) );
        }
    }

    /**
     * Returns the absolute path to the eZ Publish root directory
     *
     * @return string|null
     */
    public static function rootDir()
    {
        $instance = self::instance();
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

    /**
     * Returns the path to where all the code resides.
     *
     * @return string
     */
    public static function siteDir()
    {
        return self::instance()->SiteDir;
    }

    /**
     * Returns the relative directory path of the vhless setup.
     *
     * @return string
     */
    public static function wwwDir()
    {
        return self::instance()->WWWDir;
    }

    /**
     * Returns the filepath for the index file.
     *
     * @param bool $withAccessList
     * @return string
     */
    public static function indexDir( $withAccessList = true )
    {
        return self::wwwDir() . self::indexFile( $withAccessList );
    }

    /**
     * Returns the query string for the current request.
     * 
     * <code>
     * ?param1=value1&param2=value2
     * </code>
     *
     * @return string
     */
    public static function queryString()
    {
        return self::instance()->QueryString;
    }

    /**
     * Returns the filepath for the index file with the access path appended
     *
     * @param bool $withAccessPath
     * @return string
     */
    public static function indexFile( $withAccessPath = true )
    {
        $sys  = self::instance();
        $text = $sys->IndexFile;

        if ( $withAccessPath && ( isset( $sys->AccessPath['siteaccess']['url'][0] ) || isset( $sys->AccessPath['path']['url'][0] ) ) )
        {
            $ini = eZINI::instance();
            if ( isset( $sys->AccessPath['siteaccess']['url'][0] ) &&
                 $ini->variable( 'SiteAccessSettings', 'RemoveSiteAccessIfDefaultAccess' ) === 'enabled' )
            {
                $defaultAccess = $ini->variable( 'SiteSettings', 'DefaultAccess' );
                // 1st is proper match where code has used updated api as of 4.4, do not use siteaccess
                if ( $sys->AccessPath['siteaccess']['name'] === $defaultAccess )
                    $accessPath = implode( '/', $sys->AccessPath['path']['url'] );
                // 2nd is for compatability with older code that used eZSys api withouth defining scopes, shift default siteaccess path
                elseif ( $sys->AccessPath['siteaccess']['name'] === 'undefined' && $sys->AccessPath['siteaccess']['url'][0] === $defaultAccess )
                {
                    $accessPathArray = $sys->AccessPath;
                    array_shift( $accessPathArray['siteaccess']['url'] ); //remove default siteaccess
                    $accessPath = implode( '/', array_merge( $accessPathArray['siteaccess']['url'], $accessPathArray['path']['url'] ) );
                }
                // In case there is no default siteaccess match use full url
                else
                    $accessPath = implode( '/', array_merge( $sys->AccessPath['siteaccess']['url'], $sys->AccessPath['path']['url'] ) );
            }
            else
            {
                $accessPath = implode( '/', array_merge( $sys->AccessPath['siteaccess']['url'], $sys->AccessPath['path']['url'] ) );
            }

            $text .= '/' . $accessPath;

            // Make sure we never return just a single '/' in case where siteaccess was shifted
            if ( $text === '/' )
                $text = '';
        }
        return $text;
    }

    /**
     * Returns the filepath for the index file
     *
     * @return string
     */
    public static function indexFileName()
    {
        return self::instance()->IndexFile;
    }

    /**
     * Returns the current hostname.
     *
     * First tries to use X-Forward-Host before it goes on to use host in header, if none of them
     * exists fallback to use host part of site.ini\[SiteSettings]|SiteURL setting.
     *
     * @return string
    */
    public static function hostname()
    {
        $hostName = null;
        $forwardedHostsString = self::serverVariable( 'HTTP_X_FORWARDED_HOST', true );
        if ( $forwardedHostsString )
        {
            $forwardedHosts = explode( ',', $forwardedHostsString );
            $hostName = trim( $forwardedHosts[0] );
        }

        if ( !$hostName && self::serverVariable( 'HTTP_HOST' ) )
        {
            $hostName = self::serverVariable( 'HTTP_HOST' );
        }

        if ( !$hostName )
        {
            $siteUrl = eZINI::instance()->variable( 'SiteSettings', 'SiteURL' );
            $hostName = parse_url( "http://{$siteUrl}", PHP_URL_HOST );
        }

        return $hostName;
    }

    /**
     * Returns the client IP whether he's behind a proxy or not
     *
     * Use [HTTPHeaderSettings].ClientIpByCustomHTTPHeader in site.ini if you want
     * to use a custom http header such as X-Forwarded-For
     *
     * Note: X-Forwarded-For is transformed by PHP
     *       into $_SERVER['HTTP_X_FORWARDED_FOR]
     *
     * @return string
     */
    public static function clientIP()
    {
        $customHTTPHeader = eZINI::instance()->variable( 'HTTPHeaderSettings', 'ClientIpByCustomHTTPHeader' );
        if( $customHTTPHeader && $customHTTPHeader != 'false' )
        {
            // Transforms for instance, X-Forwarded-For into X_FORWARDED_FOR
            $phpHeader = 'HTTP_' . str_replace( '-', '_', strtoupper( $customHTTPHeader ) );
            $forwardedClientsString = eZSys::serverVariable( $phpHeader, true );

            if ( $forwardedClientsString )
            {
                // $forwardedClientsString (usually) contains a comma+space separated list of IPs
                // where the left-most being the farthest downstream client. All the others are proxy servers.
                // As X-Forwarded-For is not a standard header yet, we prefer to use a simple comma as the explode delimiter
                $forwardedClients = explode( ',', $forwardedClientsString );
                if( !empty( $forwardedClients ) )
                {
                    return trim( $forwardedClients[0] );
                }
            }

            // Fallback on $_SERVER['REMOTE_ADDR']
            eZDebug::writeWarning( "Could not get ip with ClientIpByCustomHTTPHeader={$customHTTPHeader}, fallback to using REMOTE_ADDR",
                                   __METHOD__ );
        }

        return self::serverVariable( 'REMOTE_ADDR', true );
    }

    /**
     * Determines if SSL is enabled and protocol HTTPS is used.
     *
     * @return bool
     */
    public static function isSSLNow()
    {
        $ini = eZINI::instance();
        $sslPort = $ini->variable( 'SiteSettings', 'SSLPort' );
        if ( !$sslPort )
            $sslPort = eZSSLZone::DEFAULT_SSL_PORT;
        // $nowSSl is true if current access mode is HTTPS.
        $nowSSL = ( self::serverPort() == $sslPort );

        if ( !$nowSSL )
        {
            // Check if this request might be driven through a ssl proxy
            if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) )
            {
                $nowSSL = ( $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' );
            }
            else if ( isset( $_SERVER['HTTP_X_FORWARDED_PORT'] ) )
            {
                $sslPort = $ini->variable( 'SiteSettings', 'SSLPort' );
                $nowSSL = ( $_SERVER['HTTP_X_FORWARDED_PORT'] == $sslPort );
            }
            else if ( isset( $_SERVER['HTTP_X_FORWARDED_SERVER'] ) )
            {
                $sslProxyServerName = $ini->variable( 'SiteSettings', 'SSLProxyServerName' );
                $nowSSL = ( $sslProxyServerName == $_SERVER['HTTP_X_FORWARDED_SERVER'] );
            }
        }
        return $nowSSL;
    }

    /**
     * Returns the current server protocol depending on if SSL is enabled or not.
     *
     * @return string
     */
    public static function serverProtocol()
    {
        if ( self::isSSLNow() )
            return 'https';
        else
            return 'http';
    }

    /**
     * Returns the server URL (protocol and hostname and port)
     *
     * @return string
     */
    public static function serverURL()
    {
        $host = self::hostname();
        $url = '';
        if ( $host )
        {
            if ( self::isSSLNow() )
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

    /**
     * Returns the server port or 80 as default if the server port can not
     * be retrieved from the hostname or the server variable 'SERVER_PORT'
     *
     * @return int
     */
    public static function serverPort()
    {
        if ( empty( $GLOBALS['eZSysServerPort'] ) )
        {
            $hostname = self::hostname();
            if ( preg_match( "/.*:([0-9]+)/", $hostname, $regs ) )
            {
                $port = (int) $regs[1];
            }
            else
            {
                $port = (int) self::serverVariable( 'SERVER_PORT' );
            }

            if ( !$port )
            {
                $port = 80;
            }

            $GLOBALS['eZSysServerPort'] = $port;
        }
        return $GLOBALS['eZSysServerPort'];
    }

    /**
     * Should return true when magick quotes are enabled, but instead return null.
     *
     * @deprecated since 4.5
     *
     * @return null
     */
    public static function magickQuotes()
    {
        return null;
    }

    /**
     * Returns the value of $_SERVER[$variableName] if it is set.
     *
     * If it isn't set, trigger an error message if $quiet is false
     *
     * @param string $variableName
     * @param bool $quiet
     * @return mixed|null
     */
    public static function serverVariable( $variableName, $quiet = false )
    {
        if ( !isset( $_SERVER[$variableName] ) )
        {
            if ( !$quiet )
            {
                eZDebug::writeError( "Server variable '$variableName' does not exist", __METHOD__ );
            }

            return null;
        }
        return $_SERVER[$variableName];
    }

    /**
     * Sets a server variable in the global array $_SERVER
     *
     * Note: Variables are only set for the current process/page view
     * @param string $variableName
     * @param mixed $variableValue
     * @return void
     */
    public static function setServerVariable( $variableName, $variableValue )
    {
        $_SERVER;
        $_SERVER[$variableName] = $variableValue;
    }

    /**
     * Returns the server's path string
     *
     * @param bool $quiet
     * @return mixed|null
     */
    public static function path( $quiet = false )
    {
        return self::serverVariable( 'PATH', $quiet );
    }

    /**
     * Returns an environment variable or null if it is not available
     *
     * If the variable is not available, trigger an error message
     *
     * @param string $variableName
     * @param bool $quiet
     * @return null|string
     */
    public static function environmentVariable( $variableName, $quiet = false )
    {
        if ( getenv($variableName) === false )
        {
            if ( !$quiet )
            {
                eZDebug::writeError( "Environment variable '$variableName' does not exist", __METHOD__ );
            }
            return null;
        }
        return getenv($variableName);
    }

    /**
     * Checks if an environment variable is available
     *
     * @param string $variableName
     * @return bool
     */
    public static function hasEnvironmentVariable( $variableName )
    {
        return getenv($variableName) !== false;
    }

    /**
     * Sets an environment variable for the current process/page view
     *
     * @param string $variableName
     * @param mixed $variableValue
     * @return void
     */
    public static function setEnvironmentVariable( $variableName, $variableValue )
    {
        putenv( "$variableName=$variableValue" );
    }

    /**
     * Make sure that certain attribute keys are available in $this->Attributes
     *
     * @return array
     */
    function attributes()
    {
        return array_merge( array( 'wwwdir',
                                   'sitedir',
                                   'indexfile',
                                   'indexdir',
                                   'querystring' ),
                            array_keys( $this->Attributes ) );

    }

    /**
     * Checks if the attribute $attr is set.
     *
     * @param string $attr
     * @return bool
     */
    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    /**
     * Returns the attribute value for $attr or null if the attribute does not exist
     *
     * @param string $attr
     * @return null|string
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
        else if ( $attr = 'querystring' )
        {
            return $this->queryString();
        }

        eZDebug::writeError( "Attribute '$attr' does not exist", __METHOD__ );
        return null;
    }

    /**
     * Appends the access path (parts of url that identifies siteaccess), used by {@link eZSys::indexFile()}
     * NOTE: Does not make sense to use for siteaccess, as you would want to clear current path and set new one
     *       normally, so preferably use {@link eZSys::setAccessPath()} in this case.
     *
     * @param array|string $path
     * @param string $name An identifer of the name of the path provided {@link $AccessPath}
     * @param bool $siteaccess Hints if path is siteaccess related or not, needed in case subsequesnt code suddenly
     *                         changes siteaccess and needs to clear siteaccess scope
     */
    static function addAccessPath( $path, $name = 'undefined', $siteaccess = true )
    {
        $instance = self::instance();
        if ( !is_array( $path ) )
            $path = array( $path );

        if ( $siteaccess )
        {
            $instance->AccessPath['siteaccess']['name'] = $name;
            if ( isset($instance->AccessPath['siteaccess']['url'][0]) )
                $instance->AccessPath['siteaccess']['url'] = array_merge( $instance->AccessPath['siteaccess']['url'], $path );
            else
                $instance->AccessPath['siteaccess']['url'] = $path;
        }
        else
        {
            $instance->AccessPath['path']['name'] = $name;
            if ( isset($instance->AccessPath['path']['url'][0]) )
                $instance->AccessPath['path']['url'] = array_merge( $instance->AccessPath['path']['url'], $path );
            else
                $instance->AccessPath['path']['url'] = $path;
        }
    }

    /**
     * Set access path (parts of url that identifies siteaccess), used by {@link eZSys::indexFile()}
     *
     * @param array $path
     * @param string $name An identifer of the name of the path provided {@link $AccessPath}
     * @param bool $siteaccess Hints if path is siteaccess related or not, needed in case subsequesnt code suddenly
     *                         changes siteaccess and needs to clear siteaccess scope
     */
    static function setAccessPath( array $path = array(), $name = 'undefined', $siteaccess = true  )
    {
        if ( $siteaccess )
            self::instance()->AccessPath['siteaccess'] = array( 'name' => $name, 'url' => $path );
        else
            self::instance()->AccessPath['path'] = array( 'name' => $name, 'url' => $path );
    }

    /**
     * Clears the access path, used by {@link eZSys::indexFile()}
     *
     * @param bool $siteaccess
     * @return void
     */
    static function clearAccessPath( $siteaccess = true )
    {
        if ( $siteaccess )
            self::instance()->AccessPath['siteaccess'] = array( 'name' => '', 'url' => array() );
        else
            self::instance()->AccessPath['path'] = array( 'name' => '', 'url' => array() );
    }

    /**
     * Magic function to get access readonly properties (protected)
     *
     * @param string $propertyName
     * @return mixed
     * @throws ezcBasePropertyNotFoundException
     */
    public function __get( $propertyName )
    {
        if ( $propertyName === 'AccessPath' )
            return $this->AccessPath;

        throw new ezcBasePropertyNotFoundException( $propertyName );
    }

    /**
     * Magic function to see if readonly properties (protected) exists
     *
     * @param string $propertyName Option name to check for.
     * @return bool Whether the option exists.
     * @ignore
     */
    public function __isset( $propertyName )
    {
        return $propertyName === 'AccessPath';
    }

    /**
     * Returns true if debugging of internals is enabled, this will display
     * which server variables are read.
     * Set the option with setIsDebugEnabled().
     *
     * @deprecated Since 4.5, not used
     * @return bool
     */
    public static function isDebugEnabled()
    {
    }

    /**
     * Sets whether internal debugging is enabled or not.
     *
     * @deprecated Since 4.5, has not effect anymore
     * @param bool $debug
     */
    public static function setIsDebugEnabled( $debug )
    {
    }

    /**
     * Initializes some variables according to some global PHP values.
     * This function should be called once in the index file with the parameters
     * stated in the parameter list.
     *
     * @param string $index The current index file, needed for virtual host mode detection.
     * @param bool $forceVirtualHost Virtual host mode is normally autodetected, but if not this can be forced
     *                               by setting this to true.
     */
    public static function init( $index = 'index.php', $forceVirtualHost = null )
    {
        $instance       = self::instance();
        $server         = $instance->Params['_SERVER'];
        $phpSelf        = $server['PHP_SELF'];
        $requestUri     = $server['REQUEST_URI'];
        $scriptFileName = $server['SCRIPT_FILENAME'];
        $siteDir        = rtrim( str_replace( $index, '', $scriptFileName ), '\/' ) . '/';
        $wwwDir         = '';
        $IndexFile      = '';
        $queryString    = '';

        // see if we can use phpSelf to determin wwwdir
        $tempwwwDir = self::getValidwwwDir( $phpSelf, $scriptFileName, $index );
        if ( $tempwwwDir !== null && $tempwwwDir !== false )
        {
            // Force virual host or Auto detect IIS vh mode & Apache .htaccess mode
            if ( $forceVirtualHost
              || ( isset( $server['IIS_WasUrlRewritten'] ) && $server['IIS_WasUrlRewritten'] )
              || ( isset( $server['REDIRECT_URL'] ) && isset( $server['REDIRECT_STATUS'] ) && $server['REDIRECT_STATUS'] == '200' ) )
            {
                if ( $tempwwwDir )
                {
                    $wwwDir = '/' . $tempwwwDir;
                    $wwwDirPos = strpos( $requestUri, $wwwDir );
                    if ( $wwwDirPos !== false )
                    {
                        $requestUri = substr( $requestUri, $wwwDirPos + strlen($wwwDir) );
                    }
                }
            }
            else // Non virtual host mode, use $tempwwwDir to figgure out paths
            {
                $indexDir = $index;
                if ( $tempwwwDir )
                {
                    $wwwDir  = '/' . $tempwwwDir;
                    $indexDir = $wwwDir . '/' . $indexDir;
                }
                $IndexFile = '/' . $index;

                // remove sub path from requestUri
                $indexDirPos = strpos( $requestUri, $indexDir );
                if ( $indexDirPos !== false )
                {
                    $requestUri = substr( $requestUri, $indexDirPos + strlen($indexDir) );
                }
                elseif ( $wwwDir )
                {
                    $wwwDirPos = strpos( $requestUri, $wwwDir );
                    if ( $wwwDirPos !== false )
                    {
                        $requestUri = substr( $requestUri, $wwwDirPos + strlen($wwwDir) );
                    }
                }
            }
        }

        // remove url and hash parameters
        if ( isset( $requestUri[1] ) && $requestUri !== '/'  )
        {
            $uriGetPos = strpos( $requestUri, '?' );
            if ( $uriGetPos !== false )
            {
                $queryString = substr( $requestUri, $uriGetPos );
                if ( $uriGetPos === 0 )
                    $requestUri = '';
                else
                    $requestUri = substr( $requestUri, 0, $uriGetPos );
            }

            $uriHashPos = strpos( $requestUri, '#' );
            if ( $uriHashPos === 0 )
                $requestUri = '';
            elseif ( $uriHashPos !== false )
                $requestUri = substr( $requestUri, 0, $uriHashPos );
        }

        // normalize slash use and url decode url if needed
        if ( $requestUri === '/' || $requestUri === '' )
        {
            $requestUri = '';
        }
        else
        {
            $requestUri = '/' . urldecode( trim( $requestUri, '/ ' ) );
        }

        $instance->AccessPath = array( 'siteaccess' => array( 'name' => '', 'url' => array() ),
                                       'path'       => array( 'name' => '', 'url' => array() ) );

        $instance->SiteDir    = $siteDir;
        $instance->WWWDir     = $wwwDir;
        $instance->IndexFile  = $IndexFile;
        $instance->RequestURI = $requestUri;
        $instance->QueryString = $queryString;
    }

    /**
     * Generate wwwdir from phpSelf if valid accoring to scriptFileName
     * and return null if invalid and false if there is no index in phpSelf
     *
     * @param string $phpSelf
     * @param string $scriptFileName
     * @param string $index
     * @return string|null|false String in form 'path/path2' if valid, null if not
     *                           and false if $index is not  part of phpself
     */
    protected static function getValidwwwDir( $phpSelf, $scriptFileName, $index )
    {
        if ( !isset( $phpSelf[1] ) || strpos( $phpSelf, $index ) === false )
            return false;

        // validate $index straight away
        if ( strpos( $scriptFileName, $index ) === false )
            return null;

        // optimize '/index.php' pattern
        if ( $phpSelf === "/{$index}" )
            return '';

        $phpSelfParts = explode( $index, $phpSelf );
        $validateDir = $phpSelfParts[0];
        // remove first path if home dir
        if ( $phpSelf[1] === '~' )
        {
            $uri = explode( '/', ltrim( $validateDir, '/' ) );
            array_shift( $uri );
            $validateDir = '/' . implode( '/', $uri );
        }

        // validate direclty with phpself part
        if ( strpos( $scriptFileName, $validateDir ) !== false )
            return trim( $phpSelfParts[0], '/' );

        // validate with windows path
        if ( strpos( $scriptFileName, str_replace( '/', '\\', $validateDir ) ) !== false )
            return trim( $phpSelfParts[0], '/' );

        return null;
    }

    /**
     * Returns the URI used for parsing modules, views and parameters
     *
     * May differ from $_SERVER['REQUEST_URI'].
     *
     * @return string
     */
    public static function requestURI()
    {
        return self::instance()->RequestURI;
    }

    /**
     * Returns a shared instance of the eZSys class
     *
     * @return eZSys
     */
    public static function instance()
    {
        if ( !self::$instance instanceof eZSys )
        {
            self::$instance = new eZSys();
        }
        return self::$instance;
    }

    /**
     * Sets eZSys instance or clears it if left undefined.
     *
     * @param eZSys $instance
     */
    public static function setInstance( eZSys $instance = null )
    {
        self::$instance = $instance;
    }

    /**
     * A wrapper for PHP's crc32 function. Returns the crc32 polynomial as unsigned int
     *
     * @param $string
     * @return int|string
     */
    public static function ezcrc32( $string )
    {
        $ini = eZINI::instance();

        if ( $ini->variable( 'SiteSettings', '64bitCompatibilityMode' ) === 'enabled' )
            $checksum = sprintf( '%u', crc32( $string ) );
        else
            $checksum = crc32( $string );

        return $checksum;
    }

    /**
     * Returns the schema of the request.
     *
     * @return string
     */
    public static function protocolSchema()
    {
        $schema = '';
        if( preg_match( "#^([a-zA-Z]+)/.*$#", self::serverVariable( 'SERVER_PROTOCOL' ), $schemaMatches ) )
        {
            $schema = strtolower( $schemaMatches[1] ) . '://';
        }

        return $schema;
    }

    /**
     * Wraps around the built-in glob() function to provide same functionality
     * for systems (e.g Solaris) that does not support GLOB_BRACE.
     *
     * @param string $pattern
     * @param int $flags
     * @return array
     */
    public static function globBrace( $pattern, $flags = 0 )
    {
        if ( defined( 'GLOB_BRACE' ) )
        {
            $flags = $flags | GLOB_BRACE;
            return glob( $pattern, $flags );
        }
        else
        {
            $result = array();
            $files = self::simulateGlobBrace( array( $pattern ) );
            foreach( $files as $file )
            {
                $globList = glob( $file, $flags );
                if ( is_array( $globList ) )
                {
                    $result = array_merge( $result, $globList );
                }
            }
            return $result;
        }
    }

    /**
     * Expands a list of filenames like GLOB_BRACE does.
     *
     * GLOB_BRACE is non POSIX and only available in GNU glibc. This is needed to
     * support operating systems like Solars.
     *
     * @param $filenames
     * @return array
     */
    protected static function simulateGlobBrace( $filenames )
    {
       $result = array();

       foreach ( $filenames as $filename )
       {
           if ( strpos( $filename, '{' ) === false )
           {
               $result[] = $filename;
               continue;
           }

           if ( preg_match( '/^(.*)\{(.*?)(?<!\\\\)\}(.*)$/', $filename, $match ) )
           {
               $variants = preg_split( '/(?<!\\\\),/', $match[2] );

               $newFilenames = array();
               foreach ( $variants as $variant )
               {
                   $newFilenames[] = $match[1] . $variant . $match[3];
               }

               $newFilenames = self::simulateGlobBrace( $newFilenames );
               $result = array_merge( $result, $newFilenames );
           }
           else
           {
               $result[] = $filename;
           }
       }

       return $result;
    }
}

?>