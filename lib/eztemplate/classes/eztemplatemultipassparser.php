<?php
//
// Definition of eZTemplateMultiPassParser class
//
// Created on: <26-Nov-2002 17:25:44 amos>
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

/*! \file eztemplatemultipassparser.php
*/

/*!
  \class eZTemplateMultiPassParser eztemplatemultipassparser.php
  \brief The class eZTemplateMultiPassParser does

*/

include_once( 'lib/eztemplate/classes/eztemplateparser.php' );
include_once( 'lib/eztemplate/classes/eztemplateelementparser.php' );
include_once( 'lib/eztemplate/classes/eztemplate.php' );

class eZTemplateMultiPassParser extends eZTemplateParser
{
    /*!
     Constructor
    */
    function eZTemplateMultiPassParser()
    {
        $this->ElementParser = eZTemplateElementParser::instance();
    }


    /*!
     Parses the template file $sourceText. See the description of this class
     for more information on the parsing process.

     \todo Use indexes in pass 1 and 2 instead of substrings, this means that strings are not extracted
     until they are needed.
    */
    function parse( &$tpl, &$sourceText, &$rootElement, $rootNamespace, $relation )
    {
        $relatedResource = $relation['resource'];
        $relatedTemplateName = $relation['template_name'];

        $tpl->setRelation( $rootElement, $relatedResource, $relatedTemplateName );
        $tpl->CurrentRelatedResource = $relatedResource;
        $tpl->CurrentRelatedTemplateName = $relatedTemplateName;
        $currentRoot =& $rootElement;
        $leftDelimiter = $tpl->LDelim;
        $rightDelimiter = $tpl->RDelim;
        $sourceLength = strlen( $sourceText );
        $sourcePosition = 0;

        $textElements =& $this->parseIntoTextElements( $tpl, $sourceText, $sourcePosition,
                                                       $leftDelimiter, $rightDelimiter, $sourceLength );

        $textElements =& $this->parseWhitespaceRemoval( $tpl, $textElements );

        $this->parseIntoTree( $tpl, $textElements, $currentRoot,
                              $rootNamespace, $relatedResource, $relatedTemplateName );
    }

