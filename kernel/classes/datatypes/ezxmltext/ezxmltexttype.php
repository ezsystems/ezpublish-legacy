<?php
//
// Definition of eZXMLTextType class
//
// Created on: <06-May-2002 20:02:55 bf>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
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
<section>
<header>This is a level one header</header>
<paragraph>
This is a <emphasize>block</emphasize> of text.
</paragraph>
  <section>
  <header>This is a level two header</header>
  <paragraph>
  This is the second paragraph.<emphasize>emphasized/bold text</emphasize>
  </paragraph>
  <header>This is a level two header</header>
  <paragraph>
  This is the second paragraph.<emphasize>emphasized/bold text</emphasize>
  </paragraph>
  <paragraph>
  This is the second paragraph.<emphasize>emphasized/bold text</emphasize>
  </paragraph>
  <paragraph>
  <ul>
     <li>List item 1</li>
     <li>List item 2</li>
  </ul>
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

class eZXMLTextType extends eZDataType
{
    function eZXMLTextType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_XML_TEXT, "XML Text field" );
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
         $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();
         if ( $contentClassAttribute->attribute( "data_int1" ) == 0 )
         {
              $contentClassAttribute->setAttribute( "data_int1", 10 );
              $contentClassAttribute->store();
         }
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        /// Get object for input validation
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
        }
    }

    /*!
     Fetches the http post var string input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
    }

    /*!
     Store the content.
    */
    function storeObjectAttribute( &$attribute )
    {
    }


    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        include_once( 'kernel/classes/datatypes/ezxmltext/ezxmltext.php' );
        $xmlText = new eZXMLText( $contentObjectAttribute->attribute( 'data_text' ) );
        return $xmlText;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        $metaData = "";
        $doc =& $contentObjectAttribute->attribute( "data_text" );

        $xml = new eZXML();
        $dom =& $xml->domTree( $contentObjectAttribute->attribute( "data_text" ) );

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
    function title( &$data_instance )
    {
        return $data_instance->attribute( "data_text" );
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
        return true;
    }

    /*!
     \return a DOM representation of the content object attribute
    */
    function &serializeContentObjectAttribute( $objectAttribute )
    {
        include_once( 'lib/ezxml/classes/ezdomdocument.php' );
        include_once( 'lib/ezxml/classes/ezdomnode.php' );

        $node =& eZDataType::contentObjectAttributeDOMNode( $objectAttribute );
//         $node = new eZDOMNode();
//         $node->setName( 'attribute' );
//         $node->appendAttribute( eZDOMDocument::createAttributeNode( 'name', $objectAttribute->contentClassAttributeName() ) );
//         $node->appendAttribute( eZDOMDocument::createAttributeNode( 'type', 'ezxmltext' ) );
        include_once( 'lib/ezxml/classes/ezxml.php' );
        $xml = new eZXML();
        $dom =& $xml->domTree( $objectAttribute->attribute( "data_text" ) );

//         $node->appendChild( eZDOMDocument::createTextNode( $objectAttribute->attribute( 'data_text' ) ) );
        $contentNode = new eZDOMNode();
        $contentNode->setPrefix( 'ezobject' );
        $contentNode->setName( 'content' );
        $contentNode->appendAttribute( eZDOMDocument::createAttributeNode( 'name', $objectAttribute->contentClassAttributeName() ) );
        $contentNode->appendAttribute( eZDOMDocument::createAttributeNode( 'type', 'ezxmltext' ) );

        $contentNode->appendChild( $dom->root() );
        $node->appendChild( $contentNode );

        return $node;
    }

    /*!
    */
    function customObjectAttributeHTTPAction( $http, $action, &$contentObjectAttribute )
    {
        switch ( $action )
        {
            case "enable_editor" :
            {
                $http =& eZHTTPTool::instance();
                $http->removeSessionVariable( 'DisableEditorExtension' );
            }break;
            case "disable_editor" :
            {
                $http =& eZHTTPTool::instance();
                $http->setSessionVariable( 'DisableEditorExtension', true );
            }break;
            default :
            {
                eZDebug::writeError( "Unknown custom HTTP action: " . $action, "eZOptionType" );
            }break;
        }
    }
}

eZDataType::register( EZ_DATATYPESTRING_XML_TEXT, "ezXMLTextType" );

?>
