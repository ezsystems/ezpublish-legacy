<?php
//
// Definition of eZPackageHandler class
//
// Created on: <23-Jul-2003 12:34:55 amos>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezpackagehandler.php
*/

/*!
  \class eZPackageHandler ezpackagehandler.php
  \brief Maintains eZ publish packages

*/

include_once( 'lib/ezxml/classes/ezxml.php' );


define( 'EZ_PACKAGE_VERSION', '3.2-1' );
define( 'EZ_PACKAGE_DEVELOPMENT', true );

class eZPackageHandler
{
    /*!
     Constructor
    */
    function eZPackageHandler( $parameters = array() )
    {
        $timestamp = mktime();
        $packaging = array( 'timestamp' => $timestamp,
                            'host' => $_SERVER['HOSTNAME'],
                            'packager' => false );
        $release = array( 'version' => array( 'number' => false,
                                              'release' => false ),
                          'timestamp' => false,
                          'licence' => false,
                          'state' => false,
                          'dependencies' => array( 'file-lists' => array() ),
                          'provides' => array() );
        $install = array( 'pre' => array(),
                          'post' => array() );
        $uninstall = array( 'pre' => array(),
                            'post' => array() );
        $defaults = array( 'name' => false,
                           'summary' => false,
                           'description' => false,
                           'vendor' => false,
                           'priority' => false,
                           'type' => false,
                           'extension' => false,
                           'maintainers' => array(),
                           'packaging' => $packaging,
                           'source' => false,
                           'documents' => array(),
                           'groups' => array(),
                           'changelog' => array(),
                           'release' => $release,
                           'install' => $install,
                           'uninstall' => $uninstall );
        $this->Parameters = array_merge( $defaults, $parameters );
    }

    function &create( $name, $parameters = array() )
    {
        $parameters['name'] = $name;
        $handler =& new eZPackageHandler( $parameters );
        return $handler;
    }

    function attribute( $attributeName, $attributeList = false )
    {
        $attributeValue = null;
        if ( array_key_exists( $attributeName, $this->Parameters ) )
            $attributeValue = $this->Parameters[$attributeName];
        if ( is_array( $attributeList ) )
        {
            foreach ( $attributeList as $attributeKey )
            {
                if ( !is_array( $attributeValue ) or
                     !array_key_exists( $attributeValue, $attributeKey ) )
                    break;
                $attributeValue = $attributeValue[$attributeKey];
            }
        }
        return $attributeValue;
    }

    function appendMaintainer( $name, $email, $role = false )
    {
        $this->Parameters['maintainers'][] = array( 'name' => $name,
                                                    'email' => $email,
                                                    'role' => $role );
    }

    function appendDocument( $name, $mimeType = false, $os = false, $audience = false )
    {
        if ( !$mimeType )
            $mimeType = 'text/plain';
        $this->Parameters['documents'][] = array( 'name' => $name,
                                                  'mime-type' => $mimeType,
                                                  'os' => $os,
                                                  'audience' => $audience );
    }

    function appendGroup( $name )
    {
        $this->Parameters['groups'][] = array( 'name' => $name );
    }

    function appendChange( $person, $email, $changes )
    {
        $timestamp = mktime();
        if ( !is_array( $changes ) )
            $changes = array( $changes );
        $this->Parameters['changelog'][] = array( 'timestamp' => $timestamp,
                                                  'person' => $person,
                                                  'email' => $email,
                                                  'changes' => $changes );
    }

    function appendFileList( $files, $role = false, $subDirectory = false,
                             $parameters = false )
    {
        $this->Parameters['release']['provides']['file-lists'][] = array( 'files' => $files,
                                                                          'role' => $role,
                                                                          'sub-directory' => $subDirectory,
                                                                          'parameters' => $parameters );
    }

    function appendInstall( $type, $name, $os = false, $pre = true,
                            $parameters = false )
    {
        $installEntry = array( 'type' => $type,
                               'name' => $name,
                               'os' => $os,
                               'parameters' => $parameters );
        $prePost = 'pre';
        if ( !$pre )
            $prePost = 'post';
        $this->Parameters['install'][$prePost][] = $installEntry;
    }

