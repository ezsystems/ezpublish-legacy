<?php
//
// $Id$
//
// Definition of eZINI class
//
// Created on: <12-Feb-2002 14:06:45 bf>
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

/*!
  \class eZINI ezini.php
  \ingroup eZUtils
  \brief Reads and writes .ini style configuration files

  The most common way of using it is.
  \code
  // include the file
  include_once( "classes/ezinifile.php" );

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

include_once 'lib/ezutils/classes/ezdebug.php';

/*!
 Has the date of the current cache code implementation as a timestamp,
 if this changes(increases) the cache files will need to be recreated.
*/
// define( "EZ_INI_CACHE_CODE_DATE", 1043407541 );
define( "EZ_INI_CACHE_CODE_DATE", 1043407542 );
define( "EZ_INI_DEBUG_INTERNALS", false );

class eZINI
{
    /*!
      Initialization of object;
    */
    function eZINI( $fileName, $rootDir = "", $useTextCodec = null, $useCache = null, $useLocalOverrides = null, $directAccess = false, $addArrayDefinition = false )
    {
        $this->Charset = "utf8";
        if ( $fileName == "" )
            $fileName = "site.ini";
        if ( $rootDir !== false && $rootDir == "" )
            $rootDir = "settings";
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

        if ( $this->UseLocalOverrides == true )
        {
            $this->LocalOverrideDirArray = $GLOBALS["eZINIOverrideDirList"];
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
    function isCacheEnabled()
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
    function setIsCacheEnabled( $cache )
    {
        $GLOBALS['eZINICacheEnabled'] = $cache;
    }

    /*!
     \static
     \return true if debugging of internals is enabled, this will display
     which files are loaded and when cache files are created.
      Set the option with setIsDebugEnabled().
    */
    function isDebugEnabled()
    {
        if ( !isset( $GLOBALS['eZINIDebugInternalsEnabled'] ) )
             $GLOBALS['eZINIDebugInternalsEnabled'] = EZ_INI_DEBUG_INTERNALS;
        return $GLOBALS['eZINIDebugInternalsEnabled'];
    }

    /*!
     \static
     Sets whether internal debugging is enabled or not.
    */
    function setIsDebugEnabled( $debug )
    {
        $GLOBALS['eZINIDebugInternalsEnabled'] = $debug;
    }

    /*!
     \static
     \return true if textcodecs is to be used, this will use the eZTextCodec class
             in the eZI18N library for text conversion.
      Set the option with setIsTextCodecEnabled().
    */
    function isTextCodecEnabled()
    {
        if ( !isset( $GLOBALS['eZINITextCodecEnabled'] ) )
             $GLOBALS['eZINITextCodecEnabled'] = true;
        return $GLOBALS['eZINITextCodecEnabled'];
    }

    /*!
     \static
     Sets whether textcodec conversion is enabled or not.
    */
    function setIsTextCodecEnabled( $codec )
    {
        $GLOBALS['eZINITextCodecEnabled'] = $codec;
    }

    /*!
     \static
     Check wether a specified parameter in a specified section is set in a specified file
     \param filename (optional)
     \param directory (optional)
     \param section name
     \param parameter name
     \return true if the the parameter is set.
    */
    function parameterSet( $fileName = 'site.ini', $rootDir = 'settings', &$section, &$parameter )
    {
        if ( !eZINI::exists( $fileName, $rootDir ) )
            return false;

        $iniInstance =& eZINI::instance( $fileName, $rootDir, null, null, null, true );
        return $iniInstance->hasVariable( $section, $parameter );
    }

    /*!
     \static
     \return true if the INI file \a $fileName exists in the root dir \a $rootDir.
     $fileName defaults to site.ini and rootDir to settings.
    */
    function exists( $fileName = "site.ini", $rootDir = "settings" )
    {
        if ( $fileName == "" )
            $fileName = "site.ini";
        if ( $rootDir == "" )
            $rootDir = "settings";
        if ( file_exists( $rootDir . '/' . $fileName ) )
            return true;
        else if ( file_exists( $rootDir . '/' . $fileName . '.append' ) )
            return true;
        else if ( file_exists( $rootDir . '/' . $fileName . '.append.php' ) )
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
     \private
     Looks trough all known settings and override folders to find relevant INI files.
     The result is a list with expanded paths to the files.
     \return the expanded file list.
    */
    function findInputFiles( &$inputFiles, &$iniFile )
    {
        include_once( 'lib/ezfile/classes/ezdir.php' );
        $inputFiles = array();
        if ( $this->RootDir !== false )
            $iniFile = eZDir::path( array( $this->RootDir, $this->FileName ) );
        else
            $iniFile = eZDir::path( array( $this->FileName ) );

        if ( $this->FileName == 'override.ini' )
        {
            eZExtension::prependExtensionSiteAccesses( false, $this, true, false, false );
        }

        if ( file_exists( $iniFile ) )
            $inputFiles[] = $iniFile;

        // try the same file name with '.append.php' replace with '.append'
        if ( preg_match('/^(.+.append).php$/i', $iniFile, $matches ) && file_exists( $matches[1] ) )
            $inputFiles[] = $matches[1];

        if ( file_exists ( $iniFile . '.php' ) )
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
            foreach ( $overrideDirs as $overrideDirItem )
            {
                $overrideDir = $overrideDirItem[0];
                $isGlobal = $overrideDirItem[1];
                if ( $isGlobal )
                    $overrideFile = eZDir::path( array( $overrideDir, $this->FileName ) );
                else
                    $overrideFile = eZDir::path( array( $this->RootDir, $overrideDir, $this->FileName ) );
                if ( file_exists( $overrideFile . '.php' ) )
                {
                    $inputFiles[] = $overrideFile . '.php';
                }
                if ( file_exists( $overrideFile ) )
                    $inputFiles[] = $overrideFile;

                if ( $isGlobal )
                    $overrideFile = eZDir::path( array( $overrideDir, $this->FileName . '.append' ) );
                else
                    $overrideFile = eZDir::path( array( $this->RootDir, $overrideDir, $this->FileName . '.append' ) );
                if ( file_exists( $overrideFile . '.php' ) )
                {
                    $inputFiles[] = $overrideFile . '.php';
                }
                if ( file_exists( $overrideFile ) )
                    $inputFiles[] = $overrideFile;
            }
        }
    }

    /*!
      \private
      Will load a cached version of the ini file if it exists,
      if not it will parse the original file and create the cache file.
    */
    function loadCache( $reset = true )
    {
        eZDebug::accumulatorStart( 'ini', 'ini_load', 'Load cache' );
        if ( $reset )
            $this->reset();
        $cachedDir = "var/cache/ini/";

        $this->findInputFiles( $inputFiles, $iniFile );
        if ( count( $inputFiles ) == 0 )
        {
            eZDebug::accumulatorStop( 'ini' );
            return false;
        }


        $md5_input = '';
        foreach ( $inputFiles as $inputFile )
        {
            $md5_input .= $inputFile. "\n";
        }
        if ( $this->UseTextCodec )
        {
            include_once( "lib/ezi18n/classes/eztextcodec.php" );
            $md5_input .= '-' . eZTextCodec::internalCharset();
        }
        $fileName = md5( $md5_input ) . ".php";
        $cachedFile = $cachedDir . $fileName;
        $this->CacheFile = $cachedFile;

        $inputTime = false;
        // check for modifications
        foreach ( $inputFiles as $inputFile )
        {
            $fileTime = filemtime( $inputFile );
            if ( $inputTime === false or
                 $fileTime > $inputTime )
                $inputTime = $fileTime;
        }

        $loadCache = false;
        $cacheTime = false;
		$fileInfo = @stat( $cachedFile );
        if ( $fileInfo )
        {
            $cacheTime = $fileInfo['mtime'];
            $loadCache = true;
            if ( $cacheTime < $inputTime )
            {
                $loadCache = false;
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
            if ( !isset( $eZIniCacheCodeDate ) or
                 $eZIniCacheCodeDate != EZ_INI_CACHE_CODE_DATE )
            {
                if ( eZINI::isDebugEnabled() )
                    eZDebug::writeNotice( "Old structure in cache file used, recreating '$cachedFile' to new structure", "eZINI" );
                $this->reset();
                $useCache = false;
            }
            else
            {
                $this->Charset = $charset;
                $this->BlockValues = $blockValues;
                //$this->BlockValuesPlacement = array ( 'ClassSettings' => array ( 'Formats' => array ( 0 => "settings/datetime.ini" ) ) );
                if ( isset( $blockValuesPlacement ) )
                    $this->BlockValuesPlacement = $blockValuesPlacement;
                else
                    $this->BlockValuesPlacement = array();
                $this->ModifiedBlockValues = array();
                unset( $blockValues );
            }
        }
        if ( !$useCache )
        {
            $this->parse( $inputFiles, $iniFile, false );
            $this->saveCache( $cachedDir, $cachedFile );

            // Write log message to storage.log
            include_once( 'lib/ezutils/classes/ezlog.php' );
            eZLog::writeStorageLog( $fileName, $cachedDir );
        }

        eZDebug::accumulatorStop( 'ini' );
    }


    /*!
     \private
     Stores the content of the INI object to the cache file \a $cachedFile.
    */
    function saveCache( $cachedDir, $cachedFile )
    {
        if ( !file_exists( $cachedDir ) )
        {
            include_once( 'lib/ezfile/classes/ezdir.php' );
            if ( !eZDir::mkdir( $cachedDir, 0777, true ) )
            {
                eZDebug::writeError( "Couldn't create cache directory $cachedDir, perhaps wrong permissions", "eZINI" );
            }
        }
        // save the data to a cached file
        $buffer = "";
        $i = 0;
        if ( is_array( $this->BlockValues )  )
        {
            $fp = @fopen( $cachedFile, "w+" );
            if ( $fp === false )
            {
                eZDebug::writeError( "Couldn't create cache file '$cachedFile', perhaps wrong permissions", "eZINI" );
                return;
            }
            fwrite( $fp, "<?php\n\$eZIniCacheCodeDate = " . EZ_INI_CACHE_CODE_DATE . ";\n" );
//             exit;
            if ( $this->Codec )
                fwrite( $fp, "\$charset = \"".$this->Codec->RequestedOutputCharsetCode."\";\n" );
            else
                fwrite( $fp, "\$charset = \"$this->Charset\";\n" );
            reset( $this->BlockValues );
            while ( list( $groupKey, $groupVal ) = each ( $this->BlockValues ) )
            {
                reset( $groupVal );
                while ( list( $key, $val ) = each ( $groupVal ) )
                {
                    if ( is_array( $val ) )
                    {
                        fwrite( $fp, "\$groupArray[\"$key\"] = array();\n" );
                        foreach ( $val as $arrayKey => $arrayValue )
                        {
                            if ( is_string( $arrayKey ) )
                                $tmpArrayKey = "\"" . str_replace( "\"", "\\\"", $arrayKey ) . "\"";
                            else
                                $tmpArrayKey = $arrayKey;
                            // Escape ", \ and $
                            $tmpVal = str_replace( "\\", "\\\\", $arrayValue );
                            $tmpVal = str_replace( "$", "\\$", $tmpVal );
                            $tmpVal = str_replace( "\"", "\\\"", $tmpVal );
                            fwrite( $fp, "\$groupArray[\"$key\"][$tmpArrayKey] = \"$tmpVal\";\n" );
                        }
                    }
                    else
                    {
                        // Escape ", \ and $
                        $tmpVal = str_replace( "\\", "\\\\", $val );
                        $tmpVal = str_replace( "$", "\\$", $tmpVal );
                        $tmpVal = str_replace( "\"", "\\\"", $tmpVal );

                        fwrite( $fp, "\$groupArray[\"$key\"] = \"$tmpVal\";\n" );
                    }
                }

                fwrite( $fp, "\$blockValues[\"$groupKey\"] =& \$groupArray;\n" );
                //  fwrite( $fp, "\$blockValuesPlacement[\"$groupKey\"] = datatime.ini" );
                fwrite( $fp, "unset( \$groupArray );\n" );
                $i++;
            }

            if ( is_array( $this->BlockValuesPlacement )  )
            {
                reset( $this->BlockValuesPlacement );
                while ( list( $groupKey, $groupVal ) = each ( $this->BlockValuesPlacement ) )
                {
                    reset( $groupVal );
                    while ( list( $key, $val ) = each ( $groupVal ) )
                    {
                        if ( is_array( $val ) )
                        {
                            fwrite( $fp, "\$groupPlacementArray[\"$key\"] = array();\n" );
                            foreach ( $val as $arrayKey => $arrayValue )
                            {
                                if ( is_string( $arrayKey ) )
                                    $tmpArrayKey = "\"" . str_replace( "\"", "\\\"", $arrayKey ) . "\"";
                                else
                                    $tmpArrayKey = $arrayKey;
                                // Escape ", \ and $
                                $tmpVal = str_replace( "\\", "\\\\", $arrayValue );
                                $tmpVal = str_replace( "$", "\\$", $tmpVal );
                                $tmpVal = str_replace( "\"", "\\\"", $tmpVal );
                                fwrite( $fp, "\$groupPlacementArray[\"$key\"][$tmpArrayKey] = \"$tmpVal\";\n" );
                            }
                        }
                        else
                        {
                            // Escape ", \ and $
                            $tmpVal = str_replace( "\\", "\\\\", $val );
                            $tmpVal = str_replace( "$", "\\$", $tmpVal );
                            $tmpVal = str_replace( "\"", "\\\"", $tmpVal );

                            fwrite( $fp, "\$groupPlacementArray[\"$key\"] = \"$tmpVal\";\n" );
                        }
                    }

                    fwrite( $fp, "\$blockValuesPlacement[\"$groupKey\"] =& \$groupPlacementArray;\n" );
                    //  fwrite( $fp, "\$blockValuesPlacement[\"$groupKey\"] = datatime.ini" );
                    fwrite( $fp, "unset( \$groupPlacementArray );\n" );
                    $i++;
                }
            }
            fwrite( $fp, "\n?>" );
            fclose( $fp );
            if ( eZINI::isDebugEnabled() )
                eZDebug::writeNotice( "Wrote cache file '$cachedFile'", "eZINI" );
        }
//         exit;
    }

    /*!
      \private
      Parses either the override ini file or the standard file and then the append
      override file if it exists.
     */
    function parse( $inputFiles = false, $iniFile = false, $reset = true )
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
                $this->parseFile( $inputFile );
            }
        }
    }

