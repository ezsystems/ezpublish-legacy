<?php
/**
 * File containing the eZTemplateOperatorElement class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

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
