<?php
//
// Definition of eZKeywordType class
//
// Created on: <29-Apr-2003 14:59:12 bf>
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
  \class eZKeywordType ezkeywordtype.php
  \ingroup eZDatatype
  \brief A content datatype which handles keyword indexes

*/

include_once( 'kernel/classes/ezdatatype.php' );
include_once( 'kernel/common/i18n.php' );

include_once( 'kernel/classes/datatypes/ezkeyword/ezkeyword.php' );

define( 'EZ_DATATYPESTRING_KEYWORD', 'ezkeyword' );

class eZKeywordType extends eZDataType
{
    /*!
     Initializes with a keyword id and a description.
    */
    function eZKeywordType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_KEYWORD, ezi18n( 'kernel/classes/datatypes', 'Keyword', 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     Sets the default value.
    */
    function initializeObjectAttribute( &$contentObjectAttribute, $currentVersion, &$originalContentObjectAttribute )
    {
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Fetches the http post var keyword input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_ezkeyword_data_text_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $data =& $http->postVariable( $base . '_ezkeyword_data_text_' . $contentObjectAttribute->attribute( 'id' ) );
            $keyword = new eZKeyword();
            $keyword->initializeKeyword( $data );
            $contentObjectAttribute->setContent( $keyword );
            return true;
        }
        return false;
    }

    /*!
     Does nothing since it uses the data_text field in the content object attribute.
     See fetchObjectAttributeHTTPInput for the actual storing.
    */
    function storeObjectAttribute( &$attribute )
    {
        // create keyword index
        $keyword =& $attribute->content();
        if ( is_object( $keyword ) )
        {
            $keyword->store( $attribute );
        }
    }

    function storeClassAttribute( &$attribute, $version )
    {
    }

    function storeDefinedClassAttribute( &$attribute )
    {
    }

    /*!
     \reimp
    */
    function validateClassAttributeHTTPInput( &$http, $base, &$attribute )
    {
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     \reimp
    */
    function fixupClassAttributeHTTPInput( &$http, $base, &$attribute )
    {
    }

    /*!
     \reimp
    */
    function fetchClassAttributeHTTPInput( &$http, $base, &$attribute )
    {
        return true;
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$attribute )
    {
        $keyword = new eZKeyword();
        $keyword->fetch( $attribute );

        return $keyword;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( &$attribute )
    {
        $keyword = new eZKeyword();
        $keyword->fetch( $attribute );
        $return =& $keyword->keywordString();

        return $return;
    }

    /*!
     \reuturn the collect information action if enabled
    */
    function contentActionList( &$classAttribute )
    {
        return array();
    }

    /*!
     Delete stored object attribute
    */
    function deleteStoredObjectAttribute( &$contentObjectAttribute, $version = null )
    {
        if ( $version != null ) // Do not delete if discarding draft
        {
            return;
        }

        $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );

        $db =& eZDB::instance();
        $db->query( "DELETE FROM ezkeyword_attribute_link WHERE objectattribute_id='$contentObjectAttributeID'" );
    }

    /*!
     Returns the content of the keyword for use as a title
    */
    function title( &$attribute )
    {
        $keyword = new eZKeyword();
        $keyword->fetch( $attribute );
        $return =& $keyword->keywordString();

        return $return;
    }

    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        $keyword = new eZKeyword();
        $keyword->fetch( $contentObjectAttribute );
        $array =& $keyword->keywordArray();

        return count( $array ) > 0;
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
     \param package
     \param content attribute

     \return a DOM representation of the content object attribute
    */
    function &serializeContentObjectAttribute( &$package, &$objectAttribute )
    {
        $node = new eZDOMNode();

        $node->setPrefix( 'ezobject' );
        $node->setName( 'attribute' );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'id', $objectAttribute->attribute( 'id' ), 'ezremote' ) );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'identifier', $objectAttribute->contentClassAttributeIdentifier(), 'ezremote' ) );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'name', $objectAttribute->contentClassAttributeName() ) );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'type', $this->isA() ) );

        $keyword = new eZKeyword();
        $keyword->fetch( $objectAttribute );
        $keyWordString =& $keyword->keywordString();
        $node->appendChild( eZDOMDocument::createElementTextNode( 'keyword-string', $keyWordString ) );

        return $node;
    }

    /*!
     \reimp
     Unserailize contentobject attribute

     \param package
     \param contentobject attribute object
     \param ezdomnode object
    */
    function unserializeContentObjectAttribute( &$package, &$objectAttribute, $attributeNode )
    {
        $keyWordString = $attributeNode->elementTextContentByName( 'keyword-string' );
        $keyword = new eZKeyword();
        $keyword->initializeKeyword( $keyWordString );
        $objectAttribute->setContent( $keyword );
    }
}

eZDataType::register( EZ_DATATYPESTRING_KEYWORD, 'ezkeywordtype' );

?>