    /*!
      \private
      Will parse the INI file and store the variables in the variable $this->BlockValues
     */
    function parseFile( $file )
    {
        if ( eZINI::isDebugEnabled() )
            eZDebug::writeNotice( "Parsing file '$file'", 'eZINI' );

        include_once( "lib/ezfile/classes/ezfile.php" );
        $lines = eZFile::splitLines( $file );
        if ( $lines === false )
        {
            eZDebug::writeError( "Failed opening file '$file' for reading", "eZINI" );
            return false;
        }

        $currentBlock = "";
        if ( count( $lines ) > 0 )
        {
            // check for charset
            if ( preg_match( "/#\?ini(.+)\?/", $lines[0], $ini_arr ) )
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

//         $codec =& eZTextCodec::codecForName( $this->Charset );
        unset( $this->Codec );
        if ( $this->UseTextCodec )
        {
            include_once( "lib/ezi18n/classes/eztextcodec.php" );
            $this->Codec =& eZTextCodec::instance( $this->Charset, false, false );
        }
        else
            $this->Codec = null;

        foreach ( $lines as $line )
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
            if ( preg_match("#^(\w+)\\[\\]$#", $line, $valueArray ) )
            {
                $varName = trim( $valueArray[1] );

                $valuesPlacement =& $this->BlockValuesPlacement[$currentBlock];

                if ( isset( $valuesPlacement[$varName] ) )
                    if ( !is_array( $valuesPlacement[$varName] ) )
                    {
                        eZDebug::writeError( "Wrong operation on the ini setting array '$varName'", 'eZINI' );
                        continue;
                    }

                $this->BlockValues[$currentBlock][$varName] = array();
                $valuesPlacement[$varName][] = $file;

                // In direct access mode we create empty elements at the beginning of an array
                // in case it is redefined in this ini file. So when we will save it, definition
                // will be created as well.
                if ( $this->AddArrayDefinition )
                {
                    $this->BlockValues[$currentBlock][$varName][] = "";
                }

            }
            else if ( preg_match("#^([a-zA-Z0-9_-]+)(\\[([^\\]]*)\\])?=(.*)$#", $line, $valueArray ) )
            {
                $varName = trim( $valueArray[1] );
                if ( $this->Codec )
                {
                    eZDebug::accumulatorStart( 'ini_conversion', false, 'INI string conversion' );
                    $varValue = $this->Codec->convertString( $valueArray[4] );
                    eZDebug::accumulatorStop( 'ini_conversion', false, 'INI string conversion' );
                }
                else
                {
                    $varValue = $valueArray[4];
                }
//                 $varValue = $codec->toUnicode( $varValue );

                if ( $valueArray[2] )
                {
                    if ( $valueArray[3] )
                    {
                        $keyName = $valueArray[3];
                        if ( isset( $this->BlockValues[$currentBlock][$varName] ) and
                             is_array( $this->BlockValues[$currentBlock][$varName] ) )
                        {
                            $this->BlockValues[$currentBlock][$varName][$keyName] = $varValue;
                            $this->BlockValuesPlacement[$currentBlock][$varName][$keyName] = $file;
                        }
                        else
                        {
                            $this->BlockValues[$currentBlock][$varName] = array( $keyName => $varValue );
                            $this->BlockValuesPlacement[$currentBlock][$varName] = array( $keyName => $file );
                        }
                    }
                    else
                    {

                        if ( isset( $this->BlockValues[$currentBlock][$varName] ) and
                             is_array( $this->BlockValues[$currentBlock][$varName] ) )
                        {
                            $this->BlockValues[$currentBlock][$varName][] = $varValue;
                            $arrayCount = 0;
                            $arrayCount = count( $this->BlockValues[$currentBlock][$varName] );
                            $this->BlockValuesPlacement[$currentBlock][$varName][$arrayCount -1] = $file;
                        }
                        else
                        {
                            $this->BlockValues[$currentBlock][$varName] = array( $varValue );
                            $this->BlockValuesPlacement[$currentBlock][$varName] = array( $file );
                        }
                    }
                }
                else
                {
                    $this->BlockValues[$currentBlock][$varName] = $varValue;
                    $this->BlockValuesPlacement[$currentBlock][$varName] = $file;
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
    }


    /*!
      Saves the file to disk.
      If filename is given the file is saved with that name if not the current name is used.
      If \a $useOverride is true then the file will be placed in the override directory,
      if \a $useOverride is "append" it will append ".append" to the filename.
    */
    function save( $fileName = false, $suffix = false, $useOverride = false,
                   $onlyModified = false, $useRootDir = true, $resetArrays = false )
    {
        include_once( 'lib/ezfile/classes/ezdir.php' );
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
        if ( is_string( $useOverride ) and
             $useOverride == "append" )
            $fileName .= ".append";
        if ( $suffix !== false )
            $fileName .= $suffix;

        /* Try to guess which filename would fit better: 'xxx.apend' or 'xxx.append.php'.
         * We choose 'xxx.append.php' in all cases except when
         * 'xxx.append' exists already and 'xxx.append.php' does not exist.
         */
        if( strstr( $fileName, '.append' ) )
        {
            $fnAppend    = ereg_replace( '\.php$', '', $fileName );
            $fnAppendPhp = $fnAppend.'.php';
            $fpAppend    = eZDir::path( array_merge( $pathArray, $fnAppend    ) );
            $fpAppendPhp = eZDir::path( array_merge( $pathArray, $fnAppendPhp ) );
            $fileName = ( file_exists( $fpAppend ) && !file_exists( $fpAppendPhp ) )
                       ? $fnAppend : $fnAppendPhp;
        }

        $originalFileName = $fileName;
        $backupFileName = $originalFileName . eZSys::backupFilename();
        $fileName .= '.tmp';

        $dirPath = eZDir::path( $dirArray );
        if ( !file_exists( $dirPath ) )
            eZDir::mkdir( $dirPath, octdec( '777' ), true );

        include_once( 'lib/ezfile/classes/ezdir.php' );
        $filePath = eZDir::path( array_merge( $pathArray, $fileName ) );
        $originalFilePath = eZDir::path( array_merge( $pathArray, $originalFileName ) );
        $backupFilePath = eZDir::path( array_merge( $pathArray, $backupFileName ) );

        $fp = @fopen( $filePath, "w+");
        if ( !$fp )
        {
            eZDebug::writeError( "Failed opening file '$filePath' for writing", "eZINI" );
            return false;
        }
        $writeOK = true;
        $written = 0;

        if ( $this->Codec )
            $written = fwrite( $fp, "<?php /* #?ini charset=\"" . $this->Codec->RequestedOutputCharsetCode . "\"?$lineSeparator$lineSeparator" );
        else
            $written = fwrite( $fp, "<?php /* #?ini charset=\"" . $this->Charset . "\"?$lineSeparator$lineSeparator" );
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
                            if ( $resetArrays )
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
            $written = fwrite( $fp, "*/ ?>" );
            if ( $written === false )
                $writeOK = false;
        }
        @fclose( $fp );
        if ( !$writeOK )
        {
            unlink( $filePath );
            return false;
        }

        $siteConfig =& eZINI::instance( 'site.ini' );
        $filePermissions = $siteConfig->variable( 'FileSettings', 'StorageFilePermissions');
        @chmod( $filePath, octdec( $filePermissions ) );

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
    to the eZ publish root directory.
    The third value of the array will contain the identifier of the override, if it exists.
    Identifiers are useful if you want to overwrite the current override setting.
    */
    function overrideDirs()
    {
        if ( $this->UseLocalOverrides == true )
            $dirs =& $this->LocalOverrideDirArray;
        else
            $dirs =& $GLOBALS["eZINIOverrideDirList"];

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
        if ( $this->hasVariable( $blockName, $varName ) )
            $variable = $this->variable( $blockName, $varName );
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
        $ret = false;
        if ( !isset( $this->BlockValues[$blockName] ) )
            eZDebug::writeError( "Undefined group: '$blockName'", "eZINI" );
        else if ( isset( $this->BlockValues[$blockName][$varName] ) )
            $ret = $this->BlockValues[$blockName][$varName];
        else
            eZDebug::writeError( "Undefined variable: '$varName' in group '$blockName'", "eZINI" );

        return $ret;
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
            eZDebug::writeError( "Undefined group: '$blockName'", "eZINI" );
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
        return is_array( $this->BlockValues[$sectionName] );
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
    function &variableArray( $blockName, $varName )
    {
        $ret = $this->variable( $blockName, $varName );
        if ( is_array( $ret ) )
        {
            $arr = array();
            foreach ( $ret as $retItem )
            {
                $arr[] = explode( ";", $retItem );
            }
            $ret = $arr;
        }
        else if ( $ret !== false )
            $ret = explode( ";", $ret );

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
            eZDebug::writeError( "Unknown group: '$origBlockName'", "eZINI" );
            $ret = null;
            return $ret;
        }
        $ret = $this->BlockValues[$blockName];

        return $ret;
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
        return $this->BlockValuesPlacement;
    }

    function &findSettingPlacement( $path )
    {
        if ( is_array( $path ) && count( $path ) )
            $path = $path[0];
        $directoryCount = count( explode( '/', $path ) );

        switch ( $directoryCount )
        {
            case 2:
                $placement = 'default';
            break;
            case 3:
                $placement = 'override';
            break;
            case 4:
                $placement = 'siteaccess';
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
        $this->BlockValues = $groupArray;
    }

    /*!
     Sets multiple variables from the array \a $variables.
     \param $variables Contains an associative array with groups as first key,
                       variable names as second key and variable values as values.
     \code
     $ini->setVariables( array( 'SiteSettings' => array( 'SiteName' => 'mysite',
                                                         'SiteURL' => 'http://mysite.com' ) ) );
     \encode
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
    function isLoaded( $fileName = "site.ini", $rootDir = "settings", $useLocalOverrides = null )
    {
        $isLoaded =& $GLOBALS["eZINIGlobalIsLoaded-$rootDir-$fileName-$useLocalOverrides"];
        if ( !isset( $isLoaded ) )
            return false;
        return $isLoaded;
    }

    /*!
      \static
      Returns the current instance of the given .ini file
      If $useLocalOverrides is set to true you will get a copy of the current overrides,
      but changes to the override settings will not be global.
      Direct access is for accessing the filename directly in the specified path. .append and .append.php is automaticly added to filename
      \note Use create() if you need to get a unique copy which you can alter.
    */
    function &instance( $fileName = "site.ini", $rootDir = "settings", $useTextCodec = null, $useCache = null, $useLocalOverrides = null, $directAccess = false, $addArrayDefinition = false )
    {
        $impl =& $GLOBALS["eZINIGlobalInstance-$rootDir-$fileName-$useLocalOverrides"];
        $isLoaded =& $GLOBALS["eZINIGlobalIsLoaded-$rootDir-$fileName-$useLocalOverrides"];

        $class = get_class( $impl );
        if ( $class != "ezini" )
        {
            $isLoaded = false;

            $impl = new eZINI( $fileName, $rootDir, $useTextCodec, $useCache, $useLocalOverrides, $directAccess, $addArrayDefinition );

            $isLoaded = true;
        }
        return $impl;
    }

    /*!
     Fetches the ini file \a $fileName and returns the INI object for it.
     \note This will not use the override system or read cache files, this is a direct fetch from one file.
    */
    function &fetchFromFile( $fileName, $useTextCodec = null )
    {
        $impl = new eZINI( $fileName, false, $useTextCodec, false, false, true );
        return $impl;
    }

    /*!
      \static
      Similar to instance() but will always create a new copy.
    */
    function &create( $fileName = "site.ini", $rootDir = "settings", $useTextCodec = null, $useCache = null, $useLocalOverrides = null )
    {
        $impl = new eZINI( $fileName, $rootDir, $useTextCodec, $useCache, $useLocalOverrides );
        return $impl;
    }

    /// \privatesection
    /// The charset of the ini file
    var $Charset;

    /// Variable to store the textcodec.
    var $Codec;

    /// Variable to store the ini file values.
    var $BlockValues;

    /// Variable to store the setting placement (which file is the setting in).
    var $BlockValuesPlacement;

    /// Variable to store whether variables are modified or not
    var $ModifiedBlockValues;

    /// Stores the filename
    var $FileName;

    /// The root of all ini files
    var $RootDir;

    /// Whether to use the text codec when reading the ini file or not
    var $UseTextCodec;

    /// Stores the path and filename of the cache file
    var $CacheFile;

    /// true if cache should be used
    var $UseCache;

    /// true if the overrides should only be changed locally
    var $UseLocalOverrides;

    /// Contains the override dirs, if in local mode
    var $LocalOverrideDirArray;

    /// If \c true then all file loads are done directly on the filename.
    var $DirectAccess;

    /// If \c true empty element will be created in the beginning of array if it is defined in this ini file.
    var $AddArrayDefinition;
}

?>