    function &parseIntoTextElements( &$tpl, $sourceText, $sourcePosition,
                                     $leftDelimiter, $rightDelimiter, $sourceLength )
    {
        if ( $tpl->ShowDetails )
            eZDebug::addTimingPoint( "Parse pass 1 (simple tag parsing)" );
        $textElements = array();
        while( $sourcePosition < $sourceLength )
        {
            $tagPos = strpos( $sourceText, $leftDelimiter, $sourcePosition );
            if ( $tagPos === false )
            {
                // No more tags
                unset( $data );
                $data =& substr( $sourceText, $sourcePosition );
                $textElements[] = array( "Text" => $data,
                                     "Type" => EZ_ELEMENT_TEXT );
                $sourcePosition = $sourceLength;
            }
            else
            {
                $blockStart = $tagPos;
                $tagPos++;
                if ( $tagPos < $sourceLength and
                     $sourceText[$tagPos] == "*" ) // Comment
                {
                    $endPos = strpos( $sourceText, "*$rightDelimiter", $tagPos + 1 );
                    $len = $endPos - $tagPos;
                    if ( $sourcePosition < $blockStart )
                    {
                        // Add text before tag.
                        unset( $data );
                        $data =& substr( $sourceText, $sourcePosition, $blockStart - $sourcePosition );
                        $textElements[] = array( "Text" => $data,
                                             "Type" => EZ_ELEMENT_TEXT );
                    }
                    if ( $endPos === false )
                    {
                        $endPos = $sourceLength;
                        $blockEnd = $sourceLength;
                    }
                    else
                    {
                        $blockEnd = $endPos + 2;
                    }
                    $comment_text = substr( $sourceText, $tagPos + 1, $endPos - $tagPos - 1 );
                    $textElements[] = array( "Text" => $comment_text,
                                         "Type" => EZ_ELEMENT_COMMENT );
                    if ( $sourcePosition < $blockEnd )
                        $sourcePosition = $blockEnd;
//                     eZDebug::writeDebug( "eZTemplate: Comment: $comment" );
                }
                else
                {
                    $tmp_pos = $tagPos;
                    while( ( $endPos = strpos( $sourceText, $rightDelimiter, $tmp_pos ) ) !== false )
                    {
                        if ( $sourceText[$endPos-1] != "\\" )
                            break;
                        $tmp_pos = $endPos + 1;
                    }
                    if ( $endPos === false )
                    {
                        // Unterminated tag
                        $tpl->warning( "parse()", "Unterminated tag at pos $tagPos" );
                        unset( $data );
                        $data =& substr( $sourceText, $sourcePosition );
                        $textElements[] = array( "Text" => $data,
                                             "Type" => EZ_ELEMENT_TEXT );
                        $sourcePosition = $sourceLength;
                    }
                    else
                    {
                        $blockEnd = $endPos + 1;
                        $len = $endPos - $tagPos;
                        if ( $sourcePosition < $blockStart )
                        {
                            // Add text before tag.
                            unset( $data );
                            $data =& substr( $sourceText, $sourcePosition, $blockStart - $sourcePosition );
                            $textElements[] = array( "Text" => $data,
                                                 "Type" => EZ_ELEMENT_TEXT );
                        }

                        unset( $tag );
                        $tag = substr( $sourceText, $tagPos, $len );
                        $tag = preg_replace( "/\\\\[}]/", "}", $tag );
                        $isEndTag = false;
                        $isSingleTag = false;

                        if ( $tag[0] == "/" )
                        {
                            $isEndTag = true;
                            $tag = substr( $tag, 1 );
                        }
                        else if ( $tag[strlen($tag) - 1] == "/" )
                        {
                            $isSingleTag = true;
                            $tag = substr( $tag, 0, strlen( $tag ) - 1 );
                        }

                        if ( $tag[0] == "$" or
                             $tag[0] == "\"" or
                             $tag[0] == "'" or
                             is_numeric( $tag[0] ) or
                             ( $tag[0] == '-' and
                               isset( $tag[1] ) and
                               is_numeric( $tag[1] ) ) or
                             preg_match( "/^[a-z0-9]+\(/", $tag ) )
                        {
                            $textElements[] = array( "Text" => $tag,
                                                 "Type" => EZ_ELEMENT_VARIABLE );
                        }
                        else
                        {
                            $type = EZ_ELEMENT_NORMAL_TAG;
                            if ( $isEndTag )
                                $type = EZ_ELEMENT_END_TAG;
                            else if ( $isSingleTag )
                                $type = EZ_ELEMENT_SINGLE_TAG;
                            $spacepos = strpos( $tag, " " );
                            if ( $spacepos === false )
                                $name = $tag;
                            else
                                $name = substr( $tag, 0, $spacepos );
                            $textElements[] = array( "Text" => $tag,
                                                 "Name" => $name,
                                                 "Type" => $type );
                        }

                        if ( $sourcePosition < $blockEnd )
                            $sourcePosition = $blockEnd;
                    }
                }
            }
        }
        return $textElements;
    }

