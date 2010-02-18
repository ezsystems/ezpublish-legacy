<?php
//
// Definition of eZXMLInputParser class
//
// Created on: <27-Mar-2006 15:28:39 ks>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

/*
    Base class for the input parser.
    The goal of the parser is XML/HTML analyzing, fixing and transforming.
    The input is processed in 2 passes:
    - 1st pass: Parsing input, check for syntax errors, build DOM tree.
    - 2nd pass: Walking through DOM tree, checking validity by XML schema,
                calling tag handlers to transform the tree.

    Both passes are controlled by the arrays described bellow and user handler functions.

*/

// if ( !class_exists( 'eZXMLSchema' ) ) // AS 21-09-2007: commented out because of include_once being commented out
class eZXMLInputParser
{
    /// \deprecated (back-compatibility)
    const SHOW_NO_ERRORS = 0;
    const SHOW_SCHEMA_ERRORS = 1;
    const SHOW_ALL_ERRORS = 2;

    /// Use these constants for error types
    const ERROR_NONE = 0;
    const ERROR_SYNTAX = 4;
    const ERROR_SCHEMA = 8;
    const ERROR_DATA = 16;
    const ERROR_ALL = 28; // 4+8+16

    /* $InputTags array contains properties of elements that come from the input.

    Each array element describes a tag that comes from the input. Arrays index is
    a tag's name. Each element is an array that may contain the following members:

    'name'        - a string representing a new name of the tag,
    'nameHandler' - a name of the function that returns new tag name. Function format:
                    function tagNameHandler( $tagName, &$attributes )

    If no of those elements are defined the original tag's name is used.

    'noChildren'  - boolean value that determines if this tag could have child tags,
                    default value is false.

    Example:

    public $InputTags = array(

        'original-name' => array( 'name' => 'new-name' ),

        'original-name2' => array( 'nameHandler' => 'tagNameHandler',
                                   'noChildren' => true ),

         ...

         );
    */

    public $InputTags = array();

    /*
    $OutputTags array contains properties of elements that are produced in the output.
    Each array element describes a tag presented in the output. Arrays index is
    a tag's name. Each element is an array that may contain the following members:

    'parsingHandler' - "Parsing handler" called at parse pass 1 before processing tag's children.
    'initHandler'    - "Init handler" called at pass 2 before proccessing tag's children.
    'structHandler'  - "Structure handler" called at pass 2 after proccessing tag's children,
                       but before schema validity check. It can be used to implement structure
                       transformations.
    'publishHandler' - "Publish handler" called at pass 2 after schema validity check, so it is called
                       in case the element has it's guaranteed place in the DOM tree.

    'attributes'     - an array that describes attributes transformations. Array's index is the
                       original name of an attribute, and the value is the new name.

    'requiredInputAttributes' - attributes that are required in the input tag. If they are not presented
                                it raises invalid input flag.

    Example:

    public $OutputTags = array(

        'custom'    => array( 'parsingHandler' => 'parsingHandlerCustom',
                              'initHandler' => 'initHandlerCustom',
                              'structHandler' => 'structHandlerCustom',
                              'publishHandler' => 'publishHandlerCustom',
                              'attributes' => array( 'title' => 'name' ) ),

        ...
    );

    */

    public $OutputTags = array();

    public $Namespaces = array( 'image' => 'http://ez.no/namespaces/ezpublish3/image/',
                             'xhtml' => 'http://ez.no/namespaces/ezpublish3/xhtml/',
                             'custom' => 'http://ez.no/namespaces/ezpublish3/custom/',
                             'tmp' => 'http://ez.no/namespaces/ezpublish3/temporary/' );

    /*!

    The constructor.

    \param $validate
    \param $validateErrorLevel Determines types of errors that break input processing
                               It's possible to combine any error types, by creating a bitmask of EZ_XMLINPUTPARSER_ERROR_* constants.
                               \c true value means that all errors defined by $detectErrorLevel parameter will break further processing
    \param $detectErrorLevel Determines types of errors that will be detected and added to error log ($Messages).
    */

