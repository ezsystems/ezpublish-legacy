<?php
//
// $Id$
//
// Definition of eZINI class
//
// Created on: <12-Feb-2002 14:06:45 bf>
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

/*!
 Has the date of the current cache code implementation as a timestamp,
 if this changes(increases) the cache files will need to be recreated.
*/
define( "EZ_INI_CACHE_CODE_DATE", 1035973879 );
define( "EZ_INI_DEBUG_INTERNALS", false );

class eZINI
{
    /*!
      Initialization of object;
     */
    function eZINI( $fileName, $rootDir = "", $useTextCodec = null, $useCache = null )
    {
        $this->Charset = "utf8";
        if ( $fileName == "" )
            $fileName = "site.ini";
        if ( $rootDir == "" )
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
        $this->FileName = $fileName;
        $this->RootDir = $rootDir;
        $this->UseCache = $useCache;

        $this->load();
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
        $siteBasics = $GLOBALS['eZSiteBasics'];
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
     \return true if the INI file \a $fileName exists in the root dir \a $rootDir.
     $fileName defaults to site.ini and rootDir to settings.
    */
    function exists( $fileName = "site.ini", $rootDir = "settings" )
    {
        if ( $fileName == "" )
            $fileName = "site.ini";
        if ( $rootDir == "" )
            $rootDir = "settings";
        return file_exists( $rootDir . '/' . $fileName );
    }

    function isLoaded( $fileName = "site.ini", $rootDir = "settings" )
    {
        $isLoaded =& $GLOBALS["eZINIGlobalIsLoaded-$rootDir-$fileName"];
        if ( !isset( $isLoaded ) )
            return false;
        return $isLoaded;
    }

    /*!
      \static
      Returns the current instance of the given .ini file
    */
    function &instance( $fileName = "site.ini", $rootDir = "settings", $useTextCodec = null, $useCache = null )
    {
        $impl =& $GLOBALS["eZINIGlobalInstance-$rootDir-$fileName"];
        $isLoaded =& $GLOBALS["eZINIGlobalIsLoaded-$rootDir-$fileName"];

        $class =& get_class( $impl );
        if ( $class != "ezini" )
        {
            $isLoaded = false;
            $impl = new eZINI( $fileName, $rootDir, $useTextCodec, $useCache );
            $isLoaded = true;
        }
        return $impl;
    }

    /*!
     Tries to load the ini file specified in the constructor or instance() function.
     If cache files should be used and a cache file is found it loads that instead.
     Set \a $reset to false if you don't want to reset internal data.
    */
    function load( $reset = true )
    {
        if ( $reset )
            $this->reset();
        if ( $this->UseCache )
        {
            $this->loadCache();
        }
        else
        {
            $this->parse();
        }
    }

    /*!
      \private
      Will load a cached version of the ini file if it exists,
      if not it will parse the original file and create the cache file.
    */
    function loadCache()
    {
        $cachedDir = "var/cache/ini/";
        if ( !file_exists( $cachedDir ) )
        {
            include_once( 'lib/ezutils/classes/ezdir.php' );
            if ( ! eZDir::mkdir( $cachedDir, 0777, true ) )
            {
                eZDebug::writeError( "Couldn't create cache directory $cachedDir, perhaps wrong permissions", "eZINI" );
            }
        }
        $override_dir = $this->overrideDir();
        $overrideName = $this->RootDir . "/$override_dir/" . $this->FileName;
        $appendName = $this->RootDir . "/$override_dir/" . $this->FileName . ".append";
        $override_file = "";
        if ( file_exists( $appendName ) )
            $override_file = $appendName;
        else if ( file_exists( $overrideName ) )
            $override_file = $overrideName;
        $md5_input = ( $override_dir . $override_file .
                       realpath( $this->RootDir . "/" . $this->FileName ) );
        if ( $this->UseTextCodec )
        {
            include_once( "lib/ezi18n/classes/eztextcodec.php" );
            $md5_input .= eZTextCodec::internalCharset();
        }
        $cachedFile = $cachedDir . md5( $md5_input ) . ".php";
        $this->CacheFile = $cachedFile;

        // check for modifications
        if ( file_exists( $this->RootDir . "/" . $this->FileName . ".php" ) )
            $origTime = filemtime( $this->RootDir . "/" . $this->FileName . ".php" );
        else
            $origTime = filemtime( $this->RootDir . "/" . $this->FileName );
//        $overrideTime = filemtime( "override/" . $this->FileName );
//        $appendTime = filemtime( "override/" . $this->FileName . ".append" );

        $loadCache = false;
        if ( file_exists( $cachedFile ) )
        {
            $cacheTime = filemtime( $cachedFile );
            $loadCache = true;
            if ( $cacheTime < $origTime )
            {
                $loadCache = false;
            }
//             if ( file_exists( $overrideName ) )
//             {
//                 $overrideTime = filemtime( $overrideName );
//                 print( "\$overrideTime=$overrideTime<br/>" );
//             }
            if ( file_exists( $overrideName ) and
                 $cacheTime < filemtime( $overrideName ) )
            {
                $loadCache = false;
            }
//             if ( file_exists( $appendName ) )
//             {
//                 $appendTime = filemtime( $appendName );
//                 print( "\$appendTime=$appendTime<br/>" );
//             }
            if ( file_exists( $appendName ) and
                 $cacheTime < filemtime( $appendName ) )
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
            $orderedBlockValues = array();
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
                $this->OrderedBlockValues = $orderedBlockValues;
                unset( $blockValues );
                unset( $orderedBlockValues );
            }
        }
        if ( !$useCache )
        {
            $this->parse();
            $this->saveCache( $cachedFile );
        }
    }


