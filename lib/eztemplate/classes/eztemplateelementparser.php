<?php
//
// Definition of eZTemplateElementParser class
//
// Created on: <27-Nov-2002 10:53:36 amos>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
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

class eZTemplateElementParser
{
    /*!
     Constructor
    */
    function eZTemplateElementParser()
    {
    }

    /*!
     Parses the variable and operators into a structure.
    */
    function &parseVariableTag( &$tpl, &$text, $start_pos, &$end, $len, $def_nspace )
    {
//         eZDebug::writeDebug( "text='$text', start_pos=$start_pos, len=$len", "parseVariableTag" );
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
                    $end_pos = $this->identifierEndPos( $tpl, $text, $pos, $len );
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
            $end_pos = $this->identifierEndPos( $tpl, $text, $pos, $len );
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
            $operator = new eZTemplateOperatorElement( $operator_name, $params );
            $tpl->setRelation( $operator, $tpl->CurrentRelatedResource, $tpl->CurrentRelatedTemplateName );
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
            $end_pos = $this->identifierEndPos( $tpl, $text, $pos, $len );
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
            $end_pos = $this->identifierEndPos( $tpl, $text, $pos, $len );
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
     Returns the end position of the identifier.
     If no identifier was found the end position is returned.
    */
    function identifierEndPos( &$tpl, &$text, $start_pos, $len )
    {
        $pos = $start_pos;
        while ( $pos < $len )
        {
            if ( !preg_match( "/^[a-zA-Z0-9_-]$/", $text[$pos] ) )
                return $pos;
            ++$pos;
        }
        return $pos;
    }

    /*!
     Returns the end position of the quote $quote.
     If no quote was found the end position is returned.
    */
    function quoteEndPos( &$tpl, &$text, $start_pos, $len, $quote )
    {
        $pos = $start_pos;
        while ( $pos < $len )
        {
            if ( $text[$pos] == "\\" )
                ++$pos;
            else if ( $text[$pos] == $quote )
                return $pos;
            ++$pos;
        }
        return $pos;
    }

    /*!
     Returns the end position of the numeric.
     If no numeric was found the end position is returned.
    */
    function numericEndPos( &$tpl, &$text, $start_pos, $len,
                            $allow_float = true )
    {
//         eZDebug::writeDebug( substr( $text, $start_pos ) );
//         eZDebug::writeDebug( $allow_float ? 'true' : 'false' );
        $pos = $start_pos;
        $has_comma = false;
        if ( $pos < $len )
        {
            if ( $text[$pos] == '-' )
                ++$pos;
        }
        while ( $pos < $len )
        {
//             eZDebug::writeDebug( substr( $text, $pos ) );
            if ( $text[$pos] == "." and $allow_float )
            {
                if ( $has_comma )
                    return $pos;
                $has_comma = true;
            }
            else if ( !preg_match( "/^[0-9]$/", $text[$pos] ) )
                return $pos;
            ++$pos;
        }
        if ( $has_comma and
             $start_pos + 1 == $pos )
            return $start_pos;
        return $pos;
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
