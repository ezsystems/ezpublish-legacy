<?php
//
// Definition of ezjscPacker class
//
// Created on: <23-Aug-2007 12:42:08 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ JSCore extension for eZ Publish
// SOFTWARE RELEASE: 1.x
// COPYRIGHT NOTICE: Copyright  (C) 1999-2013 eZ Systems AS
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

/*
 Functions for merging and packing css and javascript files.
 Reduces page load time both in terms of reducing connections from clients
 and bandwidth ( if packing is turned on ).

 Packing has 4 levels:
 0 = off
 1 = merge files
 2 = 1 + remove whitespace & code comments
 3 = 2 + remove more whitespace

 In case of css files, relative image paths will be replaced
 by absolute paths.

 You can also use css / js generators to generate content dynamically.
 This is better explained in ezjscore.ini[ezjscServer]

 buildStylesheetFiles and buildJavascriptFiles functions does not return html, just
 an array of file url's / content (from generators).
*/

class ezjscPacker
{
    /**
     * Constructor
     */
    protected function __construct()
    {
    }

    /**
     * Builds javascript tag(s) based on input files and packing level
     *
     * @param array|string $scriptFiles Either array of file paths, or string with file path
     * @param string $type Should be 'text/javascript'
     * @param string $lang Optionally set to 'Javascript'
     * @param string $charset
     * @param int $packLevel Level of packing, values: 0-3
     * @param bool $indexDirInCacheHash To add index path in cache hash or not
     * @return string Html with generated tags
     */
    static function buildJavascriptTag( $scriptFiles, $type = 'text/javascript', $lang = '', $charset = 'utf-8', $packLevel = 2, $indexDirInCacheHash = true )
    {
        $ret = '';
        $lang = $lang ? ' language="' . $lang . '"' : '';
        $http = eZHTTPTool::instance();
        $useFullUrl = ( isset( $http->UseFullUrl ) && $http->UseFullUrl );
        $packedFiles = ezjscPacker::packFiles( $scriptFiles, 'javascript/', '.js', $packLevel, $indexDirInCacheHash );
        if ( $charset )
            $charset = " charset=\"$charset\"";
        foreach ( $packedFiles as $packedFile )
        {
            // Is this a js file or js content?
            if ( isset( $packedFile[4] ) &&
               ( strpos( $packedFile, 'http://' ) === 0 ||
                 strpos( $packedFile, 'https://' ) === 0 ||
                 strripos( $packedFile, '.js' ) === ( strlen( $packedFile ) -3 ) ) )
            {
                if ( $useFullUrl )
                {
                    $packedFile = $http->createRedirectUrl( $packedFile, array( 'pre_url' => false ) );
                }
                $ret .= "<script$lang type=\"$type\" src=\"$packedFile\"$charset></script>\r\n";
            }
            else
            {
                $ret .=  $packedFile ? "<script$lang type=\"$type\">\r\n$packedFile\r\n</script>\r\n" : '';
            }
        }
        return $ret;
    }

    /**
     * Builds stylesheet tag(s) based on input files and packing level
     *
     * @param array|string $cssFiles Either array of file paths, or string with file path
     * @param string $media Should be media type, normally 'all'
     * @param string $type Should be 'text/css'
     * @param string $rel Should be 'stylesheet'
     * @param int $packLevel Level of packing, values: 0-3
     * @param bool $indexDirInCacheHash To add index path in cache hash or not
     * @return string Html with generated tags
     */
    static function buildStylesheetTag( $cssFiles, $media = 'all', $type = 'text/css', $rel = 'stylesheet', $packLevel = 3, $indexDirInCacheHash = true )
    {
        $ret = '';
        $packedFiles = ezjscPacker::packFiles( $cssFiles, 'stylesheets/', '.css', $packLevel, $indexDirInCacheHash, '_' . $media );
        $http = eZHTTPTool::instance();
        $useFullUrl = ( isset( $http->UseFullUrl ) && $http->UseFullUrl );
        $media = $media && $media !== 'all' ? ' media="' . $media . '"' : '';
        foreach ( $packedFiles as $packedFile )
        {
            // Is this a css file or css content?
            if ( isset( $packedFile[5] ) &&
               ( strpos( $packedFile, 'http://' ) === 0 ||
                 strpos( $packedFile, 'https://' ) === 0 ||
                 strripos( $packedFile, '.css' ) === ( strlen( $packedFile ) -4 ) ) )
            {
                if ( $useFullUrl )
                {
                    $packedFile = $http->createRedirectUrl( $packedFile, array( 'pre_url' => false ) );
                }
                $ret .= "<link rel=\"$rel\" type=\"$type\" href=\"$packedFile\"$media />\r\n";
            }
            else
            {
                $ret .= $packedFile ? "<style type=\"$type\"$media>\r\n$packedFile\r\n</style>\r\n" : '';
            }
        }
        return $ret;
    }

