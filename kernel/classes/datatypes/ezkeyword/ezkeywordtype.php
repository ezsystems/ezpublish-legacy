<?php
/**
 * File containing the eZKeywordType class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZKeywordType ezkeywordtype.php
  \ingroup eZDatatype
  \brief A content datatype which handles keyword indexes

*/



class eZKeywordType extends eZDataType
{
    const DATA_TYPE_STRING = 'ezkeyword';

    /*!
     Initializes with a keyword id and a description.
    */
    function eZKeywordType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezpI18n::tr( 'kernel/classes/datatypes', 'Keywords', 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     Sets the default value.
    */
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $originalContentObjectAttributeID = $originalContentObjectAttribute->attribute( 'id' );
            $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );

            // if translating or copying an object
            if ( $originalContentObjectAttributeID != $contentObjectAttributeID )
            {
                // copy keywords links as well
                $keyword = $originalContentObjectAttribute->content();
                if ( is_object( $keyword ) )
                {
                    $keyword->store( $contentObjectAttribute );
                }
            }
        }
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $classAttribute = $contentObjectAttribute->contentClassAttribute();

        if ( $http->hasPostVariable( $base . '_ezkeyword_data_text_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $data = $http->postVariable( $base . '_ezkeyword_data_text_' . $contentObjectAttribute->attribute( 'id' ) );

            if ( $data == "" )
            {
                if ( !$classAttribute->attribute( 'is_information_collector' ) and $contentObjectAttribute->validateIsRequired() )
                {
                    $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                         'Input required.' ) );
                    return eZInputValidator::STATE_INVALID;
                }
            }
        }
        else if ( !$classAttribute->attribute( 'is_information_collector' ) and $contentObjectAttribute->validateIsRequired() )
        {
            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes', 'Input required.' ) );
            return eZInputValidator::STATE_INVALID;
        }
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     Fetches the http post var keyword input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_ezkeyword_data_text_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $data = $http->postVariable( $base . '_ezkeyword_data_text_' . $contentObjectAttribute->attribute( 'id' ) );
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
    function storeObjectAttribute( $attribute )
    {
        // create keyword index
        $keyword = $attribute->content();
        if ( is_object( $keyword ) )
        {
            $keyword->store( $attribute );
        }
    }

    function storeClassAttribute( $attribute, $version )
    {
    }

    function storeDefinedClassAttribute( $attribute )
    {
    }

    function validateClassAttributeHTTPInput( $http, $base, $attribute )
    {
        return eZInputValidator::STATE_ACCEPTED;
    }

    function fixupClassAttributeHTTPInput( $http, $base, $attribute )
    {
    }

    function fetchClassAttributeHTTPInput( $http, $base, $attribute )
    {
        return true;
    }

    /*!
     Returns the content.
    */
    function objectAttributeContent( $attribute )
    {
        $keyword = new eZKeyword();
        $keyword->fetch( $attribute );

        return $keyword;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $attribute )
    {
        $keyword = new eZKeyword();
        $keyword->fetch( $attribute );
        $return = $keyword->keywordString();

        return $return;
    }

    /*!
     Delete stored object attribute
    */
    function deleteStoredObjectAttribute( $contentObjectAttribute, $version = null )
    {
        if ( $version != null ) // Do not delete if discarding draft
        {
            return;
        }

        $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );

        $db = eZDB::instance();

        /* First we retrieve all the keyword ID related to this object attribute */
        $res = $db->arrayQuery( "SELECT keyword_id
                                 FROM ezkeyword_attribute_link
                                 WHERE objectattribute_id='$contentObjectAttributeID'" );
        if ( !count ( $res ) )
        {
            /* If there are no keywords at all, we abort the function as there
             * is nothing more to do */
            return;
        }
        $keywordIDs = array();
        foreach ( $res as $record )
            $keywordIDs[] = $record['keyword_id'];
        $keywordIDString = implode( ', ', $keywordIDs );

        /* Then we see which ones only have a count of 1 */
        $res = $db->arrayQuery( "SELECT keyword_id
                                 FROM ezkeyword, ezkeyword_attribute_link
                                 WHERE ezkeyword.id = ezkeyword_attribute_link.keyword_id
                                     AND ezkeyword.id IN ($keywordIDString)
                                 GROUP BY keyword_id
                                 HAVING COUNT(*) = 1" );
        $unusedKeywordIDs = array();
        foreach ( $res as $record )
            $unusedKeywordIDs[] = $record['keyword_id'];
        $unusedKeywordIDString = implode( ', ', $unusedKeywordIDs );

        /* Then we delete those unused keywords */
        if ( $unusedKeywordIDString )
            $db->query( "DELETE FROM ezkeyword WHERE id IN ($unusedKeywordIDString)" );

        /* And as last we remove the link between the keyword and the object
         * attribute to be removed */
        $db->query( "DELETE FROM ezkeyword_attribute_link
                     WHERE objectattribute_id='$contentObjectAttributeID'" );
    }

    /*!
     Returns the content of the keyword for use as a title
    */
    function title( $attribute, $name = null )
    {
        $keyword = new eZKeyword();
        $keyword->fetch( $attribute );
        $return = $keyword->keywordString();

        return $return;
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        $keyword = new eZKeyword();
        $keyword->fetch( $contentObjectAttribute );
        $array = $keyword->keywordArray();

        return count( $array ) > 0;
    }

    function isIndexable()
    {
        return true;
    }

    /**
     *
     * Returns string representation of an contentobjectattribute data for simplified export
     *
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @return string
     */
    function toString( $contentObjectAttribute )
    {
        $keyword = new eZKeyword();
        $keyword->fetch( $contentObjectAttribute  );
        return  $keyword->keywordString();
    }

    function fromString( $contentObjectAttribute, $string )
    {
        $keyword = new eZKeyword();
        $keyword->initializeKeyword( $string );
        $contentObjectAttribute->setContent( $keyword );
        return true;
    }

    function serializeContentObjectAttribute( $package, $objectAttribute )
    {
        $node = $this->createContentObjectAttributeDOMNode( $objectAttribute );

        $keyword = new eZKeyword();
        $keyword->fetch( $objectAttribute );
        $keyWordString = $keyword->keywordString();
        $dom = $node->ownerDocument;
        $keywordStringNode = $dom->createElement( 'keyword-string' );
        $keywordStringNode->appendChild( $dom->createTextNode( $keyWordString ) );
        $node->appendChild( $keywordStringNode );

        return $node;
    }

    function unserializeContentObjectAttribute( $package, $objectAttribute, $attributeNode )
    {
        $keyWordString = $attributeNode->getElementsByTagName( 'keyword-string' )->item( 0 )->textContent;
        $keyword = new eZKeyword();
        $keyword->initializeKeyword( $keyWordString );
        $objectAttribute->setContent( $keyword );
    }

    function supportsBatchInitializeObjectAttribute()
    {
        return true;
    }
}

?>
