<?php
//
// Definition of eZTemplateElementParser class
//
// Created on: <27-Nov-2002 10:53:36 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file eztemplateelementparser.php
*/

/*!
  \class eZTemplateElementParser eztemplateelementparser.php
  \brief The class eZTemplateElementParser does

*/

define( "EZ_TEMPLATE_TYPE_VOID", 0 );
define( "EZ_TEMPLATE_TYPE_STRING", 1 );
define( "EZ_TEMPLATE_TYPE_NUMERIC", 2 );
define( "EZ_TEMPLATE_TYPE_IDENTIFIER", 3 );
define( "EZ_TEMPLATE_TYPE_VARIABLE", 4 );
define( "EZ_TEMPLATE_TYPE_ATTRIBUTE", 5 );
define( "EZ_TEMPLATE_TYPE_OPERATOR", 6 );

define( "EZ_TEMPLATE_TYPE_STRING_BIT", (1 << (EZ_TEMPLATE_TYPE_STRING - 1)) );
define( "EZ_TEMPLATE_TYPE_NUMERIC_BIT", (1 << (EZ_TEMPLATE_TYPE_NUMERIC - 1)) );
define( "EZ_TEMPLATE_TYPE_IDENTIFIER_BIT", (1 << (EZ_TEMPLATE_TYPE_IDENTIFIER - 1)) );
define( "EZ_TEMPLATE_TYPE_VARIABLE_BIT", (1 << (EZ_TEMPLATE_TYPE_VARIABLE - 1)) );
define( "EZ_TEMPLATE_TYPE_ATTRIBUTE_BIT", (1 << (EZ_TEMPLATE_TYPE_ATTRIBUTE - 1)) );
define( "EZ_TEMPLATE_TYPE_OPERATOR_BIT", (1 << (EZ_TEMPLATE_TYPE_OPERATOR - 1)) );

define( "EZ_TEMPLATE_TYPE_NONE", 0 );

define( "EZ_TEMPLATE_TYPE_ALL", (EZ_TEMPLATE_TYPE_STRING_BIT |
                                 EZ_TEMPLATE_TYPE_NUMERIC_BIT |
                                 EZ_TEMPLATE_TYPE_IDENTIFIER_BIT |
                                 EZ_TEMPLATE_TYPE_VARIABLE_BIT |
                                 EZ_TEMPLATE_TYPE_ATTRIBUTE_BIT |
                                 EZ_TEMPLATE_TYPE_OPERATOR_BIT ) );

define( "EZ_TEMPLATE_TYPE_BASIC", (EZ_TEMPLATE_TYPE_STRING_BIT |
                                   EZ_TEMPLATE_TYPE_NUMERIC_BIT |
                                   EZ_TEMPLATE_TYPE_IDENTIFIER_BIT |
                                   EZ_TEMPLATE_TYPE_VARIABLE_BIT |
                                   EZ_TEMPLATE_TYPE_OPERATOR_BIT ) );

define( "EZ_TEMPLATE_TYPE_MODIFIER_MASK", (EZ_TEMPLATE_TYPE_ATTRIBUTE_BIT |
                                           EZ_TEMPLATE_TYPE_OPERATOR_BIT) );

define( "EZ_TEMPLATE_NAMESPACE_SCOPE_GLOBAL", 1 );
define( "EZ_TEMPLATE_NAMESPACE_SCOPE_LOCAL", 2 );
define( "EZ_TEMPLATE_NAMESPACE_SCOPE_RELATIVE", 3 );

class eZTemplateElementParser
{
    /*!
     Constructor
    */
    function eZTemplateElementParser()
    {
    }

    function templateTypeName( $type )
    {
        switch ( $type )
        {
            case EZ_TEMPLATE_TYPE_STRING:
                return "string";
            case EZ_TEMPLATE_TYPE_NUMERIC:
                return "numeric";
            case EZ_TEMPLATE_TYPE_IDENTIFIER:
                return "identifier";
            case EZ_TEMPLATE_TYPE_VARIABLE:
                return "variable";
            case EZ_TEMPLATE_TYPE_ATTRIBUTE:
                return "attribute";
        }
        return null;
    }

