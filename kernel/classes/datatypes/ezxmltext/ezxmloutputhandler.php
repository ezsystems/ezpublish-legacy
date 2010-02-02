<?php
//
// Definition of eZXMLOutputHandler class
//
// Created on: <06-Nov-2002 15:10:02 wy>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

/*! \file
*/

/*!
  \class eZXMLOutputHandler ezxmloutputhandler
  \ingroup eZDatatype
  \brief The class eZXMLOutputHandler does

*/

// if ( !class_exists( 'eZXMLSchema' ) )
class eZXMLOutputHandler
{
    /*!
     Constructor
    */
    function eZXMLOutputHandler( $xmlData, $aliasedType, $contentObjectAttribute = null )
    {
        $this->XMLData = $xmlData;
        $this->AliasedHandler = null;
        // use of $aliasedType is deprecated as of 4.1 and setting is ignored in aliased_handler
        $this->AliasedType = $aliasedType;

        if ( is_object( $contentObjectAttribute ) )
        {
            $this->ContentObjectAttribute = $contentObjectAttribute;
            $this->ObjectAttributeID = $contentObjectAttribute->attribute( 'id' );
        }

        $ini = eZINI::instance( 'ezxml.ini' );
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
    }

    /*!
     \return an array with attribute names.
    */
    function attributes()
    {
        return array( 'output_text',
                      'aliased_type',
                      'aliased_handler',
                      'view_template_name' );
    }

    /*!
     \return true if the attribute \a $name exists.
    */
    function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

    /*!
     \return the value of the attribute \a $name if it exists, if not returns \c null.
    */
    function attribute( $name )
    {
        switch ( $name )
        {
            case 'output_text':
            {
                return $this->outputText();
            } break;
            case 'aliased_type':
            {
                eZDebug::writeWarning( "'aliased_type' is deprecated as of 4.1 and not in use anymore, meaning it will always return false.", __METHOD__ );
                return $this->AliasedType;
            } break;
            case 'view_template_name':
            {
                return $this->viewTemplateName();
            } break;
            case 'aliased_handler':
            {
                if ( $this->AliasHandler === null )
                {
                    $this->AliasedHandler = eZXMLText::inputHandler( $this->XMLData,
                                                                      $this->AliasedType,
                                                                      false,
                                                                      $this->ContentObjectAttribute );
                }
                return $this->AliasedHandler;
            } break;
            default:
            {
                eZDebug::writeError( "Attribute '$name' does not exist", 'eZXMLOutputHandler::attribute' );
                return null;
            } break;
        }
    }

    /*!
     \return the template name for this input handler, includes the edit suffix if any.
    */
    function &viewTemplateName()
    {
        $name = 'ezxmltext';
        $suffix = $this->viewTemplateSuffix();
        if ( $suffix !== false )
        {
            $name .= '_' . $suffix;
        }
        return $name;
    }

    /*!
     \virtual
     \return true if the output handler is considered valid, if not the handler will not be used.
     \note Default returns true
    */
    function isValid()
    {
        return true;
    }

    /*!
     \pure
     \return the suffix for the attribute template, if false it is ignored.
    */
    function &viewTemplateSuffix( &$contentobjectAttribute )
    {
        $suffix = false;
        return $suffix;
    }

    /*!
     \return the xml data as text.
    */
    function xmlData()
    {
        return $this->XMLData;
    }

    /*!
     Returns the output text representation of the XML structure
     Default implementation uses default mechanism of rules and tag handlers to render tags.
     */
    function &outputText()
    {
        if ( !$this->XMLData )
        {
            $output = '';
            return $output;
        }

        $this->Tpl = templateInit();
        $this->Res = eZTemplateDesignResource::instance();
        if ( $this->ContentObjectAttribute )
        {
            $this->Res->setKeys( array( array( 'attribute_identifier', $this->ContentObjectAttribute->attribute( 'contentclass_attribute_identifier' ) ) ) );
        }

        $this->Document = new DOMDocument( '1.0', 'utf-8' );
        $success = $this->Document->loadXML( $this->XMLData );

        if ( !$success )
        {
            $this->Output = '';
            return $this->Output;
        }

        $this->prefetch();

        $this->XMLSchema = eZXMLSchema::instance();

        // Add missing elements to the OutputTags array
        foreach( $this->XMLSchema->availableElements() as $element )
        {
            if ( !isset( $this->OutputTags[$element] ) )
            {
                 $this->OutputTags[$element] = array();
             }
        }

        $this->NestingLevel = 0;
        $params = array();

        $output = $this->outputTag( $this->Document->documentElement, $params );
        $this->Output = $output[1];

        unset( $this->Document );

        $this->Res->removeKey( 'attribute_identifier' );
        return $this->Output;
    }

