<?php
//
// Definition of eZMatrix class
//
// Created on: <30-May-2003 16:46:50 sp>
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

/*! \file ezmatrix.php
*/

/*!
  \class eZMatrix ezmatrix.php
  \brief The class eZMatrix does

*/

include_once( "lib/ezxml/classes/ezxml.php" );

class eZMatrix
{
    /*!
     Constructor
    */
    function eZMatrix( $name, $numRows = false, $matrixColumnDefinition = false )
    {
        $this->Name = $name;
        $this->Matrix = array();

        if ( $numRows !== false &&  $matrixColumnDefinition !== false )
        {
            $columns = $matrixColumnDefinition->attribute( 'columns' );
            $numColumns = count( $columns );
            $this->NumColumns = $numColumns;

            $sequentialColumns = array();
            foreach ( $columns as $column )
            {
                $sequentialColumns[] = array( 'identifier' => $column['identifier'],
                                            'index' => $column['index'],
                                            'name' => $column['name'] );

            }
            $this->Matrix['columns'] = array();
            $this->Matrix['columns']['sequential'] =& $sequentialColumns;

            $this->NumRows = $numRows;
            $cells = array();
            for ( $i = 0; $i < $numColumns; ++$i )
            {
                for ( $j = 0; $j < $numRows; ++$j )
                {
                    $cells[] = '';
                }
            }
            $this->Cells =& $cells;


            $xmlString =& $this->xmlString();
            $this->decodeXML( $xmlString );
        }
    }

    /*!
        Checks current eZMatrix object against definition.
        If columns ids are wrong or
           there are additional/redundant columns in definition/eZMatrix object
        then current eZMatix object will be adjusted according to \a $matrixColumnDefinition.
        Note: if id of some column was changed form "old_id" to "new_id"
              then a column with "old_id" will be removed(all data of this column
              will be lost) and an empty column with "new_id" will be created.
        Returns \a true if adjustment(matrix modification) was performed. Otherwise - \a false.
    */
    function adjustColumnsToDefinition( $classColumnsDefinition )
    {
        $matrixWasModified = false;

        $matrixWasModified |= $this->removeUselessColumns( $classColumnsDefinition );
        $matrixWasModified |= $this->updateColumns       ( $classColumnsDefinition, true, true );

        if ( $matrixWasModified )
        {
            $this->reorderColumns();

            $columns          =& $classColumnsDefinition->attribute( 'columns' );
            $numColumns       =  count( $columns );
            $this->NumColumns =  $numColumns;

            $xmlString        =& $this->xmlString();
            $this->decodeXML( $xmlString );
        }

        return $matrixWasModified;
    }

    /*!
        \a static
    */
    function buildReorderChains( &$chain, &$columns, &$curPos, &$startPos )
    {
        $column  =& $columns[$curPos];
        $toPos   =  $column['index'];
        $chain[] = $toPos;

        $curPos  = $toPos;
        $column  =& $columns[$curPos];
        if ( $column['index'] != $startPos )
        {
            eZMatrix::buildReorderChains( $chain, $columns, $curPos, $startPos );
        }
    }

    /*!
        \a static
    */
    function &buildReorderRuleForColumn( &$columns, $curPos, $startPos )
    {
        $rule = array( $curPos );
        eZMatrix::buildReorderChains( $rule, $columns, $curPos, $startPos );
        return $rule;
    }

    /*!
        \a static
    */
    function &buildReorderRules( &$columns )
    {
        $rules      = array();
        $positions  = array_keys( $columns );

        foreach ( $positions as $pos )
        {
            if ( eZMatrix::hasRuleForColumn( $rules, $pos ) )
            {
                continue;
            }

            $column =& $columns[$pos];
            if ( $column['index'] != $pos )
            {
                $rules[] =& eZMatrix::buildReorderRuleForColumn( $columns, $pos, $pos );
            }
        }
        return $rules;
    }

