<?php
//
// Definition of eZContentClassExportHandler class
//
// Created on: <23-Jul-2003 16:11:42 amos>
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

/*! \file ezcontentclassexporthandler.php
*/

/*!
  \class eZContentClassExportHandler ezcontentclassexporthandler.php
  \brief The class eZContentClassExportHandler does

*/

include_once( 'lib/ezxml/classes/ezxml.php' );
include_once( 'kernel/classes/ezcontentclass.php' );

class eZContentClassExportHandler
{
    /*!
     Constructor
    */
    function eZContentClassExportHandler()
    {
    }

    function handle( &$package, $parameters )
    {
        print( "Handling content classes\n" );
        print_r( $parameters );
        $classList = array();
        for ( $i = 0; $i < count( $parameters ); ++$i )
        {
            $parameter = $parameters[$i];
            if ( $parameter == '-class' )
            {
                $classList = explode( ',', $parameters[$i+1] );
                ++$i;
            }
        }
        print_r( $classList );
        if ( count( $classList ) > 0 )
        {
            foreach ( $classList as $classID )
            {
                if ( is_numeric( $classID ) )
                    $class =& eZContentClass::fetch( $classID );
                if ( !$class )
                    continue;
                $classNode =& eZDOMDocument::createElementNode( 'content-class' );
                $classNode->appendChild( eZDOMDocument::createElementTextNode( 'name',
                                                                               $class->attribute( 'name' ) ) );
                $classNode->appendChild( eZDOMDocument::createElementTextNode( 'identifier',
                                                                               $class->attribute( 'identifier' ) ) );
                $classNode->appendChild( eZDOMDocument::createElementTextNode( 'object-name-pattern',
                                                                               $class->attribute( 'contentobject_name' ) ) );
                $metaNode =& eZDOMDocument::createElementNode( 'meta-data' );
                $classNode->appendChild( $metaNode );
                $metaNode->appendChild( eZDOMDocument::createElementTextNode( 'created',
                                                                              $class->attribute( 'created' ) ) );
                $metaNode->appendChild( eZDOMDocument::createElementTextNode( 'modified',
                                                                              $class->attribute( 'modified' ) ) );

                $creatorNode =& eZDOMDocument::createElementNode( 'creator' );
                $metaNode->appendChild( $creatorNode );
                $creatorNode->appendChild( eZDOMDocument::createElementTextNode( 'user-id',
                                                                                 $class->attribute( 'creator_id' ) ) );
                $creator =& $class->attribute( 'creator' );
                if ( $creator )
                    $creatorNode->appendChild( eZDOMDocument::createElementTextNode( 'user-login',
                                                                                     $creator->attribute( 'login' ) ) );

                $modifierNode =& eZDOMDocument::createElementNode( 'modifier' );
                $metaNode->appendChild( $modifierNode );
                $modifierNode->appendChild( eZDOMDocument::createElementTextNode( 'user-id',
                                                                                  $class->attribute( 'modifier_id' ) ) );
                $modifier =& $class->attribute( 'modifier' );
                if ( $modifier )
                    $modifierNode->appendChild( eZDOMDocument::createElementTextNode( 'user-login',
                                                                                      $modifier->attribute( 'login' ) ) );

                $attributesNode =& eZDOMDocument::createElementNode( 'attributes' );
                $attributesNode->appendAttribute( eZDOMDocument::createAttributeNode( 'ezcontentclass-attribute',
                                                                                      'http://ezpublish/contentclassattribute',
                                                                                      'xmlns' ) );
                $classNode->appendChild( $attributesNode );

                $attributes =& $class->fetchAttributes();
                for ( $i = 0; $i < count( $attributes ); ++$i )
                {
                    $attribute =& $attributes[$i];
                    $attributeNode =& eZDOMDocument::createElementNode( 'attribute',
                                                                        array( 'datatype' => $attribute->attribute( 'data_type_string' ),
                                                                               'required' => $attribute->attribute( 'is_required' ) ? 'true' : 'false',
                                                                               'searchable' => $attribute->attribute( 'is_searchable' ) ? 'true' : 'false',
                                                                               'information-collector' => $attribute->attribute( 'is_information_collector' ) ? 'true' : 'false' ) );
                    $attributeRemoteNode =& eZDOMDocument::createElementNode( 'remote' );
                    $attributeNode->appendChild( $attributeRemoteNode );
                    $attributeRemoteNode->appendChild( eZDOMDocument::createElementTextNode( 'id',
                                                                                             $attribute->attribute( 'id' ) ) );
                    $attributeNode->appendChild( eZDOMDocument::createElementTextNode( 'name',
                                                                                       $attribute->attribute( 'name' ) ) );
                    $attributeNode->appendChild( eZDOMDocument::createElementTextNode( 'identifier',
                                                                                       $attribute->attribute( 'identifier' ) ) );
                    $attributeParametersNode =& eZDOMDocument::createElementNode( 'datatype-parameters' );
                    $attributeNode->appendChild( $attributeParametersNode );

                    $dataType =& $attribute->dataType();
                    $dataType->serializeContentClassAttribute( $attribute, $attributeNode, $attributeParametersNode );

                    $attributesNode->appendChild( $attributeNode );
                }

                $package->appendInstall( 'part', false, false, true,
                                         'class-' . $classID, 'contentclass',
                                         array( 'content' => $classNode ) );
            }
        }
    }
}

?>