    // Prefetch objects, nodes and urls for further rendering
    function prefetch()
    {
        $relatedObjectIDArray = array();
        $nodeIDArray = array();

        // Fetch all links and cache urls
        $linkIDArray = $this->getAttributeValueArray( 'link', 'url_id' );
        if ( count( $linkIDArray ) > 0 )
        {
            $inIDSQL = implode( ', ', $linkIDArray );

            $db = eZDB::instance();
            $linkArray = $db->arrayQuery( "SELECT * FROM ezurl WHERE id IN ( $inIDSQL ) " );

            foreach ( $linkArray as $linkRow )
            {
                $url = str_replace( '&', '&amp;', $linkRow['url'] );
                $this->LinkArray[$linkRow['id']] = $url;
            }
        }

        $linkRelatedObjectIDArray = $this->getAttributeValueArray( 'link', 'object_id' );
        $linkNodeIDArray = $this->getAttributeValueArray( 'link', 'node_id' );

        // Fetch all embeded objects and cache by ID
        $objectRelatedObjectIDArray = $this->getAttributeValueArray( 'object', 'id' );

        $embedRelatedObjectIDArray = $this->getAttributeValueArray( 'embed', 'object_id' );
        $embedInlineRelatedObjectIDArray = $this->getAttributeValueArray( 'embed-inline', 'object_id' );

        $embedNodeIDArray = $this->getAttributeValueArray( 'embed', 'node_id' );
        $embedInlineNodeIDArray = $this->getAttributeValueArray( 'embed-inline', 'node_id' );

        $relatedObjectIDArray = array_merge(
            $linkRelatedObjectIDArray,
            $objectRelatedObjectIDArray,
            $embedRelatedObjectIDArray,
            $embedInlineRelatedObjectIDArray );
        $relatedObjectIDArray = array_unique( $relatedObjectIDArray, SORT_STRING );

        if ( count( $relatedObjectIDArray ) > 0 )
        {
            $this->ObjectArray = eZContentObject::fetchIDArray( $relatedObjectIDArray );
        }

        $nodeIDArray = array_merge(
            $linkNodeIDArray,
            $embedNodeIDArray,
            $embedInlineNodeIDArray
        );
        $nodeIDArray = array_unique( $nodeIDArray, SORT_STRING );

        if ( count( $nodeIDArray ) > 0 )
        {
            $nodes = eZContentObjectTreeNode::fetch( $nodeIDArray );

            if ( is_array( $nodes ) )
            {
                foreach( $nodes as $node )
                {
                    $nodeID = $node->attribute( 'node_id' );
                    $this->NodeArray["$nodeID"] = $node;
                }
            }
            elseif ( $nodes )
            {
                $node = $nodes;
                $nodeID = $node->attribute( 'node_id' );
                $this->NodeArray["$nodeID"] = $node;
            }
        }
    }

    function getAttributeValueArray( $tagName, $attributeName )
    {
        $attributeValueArray = array();
        $elements = $this->Document->getElementsByTagName( $tagName );
        foreach ( $elements as $element )
        {
            $attributeValue = $element->getAttribute( $attributeName );
            if ( $attributeValue )
            {
                $attributeValueArray[] = $attributeValue;
            }
        }
        return $attributeValueArray;
    }

    // Main recursive functions for rendering tags
    //  $element        - current element
    //  $siblingParams - array of parameters that are passed by reference to all the children of the
    //                    current tag to provide a way to "communicate" between their handlers.
    //                    This array is empty for the first child.
    //  $parentParams   - parameter passed to this tag handler by the parent tag's handler.
    //                    This array is passed with no reference. Can by modified in tag's handler
    //                    for subordinate tags.