    function setPackager( $timestamp = false, $host = false, $packager = false )
    {
        if ( $timestamp )
            $this->Parameters['packaging']['timestamp'] = $timestamp;
        if ( $host )
            $this->Parameters['packaging']['host'] = $host;
        if ( $packager )
            $this->Parameters['packaging']['packager'] = $packager;
    }

    function setRelease( $version = false, $release = false, $timestamp = false,
                         $licence = false, $state = false )
    {
        if ( $version )
            $this->Parameters['release']['version']['number'] = $version;
        if ( $release )
            $this->Parameters['release']['version']['release'] = $release;
        if ( $timestamp )
            $this->Parameters['release']['timestamp'] = $timestamp;
        if ( $licence )
            $this->Parameters['release']['licence'] = $licence;
        if ( $state )
            $this->Parameters['release']['state'] = $state;
    }

    function &domStructure()
    {
        $dom = new eZDOMDocument();
        $root = $dom->createElementNode( 'package', array( 'version' => EZ_PACKAGE_VERSION,
                                                           'development' => ( EZ_PACKAGE_DEVELOPMENT ? 'true' : 'false' ) ) );
        $root->appendAttribute( $dom->createAttributeNode( 'ezpackage', 'http://ez.no/ezpackage', 'xmlns' ) );
        $dom->setRoot( $root );

        $name = $this->attribute( 'name' );
        $summary = $this->attribute( 'summary' );
        $description = $this->attribute( 'description' );
        $vendor = $this->attribute( 'vendor' );
        $priority = $this->attribute( 'priority' );
        $type = $this->attribute( 'type' );
        $extension = $this->attribute( 'extension' );
        $maintainers = $this->attribute( 'maintainers' );
        $packaging = $this->attribute( 'packaging' );
        $source = $this->attribute( 'source' );
        $documents = $this->attribute( 'documents' );
        $groups = $this->attribute( 'groups' );
        $release = $this->attribute( 'release' );
        $install = $this->attribute( 'install' );
        $uninstall = $this->attribute( 'uninstall' );

        $root->appendChild( $dom->createElementTextNode( 'name', $name ) );
        if ( $summary )
            $root->appendChild( $dom->createElementTextNode( 'summary', $summary ) );
        if ( $description )
            $root->appendChild( $dom->createElementTextNode( 'description', $description ) );
        if ( $vendor )
            $root->appendChild( $dom->createElementTextNode( 'vendor', $vendor ) );
        if ( $priority )
            $root->appendChild( $dom->createElementNode( 'priority', array( 'value' => $priority ) ) );
        if ( $type )
            $root->appendChild( $dom->createElementNode( 'type', array( 'value' => $type ) ) );
        if ( $extension )
            $root->appendChild( $dom->createElementNode( 'extension', array( 'name' => $extension ) ) );

        $ezpublishNode =& $dom->createElementNode( 'ezpublish' );
        $ezpublishNode->appendAttribute( $dom->createAttributeNode( 'ezpublish', 'http://ez.no/ezpublish', 'xmlns' ) );
        include_once( 'lib/version.php' );
        $ezpublishNode->appendChild( $dom->createElementTextNode( 'version', eZPublishSDK::version( true ) ) );
        $ezpublishNode->appendChild( $dom->createElementTextNode( 'named-version', eZPublishSDK::version( false, true ) ) );
        $root->appendChild( $ezpublishNode );

        if ( count( $maintainers ) > 0 )
        {
            $maintainersNode =& $dom->createElementNode( 'maintainers' );
            $maintainersNode->appendAttribute( $dom->createAttributeNode( 'ezmaintainer', 'http://ez.no/ezpackage', 'xmlns' ) );
            foreach ( $maintainers as $maintainer )
            {
                $maintainerNode =& $dom->createElementNode( 'maintainer' );
                $maintainerNode->appendChild( $dom->createElementTextNode( 'name', $maintainer['name'] ) );
                $maintainerNode->appendChild( $dom->createElementTextNode( 'email', $maintainer['email'] ) );
                if ( $maintainer['role'] )
                    $maintainerNode->appendChild( $dom->createElementTextNode( 'role', $maintainer['role'] ) );
                $maintainersNode->appendChild( $maintainerNode );
            }
            $root->appendChild( $maintainersNode );
        }

        $packagingNode =& $dom->createElementNode( 'packaging' );
        $packagingNode->appendAttribute( $dom->createAttributeNode( 'ezpackaging', 'http://ez.no/ezpackage', 'xmlns' ) );
        $packagingNode->appendChild( $dom->createElementTextNode( 'timestamp', $packaging['timestamp'] ) );
        $packagingNode->appendChild( $dom->createElementTextNode( 'host', $packaging['host'] ) );
        if ( $packaging['packager'] )
            $packagingNode->appendChild( $dom->createElementTextNode( 'packager', $packaging['packager'] ) );
        $root->appendChild( $packagingNode );

//         $root->appendChild( $dom->createElementNode( 'signature' ) );

        if ( count( $documents ) > 0 )
        {
            $documentsNode =& $dom->createElementNode( 'documents' );
            foreach ( $documents as $document )
            {
                $documentNode =& $dom->createElementTextNode( 'document', $document['name'],
                                                              array( 'mime-type' => $document['mime-type'] ) );
                if ( $document['os'] )
                    $documentNode->appendAttribute( $dom->createAttributeNode( 'os', $document['os'] ) );
                if ( $document['audience'] )
                    $documentNode->appendAttribute( $dom->createAttributeNode( 'audience', $document['audience'] ) );
                $documentsNode->appendChild( $documentNode );
            }
            $root->appendChild( $documentsNode );
        }

        if ( count( $groups ) > 0 )
        {
            $groupsNode =& $dom->createElementNode( 'groups' );
            foreach ( $groups as $group )
            {
                $groupNode =& $dom->createElementNode( 'group', array( 'name' => $group['name'] ) );
                $groupsNode->appendChild( $groupNode );
            }
            $root->appendChild( $groupsNode );
        }

        if ( count( $changelogs ) > 0 )
        {
            $changelogsNode =& $dom->createElementNode( 'changelogs' );
            $changelogsNode->appendAttribute( $dom->createAttributeNode( 'ezchangelog', 'http://ez.no/ezpackage', 'xmlns' ) );
            foreach ( $changelogs as $changelog )
            {
                $changelogNode =& $dom->createElementNode( 'entry' );
                $changelogNode->appendAttribute( $dom->createAttributeNode( 'timestamp', $changelog['timestamp'] ) );
                $changelogNode->appendAttribute( $dom->createAttributeNode( 'person', $changelog['person'] ) );
                $changelogNode->appendAttribute( $dom->createAttributeNode( 'email', $changelog['email'] ) );
                foreach ( $changelog['changes'] as $change )
                {
                    $changelogNode->appendAttribute( $dom->createAttributeTextNode( 'change', $change ) );
                }
                $changelogsNode->appendChild( $changelogNode );
            }
            $root->appendChild( $changelogsNode );
        }

        $releaseNode =& $dom->createElementNode( 'release' );
        $versionNode =& $dom->createElementNode( 'version' );
        $versionNode->appendAttribute( $dom->createAttributeNode( 'ezversion', 'http://ez.no/ezpackage', 'xmlns' ) );
        $versionNode->appendChild( $dom->createElementTextNode( 'number', $release['version']['number'] ) );
        $versionNode->appendChild( $dom->createElementTextNode( 'release', $release['version']['release'] ) );
        $releaseNode->appendChild( $versionNode );
        if ( !$release['timestamp'] )
            $release['timestamp'] = mktime();
        $releaseNode->appendChild( $dom->createElementTextNode( 'timestamp', $release['timestamp'] ) );
        if ( $release['licence'] )
            $releaseNode->appendChild( $dom->createElementTextNode( 'licence', $release['licence'] ) );
        if ( $release['state'] )
            $releaseNode->appendChild( $dom->createElementTextNode( 'state', $release['state'] ) );

        $providesNode =& $dom->createElementNode( 'provides' );
        $providesNode->appendAttribute( $dom->createAttributeNode( 'ezprovision', 'http://ez.no/ezpackage', 'xmlns' ) );
        foreach ( $release['provides']['file-lists'] as $fileList )
        {
            $fileListNode =& $dom->createElementNode( 'file-list' );
            if ( $fileList['role'] )
                $fileListNode->appendAttribute( $dom->createAttributeNode( 'role', $fileList['role'] ) );
            if ( $fileList['sub-directory'] )
                $fileListNode->appendAttribute( $dom->createAttributeNode( 'sub-directory', $fileList['sub-directory'] ) );
            foreach ( $fileList['parameters'] as $parameterName => $parameterValue )
            {
                $fileListNode->appendAttribute( $dom->createAttributeNode( $parameterName, $parameterValue ) );
            }
            $providesNode->appendChild( $fileListNode );
            foreach ( $fileList['files'] as $file )
            {
                $fileNode =& $dom->createElementNode( 'file', array( 'name' => $file['name'] ) );
                if ( $file['role'] )
                    $fileNode->appendAttribute( $dom->createAttributeNode( 'role', $file['role'] ) );
                if ( $file['sub-directory'] )
                    $fileNode->appendAttribute( $dom->createAttributeNode( 'sub-directory', $file['sub-directory'] ) );
                if ( $file['md5sum'] )
                    $fileNode->appendAttribute( $dom->createAttributeNode( 'md5sum', $file['md5sum'] ) );
                $fileListNode->appendChild( $fileNode );
            }
        }
        $releaseNode->appendChild( $providesNode );

        $installNode =& $dom->createElementNode( 'install' );
        $installNode->appendAttribute( $dom->createAttributeNode( 'ezinstall', 'http://ez.no/ezpackage', 'xmlns' ) );
        $installPreNode =& $dom->createElementNode( 'pre' );
        $installNode->appendChild( $installPreNode );
        $installPostNode =& $dom->createElementNode( 'post' );
        $installNode->appendChild( $installPostNode );
        $this->handleInstallPart( $installPreNode, $dom, $install['pre'] );
        $this->handleInstallPart( $installPostNode, $dom, $install['post'] );

        $releaseNode->appendChild( $installNode );

        $root->appendChild( $releaseNode );

        return $dom;
    }