    /*!
    */
    function reorderColumns()
    {
        $matrix     =& $this->attribute( 'matrix' );
        $columns    =& $matrix['columns']['sequential'];

        $rules      =& eZMatrix::buildReorderRules( $columns );

        /*
            example rule: ( 0, 3, 2, 1 )
            reorder way:
            move( 1 -> buffer )
            move( 2 -> 1 )
            move( 3 -> 2 )
            move( 0 -> 3 )
            move( buffer -> 0 )
        */
        foreach( $rules as $rule )
        {
            $buffer = array( 'columnDefinition' => array(),
                             'cellsData'        => array() );

            // last column in rule
            $pos = count( $rule ) - 1;

            $this->copyColumn( $rule[$pos], $buffer, true );

            while ( $pos != 0 )
            {
                $this->copyDataBetweenColumns( $rule[$pos - 1], $rule[$pos]);
                --$pos;
            }

            // first column in rule
            $this->copyColumn( $rule[0], $buffer, false );
        }
    }

    /*!
        \a static
    */
    function hasRuleForColumn( &$rules, $pos )
    {
        $keys = array_keys( $rules );

        foreach ( $keys as $key )
        {
            $rule =& $rules[$key];
            foreach ( $rule as $columnPos )
            {
                if ( $columnPos == $pos )
                {
                    return true;
                }
            }
        }
        return false;
    }

    /*!
    */
    function copyColumn( $colIdx, &$buffer, $toBuffer )
    {
        $this->copyColumnDefinition( $colIdx, $buffer['columnDefinition'], $toBuffer );
        $this->copyColumnCellsData ( $colIdx, $buffer['cellsData'], $toBuffer );
    }

    /*!
    */
    function copyColumnDefinition( $colIdx, &$buffer, $toBuffer )
    {
        $matrix      =& $this->attribute( 'matrix' );
        $columns     =& $matrix['columns']['sequential'];

        if ( $toBuffer )
            $buffer = $columns[$colIdx];
        else
            $columns[$colIdx] = $buffer;
    }

    /*!
    */
    function copyColumnCellsData( $colIdx, &$buffer, $toBuffer )
    {
        $columnCount =  $this->attribute( 'columnCount' );
        $rowCount    =  $this->attribute( 'rowCount' );
        $cells       =& $this->attribute( 'cells' );
        $pos         =  $colIdx;
        $i           =  0;

        // walk through rows and add elements one by one.
        while ( $rowCount > 0 )
        {
            if ( $toBuffer )
            {
                $buffer[] = $cells[$pos];
            }
            else
            {
                $cells[$pos] = $buffer[$i];
                ++$i;
            }

            $pos += $columnCount;
            --$rowCount;
        }
    }

    /*!
    */
    function copyDataBetweenColumns( $firstColIdx, $secondColIdx, $toSecondCol = true )
    {
        $this->copyDefinitionBetweenColumns( $firstColIdx, $secondColIdx, $toSecondCol );
        $this->copyCellsDataBetweenColumns ( $firstColIdx, $secondColIdx, $toSecondCol );
    }

    /*!
    */
    function copyDefinitionBetweenColumns( $firstColIdx, $secondColIdx, $toSecondCol )
    {
        $matrix      =& $this->attribute( 'matrix' );
        $columns     =& $matrix['columns']['sequential'];

        if ( $toSecondCol )
            $columns[$secondColIdx] = $columns[$firstColIdx];
        else
            $columns[$firstColIdx]  = $columns[$secondColIdx];
    }

    /*!
    */
    function copyCellsDataBetweenColumns ( $firstColIdx, $secondColIdx, $toSecondCol )
    {
        $columnCount =  $this->attribute( 'columnCount' );
        $rowCount    =  $this->attribute( 'rowCount' );
        $cells       =& $this->attribute( 'cells' );
        $firstColPos =  $firstColIdx;
        $secondColIdx=  $secondColIdx;

        // walk through rows and add elements one by one.
        while ( $rowCount > 0 )
        {
            if ( $toSecondCol )
            {
                $cells[$secondColIdx] = $cells[$firstColIdx];
            }
            else
            {
                $cells[$firstColIdx] = $cells[$secondColIdx];
            }

            $firstColIdx  += $columnCount;
            $secondColIdx += $columnCount;
            --$rowCount;
        }
    }

