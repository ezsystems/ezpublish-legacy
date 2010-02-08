<?php
//
// Definition of eZTemplateFileResource class
//
// Created on: <01-Mar-2002 13:49:18 amos>
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

/*!
 \class eZTemplateFileResource eztemplatefileresource.php
 \brief Handles filesystem retrieval of templates.

 Templates are loaded from the disk and returned to the template system.
 The name of the resource is "file:".
*/

class eZTemplateFileResource
{
    /*!
     Initializes with a default resource name "file".
     Also sets whether the resource servers static data files, this is needed
     for the cache system.
    */
    function eZTemplateFileResource( $name = "file", $servesStaticData = true )
    {
        $this->Name = $name;
        $this->ServesStaticData = $servesStaticData;
        $this->TemplateCache = array();
    }

    /*!
     Returns the name of the resource.
    */
    function resourceName()
    {
        return $this->Name;
    }

    /*
     \return true if this resource handler servers static data,
     this means that the data can be cached by the template system.
    */
    function servesStaticData()
    {
        return $this->ServesStaticData;
    }

    function templateNodeTransformation( $functionName, &$node,
                                         $tpl, &$resourceData, $parameters, $namespaceValue )
    {
        if ( $this->Name != 'file' )
            return false;
        $file = $resourceData['template-name'];
        if ( !file_exists( $file ) )
            return false;
        $newNodes = array();
        $newNodes[] = eZTemplateNodeTool::createResourceAcquisitionNode( $resourceData['resource'],
                                                                         $file, $file,
                                                                         eZTemplate::RESOURCE_FETCH, false,
                                                                         $node[4],
                                                                         array(),
                                                                         $namespaceValue );
        return $newNodes;
    }

    /*!
     Generates a unique key string from the input data and returns it.
     The key will be used for storing cached data and retrieving cache files.
     When implementing file resource handlers this key must be reimplemented if
     the current code does not generate correct keys. However most file based
     resource handlers can simple reuse this class.

     Default implementation returns an md5 of the \a $keyData.
    */
    function cacheKey( $keyData, $res, $templatePath, &$extraParameters )
    {
        $key = md5( $keyData );
        return $key;
    }

    /*!
     \return the cached node tree for the selected template.
    */
    function hasCachedProcessTree( $keyData, $uri, $res, $templatePath, &$extraParameters, $timestamp )
    {
        return false;
        $key = $this->cacheKey( $keyData, $res, $templatePath, $extraParameters );
        if ( eZTemplateTreeCache::canRestoreCache( $key, $timestamp, $templatePath ) )
            eZTemplateTreeCache::restoreCache( $key, $templatePath );
        return eZTemplateTreeCache::cachedTree( $key, $uri, $res, $templatePath, $extraParameters );
    }

    /*!
     Sets the cached node tree for the selected template to \a $root.
    */
    function compileTemplate( $tpl, $keyData, $uri, $res, $templatePath, &$extraParameters, &$resourceData )
    {
        $key = $this->cacheKey( $keyData, $res, $templatePath, $extraParameters );
        return eZTemplateCompiler::compileTemplate( $tpl, $key, $resourceData );
    }

    /*!
     Sets the cached node tree for the selected template to \a $root.
    */
    function executeCompiledTemplate( $tpl, &$textElements,
                                      $keyData, $uri, $resourceData, $templatePath,
                                      &$extraParameters, $timestamp,
                                      $rootNamespace, $currentNamespace )
    {
        $key = $this->cacheKey( $keyData, $resourceData, $templatePath, $extraParameters );
        return eZTemplateCompiler::executeCompilation( $tpl, $textElements, $key, $resourceData,
                                                       $rootNamespace, $currentNamespace );
    }

    /*!
     \return \c true if a compiled template exists for the current request.
    */
    function hasCompiledTemplate( $keyData, $uri, &$resourceData, $templatePath, &$extraParameters, $timestamp )
    {
        $key = $this->cacheKey( $keyData, $resourceData, $templatePath, $extraParameters );
        return eZTemplateCompiler::hasCompiledTemplate( $key, $timestamp, $resourceData );
    }

    /*!
     \return \c true if a compiled template can be generated for this request.
    */
    function canCompileTemplate( $tpl, &$resourceData, &$extraParameters )
    {
        return eZTemplateCompiler::isCompilationEnabled();
    }

    /*!
     \return the cached node tree for the selected template.
    */
    function cachedTemplateTree( $keyData, $uri, $res, $templatePath, &$extraParameters, $timestamp )
    {
        $key = $this->cacheKey( $keyData, $res, $templatePath, $extraParameters );
        if ( eZTemplateTreeCache::canRestoreCache( $key, $timestamp, $templatePath ) )
            eZTemplateTreeCache::restoreCache( $key, $templatePath );
        return eZTemplateTreeCache::cachedTree( $key, $uri, $res, $templatePath, $extraParameters );
    }

