<?php
//
// Definition of eZTemplateTreeCache class
//
// Created on: <28-Nov-2002 07:44:29 amos>
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

/*! \file eztemplatetreecache.php
*/

/*!
  \class eZTemplateTreeCache eztemplatetreecache.php
  \brief The class eZTemplateTreeCache does

*/

include_once( 'lib/ezutils/classes/ezdebug.php' );

define( 'EZ_TEMPLATE_TREE_CACHE_CODE_DATE', 1038468389 );

class eZTemplateTreeCache
{
    /*!
     Constructor
    */
    function eZTemplateTreeCache()
    {
    }

    function &cacheTable()
    {
        $templateCache =& $GLOBALS['eZTemplateTreeCacheTable'];
        if ( !is_array( $templateCache ) )
            $templateCache = array();
        return $templateCache;
    }

    function &cachedTree( $key, $uri, $res, $templatePath, &$extraParameters )
    {
        $templateCache =& eZTemplateTreeCache::cacheTable();
        $root = null;
        if ( isset( $templateCache[$key] ) )
        {
            $root =& $templateCache[$key]['root'];
            eZDebug::writeDebug( "Cache hit for uri '$uri' with key '$key'", 'eZTemplateTreeCache::cachedTree' );
        }
        else
            eZDebug::writeDebug( "Cache miss for uri '$uri' with key '$key'", 'eZTemplateTreeCache::cachedTree' );
//         else
//         {
//             eZDebug::writeWarning( "Template cache for key '$key', created from uri '$uri', does not exist", 'eZTemplateTreeCache;:cachedTree' );
//         }
        return $root;
    }

    function setCachedTree( $key, $uri, $res, $templatePath, &$extraParameters, &$root )
    {
        if ( $root === null )
            return;
        $templateCache =& eZTemplateTreeCache::cacheTable();
        if ( isset( $templateCache[$key] ) )
        {
            eZDebug::writeWarning( "Template cache for key '$key', created from uri '$uri', already exists", 'eZTemplateTreeCache::setCachedTree' );
        }
        else
        {
            eZDebug::writeDebug( "Setting cache for uri '$uri' with key '$key'", 'eZTemplateTreeCache::setCachedTree' );
            $templateCache[$key] = array();
        }
        $templateCache[$key]['root'] =& $root;
        $templateCache[$key]['info'] = array( 'key' => $key,
                                              'uri' => $uri,
                                              'resource' => $res,
                                              'template_path' => $templatePath,
                                              'resource_parameters' => $extraParameters );
    }

    function storeCache( $key )
    {
        $templateCache =& eZTemplateTreeCache::cacheTable();
        if ( !isset( $templateCache[$key] ) )
        {
            eZDebug::writeWarning( "Template cache for key '$key' does not exist, cannot store cache", 'eZTemplateTreeCache::storeCache' );
            return;
        }
        $internalCharset = eZTextCodec::internalCharset();
        $cacheFileKey = "$key-$internalCharset";
        $cacheFileName = md5( $cacheFileKey ) . '.php';

        $cache =& $templateCache[$key];

        include_once( 'lib/ezutils/classes/ezphpcreator.php' );

        $php = new eZPHPCreator( 'var/cache/template/tree', $cacheFileName );
        $php->addVariable( 'eZTemplateTreeCacheCodeDate', EZ_TEMPLATE_TREE_CACHE_CODE_DATE );
        $php->addSpace();
        $php->addVariable( 'info', $cache['info'] );
        $php->store();
    }
}

?>