    /*!
        Searches in matrix columns with identifiers that in \a $matrixColumnDefinition and
        a) if column exists and \a $updateColumnsAttributesAllowed is true then modification of
           column attributes is performed( index, name, etc.);
        b) if column doesn't exists and \a $addNewColumnsAllowed then new column will be created.
    */
    function updateColumns( &$matrixColumnDefinition, $addNewColumnsAllowed, $updateColumnsAttributesAllowed )
    {
        $matrixWasModified = false;

        if ( $matrixColumnDefinition && $matrixColumnDefinition !== false )
        {
            $columns    =& $matrixColumnDefinition->attribute( 'columns' );
            foreach ( $columns as $column )
            {
                $originalColumn =& $this->getColumnDefinitionByID( $column['identifier'] );
                if ( $originalColumn !== false && $updateColumnsAttributesAllowed )
                {
                    $matrixWasModified |= $this->adjustColumnName( $originalColumn, $column['name'] );
                    $matrixWasModified |= $this->adjustColumnIndex( $originalColumn, $column['index'] );
                }
                else if ( $addNewColumnsAllowed )
                {
                    $matrixWasModified |= $this->addColumn( $column );
                }
            }
        }
        return $matrixWasModified;
    }

    /*!
        Removes columns that are in matrix but not in \a $matrixColumnDefinition
    */
    function removeUselessColumns( &$matrixColumnDefinition )
    {
        $matrixWasModified  = false;
        $columnsToRemove    =& $this->getColumnsToRemove( $matrixColumnDefinition );

        if ( count( $columnsToRemove ) > 0 )
        {
            // remove begins from last column (reverse order )
            $keys = array_keys( $columnsToRemove );
            $keys = array_reverse( $keys );

            foreach ( $keys as $key )
            {
                $column =& $columnsToRemove[$key];
                $this->removeColumn( $column );
            }

            $matrixWasModified = true;
        }

        return $matrixWasModified;
    }

    /*!
        Checks if column \a $columnDefinition has name \a $newColumnName. If so does
        nothing and returns false, otherwise changes column's name to
        \a $newColumnName and returns true.
    */
    function adjustColumnName( &$columnDefinition, $newColumnName )
    {
        if ( $columnDefinition['name'] != $newColumnName )
        {
            $this->setColumnName( $columnDefinition, $newColumnName );
            return true;
        }
        return false;
    }

    /*!
        Sets column's name to \a $newColumnName.
    */
    function setColumnName( &$columnDefinition, $newColumnName )
    {
        $columnDefinition['name'] = $newColumnName;
    }

    /*!
        Checks if column \a $columnDefinition has index \a $newColumnIndex. If so does
        nothing and returns false, otherwise changes column's index to
        \a $newColumnIndex and returns true.
    */
    function adjustColumnIndex( &$columnDefinition, $newColumnIndex )
    {
        $colIdx = $columnDefinition['index'];

        if ( $columnDefinition['index'] != $newColumnIndex )
        {
            $this->setColumnIndex( $columnDefinition, $newColumnIndex );
            return true;
        }
        return false;
    }

    /*!
        Sets column's name to \a $newColumnIndex.
    */
    function setColumnIndex( &$columnDefinition, $newColumnIndex )
    {
        $columnDefinition['index'] = $newColumnIndex;
    }

    /*!
        Searches column definition by indentifier. If column was found returns it,
        otherwise returns false.
    */
    function &getColumnDefinitionByID( &$id )
    {
        $isColumnExists = false;
        $matrix         =& $this->attribute( 'matrix' );
        $columns        =& $matrix['columns']['sequential'];

        $keys = array_keys( $columns );
        foreach ( $keys as $key )
        {
            $column =& $columns[$key];
            if ( $column['identifier'] == $id )
            {
                return $column;
            }
        }
        return $isColumnExists;
    }

    /*!
        Searches columns that are in matrix but not in \a $matrixColumnDefinition.
    */
    function &getColumnsToRemove( &$matrixColumnsDefinition )
    {
        $columnsToRemove =  array();
        $matrix          =& $this->attribute( 'matrix' );
        $columns         =& $matrix['columns']['sequential'];

        foreach ( $columns as $column )
        {
            if ( !eZMatrix::isColumnExists( $column, $matrixColumnsDefinition ) )
            {
                $columnsToRemove[] = $column;
            }
        }

        return $columnsToRemove;
    }

    /*!
        \a static
        Searches column \a $columnToFind in \a $matrixColumnDefinition.
        Returns true if found, false - otherwise.
    */
    function isColumnExists( &$columnToFind, &$matrixColumnsDefinition )
    {
        $columns =& $matrixColumnsDefinition->attribute( 'columns' );

        foreach ( $columns as $column )
        {
            if ( $column['identifier'] === $columnToFind['identifier'] )
                return true;
        }

        return false;
    }

