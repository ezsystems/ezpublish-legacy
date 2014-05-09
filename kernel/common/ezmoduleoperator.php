<?php
/**
 * File containing the eZModuleOperator class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZModuleOperator ezmoduleoperator.php
  \brief The class eZModuleOperator does

*/


class eZModuleOperator
{
    /*!
     Constructor
    */
    function eZModuleOperator( $name = 'ezmodule' )
    {
        $this->Operators = array( $name );
    }

    /*!
     Returns the operators in this class.
    */
    function operatorList()
    {
        return $this->Operators;
    }

    /*!
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return array( 'uri' => array( 'type' => 'string',
                                      'required' => false,
                                      'default' => false ) );
    }
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters, $placement )
    {
        $uri = new eZURI( $namedParameters[ 'uri' ] );
        $moduleName = $uri->element( 0 );
        $moduleList = eZINI::instance( 'module.ini' )->variable( 'ModuleSettings', 'ModuleList' );
        if ( in_array( $moduleName, $moduleList, true ) )
            $check = eZModule::accessAllowed( $uri );
        $operatorValue = isset( $check['result'] ) ? $check['result'] : false;
    }

    /// \privatesection
    public $Operators;
}


?>