    /*!
     Parses the variable and operators into a structure.
    */
    function parseVariableTag( &$tpl, &$text, $startPosition, &$endPosition, $textLength, $defaultNamespace,
                               $allowedType = false, $maxElements = false, $endMarker = false,
                               $undefinedType = EZ_TEMPLATE_TYPE_ATTRIBUTE )
    {
        eZDebug::writeDebug( "text='" . substr( $text, $startPosition ) . "', startPosition=$startPosition, textLength=$textLength,\nallowedType=$allowedType, maxElements=$maxElements, endMarker=$endMarker, undefinedType=$undefinedType", "parseVariableTag" );
        $currentPosition = $startPosition;
        $elements = array();
        $lastPosition = false;
        if ( $allowedType === false )
            $allowedType = EZ_TEMPLATE_TYPE_BASIC;
        while ( $currentPosition < $textLength and
                ( $maxElements === false or
                  count( $elements ) < $maxElements ) )
        {
            if ( $lastPosition !== false and
                 $lastPosition == $currentPosition )
            {
                eZDebug::writeDebug( "Position didn't move, aborting", 'eZTemplateElementParser::parseVariableTag' );
                break;
            }
            $lastPosition = $currentPosition;
            $currentPosition = $this->whitespaceEndPos( $tpl, $text, $currentPosition, $textLength );
            if ( $endMarker !== false )
            {
                if ( $currentPosition < $textLength and
                     strpos( $endMarker, $text[$currentPosition] ) !== false )
                    break;
            }
            if ( $text[$currentPosition] == '|' )
            {
                if ( !( $allowedType & EZ_TEMPLATE_TYPE_OPERATOR_BIT ) )
                {
                    $currentPosition = $lastPosition;
                    break;
                }
                $maxOperatorElements = 1;
                $operatorEndMarker = false;
                $currentOperatorPosition = $currentPosition + 1;
                $operatorEndPosition = false;
                eZDebug::writeDebug( "currentOperatorPosition=$currentOperatorPosition, text=" . substr( $text, $currentOperatorPosition ),
                                     'operator marker start' );
                $operatorElements = $this->parseVariableTag( $tpl, $text, $currentOperatorPosition, $operatorEndPosition, $textLength, $defaultNamespace,
                                                             EZ_TEMPLATE_TYPE_OPERATOR_BIT, $maxOperatorElements, $operatorEndMarker, EZ_TEMPLATE_TYPE_OPERATOR );
                eZDebug::writeDebug( "currentOperatorPosition=$currentOperatorPosition, operatorEndPosition=$operatorEndPosition, text=" . substr( $text, $currentOperatorPosition, $operatorEndPosition - $currentOperatorPosition ),
                                     'operator marker' );
                if ( $operatorEndPosition > $currentOperatorPosition )
                {
                    $elements = array_merge( $elements, $operatorElements );
                    $currentPosition = $operatorEndPosition;
                }
            }
            else if ( $text[$currentPosition] == '.' or
                 $text[$currentPosition] == '[' )
            {
                if ( !( $allowedType & EZ_TEMPLATE_TYPE_ATTRIBUTE_BIT ) )
                {
                    $currentPosition = $lastPosition;
                    break;
                }
                $maxAttributeElements = 1;
                $attributeEndMarker = false;
                if ( $text[$currentPosition] == '[' )
                {
                    $maxAttributeElements = false;
                    $attributeEndMarker = ']';
                }
                ++$currentPosition;
                $attributeEndPosition = false;
                $attributeElements = $this->parseVariableTag( $tpl, $text, $currentPosition, $attributeEndPosition, $textLength, $defaultNamespace,
                                                              EZ_TEMPLATE_TYPE_BASIC, $maxAttributeElements, $attributeEndMarker );
                if ( $attributeEndPosition > $currentPosition )
                {
                    $element = array( EZ_TEMPLATE_TYPE_ATTRIBUTE, // type
                                      $attributeElements, // content
                                      false // debug
                                      );
                    $elements[] = $element;
                    if ( $attributeEndMarker !== false )
                        $attributeEndPosition += strlen( $attributeEndMarker );
                    $currentPosition = $attributeEndPosition;
                }
            }
            else if ( $text[$currentPosition] == "$" )
            {
                if ( !( $allowedType & EZ_TEMPLATE_TYPE_VARIABLE_BIT ) )
                {
                    $currentPosition = $lastPosition;
                    break;
                }
                ++$currentPosition;
                $variableEndPosition = $this->variableEndPos( $tpl, $text, $currentPosition, $textLength,
                                                              $variableNamespace, $variableName, $namespaceScope );
                if ( $variableEndPosition > $currentPosition )
                {
                    $element = array( EZ_TEMPLATE_TYPE_VARIABLE, // type
                                      array( $variableNamespace,
                                             $namespaceScope,
                                             $variableName ), // content
                                      false // debug
                                      );
                    $elements[] = $element;
                    $currentPosition = $variableEndPosition;
                    $allowedType = EZ_TEMPLATE_TYPE_MODIFIER_MASK;
                }
            }
            else if ( $text[$currentPosition] == "'" or
                      $text[$currentPosition] == '"' )
            {
                if ( !( $allowedType & EZ_TEMPLATE_TYPE_STRING_BIT) )
                {
                    $currentPosition = $lastPosition;
                    break;
                }
                $quote = $text[$currentPosition];
                ++$currentPosition;
                $quoteEndPosition = $this->quoteEndPos( $tpl, $text, $currentPosition, $textLength, $quote );
                $element = array( EZ_TEMPLATE_TYPE_STRING, // type
                                  substr( $text, $currentPosition, $quoteEndPosition - $currentPosition ), // content
                                  false // debug
                                  );
                $elements[] = $element;
                $currentPosition = $quoteEndPosition + 1;
                $allowedType = EZ_TEMPLATE_TYPE_NONE;
            }
            else
            {
                $float = true;
                $numericEndPosition = $this->numericEndPos( $tpl, $text, $currentPosition, $textLength, $float );
                if ( $numericEndPosition > $currentPosition )
                {
                    if ( !( $allowedType & EZ_TEMPLATE_TYPE_NUMERIC_BIT ) )
                    {
                        $currentPosition = $lastPosition;
                        break;
                    }
                    // We got a number
                    $number = substr( $text, $currentPosition, $numericEndPosition - $currentPosition );
                    if ( $float )
                        $number = (float)$number;
                    else
                        $number = (int)$number;
                    $element = array( EZ_TEMPLATE_TYPE_NUMERIC, // type
                                      $number, // content
                                      false // debug
                                      );
                    $elements[] = $element;
                    $currentPosition = $numericEndPosition;
                    $allowedType = EZ_TEMPLATE_TYPE_NONE;
                }
                else
                {
                    $identifierEndPosition = $this->identifierEndPosition( $tpl, $text, $currentPosition, $textLength );
                    eZDebug::writeDebug( "identifierEndPosition=$identifierEndPosition, currentPosition=$currentPosition, textLength=$textLength", 'identifier' );
                    if ( $currentPosition == $identifierEndPosition )
                    {
                        $currentPosition = $lastPosition;
                        break;
                    }
                    if ( ( $identifierEndPosition < $textLength and
                           $text[$identifierEndPosition] == '(' ) or
                         $undefinedType == EZ_TEMPLATE_TYPE_OPERATOR )
                    {
                        if ( !( $allowedType & EZ_TEMPLATE_TYPE_OPERATOR_BIT ) )
                        {
                            $currentPosition = $lastPosition;
                            break;
                        }
                        $operatorName = substr( $text, $currentPosition, $identifierEndPosition - $currentPosition );
                        $operatorParameterElements = array( $operatorName );

                        if ( $identifierEndPosition < $textLength and
                             $text[$identifierEndPosition] == '(' )
                        {
                            eZDebug::writeDebug( "Operator with parameters" );
                            $currentPosition = $identifierEndPosition + 1;
                            $currentOperatorPosition = $currentPosition;
                            $operatorDone = false;
                            $parameterCount = 0;
                            while ( !$operatorDone )
                            {
                                $operatorEndPosition = false;
                                $operatorParameterElement = $this->parseVariableTag( $tpl, $text, $currentOperatorPosition, $operatorEndPosition, $textLength, $defaultNamespace,
                                                                                     EZ_TEMPLATE_TYPE_BASIC, false, ',)' );
                                eZDebug::writeDebug( "operatorName=$operatorName, currentOperatorPosition=$currentOperatorPosition, operatorEndPosition=$operatorEndPosition, textLength=$textLength, text='" . substr( $text, $currentOperatorPosition, $operatorEndPosition - $currentOperatorPosition ) . "'", 'operator' );
                                if ( $operatorEndPosition < $textLength and
                                     $text[$operatorEndPosition] == ',' )
                                {
                                    if ( $operatorEndPosition == $currentOperatorPosition )
                                    {
                                        $operatorParameterElements[] = null;
                                    }
                                    else
                                        $operatorParameterElements[] = $operatorParameterElement;
                                    ++$parameterCount;
                                    $currentOperatorPosition = $operatorEndPosition + 1;
                                }
                                else if ( $operatorEndPosition < $textLength and
                                          $text[$operatorEndPosition] == ')' )
                                {
                                    $operatorDone = true;
                                    if ( $operatorEndPosition == $currentOperatorPosition )
                                    {
                                        if ( $parameterCount > 0 )
                                        {
                                            $operatorParameterElements[] = null;
                                            ++$parameterCount;
                                        }
                                    }
                                    else
                                    {
                                        $operatorParameterElements[] = $operatorParameterElement;
                                        ++$parameterCount;
                                    }
                                    ++$operatorEndPosition;
                                }
                                else
                                {
                                    $currentPosition = $lastPosition;
                                    break;
//                                 $currentOperatorPosition = $operatorEndPosition;
//                                 $operatorParameterElements[] = $operatorParameterElement;
                                }
                            }
                            if ( !$operatorDone )
                                break;
                        }
                        else
                        {
                            eZDebug::writeDebug( "Operator without parameters" );
                            $operatorEndPosition = $identifierEndPosition;
                        }

                        eZDebug::writeDebug( "operatorName=$operatorName, currentPosition=$currentPosition, operatorEndPosition=$operatorEndPosition, textLength=$textLength, text='" . substr( $text, $currentPosition, $operatorEndPosition - $currentPosition ) . "'", 'operator' );
                        $element = array( EZ_TEMPLATE_TYPE_OPERATOR, // type
                                          $operatorParameterElements, // content
                                          false // debug
                                          );
                        $elements[] = $element;
//                         $operatorEndPosition += strlen( $operatorEndMarker );
                        $currentPosition = $operatorEndPosition;
                        $allowedType = EZ_TEMPLATE_TYPE_MODIFIER_MASK;
                    }
                    else
                    {
                        eZDebug::writeDebug( "Found identifier: allowedType=$allowedType, identifierEndPosition=$identifierEndPosition, currentPosition=$currentPosition, textLength=$textLength", 'idenifier' );
                        if ( !( $allowedType & EZ_TEMPLATE_TYPE_IDENTIFIER_BIT ) )
                        {
                            eZDebug::writeDebug( "Identifier not allowed: identifierEndPosition=$identifierEndPosition, currentPosition=$currentPosition, textLength=$textLength", 'idenifier' );
                            $currentPosition = $lastPosition;
                            break;
                        }
                        $identifier = substr( $text, $currentPosition, $identifierEndPosition - $currentPosition );
                        eZDebug::writeDebug( "Found identifier '$identifier'" );
                        $element = array( EZ_TEMPLATE_TYPE_IDENTIFIER, // type
                                          $identifier, // content
                                          false // debug
                                          );
                        $elements[] = $element;
                        $currentPosition = $identifierEndPosition;
                        $allowedType = EZ_TEMPLATE_TYPE_NONE;
                    }
                }
            }
        }
        $endPosition = $currentPosition;
        return $elements;
    }

