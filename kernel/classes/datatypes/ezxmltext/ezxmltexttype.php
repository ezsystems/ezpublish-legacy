<?php
//
// Definition of eZXMLTextType class
//
// Created on: <06-May-2002 20:02:55 bf>
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
//

/*!
  \class eZXMLTextType ezxmltexttype
  \ingroup eZDatatype
  \brief The class eZXMLTextType haneles XML formatted datatypes

The formatted datatypes store the data in XML. A typical example of this is shown below:
\code
<?xml version="1.0" encoding="utf-8" ?>
<section xmlns:image="http://ez.no/namespaces/ezpublish3/image/"
         xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/">
<header>This is a level one header</header>
<paragraph>
This is a <emphasize>block</emphasize> of text.
</paragraph>
  <section>
  <header class="foo">This is a level two header has classification "foo"</header>
  <paragraph>
  This is the second paragraph with <bold class="foo">bold text which has classification "foo"</bold>
  </paragraph>
  <header>This is a level two header</header>
  <paragraph>
    <line>Paragraph can have table</line>
    <table class="foo" border='1' width='100%'>
      <tr>
        <th class="foo"><paragraph>table header of class "foo"</paragraph></th>
        <td xhtml:width="66" xhtml:colspan="2" xhtml:rowspan="2">
          <paragraph>table cell text</paragraph>
        </td>
      </tr>
    </table>
  </paragraph>
  <paragraph>
    <line>This is the first line with <anchor name="first">anchor</anchor></line>
    <line>This is the second line with <link target="_self" id="1">link</link></line>
    <line>This is the third line.</line>
  </paragraph>
  <paragraph>
    <ul class="foo">
       <li>List item 1</li>
       <li>List item 2</li>
    </ul>
  </paragraph>
  <paragraph>
    <ol>
       <li>Ordered list item 1</li>
       <li>ordered list item 2</li>
    </ol>
  </paragraph>
  <paragraph>
    <line>Paragraph can have both inline custom tag <custom name="myInlineTag">text</custom> and block custom tag</line>
    <custom name="myBlockTag">
      <paragraph>
        block text
      </paragraph>
    </custom>
  </paragraph>
  <paragraph>
    Paragraph can have image object with link <object id="55" size="large" align="center" image:ezurl_id="4" />
  </paragraph>
  <paragraph>
    You can use literal tag to write html code if you have done some changes in override system.
    <literal class="html">&lt;font color=&quot;red&quot;&gt;red text&lt;/font&gt;</literal>
  </paragraph>
  <header>This is a level two header</header>
  </section>
</section>

\endcode

*/

include_once( "kernel/classes/ezdatatype.php" );
include_once( "lib/ezxml/classes/ezxml.php" );
include_once( "kernel/common/template.php" );
include_once( 'lib/eztemplate/classes/eztemplateincludefunction.php' );
include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );
include_once( 'kernel/classes/datatypes/ezurl/ezurlobjectlink.php' );
include_once( "lib/ezutils/classes/ezini.php" );


define( "EZ_DATATYPESTRING_XML_TEXT", "ezxmltext" );
define( 'EZ_DATATYPESTRING_XML_TEXT_COLS_FIELD', 'data_int1' );
define( 'EZ_DATATYPESTRING_XML_TEXT_COLS_VARIABLE', '_ezxmltext_cols_' );

// The timestamp of the format for eZ publish 3.0.
define( 'EZ_XMLTEXT_VERSION_30_TIMESTAMP', 1045487555 );
// Contains the timestamp of the current xml format, if the stored
// timestamp is less than this it needs to be upgraded until it is correct.
define( 'EZ_XMLTEXT_VERSION_TIMESTAMP', EZ_XMLTEXT_VERSION_30_TIMESTAMP );