    function &parseWhitespaceRemoval( &$tpl, &$textElements )
    {
        if ( $tpl->ShowDetails )
            eZDebug::addTimingPoint( "Parse pass 2 (whitespace removal)" );
        $tempTextElements = array();
        reset( $textElements );
        while ( ( $key = key( $textElements ) ) !== null )
        {
            unset( $element );
            $element =& $textElements[$key];
            next( $textElements );
            $next_key = key( $textElements );
            unset( $next_element );
            $next_element = null;
            if ( $next_key !== null )
                $next_element =& $textElements[$next_key];
            switch ( $element["Type"] )
            {
                case EZ_ELEMENT_COMMENT:
                {
                    // Ignore comments
                } break;
                case EZ_ELEMENT_TEXT:
                case EZ_ELEMENT_VARIABLE:
                {
                    if ( $next_element !== null )
                    {
                        switch ( $next_element["Type"] )
                        {
                            case EZ_ELEMENT_END_TAG:
                            case EZ_ELEMENT_SINGLE_TAG:
                            case EZ_ELEMENT_NORMAL_TAG:
                            {
                                unset( $text );
                                $text =& $element["Text"];
                                $text_cnt = strlen( $text );
                                if ( $text_cnt > 0 )
                                {
                                    $char = $text[$text_cnt - 1];
                                    if ( $char == "\n" )
                                    {
                                        $text = substr( $text, 0, $text_cnt - 1 );
                                    }
                                }
                            } break;
                        }
                    }
                    if ( !empty( $element["Text"] ) )
                        $tempTextElements[] =& $element;
                } break;
                case EZ_ELEMENT_END_TAG:
                case EZ_ELEMENT_SINGLE_TAG:
                case EZ_ELEMENT_NORMAL_TAG:
                {
                    unset( $name );
                    $name =& $element["Name"];
                    if ( isset( $tpl->Literals[$name] ) )
                    {
                        unset( $text );
                        $text = "";
                        $key = key( $textElements );
                        while ( $key !== null )
                        {
                            unset( $element );
                            $element =& $textElements[$key];
                            switch ( $element["Type"] )
                            {
                                case EZ_ELEMENT_END_TAG:
                                {
                                    if ( $element["Name"] == $name )
                                    {
                                        next( $textElements );
                                        $key = null;
                                        $tempTextElements[] = array( "Text" => $text,
                                                                   "Type" => EZ_ELEMENT_TEXT );
                                    }
                                    else
                                    {
                                        $text .= $leftDelimiter . "/" . $element["Text"] . $rightDelimiter;
                                        next( $textElements );
                                        $key = key( $textElements );
                                    }
                                } break;
                                case EZ_ELEMENT_NORMAL_TAG:
                                {
                                    $text .= $leftDelimiter . $element["Text"] . $rightDelimiter;
                                    next( $textElements );
                                    $key = key( $textElements );
                                } break;
                                case EZ_ELEMENT_SINGLE_TAG:
                                {
                                    $text .= $leftDelimiter . $element["Text"] . "/" . $rightDelimiter;
                                    next( $textElements );
                                    $key = key( $textElements );
                                } break;
                                case EZ_ELEMENT_COMMENT:
                                {
                                    $text .= "$leftDelimiter*" . $element["Text"] . "*$rightDelimiter";
                                    next( $textElements );
                                    $key = key( $textElements );
                                } break;
                                default:
                                {
                                    $text .= $element["Text"];
                                    next( $textElements );
                                    $key = key( $textElements );
                                } break;
                            }
                        }
                    }
                    else
                    {
                        if ( $next_element !== null )
                        {
                            switch ( $next_element["Type"] )
                            {
                                case EZ_ELEMENT_TEXT:
                                case EZ_ELEMENT_VARIABLE:
                                {
                                    unset( $text );
                                    $text =& $next_element["Text"];
                                    $text_cnt = strlen( $text );
                                    if ( $text_cnt > 0 )
                                    {
                                        $char = $text[0];
                                        if ( $char == "\n" )
                                        {
                                            $text = substr( $text, 1 );
                                        }
                                    }
                                } break;
                            }
                        }
                        $tempTextElements[] =& $element;
                    }
                } break;
            }
        }
        return $tempTextElements;
    }

