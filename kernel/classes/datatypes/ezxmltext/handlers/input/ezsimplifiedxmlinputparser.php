<?php
//
// Definition of eZSimplifiedXMLInputParser class
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

//

include_once( 'kernel/classes/datatypes/ezxmltext/ezxmlinputparser.php' );

class eZSimplifiedXMLInputParser extends eZXMLInputParser
{
    var $InputTags = array(
        'b'       => array( 'name' => 'strong' ),
        'bold'    => array( 'name' => 'strong' ),
        'i'       => array( 'name' => 'emphasize' ),
        'em'      => array( 'name' => 'emphasize' ),
        'h'       => array( 'name' => 'header' ),
        'p'       => array( 'name' => 'paragraph' ),
        'para'    => array( 'name' => 'paragraph' ),
        'br'      => array( 'name' => 'br',
                            'noChildren' => true ),
        'a'       => array( 'name' => 'link' ),
        );

    var $OutputTags = array(
        'section'   => array(),

        'embed'     => array( //'parsingHandler' => 'breakInlineFlow',
                              'structHandler' => 'appendLineParagraph',
                              'publishHandler' => 'publishHandlerEmbed',
                              'attributes' => array( 'id' => 'xhtml:id' ),
                              'requiredInputAttributes' => array( 'href' ) ),

        'embed-inline'     => array( //'parsingHandler' => 'breakInlineFlow',
                              'structHandler' => 'appendLineParagraph',
                              'publishHandler' => 'publishHandlerEmbed',
                              'attributes' => array( 'id' => 'xhtml:id' ),
                              'requiredInputAttributes' => array( 'href' ) ),

        'object'    => array( //'parsingHandler' => 'breakInlineFlow',
                              'structHandler' => 'appendLineParagraph',
                              'publishHandler' => 'publishHandlerObject',
                              'attributes' => array( 'href' => 'image:ezurl_href',
                                                     'target' => 'image:ezurl_target',
                                                     'ezurl_href' => 'image:ezurl_href',
                                                     'ezurl_id' => 'image:ezurl_id',
                                                     'ezurl_target' => 'image:ezurl_target' ),
                              'requiredInputAttributes' => array( 'id' ) ),

        'table'     => array( 'structHandler' => 'appendParagraph' ),

        'tr'        => array(),

        'td'        => array( 'attributes' => array( 'width' => 'xhtml:width',
                                                     'colspan' => 'xhtml:colspan',
                                                     'rowspan' => 'xhtml:rowspan' ) ),

        'th'        => array( 'attributes' => array( 'width' => 'xhtml:width',
                                                     'colspan' => 'xhtml:colspan',
                                                     'rowspan' => 'xhtml:rowspan' ) ),

        'ol'        => array( 'structHandler' => 'structHandlerLists' ),

        'ul'        => array( 'structHandler' => 'structHandlerLists' ),

        'li'        => array( 'autoCloseOn' => array( 'li' ) ),

        'header'    => array( 'autoCloseOn' => array( 'paragraph' ),
                              'structHandler' => 'structHandlerHeader' ),

        'paragraph' => array( 'autoCloseOn' => array( 'paragraph' ),
                              'publishHandler' => 'publishHandlerParagraph' ),

        'line'      => array(),

        'br'        => array( 'parsingHandler' => 'breakInlineFlow',
                              'structHandler' => 'structHandlerBr',
                              'attributes' => false ),

        'literal'   => array( 'parsingHandler' => 'parsingHandlerLiteral',
                              'structHandler' => 'appendLineParagraph' ),

        'strong'    => array( 'structHandler' => 'appendLineParagraph' ),

        'emphasize' => array( 'structHandler' => 'appendLineParagraph' ),

        'link'      => array( 'structHandler' => 'appendLineParagraph',
                              'publishHandler' => 'publishHandlerLink',
                              'attributes' => array( 'title' => 'xhtml:title',
                                                     'id' => 'xhtml:id' ),
                              'requiredInputAttributes' => array( 'href' ) ),

        'anchor'    => array( 'structHandler' => 'appendLineParagraph' ),

        'custom'    => array( 'parsingHandler' => 'parsingHandlerCustom',
                              'structHandler' => 'structHandlerCustom',
                              'publishHandler' => 'publishHandlerCustom',
                              'requiredInputAttributes' => array( 'name' ) ),

        '#text'     => array( 'structHandler' => 'appendLineParagraph' )
        );

