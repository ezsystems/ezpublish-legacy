<?php
//
// Definition of eZXMLTextType class
//
// Created on: <06-May-2002 20:02:55 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \class eZXMLTextType
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
        $this->eZDataType( EZ_DATATYPESTRING_XML_TEXT, ezi18n( 'kernel/classes/datatypes', "XML Text field", 'Datatype name' ),
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
        return $outputHandler->viewTemplateSuffix( $contentobjectAttribute );
    }

    /*!
     \reimp
    */
    function &editTemplateSuffix( &$contentobjectAttribute )
    {
        $content =& $this->objectAttributeContent( $contentobjectAttribute );
        $inputHandler =& $content->attribute( 'input' );
        return $inputHandler->editTemplateSuffix( $contentobjectAttribute );
    }

    /*!
     \reimp
    */
    function &informationTemplateSuffix( &$contentobjectAttribute )
    {
        $content =& $this->objectAttributeContent( $contentobjectAttribute );
        $inputHandler =& $content->attribute( 'input' );
        return $inputHandler->editTemplateSuffix( $contentobjectAttribute );
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
            $text =& $codec->convertString( $text );
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
            $textNodes =& $dom->elementsByName( "#text" );
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
     Returns the text.
    */
    function title( &$contentObjectAttribute )
    {
        return eZXMLTextType::rawXMLText( $contentObjectAttribute );
    }

    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        $content =& $this->objectAttributeContent( $contentObjectAttribute );
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
    function &serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $textColumns = $classAttribute->attribute( EZ_DATATYPESTRING_XML_TEXT_COLS_FIELD );
        $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'text-column-count', $textColumns ) );
    }

    /*!
     \reimp
    */
    function &unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
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
    function &serializeContentObjectAttribute( &$package, &$objectAttribute )
    {
        include_once( 'lib/ezxml/classes/ezxml.php' );

        $node = new eZDOMNode();

        $node->setPrefix( 'ezobject' );
        $node->setName( 'attribute' );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'id', $objectAttribute->attribute( 'id' ), 'ezremote' ) );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'identifier', $objectAttribute->contentClassAttributeIdentifier(), 'ezremote' ) );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'name', $objectAttribute->contentClassAttributeName() ) );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'type', $this->isA() ) );

        $xml = new eZXML();
        $domDocument = $xml->domTree( $objectAttribute->attribute( 'data_text' ) );
        if ( $domDocument )
        {
            $node->appendChild( $domDocument->root() );
        }

        return $node;
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
            $objectAttribute->setAttribute( 'data_text', $rootNode->toString( 0 ) );
        }
    }

}

eZDataType::register( EZ_DATATYPESTRING_XML_TEXT, "ezXMLTextType" );

?>
