<?php
/*!
  \class   TemplateModuleParamsOperator templatemoduleparamsoperator.php
  \ingroup eZTemplateOperators
  \brief   Handles template operator module_params
  \version 1.0
  \date    29. juli 2003 18:42:38
  \author  Administrator User

  By using module_params you can ...

  Example:
\code
{module_params|wash}
\endcode
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
    }    /*!
     See eZTemplateOperator::namedParameterList
    */
    function namedParameterList()
    {
        return array( 'module_params' => array( 'first_param' => array( 'type' => 'string',
                                                                        'required' => false,
                                                                        'default' => 'default text' ),
                                                'second_param' => array( 'type' => 'integer',
                                                                         'required' => false,
                                                                         'default' => 0 ) ) );
    }
    /*!
     Executes the PHP function for the operator cleanup and modifies \a $operatorValue.
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        $firstParam = $namedParameters['first_param'];
        $secondParam = $namedParameters['second_param'];
        // Example code, this code must be modified to do what the operator should do, currently it only trims text.
        switch ( $operatorName )
        {
            case 'module_params':
            {
                trim( $operatorValue );
                $operatorValue = $GLOBALS['eZRequestedModuleParams'];
                eZDebug::writeDebug( $operatorValue, "ezmoduleparams operator" );
            } break;
        }
    }
}
?>
