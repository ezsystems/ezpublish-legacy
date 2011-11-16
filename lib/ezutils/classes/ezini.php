<?php
/**
 * File containing the eZINI class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZINI ezini.php
  \ingroup eZUtils
  \brief Reads and writes .ini style configuration files

  The most common way of using it is.
  \code

  $ini = eZINI::instance( "site.ini" );

  // get a variable from the file.
  $iniVar = $ini->variable( "BlockName", "Variable" );

  \endcode

  The default ini file is site.ini but others can be passed to the instance() function
  among with some others. It will create one unique instance for each ini file and rootdir,
  this means that the next time instance() is used with the same parameters the same
  object will be returned and no new parsing is required.

  The class will by default try to create a cache file in var/cache/ini, however to change
  this behaviour the static setIsCacheEnabled() function can be used, or use the $useCache
  parameter in instance() for setting this for one object only.

  The class will also handle charset conversion using eZTextCodec, to turn this behaviour
  off use the static setIsTextCodecEnabled() function or set the $useTextCodec parameter
  in instance() for a per object basis setting.

  Normally the eZINI class will not give out much information about what it's doing,
  it's only when errors occur that you'll see this. To enable internal debugging use
  the static setIsDebugEnabled() function. The class will then give information about
  which files are load, if cache files are used and when cache files are written.
*/
class eZINI
{
    /**
     * Constant path to directory for configuration cache
     *
     * @var string
     */
    const CONFIG_CACHE_DIR = 'var/cache/ini/';

    /**
     * Constant integer to check against configuration cache format revision
     *
     * @var int
     */
    const CONFIG_CACHE_REV = 2;

    /**
     * Set EZP_INI_FILEMTIME_CHECK constant to false to improve performance by
     * not checking modified time on ini files. You can also set it to a string, the name
     * of a ini file you still want to check modified time on, best example would be to
     * set it to 'site.ini' to make the system still check that but not the rest.
     *
     * @var null|bool
     */
    static protected $checkFileMtime = null;

    /**
     * set EZP_INI_FILE_PERMISSION constant to the permissions you want saved
     * ini and cache files to have.
     *
     * @var null|int
     */
    static protected $filePermission = null;

    /**
     * Array of eZINI instances
     *
     * @var array(eZINI)
     */
    static protected $instances = array();

    /**
     * Contains whether INI cache is globally enabled.
     *
     * @var bool
     */
    static protected $cacheEnabled = true;

    /**
     * Contains whether internals debugging is enabled.
     *
     * @var bool
     */
    static protected $debugEnabled = false;

    /**
     * Contains whether textcodec conversion  is enabled.
     *
     * @var bool
     */
    static protected $textCodecEnabled = true;

    /**
     * Initialization of eZINI object
     *
     * Enter description here ...
     * @param string $fileName
     * @param string $rootDir
     * @param null|bool $useTextCodec
     * @param null|bool $useCache
     * @param null|bool $useLocalOverrides
     * @param bool $directAccess
     * @param bool $addArrayDefinition
     * @param bool $load @since 4.5 Lets you disable automatic loading of ini values in
     *                   cases where changes on instance will be done first.
     */
    function eZINI( $fileName = 'site.ini', $rootDir = '', $useTextCodec = null, $useCache = null, $useLocalOverrides = null, $directAccess = false, $addArrayDefinition = false, $load = true )
    {
        $this->Charset = 'utf8';
        if ( $fileName == '' )
            $fileName = 'site.ini';
        if ( $rootDir !== false && $rootDir == '' )
            $rootDir = 'settings';
        if ( $useCache === null )
            $useCache = self::isCacheEnabled();
        if ( eZINI::isNoCacheAdviced() )
        {
            $useCache = false;
        }
        if ( $useTextCodec === null )
            $useTextCodec = self::isTextCodecEnabled();

        $this->UseTextCodec = $useTextCodec;
        $this->Codec = null;
        $this->FileName = $fileName;
        $this->RootDir = $rootDir;
        $this->UseCache = $useCache;
        $this->DirectAccess = $directAccess;
        $this->UseLocalOverrides = $useLocalOverrides;
        $this->AddArrayDefinition = $addArrayDefinition;

        if ( self::$checkFileMtime === null )
        {
            if ( defined('EZP_INI_FILEMTIME_CHECK') )
                self::$checkFileMtime = EZP_INI_FILEMTIME_CHECK;
            else
                self::$checkFileMtime = true;
        }

        if ( self::$GlobalOverrideDirArray === null )
        {
            self::$GlobalOverrideDirArray = self::defaultOverrideDirs();
        }

        if ( $this->UseLocalOverrides == true )
        {
            $this->LocalOverrideDirArray = self::$GlobalOverrideDirArray;
        }

        if ( self::$filePermission === null )
        {
            if ( defined( 'EZP_INI_FILE_PERMISSION' ) )
                self::$filePermission = EZP_INI_FILE_PERMISSION;
            else
                self::$filePermission = 0666;
        }

        if ( $load )
            $this->load();
    }

    /*!
     \return the filename.
    */
    function filename()
    {
        return $this->FileName;
    }

    /**
     * Returns whether INI cache is enabled globally, by default it is true.
     *
     * @see setIsCacheEnabled().
     *
     * @return bool
     */
    static function isCacheEnabled()
    {
         return self::$cacheEnabled;
    }

    /*!
     \return true if cache is not adviced to be used.
     \note The no-cache-adviced flag might not be modified in time for site.ini and some other important files to be affected.
    */
    static function isNoCacheAdviced()
    {
        if ( !isset( $GLOBALS['eZSiteBasics'] ) )
            return false;
        $siteBasics = $GLOBALS['eZSiteBasics'];
        if ( !isset( $siteBasics['no-cache-adviced'] ) )
            return false;
        return $siteBasics['no-cache-adviced'];
    }

    /**
     * Sets whether caching is enabled for INI files or not. This setting is global
     * and can be overriden in the instance() function.
     *
     * @see isCacheEnabled().
     *
     * @param bool $enabled
     */
    static function setIsCacheEnabled( $enabled )
    {
        self::$cacheEnabled = (bool)$enabled;
    }

    /**
     * Returns whether debugging of internals is enabled.
     *
     * This will display which files are loaded an when cache files are created.
     *
     * @see setIsDebugEnabled()
     *
     * @return bool
     */
    static function isDebugEnabled()
    {
        return self::$debugEnabled;
    }

    /**
     * Sets whether internal debugging is enabled or not. This setting is global
     * and can be overriden in the instance() function.
     *
     * @see isDebugEnabled().
     *
     * @param bool $enabled
     */
    static function setIsDebugEnabled( $enabled )
    {
        self::$debugEnabled = (bool)$enabled;
    }

    /**
     * Returns whether textcodecs is to be used, this will use the eZTextCodec
     * class in the eZI18N library for text conversion.
     *
     * @see setIsTextCodecEnabled()
     *
     * @return bool
     */
    static function isTextCodecEnabled()
    {
        return self::$textCodecEnabled;
    }

    /**
     * Sets whether textcodec conversion is enabled or not.
     *
     * @see isTextCodecEnabled().
     *
     * @param bool $enabled
     */
    static function setIsTextCodecEnabled( $enabled )
    {
        self::$textCodecEnabled = (bool)$enabled;
    }

