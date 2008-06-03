<?php
//
// Definition of eZTemplateStringOperator class
//
// Created on: <17-Jul-2003 13:00:18 bh>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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

/*!
  \class eZTemplateStringOperator eztemplatestringoperator.php
  \ingroup eZTemplateOperators
\code

\endcode

*/

class eZTemplateStringOperator
{
    /*!
     Constructor.
    */
    function eZTemplateStringOperator()
    {
        $this->Operators = array( 'upcase', 'downcase',
                                  'count_words', 'count_chars',
                                  'trim', 'break', 'wrap', 'shorten', 'pad',
                                  'upfirst', 'upword',
                                  'simplify', 'wash',
                                  'chr', 'ord');
        foreach ( $this->Operators as $operator )
        {
            $name = $operator . 'Name';
            $name[0] = $name[0] & "\xdf";
            $this->$name = $operator;
        }

        $this->phpMap = array ('upcase' => 'mb_strtoupper,strtoupper',
                               'downcase' => 'mb_strtolower,strtolower',
                               'break' => 'nl2br',
                               'count_chars' => 'mb_strlen,strlen');

        $this->customMap = array ( 'count_words' => array( 'return' => 'int',
                                                          'code' => '$result = preg_match_all( "#(\w+)#", $staticValues[0], $dummy );'
                                                        ),
                                   'chr' => array( 'return' => 'string',
                                                   'code' => '$codec = eZTextCodec::instance( "unicode", false );' . "\n" .
                                                             '$result = $codec->convertString( $staticValues[0] );'
                                                 ),
                                   'ord' => array( 'return' => 'string',
                                                   'code' => '$codec = eZTextCodec::instance( false, "unicode" );' . "\n" .
                                                             '$result = $codec->convertString( $staticValues[0] );'
                                                 ),
                                   'pad' => array( 'return' => 'string',
                                                   'code' => '$result = $paramCount == 2 ? str_pad( $staticValues[0], $staticValues[1] ) : str_pad ( $staticValues[0], $staticValues[1], $staticValues[2] );',
                                                   'code2' => '$result = str_pad( $staticValues[0], $staticValues[1] );',
                                                   'code3' => '$result = str_pad( $staticValues[0], $staticValues[1], $staticValues[2] );',
                                                 ),
                                   'trim' => array( 'return' => 'string',
                                                    'code' => '$result = $paramCount == 1 ? trim( $staticValues[0] ) : trim ( $staticValues[0], $staticValues[1] );',
                                                    'code1' => '$result = trim( $staticValues[0] );',
                                                    'code2' => '$result = trim( $staticValues[0], $staticValues[1] );',
                                                 ),
                                   'wrap' => array( 'return' => 'string',
                                                    'code1' => '$result = wordwrap( $staticValues[0] );',
                                                    'code2' => '$result = wordwrap( $staticValues[0], $staticValues[1] );',
                                                    'code3' => '$result = wordwrap( $staticValues[0], $staticValues[1], $staticValues[2] );',
                                                    'code4' => '$result = wordwrap( $staticValues[0], $staticValues[1], $staticValues[2], $staticValues[3] );',
                                                 ),
                                   'simplify' => array( 'return' => 'string',
                                                        'code' => 'if ( $paramCount == 1 )
                                                                   {
                                                                       $replacer = " ";
                                                                   }
                                                                   else
                                                                   {
                                                                       $replacer = $staticValues[1];
                                                                   }
                                                                   $result = preg_replace( "/".$replacer."{2,}/", $replacer, $staticValues[0] );',
                                                        'code1' => '$result = preg_replace( "/ {2,}/", " ", $staticValues[0] );',
                                                        'code2' => '$result = preg_replace( "/".$staticValues[1]."{2,}/", $staticValues[1], $staticValues[0] );',
                                                      ),
                                   'shorten' => array( 'return' => 'string',
                                                       'code' => '$length = 80; $seq = "..."; $trimType = "right";
                                                                  if ( $paramCount > 1 )
                                                                  {
                                                                      $length = $staticValues[1];
                                                                  }
                                                                  if ( $paramCount > 2 )
                                                                  {
                                                                      $seq = $staticValues[2];
                                                                  }
                                                                  if ( $paramCount > 3 )
                                                                  {
                                                                      $trimType = $staticValues[3];
                                                                  }
                                                                  if ( $trimType === "middle" )
                                                                  {
                                                                      $appendedStrLen = $strlenFunc( $seq );
                                                                      if ( $length > $appendedStrLen && ( $strlenFunc( $staticValues[0] ) > $length ) )
                                                                      {
                                                                          $operatorValueLength = $strlenFunc( $staticValues[0] );
                                                                          $chop = $length - $appendedStrLen;
                                                                          $middlePos = (int)($chop / 2);
                                                                          $leftPartLength = $middlePos;
                                                                          $rightPartLength = $chop - $middlePos;
                                                                          $result = trim( $substrFunc( $staticValues[0], 0, $leftPartLength ) . $seq . $substrFunc( $staticValues[0], $operatorValueLength - $rightPartLength, $rightPartLength ) );
                                                                      }
                                                                      else
                                                                      {
                                                                          $result = $staticValues[0];
                                                                      }
                                                                  }
                                                                  else // default: trim_type === "right"
                                                                  {
                                                                      $maxLength = $length - $strlenFunc( $seq );
                                                                      if ( ( $strlenFunc( $staticValues[0] ) > $length ) && $strlenFunc( $staticValues[0] ) > $maxLength )
                                                                      {
                                                                          $result = trim( $substrFunc( $staticValues[0], 0, $maxLength) ) . $seq;
                                                                      }
                                                                      else
                                                                      {
                                                                          $result = $staticValues[0];
                                                                      }
                                                                  }'
                                                     ),
                                   'upfirst' => array( 'return' => 'string',
                                                       'code' => '$i18nIni = eZINI::instance( \'i18n.ini\' );
                                                                  $hasMBString = ( $i18nIni->variable( \'CharacterSettings\', \'MBStringExtension\' ) == \'enabled\' and
                                                                  function_exists( "mb_strtoupper" ) and
                                                                  function_exists( "mb_substr" ) and
                                                                  function_exists( "mb_strlen" ) );

                                                                  if ( $hasMBString )
                                                                  {
                                                                      $encoding = eZTextCodec::internalCharset();
                                                                      $firstLetter = mb_strtoupper( mb_substr( $staticValues[0], 0, 1, $encoding ), $encoding );
                                                                      $remainingText = mb_substr( $staticValues[0], 1, mb_strlen( $staticValues[0], $encoding ), $encoding );
                                                                      $result = $firstLetter . $remainingText;
                                                                  }
                                                                  else
                                                                  {
                                                                     $result = ucfirst( $staticValues[0] );
                                                                  }'
                                                     ),
                                   'upword' => array( 'return' => 'string',
                                                      'code' => ' $i18nIni = eZINI::instance( \'i18n.ini\' );
                                                                  $hasMBString = ( $i18nIni->variable( \'CharacterSettings\', \'MBStringExtension\' ) == \'enabled\' and
                                                                                   function_exists( "mb_strtoupper" ) and
                                                                                   function_exists( "mb_substr" ) and
                                                                                   function_exists( "mb_strlen" ) );

                                                                  if ( $hasMBString )
                                                                  {
                                                                      $encoding = eZTextCodec::internalCharset();
                                                                      $words = explode( " ", $staticValues[0] );
                                                                      $newString = array();
                                                                      foreach ( $words as $word )
                                                                      {
                                                                          $firstLetter = mb_strtoupper( mb_substr( $word, 0, 1, $encoding ), $encoding );
                                                                          $remainingText = mb_substr( $word, 1, mb_strlen( $word, $encoding ), $encoding );
                                                                          $newString[] = $firstLetter . $remainingText;
                                                                      }
                                                                      $result = implode( " ", $newString );
                                                                      unset( $newString, $words );
                                                                  }
                                                                  else
                                                                  {
                                                                     $result = ucwords( $staticValues[0] );
                                                                  }'
                                                    )

                                 );
    }

    /*!
     Returns the template operators.
    */
    function operatorList()
    {
        return $this->Operators;
    }

    function operatorTemplateHints()
    {
        $hints = array(
            $this->BreakName        => array( 'parameters' => false, 'element-transformation-func' => 'phpMapTransformation' ),
            $this->Count_charsName   => array( 'parameters' => false, 'element-transformation-func' => 'phpMapTransformation' ),
            $this->DowncaseName     => array( 'parameters' => false, 'element-transformation-func' => 'phpMapTransformation' ),
            $this->UpcaseName       => array( 'parameters' => false, 'element-transformation-func' => 'phpMapTransformation' ),
            $this->UpfirstName      => array( 'parameters' => false, 'element-transformation-func' => 'customMapTransformation' ),
            $this->UpwordName       => array( 'parameters' => false, 'element-transformation-func' => 'customMapTransformation' ),

            $this->Count_wordsName   => array( 'parameters' => false, 'element-transformation-func' => 'customMapTransformation' ),
            $this->ChrName          => array( 'parameters' => false, 'element-transformation-func' => 'customMapTransformation' ),
            $this->OrdName          => array( 'parameters' => false, 'element-transformation-func' => 'customMapTransformation' ),
            $this->PadName          => array( 'parameters' => false, 'element-transformation-func' => 'customMapTransformation' ),
            $this->ShortenName      => array( 'parameters' => 3    , 'element-transformation-func' => 'customMapTransformation' ),
            $this->SimplifyName     => array( 'parameters' => false, 'element-transformation-func' => 'customMapTransformation' ),
            $this->TrimName         => array( 'parameters' => 1    , 'element-transformation-func' => 'customMapTransformation' ),
            $this->WrapName         => array( 'parameters' => false, 'element-transformation-func' => 'customMapTransformation' ),

            $this->WashName         => array( 'parameters' => 1, 'element-transformation-func' => 'washTransformation' ),
        );

        foreach ( $this->Operators as $operator )
        {
            $hints[$operator]['input'] = true;
            $hints[$operator]['output'] = true;
            $hints[$operator]['element-transformation'] = true;
            $hints[$operator]['transform-parameters'] = true;
            if ( !isset( $hints[$operator]['input-as-parameter'] ) )
                $hints[$operator]['input-as-parameter'] = 'always';
        }
        return $hints;
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
        return array( $this->TrimName => array( 'chars_to_remove'  => array( "type" => "string",
                                                                             "required" => false,
                                                                             "default" => false ) ),
                      $this->WrapName => array( 'wrap_at_position' => array( "type" => "integer",
                                                                             "required" => false,
                                                                             "default" => false ),
                                                'break_sequence'   => array( "type" => "string",
                                                                             "required" => false,
                                                                             "default" => false ),
                                                'cut'              => array( "type" => "boolean",
                                                                             "required" => false,
                                                                             "default" => false ) ),
                      $this->WashName => array( 'type'             => array( "type" => "string",
                                                                             "required" => false,
                                                                             "default" => "xhtml" ) ),
                      $this->ShortenName => array( 'chars_to_keep' => array( "type" => "integer",
                                                                             "required" => false,
                                                                             "default" => 80 ),
                                                   'str_to_append' => array( "type" => "string",
                                                                             "required" => false,
                                                                             "default" => "..." ),
                                                   'trim_type'     => array( "type" => "string",
                                                                             "required" => false,
                                                                             "default" => "right" ) ),
                      $this->PadName => array(  'desired_length'   => array( "type"     => "integer",
                                                                             "required" => false,
                                                                             "default"  => 80 ),
                                                'pad_sequence'     => array( "type" => "string",
                                                                             "required" => false,
                                                                             "default" => " " ) ),
                      $this->SimplifyName => array ( 'char'        => array( "type" => "string",
                                                                             "required" => false,
                                                                             "default" => false ) ) );
    }

    function phpMapTransformation( $operatorName, $node, $tpl, $resourceData,
                                   $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        $values = array();
        $phpFunctionList = explode( ',', $this->phpMap[$operatorName] );
        foreach ( $phpFunctionList as $element )
        {
            if ( function_exists( $element ) )
            {
                $phpFunction = $element;
                break;
            }
        }
        $newElements = array();

        if ( count ( $parameters ) != 1 )
        {
            return false;
        }

        if ( eZTemplateNodeTool::isStaticElement( $parameters[0] ) )
        {
            $text = eZTemplateNodeTool::elementStaticValue( $parameters[0] );
            $text = $phpFunction( $text );
            $text = str_replace( array( "'" ), array( "\\'" ), $text );
            $code = "%output% = '" . $text . "' ;\n";
        }
        else
        {
            $values[] = $parameters[0];
            $code = "%output% = $phpFunction( %1% );\n";
        }

        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
        return $newElements;
    }

    function customMapTransformation( $operatorName, $node, $tpl, $resourceData,
                                       $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        $values = array();
        $newElements = array();
        $mapEntry = $this->customMap[$operatorName];
        $paramCount = count( $parameters );
        $strlenFunc = 'strlen';
        $substrFunc = 'substr';
        $code = "\$strlenFunc = 'strlen'; \$substrFunc = 'substr';\n";
        if ( function_exists( 'mb_strlen' ) )
        {
            $strlenFunc = 'mb_strlen';
            $substrFunc = 'mb_substr';
            $code = "\$strlenFunc = 'mb_strlen'; \$substrFunc = 'mb_substr';\n";
        }

        if ( $paramCount < 1 )
        {
            return false;
        }

        $allStatic = true;
        $staticValues = array();
        $replaceMap = array('$result');
        $replacementMap = array('%output%');
        for ($i = 0; $i < $paramCount; $i++)
        {
            if ( eZTemplateNodeTool::isStaticElement( $parameters[$i] ) )
            {
                $staticValues[$i] = eZTemplateNodeTool::elementStaticValue( $parameters[$i] );
            }
            else
            {
                $allStatic = false;
            }
        }

        if ( $allStatic )
        {
            $result = false;
            if ( isset( $mapEntry['code'. $paramCount] ) )
            {
                eval( $mapEntry['code' . $paramCount] );
            }
            else
            {
                eval( $mapEntry['code'] );
            }
            return array( eZTemplateNodeTool::createStaticElement( $result ) );
        }
        else
        {
            $replaceMap = array( '$result', '$paramCount' );
            $replacementMap = array( '%output%', $paramCount );
            for ( $i = 0; $i < $paramCount; $i++ )
            {
                $values[] = $parameters[$i];
                $replaceMap[] = "\$staticValues[$i]";
                $replacementMap[] = '%' . ( $i + 1 ) . '%';
            }
            if ( isset( $mapEntry['code'. $paramCount] ) )
            {
                $code .= str_replace( $replaceMap, $replacementMap, $mapEntry['code' . $paramCount] ) . "\n";
            }
            else
            {
                $code .= str_replace( $replaceMap, $replacementMap, $mapEntry['code'] ) . "\n";
            }
        }

        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
        return $newElements;
    }

    /*!
     * \private
     */
    function wash( $operatorValue, $tpl, $type = 'xhtml' )
    {
        switch ( $type )
        {
            case "xhtml":
            {
                $operatorValue = htmlspecialchars( $operatorValue );
            } break;

            case "email":
            {
                $ini = $tpl->ini();
                $dotText = $ini->variable( 'WashSettings', 'EmailDotText' );
                $atText = $ini->variable( 'WashSettings', 'EmailAtText' );
                $operatorValue = str_replace( array( '.',
                                                     '@' ),
                                              array( $dotText,
                                                     $atText ),
                                              $operatorValue );
            } break;

            case 'pdf':
            {
                $operatorValue = str_replace( array( ' ', // use default callback functions in ezpdf library
                                                     "\r\n",
                                                     "\t" ),
                                              array( '<C:callSpace>',
                                                     '<C:callNewLine>',
                                                     '<C:callTab>' ),
                                              $operatorValue );
                $operatorValue = str_replace( "\n", '<C:callNewLine>', $operatorValue );
            } break;

            case 'javascript':
            {
                $operatorValue = str_replace( array( "\\", "\"", "'"),
                                              array( "\\\\", "\\042", "\\047" ) , $operatorValue );
            } break;
        }
        return $operatorValue;
    }

    function washTransformation( $operatorName, $node, $tpl, $resourceData,
                                 $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        $values = array();
        $tmpVarCount = 0;
        $newElements = array();
        $paramCount = count( $parameters );

        if ( $paramCount > 2 )
        {
            return false;
        }

        $allStatic = true;
        $staticValues = array();
        for ($i = 0; $i < $paramCount; $i++)
        {
            if ( eZTemplateNodeTool::isStaticElement( $parameters[$i] ) )
            {
                $staticValues[$i] = eZTemplateNodeTool::elementStaticValue( $parameters[$i] );
            }
            else
            {
                $allStatic = false;
            }
        }

        /* Do optimalizations for 'xhtml' case */
        if ( $allStatic ) {
            if ( $paramCount == 1 )
            {
                $type = 'xhtml';
            }
            else
            {
                $type = $staticValues[1];
            }
            $code = "%output% = '" . addcslashes( $this->wash( $staticValues[0], $tpl, $type ), "'\\" ) . "' ;\n";
        }
        /* XHTML: Type is static, input is not */
        else if ( ( $paramCount == 1 ) || ( ( $paramCount == 2 ) && isset( $staticValues[1] ) && ( $staticValues[1] == 'xhtml' ) ) )
        {
            $values[] = $parameters[0];
            $code = "%output% = htmlspecialchars( %1% );\n";
        }
        /* PDF: Type is static, input is not static */
        else if ( ( $paramCount == 2 ) && isset( $staticValues[1] ) && ( $staticValues[1] == 'pdf' ) )
        {
            $values[] = $parameters[0];
            $tmpVarCount = 1;
            $code = '%tmp1% = str_replace( array( " ", "\r\n", "\t" ), array( "<C:callSpace>", "<C:callNewLine>", "<C:callTab>" ), %1% );'. "\n";
            $code .= '%output% = str_replace( "\n", "<C:callNewLine>", %tmp1% );'."\n";
        }
        /* MAIL: Type is static, input is not static */
        else if ( ( $paramCount == 2 ) && isset( $staticValues[1] ) && ( $staticValues[1] == 'email' ) )
        {
            $ini = $tpl->ini();
            $dotText = addcslashes( $ini->variable( 'WashSettings', 'EmailDotText' ), "'" );
            $atText = addcslashes( $ini->variable( 'WashSettings', 'EmailAtText' ), "'" );

            $values[] = $parameters[0];
            $code = "%output% = str_replace( array( '.', '@' ), array( '$dotText', '$atText' ), %1% );\n";
        }
        /* JAVASCRIPT: Type is static, input is not static */
        else if ( ( $paramCount == 2 ) && isset( $staticValues[1] ) && ( $staticValues[1] == 'javascript' ) )
        {
            $values[] = $parameters[0];
            $code = '%output% = str_replace( array( "\\\\", "\"", "\'" ),
                                             array( "\\\\\\\\", "\\\\042", "\\\\047" ) , %1% ); ';



        }
        else /* No compiling for the rest cases */
        {
            return false;
        }

        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values, false, $tmpVarCount );
        return $newElements;
    }

