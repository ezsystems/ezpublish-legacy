<?php
//
// $Id$
//
// Definition of eZINI class
//
// Created on: <12-Feb-2002 14:06:45 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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
  \class eZINI ezini.php
  \ingroup eZUtils
  \brief Reads and writes .ini style configuration files

  The most common way of using it is.
  \code
  // include the file
  //include_once( "classes/ezinifile.php" );

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
    /*!
     Has the date of the current cache code implementation as a timestamp,
     if this changes(increases) the cache files will need to be recreated.
    */
    const CACHE_CODE_DATE = 1043407542;
    const DEBUG_INTERNALS = false;

    // set EZP_INI_FILEMTIME_CHECK constant to false to improve performance by
    // not checking modified time on ini files. You can also set it to a string, the name
    // of a ini file you still want to check modified time on, best example would be to
    // set it to 'site.ini' to make the system still check that but not the rest.
    static protected $checkFileMtime = null;

    // set EZP_INI_FILE_PERMISSION constant to the permissions you want saved
    // ini and cache files to have.
    static protected $filePermission = null;

    /*!
      Initialization of object;
    */
    function eZINI( $fileName = 'site.ini', $rootDir = '', $useTextCodec = null, $useCache = null, $useLocalOverrides = null, $directAccess = false, $addArrayDefinition = false )
    {
        $this->Charset = 'utf8';
        if ( $fileName == '' )
            $fileName = 'site.ini';
        if ( $rootDir !== false && $rootDir == '' )
            $rootDir = 'settings';
        if ( $useCache === null )
            $useCache = eZINI::isCacheEnabled();
        if ( eZINI::isNoCacheAdviced() )
        {
            $useCache = false;
        }
        if ( $useTextCodec === null )
            $useTextCodec = eZINI::isTextCodecEnabled();

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

        if ( $this->UseLocalOverrides == true )
        {
            $this->LocalOverrideDirArray = $GLOBALS['eZINIOverrideDirList'];
        }

        if ( self::$filePermission === null )
        {
	        if ( defined( 'EZP_INI_FILE_PERMISSION' ) )
	            self::$filePermission = EZP_INI_FILE_PERMISSION;
	        else
	            self::$filePermission = 0666;
        }

        $this->load();
    }

    /*!
     \return the filename.
    */
    function filename()
    {
        return $this->FileName;
    }

    /*!
     \static
     \return true if INI cache is enabled globally, the default value is true.
     Change this setting with setIsCacheEnabled.
    */
    static function isCacheEnabled()
    {
        if ( !isset( $GLOBALS['eZINICacheEnabled'] ) )
             $GLOBALS['eZINICacheEnabled'] = true;
         return $GLOBALS['eZINICacheEnabled'];
    }

    /*!
     \return true if cache is not adviced to be used.
     \note The no-cache-adviced flag might not be modified in time for site.ini and some other important files to be affected.
    */
    function isNoCacheAdviced()
    {
        if ( !isset( $GLOBALS['eZSiteBasics'] ) )
            return false;
        $siteBasics = $GLOBALS['eZSiteBasics'];
        if ( !isset( $siteBasics['no-cache-adviced'] ) )
            return false;
        return $siteBasics['no-cache-adviced'];
    }

    /*!
     \static
     Sets whether caching is enabled for INI files or not. This setting is global
     and can be overriden in the instance() function.
    */
    static function setIsCacheEnabled( $cache )
    {
        $GLOBALS['eZINICacheEnabled'] = $cache;
    }

    /*!
     \static
     \return true if debugging of internals is enabled, this will display
     which files are loaded and when cache files are created.
      Set the option with setIsDebugEnabled().
    */
    static function isDebugEnabled()
    {
        if ( !isset( $GLOBALS['eZINIDebugInternalsEnabled'] ) )
             $GLOBALS['eZINIDebugInternalsEnabled'] = eZINI::DEBUG_INTERNALS;
        return $GLOBALS['eZINIDebugInternalsEnabled'];
    }

    /*!
     \static
     Sets whether internal debugging is enabled or not.
    */
    static function setIsDebugEnabled( $debug )
    {
        $GLOBALS['eZINIDebugInternalsEnabled'] = $debug;
    }

    /*!
     \static
     \return true if textcodecs is to be used, this will use the eZTextCodec class
             in the eZI18N library for text conversion.
      Set the option with setIsTextCodecEnabled().
    */
    static function isTextCodecEnabled()
    {
        if ( !isset( $GLOBALS['eZINITextCodecEnabled'] ) )
             $GLOBALS['eZINITextCodecEnabled'] = true;
        return $GLOBALS['eZINITextCodecEnabled'];
    }

    /*!
     \static
     Sets whether textcodec conversion is enabled or not.
    */
    static function setIsTextCodecEnabled( $codec )
    {
        $GLOBALS['eZINITextCodecEnabled'] = $codec;
    }

    /*!
     \static
     Check wether a specified parameter in a specified section is set in a specified file
     \param fileName file name (optional)
     \param rootDir directory (optional)
     \param section section name
     \param parameter parameter name
     \return true if the the parameter is set.
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

    /*!
     Tries to load the ini file specified in the constructor or instance() function.
     If cache files should be used and a cache file is found it loads that instead.
     Set \a $reset to false if you don't want to reset internal data.
    */
    function load( $reset = true )
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

    /*!
     Tries to load the ini file placement specified in the constructor or instance() function.
     If cache files should be used and a cache file is found it loads that instead.
     Set \a $reset to false if you don't want to reset internal data.
    */
    function loadPlacement( $reset = true )
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
                $inputFiles[] = $iniFile . '.append';

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
        return md5( $cacheFileName ) . '.php';
    }

    /*!
      \private
      Will load a cached version of the ini file if it exists,
      if not it will parse the original file and create the cache file.
    */
    function loadCache( $reset = true, $placement = false )
    {
        eZDebug::accumulatorStart( 'ini', 'ini_load', 'Load cache' );
        if ( $reset )
            $this->reset();
        $cachedDir = 'var/cache/ini/';
        $inputFileTime = 0;

        if ( self::$checkFileMtime === true or self::$checkFileMtime === $this->FileName )
        {
            eZDebug::accumulatorStart( 'ini_find_files', 'ini_load', 'FindInputFiles' );
            $this->findInputFiles( $inputFiles, $iniFile );
            eZDebug::accumulatorStop( 'ini_find_files' );
            if ( count( $inputFiles ) === 0 )
            {
                eZDebug::accumulatorStop( 'ini' );
                return false;
            }

            $currentTime = time();
            foreach ( $inputFiles as $inputFile )
            {
                $fileTime = filemtime( $inputFile );
                if ( $currentTime < $fileTime )
                    eZDebug::writeError( 'Input file "' . $inputFile . '" has a timestamp higher then current time, ignoring timestamp to avoid infinite recursion!', 'eZINI::loadCache' );
                else if ( $inputFileTime === 0 or
                     $fileTime > $inputFileTime )
                    $inputFileTime = $fileTime;
            }
        }

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

        $loadCache = false;
        $cacheTime = false;
        if ( file_exists( $cachedFile ) )
        {
            $loadCache = true;
            if ( self::$checkFileMtime === true or self::$checkFileMtime === $this->FileName  )
            {
                $cacheTime = filemtime( $cachedFile );
                if ( $cacheTime < $inputFileTime )
                {
                    $loadCache = false;
                }
            }
        }

        $useCache = false;
        if ( $loadCache )
        {
            $useCache = true;
            if ( eZINI::isDebugEnabled() )
                eZDebug::writeNotice( "Loading cache '$cachedFile' for file '" . $this->FileName . "'", "eZINI" );
            $charset = null;
            $blockValues = array();
            include( $cachedFile );
            if ( !isset( $val ) or
                 !isset( $eZIniCacheCodeDate ) or
                 $eZIniCacheCodeDate != eZINI::CACHE_CODE_DATE )
            {
                if ( eZINI::isDebugEnabled() )
                    eZDebug::writeNotice( "Old structure in cache file used, recreating '$cachedFile' to new structure", "eZINI" );
                $this->reset();
                $useCache = false;
            }
            else
            {
                $this->Charset = $charset;
                $this->ModifiedBlockValues = array();
                if ( $placement )
                {
                    $this->BlockValuesPlacement = $val;
                }
                else
                {
                    $this->BlockValues = $val;
                }
                unset( $val );
            }
        }
        if ( !$useCache )
        {
            if ( !isset( $inputFiles ) )
            {
                eZDebug::accumulatorStart( 'ini_find_files', 'ini_load', 'FindInputFiles' );
                $this->findInputFiles( $inputFiles, $iniFile );
                eZDebug::accumulatorStop( 'ini_find_files' );
                if ( count( $inputFiles ) === 0 )
                {
                    eZDebug::accumulatorStop( 'ini' );
                    return false;
                }
            }

            eZDebug::accumulatorStart( 'ini_files_1', 'ini_load', 'Parse' );
            $this->parse( $inputFiles, $iniFile, false, $placement );
            eZDebug::accumulatorStop( 'ini_files_1' );
            eZDebug::accumulatorStart( 'ini_files_2', 'ini_load', 'Save Cache' );
            $cacheSaved = $this->saveCache( $cachedDir, $cachedFile, $placement ? $this->BlockValuesPlacement : $this->BlockValues );
            eZDebug::accumulatorStop( 'ini_files_2' );

            if ( $cacheSaved )
            {
                // Write log message to storage.log
                eZLog::writeStorageLog( $fileName, $cachedDir );
            }
        }

        eZDebug::accumulatorStop( 'ini' );
    }

    /*!
     \private
     Stores the content of the INI object to the cache file \a $cachedFile.
    */
    function saveCache( $cachedDir, $cachedFile, $data )
    {
        if ( !file_exists( $cachedDir ) )
        {
            if ( !eZDir::mkdir( $cachedDir, 0777, true ) )
            {
                eZDebug::writeError( "Couldn't create cache directory $cachedDir, perhaps wrong permissions", "eZINI" );
                return false;
            }
        }
        $tmpCacheFile = $cachedFile . '_' . substr( md5( mt_rand() ), 0, 8 );
        // save the data to a cached file
        $fp = @fopen( $tmpCacheFile, "w" );
        if ( $fp === false )
        {
            eZDebug::writeError( "Couldn't create cache file '$cachedFile', perhaps wrong permissions", "eZINI" );
            return false;
        }
        fwrite( $fp, "<?php\n\$eZIniCacheCodeDate = " . eZINI::CACHE_CODE_DATE . ";\n" );

        if ( $this->Codec )
            fwrite( $fp, "\$charset = \"".$this->Codec->RequestedOutputCharsetCode."\";\n" );
        else
            fwrite( $fp, "\$charset = \"$this->Charset\";\n" );

        fwrite( $fp, "\$val = " . preg_replace( "@\n[\s]+@", '', var_export( $data, true ) ) . ";" );
        fwrite( $fp, "\n?>" );
        fclose( $fp );
        eZFile::rename( $tmpCacheFile, $cachedFile );

        chmod( $cachedFile, self::$filePermission );

        if ( eZINI::isDebugEnabled() )
            eZDebug::writeNotice( "Wrote cache file '$cachedFile'", "eZINI" );

        return true;
    }

    /*!
      \private
      Parses either the override ini file or the standard file and then the append
      override file if it exists.
     */
    function parse( $inputFiles = false, $iniFile = false, $reset = true, $placement = false )
    {
        if ( $reset )
            $this->reset();
        if ( $inputFiles === false or
             $iniFile === false )
            $this->findInputFiles( $inputFiles, $iniFile );

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
        if ( eZINI::isDebugEnabled() )
            eZDebug::writeNotice( "Parsing file '$file'", 'eZINI' );

        $contents = file_get_contents( $file );
        if ( $contents === false )
        {
            eZDebug::writeError( "Failed opening file '$file' for reading", "eZINI" );
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
                eZDebug::accumulatorStop( 'ini_conversion', false, 'INI string conversion' );
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
                        eZDebug::writeError( "Wrong operation on the ini setting array '$varName'", 'eZINI' );
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
        if ( file_exists( $this->CacheFile ) )
            unlink( $this->CacheFile );
        if ( file_exists( $this->PlacementCacheFile ) )
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
            eZDebug::writeError( "Failed opening file '$filePath' for writing", "eZINI" );
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

    /*!
     \return the override directories, if no directories has been set "override" is returned.

    The override directories are returned as an array of arrays. The first
    value in the array is the override directory, the second is a boolean which
    defines if the directory is relative to the rootDir() or not. If the second value
    is false the override dir is relative, true means that the override dir is relative
    to the eZ Publish root directory.
    The third value of the array will contain the identifier of the override, if it exists.
    Identifiers are useful if you want to overwrite the current override setting.
    */
    function overrideDirs()
    {
        if ( $this->UseLocalOverrides == true )
            $dirs =& $this->LocalOverrideDirArray;
        else
            $dirs =& $GLOBALS['eZINIOverrideDirList'];

        if ( !isset( $dirs ) or !is_array( $dirs ) )
            $dirs = array( array( "override", false, false ) );
        return $dirs;
    }

    /*!
     Appends the override directory \a $dir to the override directory list.
     If global dir is set top
    */
    function prependOverrideDir( $dir, $globalDir = false, $identifier = false )
    {
        if ( eZINI::isDebugEnabled() )
            eZDebug::writeNotice( "Changing override dir to '$dir'", "eZINI" );

        if ( $this->UseLocalOverrides == true )
            $dirs =& $this->LocalOverrideDirArray;
        else
            $dirs =& $GLOBALS["eZINIOverrideDirList"];

        if ( !isset( $dirs ) or !is_array( $dirs ) )
            $dirs = array( array( 'override', false, false ) );

        // Check if the override with the current identifier already exists
        $overrideOverwritten = false;
        if ( $identifier !== false )
        {
            foreach ( array_keys( $dirs ) as $dirKey )
            {
                if ( $dirs[$dirKey][2] == $identifier )
                {
                    $dirs[$dirKey][0] = $dir;
                    $dirs[$dirKey][1] = $globalDir;
                    $overrideOverwritten = true;
                }
            }
        }

        if ( $overrideOverwritten == false )
            $dirs = array_merge( array( array( $dir, $globalDir, $identifier ) ), $dirs );

        $this->CacheFile = false;
     }

    /*!
     Appends the override directory \a $dir to the override directory list.
    */
    function appendOverrideDir( $dir, $globalDir = false, $identifier = false )
    {
        if ( eZINI::isDebugEnabled() )
            eZDebug::writeNotice( "Changing override dir to '$dir'", "eZINI" );

        if ( $this->UseLocalOverrides == true )
            $dirs =& $this->LocalOverrideDirArray;
        else
            $dirs =& $GLOBALS["eZINIOverrideDirList"];

        if ( !isset( $dirs ) or !is_array( $dirs ) )
            $dirs = array( 'override', false, false );

        // Check if the override with the current identifier already exists
        $overrideOverwritten = false;
        if ( $identifier !== false )
        {
            foreach ( array_keys( $dirs ) as $dirKey )
            {
                if ( $dirs[$dirKey][2] == $identifier )
                {
                    $dirs[$dirKey][0] = $dir;
                    $dirs[$dirKey][1] = $globalDir;
                    $overrideOverwritten = true;
                }
            }
        }

        if ( $overrideOverwritten == false )
            $dirs[] = array( $dir, $globalDir, $identifier = false );
        $this->CacheFile = false;
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
            eZDebug::writeError( "Undefined group: '$blockName' in " . $this->FileName, "eZINI" );
        else
            eZDebug::writeError( "Undefined variable: '$varName' in group '$blockName' in " . $this->FileName, "eZINI" );
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
            eZDebug::writeError( "Unknown group: '$blockName'", "eZINI" );
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

    function &findSettingPlacement( $path )
    {
        if ( is_array( $path ) && count( $path ) )
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
                $placement = 'siteaccess';
                if ( $exploded[0] == 'extension' )
                    $placement = 'extension:' . $exploded[1];
            }
            break;
            case 6:
            {
                $placement = 'ext-siteaccess:' . $exploded[4];
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
                                                         'SiteURL' => 'http://mysite.com' ) ) );
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

    /*!
     \static
     \return true if the ini file \a $fileName has been loaded yet.
    */
    static function isLoaded( $fileName = 'site.ini', $rootDir = 'settings', $useLocalOverrides = null )
    {
        if ( isset( $GLOBALS["eZINIGlobalInstance-$rootDir-$fileName-$useLocalOverrides"] ) )
            return true;
        return false;
    }

    /*!
      \static
      Returns the current instance of the given .ini file
      If $useLocalOverrides is set to true you will get a copy of the current overrides,
      but changes to the override settings will not be global.
      Direct access is for accessing the filename directly in the specified path. .append and .append.php is automaticly added to filename
      \note Use create() if you need to get a unique copy which you can alter.
    */
    static function instance( $fileName = 'site.ini', $rootDir = 'settings', $useTextCodec = null, $useCache = null, $useLocalOverrides = null, $directAccess = false, $addArrayDefinition = false )
    {
        $globalsKey = "eZINIGlobalInstance-$rootDir-$fileName-$useLocalOverrides";

        if ( !isset( $GLOBALS[$globalsKey] ) ||
             !( $GLOBALS[$globalsKey] instanceof eZINI ) )
        {
            $GLOBALS[$globalsKey] = new eZINI( $fileName, $rootDir, $useTextCodec, $useCache, $useLocalOverrides, $directAccess, $addArrayDefinition );
        }
        return $GLOBALS[$globalsKey];
    }

    /*!
     Fetches the ini file \a $fileName and returns the INI object for it.
     \note This will not use the override system or read cache files, this is a direct fetch from one file.
    */
    static function &fetchFromFile( $fileName, $useTextCodec = null )
    {
        $impl = new eZINI( $fileName, false, $useTextCodec, false, false, true );
        return $impl;
    }

    /*!
     \static
     Get instance siteaccess specific site.ini
     \param siteAccess the site access to get ini for
     \param iniFile the site access to get ini for
     \return eZINI object, or false if not found
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

    /*!
     \static
    */
    static function resetGlobals(  $fileName = 'site.ini', $rootDir = 'settings', $useLocalOverrides = null )
    {
        unset( $GLOBALS["eZINIGlobalInstance-$rootDir-$fileName-$useLocalOverrides"] );
    }

    static function resetAllGlobals()
    {
        foreach ( array_keys( $GLOBALS ) as $key )
        {
            if ( ( $key && strpos( $key, 'eZINIGlobalInstance-' ) === 0  )
                   || $key === 'eZINIOverrideDirList' )
            {
                unset( $GLOBALS[$key] );
            }
        }
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

    /// If \c true then all file loads are done directly on the filename.
    public $DirectAccess;

    /// If \c true empty element will be created in the beginning of array if it is defined in this ini file.
    public $AddArrayDefinition;

    /// If \c true eZINI will check each setting (before saving) for correspondence of settings in site.ini[eZINISetting].ReadonlySettingList
    public $ReadOnlySettingsCheck = true;

}

?>
