<?php
/**
 * File containing the eZi18nOperator class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

//!! eZKernel
//! The class eZi18nOperator does
/*!

*/

class eZi18nOperator
{
    function eZi18nOperator( $name = 'i18n', $extensionName = 'x18n', $dynamicName = 'd18n' )
    {
        $this->Operators = array( $name, $extensionName, $dynamicName );
        $this->Name = $name;
        $this->ExtensionName = $extensionName;
        // d18n is a i18n alias for use with dynamic variables as input
        // where you don't want ezlupdate to pick up the string
        $this->DynamicName = $dynamicName;
    }

    /*!
     Returns the operators in this class.
    */
    function operatorList()
    {
        return $this->Operators;
    }

    /*!
     \return true to tell the template engine that the parameter list exists per operator type.
    */
    function namedParameterPerOperator()
    {
        return true;
    }

    /*!
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        $def = array( $this->Name => array( 'context' => array( 'type' => 'string',
                                                                'required' => false,
                                                                'default' => false ),
                                            'comment' => array( 'type' => 'string',
                                                                'required' => false,
                                                                'default' => '' ),
                                            'arguments' => array( 'type' => 'hash',
                                                                  'required' => false,
                                                                  'default' => false ) ),
                      $this->ExtensionName => array( 'extension' => array( 'type' => 'string',
                                                                           'required' => true,
                                                                           'default' => false ),
                                                     'context' => array( 'type' => 'string',
                                                                         'required' => false,
                                                                         'default' => false ),
                                                     'comment' => array( 'type' => 'string',
                                                                         'required' => false,
                                                                         'default' => '' ),
                                                     'arguments' => array( 'type' => 'hash',
                                                                           'required' => false,
                                                                           'default' => false ) ) );
        $def[ $this->DynamicName ] = $def[ $this->Name ];
        return $def;
    }

    function operatorTemplateHints()
    {
        $def = array( $this->Name => array( 'input' => true,
                                            'output' => true,
                                            'parameters' => true,
                                            'element-transformation' => true,
                                            'transform-parameters' => true,
                                            'input-as-parameter' => 'always',
                                            'element-transformation-func' => 'i18nTrans') );
        $def[ $this->DynamicName ] = $def[ $this->Name ];
        return $def;
    }

    function i18nTrans( $operatorName, &$node, $tpl, &$resourceData,
                        $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        // i18n( $input, $context, $comment, $arguments )
        // Check if if the three first parameters are constants, if not we cannot compile it
        foreach ( array_slice( $parameters, 0, 3 ) as $parameter )
        {
            if ( $parameter !== null &&
                 !eZTemplateNodeTool::isConstantElement( $parameter ) )
            {
                return false;
            }
        }

        $value = eZTemplateNodeTool::elementConstantValue( $parameters[0] );

        $numParameters = count ( $parameters );
        $context = ( $numParameters > 1 ) ? eZTemplateNodeTool::elementConstantValue( $parameters[1] ) : null;
        $comment = ( $numParameters > 2 ) ? eZTemplateNodeTool::elementConstantValue( $parameters[2] ) : null;

        if ( $numParameters < 4 )
        {
            return array ( eZTemplateNodeTool::createStringElement( ezpI18n::tr( $context, $value, $comment, null ) ) );
        }

        $values = array();

        $ini = eZINI::instance();
        if ( $ini->variable( 'RegionalSettings', 'TextTranslation' ) != 'disabled' )
        {
            $language = eZLocale::instance()->localeFullCode();
            if ( $language != "eng-GB" ) // eng-GB does not need translation
            {
                $file = 'translation.ts';
                $ini = eZINI::instance();
                $useCache = $ini->variable( 'RegionalSettings', 'TranslationCache' ) != 'disabled';
                eZTSTranslator::initialize( $context, $language, $file, $useCache );

                $man = eZTranslatorManager::instance();
                $newValue = $man->translate( $context, $value, $comment );
                if ( $newValue )
                {
                    $value = $newValue;
                }
            }
        }

        $values[] = array( eZTemplateNodeTool::createStringElement( $value ) );
        $values[] = $parameters[3];

        $code = '%tmp1% = array();' . "\n" .
             'foreach ( %2% as %tmp2% => %tmp3% )' . "\n" .
             '{' . "\n" .
             '  if ( is_int( %tmp2% ) )' . "\n" .
             '    %tmp1%[\'%\' . ( (%tmp2%%9) + 1 )] = %tmp3%;' . "\n" .
             '  else' . "\n" .
             '    %tmp1%[%tmp2%] = %tmp3%;' . "\n" .
             '}' . "\n" .
             '%output% = strtr( %1%, %tmp1% );' . "\n";

        return array( eZTemplateNodeTool::createCodePieceElement( $code, $values, false, 3 ) );
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$value, $namedParameters )
    {
        switch ( $operatorName )
        {
            case $this->Name:
            case $this->DynamicName:
            {
                $context = $namedParameters['context'];
                $comment = $namedParameters['comment'];
                $arguments = $namedParameters['arguments'];
                $value = ezpI18n::tr( $context, $value, $comment, $arguments );
            } break;
            case $this->ExtensionName:
            {
                $extension = $namedParameters['extension'];
                $context = $namedParameters['context'];
                $comment = $namedParameters['comment'];
                $arguments = $namedParameters['arguments'];
                $value = ezpI18n::tr( $context, $value, $comment, $arguments );
            } break;
        }
    }

    /// \privatesection
    public $Operators;
    public $Name;
    public $ExtensionName;
}

?>
