<?php
//
// Definition of eZTemplateMultiPassParser class
//
// Created on: <26-Nov-2002 17:25:44 amos>
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

    */
    function parse( &$tpl, &$sourceText, &$rootElement, $rootNamespace, &$resourceData )
    {
        $relatedResource = $resourceData['resource'];
        $relatedTemplateName = $resourceData['template-filename'];

        $currentRoot =& $rootElement;
        $leftDelimiter = $tpl->LDelim;
        $rightDelimiter = $tpl->RDelim;
        $sourceLength = strlen( $sourceText );
        $sourcePosition = 0;

        eZDebug::accumulatorStart( 'template_multi_parser_1', 'template_total', 'Template parser: create text elements' );
        $textElements =& $this->parseIntoTextElements( $tpl, $sourceText, $sourcePosition,
                                                       $leftDelimiter, $rightDelimiter, $sourceLength,
                                                       $relatedTemplateName );
        eZDebug::accumulatorStop( 'template_multi_parser_1' );

        eZDebug::accumulatorStart( 'template_multi_parser_2', 'template_total', 'Template parser: remove whitespace' );
        $textElements =& $this->parseWhitespaceRemoval( $tpl, $textElements );
        eZDebug::accumulatorStop( 'template_multi_parser_2' );

        eZDebug::accumulatorStart( 'template_multi_parser_3', 'template_total', 'Template parser: construct tree' );
        $this->parseIntoTree( $tpl, $textElements, $currentRoot,
                              $rootNamespace, $relatedResource, $relatedTemplateName );
        eZDebug::accumulatorStop( 'template_multi_parser_3' );
    }

    function gotoEndPosition( $text, $line, $column, &$endLine, &$endColumn )
    {
        $lines = preg_split( "#\r\n|\r|\n#", $text );
        if ( count( $lines ) > 0 )
        {
            $endLine = $line + count( $lines ) - 1;
            $lastLine = $lines[count($lines)-1];
            if ( count( $lines ) > 1 )
                $endColumn = strlen( $lastLine );
            else
                $endColumn = $column + strlen( $lastLine );
        }
        else
        {
            $endLine = $line;
            $endColumn = $column;
        }
    }

    function &parseIntoTextElements( &$tpl, $sourceText, $sourcePosition,
                                     $leftDelimiter, $rightDelimiter, $sourceLength,
                                     $relatedTemplateName )
    {
        if ( $tpl->ShowDetails )
            eZDebug::addTimingPoint( "Parse pass 1 (simple tag parsing)" );
        $currentLine = 1;
        $currentColumn = 0;
        $textElements = array();
        while( $sourcePosition < $sourceLength )
        {
            $tagPos = strpos( $sourceText, $leftDelimiter, $sourcePosition );
            if ( $tagPos === false )
            {
                // No more tags
                unset( $data );
                $data =& substr( $sourceText, $sourcePosition );
                $this->gotoEndPosition( $data, $currentLine, $currentColumn, $endLine, $endColumn );
                $textElements[] = array( "text" => $data,
                                         "type" => EZ_ELEMENT_TEXT,
                                         'placement' => array( 'templatefile' => $relatedTemplateName,
                                                               'start' => array( 'line' => $currentLine,
                                                                                 'column' => $currentColumn,
                                                                                 'position' => $sourcePosition ),
                                                               'stop' => array( 'line' => $endLine,
                                                                                'column' => $endColumn,
                                                                                'position' => $sourceLength - 1 ) ) );
                $sourcePosition = $sourceLength;
                $currentLine = $endLine;
                $currentColumn = $endColumn;
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
                        $this->gotoEndPosition( $data, $currentLine, $currentColumn, $endLine, $endColumn );
                        $textElements[] = array( "text" => $data,
                                                 "type" => EZ_ELEMENT_TEXT,
                                                 'placement' => array( 'templatefile' => $relatedTemplateName,
                                                                       'start' => array( 'line' => $currentLine,
                                                                                         'column' => $currentColumn,
                                                                                         'position' => $sourcePosition ),
                                                                       'stop' => array( 'line' => $endLine,
                                                                                        'column' => $endColumn,
                                                                                        'position' => $blockStart ) ) );
                        $currentLine = $endLine;
                        $currentColumn = $endColumn;
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
                    $this->gotoEndPosition( $comment_text, $currentLine, $currentColumn, $endLine, $endColumn );
                    $textElements[] = array( "text" => $comment_text,
                                             "type" => EZ_ELEMENT_COMMENT,
                                             'placement' => array( 'templatefile' => $relatedTemplateName,
                                                                   'start' => array( 'line' => $currentLine,
                                                                                     'column' => $currentColumn,
                                                                                     'position' => $tagPos + 1 ),
                                                                   'stop' => array( 'line' => $endLine,
                                                                                    'column' => $endColumn,
                                                                                    'position' => $endPos - 1 ) ) );
                    if ( $sourcePosition < $blockEnd )
                        $sourcePosition = $blockEnd;
                    $currentLine = $endLine;
                    $currentColumn = $endColumn;
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
                        unset( $data );
                        $data =& substr( $sourceText, $sourcePosition );
                        $this->gotoEndPosition( $data, $currentLine, $currentColumn, $endLine, $endColumn );
                        $textBefore = substr( $sourceText, $sourcePosition, $tagPos - $sourcePosition );
                        $textPortion = substr( $sourceText, $tagPos );
                        $this->gotoEndPosition( $textBefore, $currentLine, $currentColumn, $tagStartLine, $tagStartColumn );
                        $this->gotoEndPosition( $textPortion, $tagStartLine, $tagStartColumn, $tagEndLine, $tagEndColumn );
                        $tpl->error( "", "parser error @ $relatedTemplateName:$currentLine" . "[$currentColumn]" . "\n" .
                                     "Unterminated tag, needs a $rightDelimiter to end the tag.\n" . $leftDelimiter . $textPortion,
                                     array( array( $tagStartLine, $tagStartColumn, $tagPosition ),
                                              array( $tagEndLine, $tagEndColumn, $sourceLength - 1 ),
                                              $relatedTemplateName ) );
                        $textElements[] = array( "text" => $data,
                                                 "type" => EZ_ELEMENT_TEXT,
                                                 'placement' => array( 'templatefile' => $relatedTemplateName,
                                                                       'start' => array( 'line' => $currentLine,
                                                                                         'column' => $currentColumn,
                                                                                         'position' => $sourcePosition ),
                                                                       'stop' => array( 'line' => $endLine,
                                                                                        'column' => $endColumn,
                                                                                        'position' => $sourceLength - 1 ) ) );
                        $sourcePosition = $sourceLength;
                        $currentLine = $endLine;
                        $currentColumn = $endColumn;
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
                            $this->gotoEndPosition( $data, $currentLine, $currentColumn, $endLine, $endColumn );
                            $textElements[] = array( "text" => $data,
                                                     "type" => EZ_ELEMENT_TEXT,
                                                     'placement' => array( 'templatefile' => $relatedTemplateName,
                                                                           'start' => array( 'line' => $currentLine,
                                                                                             'column' => $currentColumn,
                                                                                             'position' => $sourcePosition ),
                                                                           'stop' => array( 'line' => $endLine,
                                                                                            'column' => $endColumn,
                                                                                            'position' => $blockStart ) ) );
                            $currentLine = $endLine;
                            $currentColumn = $endColumn;
                        }

                        unset( $tag );
                        $tag = substr( $sourceText, $tagPos, $len );
                        $tag = preg_replace( "/\\\\[}]/", "}", $tag );
                        $tagTrim = trim( $tag );
                        $isEndTag = false;
                        $isSingleTag = false;

                        if ( $tag[0] == "/" )
                        {
                            $isEndTag = true;
                            $tag = substr( $tag, 1 );
                        }
                        else if ( $tagTrim[strlen( $tagTrim ) - 1] == "/" )
                        {
                            $isSingleTag = true;
                            $tagTrim = trim( substr( $tagTrim, 0, strlen( $tagTrim ) - 1 ) );
                            $tag = $tagTrim;
                        }

                        $this->gotoEndPosition( $tag, $currentLine, $currentColumn, $endLine, $endColumn );
                        if ( $tag[0] == "$" or
                             $tag[0] == "\"" or
                             $tag[0] == "'" or
                             is_numeric( $tag[0] ) or
                             ( $tag[0] == '-' and
                               isset( $tag[1] ) and
                               is_numeric( $tag[1] ) ) or
                             preg_match( "/^[a-z0-9_-]+\(/", $tag ) )
                        {
                            $textElements[] = array( "text" => $tag,
                                                     "type" => EZ_ELEMENT_VARIABLE,
                                                     'placement' => array( 'templatefile' => $relatedTemplateName,
                                                                           'start' => array( 'line' => $currentLine,
                                                                                             'column' => $currentColumn,
                                                                                             'position' => $blockStart + 1 ),
                                                                           'stop' => array( 'line' => $endLine,
                                                                                            'column' => $endColumn,
                                                                                            'position' => $blockEnd - 1 ) ) );
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
                            if ( isset( $tpl->Literals[$name] ) )
                            {
                                $literalEndTag = "{/$name}";
                                $literalEndPos = strpos( $sourceText, $literalEndTag, $blockEnd );
                                if ( $literalEndPos === false )
                                    $literalEndPos = $sourceLength;
                                $data = substr( $sourceText, $blockEnd, $literalEndPos - $blockEnd );
                                $this->gotoEndPosition( $data, $currentLine, $currentColumn, $endLine, $endColumn );
                                $blockEnd = $literalEndPos + strlen( $literalEndTag );
                                $textElements[] = array( "text" => $data,
                                                         "type" => EZ_ELEMENT_TEXT,
                                                         'placement' => false );
                            }
                            else
                                $textElements[] = array( "text" => $tag,
                                                         "name" => $name,
                                                         "type" => $type,
                                                         'placement' => array( 'templatefile' => $relatedTemplateName,
                                                                               'start' => array( 'line' => $currentLine,
                                                                                                 'column' => $currentColumn,
                                                                                                 'position' => $blockStart + 1 ),
                                                                               'stop' => array( 'line' => $endLine,
                                                                                                'column' => $endColumn,
                                                                                                'position' => $blockEnd - 1 ) ) );
                        }

                        if ( $sourcePosition < $blockEnd )
                            $sourcePosition = $blockEnd;
                        $currentLine = $endLine;
                        $currentColumn = $endColumn;
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
            switch ( $element["type"] )
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
                        switch ( $next_element["type"] )
                        {
                            case EZ_ELEMENT_END_TAG:
                            case EZ_ELEMENT_SINGLE_TAG:
                            case EZ_ELEMENT_NORMAL_TAG:
                            {
                                unset( $text );
                                $text =& $element["text"];
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
                    if ( $element["text"] !== '' )
                    {
                        $tempTextElements[] =& $element;
                    }
                } break;
                case EZ_ELEMENT_END_TAG:
                case EZ_ELEMENT_SINGLE_TAG:
                case EZ_ELEMENT_NORMAL_TAG:
                {
                    unset( $name );
                    $name =& $element["name"];
                    $startLine = false;
                    $startColumn = false;
                    $stopLine = false;
                    $stopColumn = false;
                    $templateFile = false;
                    $hasStartPlacement = false;
                    {
                        if ( $next_element !== null )
                        {
                            switch ( $next_element["type"] )
                            {
                                case EZ_ELEMENT_TEXT:
                                case EZ_ELEMENT_VARIABLE:
                                {
                                    unset( $text );
                                    $text =& $next_element["text"];
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

    function appendChild( &$root, &$node )
    {
        if ( !is_array( $root[1] ) )
            $root[1] = array();
        $root[1][] =& $node;
    }

    function parseIntoTree( &$tpl, &$textElements, &$treeRoot,
                            $rootNamespace, $relatedResource, $relatedTemplateName )
    {
        $currentRoot =& $treeRoot;
        if ( $tpl->ShowDetails )
            eZDebug::addTimingPoint( "Parse pass 3 (build tree)" );

        $tagStack = array();

        reset( $textElements );
        while ( ( $key = key( $textElements ) ) !== null )
        {
            unset( $element );
            $element =& $textElements[$key];
            $elementPlacement = $element['placement'];
            $startLine = $elementPlacement['start']['line'];
            $startColumn = $elementPlacement['start']['column'];
            $startPosition = $elementPlacement['start']['position'];
            $stopLine = $elementPlacement['stop']['line'];
            $stopColumn = $elementPlacement['stop']['column'];
            $stopPosition = $elementPlacement['stop']['position'];
            $templateFile = $elementPlacement['templatefile'];
            $placement = array( array( $startLine,
                                       $startColumn,
                                       $startPosition ),
                                array( $stopLine,
                                       $stopColumn,
                                       $stopPosition ),
                                $templateFile );
            switch ( $element["type"] )
            {
                case EZ_ELEMENT_TEXT:
                {
                    unset( $node );
                    $node = array( EZ_TEMPLATE_NODE_TEXT,
                                   false,
                                   $element['text'],
                                   $placement );
                    $this->appendChild( $currentRoot, $node );
                } break;
                case EZ_ELEMENT_VARIABLE:
                {
                    $text =& $element["text"];
                    $text_len = strlen( $text );
                    $var_data =& $this->ElementParser->parseVariableTag( $tpl, $relatedTemplateName, $text, 0, $var_end, $text_len, $rootNamespace );

                    unset( $node );
                    $node = array( EZ_TEMPLATE_NODE_VARIABLE,
                                   false,
                                   $var_data,
                                   $placement );
                    $this->appendChild( $currentRoot, $node );
                    if ( $var_end < $text_len )
                    {
                        $placement = $element['placement'];
                        $startLine = $placement['start']['line'];
                        $startColumn = $placement['start']['column'];
                        $subText = substr( $text, 0, $var_end );
                        $this->gotoEndPosition( $subText, $startLine, $startColumn, $currentLine, $currentColumn );
                        $tpl->error( "", "parser error @ $relatedTemplateName:$currentLine" . "[$currentColumn]" . "\n" .
                                     "Extra characters found in expression, they will be ignored.\n" .
                                     substr( $text, $var_end, $text_len - $var_end ) . "' (" . substr( $text, 0, $var_end ) . ")",
                                     $placement );
                    }
                } break;
                case EZ_ELEMENT_SINGLE_TAG:
                case EZ_ELEMENT_NORMAL_TAG:
                case EZ_ELEMENT_END_TAG:
                {
                    unset( $text );
                    unset( $type );
                    $text =& $element["text"];
                    $text_len = strlen( $text );
                    $type =& $element["type"];

                    $ident_pos = $this->ElementParser->identifierEndPosition( $tpl, $text, 0, $text_len );
                    $tag = substr( $text, 0, $ident_pos - 0 );
                    $attr_pos = $ident_pos;
                    unset( $args );
                    $args = array();
                    $lastPosition = false;
                    while ( $attr_pos < $text_len )
                    {
                        if ( $lastPosition !== false and
                             $lastPosition == $attr_pos )
                        {
                            break;
                        }
                        $lastPosition = $attr_pos;
                        $attr_pos_start = $this->ElementParser->whitespaceEndPos( $tpl, $text, $attr_pos, $text_len );
                        if ( $attr_pos_start == $attr_pos and
                             $attr_pos_start < $text_len )
                        {
                            $placement = $element['placement'];
                            $startLine = $placement['start']['line'];
                            $startColumn = $placement['start']['column'];
                            $subText = substr( $text, 0, $attr_pos );
                            $this->gotoEndPosition( $subText, $startLine, $startColumn, $currentLine, $currentColumn );
                            $tpl->error( "", "parser error @ $relatedTemplateName:$currentLine" . "[$currentColumn]" . "\n" .
                                         "Extra characters found, should have been a whitespace or the end of the expression\n".
                                         "Characters: '" . substr( $text, $attr_pos ) . "'" );
                            break;
                        }
                        $attr_pos = $attr_pos_start;
                        $attr_name_pos = $this->ElementParser->identifierEndPosition( $tpl, $text, $attr_pos, $text_len );
                        $attr_name = substr( $text, $attr_pos, $attr_name_pos - $attr_pos );

                        /* Skip whitespace between here and the next one */
                        $equal_sign_pos = $this->ElementParser->whitespaceEndPos( $tpl, $text, $attr_name_pos, $text_len );
                        if ( ( $equal_sign_pos < $text_len ) && ( $text[$equal_sign_pos] == '=' ) )
                        {
                            $attr_name_pos = $equal_sign_pos;
                        }

                        if ( $attr_name_pos >= $text_len or
                             ( $text[$attr_name_pos] != '=' and
                               preg_match( "/[ \t\r\n]/", $text[$attr_name_pos] ) ) )
                        {
                            unset( $var_data );
                            $var_data = array();
                            $var_data[] = array( EZ_TEMPLATE_TYPE_NUMERIC, // type
                                                 true, // content
                                                 false // debug
                                                 );
                            $args[$attr_name] = $var_data;
                            $attr_pos = $attr_name_pos;
                            continue;
                        }
                        if ( $text[$attr_name_pos] != "=" )
                        {
                            $placement = $element['placement'];
                            $startLine = $placement['start']['line'];
                            $startColumn = $placement['start']['column'];
                            $subText = substr( $text, 0, $attr_name_pos );
                            $this->gotoEndPosition( $subText, $startLine, $startColumn, $currentLine, $currentColumn );
                            $tpl->error( "", "parser error @ $relatedTemplateName:$currentLine" . "[$currentColumn]\n".
                                         "Invalid parameter characters in function '$tag': '" .
                                          substr( $text, $attr_name_pos )  . "'" );
                            break;
                        }
                        ++$attr_name_pos;
                        unset( $var_data );
                        $var_data =& $this->ElementParser->parseVariableTag( $tpl, $relatedTemplateName, $text, $attr_name_pos, $var_end, $text_len, $rootNamespace );
                        $args[$attr_name] = $var_data;
                        $attr_pos = $var_end;
                    }

                    if ( $type == EZ_ELEMENT_END_TAG and count( $args ) > 0 )
                    {
                        $placement = $element['placement'];
                        $startLine = $placement['start']['line'];
                        $startColumn = $placement['start']['column'];
                        $tpl->error( "", "parser error @ $relatedTemplateName:$startLine" . "[$startColumn]" . "\n" .
                                     "End tag \"$tag\" cannot have attributes\n$leftDelimiter/" . $text . $rightDelimiter,
                                     $element['placement'] );
                        $args = array();
                    }

                    if ( $type == EZ_ELEMENT_NORMAL_TAG )
                    {
                        unset( $node );
                        $node = array( EZ_TEMPLATE_NODE_FUNCTION,
                                       false,
                                       $tag,
                                       $args,
                                       $placement );
                        $this->appendChild( $currentRoot, $node );
                        $has_children = true;
                        if ( isset( $tpl->FunctionAttributes[$tag] ) )
                        {
                            if ( is_array( $tpl->FunctionAttributes[$tag] ) )
                                $tpl->loadAndRegisterFunctions( $tpl->FunctionAttributes[$tag] );
                            $has_children = $tpl->FunctionAttributes[$tag];
                        }
                        else if ( isset( $tpl->Functions[$tag] ) )
                        {
                            if ( is_array( $tpl->Functions[$tag] ) )
                                $tpl->loadAndRegisterFunctions( $tpl->Functions[$tag] );
                            $has_children = $tpl->hasChildren( $tpl->Functions[$tag], $tag );
                        }
                        if ( $has_children )
                        {
                            $tagStack[] = array( "Root" => &$currentRoot,
                                                 "Tag" => $tag );
                            unset( $currentRoot );
                            $currentRoot =& $node;
                        }
                    }
                    else if ( $type == EZ_ELEMENT_END_TAG )
                    {
                        $has_children = true;
                        if ( isset( $tpl->FunctionAttributes[$tag] ) )
                        {
                            if ( is_array( $tpl->FunctionAttributes[$tag] ) )
                                $tpl->loadAndRegisterFunctions( $tpl->FunctionAttributes[$tag] );
                            $has_children = $tpl->FunctionAttributes[$tag];
                        }
                        else if ( isset( $tpl->Functions[$tag] ) )
                        {
                            if ( is_array( $tpl->Functions[$tag] ) )
                                $tpl->loadAndRegisterFunctions( $tpl->Functions[$tag] );
                            $has_children = $tpl->hasChildren( $tpl->Functions[$tag], $tag );
                        }
                        if ( !$has_children )
                        {
                            $placement = $element['placement'];
                            $startLine = $placement['start']['line'];
                            $startColumn = $placement['start']['column'];
                            $tpl->error( "", "parser error @ $relatedTemplateName:$startLine" . "[$startColumn]" . "\n" .
                                         "End tag \"$tag\" for function which does not accept children, ignoring tag",
                                         $element['placement'] );
                        }
                        else
                        {
                            unset( $oldTag );
                            unset( $oldTagName );
                            include_once( "lib/ezutils/classes/ezphpcreator.php" );
                            $oldTag =& array_pop( $tagStack );
                            $oldTagName = $oldTag["Tag"];
                            unset( $currentRoot );
                            $currentRoot =& $oldTag["Root"];

                            if ( $oldTagName != $tag )
                            {
                                $placement = $element['placement'];
                                $startLine = $placement['start']['line'];
                                $startColumn = $placement['start']['column'];
                                $tpl->error( "", "parser error @ $relatedTemplateName:$startLine" . "[$startColumn]" . "\n" .
                                             "Unterminated tag \"$oldTagName\" does not match tag \"$tag\"",
                                             $element['placement'] );
                            }
                        }
                    }
                    else // EZ_ELEMENT_SINGLE_TAG
                    {
                        unset( $node );
                        $node = array( EZ_TEMPLATE_NODE_FUNCTION,
                                       false,
                                       $tag,
                                       $args,
                                       $placement );
                        $this->appendChild( $currentRoot, $node );
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
