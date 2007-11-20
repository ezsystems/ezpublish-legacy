<?php
//
// Definition of eZTemplateOperatorElement class
//
// Created on: <01-Mar-2002 13:49:50 amos>
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
    function process( $tpl, &$value, $nspace, $current_nspace )
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
    public $Name;
    /// The paramer array
    public $Params;
    public $Resource;
    public $TemplateName;
}

?>