    /**
     * Builds javascript files
     *
     * @param array|string $scriptFiles Either array of file paths, or string with file path
     * @param int $packLevel Level of packing, values: 0-3
     * @param bool $indexDirInCacheHash To add index path in cache hash or not
     * @return array List of javascript files
     */
    static function buildJavascriptFiles( $scriptFiles, $packLevel = 2, $indexDirInCacheHash = true )
    {
        return ezjscPacker::packFiles( $scriptFiles, 'javascript/', '.js', $packLevel, $indexDirInCacheHash );
    }

    /**
     * Builds stylesheet files
     *
     * @param array|string $cssFiles Either array of file paths, or string with file path
     * @param int $packLevel Level of packing, values: 0-3
     * @param bool $indexDirInCacheHash To add index path in cache hash or not
     * @return array List of css files
     */
    static function buildStylesheetFiles( $cssFiles, $packLevel = 3, $indexDirInCacheHash = true )
    {
        return ezjscPacker::packFiles( $cssFiles, 'stylesheets/', '.css', $packLevel, $indexDirInCacheHash, '_all' );
    }

    // static :: gets the cache dir
    protected static function getCacheDir()
    {
        static $cacheDir = null;
        if ( $cacheDir === null )
        {
            $cacheDir = eZSys::cacheDirectory() . '/public/';
        }
        return $cacheDir;
    }

    // static :: gets the www dir
    protected static function getWwwDir()
    {
        static $wwwDir = null;
        if ( $wwwDir === null )
        {
            $wwwDir = eZSys::wwwDir() . '/';
        }
        return $wwwDir;
    }

    // static :: gets the index dir (including index.php and siteaccess name if that is part of url)
    protected static function getIndexDir()
    {
        static $indexDir = null;
        if ( $indexDir === null )
        {
            $indexDir = eZSys::indexDir() . '/';
        }
        return $indexDir;
    }

