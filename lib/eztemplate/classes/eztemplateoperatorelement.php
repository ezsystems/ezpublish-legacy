<?php
//
// Definition of eZTemplateOperatorElement class
//
// Created on: <01-Mar-2002 13:49:50 amos>
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

/*! \defgroup eZTemplateOperators Template operators
    \ingroup eZTemplate
*/

/*!
  \class eZTemplateOperatorElement eztemplateoperatorelement.php
  \ingroup eZTemplateElements
  \brief Represents an operator element in the template tree.

  This class represents an operator with it's parameters.
*/

class eZTemplateOperatorElement
{
    /*!
     Initializes the operator with a name and parameters.
    */
    function eZTemplateOperatorElement( $name, $params, $resource = null, $templateName = null )
    {
        $this->Name = $name;
        $this->Params = $params;
        $this->Resource = $resource;
        $this->TemplateName = $templateName;
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
     Returns a reference to the name.
    */
    function &name()
    {
        return $this->Name;
    }

    function serializeData()
    {
        return array( 'class_name' => 'eZTemplateOperatorElement',
                      'parameters' => array( 'name', 'parameters', 'resource', 'template_name' ),
                      'variables' => array( 'name' => 'Name',
                                            'parameters' => 'Params',
                                            'resource' => 'Resource',
                                            'template_name' => 'TemplateName' ) );
    }

    /*!
     Process the operator and sets $value.

    */
    function process( &$tpl, &$value, $nspace, $current_nspace )
    {
        $named_params = array();
        $param_list = $tpl->operatorParameterList( $this->Name );
        $i = 0;
        foreach ( $param_list as $param_name => $param_type )
        {
            if ( !isset( $this->Params[$i] ) or
                 $this->Params[$i]["type"] == "null" )
            {
                if ( $param_type["required"] )
                {
                    $tpl->warning( "eZTemplateOperatorElement", "Parameter '$param_name' ($i) missing" );
                    $named_params[$param_name] = $param_type["default"];
                }
                else
                {
                    $named_params[$param_name] = $param_type["default"];
                }
            }
            else
            {
                $param_data = $this->Params[$i];
                $named_params[$param_name] = $tpl->elementValue( $param_data, $nspace );
            }
            ++$i;
        }
        if ( $param_list !== null )
            $tpl->doOperator( $this, $nspace, $current_nspace, $value, $this->Name, $this->Params, $named_params );
        else
            $tpl->doOperator( $this, $nspace, $current_nspace, $value, $this->Name, $this->Params );
    }

    /*!
     Returns a reference to the parameter array.
    */
    function &parameters()
    {
        return $this->Params;
    }

    /// The operator name
    var $Name;
    /// The paramer array
    var $Params;
    var $Resource;
    var $TemplateName;
}

?>