    /**
     * Check whether a specified parameter in a specified section is set in a specified file
     *
     * @deprecated Since 4.4
     * @param string fileName file name (optional)
     * @param string rootDir directory (optional)
     * @param string section section name
     * @param string parameter parameter name
     * @return bool True if the the parameter is set.
     */
    static function parameterSet( $fileName = 'site.ini', $rootDir = 'settings', &$section, &$parameter )
    {
        if ( !eZINI::exists( $fileName, $rootDir ) )
            return false;

        $iniInstance = eZINI::instance( $fileName, $rootDir, null, null, null, true );
        return $iniInstance->hasVariable( $section, $parameter );
    }

    /*!
     \static
     \return true if the INI file \a $fileName exists in the root dir \a $rootDir.
     $fileName defaults to site.ini and rootDir to settings.
    */
    static function exists( $fileName = "site.ini", $rootDir = "settings" )
    {
        if ( $fileName == "" )
            $fileName = "site.ini";
        if ( $rootDir == "" )
            $rootDir = "settings";
        if ( file_exists( $rootDir . '/' . $fileName ) )
            return true;
        else if ( file_exists( $rootDir . '/' . $fileName . '.append.php' ) )
            return true;
        else if ( file_exists( $rootDir . '/' . $fileName . '.append' ) )
            return true;
        return false;
    }

    /**
     * Tries to load the ini file specified in the constructor or instance() function.
     * If cache files should be used and a cache file is found it loads that instead.
     *
     * @param bool $reset Reset ini values on instance
     */
    public function load( $reset = true )
    {
        if ( $this->UseCache )
        {
            $this->loadCache( $reset );
        }
        else
        {
            $this->parse( false, false, $reset );
        }
    }

    /**
     * Tries to load the ini file placement specified in the constructor or instance() function.
     * If cache files should be used and a cache file is found it loads that instead.
     *
     * @param bool $reset Reset ini values on instance
     */
    public function loadPlacement( $reset = true )
    {
        if ( $this->UseCache )
        {
            $this->loadCache( $reset, true );
        }
        else
        {
            $this->parse( false, false, $reset, true );
        }
    }

    /*!
     \private
     Looks trough all known settings and override folders to find relevant INI files.
     The result is a list with expanded paths to the files.
     \return the expanded file list.
    */
    function findInputFiles( &$inputFiles, &$iniFile )
    {
        if ( $this->RootDir !== false )
            $iniFile = eZDir::path( array( $this->RootDir, $this->FileName ) );
        else
            $iniFile = eZDir::path( array( $this->FileName ) );

        $inputFiles = array();

        if ( $this->FileName === 'override.ini' )
        {
            eZExtension::prependExtensionSiteAccesses( false, $this, true, false, false );
        }

        if ( file_exists( $iniFile ) )
            $inputFiles[] = $iniFile;

        // try the same file name with '.append.php' replace with '.append'
        if ( strpos($iniFile, '.append.php') !== false && preg_match('#^(.+.append).php$#i', $iniFile, $matches ) && file_exists( $matches[1] ) )
            $inputFiles[] = $matches[1];

        if ( strpos($iniFile, '.php') === false && file_exists ( $iniFile . '.php' ) )
            $inputFiles[] = $iniFile . '.php';

        if ( $this->DirectAccess )
        {
            if ( file_exists ( $iniFile . '.append' ) )
            {
                // recursion eZDebug::writeStrict( "INI files with *.ini.append suffix is DEPRECATED, use *.ini or *.ini.append.php instead: $iniFile.append", __METHOD__ );
                $inputFiles[] = $iniFile . '.append';
            }

            if ( file_exists ( $iniFile . '.append.php' ) )
                $inputFiles[] = $iniFile . '.append.php';
        }
        else
        {
            $overrideDirs = $this->overrideDirs();
            $fileName = $this->FileName;
            $rootDir = $this->RootDir;
            foreach ( $overrideDirs as $overrideDirItem )
            {
                $overrideDir = $overrideDirItem[0];
                $isGlobal = $overrideDirItem[1];
                if ( $isGlobal )
                    $overrideFile = eZDir::path( array( $overrideDir, $fileName ) );
                 else
                    $overrideFile = eZDir::path( array( $rootDir, $overrideDir, $fileName ) );

                if ( file_exists( $overrideFile . '.php' ) )
                {
                    // recursion eZDebug::writeStrict( "INI files with *.ini.php suffix is DEPRECATED, use *.ini or *.ini.append.php instead: $overrideFile.php", __METHOD__ );
                    $inputFiles[] = $overrideFile . '.php';
                }

                if ( file_exists( $overrideFile ) )
                {
                    $inputFiles[] = $overrideFile;
                }

                if ( file_exists( $overrideFile . '.append.php' ) )
                {
                    $inputFiles[] = $overrideFile . '.append.php';
                }

                if ( file_exists( $overrideFile . '.append' ) )
                {
                    // recursion eZDebug::writeStrict( "INI files with *.ini.append suffix is DEPRECATED, use *.ini or *.ini.append.php instead: $overrideFile.append", __METHOD__ );
                    $inputFiles[] = $overrideFile . '.append';
                }
            }
        }
    }

    /*!
      \protected
      Generates cache name for loadCache
    */
    protected function cacheFileName( $placement = false )
    {
        $cacheFileName = $this->FileName . '-' . $this->RootDir . '-' . $this->DirectAccess;

        if ( !$this->DirectAccess )
        {
            $cacheFileName .= '-' . serialize( $this->overrideDirs() );
        }
        if ( $this->UseTextCodec )
        {
            $cacheFileName .= '-' . eZTextCodec::internalCharset();
        }
        if ( $placement )
        {
            $cacheFileName .= '-placement:' . $placement;
        }
        $filePreFix = explode( '.', $this->FileName);
        return $filePreFix[0] . '-' . md5( $cacheFileName ) . '.php';
    }

