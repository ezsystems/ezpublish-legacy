<?php
/**
 * File containing the eZXMLInputParser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

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

    public function __construct( $validateErrorLevel = self::ERROR_NONE, $detectErrorLevel = self::ERROR_NONE, $parseLineBreaks = false,
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
        // replace unicode chars that will break the XML validity
        // see http://www.w3.org/TR/REC-xml/#charsets
        $text = preg_replace( '/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $text, -1, $count );
        if ( $count > 0 )
        {
            $this->Messages[] = ezpI18n::tr(
                'kernel/classes/datatypes/ezxmltext',
                "%count invalid character(s) have been found and replaced by a space",
                false,
                array( '%count' => $count )
            );
        }
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
        $debug = eZDebugSetting::isConditionTrue( 'kernel-datatype-ezxmltext', eZDebug::LEVEL_DEBUG );
        if ( $debug )
        {
            eZDebug::writeDebug( $this->Document->saveXML(), eZDebugSetting::changeLabel( 'kernel-datatype-ezxmltext', 'XML after pass 1' ) );
        }

        if ( $this->QuitProcess )
        {
            return false;
        }

        // Perform pass 2
        $this->performPass2();

        //$this->Document->formatOutput = true;
        if ( $debug )
        {
            eZDebug::writeDebug( $this->Document->saveXML(), eZDebugSetting::changeLabel( 'kernel-datatype-ezxmltext', 'XML after pass 2' ) );
        }

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

                $this->handleError( self::ERROR_SYNTAX, ezpI18n::tr( 'kernel/classes/datatypes/ezxmltext', 'Wrong closing tag' ) );
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

            $this->handleError( self::ERROR_SYNTAX, ezpI18n::tr( 'kernel/classes/datatypes/ezxmltext', 'Wrong closing tag : &lt;/%1&gt;.', false, array( $closedTagName ) ) );

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
            $tagEndPos = $this->findEndOpeningTagPosition( $data, $tagBeginPos );
            if ( $tagEndPos === false )
            {
                $pos = $tagBeginPos + 1;

                $this->handleError( self::ERROR_SYNTAX, ezpI18n::tr( 'kernel/classes/datatypes/ezxmltext', 'Wrong opening tag' ) );
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
                    $this->handleError( self::ERROR_SYNTAX, ezpI18n::tr( 'kernel/classes/datatypes/ezxmltext', 'Unknown tag: &lt;%1&gt;.', false, array( $tagName ) ) );
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
                    $this->handleError( self::ERROR_SYNTAX, ezpI18n::tr( 'kernel/classes/datatypes/ezxmltext', "Can't convert tag's name: &lt;%1&gt;.", false, array( $tagName ) ) );

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

    /**
     * Finds the postion of the > character which marks the end of the opening
     * tag that starts at $tagBeginPos in $data.
     * It's not as easy as it seems, because some '>' can also appear in attributes.
     * So we need to iterate over the next '>' characters to find the correct one.
     * See https://jira.ez.no/browse/EZP-26096
     *
     * @param string $data
     * @param integer $tagBeginPos
     * @param integer $offset used for recursive call when a > is found in an attribute.
     * @return integer|false
     */
    private function findEndOpeningTagPosition( $data, $tagBeginPos, $offset = 0 )
    {
        $endPos = strpos( $data, '>', $tagBeginPos + $offset );
        if ( $endPos === false )
        {
            return false;
        }
        $tagCode = substr( $data, $tagBeginPos, $endPos - $tagBeginPos );
        if ( strpos( $tagCode, '=' ) === false )
        {
            // this tag has no attribute, so the next '>' is the right one.
            return $endPos;
        }
        if ( $this->isValidXmlTag( $tagCode ) )
        {
            return $endPos;
        }
        return $this->findEndOpeningTagPosition( $data, $tagBeginPos, $endPos - $tagBeginPos + 1 );
    }

    /**
     * Checks whether $code can be considered as a valid XML excerpt. If not,
     * it's probably because we found a '>' in the middle of an attribute.
     *
     * @param string $code
     * @return boolean
     */
    private function isValidXmlTag( $code )
    {
        if ( $code[strlen( $code ) - 1] !== '/' )
        {
            $code .= '/';
        }
        $code .= '>';
        $code = '<' . str_replace(
            array( '<', '&' ),
            array( '&lt;', '&amp;' ),
            substr( $code, 1 )
        );
        $errorHanding = libxml_use_internal_errors( true );
        $simpleXml = simplexml_load_string( $code );
        libxml_use_internal_errors( $errorHanding );
        return ( $simpleXml !== false );
    }

    function parseAttributes( $attributeString )
    {
        $attributes = array();
        // Valid characters for XML attributes
        // @see http://www.w3.org/TR/xml/#NT-Name
        $nameStartChar = ':A-Z_a-z\\xC0-\\xD6\\xD8-\\xF6\\xF8-\\x{2FF}\\x{370}-\\x{37D}\\x{37F}-\\x{1FFF}\\x{200C}-\\x{200D}\\x{2070}-\\x{218F}\\x{2C00}-\\x{2FEF}\\x{3001}-\\x{D7FF}\\x{F900}-\\x{FDCF}\\x{FDF0}-\\x{FFFD}\\x{10000}-\\x{EFFFF}';
        if (
            preg_match_all(
                "/\s+([$nameStartChar][$nameStartChar\-.0-9\\xB7\\x{0300}-\\x{036F}\\x{203F}-\\x{2040}]*)\s*=\s*(?:(?:\"([^\"]+?)\")|(?:'([^']+?)')|(?: *([^\"'\s]+)))/u",
                " " . $attributeString,
                $attributeArray,
                PREG_SET_ORDER
            )
        ) {
            foreach ( $attributeArray as $attribute )
            {
                // Value will always be at the last position
                $value = trim( array_pop( $attribute ) );
                // Value of '0' is valid ( eg. border='0' )
                if ( $value !== '' && $value !== false && $value !== null )
                {
                    $attributes[$attribute[1]] = $value;
                }
            }
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

                // Get list of classes for embed and embed inline tags
                // use specific class list this embed class type if it exists
                $contentIni      = eZINI::instance( 'content.ini' );
                list($embedType, $embedId) = explode('_', $attributes['id']);
                if( !$embedId || $embedId === '' )
                    list($embedType, $embedId) = explode('://', $attributes['href']);
                if ( $embedType === 'eZNode' || $embedType === 'eznode' )
                    $embedObject = eZContentObject::fetchByNodeID( $embedId );
                else
                    $embedObject = eZContentObject::fetch( $embedId );
                if( $embedObject instanceof eZContentObject )
                {
                    $embedClassIdentifier = $embedObject->attribute( 'class_identifier' );
                    $contentType = self::embedTagContentType( $embedClassIdentifier, $embedClassID );
                    if ( $contentIni->hasVariable( 'embed_' . $embedClassIdentifier, 'AvailableClasses' ) )
                        $classListData = $contentIni->variable( 'embed_' . $embedClassIdentifier, 'AvailableClasses' );
                    else if ( $contentIni->hasVariable( 'embed-type_' . $contentType, 'AvailableClasses' ) )
                        $classListData = $contentIni->variable( 'embed-type_' . $contentType, 'AvailableClasses' );
                    else if ( $contentIni->hasVariable( 'embed', 'AvailableClasses' ) )
                        $classListData = $contentIni->variable( 'embed', 'AvailableClasses' );

                    // For BC let's merge the list of classes just fetched for this specific object class with the whole list of classes available for generic embed
                    $classesList = array_unique( array_merge( $classesList, $classListData ) );
                }
                
                if ( !in_array( $value, $classesList ) )
                {
                    $this->handleError( self::ERROR_DATA,
                                        ezpI18n::tr( 'kernel/classes/datatypes/ezxmltext', "Class '%1' is not allowed for element &lt;%2&gt; (check content.ini).",
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
                        $element->setAttributeNS( $URI, $qualifiedName, htmlspecialchars_decode( $value ) );
                    }
                    else
                    {
                        eZDebug::writeWarning( "No namespace defined for prefix '$prefix'.", 'eZXML input parser' );
                    }
                }
                else
                {
                    $element->setAttribute( $qualifiedName,  htmlspecialchars_decode( $value ) );
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
                                        ezpI18n::tr( 'kernel/classes/datatypes/ezxmltext', "Required attribute '%1' is not presented in tag &lt;%2&gt;.",
                                        false, array( $reqAttrName, $element->nodeName ) ) );
                }
            }
        }
    }

    /*
     * Get content type by class identifier (Copied from eZOE)
     */
    public static function embedTagContentType( $classIdentifier  )
    {
        $contentIni = eZINI::instance('content.ini');

        foreach ( $contentIni->variable( 'RelationGroupSettings', 'Groups' ) as $group )
        {
            $settingName = ucfirst( $group ) . 'ClassList';
            if ( $contentIni->hasVariable( 'RelationGroupSettings', $settingName ) )
            {
                if ( in_array( $classIdentifier, $contentIni->variable( 'RelationGroupSettings', $settingName ) ) )
                    return $group;
            }
            else
                eZDebug::writeDebug( "Missing content.ini[RelationGroupSettings]$settingName setting.",
                                     __METHOD__ );
        }

        return $contentIni->variable( 'RelationGroupSettings', 'DefaultGroup' );
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
        return $text;
    }

    function convertNumericEntities( $text )
    {
        if ( strlen( $text ) < 4 )
        {
            return $text;
        }
        // Convert other HTML entities to the current charset characters.
        $convmap = array( 0x0, 0x2FFFF, 0, 0xFFFF );
        return mb_decode_numericentity( $text, $convmap, 'UTF-8' );
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

        $debug = eZDebugSetting::isConditionTrue( 'kernel-datatype-ezxmltext', eZDebug::LEVEL_DEBUG );

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
                if ( $debug )
                {
                    eZDebug::writeDebug( 'processing children, current child: ' . $child->nodeName, eZDebugSetting::changeLabel( 'kernel-datatype-ezxmltext', __METHOD__ ) );
                }

                $childReturn = $this->processSubtree( $child, $lastResult );

                unset( $lastResult );
                if ( isset( $childReturn['result'] ) )
                {
                    if ( $debug )
                    {
                        eZDebug::writeDebug( 'return result is set for child ' . $child->nodeName, eZDebugSetting::changeLabel( 'kernel-datatype-ezxmltext', __METHOD__ ) );
                    }

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

            if ( $debug )
            {
                eZDebug::writeDebug( $this->Document->saveXML(),
                                     eZDebugSetting::changeLabel( 'kernel-datatype-ezxmltext',
                                                                  'XML before processNewElements for element ' . $element->nodeName ) );
            }

            // process elements created in children handlers
            $this->processNewElements( $newElements );

            if ( $debug )
            {
                eZDebug::writeDebug( $this->Document->saveXML(),
                                     eZDebugSetting::changeLabel( 'kernel-datatype-ezxmltext',
                                                                  'XML after processNewElements for element ' . $element->nodeName ) );
            }
        }

        // Call "Structure handler"
        if ( $debug )
        {
            eZDebug::writeDebug( $this->Document->saveXML(),
                                 eZDebugSetting::changeLabel( 'kernel-datatype-ezxmltext',
                                                              'XML before callOutputHandler structHandler for element ' . $element->nodeName ) );
        }

        $ret = $this->callOutputHandler( 'structHandler', $element, $lastHandlerResult );

        if ( $debug )
        {
            eZDebug::writeDebug( $this->Document->saveXML(),
                                 eZDebugSetting::changeLabel( 'kernel-datatype-ezxmltext',
                                                              'XML after callOutputHandler structHandler for element ' . $element->nodeName ) );
            eZDebug::writeDebug( $ret,
                                 eZDebugSetting::changeLabel( 'kernel-datatype-ezxmltext',
                                                              'return value of callOutputHandler structHandler for element ' . $element->nodeName ) );
        }

        // Process by schema (check if element is allowed to exist)
        if ( !$this->processBySchemaPresence( $element ) )
        {
            if ( $debug )
            {
                eZDebug::writeDebug( $this->Document->saveXML(),
                                     eZDebugSetting::changeLabel( 'kernel-datatype-ezxmltext',
                                                                  'XML after failed processBySchemaPresence for element ' . $element->nodeName ) );
            }
            return $ret;
        }

        if ( $debug )
        {
            eZDebug::writeDebug( $this->Document->saveXML(),
                                 eZDebugSetting::changeLabel( 'kernel-datatype-ezxmltext',
                                                              'XML after processBySchemaPresence for element ' . $element->nodeName ) );
        }

        // Process by schema (check place in the tree)
        if ( !$this->processBySchemaTree( $element ) )
        {
            if ( $debug )
            {
                eZDebug::writeDebug( $this->Document->saveXML(),
                                     eZDebugSetting::changeLabel( 'kernel-datatype-ezxmltext',
                                                                  'XML after failed processBySchemaTree for element ' . $element->nodeName ) );
            }
            return $ret;
        }

        if ( $debug )
        {
            eZDebug::writeDebug( $this->Document->saveXML(),
                                 eZDebugSetting::changeLabel( 'kernel-datatype-ezxmltext',
                                                              'XML after processBySchemaTree for element ' . $element->nodeName ) );
        }


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
                                        ezpI18n::tr( 'kernel/classes/datatypes/ezxmltext', "Custom tag '%1' is not allowed.",
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
                    $this->handleError( self::ERROR_SCHEMA, ezpI18n::tr( 'kernel/classes/datatypes/ezxmltext', "&lt;%1&gt; tag can't be empty.",
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
                    $this->handleError( self::ERROR_SCHEMA, ezpI18n::tr( 'kernel/classes/datatypes/ezxmltext', "%1 is not allowed to be a child of &lt;%2&gt;.",
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
                                    ezpI18n::tr( 'kernel/classes/datatypes/ezxmltext', "Attribute '%1' is not allowed in &lt;%2&gt; element.",
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
        $debug = eZDebugSetting::isConditionTrue( 'kernel-datatype-ezxmltext', eZDebug::LEVEL_DEBUG );
        // Call handlers for newly created elements
        foreach ( $createdElements as $element )
        {
            if ( $debug )
            {
                eZDebug::writeDebug( 'processing new element ' . $element->nodeName, eZDebugSetting::changeLabel( 'kernel-datatype-ezxmltext' ) );
            }

            $tmp = null;
            if ( !$this->processBySchemaPresence( $element ) )
            {
                if ( $debug )
                {
                    eZDebug::writeDebug( $this->Document->saveXML(),
                                         eZDebugSetting::changeLabel( 'kernel-datatype-ezxmltext',
                                                                      'xml string after failed processBySchemaPresence for new element ' . $element->nodeName ) );
                }
                continue;
            }

            if ( $debug )
            {
                eZDebug::writeDebug( $this->Document->saveXML(),
                                     eZDebugSetting::changeLabel( 'kernel-datatype-ezxmltext',
                                                                  'xml string after processBySchemaPresence for new element ' . $element->nodeName ) );
            }


            // Call "Structure handler"
            $this->callOutputHandler( 'structHandler', $element, $tmp );

            if ( !$this->processBySchemaTree( $element ) )
            {
                if ( $debug )
                {
                    eZDebug::writeDebug( $this->Document->saveXML(),
                                         eZDebugSetting::changeLabel( 'kernel-datatype-ezxmltext',
                                                                      'xml string after failed processBySchemaTree for new element ' . $element->nodeName ) );
                }
                continue;
            }

            if ( $debug )
            {
                eZDebug::writeDebug( $this->Document->saveXML(),
                                     eZDebugSetting::changeLabel( 'kernel-datatype-ezxmltext',
                                                                  'xml string after processBySchemaTree for new element ' . $element->nodeName ) );
            }


            $tmp2 = null;
            // Call "Publish handler"
            $this->callOutputHandler( 'publishHandler', $element, $tmp2 );

            if ( $debug )
            {
                eZDebug::writeDebug( $this->Document->saveXML(),
                                     eZDebugSetting::changeLabel( 'kernel-datatype-ezxmltext',
                                                                  'xml string after callOutputHandler publishHandler for new element ' . $element->nodeName ) );
            }

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

    public $DOMDocumentClass = 'DOMDocument';

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
