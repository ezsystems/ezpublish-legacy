<?php
//
// Definition of eZURLType class
//
// Created on: <08-Oct-2002 19:12:43 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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
  \class eZURLType ezurltype.php
  \ingroup eZDatatype
  \brief A content datatype which handles urls

*/

require_once( 'kernel/common/i18n.php' );
class eZURLType extends eZDataType
{
    const DATA_TYPE_STRING = 'ezurl';

    /*!
     Initializes with a url id and a description.
    */
    function eZURLType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezi18n( 'kernel/classes/datatypes', 'URL', 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
        $this->MaxLenValidator = new eZIntegerValidator();
    }

    /*!
     Sets the default value.
    */
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
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
            $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();
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
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
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
                    return eZInputValidator::STATE_INVALID;
                }
            // Remove all url-object links to this attribute.
            eZURLObjectLink::removeURLlinkList( $contentObjectAttribute->attribute( "id" ), $contentObjectAttribute->attribute('version') );
        }
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     \reimp
    */
    function deleteStoredObjectAttribute( $contentObjectAttribute, $version = null )
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

        $db = eZDB::instance();
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
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_ezurl_url_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_ezurl_text_' . $contentObjectAttribute->attribute( 'id' ) )
             )
        {
            $url = $http->postVariable( $base . '_ezurl_url_' . $contentObjectAttribute->attribute( 'id' ) );
            $text = $http->postVariable( $base . '_ezurl_text_' . $contentObjectAttribute->attribute( 'id' ) );

            $contentObjectAttribute->setAttribute( 'data_text', $text );

            $contentObjectAttribute->setContent( $url );
            return true;
        }
        return false;
    }

    /*!
      Makes some post-store operations. Called by framework after store of eZContentObjectAttribute object.
    */
    function postStore( $objectAttribute )
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
    function storeObjectAttribute( $attribute )
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

    function storeClassAttribute( $attribute, $version )
    {
    }

    function storeDefinedClassAttribute( $attribute )
    {
    }

    /*!
     \reimp
    */
    function validateClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     Returns the content.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        if ( !$contentObjectAttribute->attribute( 'data_int' ) )
        {
            $attrValue = false;
            return $attrValue;
        }

        $url = eZURL::url( $contentObjectAttribute->attribute( 'data_int' ) );
        return $url;
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
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
    function title( $contentObjectAttribute, $name = null )
    {
        return  $contentObjectAttribute->attribute( 'data_text' );
    }

    function toString( $contentObjectAttribute )
    {
        if ( !$contentObjectAttribute->attribute( 'data_int' ) )
        {
            $attrValue = false;
            return $attrValue;
        }

        $url = eZURL::url( $contentObjectAttribute->attribute( 'data_int' ) );
        $text = $contentObjectAttribute->attribute( 'data_text');
        if ( $text != '' )
        {
            $exportData = $url . '|' . $text;
        }
        else
        {
            $exportData = $url;
        }
        return $exportData;
    }


    function fromString( $contentObjectAttribute, $string )
    {

        if ( $string == '' )
            return true;

        $separatorPos = strpos( $string, '|' );
        // Check if supplied data has a separator which separates url from url text
        if( $separatorPos === false )
        {
            $urlID = eZURL::registerURL( $string );
            $contentObjectAttribute->setAttribute( 'data_int', $urlID );
            return $urlID;
        }
        else
        {
            $url = substr( $string, 0, $separatorPos );
            $text = substr( $string, $separatorPos + 1 );
            if( $url )
            {
                $urlID = eZURL::registerURL( $url );
                $contentObjectAttribute->setAttribute( 'data_int', $urlID );
            }

            if( $text )
            {
                $contentObjectAttribute->setAttribute( 'data_text', $text );
            }

            return true;
        }
    }

    /*!
     \param package
     \param content attribute

     \return a DOM representation of the content object attribute
    */
    function serializeContentObjectAttribute( $package, $objectAttribute )
    {
        $node = $this->createContentObjectAttributeDOMNode( $objectAttribute );
        $dom = $node->ownerDocument;

        $url = eZURL::fetch( $objectAttribute->attribute( 'data_int' ) );
        if ( is_object( $url ) and
             trim( $url->attribute( 'url' ) ) != '' )
        {
            $urlNode = $dom->createElement( 'url', urlencode( $url->attribute( 'url' ) ) );
            $urlNode->setAttribute( 'original-url-md5', $url->attribute( 'original_url_md5' ) );
            $urlNode->setAttribute( 'is-valid', $url->attribute( 'is_valid' ) );
            $urlNode->setAttribute( 'last-checked', $url->attribute( 'last_checked' ) );
            $urlNode->setAttribute( 'created', $url->attribute( 'created' ) );
            $urlNode->setAttribute( 'modified', $url->attribute( 'modified' ) );
            $node->appendChild( $urlNode );
        }

        if ( $objectAttribute->attribute( 'data_text' ) )
        {
            $textNode = $dom->createElement( 'text', $objectAttribute->attribute( 'data_text' ) );
            $node->appendChild( $textNode );
        }

        return $node;
    }

    /*!
     \reimp
     \param package
     \param contentobject attribute object
     \param domnode object
    */
    function unserializeContentObjectAttribute( $package, $objectAttribute, $attributeNode )
    {
        $urlNode = $attributeNode->getElementsByTagName( 'url' )->item( 0 );

        if ( is_object( $urlNode ) )
        {
            unset( $url );
            $url = urldecode( $urlNode->textContent );

            $urlID = eZURL::registerURL( $url );
            if ( $urlID )
            {
                $urlObject = eZURL::fetch( $urlID );

                $urlObject->setAttribute( 'original_url_md5', $urlNode->getAttribute( 'original-url-md5' ) );
                $urlObject->setAttribute( 'is_valid', $urlNode->getAttribute( 'is-valid' ) );
                $urlObject->setAttribute( 'last_checked', $urlNode->getAttribute( 'last-checked' ) );
                $urlObject->setAttribute( 'created', time() );
                $urlObject->setAttribute( 'modified', time() );
                $urlObject->store();

                $objectAttribute->setAttribute( 'data_int', $urlID );
            }
        }

        $textNode = $attributeNode->getElementsByTagName( 'text' )->item( 0 );
        if ( $textNode )
            $objectAttribute->setAttribute( 'data_text', $textNode->textContent );
    }
}

eZDataType::register( eZURLType::DATA_TYPE_STRING, 'eZURLType' );

?>