    /**
     * Will load a cached version of the ini file if it exists,
     * if not it will parse the original file and create the cache file.
     *
     * @access protected
     * @internal Please use {@link eZINI::load()} or {@link eZINI::loadPlacement()}
     * @param bool $reset Reset ini values on instance
     * @param bool $placement Load cache for placment info, not the ini values themself.
     */
    function loadCache( $reset = true, $placement = false )
    {
        eZDebug::accumulatorStart( 'ini', 'ini_load', 'Load cache' );
        if ( $reset )
            $this->reset();
        $cachedDir = self::CONFIG_CACHE_DIR;

        $fileName = $this->cacheFileName( $placement );
        $cachedFile = $cachedDir . $fileName;
        if ( $placement )
        {
            $this->PlacementCacheFile = $cachedFile;
        }
        else
        {
            $this->CacheFile = $cachedFile;
        }

        $data = false;// this will contain cache data if cache data is valid
        if ( file_exists( $cachedFile ) )
        {
            if ( self::isDebugEnabled() )
                eZDebug::writeNotice( "Loading cache '$cachedFile' for file '" . $this->FileName . "'", __METHOD__ );

            include( $cachedFile );

            if ( !isset( $data['rev'] ) || $data['rev'] != eZINI::CONFIG_CACHE_REV )
            {
                if ( self::isDebugEnabled() )
                    eZDebug::writeNotice( "Old structure in cache file used, recreating '$cachedFile' to new structure", __METHOD__ );
                $data = false;
                $this->reset();
            }
            else if ( self::$checkFileMtime === true || self::$checkFileMtime === $this->FileName )
            {
                eZDebug::accumulatorStart( 'ini_check_mtime', 'ini_load', 'Check MTime' );
                $currentTime = time();
                $cacheCreatedTime = strtotime( $data['created'] );
                $iniFile = $data['file'];// used by findInputFiles further down
                $inputFiles = $data['files'];
                foreach ( $inputFiles as $inputFile )
                {
                    $fileTime = file_exists( $inputFile ) ? filemtime( $inputFile ) : false;
                    if ( $fileTime === false )// Refresh cache & input files if file is gone
                    {
                        unset( $inputFiles );
                        $data = false;
                        $this->reset();
                        break;
                    }
                    else if ( $fileTime > $currentTime )
                    {
                        eZDebug::writeError( 'Input file "' . $inputFile . '" has a timestamp higher then current time, ignoring to avoid infinite recursion!', __METHOD__ );
                    }
                    else if ( $fileTime > $cacheCreatedTime )// Refresh cache if file has been changed
                    {
                        $data = false;
                        $this->reset();
                        break;
                    }
                }
                eZDebug::accumulatorStop( 'ini_check_mtime' );
            }
        }

        if ( $data )// if we have cache data on this point, use it
        {
            $this->Charset = $data['charset'];
            $this->ModifiedBlockValues = array();
            if ( $placement )
                $this->BlockValuesPlacement = $data['val'];
            else
                $this->BlockValues = $data['val'];
            unset( $data );
        }
        else
        {
            if ( !isset( $inputFiles ) )// use $inputFiles from cache if defined
            {
                eZDebug::accumulatorStart( 'ini_find_files', 'ini_load', 'Find INI Files' );
                $this->findInputFiles( $inputFiles, $iniFile );
                eZDebug::accumulatorStop( 'ini_find_files' );
                if ( count( $inputFiles ) === 0 )
                {
                    eZDebug::accumulatorStop( 'ini' );
                    return false;
                }
            }

            eZDebug::accumulatorStart( 'ini_files_parse', 'ini_load', 'Parse' );
            $this->parse( $inputFiles, $iniFile, false, $placement );
            eZDebug::accumulatorStop( 'ini_files_parse' );
            eZDebug::accumulatorStart( 'ini_files_save', 'ini_load', 'Save Cache' );
            $cacheSaved = $this->saveCache( $cachedDir, $cachedFile, $placement ? $this->BlockValuesPlacement : $this->BlockValues, $inputFiles, $iniFile );
            eZDebug::accumulatorStop( 'ini_files_save' );

            if ( $cacheSaved )
            {
                // Write log message to storage.log
                eZLog::writeStorageLog( $fileName, $cachedDir );
            }
        }

        eZDebug::accumulatorStop( 'ini' );
    }

    /**
     * Stores the content of the INI object to the cache file \a $cachedFile.
     *
     * @param string $cachedDir Cache dir, usually "var/cache/ini/"
     * @param string $cachedFile Name of cache file as returned by cacheFileName()
     * @param array $data Configuration data as an associative array structure
     * @param array $inputFiles List of input files used as basis for cache (for use in load cache to check mtime)
     * @param string $iniFile Ini file path string returned by findInputFiles() for main ini file
     * @return bool
     */
    protected function saveCache( $cachedDir, $cachedFile, array $data, array $inputFiles, $iniFile )
    {
        if ( !file_exists( $cachedDir ) )
        {
            if ( !eZDir::mkdir( $cachedDir, 0777, true ) )
            {
                eZDebug::writeError( "Couldn't create cache directory $cachedDir, perhaps wrong permissions", __METHOD__ );
                return false;
            }
        }

        // Save the data to a temp cached file
        $tmpCacheFile = $cachedFile . '_' . substr( md5( mt_rand() ), 0, 8 );
        $fp = @fopen( $tmpCacheFile, "w" );
        if ( $fp === false )
        {
            eZDebug::writeError( "Couldn't create cache file '$cachedFile', perhaps wrong permissions?", __METHOD__ );
            return false;
        }

        // Write cache data as a php structure with some meta information for use while reading cache
        fwrite( $fp, "<?php\n// This is a auto generated ini cache file, time created:" . date( DATE_RFC822 ) . "\n" );

        fwrite( $fp, "\$data = array(\n" );
        fwrite( $fp, "'rev' => " . eZINI::CONFIG_CACHE_REV . ",\n" );
        fwrite( $fp, "'created' => '" . date('c') . "',\n" );

        if ( $this->Codec )
            fwrite( $fp, "'charset' => \"".$this->Codec->RequestedOutputCharsetCode."\",\n" );
        else
            fwrite( $fp, "'charset' => \"$this->Charset\",\n" );

        fwrite( $fp, "'files' => " . preg_replace( "@\n[\s]+@", '', var_export( $inputFiles, true ) ) . ",\n" );
        fwrite( $fp, "'file' => '$iniFile',\n" );

        fwrite( $fp, "'val' => " . preg_replace( "@\n[\s]+@", '', var_export( $data, true ) ) . ");" );
        fwrite( $fp, "\n?>" );
        fclose( $fp );

        // Rename cache temp file to final desitination and set permissions
        if( eZFile::rename( $tmpCacheFile, $cachedFile ) )
        {
            chmod( $cachedFile, self::$filePermission );
        }


        if ( self::isDebugEnabled() )
            eZDebug::writeNotice( "Wrote cache file '$cachedFile'", __METHOD__ );

        return true;
    }

    /*!
      \private
      Parses either the override ini file or the standard file and then the append
      override file if it exists.
     */
    function parse( $inputFiles = false, $iniFile = false, $reset = true, $placement = false )
    {
        if ( $inputFiles === false or
             $iniFile === false )
        {
            eZDebug::accumulatorStart( 'ini_parse_find_files', 'ini_load', 'Find INI Files2' );
            $this->findInputFiles( $inputFiles, $iniFile );
            eZDebug::accumulatorStop( 'ini_parse_find_files' );
        }

        if ( $reset )
            $this->reset();

        foreach ( $inputFiles as $inputFile )
        {
            if ( file_exists( $inputFile ) )
            {
                $this->parseFile( $inputFile, $placement );
            }
        }
    }