    /*!
     Parses the variable and operators into a structure.
    */
    function &parseVariableTag2( &$tpl, &$text, $start_pos, &$end, $len, $def_nspace )
    {
        eZDebug::writeDebug( "text='$text', start_pos=$start_pos, len=$len", "parseVariableTag2" );
        $pos = $start_pos;
        $struct = array();
        $end_pos = $pos;
//         while( $pos < $len )
//         {
            if ( $text[$pos] == "$" )
            {
                ++$pos;
//                 eZDebug::writeDebug( "parseVariableAndAttributes", "parseVariableTag:choice" );
                $struct = $this->parseVariableAndAttributes( $tpl, $text, $pos, $var_end, $len, $def_nspace );
                $end_pos = $var_end;
            }
            else if ( $text[$pos] == "'" or
                      $text[$pos] == '"' )
            {
                $quote = $text[$pos];
                ++$pos;
//                 eZDebug::writeDebug( "quoteEndPos", "parseVariableTag:choice" );
                $end_pos = $this->quoteEndPos( $tpl, $text, $pos, $len, $quote );
                $struct["type"] = "text";
                $struct["content"] = substr( $text, $pos, $end_pos - $pos );
                ++$end_pos;
            }
            else
            {
                $end_pos = $this->numericEndPos( $tpl, $text, $pos, $len );
                if ( $end_pos == $pos )
                {
                    $end_pos = $this->identifierEndPosition( $tpl, $text, $pos, $len );
                    if ( $end_pos < $len and
                         $text[$end_pos] == "(" )
                    {
//                         eZDebug::writeDebug( "parseOperators", "parseVariableTag:choice" );
                        $operators = $this->parseOperators( $tpl, $text, $pos, $end_pos, $len, $def_nspace, true );
                        $struct["type"] = "operators";
                        $struct["operators"] = $operators;
                    }
                    else
                    {
                        $struct["type"] = "text";
                        $struct["content"] = substr( $text, $pos, $end_pos - $pos );
                    }
                }
                else
                {
                    $struct["type"] = "numerical";
                    $struct["content"] = substr( $text, $pos, $end_pos - $pos );
                }
            }
//             if ( $end_pos > $start_pos and
//                  ( $text[$end_pos] == "." or
//                    $text[$end_pos] == "[" ) )
//             {
//                 if ( $text[$end_pos] == "." )
//                 {
//                     ++$end_pos;
//                     $attribute = $this->parseAttribute( $tpl, $text, $end_pos, $attr_end, $len, $def_nspace );
//                     if ( count( $struct ) == 0 )
//                         $struct = $attribute;
//                     else
//                     {
//                         if ( $struct["type"] == "container" )
//                         {
//                         }
//                         else
//                         {
//                             $old_struct = $struct;
//                             $struct = array();
//                             $struct["type"] = "container";
//                             $struct["content"] = $old_struct;
//                             $struct["attributes"] = $attribute;
//                         }
//                     }
//                     $end_pos = $attr_end;
//                 }
//                 else if ( $text[$end_pos] == "[" )
//                 {
//                     ++$end_pos;
//                     $attribute = $this->parseAttribute( $tpl, $text, $end_pos, $attr_end, $len, $def_nspace );
//                     $end_pos = $attr_end;
//                     if ( $text[$attr_end] == "]" )
//                     {
//                         ++$end_pos;
//                     }
//                     else
//                         echo( "Indexing didn't end with a ]\n" );
//                 }
//                 $attributes[] = $attribute;
//             }
            if ( $end_pos < $len and
                 $text[$end_pos] == "|" )
            {
                $pos = $end_pos;
//                 eZDebug::writeDebug( "parseOperators#2", "parseVariableTag:choice" );
                $struct["operators"] = $this->parseOperators( $tpl, $text, $pos, $end_pos, $len, $def_nspace );
            }
            if ( $pos == $end_pos )
            {
                break;
            }
            $pos = $end_pos;
//         }
        $end = $end_pos;
        return $struct;
    }

