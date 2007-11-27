<?php
//
// Definition of eZMatrixDefinition class
//
// Created on: <03-Jun-2003 18:30:44 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*! \file ezmatrixdefinition.php
*/

/*!
  \class eZMatrixDefinition ezmatrixdefinition.php
  \ingroup eZDatatype
  \brief The class eZMatrixDefinition does

*/

class eZMatrixDefinition
{
    /*!
     Constructor
    */
    function eZMatrixDefinition()
    {
        $this->ColumnNames = array();
    }


    function decodeClassAttribute( $xmlString )
    {
        $dom = new DOMDocument( '1.0', 'utf-8' );
        if ( strlen ( $xmlString ) != 0 )
        {
            $success = $dom->loadXML( $xmlString );
            $columns = $dom->getElementsByTagName( "column-name" );
            $columnList = array();
            foreach ( $columns as $columnElement )
            {
                $columnList[] = array( 'name' => $columnElement->textContent,
                                       'identifier' => $columnElement->getAttribute( 'id' ),
                                       'index' =>  $columnElement->getAttribute( 'idx' ) );
            }
            $this->ColumnNames = $columnList;
        }
        else
        {
            $this->addColumn( );
            $this->addColumn( );
        }

    }

    function attributes()
    {
        return array( 'columns' );
    }

    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    function attribute( $attr )
    {
        if ( $attr == 'columns' )
        {
            return $this->ColumnNames;
        }

        eZDebug::writeError( "Attribute '$attr' does not exist", 'eZMatrixDefinition::attribute' );
        return null;
    }

    function xmlString( )
    {
        $doc = new DOMDocument( '1.0', 'utf-8' );
        $root = $doc->createElement( "ezmatrix" );
        $doc->appendChild( $root );

        foreach ( $this->ColumnNames as $columnName )
        {
            $columnNameNode = $doc->createElement( 'column-name', $columnName['name'] );
            $columnNameNode->setAttribute( 'id', $columnName['identifier'] );
            $columnNameNode->setAttribute( 'idx', $columnName['index'] );
            $root->appendChild( $columnNameNode );
            unset( $columnNameNode );
            unset( $textNode );
        }

        $xml = $doc->saveXML();

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
            // Initialize transformation system
            //include_once( 'lib/ezi18n/classes/ezchartransform.php' );
            $trans = eZCharTransform::instance();
            $id = $trans->transformByGroup( $name, 'identifier' );
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
            unset( $this->ColumnNames[$index] );
        }
    }

    public $ColumnNames;

}

?>