    function parseIntoTree( &$tpl, &$textElements, &$currentRoot,
                            $rootNamespace, $relatedResource, $relatedTemplateName )
    {
        if ( $tpl->ShowDetails )
            eZDebug::addTimingPoint( "Parse pass 3 (build tree)" );

        $tagStack = array();

        reset( $textElements );
        while ( ( $key = key( $textElements ) ) !== null )
        {
            unset( $element );
            $element =& $textElements[$key];
            switch ( $element["Type"] )
            {
                case EZ_ELEMENT_TEXT:
                {
                    unset( $node );
                    $node = new eZTemplateTextElement( $element["Text"] );
                    $tpl->setRelation( $node, $relatedResource, $relatedTemplateName );
                    $currentRoot->appendChild( $node );
                } break;
                case EZ_ELEMENT_VARIABLE:
                {
                    $text =& $element["Text"];
                    $text_len = strlen( $text );
                    $var_data =& $this->ElementParser->parseVariableTag( $tpl, $text, 0, &$var_end, $text_len, $rootNamespace );

                    $node =& new eZTemplateVariableElement( $var_data );
                    $tpl->setRelation( $node, $relatedResource, $relatedTemplateName );
                    $currentRoot->appendChild( &$node );
                    if ( $var_end < $text_len )
                    {
                        $tpl->warning( "", "Junk at variable end: '" . substr( $text, $var_end, $text_len - $var_end ) . "' (" . substr( $text, 0, $var_end ) . ")" );
                    }
                } break;
                case EZ_ELEMENT_SINGLE_TAG:
                case EZ_ELEMENT_NORMAL_TAG:
                case EZ_ELEMENT_END_TAG:
                {
                    unset( $text );
                    unset( $type );
                    $text =& $element["Text"];
                    $text_len = strlen( $text );
                    $type =& $element["Type"];

                    $ident_pos = $this->ElementParser->identifierEndPos( $tpl, $text, 0, $text_len );
                    $tag = substr( $text, 0, $ident_pos - 0 );
                    $attr_pos = $ident_pos;
                    unset( $args );
                    $args = array();
                    while ( $attr_pos < $text_len )
                    {
                        $attr_pos_start = $this->skipWhiteSpace( $text, $attr_pos, $text_len );
                        if ( $attr_pos_start == $attr_pos and
                             $attr_pos_start < $text_len )
                        {
                            $tpl->error( "", "Expected whitespace, got: '" . substr( $text, $attr_pos ) . "'" );
                            break;
                        }
                        $attr_pos = $attr_pos_start;
                        $attr_name_pos = $this->ElementParser->identifierEndPos( $tpl, $text, $attr_pos, $text_len );
                        $attr_name = substr( $text, $attr_pos, $attr_name_pos - $attr_pos );
                        if ( $attr_name_pos >= $text_len )
                        {
                            continue;
//                             $tpl->error( "", "Unterminated parameter in function '$tag' ($text)" );
//                             break;
                        }
                        if ( $text[$attr_name_pos] != "=" )
                        {
                            $tpl->error( "", "Invalid parameter characters in function '$tag': '" .
                                          substr( $text, $attr_name_pos )  . "'" );
                            break;
                        }
                        ++$attr_name_pos;
                        unset( $var_data );
                        $var_data =& $this->ElementParser->parseVariableTag( $tpl, $text, $attr_name_pos, &$var_end, $text_len, $rootNamespace );
                        $args[$attr_name] = $var_data;
                        $attr_pos = $var_end;
                    }

                    if ( $type == EZ_ELEMENT_END_TAG and count( $args ) > 0 )
                    {
                        $tpl->warning( "", "End tag \"$tag\" cannot have attributes" );
                        $args = array();
                    }

                    if ( $type == EZ_ELEMENT_NORMAL_TAG )
                    {
                        unset( $node );
                        $node =& new eZTemplateFunctionElement( $tag, $args );
                        $tpl->setRelation( $node, $relatedResource, $relatedTemplateName );
                        $currentRoot->appendChild( &$node );
                        $has_children = true;
                        if ( isset( $tpl->FunctionAttributes[$tag] ) )
                        {
//                             eZDebug::writeDebug( $tpl->FunctionAttributes[$tag], "\$tpl->FunctionAttributes[$tag] #1" );
                            if ( is_array( $tpl->FunctionAttributes[$tag] ) )
                                $tpl->loadAndRegisterFunctions( $tpl->FunctionAttributes[$tag] );
                            $has_children = $tpl->FunctionAttributes[$tag];
                        }
                        else if ( isset( $tpl->Functions[$tag] ) )
                        {
//                             eZDebug::writeDebug( $tpl->Functions[$tag], "\$tpl->Functions[$tag] #1" );
                            if ( is_array( $tpl->Functions[$tag] ) )
                                $tpl->loadAndRegisterFunctions( $tpl->Functions[$tag] );
                            $has_children = $tpl->hasChildren( $tpl->Functions[$tag], $tag );
                        }
                        if ( $has_children )
                        {
                            $tagStack[] = array( "Root" => &$currentRoot,
                                                 "Tag" => &$tag );
                            unset( $currentRoot );
                            $currentRoot =& $node;
                        }
                    }
                    else if ( $type == EZ_ELEMENT_END_TAG )
                    {
                        $has_children = true;
                        if ( isset( $tpl->FunctionAttributes[$tag] ) )
                        {
//                             eZDebug::writeDebug( $tpl->FunctionAttributes[$tag], "\$tpl->FunctionAttributes[$tag] #2" );
                            if ( is_array( $tpl->FunctionAttributes[$tag] ) )
                                $tpl->loadAndRegisterFunctions( $tpl->FunctionAttributes[$tag] );
                            $has_children = $tpl->FunctionAttributes[$tag];
                        }
                        else if ( isset( $tpl->Functions[$tag] ) )
                        {
//                             eZDebug::writeDebug( $tpl->Functions[$tag], "\$tpl->Functions[$tag] #2" );
                            if ( is_array( $tpl->Functions[$tag] ) )
                                $tpl->loadAndRegisterFunctions( $tpl->Functions[$tag] );
                            $has_children = $tpl->hasChildren( $tpl->Functions[$tag], $tag );
                        }
                        if ( !$has_children )
                        {
                            $tpl->warning( "", "End tag \"$tag\" for function which does not accept children, ignoring tag" );
                        }
                        else
                        {
                            unset( $oldTag );
                            unset( $oldTagName );
                            $oldTag =& array_pop( &$tagStack );
                            $oldTagName =& $oldTag["Tag"];
                            unset( $currentRoot );
                            $currentRoot =& $oldTag["Root"];

                            if ( $oldTagName != $tag )
                                $tpl->warning( "", "Unterminated tag \"$oldTagName\" does not match tag \"$tag\" at $blockStart" );
                        }
                    }
                    else // EZ_ELEMENT_SINGLE_TAG
                    {
                        unset( $node );
                        $node =& new eZTemplateFunctionElement( $tag, $args );
                        $tpl->setRelation( $node, $relatedResource, $relatedTemplateName );
                        $currentRoot->appendChild( &$node );
                    }
                    unset( $tag );

                } break;
            }
            next( $textElements );
        }
        unset( $textElements );
        if ( $tpl->ShowDetails )
            eZDebug::addTimingPoint( "Parse pass 3 done" );
    }

    /*!
     Returns the position of the first non-whitespace characters.
    */
    function skipWhiteSpace( $text, $pos, $text_len )
    {
        if ( $pos >= $text_len )
            return $pos;
        while( $pos < $text_len and
               preg_match( "/[ \t\r\n]/", $text[$pos] ) )
        {
            ++$pos;
        }
        return $pos;
    }

    function &instance()
    {
        $instance =& $GLOBALS['eZTemplateMultiPassParserInstance'];
        if ( get_class( $instance ) != 'eztemplatemultipassparser' )
        {
            $instance = new eZTemplateMultiPassParser();
        }
        return $instance;
    }

    /// \privatesection
    var $ElementParser;
}

?>