    /*!
     Parses operator syntax.
    */
    function &parseOperators( &$tpl, &$text, $start_pos, &$end, $len, $def_nspace, $start_with_oper = false )
    {
//         eZDebug::writeDebug( "text='$text', start_pos=$start_pos, len=$len, start_with_oper=$start_with_oper", "parseOperators" );
        $pos = $start_pos;
        $operators = array();
        $i = 0;
        while ( $pos < $len and
                ( ( $i == 0 and $start_with_oper ) or $text[$pos] == "|" ) )
        {
            if ( $i != 0 or !$start_with_oper )
                ++$pos;
            $end_pos = $this->identifierEndPosition( $tpl, $text, $pos, $len );
            $operator_name = substr( $text, $pos, $end_pos - $pos );
            $pos = $end_pos;
            $params = array();
            if ( $pos < $len and
                 $text[$pos] == "(" )
            {
                ++$pos;
                while ( $pos < $len and
                        $text[$pos] != ")" )
                {
                    if ( $text[$pos] == "," )
                    {
                        $param = $tpl->emptyVariable();
                        ++$pos;
                    }
                    else
                    {
                        $param = $this->parseVariableTag( $tpl, $text, $pos, $end_pos, $len, $def_nspace );
                        $pos = $end_pos;
                        if ( $text[$pos] == "," )
                            ++$pos;
                        else if ( $text[$pos] != ")" )
                            eZDebug::writeWarning( "Parameter didn't end with a ) or , \{pos=$pos,text='" .
                                                   $text[$pos] . "',before=\"" .
                                                   substr( $text, max( $pos - 5, 0 ), 5 ) . "\",after=\"" .
                                                   substr( $text, min( $pos + 1, $len - 1 ), 5 ) . "\"}", "eZTemplate" );
                    }
                    $params[] = $param;
                }
                ++$pos;
            }
            $operator = array( EZ_TEMPLATE_NODE_OPERATOR, false, $operator_name, $params );
//             $operator = new eZTemplateOperatorElement( $operator_name, $params );
//             $tpl->setRelation( $operator, $tpl->CurrentRelatedResource, $tpl->CurrentRelatedTemplateName );
            $operators[] = $operator;
            ++$i;
        }
        $end = $pos;
//         eZDebug::writeDebug( "text='$text', end=$end, len=$len", "parseOperators:end" );
        return $operators;
    }

