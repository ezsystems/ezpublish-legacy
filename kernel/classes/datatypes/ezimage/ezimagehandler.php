<?php
//
// Definition of eZImageHandler class
//
// Created on: <16-Oct-2003 09:34:25 bf>
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
// http://ez.no/products/licences/professional/. For pricing of this licence
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
  \class eZImageHandler ezimagehandler.php
  \ingroup eZKernel
  \brief The class eZImage handles images

*/

include_once( "lib/ezxml/classes/ezxml.php" );

class eZImageHandler
{
    function eZImageHandler( &$contentObjectAttribute )
    {
        $this->ContentObjectAttribute =& $contentObjectAttribute;
    }

    function hasAttribute( $attr )
    {
        return true;
    }

    function attribute( $attr )
    {
        // try to parse the XML containing the image data
        $xml = new eZXML();

        $xmlString =& $this->ContentObjectAttribute->DataText;

        $domTree =& $xml->domTree( $xmlString );

        if ( $domTree == false )
        {
            $this->generateXMLData();
        }

        $domTree =& $xml->domTree( $xmlString );

        $imageNodeArray =& $domTree->elementsByName( "ezimage" );
        $imageVariationNodeArray =& $domTree->elementsByName( "variation" );

        foreach ( $imageVariationNodeArray as $imageVariation )
        {
            $retArray = array();

            $retArray['width'] = $imageVariation->attributeValue( 'width' );
            $retArray['height'] = $imageVariation->attributeValue( 'height' );
            $retArray['mime_type'] = $imageVariation->attributeValue( 'mime_type' );
            $retArray['filename'] = $imageVariation->attributeValue( 'filename' );
            $retArray['full_path'] = 'var/storage/variations/image/' . $imageVariation->attributeValue( 'additional_path' ) . "/". $imageVariation->attributeValue( 'filename' );

            return $retArray;
        }

    }

    function generateXMLData()
{
    include_once( "lib/ezdb/classes/ezdb.php" );
    print( "could not parse XML" );

    $db =& eZDB::instance();

    $attributeID = $this->ContentObjectAttribute->attribute( 'id' );
    $attributeVersion = $this->ContentObjectAttribute->attribute( 'version' );

    print( "$attributeID $attributeVersion <br>" );

    $imageRow =& $db->arrayQuery( "SELECT * FROM ezimage
                                           WHERE contentobject_attribute_id=$attributeID AND
                                                 version=$attributeVersion" );
    if ( count( $imageRow ) == 1 )
    {
        $fileName = $imageRow[0]['filename'];
        $originalFileName = $imageRow[0]['original_filename'];
        $mimeType = $imageRow[0]['mime_type'];
        $altText = $imageRow[0]['alternative_text'];

        $doc = new eZDOMDocument();
        $imageNode =& $doc->createElementNode( "ezimage" );
        $doc->setRoot( $imageNode );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'filename', $fileName ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'original_filename', $originalFileName ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'mime_type', $mimeType ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'alternative_text', $altText ) );

        /*
        // Fetch variations
        $imageVariationRowArray =& $db->arrayQuery( "SELECT * FROM ezimagevariation
                                           WHERE contentobject_attribute_id=$attributeID AND
                                                 version=$attributeVersion" );

        foreach ( $imageVariationRowArray as $variationRow )
        {
            unset( $imageVariationNode );
            $imageVariationNode =& $doc->createElementNode( "variation" );
            $imageNode->appendChild( $imageVariationNode );

            $imageVariationNode->appendAttribute( $doc->createAttributeNode( 'name', 'medium' ) );

            $imageVariationNode->appendAttribute( $doc->createAttributeNode( 'filename', $variationRow['filename'] ) );
            $imageVariationNode->appendAttribute( $doc->createAttributeNode( 'additional_path', $variationRow['additional_path'] ) );
            $imageVariationNode->appendAttribute( $doc->createAttributeNode( 'width', $variationRow['width'] ) );
            $imageVariationNode->appendAttribute( $doc->createAttributeNode( 'height', $variationRow['height'] ) );

        }
        */
        $xmlString = $doc->toString();
//        print( htmlspecialchars( $xmlString ) );

        // store the XML data in the attribute
        $this->ContentObjectAttribute->setAttribute( 'data_text', $xmlString );
        $this->ContentObjectAttribute->store();
    }
}

    /// Contains a reference to the object attribute
    var $ContentObjectAttribute;
}
