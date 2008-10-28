<?php
//
// Definition of eZTemplateElementParser class
//
// Created on: <27-Nov-2002 10:53:36 amos>
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

/*! \file eztemplateelementparser.php
*/

/*!
  \class eZTemplateElementParser eztemplateelementparser.php
  \brief The class eZTemplateElementParser does

*/

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
            case eZTemplate::TYPE_STRING:
                return "string";
            case eZTemplate::TYPE_NUMERIC:
                return "numeric";
            case eZTemplate::TYPE_IDENTIFIER:
                return "identifier";
            case eZTemplate::TYPE_VARIABLE:
                return "variable";
            case eZTemplate::TYPE_ATTRIBUTE:
                return "attribute";
        }
        return null;
    }

    /*!
     Parses the variable and operators into a structure.
    */
    function parseVariableTag( $tpl, $relatedTemplateName, &$text, $startPosition, &$endPosition, $textLength, $defaultNamespace,
                               $allowedType = false, $maxElements = false, $endMarker = false,
                               $undefinedType = eZTemplate::TYPE_ATTRIBUTE )
    {
        $currentPosition = $startPosition;
        $elements = array();
        $lastPosition = false;
        if ( $allowedType === false )
            $allowedType = eZTemplate::TYPE_BASIC;
        while ( $currentPosition < $textLength and
                ( $maxElements === false or
                  count( $elements ) < $maxElements ) )
        {
            if ( $lastPosition !== false and
                 $lastPosition == $currentPosition )
            {
                $tpl->error( "ElementParser::parseVariableTag", "parser error @ $relatedTemplateName[$currentPosition]\n" .
                             "Parser position did not move, this is most likely a bug in the template parser." );
                break;
            }
            $lastPosition = $currentPosition;
            $currentPosition = $this->whitespaceEndPos( $tpl, $text, $currentPosition, $textLength );
            if ( $currentPosition >= $textLength )
                continue;
            if ( $endMarker !== false )
            {
                if ( $currentPosition < $textLength and
                     strpos( $endMarker, $text[$currentPosition] ) !== false )
                    break;
            }
            if ( $text[$currentPosition] == '|' )
            {
                if ( !( $allowedType & eZTemplate::TYPE_OPERATOR_BIT ) )
                {
                    $currentPosition = $lastPosition;
                    break;
                }
                $maxOperatorElements = 1;
                $operatorEndMarker = false;
                $currentOperatorPosition = $currentPosition + 1;
                $operatorEndPosition = false;
                $operatorElements = $this->parseVariableTag( $tpl, $relatedTemplateName, $text, $currentOperatorPosition, $operatorEndPosition, $textLength, $defaultNamespace,
                                                             eZTemplate::TYPE_OPERATOR_BIT, $maxOperatorElements, $operatorEndMarker, eZTemplate::TYPE_OPERATOR );
                if ( $operatorEndPosition > $currentOperatorPosition )
                {
                    $elements = array_merge( $elements, $operatorElements );
                    $currentPosition = $operatorEndPosition;
                }
            }
            else if ( $text[$currentPosition] == '.' or
                 $text[$currentPosition] == '[' )
            {
                if ( !( $allowedType & eZTemplate::TYPE_ATTRIBUTE_BIT ) )
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
                $attributeElements = $this->parseVariableTag( $tpl, $relatedTemplateName, $text, $currentPosition, $attributeEndPosition, $textLength, $defaultNamespace,
                                                              eZTemplate::TYPE_BASIC, $maxAttributeElements, $attributeEndMarker );
                if ( $attributeEndPosition > $currentPosition )
                {
                    $element = array( eZTemplate::TYPE_ATTRIBUTE, // type
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
                if ( !( $allowedType & eZTemplate::TYPE_VARIABLE_BIT ) )
                {
                    $currentPosition = $lastPosition;
                    break;
                }
                ++$currentPosition;
                $variableEndPosition = $this->variableEndPos( $tpl, $relatedTemplateName, $text, $currentPosition, $textLength,
                                                              $variableNamespace, $variableName, $namespaceScope );
                if ( $variableEndPosition > $currentPosition )
                {
                    $element = array( eZTemplate::TYPE_VARIABLE, // type
                                      array( $variableNamespace,
                                             $namespaceScope,
                                             $variableName ), // content
                                      false // debug
                                      );
                    $elements[] = $element;
                    $currentPosition = $variableEndPosition;
                    $allowedType = eZTemplate::TYPE_MODIFIER_MASK;
                }
            }
            else if ( $text[$currentPosition] == "'" or
                      $text[$currentPosition] == '"' )
            {
                if ( !( $allowedType & eZTemplate::TYPE_STRING_BIT) )
                {
                    $currentPosition = $lastPosition;
                    break;
                }
                $quote = $text[$currentPosition];
                ++$currentPosition;
                $quoteEndPosition = $this->quoteEndPos( $tpl, $text, $currentPosition, $textLength, $quote );
                $string = substr( $text, $currentPosition, $quoteEndPosition - $currentPosition );
                $string = $this->unescapeCharacters( $string );
                $element = array( eZTemplate::TYPE_STRING, // type
                                  $string, // content
                                  false // debug
                                  );
                $elements[] = $element;
                $currentPosition = $quoteEndPosition + 1;
                $allowedType = eZTemplate::TYPE_OPERATOR_BIT;
            }
            else
            {
                $float = true;
                $numericEndPosition = $this->numericEndPos( $tpl, $text, $currentPosition, $textLength, $float );
                if ( $numericEndPosition > $currentPosition )
                {
                    if ( !( $allowedType & eZTemplate::TYPE_NUMERIC_BIT ) )
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
                    $element = array( eZTemplate::TYPE_NUMERIC, // type
                                      $number, // content
                                      false // debug
                                      );
                    $elements[] = $element;
                    $currentPosition = $numericEndPosition;
                    $allowedType = eZTemplate::TYPE_OPERATOR_BIT;
                }
                else
                {
                    $identifierEndPosition = $this->identifierEndPosition( $tpl, $text, $currentPosition, $textLength );
                    if ( $currentPosition == $identifierEndPosition )
                    {
                        $currentPosition = $lastPosition;
                        break;
                    }
                    if ( ( $identifierEndPosition < $textLength and
                           $text[$identifierEndPosition] == '(' ) or
                         $undefinedType == eZTemplate::TYPE_OPERATOR )
                    {
                        if ( !( $allowedType & eZTemplate::TYPE_OPERATOR_BIT ) )
                        {
                            $currentPosition = $lastPosition;
                            break;
                        }
                        $operatorName = substr( $text, $currentPosition, $identifierEndPosition - $currentPosition );
                        $operatorParameterElements = array( $operatorName );

                        if ( $identifierEndPosition < $textLength and
                             $text[$identifierEndPosition] == '(' )
                        {
                            $currentPosition = $identifierEndPosition + 1;
                            $currentOperatorPosition = $currentPosition;
                            $operatorDone = false;
                            $parameterCount = 0;
                            while ( !$operatorDone )
                            {
                                $operatorEndPosition = false;
                                $operatorParameterElement = $this->parseVariableTag( $tpl, $relatedTemplateName, $text, $currentOperatorPosition, $operatorEndPosition, $textLength, $defaultNamespace,
                                                                                     eZTemplate::TYPE_BASIC, false, ',)' );
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
                                }
                            }
                            if ( !$operatorDone )
                                break;
                        }
                        else
                        {
                            $operatorEndPosition = $identifierEndPosition;
                        }

                        $element = array( eZTemplate::TYPE_OPERATOR, // type
                                          $operatorParameterElements, // content
                                          false // debug
                                          );
                        $elements[] = $element;
                        $currentPosition = $operatorEndPosition;
                        $allowedType = eZTemplate::TYPE_MODIFIER_MASK;
                    }
                    else
                    {
                        if ( !( $allowedType & eZTemplate::TYPE_IDENTIFIER_BIT ) )
                        {
                            $currentPosition = $lastPosition;
                            break;
                        }
                        $identifier = substr( $text, $currentPosition, $identifierEndPosition - $currentPosition );
                        $element = array( eZTemplate::TYPE_IDENTIFIER, // type
                                          $identifier, // content
                                          false // debug
                                          );
                        $elements[] = $element;
                        $currentPosition = $identifierEndPosition;
                        $allowedType = eZTemplate::TYPE_NONE;
                    }
                }
            }
        }
        $endPosition = $currentPosition;
        return $elements;
    }

    /*!
     Returns the end position of the variable.
    */
    function variableEndPos( $tpl, $relatedTemplateName, &$text, $startPosition, $textLength,
                             &$namespace, &$name, &$scope )
    {
        $currentPosition = $startPosition;
        $namespaces = array();
        $variableName = false;
        $lastPosition = false;
        $scopeType = eZTemplate::NAMESPACE_SCOPE_LOCAL;
        $scopeRead = false;
        while ( $currentPosition < $textLength )
        {
            if ( $lastPosition !== false and
                 $lastPosition == $currentPosition )
            {
                $tpl->error( "ElementParser::variableEndPos", "parser error @ $relatedTemplateName\[" . $currentPosition . "]\n" .
                             "Parser position did not move, this is most likely a bug in the template parser." );
                break;
            }
            $lastPosition = $currentPosition;
            if ( $text[$currentPosition] == '#' )
            {
                if ( $scopeRead )
                {
                    $tpl->error( "ElementParser::variableEndPos", "parser error @ $relatedTemplateName\[" . $currentPosition . "]\n" .
                                 "Namespace scope already declared, cannot set to global." );
                }
                else
                {
                    $scopeType = eZTemplate::NAMESPACE_SCOPE_GLOBAL;
                }
                $scopeRead = true;
                ++$currentPosition;
            }
            else if ( $text[$currentPosition] == ':' )
            {
                if ( $scopeRead )
                {
                    $tpl->error( "ElementParser::variableEndPos", "parser error @ $relatedTemplateName\[" . $currentPosition . "]\n" .
                                 "Namespace scope already declared, cannot set to relative." );
                }
                else
                {
                    $scopeType = eZTemplate::NAMESPACE_SCOPE_RELATIVE;
                }
                $scopeRead = true;
                ++$currentPosition;
            }
            else
            {
                $identifierEndPosition = $this->identifierEndPosition( $tpl, $text, $currentPosition, $textLength );
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
                        $tpl->error( "ElementParser::variableEndPos", "parser error @ $relatedTemplateName\[" . $currentPosition . "]\n" .
                                     "No variable name found, this is most likely a bug in the template parser." );
                        return $startPosition;
                    }
                    break;
                }
                else
                {
                    $tpl->error( "ElementParser::variableEndPos", "parser error @ $relatedTemplateName\[" . $currentPosition . "]\n" .
                                 "Missing identifier for variable name or namespace, this is most likely a bug in the template parser." );
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
     Finds any escaped characters and unescapes them if they are one of:
     - \n - A newline
     - \r - A carriage return
     - \t - A tab
     - \' - A single quote
     - \" - A double quote

     If the escaped character is not known it keeps both characters (escape + character).
     \return The transformed string without escape characters.
    */
    function unescapeCharacters( $string )
    {
        $newString = '';
        $len = strlen( $string );

        // Fix escaped characters (double-quote, single-quote, newline, carriage-return, tab)
        for ( $i = 0; $i < $len; ++$i )
        {
            $c = $string[$i];

            // If we don't have an escape character we keep it as-is
            if ( $c != "\\" )
            {
                $newString .= $c;
                continue;
            }

            // If this is the last character we keep it as-is
            if ( $i + 1 >= $len )
            {
                $newString .= $c;
                break;
            }

            $c2 = $string[++$i];
            switch ( $c2 )
            {
                case 'n':
                {
                    $newString .= "\n";
                } break;

                case 'r':
                {
                    $newString .= "\r";
                } break;

                case 't':
                {
                    $newString .= "\t";
                } break;

                case "'":
                case '"':
                case '\\':
                {
                    $newString .= $c2;
                } break;

                // If it is not known we keep the characters.
                default:
                {
                    $newString .= $c . $c2;
                }
            }
        }
        return $newString;
    }

    /*!
     Returns the end position of the identifier.
     If no identifier was found the end position is returned.
    */
    function identifierEndPosition( $tpl, &$text, $start_pos, $len )
    {
        $pos = $start_pos;
        while ( $pos < $len )
        {
            if ( !preg_match( "/^[a-zA-Z0-9_-]$/", $text[$pos] ) )
            {
                return $pos;
            }
            ++$pos;
        }
        return $pos;
    }

    /*!
     Returns the end position of the quote $quote.
     If no quote was found the end position is returned.
    */
    function quoteEndPos( $tpl, &$text, $startPosition, $textLength, $quote )
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
    function numericEndPos( $tpl, &$text, $start_pos, $len,
                            &$float )
    {
        $pos = $start_pos;
        $has_comma = false;
        $numberPos = $pos;
        if ( $pos < $len )
        {
            if ( $text[$pos] == '-' )
            {
                ++$pos;
                $numberPos = $pos;
            }
        }
        while ( $pos < $len )
        {
            if ( $text[$pos] == "." and $float )
            {
                if ( $has_comma )
                {
                    if ( !$has_comma and
                         $float )
                        $float = false;
                    return $pos;
                }
                $has_comma = $pos;
            }
            else if ( $text[$pos] < '0' or $text[$pos] > '9' )
            {
                if ( !$has_comma and
                     $float )
                    $float = false;
                if ( $pos < $len and
                     $has_comma and
                     $pos == $has_comma + 1 )
                {
                    return $start_pos;
                }
                if ( $pos == $numberPos )
                {
                    return $start_pos;
                }
                return $pos;
            }
            ++$pos;
        }
        if ( !$has_comma and
             $float )
            $float = false;
        if ( $has_comma and
             $start_pos + 1 == $pos )
        {
            return $start_pos;
        }
        return $pos;
    }

    /*!
     Returns the position of the first non-whitespace characters.
    */
    function whitespaceEndPos( $tpl, &$text, $currentPosition, $textLength )
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
    function isWhitespace( $tpl, &$text, $startPosition )
    {
        return preg_match( "/[ \t\r\n]/", $text[$startPosition] );
    }

    static function instance()
    {
        if ( !isset( $GLOBALS['eZTemplateElementParserInstance'] ) ||
             !( $GLOBALS['eZTemplateElementParserInstance'] instanceof eZTemplateElementParser ) )
        {
            $GLOBALS['eZTemplateElementParserInstance'] = new eZTemplateElementParser();
        }

        return $GLOBALS['eZTemplateElementParserInstance'];
    }

}

?>