    /*!
     Parses attribute syntax.
    */
    function &parseAttribute( &$tpl, &$text, $start_pos, &$end, $len, $def_nspace )
    {
//         eZDebug::writeDebug( "text='$text', start_pos=$start_pos, len=$len", "parseAttribute" );
        $pos = $start_pos;
        $struct = array();
        if ( $text[$pos] == "$" )
        {
            $var = $this->parseVariableTag( $tpl, $text, $pos, $end_pos, $len, $def_nspace );
            $struct["type"] = "variable";
            $struct["content"] = $var;
        }
        else if ( preg_match( "/^[0-9]$/", $text[$pos] ) )
        {
            $end_pos = $this->numericEndPos( $tpl, $text, $pos, $len );
            $struct["type"] = "index";
            $struct["content"] = substr( $text, $pos, $end_pos - $pos );
        }
        else
        {
            $end_pos = $this->identifierEndPosition( $tpl, $text, $pos, $len );
            $struct["type"] = "map";
            $struct["content"] = substr( $text, $pos, $end_pos - $pos );
        }
        $end = $end_pos;
        return $struct;
    }

    /*!
     Parses variables and attributes.
    */
    function &parseVariableAndAttributes( &$tpl, &$text, $start_pos, &$end, $len, $def_nspace )
    {
//         eZDebug::writeDebug( "text='$text', start_pos=$start_pos, len=$len", "parseVariableAndAttributes" );
        $pos = $start_pos;
        $nspace = false;
        $var = "";
        $end_pos = $pos;
        do
        {
            if ( $text[$end_pos] == ":" )
            {
                if ( $nspace !== false )
                    $nspace = "$nspace:$var";
                else
                    $nspace = $var;
                ++$pos;
            }
            $end_pos = $this->identifierEndPosition( $tpl, $text, $pos, $len );
            $var = substr( $text, $pos, $end_pos - $pos );
            $pos = $end_pos;
        } while( $end_pos < $len and $text[$end_pos] == ":" );
        if ( $def_nspace != "" )
        {
            if ( $nspace != "" )
                $nspace = "$def_nspace:$nspace";
            else
                $nspace = $def_nspace;
        }
        $struct["namespace"] = $nspace;
        $struct["type"] = "variable";
        $struct["name"] = $var;
        $attributes = array();
        $pos = $end_pos;
        while ( $pos < $len and
                ( $text[$pos] == "." or $text[$pos] == "[" ) )
        {
            if ( $text[$pos] == "." )
            {
                ++$pos;
                $attribute = $this->parseAttribute( $tpl, $text, $pos, $attr_end, $len, $def_nspace );
                $pos = $attr_end;
            }
            else if ( $text[$pos] == "[" )
            {
                ++$pos;
                $attribute = $this->parseAttribute( $tpl, $text, $pos, $attr_end, $len, $def_nspace );
                $pos = $attr_end;
                if ( $text[$attr_end] == "]" )
                {
                    ++$pos;
                }
                else
                    eZDebug::writeWarning( "Indexing didn't end with a ]", "eZTemplate::parseVariableAndAttributes" );
            }
            $attributes[] = $attribute;
        }
        $struct["attributes"] = $attributes;
        $end = $pos;
        return $struct;
    }

