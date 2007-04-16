<?php
//
// Definition of eZXMLInputParser class
//
// Created on: <27-Mar-2006 15:28:39 ks>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

/*
    Base class for the input parser.
    The goal of the parser is XML/HTML analyzing, fixing and transforming.
    The input is processed in 2 passes:
    - 1st pass: Parsing input, check for syntax errors, build DOM tree.
    - 2nd pass: Walking through DOM tree, checking validity by XML schema,
                calling tag handlers to transform the tree.
                
    Both passes are controlled by the arrays described bellow and user handler functions.

*/

include_once( "lib/ezxml/classes/ezxml.php" );

if ( !class_exists( 'eZXMLSchema' ) )
    include_once( 'kernel/classes/datatypes/ezxmltext/ezxmlschema.php' );

/// \deprecated (back-compatibility)
define( 'EZ_XMLINPUTPARSER_SHOW_NO_ERRORS', 0 );
define( 'EZ_XMLINPUTPARSER_SHOW_SCHEMA_ERRORS', 1 );
define( 'EZ_XMLINPUTPARSER_SHOW_ALL_ERRORS', 2 );

/// Use these constants for error types
define( 'EZ_XMLINPUTPARSER_ERROR_NONE', 0 );
define( 'EZ_XMLINPUTPARSER_ERROR_SYNTAX', 4 );
define( 'EZ_XMLINPUTPARSER_ERROR_SCHEMA', 8 );
define( 'EZ_XMLINPUTPARSER_ERROR_DATA', 16 );
define( 'EZ_XMLINPUTPARSER_ERROR_ALL', 28 ); // 4+8+16

class eZXMLInputParser
{

    /* $InputTags array contains properties of elements that come from the input.
    
    Each array element describes a tag that comes from the input. Arrays index is
    a tag's name. Each element is an array that may contain the following members:
    
    'name'        - a string representing a new name of the tag,
    'nameHandler' - a name of the function that returns new tag name. Function format:
                    function &tagNameHandler( $tagName, &$attributes )
                    
    If no of those elements are defined the original tag's name is used.
                    
    'noChildren'  - boolean value that determines if this tag could have child tags,
                    default value is false.
    
    Example:
    
    var $InputTags = array(
    
        'original-name' => array( 'name' => 'new-name' ),
    
        'original-name2' => array( 'nameHandler' => 'tagNameHandler',
                                   'noChildren' => true ),
                            
         ...
         
         );
    */

    var $InputTags = array();

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
    
    var $OutputTags = array(
    
        'custom'    => array( 'parsingHandler' => 'parsingHandlerCustom',
                              'initHandler' => 'initHandlerCustom',
                              'structHandler' => 'structHandlerCustom',
                              'publishHandler' => 'publishHandlerCustom',
                              'attributes' => array( 'title' => 'name' ) ),
                              
        ...
    );
                     
    */

    var $OutputTags = array();

