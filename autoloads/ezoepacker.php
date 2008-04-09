<?php
//
// Definition of eZOEPacker class
//
// Created on: <23-Aug-2007 12:42:08 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor MCE
// SOFTWARE RELEASE: 4.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ systems AS
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
 Template operators for merging and packing css and javascript files.
 Reduces page load time both in terms of reducing connections from clients
 and bandwidth ( if packing is turned on ).
 
 Packing has 4 levels: 0=off, 1=merge files, 2= 1 + remove whitespace, 3= 2+ remove more whitespace 
 Will be forced off when template DevelopmentMode is enabled 
 for developer productivity.
 
 Recomended settings are 3 for css and 2 for javascript,
 basicly since the optimizer isn't string safe.
 eg: var str = ' My last name, and my brothers last name are: Olsen'; 
     var str = ' My last name,and my brothers last name are:Olsen';
 
 In case of css files, relative image paths will be replaced
 by absolute paths, only '../images/' paths are supported
 currently.
 
 Function packFiles can be used static from php as well.
*/

//include_once( 'lib/ezfile/classes/ezfile.php' );
//include_once( 'lib/ezutils/classes/ezuri.php' );
//include_once( 'kernel/common/ezoverride.php' );

class eZOEPacker
{
    function eZOEPacker()
    {
    }

    function operatorList()
    {
        return array( 'ezoescript', 'ezoecss' );
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array( 'ezoescript' => array( 'script_array' => array( 'type' => 'array',
                                              'required' => true,
                                              'default' => array() ),
                                           'create_html' => array( 'type' => 'bool',
                                              'required' => false,
                                              'default' => true ),
                                           'type' => array( 'type' => 'string',
                                              'required' => false,
                                              'default' => 'text/javascript' ),
                                           'language' => array( 'type' => 'string',
                                              'required' => false,
                                              'default' => 'javascript' ),
                                           'pack_level' => array( 'type' => 'integer',
                                              'required' => false,
                                              'default' => 2 )),
                      'ezoecss' => array( 'css_array' => array( 'type' => 'array',
                                              'required' => true,
                                              'default' => array() ),
                                        'create_html' => array( 'type' => 'bool',
                                              'required' => false,
                                              'default' => true ),
                                        'media' => array( 'type' => 'string',
                                              'required' => false,
                                              'default' => 'all' ),
                                        'type' => array( 'type' => 'string',
                                              'required' => false,
                                              'default' => 'text/css' ),
                                        'rel' => array( 'type' => 'string',
                                              'required' => false,
                                              'default' => 'stylesheet' ),
                                        'pack_level' => array( 'type' => 'integer',
                                              'required' => false,
                                              'default' => 3 ) ));
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        $ret = '';
        // Do not pack files if developmentMode is enabled
        $ezoeIni = eZINI::instance( 'ezoe.ini' );
        if ( $ezoeIni->variable('EditorSettings', 'DevelopmentMode') === 'enabled' )
        {
            $packLevel = 0;
        }
        else
        {
            $packLevel = (int) $namedParameters['pack_level'];
        }
        
        
        switch ( $operatorName )
        {
            case 'ezoescript':
                {                    
                    $ret = eZOEPacker::buildJavascriptTag($namedParameters['script_array'],
                                                        $namedParameters['create_html'],
                                                        $namedParameters['type'],
                                                        $namedParameters['language'],
                                                        $packLevel );                    
                } break;
            case 'ezoecss':
                {                    
                    $ret = eZOEPacker::buildStylesheetTag($namedParameters['css_array'],
                                                        $namedParameters['create_html'],
                                                        $namedParameters['media'],
                                                        $namedParameters['type'],
                                                        $namedParameters['rel'],
                                                        $packLevel );                    
                } break;
        }
        $operatorValue = $ret;
    }
    
    // static :: Builds the xhtml tag for scripts
    static function buildJavascriptTag( $scriptFiles, $asHtml, $type, $lang, $packLevel )
    {
        $ret = '';
        $packedFiles = eZOEPacker::packFiles( $scriptFiles, 'javascript/', '.js', $packLevel );
        if ( !$asHtml ) return $packedFiles;
        foreach ( $packedFiles as $packedFile )
        {
            // Is this a js file or js content?
            if ( strlen( $packedFile ) > 3 && strripos( $packedFile, '.js' ) === ( strlen( $packedFile ) -3 ) )
                $ret .=  $packedFile ? "<script language=\"$lang\" type=\"$type\" src=\"$packedFile\"></script>\n" : '';
            else
                $ret .=  $packedFile ? "<script language=\"$lang\" type=\"$type\">\n$packedFile\n</script>\n" : '';
        }
        return $ret;
    }
    
