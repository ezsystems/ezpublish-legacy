<?php
//
// Definition of eZContentCache class
//
// Created on: <12-Dec-2002 16:53:41 amos>
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

/*! \file ezcontentcache.php
*/

/*!
  \class eZContentCache ezcontentcache.php
  \brief The class eZContentCache does

*/

include_once( 'lib/ezutils/classes/ezsys.php' );
include_once( 'lib/ezutils/classes/ezdir.php' );

class eZContentCache
{
    /*!
     Constructor
    */
    function eZContentCache()
    {
    }

    function cachePathInfo( $siteDesign, $nodeID, $viewMode, $language, $offset, $roleList, $discountList )
    {
        $md5Input = array( $nodeID );
        $md5Input[] = $offset;
        sort( $roleList );
        $md5Input = array_merge( $md5Input, $roleList );
        sort( $discountList );
        $md5Input = array_merge( $md5Input, $discountList );
        $md5Text = md5( implode( '-', $md5Input ) );
        $cacheFile = $nodeID . '-' . $md5Text . '.php';
        $extraPath = eZDir::filenamePath( "$nodeID" );
        $ini =& eZINI::instance();
        $cacheDir = eZDir::path( array( eZSys::cacheDirectory(), $ini->variable( 'ContentSettings', 'CacheDir' ), $siteDesign, $viewMode, $language, $extraPath ) );
        $cachePath = eZDir::path( array( $cacheDir, $cacheFile ) );
        return array( 'dir' => $cacheDir,
                      'file' => $cacheFile,
                      'path' => $cachePath );
    }

    function exists( $siteDesign, $nodeID, $viewMode, $language, $offset, $roleList, $discountList )
    {
        $cachePathInfo = eZContentCache::cachePathInfo( $siteDesign, $nodeID, $viewMode, $language, $offset, $roleList, $discountList );
//         eZDebug::writeDebug( $cachePathInfo );
        return file_exists( $cachePathInfo['path'] );
    }

    function restore( $siteDesign, $nodeID, $viewMode, $language, $offset, $roleList, $discountList )
    {
        $result = array();
        $cachePathInfo = eZContentCache::cachePathInfo( $siteDesign, $nodeID, $viewMode, $language, $offset, $roleList, $discountList );
        $cacheDir = $cachePathInfo['dir'];
        $cacheFile = $cachePathInfo['file'];
        include_once( 'lib/ezutils/classes/ezphpcreator.php' );
        $php = new eZPHPCreator( $cacheDir, $cacheFile );
        $values =& $php->restore( array( 'content_info' => 'contentInfo',
                                         'content_path' => 'contentPath',
                                         'content_data' => 'contentData' ) );

        $result['content'] = $values['content_data'];
        if ( isset( $values['content_path'] ) )
            $result['path'] = $values['content_path'];
        return $result;
    }

    function store( $siteDesign, $nodeID, $viewMode, $language, $offset, $roleList, $discountList,
                    $result )
    {
        $cachePathInfo = eZContentCache::cachePathInfo( $siteDesign, $nodeID, $viewMode, $language, $offset, $roleList, $discountList );
        $cacheDir = $cachePathInfo['dir'];
        $cacheFile = $cachePathInfo['file'];
        include_once( 'lib/ezutils/classes/ezphpcreator.php' );
        $php = new eZPHPCreator( $cacheDir, $cacheFile );
        $contentInfo = array( 'site_design' => $siteDesign,
                              'node_id' => $nodeID,
                              'view_mode' => $viewMode,
                              'language' => $language,
                              'offset' => $offset,
                              'role_list' => $roleList,
                              'discount_list' => $discountList );
        $php->addVariable( 'contentInfo', $contentInfo );
        if ( isset( $result['path'] ) )
        {
            $php->addVariable( 'contentPath', $result['path'] );
        }
        $php->addSpace();
        $php->addCodePiece( "ob_start();\n" );
        $php->addText( $result['content'] );
        $php->addCodePiece( "\$contentData = ob_get_contents();\n" );
        $php->addCodePiece( "ob_end_clean();\n" );
        return $php->store();
    }

    function cleanup( $siteDesign, $nodeList )
    {
        $ini =& eZINI::instance();
        $cacheBaseDir = eZDir::path( array( eZSys::cacheDirectory(), $ini->variable( 'ContentSettings', 'CacheDir' ), $siteDesign ) );
        $viewModes = $ini->variableArray( 'ContentSettings', 'CachedViewModes' );
        $languages = $ini->variableArray( 'ContentSettings', 'TranslationList' );
//         eZDebug::writeDebug( $viewModes, 'viewmodes' );
//         eZDebug::writeDebug( $languages, 'languages' );
//         eZDebug::writeDebug( $nodeList, 'nodeList' );
        foreach ( $viewModes as $viewMode )
        {
            foreach ( $languages as $language )
            {
                foreach ( $nodeList as $nodeID )
                {
                    $extraPath = eZDir::filenamePath( "$nodeID" );
                    $cacheDir = eZDir::path( array( $cacheBaseDir, $viewMode, $language, $extraPath ) );
//                     eZDebug::writeDebug( $cacheDir, 'cacheDir' );
                    if ( !file_exists( $cacheDir ) )
                        continue;
//                     eZDebug::writeDebug( "$cacheDir exists", 'cacheDir' );
                    $dir = opendir( $cacheDir );
                    if ( !$dir )
                        continue;
                    while ( ( $file = readdir( $dir ) ) !== false )
                    {
                        if ( $file == '.' or
                             $file == '..' )
                            continue;
                        if ( preg_match( "/^$nodeID" . "-.*\\.php$/", $file ) )
                        {
                            $cacheFile = eZDir::path( array( $cacheDir, $file ) );
                            eZDebug::writeNotice( "Removing cache file '$cacheFile'", 'eZContentCache::cleanup' );
                            unlink( $cacheFile );
                        }
                    }
                    closedir( $dir );
                }
            }
        }
    }

}

?>