    /*!
        Adds column \a $columnDefinition to eZMatrix object.
    */
    function addColumn( &$columnDefinition )
    {
        $this->addColumnToMatrix( $columnDefinition );
        $this->addColumnToCells( $columnDefinition );
        return true;
    }

    /*!
        Adds column \a $columnDefinition to 'matrix' member of eZMatrix.
    */
    function addColumnToMatrix( &$columnDefinition )
    {
        $matrix     =& $this->attribute( 'matrix' );
        $columns    =& $matrix['columns']['sequential'];

        $newColumn  = array( 'identifier'   => $columnDefinition['identifier'],
                             'index'        => $columnDefinition['index'],
                             'name'         => $columnDefinition['name'] );

        array_splice( $columns, $columnDefinition['index'], 0, array( $newColumn ) );
    }

    /*!
        Adds column \a $columnDefinition to 'cells' member of eZMatrix.
    */
    function addColumnToCells( &$columnDefinition )
    {
        $cells          =& $this->attribute( 'cells' );
        $columnCount    =  $this->attribute( 'columnCount' );
        $rowCount       =  $this->attribute( 'rowCount' );
        $pos            = $columnDefinition['index'];

        // walk through rows and add elements one by one.
        while ( $rowCount > 0 )
        {
            array_splice( $cells, $pos, 0, '' );
            $pos += $columnCount;
            --$rowCount;
        }
    }

    /*!
        Removess column \a $columnDefinition from eZMatrix object.
    */
    function removeColumn( &$columnDefinition )
    {
        $this->removeColumnFromCells( $columnDefinition );
        $this->removeColumnFromMatrix( $columnDefinition );
    }

    /*!
        Removess column \a $columnDefinition from 'cells' member of eZMatrix.
    */
    function removeColumnFromCells( &$columnDefinition )
    {
        $cells          =& $this->attribute( 'cells' );
        $rowCount       =  $this->attribute( 'rowCount' );
        $columnCount    =  $this->attribute( 'columnCount' );

        // last position(index) of element to remove in $cells.
        $pos =  ( $rowCount - 1 ) * $columnCount + $columnDefinition['index'];

        // walk through rows and remove elements one by one.
        while ( $rowCount > 0 )
        {
            array_splice( $cells, $pos, 1 );
            $pos -= $columnCount;
            --$rowCount;
        }
    }

    /*!
        Removess column \a $columnDefinition from 'matrix' member of eZMatrix.
    */
    function removeColumnFromMatrix( &$columnDefinition )
    {
        $matrix  =& $this->attribute( 'matrix' );
        $columns =& $matrix['columns']['sequential'];
        $pos     = 0;

        foreach ( $columns as $column )
        {
            if ( $column['identifier'] == $columnDefinition['identifier'] )
            {
                array_splice( $columns, $pos, 1 );
                return true;
            }
            ++$pos;
        }
        return false;
    }

    /*!
     Sets the name of the matrix
    */
    function setName( $name )
    {
        $this->Name = $name;
    }

    /*!
     Returns the name of the matrix.
    */
    function &name()
    {
        return $this->Name;
    }

    function hasAttribute( $name )
    {
        if ( $name == "name" ||  $name == "rows" ||  $name == "columns" ||  $name == "matrix" || $name == "cells" || $name == "rowCount" || $name == "columnCount" )
            return true;
        else
            return false;
    }

    function &attribute( $name )
    {
        switch ( $name )
        {
            case "name" :
            {
                return $this->Name;
            }break;
            case "matrix" :
            {
                return $this->Matrix;
            }break;
            case "cells" :
            {
                return $this->Cells;
            }break;
            case "rows" :
            {
                return $this->Matrix['rows'];
            }break;
            case "columns" :
            {
                return $this->Matrix['columns'];
            }break;
            case "rowCount" :
            {
                return count( $this->Matrix['rows']['sequential'] );
            }break;
            case "columnCount" :
            {
                return count( $this->Matrix['columns']['sequential'] );
            }break;

        }
    }

