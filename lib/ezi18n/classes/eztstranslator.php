<?php
//
// Definition of eZTSTranslator class
//
// Created on: <07-Jun-2002 12:40:42 amos>
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

/*! \file eztstranslator.php
*/

/*!
  \class eZTSTranslator eztstranslator.php
  \ingroup eZTranslation
  \brief This provides internationalization using XML (.ts) files

*/

include_once( "lib/ezi18n/classes/eztranslatorhandler.php" );
include_once( "lib/ezi18n/classes/eztextcodec.php" );
include_once( "lib/ezi18n/classes/eztranslationcache.php" );

class eZTSTranslator extends eZTranslatorHandler
{
    /*!
     Construct the translator and loads the translation file $file if it is set and exists.
    */
    function eZTSTranslator( $file = null, $root = false, $useCache = true )
    {
        $this->UseCache = $useCache;
        $this->eZTranslatorHandler( true );

        if ( $root === false )
            $root = "share/translations";
        $this->File = $file;
        $this->RootDir = $root;
        $this->Messages = array();
        $this->CachedMessages = array();

        if ( $file !== null )
            $this->load( $this->File );
    }

    /*!
     \static
     \return true if the translation file exists.
    */
    function exists( $file, $root = false )
    {
        if ( $root === false )
            $root = "share/translations";
        $path = "$root/$file";
        return file_exists( $path );
    }

    function &initialize( $file, $root = false, $useCache = true )
    {
        $tables =& $GLOBALS["eZTSTranslationTables"];
        if ( isset( $tables[$root][$file] ) and get_class( $tables[$root][$file] ) == "eztstranslator" )
            return $tables[$root][$file];
        $translator = null;
//         if ( eZTSTranslator::exists( $file, $root ) )
//         {
        $translator = new eZTSTranslator( $file, $root, $useCache );
        $tables[$root][$file] =& $translator;
        $man =& eZTranslatorManager::instance();
        $man->registerHandler( $translator );
//         }
        return $translator;
    }

    function load( $file )
    {
        $root = $this->rootDir();
        $override = eZTSTranslator::overrideDir();
        include_once( 'lib/ezutils/classes/ezdir.php' );
        $path = eZDir::path( array( $root, '/', $file ) );
        if ( !file_exists( $path ) )
        {
            eZDebug::writeError( "Could not load translation file: $path", "eZTSTranslator::load" );
            return false;
        }

        // Load cached translations if possible
        if ( $this->UseCache == true )
        {
            $tsTimeStamp = filemtime( $path );
            $key = 'cachecontexts';
            if ( eZTranslationCache::canRestoreCache( $key, $tsTimeStamp ) )
            {
                eZTranslationCache::restoreCache( $key );
                $contexts = eZTranslationCache::contextCache( $key );
                if ( !is_array( $contexts ) )
                    $contexts = array();
                foreach ( $contexts as $context_name )
                {
                    if ( eZTranslationCache::canRestoreCache( $context_name, $tsTimeStamp ) )
                    {
                        eZTranslationCache::restoreCache( $context_name );
                        $this->CachedMessages[$context_name] =
                             eZTranslationCache::contextCache( $context_name );

                        foreach ( $this->CachedMessages[$context_name] as $key => $msg )
                        {
                            $this->Messages[$key] = $msg;
                        }
                    }
                }
                eZDebug::writeNotice( "Loading cached translation", "eZTSTranslator::load" );
                return true;
            }
            eZDebug::writeNotice( "Translation cache has expired. Will rebuild it from source.",
                                  "eZTSTranslator::load" );
        }

        $fd = fopen( $path, "r" );
        $trans_xml = fread( $fd, filesize( $path ) );
        fclose( $fd );

        include_once( "lib/ezxml/classes/ezxml.php" );
        $xml = new eZXML();

        $schema_xml = '<xsd:schema xmlns:xsd="http://www.w3.org/2001/XMLSchema"
xmlns="http://www.w3.org/2001/XMLSchema/default">

<xsd:annotation>
  <xsd:documentation xml:lang="en">
   Translation message schema for ez.no.
   Copyright 2002 ez.no. All rights reserved.
  </xsd:documentation>
 </xsd:annotation>

 <xsd:element name="TS" type="TranslateRootType"/>

 <xsd:complexType name="TranslateRootType">
  <xsd:sequence>
   <xsd:element name="context" type="ContextType"/>
  </xsd:sequence>
 </xsd:complexType>

 <xsd:complexType name="ContextType">
  <xsd:sequence>
   <xsd:element name="name" type="xsd:string" />
   <xsd:element name="message" type="MessageType"/>
  </xsd:sequence>
 </xsd:complexType>

 <xsd:complexType name="MessageType">
  <xsd:sequence>
   <xsd:element name="source"      type="xsd:string"/>
   <xsd:element name="translation" type="TranslationType"/>
   <xsd:element name="comment"     type="xsd:string" minOccurs="0" maxOccurs="1"/>
  </xsd:sequence>
 </xsd:complexType>

 <xsd:simpleType name="TranslationType">
  <xsd:restriction base="xsd:string">
  </xsd:restriction>
  <xsd:attribute name="type" type="xsd:string" />
 </xsd:simpleType>

</xsd:schema>';

        $tree =& $xml->domTree( $trans_xml );

//         include_once( "lib/ezxml/classes/ezschema.php" );

//         $schema = new eZSchema( );
//         $schema->setSchema( $schema_xml );

//         $schema->validate( $tree );

        $root =& $tree->Root;
        $children =& $root->Children;
        foreach( $children as $child )
        {
            if ( $child->type() == 1 )
            {
                if ( $child->name() == "context" )
                {
                    $context =& $child;
                    $this->handleContextNode( $context );
                }
                else
                    eZDebug::writeError( "Unknown element name: " . $child->name(),
                                         "eZTSTranslator::load" );
            }
            else
                eZDebug::writeError( "Unknown DOMnode type: " . $child->type(),
                                     "eZTSTranslator::load" );
        }

        // Save translation cache
        if ( $this->UseCache == true )
        {
            if ( eZTranslationCache::contextCache( 'cachecontexts' ) == null )
            {
                eZTranslationCache::setContextCache( 'cachecontexts',
                                                     array_keys( $this->CachedMessages ) );
                eZTranslationCache::storeCache( 'cachecontexts' );
            }

            foreach ( $this->CachedMessages as $context_name => $context )
            {
                if ( eZTranslationCache::contextCache( $context_name ) == null )
                    eZTranslationCache::setContextCache( $context_name, $context );
                eZTranslationCache::storeCache( $context_name );
            }
        }

        return true;
    }