    function eZSimplifiedXMLInputParser( $contentObjectID, $validate = true )
    {
        $this->contentObjectID = $contentObjectID;
        $this->errorLevel = 2;
        $this->eZXMLInputParser( $validate );
    }

    /*
        Parsing Handlers (called at pass 1)
    */
    function &parsingHandlerLiteral( &$element, &$param )
    {
        $ret = null;
        $data =& $param[0];
        $pos =& $param[1];

        $tablePos = strpos( $data, '</literal>', $pos );
        if ( $tablePos === false )
            $tablePos = strpos( $data, '</LITERAL>', $pos );

        if ( $tablePos === false )
            return $ret;

        $text = substr( $data, $pos, $tablePos - $pos );

        /*$text = preg_replace( "/\s*<\s?\/?t[drh|(body)].*?>/i", "", $text );

        $text = preg_replace( "/^<p.*?>/i", "", $text );

        $text = preg_replace( "/<\/\s?p>/i", "", $text );

        $text = preg_replace( "/<p.*?>/i", "\n\n", $text );
        */
        $text = preg_replace( "/<\/?\s?br.*?>/i", "\n", $text );

        $text = $this->entitiesDecode( $text );
        $text = $this->convertNumericEntities( $text );

        $textNode = $this->Document->createTextNode( $text );
        $element->appendChild( $textNode );

        $pos = $tablePos + strlen( '</literal>' );
        $ret = false;

        return $ret;
    }

    function &parsingHandlerCustom( &$element, &$param )
    {
        $ret = null;
        $name = $element->getAttribute( 'name' );

        $isInline = false;
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
        $element->setAttribute( 'inline', $isInline ? 'true' : 'false' );
        return $ret;
    }

    function &breakInlineFlow( &$element, &$param )
    {
        // Breaks the flow of inline tags. Used for non-inline tags caught within inline.
        // Works for tags with no children only.
        $ret = null;
        $data =& $param[0];
        $pos =& $param[1];
        $parent =& $element->parentNode;

        $tagBeginPos = $pos - 1;
        while( $data[$tagBeginPos] != '<' )
            $tagBeginPos--;

        $wholeTagString = substr( $data, $tagBeginPos, $pos - $tagBeginPos );

        if ( $parent &&
             //!$this->XMLSchema->isInline( $element ) &&
             $this->XMLSchema->isInline( $parent ) //&&
             //!$this->XMLSchema->check( $parent, $element )
             )
        {
            $insertData = '';
            $currentParent =& $parent;
            end( $this->ParentStack );
            do
            {
                $stackData = current( $this->ParentStack );
                $currentParentName = $stackData[0];
                $insertData .= "</$currentParentName>";
                $currentParent =& $currentParent->parentNode;
                prev( $this->ParentStack );
            }
            while( $this->XMLSchema->isInline( $currentParent ) );

            $insertData .= $wholeTagString;

            $currentParent =& $parent;
            end( $this->ParentStack );
            $appendData = '';
            do
            {
                $stackData = current( $this->ParentStack );
                $currentParentName = $stackData[0];
                $currentParentAttrString = '';
                if ( $stackData[2] )
                    $currentParentAttrString = ' ' . $stackData[2];
                $appendData = "<$currentParentName$currentParentAttrString>" . $appendData;
                $currentParent =& $currentParent->parentNode;
                prev( $this->ParentStack );
            }
            while( $this->XMLSchema->isInline( $currentParent ) );

            $insertData .= $appendData;

            $data = $insertData . substr( $data, $pos );
            $pos = 0;
            $parent->removeChild( $element );
            $ret = false;
        }

        return $ret;
    }

    
    /*
        Structure handlers. (called at pass 2)
    */
    // Structure handler for inline nodes.
    function &appendLineParagraph( &$element, &$newParent )
    {
        $ret = null;
        $parent =& $element->parentNode;
        if ( !$parent )
            return $ret;

        $parentName = $parent->nodeName;
        $next =& $element->nextSibling();
        $newParentName = $newParent != null ? $newParent->nodeName : '';

        // Correct structure by adding <line> and <paragraph> tags.
        if ( $parentName == 'line' || $this->XMLSchema->isInline( $parent ) )
        {
            return $ret;
        }

        if ( $newParentName == 'line' )
        {
            $parent->removeChild( $element );
            $newParent->appendChild( $element );
            $newLine =& $newParent;
            $ret =& $newParent;
        }
        elseif ( $parentName == 'paragraph' )
        {
            $newLine = $this->Document->createElement( 'line' );
            $parent->replaceChild( $newLine, $element );
            $newLine->appendChild( $element );
            $ret =& $newLine;
        }
        elseif ( $newParentName == 'paragraph' )
        {
            $newLine = $this->Document->createElement( 'line' );
            $parent->removeChild( $element );
            $newParent->appendChild( $newLine );
            $newLine->appendChild( $element );
            $ret =& $newLine;
        }
        elseif ( $this->XMLSchema->check( $parent, 'paragraph' ) )
        {
            $newPara =& $this->createAndPublishElement( 'paragraph' );
            $newLine = $this->Document->createElement( 'line' );
            $parent->replaceChild( $newPara, $element );
            $newPara->appendChild( $newLine );
            $newLine->appendChild( $element );
            $ret =& $newLine;
        }

        return $ret;
    }

