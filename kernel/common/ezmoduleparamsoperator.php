<?php
/**
 * File containing the eZModuleParamsOperator class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*
If you want to have autoloading of this operator you should create
a eztemplateautoload.php file and add the following code to it.
The autoload file must be placed somewhere specified in AutoloadPath
under the group TemplateSettings in settings/site.ini

$eZTemplateOperatorArray = array();
$eZTemplateOperatorArray[] = array( 'script' => 'templatemoduleparamsoperator.php',
                                    'class' => '$full_class_name',
                                    'operator_names' => array( 'module_params' ) );

By using module_params you can ...

  Example:
\code
{module_params|wash}
\endcode

*/


class eZModuleParamsOperator
{
    /*!
      Constructor, does nothing by default.
    */
    function eZModuleParamsOperator()
    {
    }

    /*!
     \return an array with the template operator name.
    */
    function operatorList()
    {
        return array( 'module_params' );
    }
    /*!
     \return true to tell the template engine that the parameter list exists per operator type,
             this is needed for operator classes that have multiple operators.
    */
    function namedParameterPerOperator()
    {
        return true;
    }
    /*!
     See eZTemplateOperator::namedParameterList
    */
    function namedParameterList()
    {
        return array( 'module_params' => array() );
    }
    /*!
     Executes the PHP function for the operator cleanup and modifies \a $operatorValue.
    */
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        switch ( $operatorName )
        {
            case 'module_params':
            {
                $operatorValue = $GLOBALS['eZRequestedModuleParams'];
            } break;
        }
    }
}
?>
