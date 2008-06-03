<?php
//
// Definition of eZURLTranslator class
//
// Created on: <25-Nov-2002 09:49:11 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file ezurltranslator.php
*/

/*!
  \class eZURLTranslator ezurltranslator.php
  \brief Translation requested URLs into new URLs internally.

  Performs translation on supplied urls, currently only does tree node translation.

  \note This class is deprecated and not in use.
  \deprecated This class is deprecated and not in use.
*/

//include_once( 'lib/ezutils/classes/ezini.php' );
//include_once( 'kernel/classes/ezurlaliasml.php' );

class eZURLTranslator
{
    /*!
     Constructor
    */
    function eZURLTranslator()
    {
    }

    /*!
     Adds a new URL alias.
    */
    function addURLAlias( $source, $destination, $isInternal = true )
    {
        die( __CLASS__ . "::" . __FUNCTION__ . " in file " . __FILE__ . ":" . __LINE__ . " is deprecated" );
    }

    /*!
     Translates the url found in the object \a $uri and returns the corrected url object.
     \return false if no url translation was done.
    */
    function translate( &$uri )
    {
        die( __CLASS__ . "::" . __FUNCTION__ . " in file " . __FILE__ . ":" . __LINE__ . " is deprecated" );
        $newURI = false;
        $functionList = array();
        $ini = eZINI::instance();
        if ( $ini->variable( 'URLTranslator', 'NodeTranslation' ) == 'enabled' )
            $functionList[] = 'translateNodeTree';
        foreach ( $functionList as $functionName )
        {
            $uriResult =& $this->$functionName( $uri );
            if ( is_string( $uriResult ) )
            {
                $newURI =& eZURI::instance( $uriResult );
                return $newURI;
            }
        }
        return $newURI;
    }

    /*!
     Tries to find a node path which matches the uri \a $uri and returns a new uri string which views that node.
     \note This code should get a separate class/package.
    */
    function translateNodeTree( &$uri )
    {
        die( __CLASS__ . "::" . __FUNCTION__ . " in file " . __FILE__ . ":" . __LINE__ . " is deprecated" );
        $nodePathString = $uri->elements();
        $nodePathString = preg_replace( "/\.\w*$/", "", $nodePathString );
        $nodePathString = preg_replace( "#\/$#", "", $nodePathString );
        print( "try to translate: $nodePathString<br>" );

        //include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

        $node = eZContentObjectTreeNode::fetchByCRC( $nodePathString );

        $uriResult = false;
        if ( $node instanceof eZContentObjectTreeNode )
        {
            $uriResult= 'content/view/full/' . $node->attribute( 'node_id' ) . '/';
        }
        return $uriResult;
    }

    /*!
     \return The only instance of the translator.
    */
    static function instance()
    {
        die( __CLASS__ . "::" . __FUNCTION__ . " in file " . __FILE__ . ":" . __LINE__ . " is deprecated" );
        $instance =& $GLOBALS['eZURLTranslatorInstance'];
        if ( !( $instance instanceof eZURLTranslator ) )
        {
            $instance = new eZURLTranslator();
        }
        return $instance;
    }
}

?>
