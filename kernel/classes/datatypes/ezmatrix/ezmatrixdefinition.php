<?php
//
// Definition of eZMatrixDefinition class
//
// Created on: <03-Jun-2003 18:30:44 sp>
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

/*! \file ezmatrixdefinition.php
*/

/*!
  \class eZMatrixDefinition ezmatrixdefinition.php
  \ingroup eZDatatype
  \brief The class eZMatrixDefinition does

*/
include_once( "lib/ezxml/classes/ezxml.php" );

class eZMatrixDefinition
{
    /*!
     Constructor
    */
    function eZMatrixDefinition()
    {
        $this->ColumnNames = array();
    }


    function &decodeClassAttribute( $xmlString )
    {
        $xml = new eZXML();
        $dom =& $xml->domTree( $xmlString );
        if ( strlen ( $xmlString ) != 0 )
        {
            $columns =& $dom->elementsByName( "column-name" );
            $columnList = array();
            foreach ( array_keys( $columns ) as $key )
            {
                $columnElement =& $columns[$key];
                $columnList[] = array( 'name' => $columnElement->textContent(),
                                       'identifier' => $columnElement->attributeValue( 'id' ),
                                       'index' =>  $columnElement->attributeValue( 'idx' ) );
            }
            $this->ColumnNames =& $columnList;
        }
        else
        {
            $this->addColumn( );
            $this->addColumn( );
        }

    }

    function &hasAttribute( $attr )
    {
        if ( $attr == 'columns' )
        {
            return true;
        }
        return false;
    }

    function &attribute( $attr )
    {
        if ( $attr == 'columns' )
        {
            return $this->ColumnNames;
        }
    }

    function &xmlString( )
    {
        $doc = new eZDOMDocument( "Matrix" );
        $root =& $doc->createElementNode( "ezmatrix" );
        $doc->setRoot( $root );

        foreach ( $this->ColumnNames as $columnName )
        {
            $columnNameNode =& $doc->createElementNode( 'column-name' );
            $columnNameNode->appendAttribute( $doc->createAttributeNode( 'id', $columnName['identifier'] ) );
            $columnNameNode->appendAttribute( $doc->createAttributeNode( 'idx', $columnName['index'] ) );
            $columnNameNode->appendChild( $doc->createTextNode( $columnName['name'] ) );
            $root->appendChild( $columnNameNode );
        }

        $xml =& $doc->toString();

        return $xml;
    }

    function addColumn( $name = false , $id = false )
    {
        if ( $name == false )
        {
            $name = 'Col_' . ( count( $this->ColumnNames ) );
        }

        if ( $id == false )
        {
            $id = strtolower( $name );
            $id = preg_replace( array( "/[^a-z0-9_ ]/" ,
                                       "/ /",
                                       "/__+/" ),
                                array( "",
                                       "_",
                                       "_" ),
                                $id );
        }

        $this->ColumnNames[] = array( 'name' => $name,
                                      'identifier' => $id,
                                      'index' => count( $this->ColumnNames ) );
    }

    function removeColumn( $index )
    {
        if ( $index == 0 && count( $this->ColumnNames ) == 1 )
        {
            $this->ColumnNames = array();
        }
        else
        {
            array_splice ( $this->ColumnNames, $index, 1 );
        }
    }

    var $ColumnNames;

}

?>
