<?php
//
// Definition of eZTemplateFunctionElement class
//
// Created on: <01-Mar-2002 13:49:30 amos>
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

/*! \defgroup eZTemplateFunctions Template functions
    \ingroup eZTemplate */

/*!
  \class eZTemplateFunctionElement eztemplatefunctionelement.php
  \ingroup eZTemplateElements
  \brief Represents a function element in the template tree.

  This class represents a function with it's parameters.
  It also contains child elements if the function was registered as having
  children.

*/

class eZTemplateFunctionElement
{
    /*!
     Initializes the function with a name and parameter array.
    */
    function eZTemplateFunctionElement( $name, $params, $children = array() )
    {
        $this->Name = $name;
        $this->Params =& $params;
        $this->Children = $children;
    }

    function setResourceRelation( $resource )
    {
        $this->Resource = $resource;
    }

    function setTemplateNameRelation( $templateName )
    {
        $this->TemplateName = $templateName;
    }

    function resourceRelation()
    {
        return $this->Resource;
    }

    function templateNameRelation()
    {
        return $this->TemplateName;
    }

    /*!
     Returns the name of the function.
    */
    function name()
    {
        return $this->Name;
    }

    function serializeData()
    {
        return array( 'class_name' => 'eZTemplateFunctionElement',
                      'parameters' => array( 'name', 'parameters', 'children' ),
                      'variables' => array( 'name' => 'Name',
                                            'parameters' => 'Params',
                                            'children' => 'Children' ) );
    }

    /*!
     Tries to run the function with the children, the actual function execution
     is done by the template class.
    */
    function process( &$tpl, &$text, $nspace, $current_nspace )
    {
        $tmp = $tpl->doFunction( $this->Name, $this, $nspace, $current_nspace );
        if ( $tmp === false )
            return;
        $tpl->appendElement( $text, $tmp, $nspace, $current_nspace );
    }

    /*!
     Returns a reference to the parameter list.
    */
    function &parameters()
    {
        return $this->Params;
    }

    /*!
     Returns a reference to the children.
    */
    function &children()
    {
        return $this->Children;
    }

    /*!
     Appends the child element $node to the child list.
    */
    function appendChild( &$node )
    {
        $this->Children[] =& $node;
    }

    /// The name of the function
    var $Name;
    /// The parameter list
    var $Params;
    /// The child elements
    var $Children = array();

    var $Resource;
    var $TemplateName;
}

?>