    /*!
     Returns the end position of the variable.
    */
    function variableEndPos( &$tpl, &$text, $startPosition, $textLength,
                             &$namespace, &$name, &$scope )
    {
        $currentPosition = $startPosition;
        $namespaces = array();
        $variableName = false;
        $lastPosition = false;
        $scopeType = EZ_TEMPLATE_NAMESPACE_SCOPE_LOCAL;
        $scopeRead = false;
        while ( $currentPosition < $textLength )
        {
            if ( $lastPosition !== false and
                 $lastPosition == $currentPosition )
            {
                eZDebug::writeDebug( "Position didn't move, aborting", 'eZTemplateElementParser::parseVariableTag' );
                break;
            }
            $lastPosition = $currentPosition;
            if ( $text[$currentPosition] == '#' )
            {
                if ( $scopeRead )
                    eZDebug::writeWarning( 'Namespace scope already declared, cannot set to global', 'eZTemplateElementParser::variableEndPos' );
                else
                    $scopeType = EZ_TEMPLATE_NAMESPACE_SCOPE_GLOBAL;
                $scopeRead = true;
                ++$currentPosition;
            }
            else if ( $text[$currentPosition] == ':' )
            {
                if ( $scopeRead )
                    eZDebug::writeWarning( 'Namespace scope already declared, cannot set to relative', 'eZTemplateElementParser::variableEndPos' );
                else
                    $scopeType = EZ_TEMPLATE_NAMESPACE_SCOPE_RELATIVE;
                $scopeRead = true;
                ++$currentPosition;
            }
            else
            {
                $identifierEndPosition = $this->identifierEndPosition( $tpl, $text, $currentPosition, $textLength );
                eZDebug::writeDebug( "currentPosition=$currentPosition, identifierEndPosition=$identifierEndPosition, textLength=$textLength" );
                if ( $identifierEndPosition > $currentPosition )
                {
                    $identifier = substr( $text, $currentPosition, $identifierEndPosition - $currentPosition );
                    $currentPosition = $identifierEndPosition;
                    if ( $identifierEndPosition < $textLength and
                         $text[$identifierEndPosition] == ':' )
                    {
                        $namespaces[] = $identifier;
                        ++$currentPosition;
                    }
                    else
                        $variableName = $identifier;
                }
                else if ( $identifierEndPosition < $textLength and
                          ( $text[$identifierEndPosition] != ":" and
                            $text[$identifierEndPosition] != "#" ) )
                {
                    if ( $variableName === false )
                    {
                        eZDebug::writeError( 'No variable name found', 'eZTemplateElementParser::variableEndPos' );
                        return $startPosition;
                    }
                    break;
                }
                else
                {
                    eZDebug::writeError( 'Missing identifier for variable name or namespace', 'eZTemplateElementParser::variableEndPos' );
                    return $startPosition;
                }
            }
        }
        $scope = $scopeType;
        $namespace = implode( ':', $namespaces );
        $name = $variableName;
        return $currentPosition;
    }

