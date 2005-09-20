<?php
//
// Definition of eZURLType class
//
// Created on: <08-Oct-2002 19:12:43 bf>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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
  \class eZURLType ezurltype.php
  \ingroup eZDatatype
  \brief A content datatype which handles urls

*/

include_once( 'kernel/classes/ezdatatype.php' );
include_once( 'lib/ezutils/classes/ezintegervalidator.php' );
include_once( 'kernel/common/i18n.php' );
include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );
include_once( 'kernel/classes/datatypes/ezurl/ezurlobjectlink.php' );

define( 'EZ_DATATYPEURL_URL', 'ezurl' );

class eZURLType extends eZDataType
{
    /*!
     Initializes with a url id and a description.
    */
    function eZURLType()
    {
        $this->eZDataType( EZ_DATATYPEURL_URL, ezi18n( 'kernel/classes/datatypes', 'URL', 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
        $this->MaxLenValidator = new eZIntegerValidator();
    }

    /*!
     Sets the default value.
    */
    function initializeObjectAttribute( &$contentObjectAttribute, $currentVersion, &$originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
//             $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
//             $currentObjectAttribute = eZContentObjectAttribute::fetch( $contentObjectAttributeID,
//                                                                         $currentVersion );
            $dataText = $originalContentObjectAttribute->attribute( "data_text" );
            $url = $originalContentObjectAttribute->attribute( "content" );
            $contentObjectAttribute->setContent( $url );
            $contentObjectAttribute->setAttribute( "data_text", $dataText );
        }
        else
        {
            $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();
            $default = $contentClassAttribute->attribute( "data_text1" );
            if ( $default !== "" )
            {
                $contentObjectAttribute->setAttribute( "data_text", $default );
            }
        }
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_ezurl_url_" . $contentObjectAttribute->attribute( "id" ) )  and
             $http->hasPostVariable( $base . "_ezurl_text_" . $contentObjectAttribute->attribute( "id" ) )
           )
        {
            $url = $http->PostVariable( $base . "_ezurl_url_" . $contentObjectAttribute->attribute( "id" ) );
            $text = $http->PostVariable( $base . "_ezurl_text_" . $contentObjectAttribute->attribute( "id" ) );
            if ( $contentObjectAttribute->validateIsRequired() )
                if ( ( $url == "" ) or ( $text == "" ) )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'Input required.' ) );
                    return EZ_INPUT_VALIDATOR_STATE_INVALID;
                }
        }
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     \reimp
    */
    function deleteStoredObjectAttribute( &$contentObjectAttribute, $version = null )
    {
        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );
        $urls = array();
        if ( $version == null )
        {
            $urls = eZURLObjectLink::fetchLinkList( $contentObjectAttributeID, false, false );
            eZURLObjectLink::removeURLlinkList( $contentObjectAttributeID, false );
        }
        else
        {
            $urls = eZURLObjectLink::fetchLinkList( $contentObjectAttributeID, $version, false );
            eZURLObjectLink::removeURLlinkList( $contentObjectAttributeID, $version );
        }
        $urls = array_unique( $urls );

        $db =& eZDB::instance();
        $db->begin();

        foreach ( $urls as $urlID )
        {
            if ( !eZURLObjectLink::hasObjectLinkList( $urlID ) )
            {
                eZURL::removeByID( $urlID );
            }
        }

        $db->commit();
    }

    /*!
     Fetches the http post var url input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_ezurl_url_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_ezurl_text_' . $contentObjectAttribute->attribute( 'id' ) )
             )
        {
            $url =& $http->postVariable( $base . '_ezurl_url_' . $contentObjectAttribute->attribute( 'id' ) );
            $text =& $http->postVariable( $base . '_ezurl_text_' . $contentObjectAttribute->attribute( 'id' ) );

            $contentObjectAttribute->setAttribute( 'data_text', $text );

            $contentObjectAttribute->setContent( $url );
            return true;
        }
        return false;
    }

    /*!
      Makes some post-store operations. Called by framework after store of eZContentObjectAttribute object.
    */
    function postStore( &$objectAttribute )
    {
        // Update url-object link
        $urlValue = $objectAttribute->content();
        if ( trim( $urlValue ) != '' )
        {
            $urlID = eZURL::registerURL( $urlValue );
            //$urlID = $objectAttribute->attribute( 'data_int' );+
            $objectAttributeID = $objectAttribute->attribute( 'id' );
            $objectAttributeVersion = $objectAttribute->attribute( 'version' );

            if ( !eZURLObjectLink::fetch( $urlID, $objectAttributeID, $objectAttributeVersion, false ) )
            {
                $linkObjectLink = eZURLObjectLink::create( $urlID, $objectAttributeID, $objectAttributeVersion );
                $linkObjectLink->store();
            }
        }
    }

    /*!
      Store the URL in the URL database and store the reference to it.
    */
    function storeObjectAttribute( &$attribute )
    {
        $urlValue = $attribute->content();
        if ( trim( $urlValue ) != '' )
        {
            $oldURLID = $attribute->attribute( 'data_int' );
            $urlID = eZURL::registerURL( $urlValue );
            $attribute->setAttribute( 'data_int', $urlID );

            if ( $oldURLID && $oldURLID != $urlID &&
                 !eZURLObjectLink::hasObjectLinkList( $oldURLID ) )
                    eZURL::removeByID( $oldURLID );
        }
        else
        {
            $attribute->setAttribute( 'data_int', 0 );
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
    function validateClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        if ( !$contentObjectAttribute->attribute( 'data_int' ) )
        {
            $attrValue = false;
            return $attrValue;
        }

        $url = eZURL::url( $contentObjectAttribute->attribute( 'data_int' ) );
        return $url;
    }

    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        if ( $contentObjectAttribute->attribute( 'data_int' ) == 0 )
            return false;

        $url = eZURL::fetch( $contentObjectAttribute->attribute( 'data_int' ) );
        if ( is_object( $url ) and
             trim( $url->attribute( 'url' ) ) != '' and
             $url->attribute( 'is_valid' ) )
            return true;
        return false;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    /*!
     Returns the content of the url for use as a title
    */
    function title( &$contentObjectAttribute )
    {
        return  $contentObjectAttribute->attribute( 'data_text' );
    }

    /*!
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

        $url = eZURL::fetch( $objectAttribute->attribute( 'data_int' ) );
        if ( is_object( $url ) and
             trim( $url->attribute( 'url' ) ) != '' )
        {
            $urlNode = eZDOMDocument::createElementNode( 'url' );
            $urlNode->appendAttribute( eZDOMDocument::createAttributeNode( 'original-url-md5', $url->attribute( 'original_url_md5' ) ) );
            $urlNode->appendAttribute( eZDOMDocument::createAttributeNode( 'is-valid', $url->attribute( 'is_valid' ) ) );
            $urlNode->appendAttribute( eZDOMDocument::createAttributeNode( 'last-checked', $url->attribute( 'last_checked' ) ) );
            $urlNode->appendAttribute( eZDOMDocument::createAttributeNode( 'created', $url->attribute( 'created' ) ) );
            $urlNode->appendAttribute( eZDOMDocument::createAttributeNode( 'modified', $url->attribute( 'modified' ) ) );
            $urlNode->appendChild( eZDOMDocument::createTextNode( $url->attribute( 'url' ) ) );
            $node->appendChild( $urlNode );
        }

        if ( $objectAttribute->attribute( 'data_text' ) )
            $node->appendChild( eZDOMDocument::createElementTextNode( 'text', $objectAttribute->attribute( 'data_text' ) ) );

        return $node;
    }

    /*!
     \reimp
     \param package
     \param contentobject attribute object
     \param ezdomnode object
    */
    function unserializeContentObjectAttribute( &$package, &$objectAttribute, $attributeNode )
    {
        $urlNode =& $attributeNode->elementByName( 'url' );
        $urlTextNode = is_object( $urlNode ) ? $urlNode->firstChild() : null;
        if ( is_object( $urlTextNode ) )
        {
            unset( $url );
            $url =& $urlTextNode->content();

            $urlID = eZURL::registerURL( $url );
            if ( $urlID )
            {
                $urlObject = eZURL::fetch( $urlID );

                $urlObject->setAttribute( 'original_url_md5', $urlNode->attributeValue( 'original-url-md5' ) );
                $urlObject->setAttribute( 'is_valid', $urlNode->attributeValue( 'is-valid' ) );
                $urlObject->setAttribute( 'last_checked', $urlNode->attributeValue( 'last-checked' ) );
                $urlObject->setAttribute( 'created', mktime() );
                $urlObject->setAttribute( 'modified', mktime() );
                $urlObject->store();

                $objectAttribute->setAttribute( 'data_int', $urlID );
            }
        }
        if ( $attributeNode->elementTextContentByName( 'text' ) )
            $objectAttribute->setAttribute( 'data_text', $attributeNode->elementTextContentByName( 'text' ) );
    }
}

eZDataType::register( EZ_DATATYPEURL_URL, 'ezurltype' );

?>