    function eZXMLInputParser( $validateErrorLevel = self::ERROR_NONE, $detectErrorLevel = self::ERROR_NONE, $parseLineBreaks = false,
                               $removeDefaultAttrs = false )
    {
        // Back-compatibility fixes:
        if ( $detectErrorLevel === self::SHOW_SCHEMA_ERRORS )
        {
            $detectErrorLevel = self::ERROR_SCHEMA;
        }
        elseif ( $detectErrorLevel === self::SHOW_ALL_ERRORS )
        {
            $detectErrorLevel = self::ERROR_ALL;
        }

        if ( $validateErrorLevel === false )
        {
            $validateErrorLevel = self::ERROR_NONE;
        }
        elseif ( $validateErrorLevel === true )
        {
            $validateErrorLevel = $detectErrorLevel;
        }

        $this->ValidateErrorLevel = $validateErrorLevel;
        $this->DetectErrorLevel = $detectErrorLevel;

        $this->RemoveDefaultAttrs = $removeDefaultAttrs;
        $this->ParseLineBreaks = $parseLineBreaks;

        $this->XMLSchema = eZXMLSchema::instance();

        $this->eZPublishVersion = eZPublishSDK::majorVersion() + eZPublishSDK::minorVersion() * 0.1;

        $ini = eZINI::instance( 'ezxml.ini' );
        if ( $ini->hasVariable( 'InputSettings', 'TrimSpaces' ) )
        {
            $trimSpaces = $ini->variable( 'InputSettings', 'TrimSpaces' );
            $this->TrimSpaces = $trimSpaces == 'true' ? true : false;
        }

        if ( $ini->hasVariable( 'InputSettings', 'AllowMultipleSpaces' ) )
        {
            $allowMultipleSpaces = $ini->variable( 'InputSettings', 'AllowMultipleSpaces' );
            $this->AllowMultipleSpaces = $allowMultipleSpaces == 'true' ? true : false;
        }

        if ( $ini->hasVariable( 'InputSettings', 'AllowNumericEntities' ) )
        {
            $allowNumericEntities = $ini->variable( 'InputSettings', 'AllowNumericEntities' );
            $this->AllowNumericEntities = $allowNumericEntities == 'true' ? true : false;
        }

        $contentIni = eZINI::instance( 'content.ini' );
        $useStrictHeaderRule = $contentIni->variable( 'header', 'UseStrictHeaderRule' );
        $this->StrictHeaders = $useStrictHeaderRule == 'true' ? true : false;
    }

    /// \public
    function setDOMDocumentClass( $DOMDocumentClass )
    {
        $this->DOMDocumentClass = $DOMDocumentClass;
    }

    /// \public
    function setParseLineBreaks( $value )
    {
        $this->ParseLineBreaks = $value;
    }

    /// \public
    function setRemoveDefaultAttrs( $value )
    {
        $this->RemoveDefaultAttrs = $value;
    }

    /// \public
    function createRootNode()
    {
        if ( !$this->Document )
        {
            $this->Document = new $this->DOMDocumentClass( '1.0', 'utf-8' );
        }

        // Creating root section with namespaces definitions
        $mainSection = $this->Document->createElement( 'section' );
        $this->Document->appendChild( $mainSection );
        foreach( array( 'image', 'xhtml', 'custom' ) as $prefix )
        {
            $mainSection->setAttributeNS( 'http://www.w3.org/2000/xmlns/', 'xmlns:' . $prefix, $this->Namespaces[$prefix] );
        }
        return $this->Document;
    }

    /*!
        \public
        Call this function to process your input
    */
    function process( $text, $createRootNode = true )
    {
        $text = str_replace( "\r", '', $text);
        $text = str_replace( "\t", ' ', $text);
        if ( !$this->ParseLineBreaks )
        {
            $text = str_replace( "\n", '', $text);
        }

        $this->Document = new $this->DOMDocumentClass( '1.0', 'utf-8' );

        if ( $createRootNode )
        {
            $this->createRootNode();
        }

        // Perform pass 1
        // Parsing the source string
        $this->performPass1( $text );

        //$this->Document->formatOutput = true;
        eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $this->Document->saveXML(), 'XML after pass 1' );

        if ( $this->QuitProcess )
        {
            return false;
        }

        // Perform pass 2
        $this->performPass2();