    function handleContextNode( &$context )
    {
        $context_name = null;
        $messages = array();
        $context_children =& $context->children();
        foreach( $context_children as $context_child )
        {
            if ( $context_child->type() == 1 )
            {
                if ( $context_child->name() == "name" )
                {
                    $name_el = $context_child->children();
                    if ( count( $name_el ) > 0 )
                    {
                        $name_el = $name_el[0];
                        $context_name = $name_el->content();
                    }
                }
                else if ( $context_child->name() == "message" )
                {
                    $messages[] = $context_child;
                }
                else
                    eZDebug::writeError( "Unknown element name: " . $context_child->name(),
                                         "eZTSTranslator::handleContextNode" );
            }
            else
                eZDebug::writeError( "Unknown DOMnode type: " . $context_child->type(),
                                     "eZTSTranslator::handleContextNode" );
        }
        if ( $context_name === null )
        {
            eZDebug::writeError( "No context name found, skipping context",
                                 "eZTSTranslator::handleContextNode" );
            return false;
        }

        foreach( $messages as $message )
        {
            $this->handleMessageNode( $context_name, $message );
        }
        return true;
    }

    function handleMessageNode( $context_name, &$message )
    {
        $source = null;
        $translation = null;
        $comment = null;
        $message_children =& $message->children();
        $codec =& eZTextCodec::instance( "utf8" );
        foreach( $message_children as $message_child )
        {
            if ( $message_child->type() == 1 )
            {
                if ( $message_child->name() == "source" )
                {
                    $source_el = $message_child->children();
                    $source_el = $source_el[0];
                    $source = $source_el->content();
                }
                else if ( $message_child->name() == "translation" )
                {
                    $translation_el = $message_child->children();
                    if ( count( $translation_el ) > 0 )
                    {
                        $translation_el = $translation_el[0];
                        $translation = $translation_el->content();
                        $translation = $codec->convertString( $translation );
                    }
                }
                else if ( $message_child->name() == "comment" )
                {
                    $comment_el = $message_child->children();
                    $comment_el = $comment_el[0];
                    $comment = $comment_el->content();
                }
                else
                    eZDebug::writeError( "Unknown element name: " . $message_child->name(),
                                         "eZTSTranslator::handleMessageNode" );
            }
            else
                eZDebug::writeError( "Unknown DOMnode type: " . $message_child->type(),
                                     "eZTSTranslator::handleMessageNode" );
        }
        if ( $source === null )
        {
            eZDebug::writeError( "No source name found, skipping message",
                                 "eZTSTranslator::handleMessageNode" );
            return false;
        }
        if ( $translation === null )
        {
//             eZDebug::writeError( "No translation, skipping message", "eZTSTranslator::messageNode" );
            return false;
        }
        $this->insert( $context_name, $source, $translation, $comment );
        return true;
    }

