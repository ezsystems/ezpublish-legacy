<?php
//
// Definition of eZXMLOutputHandler class
//
// Created on: <06-Nov-2002 15:10:02 wy>
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
//

/*! \file ezxmloutputhandler.php
*/

/*!
  \class eZXMLOutputHandler ezxmloutputhandler
  \ingroup eZDatatype
  \brief The class eZXMLOutputHandler does

*/

include_once( "lib/ezxml/classes/ezxml.php" );

include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );
include_once( 'lib/eztemplate/classes/eztemplateincludefunction.php' );

include_once( "lib/ezxml/classes/ezxml.php" );

if ( !class_exists( 'eZXMLSchema' ) )
    include_once( 'kernel/classes/datatypes/ezxmltext/ezxmlschema.php' );

class eZXMLOutputHandler
{
    /*!
     Constructor
    */
    function eZXMLOutputHandler( &$xmlData, $aliasedType, $contentObjectAttribute = null )
    {
        $this->XMLData =& $xmlData;
        $this->AliasedType = $aliasedType;
        $this->AliasedHandler = null;

        if ( is_object( $contentObjectAttribute ) )
        {
            $this->ContentObjectAttribute =& $contentObjectAttribute;
            $this->ObjectAttributeID = $contentObjectAttribute->attribute( 'id' );
        }

        $ini =& eZINI::instance( 'ezxml.ini' );
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
    function &attribute( $name )
    {
        switch ( $name )
        {
            case 'output_text':
            {
                $retValue =& $this->outputText();
            } break;
            case 'aliased_type':
            {
                return $this->AliasedType;
            } break;
            case 'view_template_name':
            {
                $retValue =& $this->viewTemplateName();
            } break;
            case 'aliased_handler':
            {
                if ( $this->AliasedType !== false and
                     $this->AliasHandler === null )
                {
                    $this->AliasedHandler =& eZXMLText::inputHandler( $this->XMLData,
                                                                      $this->AliasedType,
                                                                      false );
                }
                return $this->AliasedHandler;
            } break;
            default:
            {
                eZDebug::writeError( "Attribute '$name' does not exist", 'eZXMLOutputHandler::attribute' );
                $retValue = null;
            } break;
        }
        return $retValue;
    }

    /*!
     \return the template name for this input handler, includes the edit suffix if any.
    */
    function &viewTemplateName()
    {
        $name = 'ezxmltext';
        $suffix = $this->viewTemplateSuffix();
        if ( $suffix !== false )
            $name .= '_' . $suffix;
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
        $this->Tpl =& templateInit();
        $this->Res =& eZTemplateDesignResource::instance();
        if ( $this->ContentObjectAttribute )
        {
            $this->Res->setKeys( array( array( 'attribute_identifier', $this->ContentObjectAttribute->attribute( 'contentclass_attribute_identifier' ) ) ) );
        }

        $xml = new eZXML();
        $this->Document =& $xml->domTree( $this->XMLData, array( "TrimWhiteSpace" => false, "SetParentNode" => true ) );
        if ( !$this->Document )
        {
            $this->Output = '';
            return $this->Output;
        }

        $this->prefetch();

        $this->XMLSchema =& eZXMLSchema::instance();
        $this->NestingLevel = 0;
        $params = array();

        $output = $this->outputTag( $this->Document->Root, $params );
        $this->Output = $output[1];

        $this->Document->cleanup();

        unset( $this->Document );
        unset( $this->XMLData );

        $this->Res->removeKey( 'attribute_identifier' );
        return $this->Output;
    }

    // Prefetch objects, nodes and urls for further rendering
    function prefetch()
    {
        $relatedObjectIDArray = array();
        $nodeIDArray = array();

        // Fetch all links and cache urls
        $links =& $this->Document->elementsByName( "link" );

        if ( count( $links ) > 0 )
        {
            $linkIDArray = array();
            // Find all Link ids
            foreach ( $links as $link )
            {
                $linkID = $link->attributeValue( 'url_id' );
                if ( $linkID && !in_array( $linkID, $linkIDArray ) )
                        $linkIDArray[] = $linkID;

                $objectID = $link->attributeValue( 'object_id' );
                if ( $objectID && !in_array( $objectID, $relatedObjectIDArray ) )
                        $relatedObjectIDArray[] = $objectID;

                $nodeID = $link->attributeValue( 'node_id' );
                if ( $nodeID && !in_array( $nodeID, $nodeIDArray ) )
                        $nodeIDArray[] = $nodeID;
            }

            if ( count( $linkIDArray ) > 0 )
            {
                $inIDSQL = implode( ', ', $linkIDArray );

                $db =& eZDB::instance();
                $linkArray = $db->arrayQuery( "SELECT * FROM ezurl WHERE id IN ( $inIDSQL ) " );

                foreach ( $linkArray as $linkRow )
                {
                    $this->LinkArray[$linkRow['id']] = $linkRow['url'];
                }
            }
        }

        // Fetch all embeded objects and cache by ID
        $objectArray =& $this->Document->elementsByName( "object" );

        if ( count( $objectArray ) > 0 )
        {
            foreach ( $objectArray as $object )
            {
                $objectID = $object->attributeValue( 'id' );
                if ( $objectID != null && !in_array( $objectID, $relatedObjectIDArray ) )
                        $relatedObjectIDArray[] = $objectID;
            }
        }

        $embedTagArray =& $this->Document->elementsByName( "embed" );
        $embedInlineTagArray =& $this->Document->elementsByName( "embed-inline" );

        $embedTags = array_merge( $embedTagArray, $embedInlineTagArray );

        if ( count( $embedTags ) > 0 )
        {
            foreach ( $embedTags as $embedTag )
            {
                $objectID = $embedTag->attributeValue( 'object_id' );
                if ( $objectID && !in_array( $objectID, $relatedObjectIDArray ) )
                        $relatedObjectIDArray[] = $objectID;

                $nodeID = $embedTag->attributeValue( 'node_id' );
                if ( $nodeID && !in_array( $nodeID, $nodeIDArray ) )
                        $nodeIDArray[] = $nodeID;
            }
        }

        if ( $relatedObjectIDArray != null )
            $this->ObjectArray =& eZContentObject::fetchIDArray( $relatedObjectIDArray );

        if ( $nodeIDArray != null )
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
                $node =& $nodes;
                $nodeID = $node->attribute( 'node_id' );
                $this->NodeArray["$nodeID"] = $node;
            }
        }
    }

    // Main recursive functions for rendering tags
    //  $element        - current element
    //  $sibilingParams - array of parameters that are passed by reference to all the children of the
    //                    current tag to provide a way to "communicate" between their handlers.
    //                    This array is empty for the first child.
    //  $parentParams   - parameter passed to this tag handler by the parent tag's handler.
    //                    This array is passed with no reference. Can by modified in tag's handler
    //                    for subordinate tags.

    function outputTag( &$element, &$sibilingParams, $parentParams = array() )
    {
        $tagName = $element->nodeName;
        if ( isset( $this->OutputTags[$tagName] ) )
        {
            $currentTag =& $this->OutputTags[$tagName];
        }
        else
            $currentTag = null;

        // Prepare attributes array
        $attributeNodes = $element->attributes();
        $attributes = array();
        foreach( $attributeNodes as $attrNode )
        {
            if ( $attrNode->Prefix && $attrNode->Prefix != 'custom' )
                $attrName = $attrNode->Prefix . ':' . $attrNode->LocalName;
            else
                $attrName = $attrNode->nodeName;

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

        // Set default attribute values if not present in the input
        $attrDefaults = $this->XMLSchema->attrDefaultValues( $tagName );
        foreach( $attrDefaults as $name=>$value )
        {
            if ( !isset( $attributes[$name] ) )
                $attributes[$name] = $value;
        }

        // Call tag handler
        $result = $this->callTagInitHandler( 'initHandler', $element, $attributes, $sibilingParams, $parentParams );

        // Process children
        $childrenOutput = array();
        if ( $element->hasChildNodes() )
        {
            // Initialize sibiling parameters array for the next level children
            // Parent parameters for the children may be modified in the current tag handler.

            $nextSibilingParams = array();
            /*if ( isset( $result['next_parent_params'] ) )
                 $nextParentParams = array_merge( $parentParams, $result['next_parent_params'] );
            else
                 $nextParentParams = $parentParams;*/

            $this->NestingLevel++;
            foreach( array_keys( $element->Children ) as $key )
            {
                $child =& $element->Children[$key];
                $childOutput = $this->outputTag( $child, $nextSibilingParams, $parentParams );

                if ( is_array( $childOutput[0] ) )
                    $childrenOutput = array_merge( $childrenOutput, $childOutput );
                else
                    $childrenOutput[] = $childOutput;
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

        $templateUri = '';
        if ( !isset( $currentTag['quickRender'] ) )
        {
            // Set tpl variables by attributes and rename rules
            $vars = array();

            if ( isset( $currentTag['attrVariables'] ) )
                $attrVariables =& $currentTag['attrVariables'];
            else
                $attrVariables = array();

            foreach( $attributes as $name=>$value )
            {
                if ( isset( $attrVariables[$name] ) )
                {
                    $vars[$attrVariables[$name]] = $value;
                    continue;
                }

                if ( substr( $name, 0, 6 ) == 'custom:' )
                    $name = substr( $name, strpos( $name, ':' ) + 1 );

                $vars[$name] = $value;
            }

            // set missing variables that have defined rename rules
            // but were not present in the element
            foreach( $attrVariables as $attrName=>$varName )
            {
                if ( !isset( $attributes[$attrName] ) )
                    $vars[$varName] = '';
            }

            // Set additional variables passed by tag handler
            if ( isset( $result['tpl_vars'] ) )
            {
                $vars = array_merge( $vars, $result['tpl_vars'] );
            }

            foreach( $vars as $name=>$value )
            {
                $this->Tpl->setVariable( $name, $value, 'xmltagns' );
            }

            // Create design keys array
            $designKeys = array();
            if ( isset( $currentTag['attrDesignKeys'] ) )
            {
                foreach( $currentTag['attrDesignKeys'] as $attrName=>$keyName )
                {
                    if ( isset( $attributes[$attrName] ) && $attributes[$attrName] )
                        $designKeys[$keyName] = $attributes[$attrName];
                }
            }
            // Merge design keys set in control array and tag handler
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
            $templateUri = $this->TemplatesPath . $templateName . '.tpl';
        }

        $output = $this->callTagRenderHandler( 'renderHandler', $element, $templateUri, $childrenOutput, $attributes );
        //$handlerName = $currentTag['renderHandler'];
        //$output = $this->$handlerName( $element, $templateUri, $childrenOutput, $attributes );

        if ( !isset( $currentTag['quickRender'] ) )
        {
            // Restore saved template override keys and remove others
            foreach( $designKeys as $key=>$value )
            {
                if ( isset( $savedKeys[$key] ) )
                    $this->Res->setKeys( array( array( $key, $savedKeys[$key] ) ) );
                else
                    $this->Res->removeKey( $key );
            }

            // Unset variables
            foreach( $vars as $name=>$value )
            {
                if ( $this->Tpl->hasVariable( $name, 'xmltagns' ) )
                    $this->Tpl->unsetVariable( $name, 'xmltagns' );
            }
        }

        return $output;
    }

    function renderTag( &$element, $templateUri, $content, $attributes )
    {
        $currentTag =& $this->OutputTags[$element->nodeName];
        if ( $currentTag && isset( $currentTag['quickRender'] ) )
        {
            $renderedTag = '';
            $attrString = '';
            foreach( $attributes as $name=>$value )
            {
                if ( $value != '' )
                    $attrString .= " $name=\"$value\"";
            }

            if ( $currentTag['quickRender'][0] )
                $renderedTag = '<' . $currentTag['quickRender'][0] . "$attrString>" . $content . '</' . $currentTag['quickRender'][0] . '>';
            else
                $renderedTag = $content;

            if ( $currentTag['quickRender'][1] )
                $renderedTag .= $currentTag['quickRender'][1];
        }
        else
        {
            if ( isset( $currentTag['contentVarName'] ) )
                $contentVarName = $currentTag['contentVarName'];
            else
                $contentVarName = 'content';

            $this->Tpl->setVariable( $contentVarName, $content, 'xmltagns' );
            eZTemplateIncludeFunction::handleInclude( $textElements, $templateUri, $this->Tpl, 'foo', 'xmltagns' );
            $renderedTag = is_array( $textElements ) ? implode( '', $textElements ) : '';
        }
        return $renderedTag;
    }

    // Handler returns an array that may have following items:
    //
    // 'skip_tag_render' (boolean) :
    //                   if false tag will not be rendered, only it's children (if any).
    // 'design_keys'     array( 'design_key_name'=>'value' ) :
    //                   an array of additional design keys
    //

    function callTagInitHandler( $handlerName, &$element, &$attributes, &$sibilingParams, &$parentParams )
    {
        $result = array();
        $thisOutputTag =& $this->OutputTags[$element->nodeName];
        if ( isset( $thisOutputTag[$handlerName] ) )
        {
            if ( is_callable( array( $this, $thisOutputTag[$handlerName] ) ) )
                eval( '$result = $this->' . $thisOutputTag[$handlerName] . '( $element, $attributes, $sibilingParams, $parentParams );' );
            //else
            //    eZDebug::writeWarning( "'$handlerName' output handler for tag <$element->nodeName> doesn't exist: '" . $thisOutputTag[$handlerName] . "'.", 'eZXML converter' );
        }
        return $result;
    }

    function callTagRenderHandler( $handlerName, &$element, $templateUri, $childrenOutput, $attributes )
    {
        $result = array();
        $thisOutputTag =& $this->OutputTags[$element->nodeName];
        if ( isset( $thisOutputTag[$handlerName] ) )
        {
            if ( is_callable( array( $this, $thisOutputTag[$handlerName] ) ) )
                eval( '$result = $this->' . $thisOutputTag[$handlerName] . '( $element, $templateUri, $childrenOutput, $attributes );' );
            else
                eZDebug::writeWarning( "'$handlerName' render handler for tag <$element->nodeName> doesn't exist: '" . $thisOutputTag[$handlerName] . "'.", 'eZXML converter' );
        }
        return $result;
    }

    // This array should be overriden in derived class with the set of rules
    // for outputting tags.
    var $OutputTags = array();

    // Path to tags' templates
    var $TemplatesPath = 'design:content/datatype/view/ezxmltags/';

    /// Contains the XML data as text
    var $XMLData;
    var $Document;

    var $XMLSchema;

    var $AliasedType;
    var $AliasedHandler;

    var $Output = '';
    var $Tpl;
    var $Res;

    var $AllowMultipleSpaces = false;
    var $AllowNumericEntities = false;

    var $ContentObjectAttribute;
    var $ObjectAttributeID;

    /// Contains the URL's for <link> tags hashed by ID
    var $LinkArray = array();
    /// Contains the Objects hashed by ID
    var $ObjectArray = array();
    /// Contains the Nodes hashed by ID
    var $NodeArray = array();

    var $NestingLevel = 0;
}

?>