    /*!
     \private
     Stores the content of the INI object to the cache file \a $cachedFile.
    */
    function saveCache( $cachedFile )
    {
        // save the data to a cached file
        $buffer = "";
        $i = 0;
        if ( is_array( $this->BlockValues ) and is_array( $this->OrderedBlockValues ) )
        {
            $fp = @fopen( $cachedFile, "w+" );
            if ( $fp === false )
            {
                eZDebug::writeError( "Couldn't create cache file '$cachedFile', perhaps wrong permissions", "eZINI" );
                return;
            }
            fwrite( $fp, "<?php\n\$eZIniCacheCodeDate = " . EZ_INI_CACHE_CODE_DATE . ";\n" );

            fwrite( $fp, "\$charset = \"$this->Charset\";\n" );
            reset( $this->BlockValues );
            while ( list( $groupKey, $groupVal ) = each ( $this->BlockValues ) )
            {
                reset( $groupVal );
                fwrite( $fp, "\$groupArray = array();\n\$orderedGroupArray = array();\n" );
                while ( list( $key, $val ) = each ( $groupVal ) )
                {
                    $tmpVal = str_replace( "\"", "\\\"", $val );

                    fwrite( $fp, "\$groupArray[\"$key\"] = \"$tmpVal\";\n" );
                }
                while ( list( $key, $val ) = each ( $this->OrderedBlockValues[$groupKey] ) )
                {
                    $tmpKey = $val[0];
                    $tmpVal = str_replace( "\"", "\\\"", $val[1] );

                    fwrite( $fp, "\$orderedGroupArray[] = array(\"$tmpKey\", \"$tmpVal\");\n" );
                }

                fwrite( $fp, "\$blockValues[\"$groupKey\"] =& \$groupArray;\n" );
                fwrite( $fp, "\$orderedBlockValues[\"$groupKey\"] =& \$orderedGroupArray;\n" );
                fwrite( $fp, "unset( \$groupArray );\nunset( \$orderedGroupArray );\n" );
                $i++;
            }
            fwrite( $fp, "\n?>" );
            fclose( $fp );
            if ( eZINI::isDebugEnabled() )
                eZDebug::writeNotice( "Wrote cache file '$cachedFile'", "eZINI" );
        }
    }

    /*!
      \private
      Parses either the override ini file or the standard file and then the append
      override file if it exists.
     */
    function &parse( $file = false )
    {
        if ( $file == false )
            $file = $this->FileName;
        $override_dir = $this->overrideDir();
        $overrideName = $this->RootDir . "/$override_dir/" . $file;
        if ( file_exists( $overrideName . ".php" ) )
        {
            $this->parseFile( $overrideName . ".php" );
        }
        else if ( file_exists( $overrideName ) )
        {
            $this->parseFile( $overrideName );
        }
        else
        {
            $filePath = $this->RootDir . "/" . $file;
            if ( file_exists( $filePath . ".php" ) )
                $this->parseFile( $filePath . ".php" );
            else if ( file_exists( $filePath ) )
                $this->parseFile( $filePath );

            $appendName = $this->RootDir . "/$override_dir/" . $file . ".append";
            if ( file_exists( $appendName . ".php" ) )
                $this->parseFile( $appendName . ".php" );
            else if ( file_exists( $appendName ) )
                $this->parseFile( $appendName );
        }
    }

    /*!
      \private
      Will parse the INI file and store the variables in the variable $this->BlockValues
     */
    function &parseFile( $file )
    {
        if ( eZINI::isDebugEnabled() )
            eZDebug::writeNotice( "Parsing file '$file'", 'eZINI' );
        include_once( "lib/ezutils/classes/ezfile.php" );
        $lines =& eZFile::splitLines( $file );
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
        if ( $this->UseTextCodec )
        {
            include_once( "lib/ezi18n/classes/eztextcodec.php" );
            $codec =& eZTextCodec::instance( $this->Charset );
        }
        foreach ( $lines as $line )
        {
            if ( preg_match( "/^#.*/", $line, $regs ) )
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
            }

            // check for variable
            if ( preg_match("#^(\w+)=(.*)$#", $line, $valueArray ) )
            {
                 $varName = trim( $valueArray[1] );
                if ( $this->UseTextCodec )
                {
                    eZDebug::accumulatorStart( 'ini_conversion', false, 'INI string conversion' );
                    $varValue = $codec->convertString( $valueArray[2] );
                    eZDebug::accumulatorStop( 'ini_conversion', false, 'INI string conversion' );
                }
                else
                {
                    $varValue = $valueArray[2];
                }
//                 $varValue = $codec->toUnicode( $varValue );

                $this->BlockValues[$currentBlock][$varName] = $varValue;
                $this->OrderedBlockValues[$currentBlock][] = array( $varName,
                                                                    $varValue );
            }
        }

