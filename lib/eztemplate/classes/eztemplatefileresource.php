<?php
//
// Definition of eZTemplateFileResource class
//
// Created on: <01-Mar-2002 13:49:18 amos>
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
 \class eZTemplateFileResource eztemplatefileresource.php
 \brief Handles filesystem retrieval of templates.

 Templates are loaded from the disk and returned to the template system.
 The name of the resource is "file:".

 \todo Run the loaded text trough a text codec
 \todo Change the code so that it can be used as basis for other resource
       types, they will resolve the actual filename and pass it on to this class
*/

include_once( "lib/ezi18n/classes/eztextcodec.php" );
include_once( "lib/eztemplate/classes/eztemplatetreecache.php" );

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

    function cacheKey( $uri, $res, $templatePath, &$extraParameters )
    {
        $key = md5( $uri );
        return $key;
    }

    function &cachedTemplateTree( $uri, $res, $templatePath, &$extraParameters )
    {
        $key = $this->cacheKey( $uri, $res, $templatePath, $extraParameters );
//         if ( eZTemplateTreeCache::canRestoreCache( $key ) )
//             eZTemplateTreeCache::restoreCache( $key );
        return eZTemplateTreeCache::cachedTree( $key, $uri, $res, $templatePath, $extraParameters );
    }

    function setCachedTemplateTree( $uri, $res, $templatePath, &$extraParameters, &$root )
    {
        $key = $this->cacheKey( $uri, $res, $templatePath, $extraParameters );
        eZTemplateTreeCache::setCachedTree( $key, $uri, $res, $templatePath, $extraParameters, $root );
//         eZTemplateTreeCache::storeCache( $key );
    }

    /*!
     Loads the template file if it exists, also sets the modification timestamp.
     Returns true if the file exists.
    */
    function handleResource( &$tpl, &$text, &$tstamp, &$path, $method, &$extraParameters )
    {
        if ( !file_exists( $path ) )
            return false;
//         eZDebug::addTimingPoint( "Resource load" );
        $tstamp = filemtime( $path );
        $result = false;
        if ( $method == EZ_RESOURCE_FETCH )
        {
            $fd = fopen( $path, "r" );
            if ( $fd )
            {
                $text = fread( $fd, filesize( $path ) );
                $charset = "utf8";
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
                            if ( $vars[0] == "charset" )
                            {
                                $val = $vars[1];
                                if ( $val[0] == '"' and
                                     strlen( $val ) > 0 and
                                     $val[strlen($val)-1] == '"' )
                                    $val = substr( $val, 1, strlen($val) - 2 );
                                $charset = $val;
                            }
                        }
                    }
                }
                if ( eZTemplate::isDebugEnabled() )
                    eZDebug::writeNotice( "$path, $charset" );
//                 eZDebug::addTimingPoint( "Resource conversion ($charset)" );
                $codec =& eZTextCodec::instance( $charset );
                eZDebug::accumulatorStart( 'templage_resource_conversion', 'template_total', 'String conversion in template resource' );
                $text = $codec->convertString( $text );
                eZDebug::accumulatorStop( 'templage_resource_conversion', 'template_total', 'String conversion in template resource' );
//                 eZDebug::addTimingPoint( "Resource conversion done ($charset)" );
                $result = true;
            }
        }
        else if ( $method == EZ_RESOURCE_QUERY )
            $result = true;
//         eZDebug::addTimingPoint( "Resource load done" );
        return $result;
    }

    /// \privatesection
    /// The name of the resource
    var $Name;
    /// True if the data served from this resource is static, ie it can be cached properly
    var $ServesStaticData;
    /// The cache for templates
    var $TemplateCache;
}

?>