    /*!
      \private
      Will parse the INI file and store the variables in the variable $this->BlockValues
     */
    function parseFile( $file, $placement = false )
    {
        if ( self::isDebugEnabled() )
            eZDebug::writeNotice( "Parsing file '$file'", __METHOD__ );

        $contents = file_get_contents( $file );
        if ( $contents === false )
        {
            eZDebug::writeError( "Failed opening file '$file' for reading", __METHOD__ );
            return false;
        }

        $contents = str_replace( "\r", '', $contents );
        $endOfLine = strpos( $contents, "\n" );
        $line = substr( $contents, 0, $endOfLine );

        $currentBlock = "";
        if ( $line )
        {
            // check for charset
            if ( preg_match( "/#\?ini(.+)\?/", $line, $ini_arr ) )
            {
                $args = explode( " ", trim( $ini_arr[1] ) );
                foreach ( $args as $arg )
                {
                    $vars = explode( '=', trim( $arg ) );
                    if ( $vars[0] == "charset" )
                    {
                        $val = $vars[1];
                        if ( $val[0] == '"' and
                             strlen( $val ) > 0 and
                             $val[strlen($val)-1] == '"' )
                            $val = substr( $val, 1, strlen($val) - 2 );
                        $this->Charset = $val;
                    }
                }
            }
        }

        unset( $this->Codec );
        if ( $this->UseTextCodec )
        {
            $this->Codec = eZTextCodec::instance( $this->Charset, false, false );

            if ( $this->Codec )
            {
                eZDebug::accumulatorStart( 'ini_conversion', false, 'INI string conversion' );
                $contents = $this->Codec->convertString( $contents );
                eZDebug::accumulatorStop( 'ini_conversion' );
            }
        }
        else
            $this->Codec = null;

        foreach ( explode( "\n", $contents ) as $line )
        {
            if ( $line == '' or $line[0] == '#' )
                continue;
            if ( preg_match( "/^(.+)##.*/", $line, $regs ) )
                $line = $regs[1];
            if ( trim( $line ) == '' )
                continue;
            // check for new block
            if ( preg_match("#^\[(.+)\]\s*$#", $line, $newBlockNameArray ) )
            {
                $newBlockName = trim( $newBlockNameArray[1] );
                $currentBlock = $newBlockName;
                continue;
            }

            // check for variable
            if ( preg_match("#^([\w_*@-]+)\\[\\]$#", $line, $valueArray ) )
            {
                $varName = trim( $valueArray[1] );

                if ( $placement )
                {
                    if ( isset( $this->BlockValuesPlacement[$currentBlock][$varName] ) &&
                         !is_array( $this->BlockValuesPlacement[$currentBlock][$varName] ) )
                    {
                        eZDebug::writeError( "Wrong operation on the ini setting array '$varName'", __METHOD__ );
                        continue;
                    }

                    $this->BlockValuesPlacement[$currentBlock][$varName][] = $file;
                }
                else
                {
                    $this->BlockValues[$currentBlock][$varName] = array();

                    // In direct access mode we create empty elements at the beginning of an array
                    // in case it is redefined in this ini file. So when we will save it, definition
                    // will be created as well.
                    if ( $this->AddArrayDefinition )
                    {
                        $this->BlockValues[$currentBlock][$varName][] = "";
                    }
                }
            }
            else if ( preg_match("#^([\w_*@-]+)(\\[([^\\]]*)\\])?=(.*)$#", $line, $valueArray ) )
            {
                $varName = trim( $valueArray[1] );
                $varValue = $valueArray[4];

                if ( $valueArray[2] )
                {
                    if ( $valueArray[3] )
                    {
                        $keyName = $valueArray[3];
                        if ( $placement )
                        {
                            $this->BlockValuesPlacement[$currentBlock][$varName][$keyName] = $file;
                        }
                        else
                        {
                            $this->BlockValues[$currentBlock][$varName][$keyName] = $varValue;
                        }
                    }
                    else
                    {
                        if ( $placement )
                        {
                            $this->BlockValuesPlacement[$currentBlock][$varName][] = $file;
                        }
                        else
                        {
                            $this->BlockValues[$currentBlock][$varName][] = $varValue;
                        }
                    }
                }
                else
                {
                    if ( $placement )
                    {
                        $this->BlockValuesPlacement[$currentBlock][$varName] = $file;
                    }
                    else
                    {
                        $this->BlockValues[$currentBlock][$varName] = $varValue;
                    }
                }
            }
        }
    }

    /*!
     \removes the cache file if it exists.
    */
    function resetCache()
    {
        if ( $this->CacheFile && file_exists( $this->CacheFile ) )
            unlink( $this->CacheFile );
        if ( $this->PlacementCacheFile && file_exists( $this->PlacementCacheFile ) )
            unlink( $this->PlacementCacheFile );
    }


    /*!
      Saves the file to disk.
      If filename is given the file is saved with that name if not the current name is used.
      If \a $useOverride is true then the file will be placed in the override directory,
      if \a $useOverride is "append" it will append ".append" to the filename.
    */
    function save( $fileName = false, $suffix = false, $useOverride = false,
                   $onlyModified = false, $useRootDir = true, $resetArrays = false,
                   $encapsulateInPHP = true )
    {
        $lineSeparator = eZSys::lineSeparator();
        $pathArray = array();
        $dirArray = array();
        if ( $fileName === false )
            $fileName = $this->FileName;
        if ( $useRootDir === true )
        {
            $pathArray[] = $this->RootDir;
            $dirArray[] = $this->RootDir;
        }
        else if ( is_string( $useRootDir ) )
        {
            $pathArray[] = $useRootDir;
            $dirArray[] = $useRootDir;
        }
        if ( $useOverride )
        {
            $pathArray[] = 'override';
            $dirArray[] = 'override';
        }
        if ( $useOverride === 'append' )
            $fileName .= '.append';
        if ( $suffix !== false )
            $fileName .= $suffix;

        /* Try to guess which filename would fit better: 'xxx.apend' or 'xxx.append.php'.
         * We choose 'xxx.append.php' in all cases except when
         * 'xxx.append' exists already and 'xxx.append.php' does not exist.
         */
        if( strstr( $fileName, '.append' ) )
        {
            $fnAppend    = preg_replace( '#\.php$#', '', $fileName );
            $fnAppendPhp = $fnAppend.'.php';
            $fpAppend    = eZDir::path( array_merge( $pathArray, array( $fnAppend ) ) );
            $fpAppendPhp = eZDir::path( array_merge( $pathArray, array( $fnAppendPhp ) ) );
            $fileName = ( file_exists( $fpAppend ) && !file_exists( $fpAppendPhp ) )
                       ? $fnAppend : $fnAppendPhp;
        }

        $originalFileName = $fileName;
        $backupFileName = $originalFileName . eZSys::backupFilename();
        $fileName .= '.tmp';

        $dirPath = eZDir::path( $dirArray );
        if ( !file_exists( $dirPath ) )
            eZDir::mkdir( $dirPath, octdec( '777' ), true );

        $filePath = eZDir::path( array_merge( $pathArray, array( $fileName ) ) );
        $originalFilePath = eZDir::path( array_merge( $pathArray, array( $originalFileName ) ) );
        $backupFilePath = eZDir::path( array_merge( $pathArray, array( $backupFileName ) ) );

        $fp = @fopen( $filePath, "w+");
        if ( !$fp )
        {
            eZDebug::writeError( "Failed opening file '$filePath' for writing", __METHOD__ );
            return false;
        }
        $writeOK = true;
        $written = 0;

        $charset = $this->Codec ? $this->Codec->RequestedOutputCharsetCode : $this->Charset;
        if ( $encapsulateInPHP )
        {
            $written = fwrite( $fp, "<?php /* #?ini charset=\"$charset\"?$lineSeparator$lineSeparator" );
        }
        else
        {
            $written = fwrite( $fp, "#?ini charset=\"$charset\"?$lineSeparator$lineSeparator" );
        }

        if ( $written === false )
            $writeOK = false;
        $i = 0;
        if ( $writeOK )
        {
            foreach( array_keys( $this->BlockValues ) as $blockName )
            {
                if ( $onlyModified )
                {
                    $groupHasModified = false;
                    if ( isset( $this->ModifiedBlockValues[$blockName] ) )
                    {
                        foreach ( $this->ModifiedBlockValues[$blockName] as $modifiedValue )
                        {
                            if ( $modifiedValue )
                                $groupHasModified = true;
                        }
                    }
                    if ( !$groupHasModified )
                        continue;
                }
                $written = 0;
                if ( $i > 0 )
                    $written = fwrite( $fp, "$lineSeparator" );
                if ( $written === false )
                {
                    $writeOK = false;
                    break;
                }
                $written = fwrite( $fp, "[$blockName]$lineSeparator" );
                if ( $written === false )
                {
                    $writeOK = false;
                    break;
                }
                foreach( array_keys( $this->BlockValues[$blockName] ) as $blockVariable )
                {
                    if ( $onlyModified )
                    {
                        if ( !isset( $this->ModifiedBlockValues[$blockName][$blockVariable] ) or
                             !$this->ModifiedBlockValues[$blockName][$blockVariable] )
                            continue;
                    }
                    $varKey = $blockVariable;
                    $varValue = $this->BlockValues[$blockName][$blockVariable];
                    if ( is_array( $varValue ) )
                    {
                        if ( count( $varValue ) > 0 )
                        {
                            $customResetArray = ( isset( $this->BlockValues[$blockName]['ResetArrays'] ) and
                                                  $this->BlockValues[$blockName]['ResetArrays'] == 'false' )
                                                ? true
                                                : false;
                            if ( $resetArrays and !$customResetArray )
                                $written = fwrite( $fp, "$varKey" . "[]$lineSeparator" );
                            foreach ( $varValue as $varArrayKey => $varArrayValue )
                            {
                                if ( is_string( $varArrayKey ) )
                                    $written = fwrite( $fp, "$varKey" . "[$varArrayKey]=$varArrayValue$lineSeparator" );
                                else
                                {
                                    if ( $varArrayValue == NULL )
                                        $written = fwrite( $fp, "$varKey" . "[]$lineSeparator" );
                                    else
                                        $written = fwrite( $fp, "$varKey" . "[]=$varArrayValue$lineSeparator" );
                                }
                                if ( $written === false )
                                    break;
                            }
                        }
                        else
                            $written = fwrite( $fp, "$varKey" . "[]$lineSeparator" );
                    }
                    else
                    {
                        $written = fwrite( $fp, "$varKey=$varValue$lineSeparator" );
                    }
                    if ( $written === false )
                    {
                        $writeOK = false;
                        break;
                    }
                }
                if ( !$writeOK )
                    break;
                ++$i;
            }
        }
        if ( $writeOK )
        {
            if ( $encapsulateInPHP )
            {
                $written = fwrite( $fp, "*/ ?>" );

                if ( $written === false )
                    $writeOK = false;
            }
        }
        @fclose( $fp );
        if ( !$writeOK )
        {
            unlink( $filePath );
            return false;
        }

        chmod( $filePath, self::$filePermission );

        if ( file_exists( $backupFilePath ) )
            unlink( $backupFilePath );
        if ( file_exists( $originalFilePath ) )
        {
            if ( !rename( $originalFilePath, $backupFilePath ) )
                return false;
        }
        if ( !rename( $filePath, $originalFilePath ) )
        {
            rename( $backupFilePath, $originalFilePath );
            return false;
        }

        return true;
    }