    /**
     * Merges a collection of files togheter and returns array of paths to the files.
     * js /css content is returned as string if packlevel is 0 and you use a js/ css generator.
     * $fileArray can also be array of array of files, like array(  'file.js', 'file2.js', array( 'file5.js' ) )
     * The name of the cached file is a md5 hash consistant of the file paths
     * of the valid files in $file_array and the packlevel.
     * The whole argument is used instead of file path on js/ css generators in the cache hash.
     *
     * @param array|string $fileArray Either array of file paths, or string with file path
     * @param string $subPath In witch sub path of design folder to look for files.
     * @param string $fileExtension File extension name (for use on cache file)
     * @param int $packLevel Level of packing, values: 0-3
     * @param bool $indexDirInCacheHash To add index path in cache hash or not
     * @param string $filePostName Extra file name part, example "_screen" in case of medai use for css
     *
     * @return array List of css files
     */
    static function packFiles( $fileArray, $subPath = '', $fileExtension = '.js', $packLevel = 2, $indexDirInCacheHash = false, $filePostName = '' )
    {
        if ( !$fileArray )
        {
            return array();
        }
        else if ( !is_array( $fileArray ) )
        {
            $fileArray = array( $fileArray );
        }

        $ezjscINI    = eZINI::instance( 'ezjscore.ini' );
        $bases       = eZTemplateDesignResource::allDesignBases();
        $customHosts = $ezjscINI->variable( 'Packer', 'CustomHosts' );
        $data = array(
            'http'           => array(),
            'www'            => array(),
            'locale'         => array(),
            'cache_name'     => '',
            'cache_hash'     => '',
            'cache_path'     => '',
            'last_modified'  => 0,
            'file_extension' => $fileExtension,
            'file_post_name' => $filePostName,
            'pack_level'     => $packLevel,
            'sub_path'       => $subPath,
            'cache_dir'      => self::getCacheDir(),
            'www_dir'        => self::getWwwDir(),
            'index_dir'      => self::getIndexDir(),
            'custom_host'    => ( isset( $customHosts[$fileExtension] ) ? $customHosts[$fileExtension] : '' ),
        );

        // Only pack files if Packer is enabled and if not set DevelopmentMode is disabled
        if ( $ezjscINI->hasVariable( 'eZJSCore', 'Packer' ) )
        {
            $packerIniValue = $ezjscINI->variable( 'eZJSCore', 'Packer' );
            if ( $packerIniValue === 'disabled' )
                $data['pack_level'] = 0;
            else if ( is_numeric( $packerIniValue ) )
                $data['pack_level'] = (int) $packerIniValue;
        }
        else
        {
            if ( eZINI::instance()->variable( 'TemplateSettings', 'DevelopmentMode' ) === 'enabled' )
            {
                $data['pack_level'] = 0;
            }
        }

        // Needed for image includes to work on ezp installs with mixed access methods (virtualhost + url based setup)
        if ( $indexDirInCacheHash )
        {
            $data['cache_name'] = $data['index_dir'];
        }

        $originalFileArray = $fileArray;
        while ( !empty( $fileArray ) )
        {
            $file = array_shift( $fileArray );

            // if $file is array, concat it to the file array and continue
            if ( $file && is_array( $file ) )
            {
                $fileArray = array_merge( $file, $fileArray );
                continue;
            }
            else if ( !$file )
            {
                continue;
            }
            // if the file name contains :: it is threated as a custom code genarator
            else if ( strpos( $file, '::' ) !== false )
            {
                $server = self::serverCallHelper( explode( '::', $file )  );
                if ( !$server instanceof ezjscServerRouter )
                {
                    continue;
                }

                $fileTime = $server->getCacheTime( $data );

                // Generate content straight away if packing is disabled
                if ( $data['pack_level'] === 0 )
                {
                   $data['www'][] = $server->call( $fileArray );
                }
                // Always generate functions with file_time=-1 (they modify $fileArray )
                // or they return content that should not be part of the cache file
                else if ( $fileTime === -1 )
                {
                    $data['http'][] = $server->call( $fileArray );
                }
                else
                {
                    $data['locale'][]    = $server;
                    $data['cache_name'] .= $file . '_';
                }
                $data['last_modified'] = max( $data['last_modified'], $fileTime );
                continue;
            }
            // is it a http / https url  ?
            else if ( strpos( $file, 'http://' ) === 0 || strpos( $file, 'https://' ) === 0 )
            {
                $data['http'][] = $file;
                continue;
            }
            // is it a http / https url where protocol is selected dynamically  ?
            else if ( strpos( $file, '://' ) === 0 )
            {
                if ( !isset( $protocol ) )
                    $protocol = ( eZSys::isSSLNow() ? 'https' : 'http' );

                $data['http'][] = $protocol . $file;
                continue;
            }
            // is it a absolute path ?
            else if ( strpos( $file, 'var/' ) === 0 )
            {
                if ( substr( $file, 0, 2 ) === '//' || preg_match( "#^[a-zA-Z0-9]+:#", $file ) )
                    $file = '/';
                else if ( strlen( $file ) > 0 &&  $file[0] !== '/' )
                    $file = '/' . $file;

                eZURI::transformURI( $file, true, 'relative' );
                // Get file time and continue if it return false
                $file     = str_replace( '//' . $data['www_dir'], '', '//' . $file );
                $fileTime = file_exists( $file ) ? filemtime( $file ): false;
                $wwwFile  = $data['www_dir'] . $file;
            }
            // or is it a relative path
            else
            {
                // Allow path to be outside subpath if it starts with '/'
                if ( $file[0] === '/' )
                    $file = ltrim( $file, '/' );
                else
                    $file = $subPath . $file;

                $triedFiles = array();
                $match = eZTemplateDesignResource::fileMatch( $bases, '', $file, $triedFiles );
                if ( $match === false )
                {
                    eZDebug::writeWarning( "Could not find: $file", __METHOD__ );
                    continue;
                }
                $file = htmlspecialchars( $match['path'] );
                $fileTime = file_exists( $file ) ? filemtime( $file ): false;
                $wwwFile  = $data['www_dir'] . $file;
            }

            if ( $fileTime === false )
            {
                eZDebug::writeWarning( "Could not get modified time of file: $file", __METHOD__ );
                continue;
            }

            // Calculate last modified time and store in arrays
            $data['last_modified'] = max( $data['last_modified'], $fileTime );
            $data['locale'][]      = $file;
            $data['www'][]         = $wwwFile;
            $data['cache_name']   .= $file . '_';
        }

        if ( $data['pack_level'] === 0 )
        {
            self::$log[] = $data;
            // if packing is disabled, return the valid paths / content we have generated
            return array_merge( $data['http'], $data['www'] );
        }
        else if ( empty($data['locale']) && !empty($data['http']) )
        {
            self::$log[] = $data;
            // return if there are only external scripts and no local files to cache
            return array_merge( $data['http'], $data['www'] );
        }
        else if ( empty($data['locale']) )
        {
            eZDebug::writeWarning( "Could not find any files: " . var_export( $originalFileArray, true ), __METHOD__ );
            return array();
        }

        // See if cahe file exists and if it has expired (only if time is not part of name)
        if ( $ezjscINI->variable( 'Packer', 'AppendLastModifiedTime' ) === 'enabled' )
        {
            $data['cache_hash'] = md5( $data['cache_name'] . $data['pack_level'] ) . '_' . $data['last_modified'] .
                $data['file_post_name'] . $data['file_extension'];
            $data['cache_path'] = $data['cache_dir'] . $subPath . $data['cache_hash'];
            $clusterFileHandler = eZClusterFileHandler::instance( $data['cache_path'] );
            if ( $clusterFileHandler->fileExists( $data['cache_path'] ) )
            {
                $data['http'][] = $data['custom_host'] . $data['www_dir'] . $data['cache_path'];
                self::$log[] = $data;
                return $data['http'];
            }
        }
        else
        {
            $data['cache_hash'] = md5( $data['cache_name'] . $data['pack_level'] ) .
               $data['file_post_name'] . $data['file_extension'];
            $data['cache_path'] = $data['cache_dir'] . $subPath . $data['cache_hash'];
            $clusterFileHandler = eZClusterFileHandler::instance( $data['cache_path'] );
            // Check last modified time and return path to cache file if valid
            if ( $clusterFileHandler->fileExists( $data['cache_path'] ) && $data['last_modified'] <= $clusterFileHandler->mtime( $data['cache_path'] ) )
            {
                $data['http'][] = $data['custom_host'] . $data['www_dir'] . $data['cache_path'];
                self::$log[] = $data;
                return $data['http'];
            }
        }

        // Merge file content and create new cache file
        $content = '';
        $isCSS = $data['file_extension'] === '.css';
        foreach( $data['locale'] as $i => $file )
        {
            // if this is a js / css generator, call to get content
            if ( $file instanceOf ezjscServerRouter )
            {
                $content .= $file->call( $data['locale'] );
                continue;
            }
            else if ( !$file )
            {
                continue;
            }

            // else, get content of normal file
            $fileContent = file_get_contents( $file );

            if ( !trim( $fileContent ) )
            {
                $content .= "/* empty: $file */\r\n";
                continue;
            }

            if ( $isCSS )
            {
                // We need to fix relative background image paths if this is a css file
                $fileContent = ezjscPacker::fixImgPaths( $fileContent, $file );
                // Remove @charset if this is not the first file (some browsers will ignore css after a second occurance of this)
                if ( $i ) $fileContent = preg_replace('/^@charset[^;]+;/i', '', $fileContent);
            }

            $content .= "/* start: $file */\r\n";
            $content .= $fileContent;
            $content .= "\r\n/* end: $file */\r\n\r\n";
        }

        // Pack all files to save bandwidth
        if ( $data['pack_level'] > 1 )
        {
            foreach( $ezjscINI->variable( 'eZJSCore', $isCSS ? 'CssOptimizer' : 'JavaScriptOptimizer' ) as $optimizer )
            {
                if ( is_callable( array( $optimizer, 'optimize' ) ) )
                    $content = call_user_func( array( $optimizer, 'optimize' ), $content, $data['pack_level'] );
                else
                    eZDebug::writeWarning( "Could not call optimizer '{$optimizer}'", __METHOD__ );
            }
        }

        // Save cache file and return path
        $clusterFileHandler->fileStoreContents( $data['cache_path'], $content, 'ezjscore', $isCSS ? 'text/css' : 'text/javascript' );
        $data['http'][] = $data['custom_host'] . $data['www_dir'] . $data['cache_path'];

        self::$log[] = $data;
        return $data['http'];
    }