class eZXMLTextType extends eZDataType
{
    function eZXMLTextType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_XML_TEXT, ezi18n( 'kernel/classes/datatypes', "XML block", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     Set class attribute value for template version
    */
    function initializeClassAttribute( &$classAttribute )
    {
        if ( $classAttribute->attribute( EZ_DATATYPESTRING_XML_TEXT_COLS_FIELD ) == null )
            $classAttribute->setAttribute( EZ_DATATYPESTRING_XML_TEXT_COLS_FIELD, 10 );
        $classAttribute->store();
    }

    /*!
     Sets the default value.
    */
    function initializeObjectAttribute( &$contentObjectAttribute, $currentVersion, &$originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $xmlText = eZXMLTextType::rawXMLText( $originalContentObjectAttribute );
            $contentObjectAttribute->setAttribute( "data_text", $xmlText );
        }
        else
        {
            include_once( 'kernel/classes/datatypes/ezxmltext/ezxmlinputparser.php' );
            $parser = new eZXMLInputParser;
            $doc = $parser->createRootNode();
            $xmlText = eZXMLTextType::domString( $doc );
            $contentObjectAttribute->setAttribute( "data_text", $xmlText );
        }
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        /// Get object for input validation
        // To do: only validate, not save data
        $xmlText =& $this->objectAttributeContent( $contentObjectAttribute );
        $input =& $xmlText->attribute( 'input' );
        $isValid = $input->validateInput( $http, $base, $contentObjectAttribute );

        return $isValid;
    }

    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        $column = $base . EZ_DATATYPESTRING_XML_TEXT_COLS_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $column ) )
        {
            $columnValue = $http->postVariable( $column );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_XML_TEXT_COLS_FIELD,  $columnValue );
            return true;
        }
        return false;
    }

    /*!
     Fetches the http post var string input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        // To do: Data should be saved here.
        /*$xmlText =& $this->objectAttributeContent( $contentObjectAttribute );
        $input =& $xmlText->attribute( 'input' );
        $isValid = $input->validateInput( $http, $base, $contentObjectAttribute );*/
        return true;
    }

    /*!
     Initializes the object attribute with some data after object attribute is already stored.
     It means that for initial version you allready have an attribute_id and you can store data somewhere using this id.
     \note Default implementation does nothing.
    */
    function postInitializeObjectAttribute( &$objectAttribute, $currentVersion, &$originalContentObjectAttribute )
    {
    }

    /*!
     Store the content.
    */
    function storeObjectAttribute( &$attribute )
    {
        $attribute->setAttribute( 'data_int', EZ_XMLTEXT_VERSION_TIMESTAMP );
    }

    /*!
     \reimp
    */
    function &viewTemplate( &$contentobjectAttribute )
    {
        $template = $this->DataTypeString;
        $suffix = $this->viewTemplateSuffix( $contentobjectAttribute );
        if ( $suffix )
            $template .= '_' . $suffix;
        return $template;
    }

    /*!
     \reimp
    */
    function &editTemplate( &$contentobjectAttribute )
    {
        $template = $this->DataTypeString;
        $suffix = $this->editTemplateSuffix( $contentobjectAttribute );
        if ( $suffix )
            $template .= '_' . $suffix;
        return $template;
    }

    /*!
     \reimp
    */
    function &informationTemplate( &$contentobjectAttribute )
    {
        $template = $this->DataTypeString;
        $suffix = $this->informationTemplateSuffix( $contentobjectAttribute );
        if ( $suffix )
            $template .= '_' . $suffix;
        return $template;
    }

    /*!
     \reimp
    */
    function &viewTemplateSuffix( &$contentobjectAttribute )
    {
        $content =& $this->objectAttributeContent( $contentobjectAttribute );
        $outputHandler =& $content->attribute( 'output' );
        $suffix = $outputHandler->viewTemplateSuffix( $contentobjectAttribute );
        return $suffix;
    }

    /*!
     \reimp
    */
    function &editTemplateSuffix( &$contentobjectAttribute )
    {
        $content =& $this->objectAttributeContent( $contentobjectAttribute );
        $inputHandler =& $content->attribute( 'input' );
        $suffix =& $inputHandler->editTemplateSuffix( $contentobjectAttribute );
        return $suffix;
    }

    /*!
     \reimp
    */
    function &informationTemplateSuffix( &$contentobjectAttribute )
    {
        $content =& $this->objectAttributeContent( $contentobjectAttribute );
        $inputHandler =& $content->attribute( 'input' );
        $suffix =& $inputHandler->informationTemplateSuffix( $contentobjectAttribute );
        return $suffix;
    }

    /*!
     \return the RAW XML text from the attribute \a $contentobjectAttribute.
             If the XML format is older than the current one it will
             be upgraded to the current before being returned.
    */
    function rawXMLText( &$contentObjectAttribute )
    {
        $text = $contentObjectAttribute->attribute( 'data_text' );
        $timestamp = $contentObjectAttribute->attribute( 'data_int' );
        if ( $timestamp < EZ_XMLTEXT_VERSION_30_TIMESTAMP )
        {
            include_once( 'lib/ezi18n/classes/eztextcodec.php' );
            $charset = 'UTF-8';
            $codec =& eZTextCodec::instance( false, $charset );
            $text = $codec->convertString( $text );
            $timestamp = EZ_XMLTEXT_VERSION_30_TIMESTAMP;
        }
        return $text;
    }

    /*!
     \static
     \return the XML structure in \a $domDocument as text.
             It will take of care of the necessary charset conversions
             for content storage.
    */
    function domString( &$domDocument )
    {
        $ini =& eZINI::instance();
        $xmlCharset = $ini->variable( 'RegionalSettings', 'ContentXMLCharset' );
        if ( $xmlCharset == 'enabled' )
        {
            include_once( 'lib/ezi18n/classes/eztextcodec.php' );
            $charset = eZTextCodec::internalCharset();
        }
        else if ( $xmlCharset == 'disabled' )
            $charset = true;
        else
            $charset = $xmlCharset;
        if ( $charset !== true )
        {
            include_once( 'lib/ezi18n/classes/ezcharsetinfo.php' );
            $charset = eZCharsetInfo::realCharsetCode( $charset );
        }
        $domString = $domDocument->toString( $charset );

        $eZXMLini =& eZINI::instance( 'ezxml.ini' );
        if ( $eZXMLini->hasVariable( 'InputSettings', 'AllowNumericEntities' ) )
        {
            if ( $eZXMLini->variable( 'InputSettings', 'AllowNumericEntities' ) == 'true' )
            {
                $domString = preg_replace( '/&amp;#([0-9]+);/', '&#\1;', $domString );
            }
        }

        return $domString;
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        include_once( 'kernel/classes/datatypes/ezxmltext/ezxmltext.php' );
        $xmlText = new eZXMLText( eZXMLTextType::rawXMLText( $contentObjectAttribute ), $contentObjectAttribute );
        return $xmlText;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        $metaData = "";

        $xml = new eZXML();
        $dom =& $xml->domTree( eZXMLTextType::rawXMLText( $contentObjectAttribute ) );

        if ( $dom )
        {
            $textNodes = $dom->elementsByName( "#text" );
            if ( is_array( $textNodes ) )
            {
                foreach ( $textNodes as $node )
                {
                    $metaData .= " " . $node->content();
                }
            }
        }
        return $metaData;
    }
    /*!
     \return string representation of an contentobjectattribute data for simplified export

    */
    function toString( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    function fromString( &$contentObjectAttribute, $string )
    {
        return $contentObjectAttribute->setAttribute( 'data_text', $string );
    }


    /*!
     Returns the text.
    */
    function title( &$contentObjectAttribute )
    {
        $text = eZXMLTextType::rawXMLText( $contentObjectAttribute );
        // parse xml
        include_once( 'lib/ezxml/classes/ezxml.php' );
        $xml = new eZXML();
        $document =& $xml->domTree( $text );

        // Get first text element of xml
        if ( !$document )
            return $text;

        $root =& $document->root();
        $section =& $root->firstChild();
        $textDom = false;
        if ( $section )
            $textDom =& $section->firstChild();

        if ( $textDom and $textDom->hasChildren() )
        {
            $textDomContent =& $textDom->firstChild();
            $text = $textDomContent->content();
        }
        elseif ( $textDom )
        {
            $text = $textDom->content();
        }

        return $text;
    }

    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        $content = $this->objectAttributeContent( $contentObjectAttribute );
        if ( is_object( $content ) and
             !$content->attribute( 'is_empty' ) )
            return true;
        return false;
    }

    /*!
     \reimp
    */
    function isIndexable()
    {
        return true;
    }

    /*!
     \reimp
    */
    function isInformationCollector()
    {
        return false;
    }

    /*!
     \reimp
     Makes sure content/datatype/.../ezxmltags/... are included.
    */
    function templateList()
    {
        return array( array( 'regexp',
                             '#^content/datatype/[a-zA-Z]+/ezxmltags/#' ) );
    }

    /*!
     \reimp
    */
    function serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $textColumns = $classAttribute->attribute( EZ_DATATYPESTRING_XML_TEXT_COLS_FIELD );
        $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'text-column-count', $textColumns ) );
    }

    /*!
     \reimp
    */
    function unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $textColumns = $attributeParametersNode->elementTextContentByName( 'text-column-count' );
        $classAttribute->setAttribute( EZ_DATATYPESTRING_XML_TEXT_COLS_FIELD, $textColumns );
    }

    /*!
    */
    function customObjectAttributeHTTPAction( $http, $action, &$contentObjectAttribute )
    {
        $content =& $this->objectAttributeContent( $contentObjectAttribute );
        $inputHandler =& $content->attribute( 'input' );
        $inputHandler->customObjectAttributeHTTPAction( $http, $action, $contentObjectAttribute );
    }

    /*!
     \reimp
     \return a DOM representation of the content object attribute
    */
    function serializeContentObjectAttribute( &$package, &$objectAttribute )
    {
        include_once( 'lib/ezxml/classes/ezxml.php' );

        $DOMNode = $this->createContentObjectAttributeDOMNode( $objectAttribute );

        $xml = new eZXML();
        $doc =& $xml->domTree( $objectAttribute->attribute( 'data_text' ) );

        if ( $doc )
        {
            /* For all links found in the XML, do the following:
             * - add "href" attribute fetching it from ezurl table.
             * - remove "id" attribute.
             */
            {
                include_once( 'kernel/classes/datatypes/ezurl/ezurlobjectlink.php' );
                include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );

                $links =& $doc->elementsByName( 'link' );
                $embeds =& $doc->elementsByName( 'embed' );
                $objects =& $doc->elementsByName( 'object' );
                $embedsInline =& $doc->elementsByName( 'embed-inline' );

                $allTags = array_merge( $links, $embeds, $embedsInline, $objects );

                if ( is_array( $allTags ) )
                {
                    foreach ( array_keys( $allTags ) as $index )
                    {
                        $tag =& $allTags[$index];
                        $linkID = $tag->getAttribute( 'url_id' );
                        if ( $tag->nodeName == 'object' )
                            $objectID = $tag->getAttribute( 'id' );
                        else
                            $objectID = $tag->getAttribute( 'object_id' );
                        $nodeID = $tag->getAttribute( 'node_id' );
                        if ( $linkID )
                        {
                            $urlObj = eZURL::fetch( $linkID );
                            if ( !$urlObj ) // an error occured
                                continue;
                            $url =& $urlObj->attribute( 'url' );
                            $tag->setAttribute( 'href', $url );
                            $tag->removeAttribute( 'url_id' );
                            unset( $urlObj );
                        }
                        elseif ( $objectID )
                        {
                            $object = eZContentObject::fetch( $objectID, false );
                            if ( is_array( $object ) )
                                $tag->setAttribute( 'object_remote_id', $object['remote_id'] );

                            if ( $tag->nodeName == 'object' )
                                $tag->removeAttribute( 'id' );
                            else
                                $tag->removeAttribute( 'object_id' );
                        }
                        elseif ( $nodeID )
                        {
                            $node = eZContentObjectTreeNode::fetch( $nodeID, false, false );
                            if ( is_array( $node ) )
                                $tag->setAttribute( 'node_remote_id', $node['remote_id'] );
                            $tag->removeAttribute( 'node_id' );
                        }
                    }
                }
            }

            $DOMNode->appendChild( $doc->root() );
        }

        return $DOMNode;
    }

    /*!
     \reimp
     \param contentobject attribute object
     \param ezdomnode object
    */
    function unserializeContentObjectAttribute( &$package, &$objectAttribute, $attributeNode )
    {
        $rootNode = $attributeNode->firstChild();
        if ( $rootNode )
        {

            /* For all links found in the XML, do the following:
             * Search for url specified in 'href' link attribute (in ezurl table).
             * If the url not found then create a new one.
             * Then associate the found (or created) URL with the object attribute by creating new url-object link.
             * After that, remove "href" attribute, add new "id" attribute.
             * This new 'id' will always refer to the existing url object.
             */
            include_once( 'kernel/classes/datatypes/ezurl/ezurlobjectlink.php' );
            include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );

            $xml = new eZXML();
            $domDocument =& $xml->domTree( $rootNode->toString( 0 ), array ( 'CharsetConversion' => false ) );

            if ( $domDocument )
            {
                $links =& $domDocument->elementsByName( 'link' );
                if ( !is_array( $links ) )
                    $links = array();

                foreach ( array_keys( $links ) as $index )
                {
                    $linkRef =& $links[$index];
                    $href = $linkRef->attributeValue( 'href' );
                    if ( !$href )
                        continue;
                    $urlObj = eZURL::urlByURL( $href );

                    if ( !$urlObj )
                    {
                        $urlObj = eZURL::create( $href );
                        $urlObj->store();
                    }

                    $linkRef->remove_attribute( 'href' );
                    $linkRef->set_attribute( 'url_id', $urlObj->attribute( 'id' ) );
                    $urlObjectLink = eZURLObjectLink::create( $urlObj->attribute( 'id' ),
                                                              $objectAttribute->attribute( 'id' ),
                                                              $objectAttribute->attribute( 'version' ) );
                    $urlObjectLink->store();

                }
                $objectAttribute->setAttribute( 'data_text', eZXMLTextType::domString( $domDocument ) );
                $domDocument->cleanup();
            }
        }
    }

    function postUnserializeContentObjectAttribute( &$package, &$objectAttribute )
    {
        $xmlString = $objectAttribute->attribute( 'data_text' );
        $xml = new eZXML();
        $doc =& $xml->domTree( $xmlString );

        if ( !is_object( $doc ) )
            return false;

        $links =& $doc->elementsByName( 'link' );
        $objects =& $doc->elementsByName( 'object' );
        $embeds =& $doc->elementsByName( 'embed' );
        $embedsInline =& $doc->elementsByName( 'embed-inline' );

        $allTags = array_merge( $links, $embeds, $embedsInline, $objects );
        $modified = false;

        $contentObject =& $objectAttribute->attribute( 'object' );
        foreach( array_keys( $allTags ) as $key )
        {
            $tag =& $allTags[$key];

            $objectRemoteID = $tag->getAttribute( 'object_remote_id' );
            $nodeRemoteID = $tag->getAttribute( 'node_remote_id' );
            if ( $objectRemoteID )
            {
                $objectArray = eZContentObject::fetchByRemoteID( $objectRemoteID, false );
                if ( !is_array( $objectArray ) )
                {
                    eZDebug::writeWarning( "Can't fetch object with remoteID = $objectRemoteID", 'eZXMLTextType::unserialize' );
                    continue;
                }

                $objectID = $objectArray['id'];
                if ( $tag->nodeName == 'object' )
                    $tag->setAttribute( 'id', $objectID );
                else
                    $tag->setAttribute( 'object_id', $objectID );
                $tag->removeAttribute( 'object_remote_id' );
                $modified = true;

                // add as related object
                if ( $contentObject )
                {
                    $relationType = $tag->nodeName == 'link' ? EZ_CONTENT_OBJECT_RELATION_LINK : EZ_CONTENT_OBJECT_RELATION_EMBED;
                    $contentObject->addContentObjectRelation( $objectID, $objectAttribute->attribute( 'version' ), false, 0, $relationType );
                }
            }
            elseif ( $nodeRemoteID )
            {
                $nodeArray = eZContentObjectTreeNode::fetchByRemoteID( $nodeRemoteID, false );
                if ( !is_array( $nodeArray ) )
                {
                    eZDebug::writeWarning( "Can't fetch node with remoteID = $nodeRemoteID", 'eZXMLTextType::unserialize' );
                    continue;
                }

                $nodeID = $nodeArray['node_id'];
                $tag->setAttribute( 'node_id', $nodeID );
                $tag->removeAttribute( 'node_remote_id' );
                $modified = true;

                // add as related object
                if ( $contentObject )
                {
                    $node = eZContentObjectTreeNode::fetch( $nodeID );
                    if ( $node )
                    {
                        $relationType = $tag->nodeName == 'link' ? EZ_CONTENT_OBJECT_RELATION_LINK : EZ_CONTENT_OBJECT_RELATION_EMBED;
                        $contentObject->addContentObjectRelation( $node->attribute( 'contentobject_id' ), $objectAttribute->attribute( 'version' ), false, 0, $relationType );
                    }
                }
            }
        }

        if ( $modified )
        {
            $objectAttribute->setAttribute( 'data_text', eZXMLTextType::domString( $doc ) );
            return true;
        }
        else
        {
            return false;
        }
    }
    /*!
     Delete stored object attribute, this will clean up the ezurls and ezobjectlinks
    */
    function deleteStoredObjectAttribute( &$contentObjectAttribute, $version = null )
    {
        $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );

        $db =& eZDB::instance();

        /* First we remove the link between the keyword and the object
         * attribute to be removed */
        include_once( 'kernel/classes/datatypes/ezurl/ezurlobjectlink.php' );
        if ( $version == null )
        {
            eZPersistentObject::removeObject( eZURLObjectLink::definition(),
                                              array( 'contentobject_attribute_id' => $contentObjectAttributeID ) );

        }
        else
        {
            eZPersistentObject::removeObject( eZURLObjectLink::definition(),
                                              array( 'contentobject_attribute_id' => $contentObjectAttributeID,
                                                     'contentobject_attribute_version' => $version ) );
        }

        /* Here we figure out which which URLs are not in use at all */
        if ( $db->databaseName() == 'oracle' )
        {
            $res = $db->arrayQuery( "SELECT DISTINCT id
                                     FROM ezurl, ezurl_object_link
                                     WHERE ezurl.id = ezurl_object_link.url_id(+)
                                         AND url_id IS NULL" );
        }
        else
        {
            $res = $db->arrayQuery(" SELECT DISTINCT id
                                     FROM ezurl LEFT JOIN ezurl_object_link ON (ezurl.id  = ezurl_object_link.url_id)
                                     WHERE url_id IS NULL" );
        }

        /* And if there are some, we delete them */
        if ( count( $res ) )
        {
            $unusedUrlIDs = array();
            foreach ( $res as $record )
                $unusedUrlIDs[] = $record['id'];
            $unusedUrlIDString = implode( ', ', $unusedUrlIDs );

            $db->query( "DELETE FROM ezurl WHERE id IN ($unusedUrlIDString)" );
        }
    }

    /*!
      \reimp
    */
    function diff( $old, $new, $options = false )
    {
        include_once( 'lib/ezdiff/classes/ezdiff.php' );
        $diff = new eZDiff();
        $diff->setDiffEngineType( $diff->engineType( 'xml' ) );
        $diff->initDiffEngine();
        $diffObject = $diff->diff( $old, $new );
        return $diffObject;
    }

}

eZDataType::register( EZ_DATATYPESTRING_XML_TEXT, "ezXMLTextType" );

?>