    function setRootDir( $dir )
    {
        $this->RootDir = $dir;
    }

    function rootDir()
    {
        return $this->RootDir;
    }

    /*!
     \static
     \return the override directory, if no directory has been set "override" is returned.

     The override directory is relative to the rootDir().
    */
    function overrideDir()
    {
        $dir =& $GLOBALS["eZTSTranslatorOverrideDir"];
        if ( !isset( $dir ) or !is_string( $dir ) )
            $dir = "override";
        return $dir;
    }

    /*!
     Sets the override directory to $dir.
    */
    function setOverrideDir( $dir )
    {
        $GLOBALS["eZTSTranslatorOverrideDir"] = $dir;
    }

    /*!
     \reimp
    */
    function &findKey( $key )
    {
        $msg = null;
        if ( isset( $this->Messages[$key] ) )
        {
            $msg =& $this->Messages[$key];
        }
        return $msg;
    }

    /*!
     \reimp
    */
    function &findMessage( $context, $source, $comment = null )
    {
        // First try with comment,
        $man =& eZTranslatorManager::instance();
        $key = $man->createKey( $context, $source, $comment );

        if ( !isset( $this->Messages[$key] ) )
        {
            // then try without comment for general translation
            $key = $man->createKey( $context, $source );
        }
        $msg =& $this->findKey( $key );
        return $msg;
    }

    /*!
     \reimp
    */
    function &keyTranslate( $key )
    {
        $msg =& $this->findKey( $key );
        if ( $msg !== null )
            return $msg["translation"];
        else
            return null;
    }

    /*!
     \reimp
    */
    function &translate( $context, $source, $comment = null )
    {
        $msg =& $this->findMessage( $context, $source, $comment );
        if ( $msg !== null )
            return $msg["translation"];
        else
//             eZDebug::writeWarning( "abc" );
            return null;
    }

    /*!
     Inserts the \a $translation for the \a $context and \a $source as a translation message
     and returns the key for the message. If $comment is non-null it will be included in the message.

     If the translation message exists no new message is created and the existing key is returned.
    */
    function insert( $context, $source, $translation, $comment = null )
    {
        if ( $context == "" )
            $context = "default";
        $man =& eZTranslatorManager::instance();
        $key = $man->createKey( $context, $source, $comment );
        if ( isset( $this->Messages[$key] ) )
            return $key;
        $msg =& $man->createMessage( $context, $source, $comment, $translation );
        $msg["key"] = $key;
        $this->Messages[$key] =& $msg;
        // Set array of messages to be cached
        if ( $this->UseCache == true )
        {
            if ( !isset( $this->CachedMessages[$context] ) )
                $this->CachedMessages[$context] = array();
            $this->CachedMessages[$context][$key] =& $msg;
        }
        return $key;
    }

    /*!
     Removes the translation message with \a $context and \a $source.
     Returns true if the message was removed, false otherwise.

     If you have the translation key use removeKey() instead.
    */
    function remove( $context, $source, $message = null )
    {
        if ( $context == "" )
            $context = "default";
        $man =& eZTranslatorManager::instance();
        $key = $man->createKey( $context, $source, $message );
        if ( isset( $this->Messages[$key] ) )
            unset( $this->Messages[$key] );
    }

    /*!
     Removes the translation message with \a $key.
     Returns true if the message was removed, false otherwise.
    */
    function removeKey( $key )
    {
        if ( isset( $this->Messages[$key] ) )
            unset( $this->Messages[$key] );
    }

    /// \privatesection
    /// Contains the hash table with message translations
    var $Messages;
    var $File;
    var $RootDir;
    var $RootDir;
    var $UseCache;
    var $CachedMessages;
}

?>