    // Structure handler for temporary <br> elements
    function &structHandlerBr( &$element, &$newParent )
    {
        $ret =& $newParent;

        if ( $newParent )
        {
            if ( $newParent->nodeName == 'line' )
            {
                $ret =& $newParent->parentNode;
                $next =& $element->nextSibling();
                if ( $next && $next->nodeName == 'br' && $ret->parentNode )
                {
                    $ret =& $ret->parentNode;
                }
            }
            elseif ( $newParent->nodeName == 'paragraph' )
            {
                $next =& $element->nextSibling();
                if ( $next && $next->nodeName == 'br' )
                {
                    $ret =& $newParent->parentNode;
                }
            }
        }
        return $ret;
    }

    // Structure handler for in-paragraph nodes.
    function &appendParagraph( &$element, &$newParent )
    {
        $ret = null;
        $parent =& $element->parentNode;
        if ( !$parent )
            return $ret;

        $parentName = $parent->nodeName;

        if ( $parentName != 'paragraph' )
        {
            if ( $newParent && $newParent->nodeName == 'paragraph' )
            {
                $parent->removeChild( $element );
                $newParent->appendChild( $element );
                return $newParent;
            }
            if ( $newParent && $newParent->parentNode && $newParent->parentNode->nodeName == 'paragraph' )
            {
                $para =& $newParent->parentNode;
                $parent->removeChild( $element );
                $para->appendChild( $element );
                return $newParent->parentNode;
            }

            if ( $this->XMLSchema->check( $parentName, 'paragraph' ) )
            {
                $newPara =& $this->createAndPublishElement( 'paragraph' );;
                $parent->replaceChild( $newPara, $element );
                $newPara->appendChild( $element );
                $ret =& $newPara;
            }
        }
        return $ret;
    }

    // Structure handler for 'header' tag.
    function &structHandlerHeader( &$element, &$param )
    {
        $ret = null;
        $parent =& $element->parentNode;
        $level = $element->getAttribute( 'level' );
        if ( !$level )
            $level = 1;

        $element->removeAttribute( 'level' );
        if ( $level )
        {
            $sectionLevel = -1;
            $current =& $element;
            while( $current->parentNode )
            {
                $tmp =& $current;
                $current =& $tmp->parentNode;
                if ( $current->nodeName == 'section' )
                    $sectionLevel++;
                else
                    if ( $current->nodeName == 'td' )
                    {
                        $sectionLevel++;
                        break;
                    }
            }
            if ( $level > $sectionLevel )
            {
                $newParent =& $parent;
                for ( $i = $sectionLevel; $i < $level; $i++ )
                {
                   $newSection = $this->Document->createElement( 'section' );
                   $newParent->appendChild( $newSection );
                   // Schema check
                   if ( !$this->processElementBySchema( $newSection, false ) )
                   {
                       return $ret;
                   }
                   $newParent =& $newSection;
                   unset( $newSection );
                }
                $elementToMove =& $element;
                while( $elementToMove && $elementToMove->nodeName != 'section' )
                {
                    $next =& $elementToMove->nextSibling();
                    $parent->removeChild( $elementToMove );
                    $newParent->appendChild( $elementToMove );
                    $elementToMove =& $next;
                }
            }
            elseif ( $level < $sectionLevel )
            {
                $newLevel = $sectionLevel + 1;
                $current =& $element;
                while( $level < $newLevel )
                {
                    $tmp =& $current;
                    $current =& $tmp->parentNode;
                    if ( $current->nodeName == 'section' )
                        $newLevel--;
                }
                $elementToMove =& $element;
                while( $elementToMove && $elementToMove->nodeName != 'section' )
                {
                    $next =& $elementToMove->nextSibling();
                    $parent->removeChild( $elementToMove );
                    $current->appendChild( $elementToMove );
                    $elementToMove =& $next;
                }
            }
        }
        return $ret;
    }

