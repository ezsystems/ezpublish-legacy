<?php
//
// Definition of eZi18nOperator class
//
// Created on: <18-Apr-2002 12:15:07 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

//!! eZKernel
//! The class eZi18nOperator does
/*!

*/

function make_seed() {
    list($usec, $sec) = explode(' ', microtime());
    return (float) $sec + ((float) $usec * 100000);
}

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
    function &operatorList()
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

    function i18nTrans( $operatorName, &$node, &$tpl, &$resourceData,
                        &$element, &$lastElement, &$elementList, &$elementTree, &$parameters )
    {
        foreach ( $parameters as $parameter )
        {
            if ( eZTemplateNodeTool::isConstantElement( $parameter ) )
            {
                return false;
            }
        }

        include_once( 'kernel/common/i18n.php' );
        $value = eZTemplateNodeTool::elementStaticValue( $parameters[0] );

        $numParameters = count ( $parameters );
        $context = ( $numParameters > 1 ) ? eZTemplateNodeTool::elementStaticValue( $parameters[1] ) : null;
        $comment = ( $numParameters > 2 ) ? eZTemplateNodeTool::elementStaticValue( $parameters[2] ) : null;

        if ( $numParameters < 4 )
        {
            return array ( eZTemplateNodeTool::createStringElement( ezi18n( $context, $value, $comment, null ) ) );
        }

        $values = array();

        $ini =& eZINI::instance();
        if ( $ini->variable( 'RegionalSettings', 'TextTranslation' ) != 'disabled' )
        {
            $language = ezcurrentLanguage();
            if ( $language != "eng-GB" ) // eng-GB does not need translation
            {
                $file = 'translation.ts';
                $ini =& eZINI::instance();
                $useCache = $ini->variable( 'RegionalSettings', 'TranslationCache' ) != 'disabled';
                eZTSTranslator::initialize( $context, $language, $file, $useCache );

                $man =& eZTranslatorManager::instance();
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
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$value, &$namedParameters )
    {
        include_once( 'kernel/common/i18n.php' );
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
    var $Operators;
    var $Name;
    var $ExtensionName;
};

?>