    /*!
     Removes all read data from .ini files.
    */
    function reset()
    {
        $this->BlockValues = array();
        $this->ModifiedBlockValues = array();
    }

    /*!
     \return the root directory from where all .ini and override files are read.

     This is set by the instance() or eZINI() functions.
    */
    function rootDir()
    {
        return $this->RootDir;
    }

    /**
     * Return the override directories witch raw override dir data, or within a scope if $scope is set,
     * see {@link eZINI::defaultOverrideDirs()} for how the raw data looks like.
     *
     * @param string|null|false $scope See {@link eZINI::defaultOverrideDirs()} for possible scope values
     *              If false then you'll get raw override dir structure, null (default) is a simplified
     *              variant withouth scopes that is easy to iterate over.
     * @return array
     */
    function overrideDirs( $scope = null )
    {
        if ( $this->UseLocalOverrides == true )
            $dirs =& $this->LocalOverrideDirArray;
        else
            $dirs =& self::$GlobalOverrideDirArray;

        return self::overrideDirsByScope( $dirs, $scope );
    }

    /**
     * Return the global override directories witch raw override dir data, or within a scope if $scope is set,
     * see {@link eZINI::defaultOverrideDirs()} for how the raw data looks like.
     *
     * @param string|false|null $scope See {@link eZINI::defaultOverrideDirs()} for possible scope values
     *              If false then you'll get raw override dir structure, null (default) is a simplified
     *              variant withouth scopes that is easy to iterate over.
     * @return array
     */
    public static function globalOverrideDirs( $scope = null )
    {
        return self::overrideDirsByScope( self::$GlobalOverrideDirArray, $scope );
    }

    /**
     * Return the override directories witch raw override dir data, or within a scope if $scope is set,
     * see {@link eZINI::defaultOverrideDirs()} for how the raw data looks like.
     *
     * @param array $dirs Directories directly from internal raw structure (see above).
     * @param string|null|false $scope See {@link eZINI::defaultOverrideDirs()} for possible scope values
     *              If false then you'll get raw override dir structure, null (default) is a simplified
     *              variant withouth scopes that is easy to iterate over.
     * @return array
     */
    protected static function overrideDirsByScope( array $dirs, $scope = null )
    {
        if ( $scope !== null )
        {
            if ( $scope === false )
                return $dirs;
            if ( isset( $dirs[$scope] ) )
                return $dirs[$scope];
            eZDebug::writeWarning( "Undefined override dir scope: '$scope'", __METHOD__ );
        }

        return array_merge( $dirs['sa-extension'], $dirs['siteaccess'], $dirs['extension'], $dirs['override'] );
    }

    /**
     * Default override directories as raw array data
     *
     * @return array An associated array of associated arrays of arrays..
     *               First level keys are the scope and values are arrays
     *               Second level keys are identifier (numberic if not defined by caller) and value is arrays
     *               Third level contains (string) override dir, (bool) global flag if false then
     *               relative to {@link $RootDir} and (string|false) optional identifier as used by
     *               {@link eZINI::prependOverrideDir()} to match and replace values on.
     */
    static public function defaultOverrideDirs()
    {
        static $def =  array(
                'sa-extension' => array(),
                'siteaccess' => array(),
                'extension' => array(),
                'override' => array( array( 'override', false ) )
        );
        return $def;
    }

    /**
     * Set the override directories witch raw override dir data, or within a scope if $scope is set,
     * see {@link eZINI::defaultOverrideDirs()} for how the raw data looks like.
     *
     * @param array $newDirs
     * @param string|false $scope See {@link eZINI::defaultOverrideDirs()} for possible scope values
     */
    function setOverrideDirs( array $newDirs, $scope = false )
    {
        if ( $this->UseLocalOverrides == true )
            $dirs =& $this->LocalOverrideDirArray;
        else
            $dirs =& self::$GlobalOverrideDirArray;

        if ( $scope === false )
            $dirs = $newDirs;
        else if ( isset( $dirs[$scope] ) )
            $dirs[$scope] = $newDirs;
        else
            eZDebug::writeWarning( "Undefined override dir scope: '$scope'", __METHOD__ );

        $this->CacheFile = false;
    }