    // Structure handler for 'custom' tag.
    function &structHandlerCustom( &$element, &$params )
    {
        $ret = null;
        $isInline = $element->getAttribute( 'inline' );
        if ( $isInline === 'true' )
        {
            $ret =& $this->appendLineParagraph( $element, $params );
        }
        elseif ( $isInline === 'false' )
        {
            $ret =& $this->appendParagraph( $element, $params );
        }
        return $ret;
    }

    // Structure handler for 'ul' and 'ol' tags.
    function &structHandlerLists( &$element, &$params )
    {
        $ret = null;
        $parent =& $element->parentNode;
        $parentName = $parent->nodeName;

        if ( $parentName == 'paragraph' )
            return $ret;

        // If we are inside a list
        if ( $parentName == 'ol' || $parentName == 'ul' )
        {
            // If previous 'li' doesn't exist, create it,
            // else append to the previous 'li' element.
            $prev =& $element->previousSibling();
            if ( !$prev )
            {
                $li = $this->Document->createElement( 'li' );
                $parent->insertBefore( $li, $element );
                $parent->removeChild( $element );
                $li->appendChild( $element );
            }
            else
            {
                $lastChild =& $prev->lastChild();
                if ( $lastChild->nodeName != 'paragraph' )
                {
                    $para = $this->Document->createElement( 'paragraph' );
                    $parent->removeChild( $element );
                    $prev->appendChild( $element );
                    $ret =& $para;
                }
                else
                {
                    $parent->removeChild( $element );
                    $lastChild->appendChild( $element );
                    $ret =& $lastChild;
                }
                return $ret;
            }
        }
        if ( $parentName == 'li' )
        {
            $prev =& $element->previousSibling();
            if ( $prev )
            {
                $parent->removeChild( $element );
                $prev->appendChild( $element );
                return $prev;
            }
        }
        $ret =& $this->appendParagraph( $element, $params );

        return $ret;
    }

    /*
        Publish handlers. (called at pass 2)
    */
    // Publish handler for 'paragraph' element.
    function &publishHandlerParagraph( &$element, &$params )
    {
        // Removes single line tag
        $ret = null;
        // php5 TODO: childNodes->length
        $line =& $element->lastChild();
        if ( count( $element->Children ) == 1 && $line->nodeName == 'line' )
        {
            foreach( array_keys( $line->Children ) as $key )
            {
                $newChild =& $line->Children[$key];
                $line->removeChild( $newChild );
                $element->appendChild( $newChild );
            }
            $element->removeChild( $line );
        }
        return $ret;
    }