    function addRow( $beforeIndex = false, $addCount = 1 )
    {
        $addCount = min( $addCount, 40 );

        for ( $r = $addCount; $r > 0; $r-- )
        {
            $newCells = array();
            $numColumns = $this->attribute( 'columnCount' );
            $numRows = $this->attribute( 'rowCount' );
            for ( $i = 0; $i < $numColumns; $i++ )
            {
                $newCells[] = '';
            }
            $newRow = array();
            $newRow['columns'] = $newCells;
            $this->NumRows++;
            if ( $beforeIndex === false )
            {
                $this->Cells = array_merge( $this->Cells, $newCells );
                $newRow['identifier'] =  'row_' . ( $numRows + 1 );
                $newRow['name'] = 'Row_' . ( $numRows + 1 );
                $this->Matrix['rows']['sequential'][] = $newRow;

            }
            else
            {
                $insertIndex  = ( $beforeIndex + 1 ) * $numColumns - $numColumns;
                array_splice( $this->Cells, $insertIndex, 0, $newCells );
                $newRow['identifier'] =  'row_' . $beforeIndex;
                $newRow['name'] = 'Row_' . ( $numRows + 1 );
                array_splice( $this->Matrix['rows']['sequential'], $beforeIndex, 0,  array( $newRow ) );

            }
        }
	}

    function removeRow( $rowNum )
    {
        $numColumns = $this->attribute( 'columnCount' );
        $numRows    = $this->attribute( 'rowCount' );

        array_splice( $this->Cells, $rowNum * $numColumns, $numColumns );
        array_splice( $this->Matrix['rows']['sequential'], $rowNum, 1 );
        $this->NumRows--;
    }

    /*!
     Will decode an xml string and initialize the eZ matrix object
    */
    function decodeXML( $xmlString )
    {
        $xml = new eZXML();
        $dom =& $xml->domTree( $xmlString );
        if ( $xmlString != "" )
        {
            // set the name of the node
            $nameArray =& $dom->elementsByName( "name" );
            $this->setName( $nameArray[0]->textContent() );

            $columns = & $dom->elementsByName( "columns" );
            $numColumns = $columns[0]->attributeValue( 'number');

            $rows = & $dom->elementsByName( "rows" );
            $numRows = $rows[0]->attributeValue( 'number');

            $namedColumns =& $dom->elementsByName( "column" );
            $namedColumnList = array();
            if ( count( $namedColumns ) > 0 )
            {
                foreach ( array_keys( $namedColumns ) as $key )
                {
                    $namedColumn =& $namedColumns[$key];
                    $columnName =& $namedColumn->textContent();
                    $columnID = $namedColumn->attributeValue( 'id' );
                    $columnNumber = $namedColumn->attributeValue( 'num' );
                    $namedColumnList[$columnNumber] = array( 'name' => $columnName,
                                                             'column_number' => $columnNumber,
                                                             'column_id' => $columnID );
                }
            }
            $cellArray =& $dom->elementsByName( "c" );
            $cellCount = count( $cellArray );
            $cellList = array();
            for ( $i = 0; $i < $cellCount; ++$i )
            {
                $cellList[] =& $cellArray[$i]->textContent();
            }

            $rows = array( 'sequential' => array() );
            $sequentialRows =& $rows['sequential'];

            for ( $i = 1; $i <= $numRows; $i++ )
            {
                $row = array( 'identifier' => 'row_' . $i ,
                              'name' => 'Row_' . $i );
                $rowColumns = array();
                for ( $j = 1; $j <= $numColumns; $j++ )
                {
                    $rowColumns[] = $cellList[ ($i-1) * $numColumns + $j-1];
                }
                $row['columns'] = $rowColumns;
                $sequentialRows[] = $row;
            }

            $columns = array( 'sequential' => array(),
                              'id' => array() );
            $sequentialColumns =& $columns['sequential'];
            $idColumns =& $columns['id'];

            for ( $i = 0; $i < $numColumns; $i++ )
            {
                if ( isset( $column ) )
                    unset( $column );
                $column = array();
                if ( isset( $namedColumnList[$i] ) && is_array( $namedColumnList[$i] ) )
                {
                    $column = array( 'identifier' => $namedColumnList[$i]['column_id'],
                                     'index' => $i,
                                     'name' => $namedColumnList[$i]['name'] );
                }
                else
                {
                    $column = array( 'identifier' => 'col_' . ($i + 1),
                                     'index' => $i,
                                     'name' => 'Col_' . ($i + 1) );

                }

                $columnRows = array();
                for( $j = 0; $j < $numRows; $j++ )
                {
                    $columnRows[] =& $sequentialRows[$j]['columns'][$i];
                }
                $column['rows'] =& $columnRows;
                unset( $columnRows );
                $sequentialColumns[] =& $column;
                $idColumns[$column['identifier']] =& $column;

            }
            $matrix = array( 'rows' => &$rows,
                             'columns' => &$columns,
                             'cells' => &$cellList );

            $this->Matrix =& $matrix;
            $this->NumRows =& $numRows;
            $this->NumColumns =& $numColumns;
            $this->Cells =& $cellList;
        }
        else
        {
            $this->Matrix = array();
        }
    }