    /**
     * Reset the global override directories with data from {@link eZINI::defaultOverrideDirs()}
     */
    static public function resetGlobalOverrideDirs()
    {
        self::$GlobalOverrideDirArray = self::defaultOverrideDirs();
    }

    /**
     * Reset the override directories with data from {@link eZINI::defaultOverrideDirs()}
     */
    public function resetOverrideDirs()
    {
        $this->setOverrideDirs( self::defaultOverrideDirs() );
    }

    /**
     * Removes an override dir by identifier
     * See {@link eZINI::defaultOverrideDirs()} for how these parameters are used.
     *
     * @param string $identifier Will remove existing directory with identifier it it exists
     * @param string $scope By default 'extension'
     * @return bool True if new dir was appended, false if there was a $identifier match and a overwrite
     */
    function removeOverrideDir( $identifier, $scope = 'extension' )
    {
        if ( $this->UseLocalOverrides == true )
            $dirs =& $this->LocalOverrideDirArray;
        else
            $dirs =& self::$GlobalOverrideDirArray;

        if ( !$identifier || !is_string( $identifier ) )
        {
            eZDebug::writeError( "\$identifier must be a string", __METHOD__ );
            return false;
        }

        if ( !isset( $dirs[$scope] ) )
        {
            eZDebug::writeWarning( "Undefined override dir scope: '$scope'", __METHOD__ );
            $scope = 'extension';
        }

        $overrideRemoved = false;
        if ( isset( $dirs[$scope][$identifier] ) )
        {
            unset( $dirs[$scope][$identifier] );
            $overrideRemoved = true;
            $this->CacheFile = false;
        }

        return $overrideRemoved;
    }

    /**
     * Prepends the override directory $dir to the override directory list.
     * Prepends override dir to 'extension' scope by default, bellow siteaccess and override settings.
     * See {@link eZINI::defaultOverrideDirs()} for how these parameters are used.
     *
     * @param string $dir
     * @param bool $globalDir
     * @param string|false $identifier Will overwrite existing directory with same identifier if set
     * @param string|null $scope
     * @return bool True if new dir was prepended, false if there was a $identifier match and a overwrite
     */
    function prependOverrideDir( $dir, $globalDir = false, $identifier = false, $scope = null )
    {
        if ( self::isDebugEnabled() )
            eZDebug::writeNotice( "Prepending override dir '$dir'", "eZINI" );

        if ( $this->UseLocalOverrides == true )
            $dirs =& $this->LocalOverrideDirArray;
        else
            $dirs =& self::$GlobalOverrideDirArray;

        $scope = self::selectOverrideScope( $scope, $identifier, $dir, 'extension' );

        // Check if the override with the current identifier already exists
        $overrideOverwritten = false;
        if ( $identifier && isset( $dirs[$scope][$identifier] ) )
        {
            $dirs[$scope][$identifier] = array( $dir, $globalDir );
            $overrideOverwritten = true;
        }
        else
        {
            if ( $identifier )
                $dirs[$scope] = array_merge( array( $identifier => array( $dir, $globalDir ) ), $dirs[$scope] );
            else
                $dirs[$scope] = array_merge( array( array( $dir, $globalDir ) ), $dirs[$scope] );
        }

        $this->CacheFile = false;
        return $overrideOverwritten === false;
     }

    /**
     * Appends the override directory $dir to the override directory list.
     * Appends override dir to 'override' scope if scope is not defined, meaning above anything else.
     * See {@link eZINI::defaultOverrideDirs()} for how these parameters are used.
     *
     * @param string $dir
     * @param bool $globalDir
     * @param string|false $identifier Will overwrite existing directory with same identifier if set
     * @param string|null $scope
     * @return bool True if new dir was appended, false if there was a $identifier match and a overwrite
     */
    function appendOverrideDir( $dir, $globalDir = false, $identifier = false, $scope = null )
    {
        if ( self::isDebugEnabled() )
            eZDebug::writeNotice( "Appending override dir '$dir'", __METHOD__ );

        if ( $this->UseLocalOverrides == true )
            $dirs =& $this->LocalOverrideDirArray;
        else
            $dirs =& self::$GlobalOverrideDirArray;

        $scope = self::selectOverrideScope( $scope, $identifier, $dir, 'override' );

        // Check if the override with the current identifier already exists
        $overrideOverwritten = false;
        if ( $identifier && isset( $dirs[$scope][$identifier] ) )
        {
            $dirs[$scope][$identifier] = array( $dir, $globalDir );
            $overrideOverwritten = true;
        }
        else
        {
            if ( $identifier )
                $dirs[$scope][$identifier] = array( $dir, $globalDir );
            else
                $dirs[$scope][] = array( $dir, $globalDir );
        }

        $this->CacheFile = false;
        return $overrideOverwritten === false;
    }

    /**
     * Function to handle bc with code from pre 4.4 that does not know about scopes
     *
     * @since 4.4
     * @param string|null $scope
     * @param string $identifier
     * @param string $dir
     * @param string $default
     * @return string
     */
    protected static function selectOverrideScope( $scope, $identifier, $dir, $default )
    {
        if ( $scope !== null )
        {
            $def = self::defaultOverrideDirs();
            if ( isset( $def[$scope] ) )
                return $scope;
            eZDebug::writeWarning( "Undefined override dir scope: '$scope' with dir: '$dir'", __METHOD__ );
        }
        if ( $identifier === 'siteaccess' )
            return 'siteaccess';
        else if ( $identifier && strpos($identifier, 'extension:') === 0 )
            return 'extension';
        else if ( strpos($dir, 'siteaccess') !== false )
            return 'siteaccess';
        else if ( strpos($dir, 'extension') !== false )
            return 'extension';

        eZDebug::writeStrict( "Could not figgure out INI scope for \$identifier: '$identifier' with \$dir: '$dir', falling back to '$default'", __METHOD__ );
        return $default;
    }

    /*!
      Reads a variable from the ini file and puts it in the parameter \a $variable.
      \note \a $variable is not modified if the variable does not exist
    */
    function assign( $blockName, $varName, &$variable )
    {
        if ( isset( $this->BlockValues[$blockName][$varName] ) )
            $variable = $this->BlockValues[$blockName][$varName];
        else
            return false;
        return true;
    }

    /*!
      Reads a variable from the ini file.
      false is returned if the variable was not found.
    */
    function variable( $blockName, $varName )
    {
        if ( isset( $this->BlockValues[$blockName][$varName] ) )
            return $this->BlockValues[$blockName][$varName];
        else if ( !isset( $this->BlockValues[$blockName] ) )
            eZDebug::writeError( "Undefined group: '$blockName' in " . $this->FileName, __METHOD__ );
        else
            eZDebug::writeError( "Undefined variable: '$varName' in group '$blockName' in " . $this->FileName, __METHOD__ );
        return false;
    }