    // static :: Builds the xhtml tag for stylesheets
    static function buildStylesheetTag( $cssFiles, $asHtml, $media, $type, $rel, $packLevel )
    {
        $ret = '';
        $packedFiles = eZOEPacker::packFiles( $cssFiles, 'stylesheets/', '_' . $media . '.css', $packLevel );
        if ( !$asHtml ) return $packedFiles;
        foreach ( $packedFiles as $packedFile )
        {
            // Is this a css file or css content?
            if ( strlen( $packedFile ) > 4 && strripos( $packedFile, '.css' ) === ( strlen( $packedFile ) -4 ) )
                $ret .= $packedFile ? "<link rel=\"$rel\" type=\"$type\" href=\"$packedFile\" media=\"$media\" />\n" : '';
            else
                $ret .= $packedFile ? "<style rel=\"$rel\" type=\"$type\" media=\"$media\">\n$packedFile\n</style>\n" : '';
        }
        return $ret;
    }
    
    /* static ::
     Merges a collection of files togheter and returns array of paths to the files.
     The name of the cached file is a md5 hash consistant of the file paths
     of the valid files in $file_array and the highest timestamp of those files
     And if template debug is turned off it also packs the files by removing
     unnecesery whitespace and comments
     */
    static function packFiles( $fileArray, $path = '', $fileExtension = '.js', $packLevel = 2 )
    {
        if ( !$fileArray )
        {
            return array();
        }

        $cacheName = '';
        $lastmodified = 0;
        $validFiles = array();
        $validWWWFiles = array();
        $sys = eZSys::instance();
        $wwwDir = $sys->wwwDir() . '/';
        $ezoeIni = eZINI::instance( 'ezoe.ini' );
        $bases   = eZTemplateDesignResource::allDesignBases();

        while( count( $fileArray ) > 0 )
        {
            $file = array_shift( $fileArray );
            if ( $file && is_array( $file ) )
            {
                $fileArray = array_merge( $fileArray, $file );
                continue;
            }
            else if ( !$file )
            {
                continue;
            }
            else if ( strpos( $file, '::' ) !== false )
            {
                //  check modified time for packer function if it has getCacheTime method
                $args = explode( '::', $file );
                if ( $ezoeIni->hasGroup( 'Packer_' . $args[0] ) )
                {
                    if ( $ezoeIni->hasVariable( 'Packer_' . $args[0], 'File' ) )
                        include_once( $ezoeIni->variable( 'Packer_' . $args[0], 'File' ) );
                    if ( $ezoeIni->hasVariable( 'Packer_' . $args[0], 'Class' ) )
                        $args[0] = $ezoeIni->variable( 'Packer_' . $args[0], 'Class' );
                }
                if ( method_exists( $args[0], 'getCacheTime' ) )
                {
                    $lastmodified  = max( $lastmodified, call_user_func( array( $args[0], 'getCacheTime' ), $args[1] ));
                }
                else if ( !method_exists( $args[0], $args[1] ) )
                {
                    eZDebug::writeWarning( "Could not find function: $args[0]::$args[1]()", "eZOEPacker::packFiles()" );
                    continue;
                }

                $validFiles[] = $args;
                $cacheName   .= $file . '_';
                if ( $packLevel === 0 )
                {
                    // generate content straight away if packing is disabled
                   $functionClass = array_shift( $args );
                   $functionName = array_shift( $args );
                   $validWWWFiles[] = call_user_func( array( $functionClass, $functionName ), $args, $fileExtension );
                }
                continue;
            }

            // is it a absolute path or relative ?
            if ( strpos( $file, 'var/' ) === 0 )
            {
                if ( substr( $file, 0, 2 ) === '//' || preg_match( "#^[a-zA-Z0-9]+:#", $file ) )
                    $file = '/';
                else if ( strlen( $file ) > 0 &&  $file[0] !== '/' )
                    $file = '/' . $file;

                eZURI::transformURI( $file, true, 'relative' );
            }
            else
            {
                $file = $path . $file;
                $triedFiles = array();
                $match = eZTemplateDesignResource::fileMatch( $bases, '', $file, $triedFiles );

                if ( $match === false )
                {
                    eZDebug::writeWarning( "Could not find: $file", "eZOEPacker::packFiles()" );
                    continue;
                }
                $file = htmlspecialchars( $wwwDir . $match['path'] );
            }

            // get file time and abort if it return false
            $file      = str_replace( '//' . $wwwDir, '', '//' . $file );
            $fileTime = @filemtime( $file );

            if ( $fileTime === false )
            {
                eZDebug::writeWarning( "Could not find: $file", "eZOEPacker::packFiles()" );
                continue;
            }

            // calculate last modified file and store
            $lastmodified  = max( $lastmodified, $fileTime);
            $validFiles[] = $file;
            $validWWWFiles[] = $wwwDir . $file;
            $cacheName   .= $file . '_';
        }
        
        if ( $packLevel === 0 ) return $validWWWFiles;

        if ( !$validFiles )
        {
            eZDebug::writeWarning( "Could not find any files: " . var_export( $fileArray, true ), "eZOEPacker::packFiles()" );
            return array();
        }

        // return cache file path if it exists
        $cacheName = md5( $cacheName . $packLevel ) . $fileExtension;
        $cacheDir  = $sys->cacheDirectory() . '/' . $path;

        if ( file_exists( $cacheDir . $cacheName ) )
        {
            if ( $lastmodified <= filemtime( $cacheDir . $cacheName ) )
            {
                return array( $wwwDir . $cacheDir . $cacheName );
            }
        }

        // Merge file content and create new cache file
        $content = '';
        foreach ( $validFiles as $file )
        {

           if ( is_array( $file ) )
           {
               $functionClass = array_shift( $file );
               $functionName = array_shift( $file );
               $content .= call_user_func( array( $functionClass, $functionName ), $file, $fileExtension );
               continue;
           }
        
           $fileContent = @file_get_contents( $file );

           if ( !trim( $fileContent ) )
           {
               $content .= "/* empty: $file */\n";
               continue;
           }

           // we need to fix relative background image paths if this is a css file
           if ( strpos($fileExtension, '.css') !== false &&
                preg_match_all("/url\(\s?[\'|\"]?(.+)[\'|\"]?\s?\)/ix", $fileContent, $urlMatches) )
           {
               $urlMatches = array_unique( $urlMatches[1] );
               $cssPathArray   = explode( '/', $file );
               // pop the css file name
               array_pop( $cssPathArray );
               $cssPathCount   = count( $cssPathArray );
               foreach( $urlMatches as $match )
               {
                   $match = str_replace( array('"', "'"), '', $match );
                   $relCount = substr_count( $match, '../' );
                   if ( $match[0] !== '/' and strpos( $match, 'http:' ) === false )
                   {
                       $cssPathSlice = $relCount === 0 ? $cssPathArray : array_slice( $cssPathArray  , 0, $cssPathCount - $relCount  );
                       $newMatchPath = $wwwDir . implode('/', $cssPathSlice) . '/' . str_replace('../', '', $match);
                       $fileContent = str_replace( $match, $newMatchPath, $fileContent );
                   }
               }
           }

           $content .= "/* start: $file */\n";
           $content .= $fileContent;
           $content .= "\n/* end: $file */\n\n";
        }

        // Pack the file when development mode is turned off to save bandwidth
        // the packer is targeted at being function safe, so not to aggressiv
        if ( $packLevel > 1 )
        {
            // normalize line feeds
            $content = str_replace(array("\r\n", "\r"), "\n", $content);

            // remove multiline comments
            $content = preg_replace('!(?:\n|\s|^)/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);

            // remove whitespace form start and end of line + singelline comment + multiple linefeeds
            $content = preg_replace(array('/\n\s+/', '/\s+\n/', '!\n//.+\n!', '!\n//\n!', '/\n+/'), "\n", $content);                    

            if ( $packLevel > 2 )
            {
                // remove space around ':' and ','
                $content = preg_replace(array('/:\s+/', '/\s+:/'), ':', $content);
                $content = preg_replace(array('/,\s+/', '/\s+,/'), ',', $content);

                // remove unnecesery line breaks
                $content = str_replace(array(";\n", '; '), ';', $content);
                $content = str_replace(array("}\n","\n}", ';}'), '}', $content);
                $content = str_replace(array("{\n", "\n{", '{;'), '{', $content);

                if ( strpos($fileExtension, '.css') !== false )
                {
                    // optimize css
                    $content = str_replace(array(' 0em', ' 0px',' 0pt', ' 0pc'), ' 0', $content);
                    $content = str_replace(array(':0em', ':0px',':0pt', ':0pc'), ':0', $content);
                    $content = str_replace('0 0 0 0', '0', $content);

                    // these should use regex
                    $content = str_replace(array('#ffffff','#FFFFFF'), '#fff', $content);
                    $content = str_replace('#000000', '#000', $content);
                }
                else if ( strpos($fileExtension, '.js') !== false )
                {
                    // optimize javascript ( these are not string safe and the reason
                    // why ezscript() has a default pack level of 1 )
                    $content = str_replace(' )}', ')}', $content);
                    $content = str_replace(array('( ', " (\n"), '(', $content);
                    $content = str_replace(array(' =', '= '), '=', $content);
                    $content = str_replace(array('+ ', ' +'), '+', $content);
                    $content = str_replace(array('- ', ' -'), '-', $content);
                    $content = str_replace(array('< ', ' <'), '<', $content);
                    $content = str_replace(array('> ', ' >'), '>', $content);
                    $content = str_replace( ' var', 'var', $content);
                }
            }
        }

        // save file and return path if sucsessfull
        if( eZFile::create( $cacheName, $cacheDir, $content ) )
        {
            return array( $wwwDir . $cacheDir . $cacheName );
        }

        return array();
    }
}

?>