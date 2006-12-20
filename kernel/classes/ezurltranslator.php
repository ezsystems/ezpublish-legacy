<?php
//
// Definition of eZURLTranslator class
//
// Created on: <25-Nov-2002 09:49:11 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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
*/

include_once( 'lib/ezutils/classes/ezini.php' );
include_once( 'kernel/classes/ezurlalias.php' );

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
    function &addURLAlias( $source, $destination, $isInternal = true )
    {
        $alias = new eZURLAlias( array() );
        $alias->setAttribute( 'source_url', $source );
        $alias->setAttribute( 'destination_url', $destination );
        $alias->setAttribute( 'is_internal', $isInternal );
        $alias->store();
        return $alias;
    }

    /*!
     Translates the url found in the object \a $uri and returns the corrected url object.
     \return false if no url translation was done.
    */
    function &translate( &$uri )
    {
        $newURI = false;
        $functionList = array();
        $ini =& eZINI::instance();
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
        $nodePathString = $uri->elements();
        $nodePathString = preg_replace( "/\.\w*$/", "", $nodePathString );
        $nodePathString = preg_replace( "#\/$#", "", $nodePathString );
        print( "try to translate: $nodePathString<br>" );

        include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

        $node = eZContentObjectTreeNode::fetchByCRC( $nodePathString );

        $uriResult = false;
        if ( get_class( $node ) == 'ezcontentobjecttreenode' )
        {
            $uriResult= 'content/view/full/' . $node->attribute( 'node_id' ) . '/';
        }
        return $uriResult;
    }

    /*!
     \return The only instance of the translator.
    */
    function &instance()
    {
        $instance =& $GLOBALS['eZURLTranslatorInstance'];
        if ( get_class( $instance ) != 'ezurltranslator' )
        {
            $instance = new eZURLTranslator();
        }
        return $instance;
    }
}

?>