    /*!
     Returns the end position of the identifier.
     If no identifier was found the end position is returned.
    */
    function identifierEndPosition( &$tpl, &$text, $start_pos, $len )
    {
        $pos = $start_pos;
        while ( $pos < $len )
        {
            if ( !preg_match( "/^[a-zA-Z0-9_-]$/", $text[$pos] ) )
            {
                eZDebug::writeDebug( "start_pos=$start_pos, pos=$pos, " . substr( $text, $start_pos, $pos - $start_pos ), 'identifierEndPosition' );
                return $pos;
            }
            ++$pos;
        }
        eZDebug::writeDebug( "start_pos=$start_pos, pos=$pos, " . substr( $text, $start_pos, $pos - $start_pos ), 'identifierEndPosition' );
        return $pos;
    }

    /*!
     Returns the end position of the quote $quote.
     If no quote was found the end position is returned.
    */
    function quoteEndPos( &$tpl, &$text, $startPosition, $textLength, $quote )
    {
        $currentPosition = $startPosition;
        while ( $currentPosition < $textLength )
        {
            if ( $text[$currentPosition] == "\\" )
                ++$currentPosition;
            else if ( $text[$currentPosition] == $quote )
                return $currentPosition;
            ++$currentPosition;
        }
        return $currentPosition;
    }