    /*
     The modify function takes care of the various operations.
    */
    function modify( $tpl,
                     $operatorName,
                     $operatorParameters,
                     $rootNamespace,
                     $currentNamespace,
                     &$operatorValue,
                     $namedParameters,
                     $placement )
    {
        switch ( $operatorName )
        {
            // Convert all alphabetical chars of operatorvalue to uppercase.
            case $this->UpcaseName:
            {
                $funcName = function_exists( 'mb_strtoupper' ) ? 'mb_strtoupper' : 'strtoupper';
                $operatorValue = $funcName( $operatorValue );
            } break;

            // Convert all alphabetical chars of operatorvalue to lowercase.
            case $this->DowncaseName:
            {
                $funcName = function_exists( 'mb_strtolower' ) ? 'mb_strtolower' : 'strtolower';
                $operatorValue = $funcName( $operatorValue );
            } break;

            // Count and return the number of words in operatorvalue.
            case $this->Count_wordsName:
            {
                $operatorValue = preg_match_all( "#(\w+)#", $operatorValue, $dummy_match );
            }break;

            // Count and return the number of chars in operatorvalue.
            case $this->Count_charsName:
            {
                $funcName = function_exists( 'mb_strlen' ) ? 'mb_strlen' : 'strlen';
                $operatorValue = $funcName( $operatorValue );
            }break;

            // Insert HTML line breaks before newlines.
            case $this->BreakName:
            {
                $operatorValue = nl2br( $operatorValue );
            }break;

            // Wrap line (insert newlines).
            case $this->WrapName:
            {
                $parameters = array( $operatorValue );
                if ( $namedParameters['wrap_at_position'] )
                {
                    $parameters[] = $namedParameters['wrap_at_position'];
                    if ( $namedParameters['break_sequence'] )
                    {
                        $parameters[] = $namedParameters['break_sequence'];
                        if ( $namedParameters['cut'] )
                        {
                            $parameters[] = $namedParameters['cut'];
                        }
                    }
                }
                $operatorValue = call_user_func_array( 'wordwrap', $parameters );
            }break;

            // Convert the first character to uppercase.
            case $this->UpfirstName:
            {
                $i18nIni = eZINI::instance( 'i18n.ini' );
                $hasMBString = ( $i18nIni->variable( 'CharacterSettings', 'MBStringExtension' ) == 'enabled' and
                                 function_exists( "mb_strtoupper" ) and
                                 function_exists( "mb_substr" ) and
                                 function_exists( "mb_strlen" ) );

                if ( $hasMBString )
                {
                    $encoding = eZTextCodec::internalCharset();
                    $firstLetter = mb_strtoupper( mb_substr( $operatorValue, 0, 1, $encoding ), $encoding );
                    $remainingText = mb_substr( $operatorValue, 1, mb_strlen( $operatorValue, $encoding ), $encoding );
                    $operatorValue = $firstLetter . $remainingText;
                }
                else
                {
                   $operatorValue = ucfirst( $operatorValue );
                }

            }break;

            // Simplify / transform multiple consecutive characters into one.
            case $this->SimplifyName:
            {
                $simplifyCharacter = $namedParameters['char'];
                if ( $namedParameters['char'] === false )
                {
                    $replace_this = "/\s{2,}/";
                    $simplifyCharacter = ' ';
                }
                else
                {
                    $replace_this = "/". $simplifyCharacter ."{2,}/";
                }
                $operatorValue = preg_replace( $replace_this, $simplifyCharacter, $operatorValue );
            }break;
            // Convert all first characters [in all words] to uppercase.
            case $this->UpwordName:
            {
                $i18nIni = eZINI::instance( 'i18n.ini' );
                $hasMBString = ( $i18nIni->variable( 'CharacterSettings', 'MBStringExtension' ) == 'enabled' and
                                 function_exists( "mb_strtoupper" ) and
                                 function_exists( "mb_substr" ) and
                                 function_exists( "mb_strlen" ) );

                if ( $hasMBString )
                {
                    $encoding = eZTextCodec::internalCharset();
                    $words = explode( " ", $operatorValue );
                    $newString = array();
                    foreach ( $words as $word )
                    {
                        $firstLetter = mb_strtoupper( mb_substr( $word, 0, 1, $encoding ), $encoding );
                        $remainingText = mb_substr( $word, 1, mb_strlen( $word, $encoding ), $encoding );
                        $newString[]= $firstLetter . $remainingText;
                    }
                    $operatorValue = implode( " ", $newString );
                    unset( $newString, $words );
                }
                else
                {
                   $operatorValue = ucwords( $operatorValue );
                }
            }break;

            // Strip whitespace from the beginning and end of a string.
            case $this->TrimName:
            {
                if ( $namedParameters['chars_to_remove'] === false )
                    $operatorValue = trim( $operatorValue );
                else
                    $operatorValue = trim( $operatorValue, $namedParameters['chars_to_remove'] );
            }break;

            // Pad...
            case $this->PadName:
            {
                if (strlen( $operatorValue ) < $namedParameters['desired_length'])
                {
                    $operatorValue = str_pad( $operatorValue,
                                              $namedParameters['desired_length'],
                                              $namedParameters['pad_sequence'] );
                }
            }break;

            // Shorten string [default or specified length, length=text+"..."] and add '...'
            case $this->ShortenName:
            {
                $strlenFunc = function_exists( 'mb_strlen' ) ? 'mb_strlen' : 'strlen';
                $substrFunc = function_exists( 'mb_substr' ) ? 'mb_substr' : 'substr';
                if ( $strlenFunc( $operatorValue ) > $namedParameters['chars_to_keep'] )
                {
                    $operatorLength = $strlenFunc( $operatorValue );

                    if ( $namedParameters['trim_type'] === 'middle' )
                    {
                        $appendedStrLen = $strlenFunc( $namedParameters['str_to_append'] );

                        if ( $namedParameters['chars_to_keep'] > $appendedStrLen )
                        {
                            $chop = $namedParameters['chars_to_keep'] - $appendedStrLen;

                            $middlePos = (int)($chop / 2);
                            $leftPartLength = $middlePos;
                            $rightPartLength = $chop - $middlePos;

                            $operatorValue = trim( $substrFunc( $operatorValue, 0, $leftPartLength ) . $namedParameters['str_to_append'] . $substrFunc( $operatorValue, $operatorLength - $rightPartLength, $rightPartLength ) );
                        }
                        else
                        {
                            $operatorValue = $namedParameters['str_to_append'];
                        }
                    }
                    else // default: trim_type === 'right'
                    {
                        $chop = $namedParameters['chars_to_keep'] - $strlenFunc( $namedParameters['str_to_append'] );
                        $operatorValue = $substrFunc( $operatorValue, 0, $chop );
                        $operatorValue = trim( $operatorValue );
                        if ( $operatorLength > $chop )
                            $operatorValue = $operatorValue.$namedParameters['str_to_append'];
                    }
                }
            }break;

            // Wash (translate strings to non-spammable text):
            case $this->WashName:
            {
                $type = $namedParameters['type'];
                $operatorValue = $this->wash( $operatorValue, $tpl, $type );
            }break;

            // Ord (translate a unicode string to actual unicode id/numbers):
            case $this->OrdName:
            {
                $codec = eZTextCodec::instance( false, 'unicode' );
                $output = $codec->convertString( $operatorValue );
                $operatorValue = $output;
            }break;

            // Chr (generate unicode characters based on input):
            case $this->ChrName:
            {
                $codec = eZTextCodec::instance( 'unicode', false );
                $output = $codec->convertString( $operatorValue );
                $operatorValue = $output;
            }break;

            // Default case: something went wrong - unknown things...
            default:
            {
                $tpl->warning( $operatorName, "Unknown string type '$type'", $placement );
            } break;
        }
    }

    /// The array of operators, used for registering operators
    public $Operators;
}

?>