    function outputTag( $element, &$siblingParams, $parentParams = array() )
    {
        $tagName = $element->localName;
        if ( isset( $this->OutputTags[$tagName] ) )
        {
            $currentTag = $this->OutputTags[$tagName];
        }
        else
        {
            $currentTag = null;
        }

        // Prepare attributes array
        $attributes = array();
        if ( $element->hasAttributes() )
        {
            $attributeNodes = $element->attributes;

            foreach ( $attributeNodes as $attrNode )
            {
                if ( $attrNode->prefix && $attrNode->prefix != 'custom' )
                {
                    $attrName = $attrNode->prefix . ':' . $attrNode->localName;
                }
                else
                {
                    $attrName = $attrNode->nodeName;
                }

                // classes check
                if ( $attrName == 'class' )
                {
                    $classesList = $this->XMLSchema->getClassesList( $tagName );
                    if ( !in_array( $attrNode->value, $classesList ) )
                    {
                        eZDebug::writeWarning( "Using tag '$tagName' with class '$attrNode->value' is not allowed.", 'XML output handler' );
                        return array( true, '' );
                    }
                }

                $attributes[$attrName] = $attrNode->value;
            }
        }

        // Set default attribute values if not present in the input
        $attrDefaults = $this->XMLSchema->attrDefaultValues( $tagName );
        foreach ( $attrDefaults as $name=>$value )
        {
            if ( !isset( $attributes[$name] ) )
            {
                $attributes[$name] = $value;
            }
        }

        // Init handler returns an array that may contain the following items:
        //
        // 'no_render'       (boolean) :
        //                   If false tag will not be rendered, only it's children (if any).
        // 'design_keys'     array( 'design_key_name_1' => 'value_1', 'design_key_name_2'=>'value_2', ... ) :
        //                   An array of additional design keys.
        // 'tpl_vars'        array( 'var_name_1' => 'value_1', 'var_name_2' => 'value_2', ... ) :
        //                   An array of additional template variables.
        // 'template_name'   (string) :
        //                   Overrides tag template name.

        $result = $this->callTagInitHandler( 'initHandler', $element, $attributes, $siblingParams, $parentParams );

        // Process children
        $childrenOutput = array();
        if ( $element->hasChildNodes() )
        {
            // Initialize sibiling parameters array for the next level children
            // Parent parameters for the children may be modified in the current tag handler.
            $nextSibilingParams = array();

            $this->NestingLevel++;
            foreach( $element->childNodes as $child )
            {
                $childOutput = $this->outputTag( $child, $nextSibilingParams, $parentParams );

                if ( is_array( $childOutput[0] ) )
                {
                    $childrenOutput = array_merge( $childrenOutput, $childOutput );
                }
                else
                {
                    $childrenOutput[] = $childOutput;
                }
            }
            $this->NestingLevel--;
        }
        else
        {
            $childrenOutput = array( array( true, '' ) );
        }

        if ( isset( $result['no_render'] ) && $result['no_render'] )
        {
            return $childrenOutput;
        }

        // Set tpl variables by attributes and rename rules
        $vars = array();

        if ( !isset( $currentTag['quickRender'] ) && isset( $currentTag['attrNamesTemplate'] ) )
        {
            $attrRenameRules = $currentTag['attrNamesTemplate'];
        }
        elseif ( isset( $currentTag['quickRender'] ) && isset( $currentTag['attrNamesQuick'] ) )
        {
            $attrRenameRules = $currentTag['attrNamesQuick'];
        }
        else
        {
            $attrRenameRules = array();
        }

        foreach( $attributes as $name=>$value )
        {
            if ( isset( $attrRenameRules[$name] ) )
            {
                $vars[$attrRenameRules[$name]] = $value;
                continue;
            }

            if ( strpos( $name, 'custom:' ) === 0 )
            {
                $name = substr( $name, 7 );
            }

            $vars[$name] = $value;
        }

        // set missing variables that have rename rules defined
        // but were not present in the element
        foreach( $attrRenameRules as $attrName=>$varName )
        {
            if ( !isset( $attributes[$attrName] ) )
            {
                $vars[$varName] = '';
            }
        }

        $this->TemplateUri = '';

        // In quick render mode we does not use templates and
        // render template variables as tag attributes
        if ( !isset( $currentTag['quickRender'] ) )
        {
            // Set additional variables passed by tag handler
            if ( isset( $result['tpl_vars'] ) )
            {
                $vars = array_merge( $vars, $result['tpl_vars'] );
            }

            foreach( $vars as $name=>$value )
            {
                $this->Tpl->setVariable( $name, $value, 'xmltagns' );
            }

            // Create design keys array (including the ones with no value so they still overwrite values of parent tag)
            $designKeys = array();
            if ( isset( $currentTag['attrDesignKeys'] ) )
            {
                foreach( $currentTag['attrDesignKeys'] as $attrName=>$keyName )
                {
                    if ( isset( $attributes[$attrName] ) )
                    {
                        $designKeys[$keyName] = $attributes[$attrName];
                    }
                }
            }
            // Set additional design keys passed by tag handler
            if ( isset( $result['design_keys'] ) )
            {
                $designKeys = array_merge( $designKeys, $result['design_keys'] );
            }

            $existingKeys = $this->Res->keys();
            $savedKeys = array();

            // Save old keys values and set new design keys
            foreach( $designKeys as $key=>$value )
            {
                if ( isset( $existingKeys[$key] ) )
                {
                    $savedKeys[$key] = $existingKeys[$key];
                }
                $this->Res->setKeys( array( array( $key, $value ) ) );
            }

            // Template name
            if ( isset( $result['template_name'] ) )
            {
                $templateName = $result['template_name'];
            }
            else
            {
                $templateName = $element->nodeName;
            }
            $this->TemplateUri = $this->TemplatesPath . $templateName . '.tpl';
        }

        $output = $this->callTagRenderHandler( 'renderHandler', $element, $childrenOutput, $vars );

        if ( !isset( $currentTag['quickRender'] ) )
        {
            // Restore saved template override keys and remove others
            foreach( $designKeys as $key => $value )
            {
                if ( isset( $savedKeys[$key] ) )
                {
                    $this->Res->setKeys( array( array( $key, $savedKeys[$key] ) ) );
                }
                else
                {
                    $this->Res->removeKey( $key );
                }
            }

            // Unset variables
            foreach ( $vars as $name=>$value )
            {
                if ( $this->Tpl->hasVariable( $name, 'xmltagns' ) )
                {
                    $this->Tpl->unsetVariable( $name, 'xmltagns' );
                }
            }
        }

        return $output;
    }