    /**
     * Fix image paths in css.
     *
     * @param string $fileContent Css string
     * @param string $file File incl path to calculate relative paths from.
     * @return string
     */
    static function fixImgPaths( $fileContent, $file )
    {
        if ( preg_match_all( "/url\(\s*[\'|\"]?([A-Za-z0-9_\-\/\.\\%?&#]+)[\'|\"]?\s*\)/ix", $fileContent, $urlMatches ) )
        {
           $urlMatches = array_unique( $urlMatches[1] );
           $cssPathArray   = explode( '/', $file );
           $wwwDir = self::getWwwDir();
           // Pop the css file name
           array_pop( $cssPathArray );
           $cssPathCount = count( $cssPathArray );
           foreach ( $urlMatches as $match )
           {
               $match = str_replace( '\\', '/', $match );
               $relativeCount = substr_count( $match, '../' );
               // Replace path if it is realtive
               if ( $match[0] !== '/' and strpos( $match, 'http:' ) === false )
               {
                   $cssPathSlice = $relativeCount === 0 ? $cssPathArray : array_slice( $cssPathArray  , 0, $cssPathCount - $relativeCount  );
                   $newMatchPath = $wwwDir;
                   if ( !empty( $cssPathSlice ) )
                   {
                       $newMatchPath .= implode( '/', $cssPathSlice ) . '/';
                   }
                   $newMatchPath .= str_replace( '../', '', $match );
                   $fileContent = str_replace( $match, $newMatchPath, $fileContent );
               }
           }
        }
        return $fileContent;
    }