    /*!
      Reads multiple variables from the ini file.
      false is returned if the variable was not found.
    */
    function variableMulti( $blockName, $varNames, $signatures = array() )
    {
        $ret = array();

        if ( !isset( $this->BlockValues[$blockName] ) )
        {
            eZDebug::writeError( "Undefined group: '$blockName' in " . $this->FileName, "eZINI" );
            return false;
        }
        foreach ( $varNames as $key => $varName )
        {
            if ( isset( $this->BlockValues[$blockName][$varName] ) )
            {
                $ret[$key] = $this->BlockValues[$blockName][$varName];

                if ( isset( $signatures[$key] ) )
                {
                    switch ( $signatures[$key] )
                    {
                        case 'enabled':
                            $ret[$key] = $this->BlockValues[$blockName][$varName] == 'enabled';
                            break;
                    }
                }
            }
            else
            {
                $ret[] = null;
            }
        }

        return $ret;
    }

    /*!
      Checks if a variable is set. Returns true if the variable exists, false if not.
    */
    function hasVariable( $blockName, $varName )
    {
        return isset( $this->BlockValues[$blockName][$varName] );
    }

    /*!
      Check if a block/section is set. Returns true if the section/block is set, false if not
    */
    function hasSection( $sectionName )
    {
        return isset( $this->BlockValues[$sectionName] );
    }

    /*!
      \return true if the variable \a $varName in group \a $blockName has been modified.
    */
    function isVariableModified( $blockName, $varName )
    {
        return ( isset( $this->ModifiedBlockValues[$blockName][$varName] ) and
                 $this->ModifiedBlockValues[$blockName][$varName] );
    }

    /*!
      Reads a variable from the ini file. The variable
      will be returned as an array. ; is used as delimiter.
     */
    function variableArray( $blockName, $varName )
    {
        if ( isset( $this->BlockValues[$blockName][$varName] ) )
            $ret = $this->BlockValues[$blockName][$varName];
        else
            return false;

        if ( is_array( $ret ) )
        {
            $arr = array();
            foreach ( $ret as $key => $retItem )
            {
                $arr[$key] = explode( ';', $retItem );
            }
            $ret = $arr;
        }
        else if ( $ret !== false )
        {
            $ret = trim( $ret ) === '' ? array() : explode( ';', $ret );
        }

        return $ret;
    }

    /*!
      Checks if group $blockName is set. Returns true if the group exists, false if not.
    */
    function hasGroup( $blockName )
    {
        return isSet( $this->BlockValues[$blockName] );
    }

    /*!
      Fetches a variable group and returns it as an associative array.
     */
    function &group( $blockName )
    {
        if ( !isset( $this->BlockValues[$blockName] ) )
        {
            eZDebug::writeError( "Unknown group: '$blockName'", __METHOD__ );
            $ret = null;
            return $ret;
        }
        $ret = $this->BlockValues[$blockName];

        return $ret;
    }

    function isSettingReadOnly( $fileName = false, $blockName = false, $settingName = false )
    {
        if ( !$this->readOnlySettingsCheck() )
            return true;

        $ini = eZINI::instance();
        if ( !$ini->hasVariable( 'eZINISettings', 'ReadonlySettingList' ) )
            return true;

        $fileName = $fileName === false ? $ini->FileName : $fileName;
        $fileNameExploded = explode( '.', $fileName );
        $realFileName = $fileNameExploded[0] . '.' . $fileNameExploded[1];
        $blockName = $blockName === false ? '*' : $blockName;
        $settingName = $settingName === false ? '*' : $settingName;
        $currentSetting = $realFileName . '/' . $blockName . '/' . $settingName;

        $settingList = $ini->variable( 'eZINISettings', 'ReadonlySettingList' );
        $settingList[] = 'site.ini/eZINISettings/*';

        $result = !( in_array( $realFileName . '/*' , $settingList ) or
                     in_array( $realFileName . '/' . $blockName . '/*'  , $settingList ) or
                     in_array( $realFileName . '/' . $blockName . '/' . $settingName  , $settingList ) );

        return $result;
    }
    /*!
      Removes the group and all it's settings from the .ini file
    */
    function removeGroup( $blockName )
    {
        unset( $this->BlockValues[$blockName] );
        unset( $this->BlockValuesPlacement[$blockName] );
    }

    function removeSetting( $blockName, $settingName )
    {
        unset( $this->BlockValues[$blockName][$settingName] );
        unset( $this->BlockValuesPlacement[$blockName][$settingName] );
        if ( $this->BlockValues[$blockName] == null )
            $this->removeGroup( $blockName );
    }

    /*!
     Fetches all defined groups and returns them as an associative array
    */
    function &groups()
    {
        return $this->BlockValues;
    }

    /*!
     Fetches all defined placements for every setting and returns them as an associative array
    */
    function &groupPlacements()
    {
        if ( !$this->BlockValuesPlacement )
        {
            $this->loadPlacement();
        }
        return $this->BlockValuesPlacement;
    }

    /**
     * Gives you the location of a ini file based on it's path, format is same as used internally
     * for $identifer for override dirs-
     * Eg: default / ext-siteaccess:<ext> / siteaccess / extension:<ext> / override
     *
     * @param string $path
     * @return string
     */
    function findSettingPlacement( $path )
    {
        if ( is_array( $path ) && isset( $path[0] ) )
            $path = $path[0];
        $exploded = explode( '/', $path );
        $directoryCount = count( $exploded );
        switch ( $directoryCount )
        {
            case 2:
                $placement = 'default';
            break;
            case 3:
                $placement = 'override';
            break;
            case 4:
            {
                if ( $exploded[0] === 'extension' )
                    $placement = 'extension:' . $exploded[1];
                else
                    $placement = 'siteaccess';
            }
            break;
            case 6:
            {
                $placement = 'ext-siteaccess:' . $exploded[1];
            }
            break;
            default:
                $placement = 'undefined';
            break;
        }
        return $placement;
    }

    function settingType( $settingValue )
    {
        if ( is_array( $settingValue ) )
            return 'array';

        if ( is_numeric( $settingValue ) )
            return 'numeric';

        if ( $settingValue == 'true' or $settingValue == 'false' )
        {
            return 'true/false';
        }
        if ( $settingValue == 'enabled' or $settingValue == 'disabled' )
        {
            return 'enable/disable';
        }

        return 'string';
    }

    /*!
     Sets all groups overwriting the current values
    */
    function setGroups( $groupArray )
    {
        $resultArray = array();
        // Check for readOnly
        foreach ( $groupArray as $blockName => $blockVariables )
        {
            foreach ( $blockVariables as $variableName => $variableValue )
            {
                if ( !$this->isSettingReadOnly( $this->FileName, $blockName, $variableName ) )
                    continue;
                $resultArray[$blockName][$variableName] = $variableValue;
            }
        }
        $this->BlockValues = $resultArray;
    }

    /*!
     Sets multiple variables from the array \a $variables.
     \param $variables Contains an associative array with groups as first key,
                       variable names as second key and variable values as values.
     \code
     $ini->setVariables( array( 'SiteSettings' => array( 'SiteName' => 'mysite',
                                                         'SiteURL'  => 'mysite.com' ) ) );
     \endcode
     \sa setVariable
    */
    function setVariables( $variables )
    {
        foreach ( $variables as $blockName => $blockVariables )
        {
            foreach ( $blockVariables as $variableName => $variableValue )
            {
                $this->setVariable( $blockName, $variableName, $variableValue );
            }
        }
    }

