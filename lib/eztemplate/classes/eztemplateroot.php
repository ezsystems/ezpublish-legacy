<?php
//
// Definition of eZTemplateRoot class
//
// Created on: <01-Mar-2002 13:50:20 amos>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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

/*! \defgroup eZTemplateElements Template elements
    \ingroup eZTemplate
*/

/*!
  \class eZTemplateRoot eztemplateroot.php
  \ingroup eZTemplateElements
  \brief Represents a root element of the template tree.

  This starts the template tree and is the base of template includes.

  It has a list of child elements and runs process() on each child.
*/

class eZTemplateRoot
{
    /*!
     Initializes the object.
    */
    function eZTemplateRoot( $children = array() )
    {
        $this->Children = $children;
    }

    /*!
     Returns #root as the name.
    */
    function name()
    {
        return "#root";
    }

    function serializeData()
    {
        return array( 'class_name' => 'eZTemplateRoot',
                      'parameters' => array( 'children' ),
                      'variables' => array( 'children' => 'Children' ) );
    }

    /*!
     Runs process() on all child elements.
    */
    function process( &$tpl, &$text, $nspace, $current_nspace )
    {
        foreach( array_keys( $this->Children ) as $key )
        {
            $this->Children[$key]->process( $tpl, $text, $nspace, $current_nspace );
        }
    }

    /*!
     Removes all children.
    */
    function clear()
    {
        $this->Children = array();
    }

    /*!
     Returns a reference to the child array.
    */
    function &children()
    {
        return $this->Children;
    }

    /*!
     Appends the child $node to the child array.
    */
    function appendChild( &$node )
    {
        $this->Children[] =& $node;
    }

    /// The child array
    var $Children = array();
}

?>
