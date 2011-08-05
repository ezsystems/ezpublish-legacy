<?php
/**
 * File containing the eZMatrixDefinition class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
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

        eZDebug::writeError( "Attribute '$attr' does not exist", __METHOD__ );
        return null;
    }

    function xmlString( )
    {
        $doc = new DOMDocument( '1.0', 'utf-8' );
        $root = $doc->createElement( "ezmatrix" );
        $doc->appendChild( $root );

        foreach ( $this->ColumnNames as $columnName )
        {
            $columnNameNode = $doc->createElement( 'column-name' );
            $columnNameNode->appendChild( $doc->createTextNode( $columnName['name'] ) );
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