    /*!
     Returns the end position of the numeric.
     If no numeric was found the end position is returned.
    */
    function numericEndPos( &$tpl, &$text, $start_pos, $len,
                            &$float )
    {
//         eZDebug::writeDebug( substr( $text, $start_pos ) );
//         eZDebug::writeDebug( $float ? 'true' : 'false' );
        $pos = $start_pos;
        $has_comma = false;
        $numberPos = $pos;
        if ( $pos < $len )
        {
            if ( $text[$pos] == '-' )
            {
                ++$pos;
                $numberPos = $pos;
                eZDebug::writeDebug( "Possible negative number at $pos", 'numericEndPos' );
            }
        }
        while ( $pos < $len )
        {
//             eZDebug::writeDebug( substr( $text, $pos ) );
            if ( $text[$pos] == "." and $float )
            {
                if ( $has_comma )
                    return $pos;
                $has_comma = $pos;
            }
            else if ( !preg_match( "/^[0-9]$/", $text[$pos] ) )
            {
                eZDebug::writeDebug( "pos=$pos, numberPos=$numberPos", 'numericEndPos' );
                if ( $pos < $len and
                     $has_comma and
                     $pos == $has_comma + 1 )
                    return $start_pos;
                if ( $pos == $numberPos )
                    return $start_pos;
                return $pos;
            }
            ++$pos;
        }
        if ( $has_comma and
             $start_pos + 1 == $pos )
        {
            return $start_pos;
        }
        if ( !$has_comma and
             $float )
            $float = false;
        eZDebug::writeDebug( substr( $text, $start_pos, $pos - $start_pos ), 'numericEndPos' );
        return $pos;
    }

    /*!
     Returns the position of the first non-whitespace characters.
    */
    function whitespaceEndPos( &$tpl, &$text, $currentPosition, $textLength )
    {
        if ( $currentPosition >= $textLength )
            return $currentPosition;
        while( $currentPosition < $textLength and
               preg_match( "/[ \t\r\n]/", $text[$currentPosition] ) )
        {
            ++$currentPosition;
        }
        return $currentPosition;
    }

    /*!
     Returns the position of the first non-whitespace characters.
    */
    function isWhitespace( &$tpl, &$text, $startPosition )
    {
        return preg_match( "/[ \t\r\n]/", $text[$startPosition] );
    }

    function &instance()
    {
        $instance =& $GLOBALS['eZTemplateElementParserInstance'];
        if ( get_class( $instance ) != 'eztemplateelementparser' )
        {
            $instance = new eZTemplateElementParser();
        }
        return $instance;
    }

}

?>