    /*!
     Sets an INI file variable.
     \code
     $ini->setVariable( 'SiteSettings', 'SiteName', 'mysite' );
     \endcode
     \sa setVariables
    */
    function setVariable( $blockName, $variableName, $variableValue )
    {
        if ( !$this->isSettingReadOnly( $this->filename(), $blockName, $variableName ) )
            return false;

        $this->BlockValues[$blockName][$variableName] = $variableValue;
        $this->ModifiedBlockValues[$blockName][$variableName] = true;
    }

    /*!
      Returns BlockValues, which is a nicely named Array
    */
    function getNamedArray()
    {
        return $this->BlockValues;
    }

    /**
     * Returns whether the mentioned ini file has been loaded.
     *
     * @param string $fileName
     * @param string $rootDir
     * @param null|bool $useLocalOverrides default system setting if null
     *
     * @return bool
     */
    static function isLoaded( $fileName = 'site.ini', $rootDir = 'settings', $useLocalOverrides = null )
    {
        return isset( self::$instances["$rootDir-$fileName-$useLocalOverrides"] );
    }

    /**
     * Returns a shared instance of the eZINI class pr $fileName, $rootDir and $useLocalOverrides
     * param combinations.
     * If $useLocalOverrides is set to true you will get a copy of the current overrides,
     * but changes to the override settings will not be global.
     * Direct access is for accessing the filename directly in the specified path. .append and .append.php is automaticly added to filename
     * note: Use create() if you need to get a unique copy which you can alter.
     *
     * @param string $fileName
     * @param string $rootDir
     * @param null|bool $useTextCodec Default system setting if null (instance not used if not null!)
     * @param null|bool $useCache Default system setting if null (instance not used if not null!)
     * @param null|bool $useLocalOverrides Default system setting if null
     * @param bool $directAccess Direct access to specific file instead of values from several (instance not used if true!)
     * @param bool $addArrayDefinition @deprecated since version 4.5, use "new eZINI()" (instance not used if true!)
     * @return eZINI
     */
    static function instance( $fileName = 'site.ini', $rootDir = 'settings', $useTextCodec = null, $useCache = null, $useLocalOverrides = null, $directAccess = false, $addArrayDefinition = false )
    {
        if ( $addArrayDefinition !== false  || $directAccess !== false || $useTextCodec !== null || $useCache !== null )
        {
            // Could have trown strict error here but will cause issues if ini system has not been setup yet..
            return new eZINI( $fileName, $rootDir, $useTextCodec, $useCache, $useLocalOverrides, $directAccess, $addArrayDefinition );
        }

        $key = "$rootDir-$fileName-$useLocalOverrides";
        if ( !isset( self::$instances[$key] ) )
        {
            self::$instances[$key] = new eZINI( $fileName, $rootDir, $useTextCodec, $useCache, $useLocalOverrides, $directAccess, $addArrayDefinition );
        }
        return self::$instances[$key];
    }

    /*!
     Fetches the ini file \a $fileName and returns the INI object for it.
     \note This will not use the override system or read cache files, this is a direct fetch from one file.
    */
    static function fetchFromFile( $fileName, $useTextCodec = null )
    {
        return new eZINI( $fileName, false, $useTextCodec, false, false, true );
    }

    /**
     * Get ini file for a specific siteaccess (not incl extesnions or overrides)
     * use {@link eZSiteAccess::getIni()} instead if you want to have full ini env.
     *
     * @param string $siteAccess
     * @param string $iniFile
     * @return eZINI
     */
    static function getSiteAccessIni( $siteAccess, $iniFile )
    {
        $saPath = eZSiteAccess::findPathToSiteAccess( $siteAccess );
        return self::fetchFromFile( "$saPath/$iniFile" );
    }

    /*!
      \static
      Similar to instance() but will always create a new copy.
    */
    static function create( $fileName = 'site.ini', $rootDir = 'settings', $useTextCodec = null, $useCache = null, $useLocalOverrides = null )
    {
        $impl = new eZINI( $fileName, $rootDir, $useTextCodec, $useCache, $useLocalOverrides );
        return $impl;
    }

    /*!
       Sets ReadonlySettingsCheck variable.
    */
    function setReadOnlySettingsCheck( $readOnly = true )
    {
        $this->ReadOnlySettingsCheck = $readOnly;
    }

    /*!
       \return ReadonlySettingsCheck variable.
    */
    function readOnlySettingsCheck()
    {
        return $this->ReadOnlySettingsCheck;
    }

    /**
     * Resets a specific instance of eZINI.
     *
     * @deprecated since 4.5, use resetInstance() instead
     *
     * @param string $fileName
     * @param string $rootDir
     * @param null|bool $useLocalOverrides default system setting if null
     *
     * @see resetInstance()
     */
    static function resetGlobals( $fileName = 'site.ini', $rootDir = 'settings', $useLocalOverrides = null )
    {
        self::resetInstance( $fileName, $rootDir, $useLocalOverrides );
    }

    /**
     * Resets a specific instance of eZINI.
     *
     * @since 4.5
     *
     * @param string $fileName
     * @param string $rootDir
     * @param null|bool $useLocalOverrides default system setting if null
     */
    static function resetInstance( $fileName = 'site.ini', $rootDir = 'settings', $useLocalOverrides = null )
    {
        unset( self::$instances["$rootDir-$fileName-$useLocalOverrides"] );
    }

    /**
     * Reset all eZINI instances as well override dirs ( optional )
     *
     * @deprecated since 4.5, use resetAllInstances() instead
     *
     * @param bool $resetOverrideDirs Specify if you don't want to clear override dirs
     *
     * @see resetAllInstances()
     */
    static function resetAllGlobals( $resetGlobalOverrideDirs = true )
    {
        self::resetAllInstances( $resetGlobalOverrideDirs );
    }

    /**
     * Reset all eZINI instances as well override dirs ( optional )
     *
     * @since 4.5
     *
     * @param bool $resetOverrideDirs Specify if you don't want to clear override dirs
     */
    static function resetAllInstances( $resetGlobalOverrideDirs = true )
    {
        self::$instances = array();

        if ( $resetGlobalOverrideDirs )
            self::resetGlobalOverrideDirs();
    }

    /// \privatesection
    /// The charset of the ini file
    public $Charset;

    /// Variable to store the textcodec.
    public $Codec;

    /// Variable to store the ini file values.
    public $BlockValues;

    /// Variable to store the setting placement (which file is the setting in).
    public $BlockValuesPlacement;

    /// Variable to store whether variables are modified or not
    public $ModifiedBlockValues;

    /// Stores the filename
    public $FileName;

    /// The root of all ini files
    public $RootDir;

    /// Whether to use the text codec when reading the ini file or not
    public $UseTextCodec;

    /// Stores the path and filename of the value cache file
    public $CacheFile;

    /// Stores the path and filename of the placement cache file
    public $PlacementCacheFile;

    /// true if cache should be used
    public $UseCache;

    /// true if the overrides should only be changed locally
    public $UseLocalOverrides;

    /// Contains the override dirs, if in local mode
    public $LocalOverrideDirArray;

    /// Contains global override dirs
    static protected $GlobalOverrideDirArray = null;

    /// If \c true then all file loads are done directly on the filename.
    public $DirectAccess;

    /// If \c true empty element will be created in the beginning of array if it is defined in this ini file.
    public $AddArrayDefinition;

    /// If \c true eZINI will check each setting (before saving) for correspondence of settings in site.ini[eZINISetting].ReadonlySettingList
    public $ReadOnlySettingsCheck = true;

}

?>
