<?php
/**
 * File containing the eZXMLTextType class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

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

class eZXMLTextType extends eZDataType
{
    const DATA_TYPE_STRING = "ezxmltext";
    const COLS_FIELD = 'data_int1';
    const COLS_VARIABLE = '_ezxmltext_cols_';

    // Tag support preset
    const TAG_PRESET_FIELD = 'data_text2';
    const TAG_PRESET_VARIABLE = '_ezxmltext_tagpreset_';

    // The timestamp of the format for eZ Publish 3.0.
    const VERSION_30_TIMESTAMP = 1045487555;
    // Contains the timestamp of the current xml format, if the stored
    // timestamp is less than this it needs to be upgraded until it is correct.
    const VERSION_TIMESTAMP = 1045487555; // AS 21-09-2007: should be the same as VERSION_30_TIMESTAMP

    function eZXMLTextType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezpI18n::tr( 'kernel/classes/datatypes', "XML block", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     Set class attribute value for template version
    */
    function initializeClassAttribute( $classAttribute )
    {
        if ( $classAttribute->attribute( self::COLS_FIELD ) == null )
            $classAttribute->setAttribute( self::COLS_FIELD, 10 );
        $classAttribute->store();
    }

    /*!
     Sets the default value.
    */
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $xmlText = eZXMLTextType::rawXMLText( $originalContentObjectAttribute );
            $contentObjectAttribute->setAttribute( "data_text", $xmlText );
        }
        else
        {
            $parser = new eZXMLInputParser();
            $doc = $parser->createRootNode();
            $xmlText = eZXMLTextType::domString( $doc );
            $contentObjectAttribute->setAttribute( "data_text", $xmlText );
        }
    }

    /**
     * Method triggered on publish for xml text datatype
     *
     * This method makes sure that links from all translations of an xml text
     * are registered in the ezurl_object_link table, and thus retained, if
     * previous versions of an object are removed.
     *
     * It also checks for embedded objects in other languages xml, and makes
     * sure the matching object relations are stored for the publish version.
     *
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @param eZContentObject $object
     * @param array $publishedNodes
     * @return boolean
     */
    function onPublish( $contentObjectAttribute, $object, $publishedNodes )
    {
        $currentVersion = $object->currentVersion();
        $langMask = $currentVersion->attribute( 'language_mask' );

        // We find all translations present in the current version. We calculate
        // this from the language mask already present in the fetched version,
        // so no further round-trip to the DB is required.
        $languageList = eZContentLanguage::decodeLanguageMask( $langMask, true );
        $languageList = $languageList['language_list'];

        // We want to have the class attribute identifier of the attribute
        // containing the current ezxmltext, as we then can use the more efficient
        // eZContentObject->fetchAttributesByIdentifier() to get the data
        $identifier = $contentObjectAttribute->attribute( 'contentclass_attribute_identifier' );

        $attributeArray = $object->fetchAttributesByIdentifier( array( $identifier ),
                                                                $currentVersion->attribute( 'version' ),
                                                                $languageList );

        foreach ( $attributeArray as $attribute )
        {
            $xmlText = eZXMLTextType::rawXMLText( $attribute );

            $dom = new DOMDocument( '1.0', 'utf-8' );
            if ( !$dom->loadXML( $xmlText ) )
                continue;

            // urls
            $urlIdArray = array();
            foreach ( $dom->getElementsByTagName( 'link' ) as $link )
            {
                // We are looking for external 'http://'-style links, not the internal
                // object or node links.
                if ( $link->hasAttribute( 'url_id' ) )
                {
                    $urlIdArray[] = $link->getAttribute( 'url_id' );
                }
            }

            if ( count( $urlIdArray ) > 0 )
            {
                eZSimplifiedXMLInput::updateUrlObjectLinks( $attribute, $urlIdArray );
            }

            // linked objects
            $linkedObjectIdArray = $this->getRelatedObjectList( $dom->getElementsByTagName( 'link' ) );

            // embedded objects
            $embeddedObjectIdArray = array_merge(
                $this->getRelatedObjectList( $dom->getElementsByTagName( 'embed' ) ),
                $this->getRelatedObjectList( $dom->getElementsByTagName( 'embed-inline' ) )
            );

            if ( !empty( $embeddedObjectIdArray ) )
            {
                $object->appendInputRelationList( $embeddedObjectIdArray, eZContentObject::RELATION_EMBED );
            }

            if ( !empty( $linkedObjectIdArray ) )
            {
                $object->appendInputRelationList( $linkedObjectIdArray, eZContentObject::RELATION_LINK );
            }
            if ( !empty( $linkedObjectIdArray ) || !empty( $embeddedObjectIdArray ) )
            {
                $object->commitInputRelations( $currentVersion->attribute( 'version' ) );
            }

        }
    }

    /**
     * Extracts ids of embedded/linked objects in an eZXML DOMNodeList
     * @param DOMNodeList $domNodeList
     * @return array
     */
    private function getRelatedObjectList( DOMNodeList $domNodeList )
    {
        $embeddedObjectIdArray = array();
        foreach( $domNodeList as $embed )
        {
            if ( $embed->hasAttribute( 'object_id' ) )
            {
                $embeddedObjectIdArray[] = $embed->getAttribute( 'object_id' );
            }
            elseif ( $embed->hasAttribute( 'node_id' ) )
            {
                if ( $object = eZContentObject::fetchByNodeID( $embed->getAttribute( 'node_id' ) ) )
                {
                    $embeddedObjectIdArray[] = $object->attribute( 'id' );
                }
            }
        }
        return $embeddedObjectIdArray;
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        /// Get object for input validation
        // To do: only validate, not save data
        $xmlText = $this->objectAttributeContent( $contentObjectAttribute );
        $input = $xmlText->attribute( 'input' );
        $isValid = $input->validateInput( $http, $base, $contentObjectAttribute );

        return $isValid;
    }

    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $column = $base . self::COLS_VARIABLE . $classAttribute->attribute( 'id' );
        $tagPreset = $base . self::TAG_PRESET_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $column ) )
        {
            $columnValue = $http->postVariable( $column );
            $classAttribute->setAttribute( self::COLS_FIELD,  $columnValue );
            if ( $http->hasPostVariable( $tagPreset ) )
            {
                $tagPresetValue = $http->postVariable( $tagPreset );
                $classAttribute->setAttribute( self::TAG_PRESET_FIELD, $tagPresetValue );
            }
            return true;
        }
        return false;
    }

    /*!
     Fetches the http post var string input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        // To do: Data should be saved here.
        /*$xmlText = $this->objectAttributeContent( $contentObjectAttribute );
        $input = $xmlText->attribute( 'input' );
        $isValid = $input->validateInput( $http, $base, $contentObjectAttribute );*/
        return true;
    }

    /*!
     Initializes the object attribute with some data after object attribute is already stored.
     It means that for initial version you allready have an attribute_id and you can store data somewhere using this id.
     \note Default implementation does nothing.
    */
    function postInitializeObjectAttribute( $objectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
    }

    /*!
     Store the content.
    */
    function storeObjectAttribute( $attribute )
    {
        $attribute->setAttribute( 'data_int', self::VERSION_TIMESTAMP );
    }

    function viewTemplate( $contentobjectAttribute )
    {
        $template = $this->DataTypeString;
        $suffix = $this->viewTemplateSuffix( $contentobjectAttribute );
        if ( $suffix )
        {
            $template .= '_' . $suffix;
        }
        return $template;
    }

    function editTemplate( $contentobjectAttribute )
    {
        $template = $this->DataTypeString;
        $suffix = $this->editTemplateSuffix( $contentobjectAttribute );
        if ( $suffix )
            $template .= '_' . $suffix;
        return $template;
    }

    function informationTemplate( $contentobjectAttribute )
    {
        $template = $this->DataTypeString;
        $suffix = $this->informationTemplateSuffix( $contentobjectAttribute );
        if ( $suffix )
            $template .= '_' . $suffix;
        return $template;
    }

    function viewTemplateSuffix( &$contentobjectAttribute )
    {
        $content = $this->objectAttributeContent( $contentobjectAttribute );
        $outputHandler = $content->attribute( 'output' );
        return $outputHandler->viewTemplateSuffix( $contentobjectAttribute );
    }

    function editTemplateSuffix( &$contentobjectAttribute )
    {
        $content = $this->objectAttributeContent( $contentobjectAttribute );
        $inputHandler = $content->attribute( 'input' );
        return $inputHandler->editTemplateSuffix( $contentobjectAttribute );
    }

    function informationTemplateSuffix( &$contentobjectAttribute )
    {
        $content = $this->objectAttributeContent( $contentobjectAttribute );
        $inputHandler = $content->attribute( 'input' );
        return $inputHandler->informationTemplateSuffix( $contentobjectAttribute );
    }

    /*!
     \return the RAW XML text from the attribute \a $contentobjectAttribute.
             If the XML format is older than the current one it will
             be upgraded to the current before being returned.
    */
    static function rawXMLText( $contentObjectAttribute )
    {
        $text = $contentObjectAttribute->attribute( 'data_text' );
        $timestamp = $contentObjectAttribute->attribute( 'data_int' );
        if ( $timestamp < self::VERSION_30_TIMESTAMP )
        {
            $charset = 'UTF-8';
            $codec = eZTextCodec::instance( false, $charset );
            $text = $codec->convertString( $text );
            $timestamp = self::VERSION_30_TIMESTAMP;
        }
        return $text;
    }

    /*!
     \static
     \return the XML structure in \a $domDocument as text.
             It will take of care of the necessary charset conversions
             for content storage.
    */
    static function domString( $domDocument )
    {
        return $domDocument->saveXML();
    }

    /*!
     Returns the content.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        $xmlText = new eZXMLText( eZXMLTextType::rawXMLText( $contentObjectAttribute ), $contentObjectAttribute );
        return $xmlText;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        $metaData = "";

        $dom = new DOMDocument( '1.0', 'utf-8' );
        $text = eZXMLTextType::rawXMLText( $contentObjectAttribute );
        if ( trim( $text ) == '' )
        {
            return $metaData;
        }
        $success = $dom->loadXML( $text );

        if ( $success )
        {
            $metaData = trim( eZXMLTextType::concatTextContent( $dom->documentElement ) );
        }
        return $metaData;
    }

    /**
     * Recursively drills down in the xml tree in $node and
     * concatenates text content from the DOMNodes with a space
     * in-between to make sure search words from two different lines
     * are merged.
     *
     * @param DOMNode $node
     * @return string
     */
    static function concatTextContent( $node )
    {
        $retString = '';
        if ( !( $node instanceof DOMNode ) )
        {
            return $retString;
        }
        if ( $node->hasChildNodes() )
        {
            $childArray = $node->childNodes;
            foreach ( $childArray as $child )
            {
                $retString .= eZXMLTextType::concatTextContent( $child );
            }
        }
        elseif ( $node->nodeType === XML_TEXT_NODE )
        {
            return $node->textContent . ' ';
        }
        return $retString;
    }

    /*!
     \return string representation of an contentobjectattribute data for simplified export

    */
    function toString( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    function fromString( $contentObjectAttribute, $string )
    {
        return $contentObjectAttribute->setAttribute( 'data_text', $string );
    }


    /*!
     Returns the text.
    */
    function title( $contentObjectAttribute, $value = null )
    {
        $text = eZXMLTextType::rawXMLText( $contentObjectAttribute );

        $dom = new DOMDocument( '1.0', 'utf-8' );
        $success = $dom->loadXML( $text );

        // Get first text element of xml
        if ( !$success )
        {
            return $text;
        }

        $root = $dom->documentElement;
        $section = $root->firstChild;
        $textDom = false;
        if ( $section )
        {
            $textDom = $section->firstChild;
        }

        if ( $textDom && $textDom->hasChildNodes() )
        {
            $text = $textDom->firstChild->textContent;
        }
        elseif ( $textDom )
        {
            $text = $textDom->textContent;
        }

        return $text;
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        $content = $this->objectAttributeContent( $contentObjectAttribute );
        if ( is_object( $content ) and
             !$content->attribute( 'is_empty' ) )
            return true;
        return false;
    }

    function isIndexable()
    {
        return true;
    }

    function isInformationCollector()
    {
        return false;
    }

    /*!
     Makes sure content/datatype/.../ezxmltags/... are included.
    */
    function templateList()
    {
        return array( array( 'regexp',
                             '#^content/datatype/[a-zA-Z]+/ezxmltags/#' ) );
    }

    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $textColumns = $classAttribute->attribute( self::COLS_FIELD );
        $dom = $attributeParametersNode->ownerDocument;
        $textColumnCountNode = $dom->createElement( 'text-column-count' );
        $textColumnCountNode->appendChild( $dom->createTextNode( $textColumns ) );
        $attributeParametersNode->appendChild( $textColumnCountNode );
    }

    function unserializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $textColumns = $attributeParametersNode->getElementsByTagName( 'text-column-count' )->item( 0 )->textContent;
        $classAttribute->setAttribute( self::COLS_FIELD, $textColumns );
    }

    function customObjectAttributeHTTPAction( $http, $action, $contentObjectAttribute, $parameters )
    {
        $content = $this->objectAttributeContent( $contentObjectAttribute );
        $inputHandler = $content->attribute( 'input' );
        $inputHandler->customObjectAttributeHTTPAction( $http, $action, $contentObjectAttribute );
    }

    /*!
     \return a DOM representation of the content object attribute
    */
    function serializeContentObjectAttribute( $package, $objectAttribute )
    {

        $DOMNode = $this->createContentObjectAttributeDOMNode( $objectAttribute );
        $xmlString = $objectAttribute->attribute( 'data_text' );

        if ( $xmlString != '' )
        {
            $doc = new DOMDocument( '1.0', 'utf-8' );
            $success = $doc->loadXML( $xmlString );

            /* For all links found in the XML, do the following:
             * - add "href" attribute fetching it from ezurl table.
             * - remove "id" attribute.
             */

            $links = $doc->getElementsByTagName( 'link' );
            $embeds = $doc->getElementsByTagName( 'embed' );
            $objects = $doc->getElementsByTagName( 'object' );
            $embedsInline = $doc->getElementsByTagName( 'embed-inline' );

            eZXMLTextType::transformLinksToRemoteLinks( $links );
            eZXMLTextType::transformLinksToRemoteLinks( $embeds );
            eZXMLTextType::transformLinksToRemoteLinks( $objects );
            eZXMLTextType::transformLinksToRemoteLinks( $embedsInline );

            $importedRootNode = $DOMNode->ownerDocument->importNode( $doc->documentElement, true );
            $DOMNode->appendChild( $importedRootNode );
        }

        return $DOMNode;
    }

    static function transformLinksToRemoteLinks( DOMNodeList $nodeList )
    {
        foreach ( $nodeList as $node )
        {
            $linkID = $node->getAttribute( 'url_id' );
            $isObject = ( $node->localName == 'object' );
            $objectID = $isObject ? $node->getAttribute( 'id' ) : $node->getAttribute( 'object_id' );
            $nodeID = $node->getAttribute( 'node_id' );

            if ( $linkID )
            {
                $urlObj = eZURL::fetch( $linkID );
                if ( !$urlObj ) // an error occured
                {
                    continue;
                }
                $url = $urlObj->attribute( 'url' );
                $node->setAttribute( 'href', $url );
                $node->removeAttribute( 'url_id' );
            }
            elseif ( $objectID )
            {
                $object = eZContentObject::fetch( $objectID, false );
                if ( is_array( $object ) )
                {
                    $node->setAttribute( 'object_remote_id', $object['remote_id'] );
                }

                if ( $isObject )
                {
                    $node->removeAttribute( 'id' );
                }
                else
                {
                    $node->removeAttribute( 'object_id' );
                }
            }
            elseif ( $nodeID )
            {
                $nodeData = eZContentObjectTreeNode::fetch( $nodeID, false, false );
                if ( is_array( $nodeData ) )
                {
                    $node->setAttribute( 'node_remote_id', $nodeData['remote_id'] );
                }
                $node->removeAttribute( 'node_id' );
            }
        }
    }

    /*!
     \param contentobject attribute object
     \param domnode object
    */
    function unserializeContentObjectAttribute( $package, $objectAttribute, $attributeNode )
    {
        /* For all links found in the XML, do the following:
         * Search for url specified in 'href' link attribute (in ezurl table).
         * If the url not found then create a new one.
         * Then associate the found (or created) URL with the object attribute by creating new url-object link.
         * After that, remove "href" attribute, add new "id" attribute.
         * This new 'id' will always refer to the existing url object.
         */
        $linkNodes = $attributeNode->getElementsByTagName( 'link' );

        foreach ( $linkNodes as $linkNode )
        {
            $href = $linkNode->getAttribute( 'href' );
            if ( !$href )
                continue;
            $urlObj = eZURL::urlByURL( $href );

            if ( !$urlObj )
            {
                $urlObj = eZURL::create( $href );
                $urlObj->store();
            }

            $linkNode->removeAttribute( 'href' );
            $linkNode->setAttribute( 'url_id', $urlObj->attribute( 'id' ) );
            $urlObjectLink = eZURLObjectLink::create( $urlObj->attribute( 'id' ),
                                                      $objectAttribute->attribute( 'id' ),
                                                      $objectAttribute->attribute( 'version' ) );
            $urlObjectLink->store();
        }

        foreach ( $attributeNode->childNodes as $childNode )
        {
            if ( $childNode->nodeType == XML_ELEMENT_NODE )
            {
                $xmlString = $childNode->ownerDocument->saveXML( $childNode );
                $objectAttribute->setAttribute( 'data_text', $xmlString );
                break;
            }
        }
    }

    function postUnserializeContentObjectAttribute( $package, $objectAttribute )
    {
        $xmlString = $objectAttribute->attribute( 'data_text' );
        $doc = new DOMDocument( '1.0', 'utf-8' );
        $success = $doc->loadXML( $xmlString );

        if ( !$success )
        {
            return false;
        }

        $links = $doc->getElementsByTagName( 'link' );
        $objects = $doc->getElementsByTagName( 'object' );
        $embeds = $doc->getElementsByTagName( 'embed' );
        $embedsInline = $doc->getElementsByTagName( 'embed-inline' );

        $modified = array();
        $modified[] = eZXMLTextType::transformRemoteLinksToLinks( $links, $objectAttribute );
        $modified[] = eZXMLTextType::transformRemoteLinksToLinks( $objects, $objectAttribute );
        $modified[] = eZXMLTextType::transformRemoteLinksToLinks( $embeds, $objectAttribute );
        $modified[] = eZXMLTextType::transformRemoteLinksToLinks( $embedsInline, $objectAttribute );

        if ( in_array( true, $modified ) )
        {
            $objectAttribute->setAttribute( 'data_text', eZXMLTextType::domString( $doc ) );
            return true;
        }
        else
        {
            return false;
        }
    }

    static function transformRemoteLinksToLinks( DOMNodeList $nodeList, $objectAttribute )
    {
        $modified = false;

        $contentObject = $objectAttribute->attribute( 'object' );
        foreach ( $nodeList as $node )
        {
            $objectRemoteID = $node->getAttribute( 'object_remote_id' );
            $nodeRemoteID = $node->getAttribute( 'node_remote_id' );
            if ( $objectRemoteID )
            {
                $objectArray = eZContentObject::fetchByRemoteID( $objectRemoteID, false );
                if ( !is_array( $objectArray ) )
                {
                    eZDebug::writeWarning( "Can't fetch object with remoteID = $objectRemoteID", __METHOD__ );
                    continue;
                }

                $objectID = $objectArray['id'];
                if ( $node->localName == 'object' )
                    $node->setAttribute( 'id', $objectID );
                else
                    $node->setAttribute( 'object_id', $objectID );
                $node->removeAttribute( 'object_remote_id' );
                $modified = true;

                // add as related object
                if ( $contentObject )
                {
                    $relationType = $node->localName == 'link' ? eZContentObject::RELATION_LINK : eZContentObject::RELATION_EMBED;
                    $contentObject->addContentObjectRelation( $objectID, $objectAttribute->attribute( 'version' ), 0, $relationType );
                }
            }
            elseif ( $nodeRemoteID )
            {
                $nodeArray = eZContentObjectTreeNode::fetchByRemoteID( $nodeRemoteID, false );
                if ( !is_array( $nodeArray ) )
                {
                    eZDebug::writeWarning( "Can't fetch node with remoteID = $nodeRemoteID", __METHOD__ );
                    continue;
                }

                $node->setAttribute( 'node_id', $nodeArray['node_id'] );
                $node->removeAttribute( 'node_remote_id' );
                $modified = true;

                // add as related object
                if ( $contentObject )
                {
                    $relationType = $node->nodeName == 'link' ? eZContentObject::RELATION_LINK : eZContentObject::RELATION_EMBED;
                    $contentObject->addContentObjectRelation( $nodeArray['id'], $objectAttribute->attribute( 'version' ), 0, $relationType );
                }
            }
        }

        return $modified;
    }

    /*!
     Delete stored object attribute, this will clean up the ezurls and ezobjectlinks
    */
    function deleteStoredObjectAttribute( $contentObjectAttribute, $version = null )
    {
        $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );

        $db = eZDB::instance();

        /* First we remove the link between the keyword and the object
         * attribute to be removed */
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

    function diff( $old, $new, $options = false )
    {
        $diff = new eZDiff();
        $diff->setDiffEngineType( $diff->engineType( 'xml' ) );
        $diff->initDiffEngine();
        $diffObject = $diff->diff( $old, $new );
        return $diffObject;
    }

    function supportsBatchInitializeObjectAttribute()
    {
        return true;
    }

    function batchInitializeObjectAttributeData( $classAttribute )
    {
        $parser = new eZXMLInputParser();
        $doc = $parser->createRootNode();
        $xmlText = eZXMLTextType::domString( $doc );
        $db = eZDB::instance();
        $xmlText = "'" . $db->escapeString( $xmlText ) . "'";
        return array( 'data_text' => $xmlText );
    }
}

?>