    /*!
     Sets the cached node tree for the selected template to \a $root.
    */
    function setCachedTemplateTree( $keyData, $uri, $res, $templatePath, &$extraParameters, &$root )
    {
        $key = $this->cacheKey( $keyData, $res, $templatePath, $extraParameters );
        eZTemplateTreeCache::setCachedTree( $key, $uri, $res, $templatePath, $extraParameters, $root );
        eZTemplateTreeCache::storeCache( $key, $templatePath );
    }

    /*!
     Loads the template file if it exists, also sets the modification timestamp.
     Returns true if the file exists.
    */
    function handleResource( $tpl, &$resourceData, $method, &$extraParameters )
    {
        return $this->handleResourceData( $tpl, $this, $resourceData, $method, $extraParameters );
    }

    /*!
     \static
     Reusable function for handling file based loading.
     Call this with the resource handler object in \a $handler.
     It will load the template file and handle any charsets conversion if necessary.
     It will also handle tree node caching if one is found.
    */
    function handleResourceData( $tpl, $handler, &$resourceData, $method, &$extraParameters )
    {
        // &$templateRoot, &$text, &$tstamp, $uri, $resourceName, &$path, &$keyData
        $templateRoot =& $resourceData['root-node'];
        $text =& $resourceData['text'];
        $tstamp =& $resourceData['time-stamp'];
        $uri =& $resourceData['uri'];
        $resourceName =& $resourceData['resource'];
        $path =& $resourceData['template-filename'];
        $keyData =& $resourceData['key-data'];
        $localeData =& $resourceData['locales'];

        if ( !file_exists( $path ) )
            return false;
        $tstamp = filemtime( $path );
        $result = false;
        $canCache = true;
        $templateRoot = null;
        if ( !$handler->servesStaticData() )
            $canCache = false;
        if ( !$tpl->isCachingAllowed() )
            $canCache = false;
        $keyData = 'file:' . $path;
        if ( $method == eZTemplate::RESOURCE_FETCH )
        {
            if ( $canCache )
            {
                if ( $handler->hasCompiledTemplate( $keyData, $uri, $resourceData, $path, $extraParameters, $tstamp ) )
                {
                    $resourceData['compiled-template'] = true;
                    return true;
                }
            }
            if ( $canCache )
                $templateRoot = $handler->cachedTemplateTree( $keyData, $uri, $resourceName, $path, $extraParameters, $tstamp );

            if ( $templateRoot !== null )
                return true;

            if ( is_readable( $path ) )
            {
                $text = file_get_contents( $path );
                $text = preg_replace( "/\n|\r\n|\r/", "\n", $text );
                $tplINI = $tpl->ini();
                $charset = $tplINI->variable( 'CharsetSettings', 'DefaultTemplateCharset' );
                $locales = array();
                $pos = strpos( $text, "\n" );
                if ( $pos !== false )
                {
                    $line = substr( $text, 0, $pos );
                    if ( preg_match( "/^\{\*\?template(.+)\?\*\}/", $line, $tpl_arr ) )
                    {
                        $args = explode( " ", trim( $tpl_arr[1] ) );
                        foreach ( $args as $arg )
                        {
                            $vars = explode( '=', trim( $arg ) );
                            switch ( $vars[0] ) {
                                case 'charset': {
                                    $val = $vars[1];
                                    if ( $val[0] == '"' and
                                         strlen( $val ) > 0 and
                                         $val[strlen($val)-1] == '"' )
                                    {
                                        $val = substr( $val, 1, strlen($val) - 2 );
                                    }
                                    $charset = $val;
                                } break;
                                case 'locale': {
                                    $val = $vars[1];
                                    if ( $val[0] == '"' and
                                         strlen( $val ) > 0 and
                                         $val[strlen($val)-1] == '"' )
                                    {
                                        $val = substr( $val, 1, strlen($val) - 2 );
                                    }
                                    $locales = explode( ',', $val );
                                } break;
                            }
                        }
                    }
                }

                /* Setting locale to allow standard PHP functions to handle
                 * strtoupper/lower() */
                $defaultLocale = trim( $tplINI->variable( 'CharsetSettings', 'DefaultTemplateLocale' ) );
                if ( $defaultLocale != '' )
                {
                    $locales = array_merge( $locales, explode( ',', $defaultLocale ) );
                }
                $localeData = $locales;
                if ( $locales && count( $locales ) )
                {
                    setlocale( LC_CTYPE, $locales );
                }

                if ( eZTemplate::isDebugEnabled() )
                    eZDebug::writeNotice( "$path, $charset" );
                $codec = eZTextCodec::instance( $charset, false, false );
                if ( $codec )
                {
                    eZDebug::accumulatorStart( 'template_resource_conversion', 'template_total', 'String conversion in template resource' );
                    $text = $codec->convertString( $text );
                    eZDebug::accumulatorStop( 'template_resource_conversion', 'template_total', 'String conversion in template resource' );
                }
                $result = true;
            }
        }
        else if ( $method == eZTemplate::RESOURCE_QUERY )
            $result = true;
        return $result;
    }

    /// \privatesection
    /// The name of the resource
    public $Name;
    /// True if the data served from this resource is static, ie it can be cached properly
    public $ServesStaticData;
    /// The cache for templates
    public $TemplateCache;
}

?>