    function handleInstallPart( &$installNode, &$dom, $list )
    {
        foreach ( $list as $item )
        {
            $type = $item['type'];
            switch ( $type )
            {
                case 'run':
                {
                } break;
                case 'database':
                {
                } break;
                case 'part':
                {
                    if ( $item['parameters']['content'] )
                    {
                        $content = $item['parameters']['content'];
                        $partNode =& $dom->createElementNode( 'part' );
                        if ( $item['os'] )
                            $partNode->appendAttribute( $dom->createAttributeNode( 'os', $item['os'] ) );
                        if ( $item['name'] )
                            $partNode->appendAttribute( $dom->createAttributeNode( 'name', $item['name'] ) );
                        if ( get_class( $content ) == 'ezdomnode' )
                        {
                            $partContentNode =& $content;
                        }
                        $partNode->appendChild( $partContentNode );
                        $installNode->appendChild( $partNode );
                    }
                } break;
                case 'operation':
                {
                } break;
            }
        }
    }

    function toString()
    {
        $dom =& $this->domStructure();
        $string = $dom->toString();
        return $string;
    }

    function store( $filename )
    {
        $file = fopen( $filename, 'w' );
        if ( $file )
        {
            $data = $this->toString();
            fwrite( $file, $data );
            fclose( $file );
            return true;
        }
        return false;
    }
}

?>