    /**
     * Helper function to get and validate server functions
     *
     * @param string $strServerCall
     * @return ezjscServerRouter|null
     */
    static function serverCallHelper( $strServerCall )
    {
        $server = ezjscServerRouter::getInstance( $strServerCall );
        if ( !$server instanceof ezjscServerRouter )
        {
            eZDebug::writeError( 'Not a valid ezjscServer function: ' . implode( '::', $strServerCall ), __METHOD__ );
            return null;
        }
        // Make sure the function is present on the class
        if ( !$server->hasFunction() )
        {
            eZDebug::writeError( 'Could not find function: ' . $server->getName() . '()', __METHOD__ );
            return null;
        }
        return $server;
    }

    /**
     * Generate a debug report of packer use
     *
     * @internal
     * @param bool $as_html
     * @return string
     */
    static public function printDebugReport( $as_html = true )
    {
        if ( !eZTemplate::isTemplatesUsageStatisticsEnabled() )
            return '';

        $stats = '';
        if ( $as_html )
        {
            $stats .= '<h3>CSS/JS files loaded with "ezjscPacker" during request:</h3>';
            $stats .= '<table id="ezjscpackerusage" class="debug_resource_usage" title="List of used files, hover over italic text for more info!">';
            $stats .= '<tr><th>Cache</th><th>Type</th><th>Packlevel</th><th>SourceFiles</th></tr>';
        }
        else
        {
            $stats .= "CSS/JS files loaded with 'ezjscPacker' during request\n";
            $stats .= sprintf( "%-40s%-40s%-40s\n", 'Cache', 'Type', 'Packlevel' );
        }

        foreach( self::$log as $data )
        {
            $extension = $data['file_extension'] === '.js' ? 'JS' : 'CSS';
            if ( $as_html )
            {
                $sourceFilesStats = self::printDebugReportFiles( $data, $as_html );
                $cache = $data['cache_path'] === '' ? ''
                                                    : "<span class='debuginfo' title='Full path: {$data['cache_path']}'>{$data['cache_hash']}</span>";
                $stats .= "<tr class='data'><td>{$cache}</td><td>{$extension}</td><td>{$data['pack_level']}</td><td>{$sourceFilesStats}</td></tr>";
            }
            else
            {
                $stats .= sprintf( "%-40s%-40s%-40s\n", $data['cache_hash'], $extension, $data['pack_level'] );
            }
        }

        if ( $as_html )
        {
            $stats .= '</table>';
        }

        return $stats;
    }

    /**
     * Return data for array of files
     *
     * @param array ['locale']
     * @param bool $as_html
     */
    static protected function printDebugReportFiles( array $data )
    {
        $stats = '';
        foreach ( $data['http'] as $i => $file )
        {
            // Skip last if it is cache file
            if ( !isset( $data['http'][$i+1] ) && $data['cache_path'] !== '' )
                break;

            if ( !$file )
                continue;
            else if ( $stats !== '' )
                $stats .= '<br />';

            $stats .= "<span class='debuginfo' title='Served directly from external source(not part of cache file)'>{$file}</span>";
        }

        foreach ( $data['locale'] as $file )
        {
            if ( !$file )
                continue;
            elseif ( $stats !== '' )
                $stats .= '<br />';

            if ( $file instanceOf ezjscServerRouter )
                $stats .= $file->getName();
            else
                $stats .= $file;
        }
        return $stats;
    }

    /**
     * Internal log of all generated files and source files, for use by {@link printDebugReport()}
     *
     * @var array
     */
    protected static $log = array();
}

// Auto append callback on eZDebug to be able to show report on packer use
eZDebug::appendBottomReport( 'ezjscPacker', array( 'ezjscPacker', 'printDebugReport' ) );

?>
