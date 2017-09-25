<?php
/**
 * File containing the eZModuleParamsOperator class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*
If you want to have autoloading of this operator you should create
a eztemplateautoload.php file and add the following code to it.
The autoload file must be placed somewhere specified in AutoloadPathList
under the group TemplateSettings in settings/site.ini

$eZTemplateOperatorArray = array();
$eZTemplateOperatorArray[] = array( 'class' => '$full_class_name',
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
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters, $placement )
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