    var $Namespaces = array( 'image' => 'http://ez.no/namespaces/ezpublish3/image/',
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

    function eZXMLInputParser( $validateErrorLevel = EZ_XMLINPUTPARSER_ERROR_NONE, $detectErrorLevel = EZ_XMLINPUTPARSER_ERROR_NONE, $parseLineBreaks = false,
                               $removeDefaultAttrs = false )
    {
        // Back-compatibility fixes:
        if ( $detectErrorLevel === EZ_XMLINPUTPARSER_SHOW_SCHEMA_ERRORS )
            $detectErrorLevel = EZ_XMLINPUTPARSER_ERROR_SCHEMA;
        elseif ( $detectErrorLevel === EZ_XMLINPUTPARSER_SHOW_ALL_ERRORS )
            $detectErrorLevel = EZ_XMLINPUTPARSER_ERROR_ALL;

        if ( $validateErrorLevel === false )
            $validateErrorLevel = EZ_XMLINPUTPARSER_ERROR_NONE;
        elseif ( $validateErrorLevel === true )
            $validateErrorLevel = $detectErrorLevel;

        $this->ValidateErrorLevel = $validateErrorLevel;
        $this->DetectErrorLevel = $detectErrorLevel;

        $this->RemoveDefaultAttrs = $removeDefaultAttrs;
        $this->ParseLineBreaks = $parseLineBreaks;

        $this->XMLSchema =& eZXMLSchema::instance();
        //$this->getClassesList();

        include_once( 'lib/version.php' );
        $this->eZPublishVersion = eZPublishSDK::majorVersion() + eZPublishSDK::minorVersion() * 0.1;

        $ini =& eZINI::instance( 'ezxml.ini' );
        if ( $this->eZPublishVersion >= 3.8 )
        {
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
        }
        else
        {
            $this->TrimSpaces = true;
            $this->AllowMultipleSpaces = false;
        }

        if ( $this->eZPublishVersion >= 3.9 )
        {
            if ( $ini->hasVariable( 'InputSettings', 'AllowNumericEntities' ) )
            {
                $allowNumericEntities = $ini->variable( 'InputSettings', 'AllowNumericEntities' );
                $this->AllowNumericEntities = $allowNumericEntities == 'true' ? true : false;
            }
        }
        else
        {
            $this->AllowNumericEntities = false;
        }

        $contentIni =& eZINI::instance( 'content.ini' );
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

        if ( $createRootNode )
        {
            // Creating root section with namespaces definitions
            $this->Document = new $this->DOMDocumentClass( '', true );
            $mainSection =& $this->Document->createElement( 'section' );
            $this->Document->appendChild( $mainSection );
            foreach( array( 'image', 'xhtml', 'custom' ) as $prefix )
            {
                $mainSection->setAttributeNS( 'http://www.w3.org/2000/xmlns/', 'xmlns:' . $prefix, $this->Namespaces[$prefix] );
            }
        }

        // Perform pass 1
        // Parsing the source string
        $this->performPass1( $text );

        if ( $this->QuitProcess )
            return false;

        // Perform pass 2
        $this->performPass2();

        if ( $this->QuitProcess )
            return false;

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

        if ( $this->Document->Root )
        {
            do
            {
                $this->parseTag( $data, $pos, $this->Document->Root );
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

        /*if ( $this->trimSpaces )
        {
            while( $pos < strlen( $data ) && $data[$pos] == ' ' ) $pos++;
        }*/

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
                if ( $tagBeginPos === false )
                    $tagBeginPos = $lineBreakPos;
                else
                    $tagBeginPos = min( $tagBeginPos, $lineBreakPos );
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
                $tagBeginPos = strlen( $data );

            $textContent = substr( $data, $pos, $tagBeginPos - $pos );

            $textContent = $this->washText( $textContent );

            $pos = $tagBeginPos;
            if ( $textContent === '' )
                return false;
        }
        // Process closing tag.
        elseif ( $data[$tagBeginPos] == '<' && $tagBeginPos + 1 < strlen( $data ) &&
                 $data[$tagBeginPos + 1] == '/' )
        {
            $tagEndPos = strpos( $data, '>', $tagBeginPos + 1 );
            if ( $tagEndPos === false )
            {
                $pos = $tagBeginPos + 1;

                $this->handleError( EZ_XMLINPUTPARSER_ERROR_SYNTAX, 'Wrong closing tag' );
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
                        return true;
                    else
                        return false;
                }
                $firstLoop = false;
            }

            $this->handleError( EZ_XMLINPUTPARSER_ERROR_SYNTAX, 'Wrong closing tag : &lt;/%1&gt;.', array( $closedTagName ) );

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

                $this->handleError( EZ_XMLINPUTPARSER_ERROR_SYNTAX, 'Wrong opening tag' );
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
                    $attributes = $this->parseAttributes( $attributeString );
            }

            // Determine tag's name
            if ( isset( $this->InputTags[$tagName] ) )
            {
                $thisInputTag =& $this->InputTags[$tagName];

                if ( isset( $thisInputTag['name'] ) )
                    $newTagName = $thisInputTag['name'];
                else
                    $newTagName =& $this->callInputHandler( 'nameHandler', $tagName, $attributes );
            }
            else
            {
                if ( $this->XMLSchema->exists( $tagName ) )
                {
                    $newTagName = $tagName;
                }
                else
                {
                    $this->handleError( EZ_XMLINPUTPARSER_ERROR_SYNTAX, 'Unknown tag: &lt;%1&gt;.', array( $tagName ) );
                    return false;
                }
            }

            // Check 'noChildren' property
            if ( isset( $thisInputTag['noChildren'] ) )
                $noChildren = true;

            $thisOutputTag =& $this->OutputTags[$newTagName];

            // Implementation of 'autoCloseOn' rule ( Handling of unclosed tags, ex.: <p>, <li> )
            if ( isset( $thisOutputTag['autoCloseOn'] ) &&
                 $parent &&
                 $parent->parentNode &&
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
                    $this->handleError( EZ_XMLINPUTPARSER_ERROR_SYNTAX, "Can't convert tag's name: &lt;%1&gt;.", array( $tagName ) );

                return false;
            }

            // wordmatch.ini support
            if ( $attributeString )
            {
                $this->wordMatchSupport( $newTagName, $attributes, $attributeString );
            }
        }

        // Create text or normal node.
        if ( $newTagName == '#text' )
            $element = $this->Document->createTextNode( $textContent );
        else
            $element =& $this->Document->createElement( $newTagName );

        if ( $attributes )
        {
            $this->setAttributes( $element, $attributes );
        }

        // Append element as a child or set it as root if there is no parent.
        if ( $parent )
            $parent->appendChild( $element );
        else
            $this->Document->appendChild( $element );
        // php5 TODO : $this->Document->documentElement->appendChild( $element );

        $params = array();
        $params[] =& $data;
        $params[] =& $pos;
        $params[] =& $tagBeginPos;
        $result =& $this->callOutputHandler( 'parsingHandler', $element, $params );

        if ( $result === false )
        {
            // This tag is already parsed in handler
            if ( !$noChildren )
                array_pop( $this->ParentStack );
            return false;
        }

        if ( $this->QuitProcess )
            return false;

        // Process children
        if ( !$noChildren )
        {
            do
            {
                $parseResult = $this->parseTag( $data, $pos, $element );
                if ( $this->QuitProcess )
                    return false;
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
                continue;
    
            list( $attrName, $attrValue ) = split( '="', $attrStr );
    
            $attrName = strtolower( trim( $attrName ) );
            if ( !$attrName )
                continue;
    
            $attrValue = substr( $attrValue, 0, -1 );
            if ( $attrValue === '' || $attrValue === false )
                continue;
    
            $attributes[$attrName] = $attrValue;
        }
    
        return $attributes;
    }

    function setAttributes( &$element, $attributes )
    {
        $thisOutputTag =& $this->OutputTags[$element->nodeName];

        foreach( $attributes as $key => $value )
        {
            // Convert attribute names
            if ( isset( $thisOutputTag['attributes'] ) &&
                 isset( $thisOutputTag['attributes'][$key] ) )
            {
                $qualifiedName = $thisOutputTag['attributes'][$key];
            }
            else
                $qualifiedName = $key;

            // Filter classes
            if ( $qualifiedName == 'class' )
            {
                $classesList = $this->XMLSchema->getClassesList( $element->nodeName );
                if ( !in_array( $value, $classesList ) )
                {
                    $this->handleError( EZ_XMLINPUTPARSER_ERROR_DATA,
                                        "Class '%1' is not allowed for element &lt;%2&gt; (check content.ini).",
                                        array( $value, $element->nodeName ) );
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
                        eZDebug::writeWarning( "No namespace defined for prefix '$prefix'.", 'eZXML input parser' );
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
                    $this->handleError( EZ_XMLINPUTPARSER_ERROR_SCHEMA,
                                        "Required attribute '%1' is not presented in tag &lt;%2&gt;.",
                                        array( $reqAttrName, $element->nodeName ) );
                }
            }
        }
    }

    function washText( $textContent )
    {
        $textContent = $this->entitiesDecode( $textContent );

        if ( !$this->AllowNumericEntities )
            $textContent = $this->convertNumericEntities( $textContent );

        if ( !$this->AllowMultipleSpaces )
            $textContent = preg_replace( "/ {2,}/", " ", $textContent );

        return $textContent;
    }

    function entitiesDecode( $text )
    {
        //$text = str_replace( "&amp;", "&", $text );
        $text = str_replace( "&#039;", "'", $text );

        $text = str_replace( "&gt;", ">", $text );
        $text = str_replace( "&lt;", "<", $text );
        $text = str_replace( "&apos;", "'", $text );
        $text = str_replace( "&quot;", '"', $text );
        $text = str_replace( "&amp;", "&", $text );
        $text = str_replace( "&nbsp;", " ", $text );
        return $text;
    }

    function convertNumericEntities( $text )
    {
        if ( strlen( $text ) < 4 )
        {
            return $text;
        }
        // Convert other HTML entities to the current charset characters.
        include_once( 'lib/ezi18n/classes/eztextcodec.php' );
        $codec = eZTextCodec::instance( 'unicode', false );
        $pos = 0;
        $domString = "";
        while ( $pos < strlen( $text ) - 1 )
        {
            $startPos = $pos;
            while( !( $text[$pos] == '&' && $text[$pos + 1] == '#' ) && $pos < strlen( $text ) - 1 )
                $pos++;
    
            $domString .= substr( $text, $startPos, $pos - $startPos );
    
            if ( $pos < strlen( $text ) - 1 )
            {
                $endPos = strpos( $text, ";", $pos + 2 );
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

    /*function getClassesList()
    {
        $ini =& eZINI::instance( 'content.ini' );
        foreach( array_keys( $this->OutputTags ) as $tagName )
        {
            if ( $ini->hasVariable( $tagName, 'AvailableClasses' ) )
            {
                $avail = $ini->variable( $tagName, 'AvailableClasses' );
                if ( is_array( $avail ) && count( $avail ) )
                    $this->OutputTags[$tagName]['classesList'] = $avail;
                else
                    $this->OutputTags[$tagName]['classesList'] = array();
            }
            else
                $this->OutputTags[$tagName]['classesList'] = array();
        }
    }*/

    function wordMatchSupport( $newTagName, &$attributes, $attributeString )
    {
        $ini =& eZINI::instance( 'wordmatch.ini' );
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
    }


    /*!
        \public
        Pass 2: Process the tree, run handlers, rebuild and validate.
    */

    function performPass2()
    {
        $tmp = null;

        //php5 TODO: $this->Document->documentElement;
        $this->processSubtree( $this->Document->Root, $tmp );
    }

    // main recursive function for pass 2

    function &processSubtree( &$element, &$lastHandlerResult )
    {
        $ret = null;
        $tmp = null;

        //eZDOMNode::writeDebugStr( $element, '$element' );
        //eZDOMNode::writeDebugStr( $this->Document->Root, 'root' );

        // Call "Init handler"
        $this->callOutputHandler( 'initHandler', $element, $tmp );

        // Process children
        if ( $element->hasChildNodes() )
        {
            // Make another copy of children to save primary structure
            // php5 TODO: childNodes->item(), childNodes->length()
            $childrenCount = count( $element->Children );
            $children = array();
            foreach( array_keys( $element->Children ) as $child_key )
            {
                $children[] =& $element->Children[$child_key];
            }
            $lastResult = null;
            $newElements = array();
            for( $i = 0; $i < $childrenCount; $i++ )
            {
                $childReturn =& $this->processSubtree( $children[$i], $lastResult );

                if ( isset( $childReturn['result'] ) )
                {
                    unset( $lastResult );
                    $lastResult =& $childReturn['result'];
                }
                else
                    unset( $lastResult );

                if ( isset( $childReturn['new_elements'] ) )
                    $newElements = array_merge( $newElements, $childReturn['new_elements'] );

                unset( $childReturn );

                if ( $this->QuitProcess )
                    return $ret;
            }

            // process elements created in children handlers
            $this->processNewElements( $newElements );
        }

        // Call "Structure handler"
        $ret =& $this->callOutputHandler( 'structHandler', $element, $lastHandlerResult );

        // Process by schema (check if element is allowed to exist)
        if ( !$this->processBySchemaPresence( $element ) )
            return $ret;

        // Process by schema (check place in the tree)
        if ( !$this->processBySchemaTree( $element ) )
            return $ret;

        $tmp = null;
        // Call "Publish handler"
        $this->callOutputHandler( 'publishHandler', $element, $tmp );

        // Process attributes according to the schema
        if( $element->hasAttributes() )
        {
            if ( !$this->XMLSchema->hasAttributes( $element ) )
            {
                $element->removeAttributes();
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

    // Check if the element is allowed to exist in this document and remove it if not.
    function processBySchemaPresence( &$element )
    {
        $parent =& $element->parentNode;
        if ( $parent )
        {
            // If this is a foreign element, remove it
            if ( !$this->XMLSchema->exists( $element ) )
            {
                if ( $element->nodeName == 'custom' )
                {
                    $this->handleError( EZ_XMLINPUTPARSER_ERROR_SCHEMA, "Custom tag '%1' is not allowed.",
                                        array( $element->getAttribute( 'name' ) ) );
                }
                $parent->removeChild( $element );
                return false;
            }

            // Delete if children required and no children
            // If this is an auto-added element, then do not throw error
            if ( ( $this->XMLSchema->childrenRequired( $element ) || $element->getAttribute( 'children_required' ) )
                 && !$element->hasChildNodes() )
            {
                $parent->removeChild( $element );
                if ( !$element->getAttributeNS( 'http://ez.no/namespaces/ezpublish3/temporary/', 'new-element' ) )
                {
                    $this->handleError( EZ_XMLINPUTPARSER_ERROR_SCHEMA, "&lt;%1&gt; tag can't be empty.",
                                    array( $element->nodeName ) );
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
    function processBySchemaTree( &$element )
    {
        $parent =& $element->parentNode;
        if ( $parent )
        {
            $schemaCheckResult = $this->XMLSchema->check( $parent, $element );
            if ( !$schemaCheckResult )
            {
                if ( $schemaCheckResult === false )
                {
                    // Remove indenting spaces
                    if ( $element->Type == EZ_XML_NODE_TEXT && !trim( $element->content() ) )
                    {
                        $parent->removeChild( $element );
                        return false;
                    }

                    $elementName = $element->nodeName == '#text' ? $element->nodeName : '&lt;' . $element->nodeName . '&gt;';
                    $this->handleError( EZ_XMLINPUTPARSER_ERROR_SCHEMA, "%1 is not allowed to be a child of &lt;%2&gt;.",
                                        array( $elementName, $parent->nodeName ) );
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
    function fixSubtree( &$element, &$mainChild )
    {
        $parent =& $element->parentNode;
        $mainParent =& $mainChild->parentNode;
        if ( $element->hasChildNodes() )
        {
            foreach( array_keys( $element->Children ) as $child_key )
            {
                $child =& $element->Children[$child_key];

                $element->removeChild( $child );
                // php5 TODO: use child returned by insertBefore (php dom manual)
                $mainParent->insertBefore( $child, $mainChild );

                if ( !$this->XMLSchema->check( $mainParent, $child ) )
                    $this->fixSubtree( $child, $mainChild );
            }
        }
        $parent->removeChild( $element );
    }

    function processAttributesBySchema( &$element )
    {
        // Remove attributes that don't match schema
        $schemaAttributes = $this->XMLSchema->attributes( $element );
        if ( $this->eZPublishVersion >= 3.9 )
        {
            $schemaCustomAttributes = $this->XMLSchema->customAttributes( $element );
        }

        $attributes = $element->attributes();

        foreach( $attributes as $attr )
        {
            if ( $attr->Prefix == 'tmp' )
            {
                $element->removeAttributeNS( $attr->NamespaceURI, $attr->LocalName );
                continue;
            }

            $allowed = false;
            $removeAttr = false;

            // php5 TODO: small letters
            if ( $attr->Prefix )
                $fullName = $attr->Prefix . ':' . $attr->LocalName;
            else
                $fullName = $attr->LocalName;

            if ( $this->eZPublishVersion >= 3.9 )
            {
                // check for allowed custom attributes (3.9)
                if ( $attr->Prefix == 'custom' && in_array( $attr->LocalName, $schemaCustomAttributes ) )
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
                        $element->setAttributeNS( $this->Namespaces['custom'], 'custom:' . $fullName, $attr->content() );
                    }
                }
            }
            else
            {
                if ( $attr->Prefix == 'custom' ||
                     in_array( $fullName, $schemaAttributes ) )
                {
                    $allowed = true;
                }
            }

            if ( !$allowed )
            {
                $removeAttr = true;
                $this->handleError( EZ_XMLINPUTPARSER_ERROR_SCHEMA,
                                    "Attribute '%1' is not allowed in &lt;%2&gt; element.",
                                    array( $fullName, $element->nodeName ) );
            }
            elseif ( $this->RemoveDefaultAttrs ) 
            {
                // Remove attributes having default values
                $default = $this->XMLSchema->attrDefaultValue( $element->nodeName, $fullName );
                if ( $attr->Content == $default )
                {
                    $removeAttr = true;
                }
            }

            if ( $removeAttr )
            {
                if ( $attr->Prefix )
                    $element->removeAttributeNS( $attr->NamespaceURI, $attr->LocalName );
                else
                    $element->removeAttribute( $attr->nodeName );
            }
        }
    }

    function &callInputHandler( $handlerName, $tagName, &$attributes )
    {
        $result = null;
        $thisInputTag =& $this->InputTags[$tagName];
        if ( isset( $thisInputTag[$handlerName] ) )
        {
            if ( is_callable( array( $this, $thisInputTag[$handlerName] ) ) )
                eval( '$result =& $this->' . $thisInputTag[$handlerName] . '( $tagName, $attributes );' );
            else
                eZDebug::writeWarning( "'$handlerName' input handler for tag <$tagName> doesn't exist: '" . $thisInputTag[$handlerName] . "'.", 'eZXML input parser' );
        }
        return $result;
    }

    function &callOutputHandler( $handlerName, &$element, &$params )
    {
        $result = null;
        $thisOutputTag =& $this->OutputTags[$element->nodeName];
        if ( isset( $thisOutputTag[$handlerName] ) )
        {
            if ( is_callable( array( $this, $thisOutputTag[$handlerName] ) ) )
                eval( '$result =& $this->' . $thisOutputTag[$handlerName] . '( $element, $params );' );
            else
                eZDebug::writeWarning( "'$handlerName' output handler for tag <$element->nodeName> doesn't exist: '" . $thisOutputTag[$handlerName] . "'.", 'eZXML input parser' );
        }

        return $result;
    }

    // Creates new element and adds it to array for further post-processing.
    // Use this function if you need to process newly created element (check it by schema
    // and call 'structure' and 'publish' handlers)
    function &createAndPublishElement( $elementName, &$ret )
    {
        $element =& $this->Document->createElement( $elementName );
        $element->setAttributeNS( 'http://ez.no/namespaces/ezpublish3/temporary/', 'tmp:new-element', 'true' );

        if ( !isset( $ret['new_elements'] ) )
     	    $ret['new_elements'] = array();

        $ret['new_elements'][] =& $element;
        return $element;
    }

    function processNewElements( &$createdElements )
    {
        // Call handlers for newly created elements
        foreach( array_keys( $createdElements ) as $key )
        {
            $element =& $createdElements[$key];

            $tmp = null;

            if ( !$this->processBySchemaPresence( $element ) )
                continue;

            // Call "Structure handler"
            $this->callOutputHandler( 'structHandler', $element, $tmp );

            if ( !$this->processBySchemaTree( $element ) )
                continue;

            $tmp2 = null;
            // Call "Publish handler"
            $this->callOutputHandler( 'publishHandler', $element, $tmp2 );

            // Process attributes according to the schema
            if( $element->hasAttributes() )
            {
                if ( !$this->XMLSchema->hasAttributes( $element ) )
                {
                    $element->removeAttributes();
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

    function handleError( $type, $message, $params = false )
    {
        if ( $type & $this->DetectErrorLevel )
        {
            $this->IsInputValid = false;
            if ( $message )
                $this->Messages[] = ezi18n( 'kernel/classes/datatypes/ezxmltext', $message,
                                            false, $params );
        }

        if ( $type & $this->ValidateErrorLevel )
        {
            $this->IsInputValid = false;
            $this->QuitProcess = true;
        }
    }

    var $DOMDocumentClass = 'eZDOMDocument';

    var $XMLSchema;
    var $Document;
    var $Messages = array();
    var $eZPublishVersion;

    var $ParentStack = array();
  
    var $ValidateErrorLevel;
    var $DetectErrorLevel;

    var $IsInputValid = true;
    var $QuitProcess = false;
    
    // options that depend on settings
    var $TrimSpaces = true;
    var $AllowMultipleSpaces = false;
    var $AllowNumericEntities = false;
    var $StrictHeaders = false;

    // options that depend on parameters passed
    var $parseLineBreaks = false;
    var $removeDefaultAttrs = false;

    var $createdElements = array();
}
?>
