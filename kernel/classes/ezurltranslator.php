<?php
//
// Definition of eZURLTranslator class
//
// Created on: <25-Nov-2002 09:49:11 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
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