    /*!
     Will return the XML string for this matrix.
    */
    function &xmlString( )
    {
        $doc = new eZDOMDocument( "Matrix" );
        $root =& $doc->createElementNode( "ezmatrix" );
        $doc->setRoot( $root );

        $name =& $doc->createElementNode( "name" );
        $nameValue =& $doc->createTextNode( $this->Name );
        $name->appendChild( $nameValue );

        $name->setContent( $this->Name() );

        $root->appendChild( $name );


        $columnsNode =& $doc->createElementNode( "columns" );


        $sequentalColumns =& $this->Matrix['columns']['sequential'];
//        $columnAmount = count(  $sequentalColumns );
        $columnAmount = $this->NumColumns;
        $columnsNode->appendAttribute( $doc->createAttributeNode( 'number', $columnAmount ) );
        $root->appendChild( $columnsNode );

        if ( $sequentalColumns != null )
        {
            for( $i = 0; $i < $columnAmount; $i++ )
            {
                $column =& $sequentalColumns[$i];
                if( $column != null && $column['identifier'] != 'col_'. $i+1 )
                {
                    $columnNode =& $doc->createElementNode( 'column' );
                    $columnNode->appendAttribute( $doc->createAttributeNode( 'num', $i ) );
                    $columnNode->appendAttribute( $doc->createAttributeNode( 'id', $column['identifier'] ) );

                    $columnValueNode =& $doc->createTextNode( $column["name"] );

                    $columnNode->appendChild( $columnValueNode );
                    $columnsNode->appendChild( $columnNode );
                }
            }

        }
//        $rows = & $dom->elementsByName( "rows" );

        $rowsNode =&  $doc->createElementNode( "rows" );
//        $rowAmount = count( $this->Matrix['rows'] );
        $rowAmount = $this->NumRows;

        $rowsNode->appendAttribute( $doc->createAttributeNode( 'number', $rowAmount ) );

        $root->appendChild( $rowsNode );


        foreach ( $this->Cells as $cell )
        {
            $cellNode =& $doc->createElementNode( 'c' );
            $columnValueNode =& $doc->createTextNode( $cell );

            $cellNode->appendChild( $columnValueNode );
            $root->appendChild( $cellNode );
        }

        $xml =& $doc->toString();

        return $xml;
    }

    /// Contains the Matrix name
    var $Name;

    /// Contains the Matrix array
    var $Matrix;

    /// Contains the number of columns
    var $NumColumns;

    /// Contains the number of rows

    var $NumRows;
    var $Cells;



}
/*
$content = array( 'rows' => array( array( 'identifier' => 'some',
                                         'name' => 'Some',
                                         'columns' => array( 1, "test", 5 ) ),
                                  array( 'identifier' => 'some2',
                                         'name' => 'Some2',
                                         'columns' => array( 2, "test2", 10 ) ) ),
                 'columns' => array( 'id' => array( 'c1' => &array( 'identifier' => 'c1',
                                                                    'name' => 'C1',
                                                                    'index' => 1,
                                                                    'columns' => array( 1, 2 ) ) ),
                                     'sequential' => array( array( 'identifier' => 'c1',
                                                                   'name' => 'C1',
                                                                   'columns' => array( 1, 2 ) ),
                                                            array( 'identifier' => 'c2',
                                                                   'name' => 'C2',
                                                                   'columns' => array( "test", "test2" ) ),
                                                            array( 'identifier' => 'c3',
                                                                   'name' => 'C3',
                                                                   'columns' => array( 5, 10 ) ) ) );

'<input type="text" name="_c1_r1" ';

$matrix = array( array( 1, "test", 5 ),
                array( 2, "test2", 10 ) );
$matrix[1][1] = 42;
*/
?>