        //$this->Document->formatOutput = true;
        eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $this->Document->saveXML(), 'XML after pass 2' );

        if ( $this->QuitProcess )
        {
            return false;
        }

        return $this->Document;
    }

    /*
       \public
       Pass 1: Parsing the source HTML string.
    */

    function performPass1( &$data )
    {
        $ret = true;
        $pos = 0;

        if ( $this->Document->documentElement )
        {
            do
            {
                $this->parseTag( $data, $pos, $this->Document->documentElement );
                if ( $this->QuitProcess )
                {
                    $ret = false;
                    break;
                }

            }
            while( $pos < strlen( $data ) );
        }
        else
        {
            $tmp = null;
            $this->parseTag( $data, $pos, $tmp );
            if ( $this->QuitProcess )
            {
                $ret = false;
            }
        }
        return $ret;
    }

    // The main recursive function for pass 1

    function parseTag( &$data, &$pos, &$parent )
    {
        // Find tag, determine it's type, name and attributes.
        $initialPos = $pos;

        if ( $pos >= strlen( $data ) )
        {
            return true;
        }
        $tagBeginPos = strpos( $data, '<', $pos );

        if ( $this->ParseLineBreaks )
        {
            // Regard line break as a start tag position
            $lineBreakPos = strpos( $data, "\n", $pos );
            if ( $lineBreakPos !== false )
            {
                $tagBeginPos = $tagBeginPos === false ? $lineBreakPos : min( $tagBeginPos, $lineBreakPos );
            }
        }

        $tagName = '';
        $attributes = null;
        // If it doesn't begin with '<' then its a text node.
        if ( $tagBeginPos != $pos || $tagBeginPos === false )
        {
            $pos = $initialPos;
            $tagName = $newTagName = '#text';
            $noChildren = true;

            if ( !$tagBeginPos )
            {
                $tagBeginPos = strlen( $data );
            }

            $textContent = substr( $data, $pos, $tagBeginPos - $pos );

            $textContent = $this->washText( $textContent );

            $pos = $tagBeginPos;
            if ( $textContent === '' )
            {
                return false;
            }
        }
        // Process closing tag.
        elseif ( $data[$tagBeginPos] == '<' && $tagBeginPos + 1 < strlen( $data ) &&
                 $data[$tagBeginPos + 1] == '/' )
        {
            $tagEndPos = strpos( $data, '>', $tagBeginPos + 1 );
            if ( $tagEndPos === false )
            {
                $pos = $tagBeginPos + 1;

                $this->handleError( self::ERROR_SYNTAX, ezpI18n::translate( 'kernel/classes/datatypes/ezxmltext', 'Wrong closing tag' ) );
                return false;
            }

            $pos = $tagEndPos + 1;
            $closedTagName = strtolower( trim( substr( $data, $tagBeginPos + 2, $tagEndPos - $tagBeginPos - 2 ) ) );

            // Find matching tag in ParentStack array
            $firstLoop = true;
            for( $i = count( $this->ParentStack ) - 1; $i >= 0; $i-- )
            {
                $parentNames = $this->ParentStack[$i];
                if ( $parentNames[0] == $closedTagName )
                {
                    array_pop( $this->ParentStack );
                    if ( !$firstLoop )
                    {
                        $pos = $tagBeginPos;
                        return true;
                    }
                    // If newTagName was '' we don't break children loop
                    elseif ( $parentNames[1] !== '' )
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
                $firstLoop = false;
            }

            $this->handleError( self::ERROR_SYNTAX, ezpI18n::translate( 'kernel/classes/datatypes/ezxmltext', 'Wrong closing tag : &lt;/%1&gt;.', false, array( $closedTagName ) ) );

            return false;
        }
        // Insert <br/> instead of linebreaks
        elseif ( $this->ParseLineBreaks && $data[$tagBeginPos] == "\n" )
        {
            $newTagName = 'br';
            $noChildren = true;
            $pos = $tagBeginPos + 1;
        }
        //  Regular tag: get tag's name and attributes.
        else
        {
            $tagEndPos = strpos( $data, '>', $tagBeginPos );
            if ( $tagEndPos === false )
            {
                $pos = $tagBeginPos + 1;

                $this->handleError( self::ERROR_SYNTAX, ezpI18n::translate( 'kernel/classes/datatypes/ezxmltext', 'Wrong opening tag' ) );
                return false;
            }

            $pos = $tagEndPos + 1;
            $tagString = substr( $data, $tagBeginPos + 1, $tagEndPos - $tagBeginPos - 1 );
            // Check for final backslash
            $noChildren = substr( $tagString, -1, 1 ) == '/' ? true : false;
            // Remove final backslash and spaces
            $tagString = preg_replace( "/\s*\/$/", "", $tagString );

            $firstSpacePos = strpos( $tagString, ' ' );
            if ( $firstSpacePos === false )
            {
                $tagName = strtolower( trim( $tagString ) );
                $attributeString = '';
            }
            else
            {
                $tagName = strtolower( substr( $tagString, 0, $firstSpacePos ) );
                $attributeString = substr( $tagString, $firstSpacePos + 1 );
                $attributeString = trim( $attributeString );
                // Parse attribute string
                if ( $attributeString )
                {
                    $attributes = $this->parseAttributes( $attributeString );
                }
            }

            // Determine tag's name
            if ( isset( $this->InputTags[$tagName] ) )
            {
                $thisInputTag = $this->InputTags[$tagName];

                if ( isset( $thisInputTag['name'] ) )
                {
                    $newTagName = $thisInputTag['name'];
                }
                else
                {
                    $newTagName = $this->callInputHandler( 'nameHandler', $tagName, $attributes );
                }
            }
            else
            {
                if ( $this->XMLSchema->exists( $tagName ) )
                {
                    $newTagName = $tagName;
                }
                else
                {
                    $this->handleError( self::ERROR_SYNTAX, ezpI18n::translate( 'kernel/classes/datatypes/ezxmltext', 'Unknown tag: &lt;%1&gt;.', false, array( $tagName ) ) );
                    return false;
                }
            }

            // Check 'noChildren' property
            if ( isset( $thisInputTag['noChildren'] ) )
            {
                $noChildren = true;
            }

            $thisOutputTag = isset( $this->OutputTags[$newTagName] ) ? $this->OutputTags[$newTagName] : null;

            // Implementation of 'autoCloseOn' rule ( Handling of unclosed tags, ex.: <p>, <li> )
            if ( isset( $thisOutputTag['autoCloseOn'] ) &&
                 $parent &&
                 $parent->parentNode instanceof DOMElement &&
                 in_array( $parent->nodeName, $thisOutputTag['autoCloseOn'] ) )
            {
                // Wrong nesting: auto-close parent and try to re-parse this tag at higher level
                array_pop( $this->ParentStack );
                $pos = $tagBeginPos;
                return true;
            }

            // Append to parent stack
            if ( !$noChildren && $newTagName !== false )
            {
                $this->ParentStack[] = array( $tagName, $newTagName, $attributeString );
            }

            if ( !$newTagName )
            {
                // If $newTagName is an empty string then it's not a error
                if ( $newTagName === false )
                    $this->handleError( self::ERROR_SYNTAX, ezpI18n::translate( 'kernel/classes/datatypes/ezxmltext', "Can't convert tag's name: &lt;%1&gt;.", false, array( $tagName ) ) );

                return false;
            }

            // wordmatch.ini support
            if ( $attributeString )
            {
                $attributes = $this->wordMatchSupport( $newTagName, $attributes, $attributeString );
            }
        }

        // Create text or normal node.
        if ( $newTagName == '#text' )
        {
            $element = $this->Document->createTextNode( $textContent );
        }
        else
        {
            $element = $this->Document->createElement( $newTagName );
        }

        if ( $attributes )
        {
            $this->setAttributes( $element, $attributes );
        }

        // Append element as a child or set it as root if there is no parent.
        if ( $parent )
        {
            $parent->appendChild( $element );
        }
        else
        {
            $this->Document->appendChild( $element );
        }

        $params = array();
        $params[] =& $data;
        $params[] =& $pos;
        $params[] =& $tagBeginPos;
        $result = $this->callOutputHandler( 'parsingHandler', $element, $params );

        if ( $result === false )
        {
            // This tag is already parsed in handler
            if ( !$noChildren )
            {
                array_pop( $this->ParentStack );
            }
            return false;
        }

        if ( $this->QuitProcess )
        {
            return false;
        }

        // Process children
        if ( !$noChildren )
        {
            do
            {
                $parseResult = $this->parseTag( $data, $pos, $element );
                if ( $this->QuitProcess )
                {
                    return false;
                }
            }
            while( $parseResult !== true );
        }

        return false;
    }

    /*
        Helper functions for pass 1
    */

    function parseAttributes( $attributeString )
    {
        // Convert single quotes to double quotes
        $attributeString = preg_replace( "/ +([a-zA-Z0-9:-_#\-]+) *\='(.*?)'/e", "' \\1'.'=\"'.'\\2'.'\"'", ' ' . $attributeString );

        // Convert no quotes to double quotes and remove extra spaces
        $attributeString = preg_replace( "/ +([a-zA-Z0-9:-_#\-]+) *\= *([^\s'\"]+)/e", "' \\1'.'=\"'.'\\2'.'\" '", $attributeString );

        // Split by quotes followed by spaces
        $attributeArray = preg_split( "#(?<=\") +#", $attributeString );

        $attributes = array();
        foreach( $attributeArray as $attrStr )
        {
            if ( !$attrStr || strlen( $attrStr ) < 4 )
            {
                continue;
            }

            list( $attrName, $attrValue ) = preg_split( "/ *= *\"/", $attrStr );

            $attrName = strtolower( trim( $attrName ) );
            if ( !$attrName )
            {
                continue;
            }

            $attrValue = substr( $attrValue, 0, -1 );
            if ( $attrValue === '' || $attrValue === false )
            {
                continue;
            }

            $attributes[$attrName] = $attrValue;
        }

        return $attributes;
    }

    function setAttributes( $element, $attributes )
    {
        $thisOutputTag = $this->OutputTags[$element->nodeName];

        foreach( $attributes as $key => $value )
        {
            // Convert attribute names
            if ( isset( $thisOutputTag['attributes'] ) &&
                 isset( $thisOutputTag['attributes'][$key] ) )
            {
                $qualifiedName = $thisOutputTag['attributes'][$key];
            }
            else
            {
                $qualifiedName = $key;
            }

            // Filter classes
            if ( $qualifiedName == 'class' )
            {
                $classesList = $this->XMLSchema->getClassesList( $element->nodeName );
                if ( !in_array( $value, $classesList ) )
                {
                    $this->handleError( self::ERROR_DATA,
                                        ezpI18n::translate( 'kernel/classes/datatypes/ezxmltext', "Class '%1' is not allowed for element &lt;%2&gt; (check content.ini).",
                                        false, array( $value, $element->nodeName ) ) );
                    continue;
                }
            }

            // Create attribute nodes
            if ( $qualifiedName )
            {
                if ( strpos( $qualifiedName, ':' ) )
                {
                    list( $prefix, $name ) = explode( ':', $qualifiedName );
                    if ( isset( $this->Namespaces[$prefix] ) )
                    {
                        $URI = $this->Namespaces[$prefix];
                        $element->setAttributeNS( $URI, $qualifiedName, $value );
                    }
                    else
                    {
                        eZDebug::writeWarning( "No namespace defined for prefix '$prefix'.", 'eZXML input parser' );
                    }
                }
                else
                {
                    $element->setAttribute( $qualifiedName, $value );
                }
            }
        }

        // Check for required attrs are present
        if ( isset( $this->OutputTags[$element->nodeName]['requiredInputAttributes'] ) )
        {
            foreach( $this->OutputTags[$element->nodeName]['requiredInputAttributes'] as $reqAttrName )
            {
                $presented = false;
                foreach( $attributes as $key => $value )
                {
                    if ( $key == $reqAttrName )
                    {
                        $presented = true;
                        break;
                    }
                }
                if ( !$presented )
                {
                    $this->handleError( self::ERROR_SCHEMA,
                                        ezpI18n::translate( 'kernel/classes/datatypes/ezxmltext', "Required attribute '%1' is not presented in tag &lt;%2&gt;.",
                                        false, array( $reqAttrName, $element->nodeName ) ) );
                }
            }
        }
    }

    function washText( $textContent )
    {
        $textContent = $this->entitiesDecode( $textContent );

        if ( !$this->AllowNumericEntities )
        {
            $textContent = $this->convertNumericEntities( $textContent );
        }

        if ( !$this->AllowMultipleSpaces )
        {
            $textContent = preg_replace( "/ {2,}/", " ", $textContent );
        }

        return $textContent;
    }

    function entitiesDecode( $text )
    {
        $text = str_replace( '&#039;', "'", $text );

        $text = str_replace( '&gt;', '>', $text );
        $text = str_replace( '&lt;', '<', $text );
        $text = str_replace( '&apos;', "'", $text );
        $text = str_replace( '&quot;', '"', $text );
        $text = str_replace( '&amp;', '&', $text );
        $text = str_replace( '&nbsp;', ' ', $text );
        return $text;
    }

    function convertNumericEntities( $text )
    {
        if ( strlen( $text ) < 4 )
        {
            return $text;
        }
        // Convert other HTML entities to the current charset characters.
        $codec = eZTextCodec::instance( 'unicode', false );
        $pos = 0;
        $domString = "";
        while ( $pos < strlen( $text ) - 1 )
        {
            $startPos = $pos;
            while( !( $text[$pos] == '&' && $text[$pos + 1] == '#' ) && $pos < strlen( $text ) - 1 )
            {
                $pos++;
            }

            $domString .= substr( $text, $startPos, $pos - $startPos );

            if ( $pos < strlen( $text ) - 1 )
            {
                $endPos = strpos( $text, ';', $pos + 2 );
                if ( $endPos === false )
                {
                    $convertedText .= '&#';
                    $pos += 2;
                    continue;
                }

                $code = substr( $text, $pos + 2, $endPos - ( $pos + 2 ) );
                $char = $codec->convertString( array( $code ) );

                $pos = $endPos + 1;
                $domString .= $char;
            }
            else
            {
                $domString .= substr( $text, $pos, 2 );
            }
        }
        return $domString;
    }

    /*!
     Returns modified attributes parameter
     */
    protected function wordMatchSupport( $newTagName, $attributes, $attributeString )
    {
        $ini = eZINI::instance( 'wordmatch.ini' );
        if ( $ini->hasVariable( $newTagName, 'MatchString' ) )
        {
            $matchArray = $ini->variable( $newTagName, 'MatchString' );
            if ( $matchArray )
            {
                foreach ( array_keys( $matchArray ) as $key )
                {
                    $matchString = $matchArray[$key];
                    if (  preg_match( "/$matchString/i", $attributeString ) )
                    {
                        $attributes['class'] = $key;
                        unset( $attributes['style'] );
                    }
                }
            }
        }
        return $attributes;
    }


    /*!
        \public
        Pass 2: Process the tree, run handlers, rebuild and validate.
    */

    function performPass2()
    {
        $tmp = null;

        $this->processSubtree( $this->Document->documentElement, $tmp );
    }

    // main recursive function for pass 2

    function processSubtree( $element, &$lastHandlerResult )
    {
        $ret = null;
        $tmp = null;

        // Call "Init handler"
        $this->callOutputHandler( 'initHandler', $element, $tmp );

        // Process children
        if ( $element->hasChildNodes() )
        {
            // Make another copy of children to save primary structure
            $childNodes = $element->childNodes;
            $childrenCount = $childNodes->length;

            // we can not loop directly over the childNodes property, because this will change while we are working on it's parent's children
            $children = array();
            foreach ( $childNodes as $childNode )
            {
                $children[] = $childNode;
            }

            $lastResult = null;
            $newElements = array();
            foreach ( $children as $child )
            {
                eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', 'processing children, current child: ' . $child->nodeName );
                $childReturn = $this->processSubtree( $child, $lastResult );

                unset( $lastResult );
                if ( isset( $childReturn['result'] ) )
                {
                    eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', 'return result is set for child ' . $child->nodeName );
                    $lastResult = $childReturn['result'];
                }

                if ( isset( $childReturn['new_elements'] ) )
                {
                    $newElements = array_merge( $newElements, $childReturn['new_elements'] );
                }

                if ( $this->QuitProcess )
                {
                    return $ret;
                }
            }

            eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $this->Document->saveXML(), 'XML before processNewElements for element ' . $element->nodeName );
            // process elements created in children handlers
            $this->processNewElements( $newElements );
            eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $this->Document->saveXML(), 'XML after processNewElements for element ' . $element->nodeName );
        }

        // Call "Structure handler"
        eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $this->Document->saveXML(), 'XML before callOutputHandler structHandler for element ' . $element->nodeName );
        $ret = $this->callOutputHandler( 'structHandler', $element, $lastHandlerResult );
        eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $this->Document->saveXML(), 'XML after callOutputHandler structHandler for element ' . $element->nodeName );
        eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $ret, 'return value of callOutputHandler structHandler for element ' . $element->nodeName );

        // Process by schema (check if element is allowed to exist)
        if ( !$this->processBySchemaPresence( $element ) )
        {
            eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $this->Document->saveXML(), 'XML after processBySchemaPresence for element ' . $element->nodeName );
            return $ret;
        }

        eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $this->Document->saveXML(), 'XML after processBySchemaPresence for element ' . $element->nodeName );

        // Process by schema (check place in the tree)
        if ( !$this->processBySchemaTree( $element ) )
        {
            eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $this->Document->saveXML(), 'XML after processBySchemaTree for element ' . $element->nodeName );
            return $ret;
        }

        eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $this->Document->saveXML(), 'XML after processBySchemaTree for element ' . $element->nodeName );


        $tmp = null;
        // Call "Publish handler"
        $this->callOutputHandler( 'publishHandler', $element, $tmp );

        // Process attributes according to the schema
        if ( $element->hasAttributes() )
        {
            if ( !$this->XMLSchema->hasAttributes( $element ) )
            {
                eZXMLInputParser::removeAllAttributes( $element );
            }
            else
            {
                $this->processAttributesBySchema( $element );
            }
        }
        return $ret;
    }
    /*
        Helper functions for pass 2
    */

    /*!
       Removes all attribute nodes from element node $element
    */
    function removeAllAttributes( DOMElement $element )
    {
        $attribs = $element->attributes;
        for ( $i = $attribs->length - 1; $i >= 0; $i-- )
        {
            $element->removeAttributeNode( $attribs->item( $i ) );
        }
    }

    // Check if the element is allowed to exist in this document and remove it if not.
    function processBySchemaPresence( $element )
    {
        $parent = $element->parentNode;
        if ( $parent instanceof DOMElement )
        {
            // If this is a foreign element, remove it
            if ( !$this->XMLSchema->exists( $element ) )
            {
                if ( $element->nodeName == 'custom' )
                {
                    $this->handleError( self::ERROR_SCHEMA,
                                        ezpI18n::translate( 'kernel/classes/datatypes/ezxmltext', "Custom tag '%1' is not allowed.",
                                        false, array( $element->getAttribute( 'name' ) ) ) );
                }
                $element = $parent->removeChild( $element );
                return false;
            }

            // Delete if children required and no children
            // If this is an auto-added element, then do not throw error

            if ( $element->nodeType == XML_ELEMENT_NODE && ( $this->XMLSchema->childrenRequired( $element ) || $element->getAttribute( 'children_required' ) )
                 && !$element->hasChildNodes() )
            {
                $element = $parent->removeChild( $element );
                if ( !$element->getAttributeNS( 'http://ez.no/namespaces/ezpublish3/temporary/', 'new-element' ) )
                {
                    $this->handleError( self::ERROR_SCHEMA, ezpI18n::translate( 'kernel/classes/datatypes/ezxmltext', "&lt;%1&gt; tag can't be empty.",
                                        false, array( $element->nodeName ) ) );
                    return false;
                }
            }
        }
        // TODO: break processing of any node that doesn't have parent
        //       and is not a root node.
        elseif ( $element->nodeName != 'section' )
        {
            return false;
        }
        return true;
    }

    // Check that element has a correct position in the tree and fix it if not.
    function processBySchemaTree( $element )
    {
        $parent = $element->parentNode;

        if ( $parent instanceof DOMElement )
        {
            $schemaCheckResult = $this->XMLSchema->check( $parent, $element );
            if ( !$schemaCheckResult )
            {
                if ( $schemaCheckResult === false )
                {
                    // Remove indenting spaces
                    if ( $element->nodeType == XML_TEXT_NODE && !trim( $element->textContent ) )
                    {
                        $element = $parent->removeChild( $element );
                        return false;
                    }

                    $elementName = $element->nodeType == XML_ELEMENT_NODE ? '&lt;' . $element->nodeName . '&gt;' : $element->nodeName;
                    $this->handleError( self::ERROR_SCHEMA, ezpI18n::translate( 'kernel/classes/datatypes/ezxmltext', "%1 is not allowed to be a child of &lt;%2&gt;.",
                                        false, array( $elementName, $parent->nodeName ) ) );
                }
                $this->fixSubtree( $element, $element );
                return false;
            }
        }
        // TODO: break processing of any node that doesn't have parent
        //       and is not a root node.
        elseif ( $element->nodeName != 'section' )
        {
            return false;
        }
        return true;
    }

    // Remove only nodes that don't match schema (recursively)
    function fixSubtree( $element, $mainChild )
    {
        $parent = $element->parentNode;
        $mainParent = $mainChild->parentNode;
        while ( $element->hasChildNodes() )
        {
            $child = $element->firstChild;

            $child = $element->removeChild( $child );
            $child = $mainParent->insertBefore( $child, $mainChild );

            if ( !$this->XMLSchema->check( $mainParent, $child ) )
            {
                $this->fixSubtree( $child, $mainChild );
            }
        }
        $parent->removeChild( $element );
    }

    function processAttributesBySchema( $element )
    {
        // Remove attributes that don't match schema
        $schemaAttributes = $this->XMLSchema->attributes( $element );
        $schemaCustomAttributes = $this->XMLSchema->customAttributes( $element );

        $attributes = $element->attributes;

        for ( $i = $attributes->length - 1; $i >=0; $i-- )
        {
            $attr = $attributes->item( $i );
            if ( $attr->prefix == 'tmp' )
            {
                $element->removeAttributeNode( $attr );
                continue;
            }

            $allowed = false;
            $removeAttr = false;

            $fullName = $attr->prefix ? $attr->prefix . ':' . $attr->localName : $attr->nodeName;

            // check for allowed custom attributes (3.9)
            if ( $attr->prefix == 'custom' && in_array( $attr->localName, $schemaCustomAttributes ) )
            {
                $allowed = true;
            }
            else
            {
                if ( in_array( $fullName, $schemaAttributes ) )
                {
                   $allowed = true;
                }
                elseif ( in_array( $fullName, $schemaCustomAttributes ) )
                {
                    // add 'custom' prefix if it is not given
                    $allowed = true;
                    $removeAttr = true;
                    $element->setAttributeNS( $this->Namespaces['custom'], 'custom:' . $fullName, $attr->value );
                }
            }

            if ( !$allowed )
            {
                $removeAttr = true;
                $this->handleError( self::ERROR_SCHEMA,
                                    ezpI18n::translate( 'kernel/classes/datatypes/ezxmltext', "Attribute '%1' is not allowed in &lt;%2&gt; element.",
                                    false, array( $fullName, $element->nodeName ) ) );
            }
            elseif ( $this->RemoveDefaultAttrs )
            {
                // Remove attributes having default values
                $default = $this->XMLSchema->attrDefaultValue( $element->nodeName, $fullName );
                if ( $attr->value == $default )
                {
                    $removeAttr = true;
                }
            }

            if ( $removeAttr )
            {
                $element->removeAttributeNode( $attr );
            }
        }
    }

    function callInputHandler( $handlerName, $tagName, &$attributes )
    {
        $result = null;
        $thisInputTag = $this->InputTags[$tagName];
        if ( isset( $thisInputTag[$handlerName] ) )
        {
            if ( is_callable( array( $this, $thisInputTag[$handlerName] ) ) )
            {
                $result = call_user_func_array( array( $this, $thisInputTag[$handlerName] ),
                                                array( $tagName, &$attributes ) );
            }
            else
            {
                eZDebug::writeWarning( "'$handlerName' input handler for tag <$tagName> doesn't exist: '" . $thisInputTag[$handlerName] . "'.", 'eZXML input parser' );
            }
        }
        return $result;
    }

    function callOutputHandler( $handlerName, $element, &$params )
    {
        $result = null;
        $thisOutputTag = $this->OutputTags[$element->nodeName];
        if ( isset( $thisOutputTag[$handlerName] ) )
        {
            if ( is_callable( array( $this, $thisOutputTag[$handlerName] ) ) )
            {
                $result = call_user_func_array( array( $this, $thisOutputTag[$handlerName] ),
                                                array( $element, &$params ) );
            }
            else
            {
                eZDebug::writeWarning( "'$handlerName' output handler for tag <$element->nodeName> doesn't exist: '" . $thisOutputTag[$handlerName] . "'.", 'eZXML input parser' );
            }
        }

        return $result;
    }

    // Creates new element and adds it to array for further post-processing.
    // Use this function if you need to process newly created element (check it by schema
    // and call 'structure' and 'publish' handlers)
    function createAndPublishElement( $elementName, &$ret )
    {
        $element = $this->Document->createElement( $elementName );
        $element->setAttributeNS( 'http://ez.no/namespaces/ezpublish3/temporary/', 'tmp:new-element', 'true' );

        if ( !isset( $ret['new_elements'] ) )
        {
            $ret['new_elements'] = array();
        }

        $ret['new_elements'][] = $element;
        return $element;
    }

    function processNewElements( $createdElements )
    {
        // Call handlers for newly created elements
        foreach ( $createdElements as $element )
        {
            eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', 'processing new element ' . $element->nodeName );
            $tmp = null;

            if ( !$this->processBySchemaPresence( $element ) )
            {
                eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $this->Document->saveXML(), 'xml string after processBySchemaPresence for new element ' . $element->nodeName );
                continue;
            }
            eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $this->Document->saveXML(), 'xml string after processBySchemaPresence for new element ' . $element->nodeName );


            // Call "Structure handler"
            $this->callOutputHandler( 'structHandler', $element, $tmp );

            if ( !$this->processBySchemaTree( $element ) )
            {
                eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $this->Document->saveXML(), 'xml string after processBySchemaTree for new element ' . $element->nodeName );
                continue;
            }
            eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $this->Document->saveXML(), 'xml string after processBySchemaTree for new element ' . $element->nodeName );


            $tmp2 = null;
            // Call "Publish handler"
            $this->callOutputHandler( 'publishHandler', $element, $tmp2 );
            eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $this->Document->saveXML(), 'xml string after callOutputHandler publishHandler for new element ' . $element->nodeName );

            // Process attributes according to the schema
            if( $element->hasAttributes() )
            {
                if ( !$this->XMLSchema->hasAttributes( $element ) )
                {
                    eZXMLInputParser::removeAllAttributes( $element );
                }
                else
                {
                    $this->processAttributesBySchema( $element );
                }
            }
        }
    }

    /// \public
    function getMessages()
    {
        return $this->Messages;
    }

    /// \public
    function isValid()
    {
        return $this->IsInputValid;
    }

    function handleError( $type, $message )
    {
        if ( $type & $this->DetectErrorLevel )
        {
            $this->IsInputValid = false;
            if ( $message )
            {
                $this->Messages[] = $message;
            }
        }

        if ( $type & $this->ValidateErrorLevel )
        {
            $this->IsInputValid = false;
            $this->QuitProcess = true;
        }
    }

    public $DOMDocumentClass = 'DOMDOcument';

    public $XMLSchema;
    public $Document = null;
    public $Messages = array();
    public $eZPublishVersion;

    public $ParentStack = array();

    public $ValidateErrorLevel;
    public $DetectErrorLevel;

    public $IsInputValid = true;
    public $QuitProcess = false;

    // options that depend on settings
    public $TrimSpaces = true;
    public $AllowMultipleSpaces = false;
    public $AllowNumericEntities = false;
    public $StrictHeaders = false;

    // options that depend on parameters passed
    public $ParseLineBreaks = false;
    public $RemoveDefaultAttrs = false;
}
?>
