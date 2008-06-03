<?php
//
// Definition of eZi18nOperator class
//
// Created on: <18-Apr-2002 12:15:07 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

//!! eZKernel
//! The class eZi18nOperator does
/*!

*/

//include_once( 'lib/ezi18n/classes/eztranslatormanager.php' );
//include_once( 'lib/ezi18n/classes/eztstranslator.php' );

class eZi18nOperator
{
    /*!
    */
    function eZi18nOperator( $name = 'i18n', $extensionName = 'x18n' )
    {
        $this->Operators = array( $name, $extensionName );
        $this->Name = $name;
        $this->ExtensionName = $extensionName;
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
        return array( $this->Name => array( 'context' => array( 'type' => 'string',
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
    }

    function operatorTemplateHints()
    {
        return array( $this->Name => array( 'input' => true,
                                            'output' => true,
                                            'parameters' => true,
                                            'element-transformation' => true,
                                            'transform-parameters' => true,
                                            'input-as-parameter' => 'always',
                                            'element-transformation-func' => 'i18nTrans') );
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

        require_once( 'kernel/common/i18n.php' );
        $value = eZTemplateNodeTool::elementStaticValue( $parameters[0] );

        $numParameters = count ( $parameters );
        $context = ( $numParameters > 1 ) ? eZTemplateNodeTool::elementStaticValue( $parameters[1] ) : null;
        $comment = ( $numParameters > 2 ) ? eZTemplateNodeTool::elementStaticValue( $parameters[2] ) : null;

        if ( $numParameters < 4 )
        {
            return array ( eZTemplateNodeTool::createStringElement( ezi18n( $context, $value, $comment, null ) ) );
        }

        $values = array();

        $ini = eZINI::instance();
        if ( $ini->variable( 'RegionalSettings', 'TextTranslation' ) != 'disabled' )
        {
            $language = ezcurrentLanguage();
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

    /*!
     \reimp
    */
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$value, $namedParameters )
    {
        require_once( 'kernel/common/i18n.php' );
        switch ( $operatorName )
        {
            case $this->Name:
            {
                $context = $namedParameters['context'];
                $comment = $namedParameters['comment'];
                $arguments = $namedParameters['arguments'];
                $value = ezi18n( $context, $value, $comment, $arguments );
            } break;
            case $this->ExtensionName:
            {
                $extension = $namedParameters['extension'];
                $context = $namedParameters['context'];
                $comment = $namedParameters['comment'];
                $arguments = $namedParameters['arguments'];
                $value = ezx18n( $extension, $context, $value, $comment, $arguments );
            } break;
        }
    }

    /// \privatesection
    public $Operators;
    public $Name;
    public $ExtensionName;
};

?>
