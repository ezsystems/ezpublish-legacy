<?php
//
// Definition of eZXMLInputParser class
//
// Created on: <27-Mar-2006 15:28:39 ks>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
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

define( 'EZ_XMLINPUTPARSER_SHOW_NO_ERRORS', 0 );
define( 'EZ_XMLINPUTPARSER_SHOW_SCHEMA_ERRORS', 1 );
define( 'EZ_XMLINPUTPARSER_SHOW_ALL_ERRORS', 2 );

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
    
        'old-name' => array( 'name' => 'new-name' ),
    
        'tagname' => array( 'nameHandler' => 'tagNameHandler',
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
                             'custom' => 'http://ez.no/namespaces/ezpublish3/custom/' );

    /*!
    
    The constructor.
       
    \param $validate   If true, parser quits immediately after validity flag (isInputValid)
                       set to false and function 'process' returns false.
                       
                       If false, parser tries to modify and transform the input automatically
                       in order to get the valid result. 
    */

    function eZXMLInputParser( $validate = false, $errorLevel = EZ_XMLINPUTPARSER_SHOW_NO_ERRORS, $parseLineBreaks = false,
                               $removeDefaultAttrs = false )
    {
        $this->quitIfInvalid = $validate;
        $this->errorLevel = $errorLevel;

        $this->removeDefaultAttrs = $removeDefaultAttrs;
        $this->parseLineBreaks = $parseLineBreaks;

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

    }

    function setDOMDocumentClass( $DOMDocumentClass )
    {
        $this->DOMDocumentClass = $DOMDocumentClass;
    }

    function setParseLineBreaks( $value )
    {
        $this->parseLineBreaks = $value;
    }

    function setRemoveDefaultAttrs( $value )
    {
        $this->removeDefaultAttrs = $value;
    }

    /*!
        Call this function to process your input
    */
    function process( $text, $createRootNode = true )
    {
        $text = str_replace( "\r", '', $text);
        $text = str_replace( "\t", ' ', $text);
        if ( !$this->parseLineBreaks )
        {
            $text = str_replace( "\n", '', $text);
        }

        if ( $createRootNode )
        {
            // Creating root section with namespaces definitions
            $this->Document = new $this->DOMDocumentClass( '', true );
            $mainSection =& $this->Document->createElement( 'section' );
            $this->Document->appendChild( $mainSection );
            foreach( $this->Namespaces as $prefix => $value )
            {
                $mainSection->setAttributeNS( 'http://www.w3.org/2000/xmlns/', 'xmlns:' . $prefix, $value );
            }
        }

        // Perform pass 1
        // Parsing the source string
        $this->performPass1( $text );

        if ( $this->quitIfInvalid && !$this->isInputValid )
            return false;

        // Perform pass 2
        $this->performPass2();

        if ( $this->quitIfInvalid && !$this->isInputValid )
            return false;

        // Call publish handlers for newly created elements
        $this->processNewElements();

        return $this->Document;
    }

    /*
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
                if ( $this->quitIfInvalid && !$this->isInputValid )
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
            if ( $this->quitIfInvalid && !$this->isInputValid )
            {
                $ret = false;
                break;
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

        if ( $this->parseLineBreaks )
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

                $this->isInputValid = false;
                if ( $this->errorLevel >= 2 )
                    $this->Messages[] = ezi18n( 'kernel/classes/datatypes/ezxmltext', 'Wrong closing tag' );
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

            $this->isInputValid = false;
            if ( $this->errorLevel >= 2 )
                $this->Messages[] = ezi18n( 'kernel/classes/datatypes/ezxmltext', 'Wrong closing tag : &lt;/%1&gt;.', false, array( $closedTagName ) );

            return false;
        }
        // Insert <br/> instead of linebreaks
        elseif ( $this->parseLineBreaks && $data[$tagBeginPos] == "\n" )
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

                $this->isInputValid = false;
                if ( $this->errorLevel >= 2 )
                    $this->Messages[] = ezi18n( 'kernel/classes/datatypes/ezxmltext', 'Wrong opening tag' );
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
                    $this->isInputValid = false;
                    if ( $this->errorLevel >= 2 )
                        $this->Messages[] = ezi18n( 'kernel/classes/datatypes/ezxmltext', "Unknown tag: &lt;%1&gt;.", false, array( $tagName ) );

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
                if ( $newTagName === false )
                {
                    $this->isInputValid = false;
                    if ( $this->errorLevel >= 2 )
                        $this->Messages[] = ezi18n( 'kernel/classes/datatypes/ezxmltext', "Can't convert tag's name: &lt;%1&gt;.", false, array( $tagName ) );
                }
                return false;
                // TODO: return it before and don't append to ParentStack?
                // (need to skip processing closing tag on empty tagname)
                // LATER: no.. this is not very good for data consistance
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

        if ( $this->quitIfInvalid && !$this->isInputValid )
            return false;

        // Process children
        if ( !$noChildren )
        {
            do
            {
                $parseResult = $this->parseTag( $data, $pos, $element );

                if ( $this->quitIfInvalid && !$this->isInputValid )
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
                    $this->isInputValid = false;
                    if ( $this->errorLevel >= 2 )
                        $this->Messages[] = ezi18n( 'kernel/classes/datatypes/ezxmltext', "Class '%1' is not allowed for element &lt;%2&gt; (check content.ini).", false, array( $value, $element->nodeName ) );
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
                    $this->isInputValid = false;
                    if ( $this->errorLevel >= 2 )
                        $this->Messages[] = ezi18n( 'kernel/classes/datatypes/ezxmltext', "Required attribute '%1' is not presented in tag &lt;%2&gt;.",
                                                    false, array( $reqAttrName, $element->nodeName ) );
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


    /*
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
            for( $i = 0; $i < $childrenCount; $i++ )
            {
                $result =& $this->processSubtree( $children[$i], $lastResult );
                $lastResult =& $result;

                if ( $this->quitIfInvalid && !$this->isInputValid )
                {
                    return $ret;
                }
            }
        }

        // Call "Structure handler"
        $ret =& $this->callOutputHandler( 'structHandler', $element, $lastHandlerResult );

        // Process by schema and fix tree
        if ( !$this->processElementBySchema( $element ) )
        {
            return $ret;
        }
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

    // Check element's schema and fix subtree if needed
    function processElementBySchema( &$element, $verbose = true )
    {
        $parent =& $element->parentNode;
        if ( $parent )
        {
            // If this is a foreign element, remove it
            if ( !$this->XMLSchema->exists( $element ) )
            {
                if ( $element->nodeName == 'custom' )
                {
                    $this->isInputValid = false;
                    $this->Messages[] = ezi18n( 'kernel/classes/datatypes/ezxmltext', "Custom tag '%1' is not allowed.",
                                                false, array( $element->getAttribute( 'name' ) ) );
                }
                $parent->removeChild( $element );
                return false;
            }

            // Delete if children required and no children
            if ( ( $this->XMLSchema->childrenRequired( $element ) || $element->getAttribute( 'children_required' ) )
                 && !$element->hasChildNodes() )
            {
                $parent->removeChild( $element );
                return false;
            }

            // Check schema and remove wrong elements
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

                    $this->isInputValid = false;
                    if ( $verbose && $this->errorLevel >= 1 )
                    {
                        $elementName = $element->nodeName == '#text' ? $element->nodeName : '&lt;' . $element->nodeName . '&gt;';
                        $this->Messages[] = ezi18n( 'kernel/classes/datatypes/ezxmltext', "%1 is not allowed to be a child of &lt;%2&gt;.",
                                                    false, array( $elementName, $parent->nodeName ) );
                    }
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
                $tmpResult = null;
                $child =& $element->Children[$child_key];

                $element->removeChild( $child );
                // php5 TODO: use child returned by insertBefore (php dom manual)
                $mainParent->insertBefore( $child, $mainChild );
                $tmpResult =& $this->callOutputHandler( 'structHandler', $child, $tmpResult );

                if ( !$tmpResult && !$this->XMLSchema->check( $mainParent, $child ) )
                    $this->fixSubtree( $child, $mainChild );
            }
        }
        $parent->removeChild( $element );
    }

    function processAttributesBySchema( &$element, $verbose = true )
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
                $this->isInputValid = false;
                if ( $verbose && $this->errorLevel >= 1 )
                {
                    $this->Messages[] = ezi18n( 'kernel/classes/datatypes/ezxmltext', "Attribute '%1' is not allowed in &lt;%2&gt; element.",
                                                        false, array( $fullName, $element->nodeName ) );
                }
            }
            elseif ( $this->removeDefaultAttrs ) 
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
    // Use this function if "Publish handler" should be called for a newly created element.
    function &createAndPublishElement( $elementName )
    {
        $element =& $this->Document->createElement( $elementName );
        $this->createdElements[] =& $element;
        return $element;
    }

    function processNewElements()
    {
        // Call publish handlers for newly created elements
        foreach( array_keys( $this->createdElements ) as $key )
        {
            $element =& $this->createdElements[$key];

            if ( !$this->processElementBySchema( $element ) )
                continue;

            $tmp = null;
            // Call "Publish handler"
            $this->callOutputHandler( 'publishHandler', $element, $tmp );
        }
    }

    function getMessages()
    {
        return $this->Messages;
    }

    function isValid()
    {
        return $this->isInputValid;
    }

    var $DOMDocumentClass = 'eZDOMDocument';

    var $XMLSchema;
    var $Document;
    var $Messages = array();
    var $eZPublishVersion;

    var $ParentStack = array();

    var $errorLevel = 0;

    var $isInputValid = true;
    var $quitIfInvalid = false;

    // options that depend on settings
    var $TrimSpaces = true;
    var $AllowMultipleSpaces = false;
    var $AllowNumericEntities = false;

    // options that depend on parameters passed
    var $parseLineBreaks = false;
    var $removeDefaultAttrs = false;

    var $createdElements = array();
}
?>