    // Publish handler for 'link' element.
    function &publishHandlerLink( &$element, &$params )
    {
        $ret = null;

        $href = $element->getAttribute( 'href' );

        if ( $href )
        {
            if ( ereg( "^ezobject://[0-9]+(#.*)?$", $href ) )
            {
                $url = strtok( $href, '#' );
                $anchorName = strtok( '#' );
                $objectID = substr( strrchr( $url, "/" ), 1 );
                $element->setAttribute( 'object_id', $objectID );

              /*  Adding to related objects: temporary disabled
               
                 if ( !in_array( $objectID, $this->relatedObjectIDArray ) )
                    $this->relatedObjectIDArray[] = $objectID;
               */
            }
            elseif ( ereg( "^eznode://.+(#.*)?$" , $href ) )
            {
                $objectID = null;
                $url = strtok( $href, '#' );
                $anchorName = strtok( '#' );
                $nodePath = substr( strchr( $url, "/" ), 2 );
                if ( ereg( "^[0-9]+$", $nodePath ) )
                {
                    $nodeID = $nodePath;
                    $node = eZContentObjectTreeNode::fetch( $nodeID );
                    if ( !$node && $this->errorLevel >= 1 )
                    {
                        $this->Messages[] = ezi18n( 'kernel/classes/datatypes', 'Node %1 does not exist.',
                                                    false, array( $nodeID ) );
                    }
                    else
                    {
                        $objectID = $node->attribute( 'contentobject_id' );
                    }
                }
                else
                {
                    $node = eZContentObjectTreeNode::fetchByURLPath( $nodePath );
                    if ( !$node && $this->errorLevel >= 1 )
                    {
                        $this->Messages[] = ezi18n( 'kernel/classes/datatypes', 'Node \'%1\' does not exist.',
                                                    false, array( $nodePath ) );
                    }
                    else
                    {
                        $nodeID = $node->attribute( 'node_id' );
                        $objectID = $node->attribute( 'contentobject_id' );
                    }
                    $element->setAttribute( 'show_path', 'true' );
                }
                $element->setAttribute( 'node_id', $nodeID );

                /*  Adding to related objects: temporary disabled

                if ( $objectID && !in_array( $objectID, $this->relatedObjectIDArray ) )
                    $this->relatedObjectIDArray[] = $objectID;
                */
            }
            elseif ( ereg( "^#.*$" , $href ) )
            {
                $anchorName = substr( $href, 1 );
            }
            else
            {
                //washing href. single and double quotes replaced with their urlencoded form
                $href = str_replace( array('\'','"'), array('%27','%22'), $href );

                $temp = explode( '#', $href );
                $url = $temp[0];
                if ( isset( $temp[1] ) )
                    $anchorName = $temp[1];

                if ( $url )
                {
                    // Protection from XSS attack
                    if ( preg_match( "/^(java|vb)script:.*/i" , $url ) && $this->errorLevel >= 1 )
                    {
                        $this->Messages[] = ezi18n( 'kernel/classes/datatypes', "Using scripts in links is not allowed, link '%1' has been removed",
                                                    false, array( $url ) );
                    }
                    else
                    {
                        $urlID = $this->convertHrefToID( $url );
                        if ( $urlID )
                        {
                            if ( $this->eZPublishVersion >= 3.6 )
                                $urlIDAttributeName = 'url_id';
                            else
                                $urlIDAttributeName = 'id';
                            $element->setAttribute( $urlIDAttributeName, $urlID );
                        }
                    }
                }
            }

            if ( isset( $anchorName ) && $anchorName )
                    $element->setAttribute( 'anchor_name', $anchorName );

            $element->removeAttribute( 'href' );
        }

        return $ret;
    }

    function convertHrefToID( $href )
    {
        $href = str_replace("&amp;", "&", $href );

        $urlID = eZURL::registerURL( $href );

        if ( !in_array( $urlID, $this->urlIDArray ) )
             $this->urlIDArray[] = $urlID;

        return $urlID;
    }