        return $ret;
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
    function &save( $fileName = false, $suffix = false, $useOverride = false )
    {
        $sep = eZSys::lineSeparator();
        $pathArray = array();
        if ( $fileName === false )
            $fileName = $this->FileName;
        $pathArray[] = $this->RootDir;
        if ( $useOverride )
            $pathArray[] = $this->overrideDir();
        if ( is_string( $useOverride ) and
             $useOverride == "append" )
            $fileName .= ".append";
        if ( $suffix !== false )
            $fileName .= $suffix;
        $originalFileName = $fileName;
        $backupFileName = $originalFileName . eZSys::backupFilename();
        $fileName .= '.tmp';

        include_once( 'lib/ezutils/classes/ezdir.php' );
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
        $written = fwrite( $fp, "<?php /* #?ini charset=\"" . $this->Charset . "\"?$sep$sep" );
        if ( $written === false )
            $writeOK = false;
        $i = 0;
        if ( $writeOK )
        {
            foreach( array_keys( $this->BlockValues ) as $blockName )
            {
                $written = 0;
                if ( $i > 0 )
                    $written = fwrite( $fp, "$sep" );
                if ( $written === false )
                {
                    $writeOK = false;
                    break;
                }
                $written = fwrite( $fp, "[$blockName]$sep" );
                if ( $written === false )
                {
                    $writeOK = false;
                    break;
                }
                foreach( array_keys( $this->BlockValues[$blockName] ) as $blockVariable )
                {
                    $varKey = $blockVariable;
                    $varValue = $this->BlockValues[$blockName][$blockVariable];
                    $written = fwrite( $fp, "$varKey=$varValue$sep" );
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

        if ( file_exists( $backupFileName ) )
            unlink( $backupFileName );
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
        $this->OrderedBlockValues = array();
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
     \return the override directory, if no directory has been set "override" is returned.

     The override directory is relative to the rootDir().
    */
    function overrideDir()
    {
        $dir =& $GLOBALS["eZINIOverrideDir"];
        if ( !isset( $dir ) or !is_string( $dir ) )
            $dir = "override";
        return $dir;
    }

    /*!
     Sets the override directory to $dir.
    */
    function setOverrideDir( $dir )
    {
        if ( eZINI::isDebugEnabled() )
            eZDebug::writeNotice( "Changing override dir to '$dir'", "eZINI" );
        $GLOBALS["eZINIOverrideDir"] = $dir;
    }

    /*!
      Reads a variable from the ini file and puts it in the parameter \a $variable.
      \note \a $variable is not modified if the variable does not exist
    */
    function &assign( $blockName, $varName, &$variable )
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
    function &variable( $blockName, $varName )
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
      Checks if a variable is set. Returns true if the variable exists, false if not.
    */
    function &hasVariable( $blockName, $varName )
    {
        return isSet( $this->BlockValues[$blockName][$varName] );
    }

    /*!
      Reads a variable from the ini file. The variable
      will be returned as an array. ; is used as delimiter.
     */
    function &variableArray( $blockName, $varName )
    {
        $ret = $this->variable( $blockName, $varName );
        if ( $ret !== false )
            $ret =& explode( ";", $ret );

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
    function &group( $blockName, $ordered = false )
    {
        if ( $ordered )
        {
            if ( !isset( $this->OrderedBlockValues[$blockName] ) )
            {
                eZDebug::writeError( "Unknown group: '$origBlockName'", "eZINI" );
                return null;
            }
            $ret = $this->OrderedBlockValues[$blockName];
        }
        else
        {
            if ( !isset( $this->BlockValues[$blockName] ) )
            {
                eZDebug::writeError( "Unknown group: '$origBlockName'", "eZINI" );
                return null;
            }
            $ret = $this->BlockValues[$blockName];
        }

        return $ret;
    }

    /*!
      Sets an INI file variable.
    */
    function &setVariable( $blockName, $varName, $varValue )
    {
        $this->BlockValues[$blockName][$varName] = $varValue;
    }

    /*!
      Returns BlockValues, which is a nicely named Array
    */
    function getNamedArray()
    {
        return $this->BlockValues;
    }

    /// \privatesection
    /// The charset of the ini file
    var $Charset;

    /// Variable to store the ini file values.
    var $BlockValues;

    /// Variable to store the ini file values in ordered form.
    var $OrderedBlockValues;

    /// Stores the filename
    var $FileName;

    /// The root of all ini files
    var $RootDir;

    /// Whether to use the text codec when reading the ini file or not
    var $UseTextCodec;

    /// Stores the path and filename of the cache file
    var $CacheFile;
}

?>