    function renderTag( $element, $content, $vars )
    {
        $currentTag = $this->OutputTags[$element->nodeName];
        if ( $currentTag && isset( $currentTag['quickRender'] ) )
        {
            $renderedTag = '';
            $attrString = '';
            foreach( $vars as $name => $value )
            {
                if ( $value != '' )
                {
                    $attrString .= " $name=\"$value\"";
                }
            }

            if ( isset( $currentTag['quickRender'][0] ) && $currentTag['quickRender'][0] )
            {
                $renderedTag = '<' . $currentTag['quickRender'][0] . "$attrString>" . $content . '</' . $currentTag['quickRender'][0] . '>';
            }
            else
            {
                $renderedTag = $content;
            }

            if ( isset( $currentTag['quickRender'][1] ) && $currentTag['quickRender'][1] )
            {
                $renderedTag .= $currentTag['quickRender'][1];
            }
        }
        else
        {
            if ( isset( $currentTag['contentVarName'] ) )
            {
                $contentVarName = $currentTag['contentVarName'];
            }
            else
            {
                $contentVarName = 'content';
            }

            $this->Tpl->setVariable( $contentVarName, $content, 'xmltagns' );
            eZTemplateIncludeFunction::handleInclude( $textElements, $this->TemplateUri, $this->Tpl, 'foo', 'xmltagns' );
            $renderedTag = is_array( $textElements ) ? implode( '', $textElements ) : '';
        }
        return $renderedTag;
    }

    // Default render handler
    // Renders all the content of children tags inside the current tag
    function renderAll( $element, $childrenOutput, $vars )
    {
        $tagText = '';
        foreach( $childrenOutput as $childOutput )
        {
            $tagText .= $childOutput[1];
        }
        $tagText = $this->renderTag( $element, $tagText, $vars );
        return array( false, $tagText );
    }

    function callTagInitHandler( $handlerName, $element, &$attributes, &$siblingParams, &$parentParams )
    {
        $result = array();
        $thisOutputTag = $this->OutputTags[$element->nodeName];
        if ( isset( $thisOutputTag[$handlerName] ) )
        {
            if ( is_callable( array( $this, $thisOutputTag[$handlerName] ) ) )
            {
                $result = call_user_func_array( array( $this, $thisOutputTag[$handlerName] ),
                                                array( $element, &$attributes, &$siblingParams, &$parentParams ) );
            }
        }
        return $result;
    }

    function callTagRenderHandler( $handlerName, $element, $childrenOutput, $vars )
    {
        $result = array();
        $thisOutputTag = $this->OutputTags[$element->nodeName];
        if ( isset( $thisOutputTag[$handlerName] ) )
        {
            $handlerFunction = $thisOutputTag[$handlerName];
        }
        else
        {
            $handlerFunction = 'renderAll';
        }

        if ( is_callable( array( $this, $handlerFunction ) ) )
        {
            $result = call_user_func_array( array( $this, $handlerFunction ),
                                            array( $element, $childrenOutput, $vars ) );
        }
        else
        {
            eZDebug::writeWarning( "'$handlerName' render handler for tag <$element->nodeName> doesn't exist: '" . $thisOutputTag[$handlerName] . "'.", 'eZXML converter' );
        }
        return $result;
    }

    // This array should be overriden in derived class with the set of rules
    // for outputting tags.
    public $OutputTags = array();

    // Path to tags' templates
    public $TemplatesPath = 'design:content/datatype/view/ezxmltags/';

    /// Contains the XML data as text
    public $XMLData;
    public $Document;

    public $XMLSchema;

    public $AliasedType;
    public $AliasedHandler;

    public $Output = '';
    public $Tpl;
    public $TemplateURI = '';
    public $Res;

    public $AllowMultipleSpaces = false;
    public $AllowNumericEntities = false;

    public $ContentObjectAttribute;
    public $ObjectAttributeID;

    /// Contains the URL's for <link> tags hashed by ID
    public $LinkArray = array();
    /// Contains the Objects hashed by ID
    public $ObjectArray = array();
    /// Contains the Nodes hashed by ID
    public $NodeArray = array();

    public $NestingLevel = 0;
}

?>
