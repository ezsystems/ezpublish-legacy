<?php
//
// Definition of eZSimplifiedXMLInput class
//
// Created on: <28-Jan-2003 13:28:39 bf>
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

include_once( 'kernel/classes/datatypes/ezxmltext/ezxmlinputhandler.php' );
include_once( 'kernel/classes/datatypes/ezurl/ezurlobjectlink.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'lib/ezutils/classes/ezini.php' );

class eZSimplifiedXMLInput extends eZXMLInputHandler
{
    function eZSimplifiedXMLInput( &$xmlData, $aliasedType, $contentObjectAttribute )
    {
        // Initialize size array for image.
        /*$imageIni =& eZINI::instance( 'image.ini' );
        if ( $imageIni->hasVariable( 'AliasSettings', 'AliasList' ) )
        {
            $sizeArray = $imageIni->variable( 'AliasSettings', 'AliasList' );
            $sizeArray[] = 'original';
        }
        else
            $sizeArray = array( 'original' );
        */

        $this->eZXMLInputHandler( $xmlData, $aliasedType, $contentObjectAttribute );

        $this->IsInputValid = true;
        $this->ContentObjectAttribute = $contentObjectAttribute;

        $contentIni =& eZINI::instance( 'content.ini' );
        
        /*
        if ( $contentIni->hasVariable( 'header', 'UseStrictHeaderRule' ) )
        {
            if ( $contentIni->variable( 'header', 'UseStrictHeaderRule' ) == "true" )
                $this->IsStrictHeader = true;
        }
        */
    }

    /*!
      Updates related objects list.
    */
    function updateRelatedObjectsList( $contentObjectAttribute, $relatedObjectIDArray )
    {
        $contentObjectID = $contentObjectAttribute->attribute( "contentobject_id" );
        $editVersion = $contentObjectAttribute->attribute('version');
        $editObject =& eZContentObject::fetch( $contentObjectID );

        foreach ( $relatedObjectIDArray as $relatedObjectID )
        {
            $editObject->addContentObjectRelation( $relatedObjectID, $editVersion );
        }
    }

    /*!
      Updates URL - object links.
    */
    function updateUrlObjectLinks( $contentObjectAttribute, $urlIDArray )
    {
        $objectAttributeID = $contentObjectAttribute->attribute( "id" );
        $objectAttributeVersion = $contentObjectAttribute->attribute('version');

        foreach( $urlIDArray as $urlID )
        {
            $linkObjectLink = eZURLObjectLink::fetch( $urlID, $objectAttributeID, $objectAttributeVersion );
            if ( $linkObjectLink == null )
            {
                $linkObjectLink = eZURLObjectLink::create( $urlID, $objectAttributeID, $objectAttributeVersion );
                $linkObjectLink->store();
            }
        }
    }

    /*!
     \reimp
     Validates the input and returns true if the input was valid for this datatype.
    */
    function validateInput( &$http, $base, &$contentObjectAttribute )
    {
        $contentObjectID = $contentObjectAttribute->attribute( "contentobject_id" );
        $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
        $contentObjectAttributeVersion = $contentObjectAttribute->attribute('version');
        if ( $http->hasPostVariable( $base . "_data_text_" . $contentObjectAttributeID ) )
        {
            $data = $http->postVariable( $base . "_data_text_" . $contentObjectAttributeID );

            // Set original input to a global variable
            $originalInput = "originalInput_" . $contentObjectAttributeID;
            $GLOBALS[$originalInput] = $data;

            // Set input valid true to a global variable
            $isInputValid = "isInputValid_" . $contentObjectAttributeID;
            $GLOBALS[$isInputValid] = true;

            include_once( 'kernel/classes/datatypes/ezxmltext/handlers/input/ezsimplifiedxmlinputparser.php' );

            $text = $data;

            $text = preg_replace('/\n/', '<br/>', $text);
            $text = preg_replace('/\r/', '', $text);
            $text = preg_replace('/\t/', ' ', $text);

            $parser = new eZSimplifiedXMLInputParser( $contentObjectID );
            $document = $parser->process( $text );

            if ( !is_object( $document ) )
            {
                $GLOBALS[$isInputValid] = false;
                $errorMessage = implode( ' ', $parser->getMessages() );
                $contentObjectAttribute->setValidationError( $errorMessage );
                return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }

            $xmlString = eZXMLTextType::domString( $document );

            //eZDebug::writeDebug( $xmlString, '$xmlString' );

            $relatedObjectIDArray = $parser->getRelatedObjectIDArray();
            $urlIDArray = $parser->getUrlIDArray();
            
            if ( count( $urlIDArray ) > 0 )
            {
                $this->updateUrlObjectLinks( $contentObjectAttribute, $urlIDArray );
            }

            if ( count( $relatedObjectIDArray ) > 0 )
            {
                $this->updateRelatedObjectsList( $contentObjectAttribute, $relatedObjectIDArray );
            }

            $classAttribute =& $contentObjectAttribute->contentClassAttribute();
            if ( $classAttribute->attribute( "is_required" ) == true )
            {
                $root =& $document->Root;
                if ( !count( $root->Children ) )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'Content required' ) );
                    return EZ_INPUT_VALIDATOR_STATE_INVALID;
                }
            }
            $contentObjectAttribute->setValidationLog( $parser->getMessages() );

            $contentObjectAttribute->setAttribute( "data_text", $xmlString );
            return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
        }
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }


    /*!
     \reimp
     Returns the input XML representation of the datatype.
    */
    function &inputXML()
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );

        $originalInput = "originalInput_" . $contentObjectAttributeID;
        $isInputValid = "isInputValid_" . $contentObjectAttributeID;

        if ( isset( $GLOBALS[$isInputValid] ) and $GLOBALS[$isInputValid] == false )
        {
            $output = $GLOBALS[$originalInput];
        }
        else
        {
            $xml = new eZXML();
            $dom =& $xml->domTree( $this->XMLData, array( 'CharsetConversion' => false, 'ConvertSpecialChars' => false ) );
            $links = array();
            $node = array();

            if ( $dom )
            {
                $node =& $dom->elementsByName( "section" );

                // Fetch all links and cache the url's
                $links =& $dom->elementsByName( "link" );
            }
            if ( count( $links ) > 0 )
            {
                $linkIDArray = array();
                // Find all Link id's
                foreach ( $links as $link )
                {
                    $linkIDValue = $link->attributeValue( 'url_id' );
                    if ( !$linkIDValue )
                        continue;
                    if ( !in_array( $linkIDValue, $linkIDArray ) )
                         $linkIDArray[] = $linkIDValue;
                }

                if ( count($linkIDArray) > 0 )
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

            $output = "";
            if ( count( $node ) > 0 )
            {
                $children = $node[0]->children();
                if ( count( $children ) > 0 )
                {
                    $output .= $this->inputSectionXML( $node[0], 0 );
                }
            }
        }
        return $output;
    }

    /*!
     \private
     \return the user input format for the given table cell
    */
    function &inputTdXML( &$tdNode, $currentSectionLevel, $tdSectionLevel = null )
    {
        $output = "";
        if ( get_class( $tdNode ) == "ezdomnode" )
            $tagName = $tdNode->name();
        else
            $tagName = "";
        switch ( $tagName )
        {
            case 'paragraph' :
            {
                $trimmedParaText = trim( $this->inputParagraphXML( $tdNode, $currentSectionLevel, $tdSectionLevel ) );
                if ( ( $pclass = $tdNode->attributeValue( 'class' ) ) )
                    $output .= "<paragraph class=\"$pclass\">$trimmedParaText</paragraph>\n";
                else
                    $output .= "$trimmedParaText\n\n";
            }break;
            case 'section' :
            {
                $tdSectionLevel += 1;
                $output .= $this->inputSectionXML( $tdNode, $currentSectionLevel, $tdSectionLevel );
            }break;

            default :
            {
                eZDebug::writeError( "Unsupported tag at this level: $tagName", "eZXMLTextType::inputTdXML()" );
            }break;
        }
        return $output;
    }


    /*!
     \private
     \return the user input format for the given table cell
    */
    function &inputListXML( &$listNode, $currentSectionLevel, $listSectionLevel = null )
    {
        $output = "";
        if ( get_class( $listNode ) == "ezdomnode" )
            $tagName = $listNode->name();
        else
            $tagName = "";
        switch ( $tagName )
        {
            case 'paragraph' :
            {
                $output .= trim( $this->inputParagraphXML( $listNode, $currentSectionLevel, $listSectionLevel ) ) . "\n\n";
            }break;
            case 'section' :
            {
                $listSectionLevel += 1;
                $output .= $this->inputSectionXML( $tdNode, $currentSectionLevel, $listSectionLevel );
            }break;

            default :
            {
                eZDebug::writeError( "Unsupported tag at this level: $tagName", "eZXMLTextType::inputListXML()" );
            }break;
        }
        return $output;
    }

    /*!
     \private
     \return the user input format for the given section
    */
    function &inputSectionXML( &$section, $currentSectionLevel, $tdSectionLevel = null )
    {
        $output = "";
        foreach ( $section->children() as $sectionNode )
        {
            if ( $tdSectionLevel == null )
            {
                $sectionLevel = $currentSectionLevel;
            }
            else
            {
                $sectionLevel = $tdSectionLevel;
                $currentSectionLevel = $currentSectionLevel;
            }
            $tagName = $sectionNode->name();

            switch ( $tagName )
            {
                case 'header' :
                {
                    $level = $sectionLevel;
                    $anchorName = $sectionNode->attributeValue( 'anchor_name' );
                    $className = $sectionNode->attributeValue( 'class' );
                    $headerAttr = "level='$level'";
                    if ( $anchorName != null )
                        $headerAttr .= " anchor_name='$anchorName'";
                    if ( $className != null )
                        $headerAttr .= " class='$className'";
                    $output .= "<$tagName $headerAttr>" . $sectionNode->textContent(). "</$tagName>\n";
                }break;

                case 'paragraph' :
                {
                    $trimmedParaText = trim( $this->inputParagraphXML( $sectionNode, $sectionLevel, $tdSectionLevel ) );
                    if ( ( $pclass = $sectionNode->attributeValue( 'class' ) ) )
                        $output .= "<paragraph class=\"$pclass\">$trimmedParaText</paragraph>\n";
                    else
                        $output .= "$trimmedParaText\n\n";
                }break;

                case 'section' :
                {
                    $sectionLevel += 1;
                    if ( $tdSectionLevel == null )
                        $output .= $this->inputSectionXML( $sectionNode, $sectionLevel );
                    else
                        $output .= $this->inputSectionXML( $sectionNode, $currentSectionLevel, $sectionLevel );
                }break;

                default :
                {
                    eZDebug::writeError( "Unsupported tag at this level: $tagName", "eZXMLTextType::inputSectionXML()" );
                }break;
            }
        }
        return $output;
    }

    /*!
     \return the input xml of the given paragraph
    */
    function &inputParagraphXML( &$paragraph, $currentSectionLevel, $tdSectionLevel = null )
    {
        $output = "";
        foreach ( $paragraph->children() as $paragraphNode )
        {
            $output .= $this->inputTagXML( $paragraphNode, $currentSectionLevel, $tdSectionLevel );
        }
        return $output;
    }

    /*!
     \return the input xml for the given tag
    */
    function &inputTagXML( &$tag, $currentSectionLevel, $tdSectionLevel = null )
    {
        $output = "";
        $tagName = $tag->name();
        $childTagText = "";
        // render children tags
        $tagChildren = $tag->children();
        foreach ( $tagChildren as $childTag )
        {
            if ( $tagName == 'literal' )
            {
                $tagContent = $childTag->content();
                $tagContent = str_replace("&lt;", "<", $tagContent );
                $tagContent = str_replace("&gt;", ">", $tagContent );
                $tagContent = str_replace("&apos;", "'", $tagContent );
                $tagContent = str_replace("&quot;", '"', $tagContent );
                $tagContent = str_replace("&amp;", "&", $tagContent );

                $childTagText .= $tagContent;
            }
            else
                $childTagText .= $this->inputTagXML( $childTag, $currentSectionLevel, $tdSectionLevel );
        }

        switch ( $tagName )
        {
            case '#text' :
            {
                $tagContent =& $tag->content();

                // If there is a character after '&lt;', we should not convert it to '<' to avoid conflicts.
                // UPD: REMOVED - always convert to &lt;
                //$tagContent = preg_replace( "#&lt;(?![a-zA-Z_:/])#", "<", $tagContent );

                $tagContent = str_replace("&gt;", ">", $tagContent );
                $tagContent = str_replace("&apos;", "'", $tagContent );
                $tagContent = str_replace("&quot;", '"', $tagContent );

                // Sequence like '&amp;amp;' should not be converted to '&amp;' ( if not inside a literal tag )
                $tagContent = preg_replace("#&amp;(?!lt;|gt;|amp;|&apos;|&quot;)#", "&", $tagContent );

                $output .= $tagContent;
                // Get rid of linebreak and spaces stored in xml file
                $output = preg_replace( "#[\n]+#", "", $output );
                $output = preg_replace( "#    #", "", $output );
            }break;

            case 'object' :
            {
                $view = $tag->attributeValue( 'view' );
                $size = $tag->attributeValue( 'size' );
                $alignment = $tag->attributeValue( 'align' );
                $className = $tag->attributeValue( 'class' );
                $hasLink = false;
                $linkID = $tag->attributeValueNS( 'ezurl_id', "http://ez.no/namespaces/ezpublish3/image/" );

                if ( $linkID != null )
                {
                    $href = eZURL::url( $linkID );
                    $target = $tag->attributeValueNS( 'ezurl_target', "http://ez.no/namespaces/ezpublish3/image/" );
                    $hasLink = true;
                }

                if ( strlen( $view ) == 0 )
                    $view = "embed";

                $objectID = $tag->attributeValue( 'id' );

                $objectAttr = "id=\"$objectID\"";

                if ( $className != null )
                     $objectAttr .= " class=\"$className\"";
                if ( $size != null )
                    $objectAttr .= " size=\"$size\"";
                if ( $alignment != null )
                    $objectAttr .= " align=\"$alignment\"";
                if ( $hasLink )
                {
                    $objectAttr .= " href=\"$href\"";
                    if ( $target != null )
                        $objectAttr .= " target=\"$target\"";
                }

                if ( $view != "embed" )
                {
                    $objectAttr .= " view=\"$view\"";
                }

                $customAttributes =& $tag->attributesNS( "http://ez.no/namespaces/ezpublish3/custom/" );
                foreach ( $customAttributes as $attribute )
                {
                    $objectAttr .= " $attribute->Name=\"$attribute->Content\"";
                }
                $output .= "<object $objectAttr />";
            }break;

            case 'embed' :
            case 'embed-inline' :
            {
                $view = $tag->attributeValue( 'view' );
                $size = $tag->attributeValue( 'size' );
                $alignment = $tag->attributeValue( 'align' );
                $objectID = $tag->attributeValue( 'object_id' );
                $nodeID = $tag->attributeValue( 'node_id' );
                $showPath = $tag->attributeValue( 'show_path' );
                $htmlID = $tag->attributeValueNS( 'id', 'http://ez.no/namespaces/ezpublish3/xhtml/' );
                $className = $tag->attributeValue( 'class' );

                if ( $objectID != null )
                {
                    $href = 'ezobject://' .$objectID;
                }
                elseif ( $showPath == 'true' )
                {
                	$node = eZContentObjectTreeNode::fetch( $nodeID );
                    if ( $node )
                    	$href = 'eznode://' . $node->attribute('path_identification_string');
                    else
                    	$href = 'eznode://' . $nodeID;
                }
                else
                	$href = 'eznode://' . $nodeID;

                $objectAttr = " href='$href'";

                if ( $size != null )
                    $objectAttr .= " size='$size'";
                if ( $alignment != null )
                    $objectAttr .= " align='$alignment'";
                if ( $view != null )
                    $objectAttr .= " view='$view'";
                if ( $htmlID != '' )
                    $objectAttr .= " id='$htmlID'";
                if ( $className != null )
                     $objectAttr .= " class='$className'";

                $customAttributes =& $tag->attributesNS( "http://ez.no/namespaces/ezpublish3/custom/" );
                foreach ( $customAttributes as $attribute )
                {
                    $objectAttr .= " $attribute->Name='$attribute->Content'";
                }
                $output .= "<$tagName$objectAttr />";
            }break;

            case 'ul' :
            case 'ol' :
            {
                $listContent = "";
                $className = $tag->attributeValue( 'class' );
                // find all list elements
                foreach ( $tag->children() as $listItemNode )
                {
                    $listItemContent = "";
                    foreach ( $listItemNode->children() as $itemChildNode )
                    {
                        $listSectionLevel = $currentSectionLevel;
                        if ( $itemChildNode->name() == "section" or $itemChildNode->name() == "paragraph" )
                            $listItemContent .= $this->inputListXML( $itemChildNode, $currentSectionLevel, $listSectionLevel );
                        else
                            $listItemContent .= $this->inputTagXML( $itemChildNode, $currentSectionLevel, $tdSectionLevel );
                    }
                    $liClassName = $listItemNode->attributeValue( 'class' );
                    if ( $liClassName != null )
                        $listContent .= "  <li class='$liClassName'>" . trim($listItemContent) . "</li>\n";
                    else
                        $listContent .= "  <li>" . trim($listItemContent). "</li>\n";
                }
                if ( $className != null )
                    $output .= "<$tagName class='$className'>\n$listContent</$tagName>" .  "\n";
                else
                    $output .= "<$tagName>\n$listContent</$tagName>" .  "\n";
            }break;

            case 'table' :
            {
                $tableRows = "";
                $border = $tag->attributeValue( 'border' );
                $width = $tag->attributeValue( 'width' );
                $tableClass = $tag->attributeValue( 'class' );

                // find all table rows
                foreach ( $tag->children() as $tableRow )
                {
                    $tableData = "";
                    foreach ( $tableRow->children() as $tableCell )
                    {
                        $cellContent = "";
                        $tdSectionLevel = $currentSectionLevel;
                        $className = $tableCell->attributeValue( 'class' );
                        $cellWidth = $tableCell->attributeValueNS( 'width', "http://ez.no/namespaces/ezpublish3/xhtml/" );
                        $colspan = $tableCell->attributeValueNS( 'colspan', "http://ez.no/namespaces/ezpublish3/xhtml/" );
                        $rowspan = $tableCell->attributeValueNS( 'rowspan', "http://ez.no/namespaces/ezpublish3/xhtml/" );

                        foreach ( $tableCell->children() as $tableCellChildNode )
                        {
                            $cellContent .= $this->inputTdXML( $tableCellChildNode, $currentSectionLevel, $tdSectionLevel - $currentSectionLevel );
                        }

                        $cellAttribute = "";
                        if ( $className != null )
                            $cellAttribute .= " class='$className'";
                        if ( $cellWidth != null )
                            $cellAttribute .= " width='$cellWidth'";
                        if ( $colspan != null )
                            $cellAttribute .= " colspan='$colspan'";
                        if ( $rowspan != null )
                            $cellAttribute .= " rowspan='$rowspan'";

                        if ( $tableCell->name() == "th" )
                        {
                            $tableData .= "  <th$cellAttribute>" . trim( $cellContent ) . "</th>\n";
                        }
                        else
                        {
                            $tableData .= "  <td$cellAttribute>" . trim( $cellContent ) . "</td>\n";
                        }
                    }
                    $tableRows .= "<tr>\n$tableData</tr>\n";
                }

                $attributes = "";
                if ( $tableClass != null )
                    $attributes .= " class='$tableClass'";
                if ( $border != null )
                    $attributes .= " border='$border'";
                if ( $width != null )
                    $attributes .= " width='$width'";

                $output .= "<table$attributes>\n$tableRows</table>\n";
            }break;

            case 'literal' :
            {
                $className = $tag->attributeValue( 'class' );
		//		$literalText = '';
        //        foreach ( $tagChildren as $childTag )
        //        {
        //            $literalText .= $childTag->content();
        //        }

                if ( $className != null )
                    $output .= "<$tagName class='$className'>" . $childTagText . "</$tagName>";
                else
                    $output .= "<$tagName>" . $childTagText . "</$tagName>";
            }break;

            // normal content tags
            case 'emphasize' :
            case 'em' :
            case 'i' :
            {
                $className = $tag->attributeValue( 'class' );
                if ( $className != null )
                    $output .= "<emphasize class='$className'>" . $childTagText . "</emphasize>";
                else
                    $output .= "<emphasize>" . $childTagText . "</emphasize>";
            }break;

            // normal content tags
            case 'b' :
            case 'bold' :
            case 'strong' :
            {
                $className = $tag->attributeValue( 'class' );
                if ( $className != null )
                    $output .= "<strong class='$className'>" . $childTagText . "</strong>";
                else
                    $output .= "<strong>" . $childTagText . "</strong>";
            }break;

            // Custom tags
            case 'custom' :
            {
                $name = $tag->attributeValue( 'name' );
                $isInline = false;
                include_once( "lib/ezutils/classes/ezini.php" );
                $ini =& eZINI::instance( 'content.ini' );

                $isInlineTagList = $ini->variable( 'CustomTagSettings', 'IsInline' );
                foreach ( array_keys ( $isInlineTagList ) as $key )
                {
                    $isInlineTagValue =& $isInlineTagList[$key];
                    if ( $isInlineTagValue )
                    {
                        if ( $name == $key )
                            $isInline = true;
                    }
                }

                $customAttributes =& $tag->attributesNS( "http://ez.no/namespaces/ezpublish3/custom/" );
                $customAttr = "";
                foreach ( $customAttributes as $attribute )
                {
                    $customAttr .= " $attribute->Name=\"$attribute->Content\"";
                }

                if ( $isInline )
                    $output .= "<$tagName name='$name'" . $customAttr . ">" . $childTagText . "</custom>";
                else
                {
                    $customTagContent = "";
                    foreach ( $tag->children() as $tagChild )
                    {
                        $customTagContent .= $this->inputTdXML( $tagChild, $currentSectionLevel, $tdSectionLevel );
                    }
                    $output .= "<$tagName name='$name'" . $customAttr . ">\n" .   trim( $customTagContent ) . "\n</$tagName>";
                }
            }break;

            case 'link' :
            {
                $linkID = $tag->attributeValue( 'url_id' );
                $target = $tag->attributeValue( 'target' );
                $className = $tag->attributeValue( 'class' );
                $objectID = $tag->attributeValue( 'object_id' );
                $nodeID = $tag->attributeValue( 'node_id' );
                $anchorName = $tag->attributeValue( 'anchor_name' );
                $showPath = $tag->attributeValue( 'show_path' );

                $htmlID = $tag->attributeValueNS( 'id', 'http://ez.no/namespaces/ezpublish3/xhtml/' );
                $htmlTitle = $tag->attributeValueNS( 'title', 'http://ez.no/namespaces/ezpublish3/xhtml/' );

                if ( $objectID != null )
                {
                    $href = 'ezobject://' .$objectID;
                }
                elseif ( $nodeID != null )
                {
                	if ( $showPath == 'true' )
                    {
                    	$node = eZContentObjectTreeNode::fetch( $nodeID );
	                    if ( $node )
	                        $href = 'eznode://' . $node->attribute('path_identification_string');
	                    else
	                        $href = 'eznode://' . $nodeID;
            		}
                	else
                    	$href = 'eznode://' . $nodeID;
                }
                elseif ( $linkID != null )
                {
                    // Fetch URL from cached array
                    $href = $this->LinkArray[$linkID];
                }
                else
                {
                    $href = $tag->attributeValue( 'href' );
                }

                if ( $anchorName != null )
                {
                    $href .= '#' .$anchorName;
                }

                $attributes = array();
                if ( $className != '' )
                    $attributes[] = "class='$className'";
                $attributes[] = "href='$href'";
                if ( $target != '' )
                    $attributes[] = "target='$target'";
                if ( $htmlTitle != '' )
                    $attributes[] = "title='$htmlTitle'";
                if ( $htmlID != '' )
                    $attributes[] = "id='$htmlID'";

                $attributeText = '';
                if ( count( $attributes ) > 0 )
                    $attributeText = ' ' .implode( ' ', $attributes );
                $output .= "<$tagName$attributeText>" . $childTagText . "</$tagName>";
            }break;

            case 'anchor' :
            {
                $name = $tag->attributeValue( 'name' );
                $output .= "<$tagName name='$name' />";
            }break;

            case 'line' :
            {
                $output .= $childTagText . "\n";
            }break;

            case 'tr' :
            case 'td' :
            case 'th' :
            case 'li' :
            case 'header' :
            case 'section' :
            case 'paragraph' :
            {
            }break;
            default :
            {
                eZDebug::writeError( "Unsupported tag: $tagName", "eZXMLTextType::inputParagraphXML()" );
            }break;
        }
        return $output;
    }
   /*
    var $SectionArray = array( 'paragraph', 'section', 'header' );

    var $BlockTagArray = array( 'table', 'ul', 'ol', 'literal', 'custom' );

    var $InLineTagArray = array( 'emphasize', 'strong', 'link', 'anchor', 'line', 'object', 'embed', 'embed-inline' );

    var $LineTagArray = array( 'emphasize', 'strong', 'link', 'anchor', 'li', 'object', 'embed', 'embed-inline' );

    var $TagAliasArray = array( 'strong' => array( 'b', 'bold', 'strong' ), 'emphasize' => array( 'em', 'i', 'emphasize' ), 'link' => array( 'link', 'a' ) , 'header' => array( 'header', 'h' ), 'paragraph' => array( 'p' ) );

    /// Contains all supported tag for xml parse
    var $SupportedTagArray = array( 'paragraph', 'section', 'header', 'table', 'ul', 'ol', 'literal', 'custom', 'object', 'embed', 'embed-inline', 'emphasize', 'strong', 'link', 'anchor', 'tr', 'td', 'th', 'li', 'line' );

    /// Contains all supported input tag
    var $SupportedInputTagArray = array( 'header', 'table', 'ul', 'ol', 'literal', 'custom', 'object', 'embed', 'embed-inline', 'emphasize', 'strong', 'link', 'anchor', 'tr', 'td', 'th', 'li' );
    */
    var $ContentObjectAttribute;

    var $IsInputValid;

    /// Contains all links hashed by ID
    var $LinkArray = array();

    var $IsStrictHeader = false;
}
?>