    // Publish handler for 'embed' element.
    function &publishHandlerEmbed( &$element, &$params )
    {
        $ret = null;
        
        $href = $element->getAttribute( 'href' );
        //washing href. single and double quotes replaced with their urlencoded form
        $href = str_replace( array('\'','"'), array('%27','%22'), $href );

        if ( $href != null )
        {
            if ( ereg( "^ezobject://[0-9]+$" , $href ) )
            {
                $objectID = substr( strrchr( $href, "/" ), 1 );

                // protection from self-embedding
                if ( $objectID == $this->contentObjectID )
                {
                    $this->isInputValid = false;
                    if ( $this->errorLevel >= 1 )
                        $this->Messages[] = ezi18n( 'kernel/classes/datatypes',
                                                    'Object %1 can not be embeded to itself.', false, array( $objectID ) );
                    return $ret;
                }

                $element->setAttribute( 'object_id', $objectID );

                if ( !in_array( $objectID, $this->relatedObjectIDArray ) )
                    $this->relatedObjectIDArray[] = $objectID;
            }
            elseif ( ereg( "^eznode://.+$" , $href ) )
            {
                $nodePath = substr( strchr( $href, "/" ), 2 );

                if ( ereg( "^[0-9]+$", $nodePath ) )
                {
                    $nodeID = $nodePath;
                    $node = eZContentObjectTreeNode::fetch( $nodeID );
                    if ( !$node )
                    {
                        $this->isInputValid = false;

                        if ( $this->errorLevel >= 1 )
                            $this->Messages[] = ezi18n( 'kernel/classes/datatypes', 'Node %1 does not exist.',
                                                        false, array( $nodeID ) );
                        return $ret;
                    }
                }
                else
                {
                    $node = eZContentObjectTreeNode::fetchByURLPath( $nodePath );
                    if ( !$node )
                    {
                        $this->isInputValid = false;

                        if ( $this->errorLevel >= 1 )
                            $this->Messages[] = ezi18n( 'kernel/classes/datatypes', 'Node \'%1\' does not exist.',
                                                        false, array( $nodePath ) );
                        return $ret;
                    }
                    $nodeID = $node->attribute('node_id');
                    $element->setAttribute( 'show_path', 'true' );
                }

                $element->setAttribute( 'node_id', $nodeID );
                $objectID = $node->attribute( 'contentobject_id' );

                // protection from self-embedding
                if ( $objectID == $this->contentObjectID )
                {
                    $this->isInputValid = false;

                    if ( $this->errorLevel >= 1 )
                        $this->Messages[] = ezi18n( 'kernel/classes/datatypes', 'Object %1 can not be embeded to itself.',
                                                    false, array( $objectID ) );
                    return $ret;
                }

                if ( !in_array( $objectID, $this->relatedObjectIDArray ) )
                     $this->relatedObjectIDArray[] = $objectID;
            }
            else
            {
                $this->isInputValid = false;
                $this->Messages[] = ezi18n( 'kernel/classes/datatypes', 'Invalid reference in <embed> tag. Note that <embed> tag supports only \'eznode\' and \'ezobject\' protocols.' );
                return $ret;
            }
        }

        $element->removeAttribute( 'href' );
        $this->convertCustomAttributes( $element );
        return $ret;
    }

    // Publish handler for 'object' element.
    function &publishHandlerObject( &$element, &$params )
    {
        $ret = null;

        $objectID = $element->getAttribute( 'id' );
        // protection from self-embedding
        if ( $objectID == $this->contentObjectID )
        {
            $this->isInputValid = false;
            $this->Messages[] = ezi18n( 'kernel/classes/datatypes',
                                        'Object %1 can not be embeded to itself.', false, array( $objectID ) );
            return $ret;
        }

        if ( !in_array( $objectID, $this->relatedObjectIDArray ) )
             $this->relatedObjectIDArray[] = $objectID;

        // If there are any image object with links.
        $href = $element->getAttributeNS( $this->Namespaces['image'], 'ezurl_href' );
        //washing href. single and double quotes inside url replaced with their urlencoded form
        $href = str_replace( array('\'','"'), array('%27','%22'), $href );

        $urlID = $element->getAttributeNS( $this->Namespaces['image'], 'ezurl_id' );

        if ( $href != null )
        {
            $urlID = eZURL::registerURL( $href );
            $element->setAttributeNS( $this->Namespaces['image'], 'image:ezurl_id', $urlID );
            $element->removeAttributeNS( $this->Namespaces['image'], 'ezurl_href' );
        }

        if ( $urlID != null )
        {
            $this->urlIDArray[] = $urlID;
        }
        
        $this->convertCustomAttributes( $element );
        
        return $ret;
    }

    // Publish handler for 'custom' element.
    function &publishHandlerCustom( &$element, &$params )
    {
        $ret = null;
        
        $element->removeAttribute( 'inline' );
        $this->convertCustomAttributes( $element );

        return $ret;
    }

    function convertCustomAttributes( &$element )
    {
        $schemaAttrs = $this->XMLSchema->attributes( $element );
        $attributes = $element->attributes();
    
        foreach( $attributes as $attr )
        {
            if ( !$attr->Prefix && !in_array( $attr->LocalName, $schemaAttrs ) )
            {
                $element->setAttributeNS( $this->Namespaces['custom'], 'custom:' . $attr->LocalName, $element->getAttribute( $attr->LocalName ) );
                $element->removeAttribute( $attr->LocalName );
            }
        }
    }

    function getRelatedObjectIDArray()
    {
        return $this->relatedObjectIDArray;
    }

    function getUrlIDArray()
    {
        return $this->urlIDArray;
    }

    var $urlIDArray = array();
    var $relatedObjectIDArray = array();

    // needed for self-embedding protection
    var $contentObjectID = 0;
}
?>
