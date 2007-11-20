<?php
//
// Definition of eZTemplateFunctionElement class
//
// Created on: <01-Mar-2002 13:49:30 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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
    function process( $tpl, &$text, $nspace, $current_nspace )
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
    public $Name;
    /// The parameter list
    public $Params;
    /// The child elements
    public $Children = array();

    public $Resource;
    public $TemplateName;
}

?>
