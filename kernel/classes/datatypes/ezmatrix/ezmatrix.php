<?php
//
// Definition of eZMatrix class
//
// Created on: <30-May-2003 16:46:50 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

/*! \file
*/

/*!
  \class eZMatrix ezmatrix.php
  \ingroup eZDatatype
  \brief The class eZMatrix does

*/

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
            $this->Matrix['columns']['sequential'] = $sequentialColumns;

            $this->NumRows = $numRows;
            $cells = array();
            for ( $i = 0; $i < $numColumns; ++$i )
            {
                for ( $j = 0; $j < $numRows; ++$j )
                {
                    $cells[] = '';
                }
            }
            $this->Cells = $cells;


            $xmlString = $this->xmlString();
            $this->decodeXML( $xmlString );
        }
    }

    /*!
        Check if column index differs, and so, set new index.

        \param columnIndex internal column index
        \param newColumnIndex new column index

        \return true if index differs
    */
    function adjustColumnIndex( $columnIndex, $newColumnIndex )
    {
        $matrix = $this->attribute( 'matrix' );
        $columnDefinition = $matrix['columns']['sequential'][$columnIndex];
        if ( $columnDefinition['index'] != $newColumnIndex )
        {
            $this->setColumnIndex( $columnIndex, $newColumnIndex );
            return true;
        }
        return false;
    }

    /*!
        Sets column's index to \a $newColumnIndex.
    */
    function setColumnIndex( $columnIndex, $newColumnIndex )
    {
        $this->Matrix['columns']['sequential'][$columnIndex]['index'] = $newColumnIndex;
    }

    /*!
        Searches in matrix columns with identifiers that in \a $matrixColumnDefinition an
        a) if column exists and \a $updateColumnsAttributesAllowed is true then modification of
           column attributes is performed( index, name, etc.);
        b) if column doesn't exists and \a $addNewColumnsAllowed then new column will be created.
    */
    protected function updateColumns( $matrixColumnDefinition )
    {
        $matrixWasModified = false;

        if ( $matrixColumnDefinition && $matrixColumnDefinition !== false )
        {
            $columns = $matrixColumnDefinition->attribute( 'columns' );
            foreach ( $columns as $column )
            {
                $columnIndex = $this->columnIndex( $column['identifier'] );
                if ( $columnIndex !== false )
                {
                    $matrixWasModified |= $this->adjustColumnName( $columnIndex, $column['name'] );
                    $matrixWasModified |= $this->adjustColumnIndex( $columnIndex, $column['index'] );
                }
                else
                {
                    $matrixWasModified |= $this->addColumn( $column );
                }
            }
        }
        return $matrixWasModified;
    }

    /*!
        Adds column \a $columnDefinition to eZMatrix object.
    */
    function addColumn( $columnDefinition )
    {
        $this->addColumnToMatrix( $columnDefinition );
        $this->addColumnToCells( $columnDefinition );
        return true;
    }

    /*!
        Adds column \a $columnDefinition to 'matrix' member of eZMatrix.
    */
    function addColumnToMatrix( $columnDefinition )
    {
        $newColumn  = array( 'identifier'   => $columnDefinition['identifier'],
                             'index'        => $columnDefinition['index'],
                             'name'         => $columnDefinition['name'] );

        array_splice( $this->Matrix['columns']['sequential'], $columnDefinition['index'], 0, array( $newColumn ) );
    }

    /*!
        Adds column \a $columnDefinition to 'cells' member of eZMatrix.
    */
    function addColumnToCells( $columnDefinition )
    {
        $cells = $this->attribute( 'cells' );
        $columnCount = $this->attribute( 'columnCount' );
        $rowCount = $this->attribute( 'rowCount' );
        $pos = $columnDefinition['index'];

        // walk through rows and add elements one by one.
        while ( $rowCount > 0 )
        {
            array_splice( $this->Cells, $pos, 0, '' );
            $pos += $columnCount;
            --$rowCount;
        }
    }

    /*!
        Check if new column name differs from existing column name, and sets new name.

        \param columnIndex internal column index
        \param newColumnName column name

        \return true if name differs
    */
    function adjustColumnName( $columnIndex, $newColumnName )
    {
        $matrix = $this->attribute( 'matrix' );
        $columnDefinition = $matrix['columns']['sequential'][$columnIndex];
        if ( $columnDefinition['name'] != $newColumnName )
        {
            $this->setColumnName( $columnIndex, $newColumnName );
            return true;
        }
        return false;
    }

    /*!
        Sets column's name to \a $newColumnName.
    */
    function setColumnName( $columnIndex, $newColumnName )
    {
        $this->Matrix['columns']['sequential'][$columnIndex]['name'] = $newColumnName;
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
        $matrixWasModified |= $this->updateColumns( $classColumnsDefinition, true, true );

        if ( $matrixWasModified )
        {
            $this->reorderColumns();

            $columns          = $classColumnsDefinition->attribute( 'columns' );
            $numColumns       =  count( $columns );
            $this->NumColumns =  $numColumns;

            $xmlString        = $this->xmlString();
            $this->decodeXML( $xmlString );
        }

        return $matrixWasModified;
    }

    /*!
        Create reorder column reference array.
    */
    function buildReorderRuleForColumn( $columns, $pos )
    {
        $rule = array( $pos );
        $startPos = $pos;

        $column = $columns[$pos];
        while( $column['index'] != $startPos )
        {
            $pos = $column['index'];
            $rule[] = $pos;
            $column = $columns[$pos];
        }

        return $rule;
    }

    /*!
        Build column reorder rules.
    */
    function buildReorderRules()
    {
        $matrix     = $this->attribute( 'matrix' );
        $columns    = $matrix['columns']['sequential'];
        $rules      = array();
        $positions  = array_keys( $columns );

        foreach ( $columns as $pos => $column )
        {
            if ( $this->hasRuleForColumn( $rules, $pos ) )
            {
                continue;
            }

            if ( $column['index'] != $pos )
            {
                $rules[] = $this->buildReorderRuleForColumn( $columns, $pos );
            }
        }
        return $rules;
    }

    function reorderColumns()
    {
        $rules = $this->buildReorderRules();

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
            // last column in rule
            $pos = count( $rule ) - 1;

            $column = $this->column( $rule[$pos] );

            while ( $pos != 0 )
            {
                $this->copyDataBetweenColumns( $rule[$pos - 1], $rule[$pos]);
                --$pos;
            }

            // first column in rule
            $this->setColumn( $rule[0], $column );
        }
    }

    /*!
        \a static
    */
    function hasRuleForColumn( $rules, $pos )
    {
        foreach ( $rules as $rule )
        {
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
     Set column data and definition

     \param column index
     \param column data and definition
    */
    function setColumn( $colIdx, $column )
    {
        $this->setColumnDefinition( $colIdx, $column['columnDefinition'] );
        $this->setColumnCellData( $colIdx, $column['cellsData'] );
    }

    /*!
     Get column data and definition

     \param colIdx column index

     \return column data and definition
    */
    function column( $colIdx )
    {
        return array( 'columnDefinition' => $this->columnDefinition( $colIdx ),
                      'cellsData' => $this->columnCellsData ( $colIdx ) );
    }

    /*!
     Set column definition.

     \param colIdx column index
     \param columnDefinition column definition
    */
    protected function setColumnDefinition( $colIdx, $columnDefinition )
    {
        $this->Matrix['columns']['sequential'][$colIdx] = $columnDefinition;
    }

    /*!
     Get column definition.

     \param colIdx column index

     \return column definition
    */
    protected function columnDefinition( $colIdx )
    {
        $matrix = $this->attribute( 'matrix' );
        return $matrix['columns']['sequential'][$colIdx];
    }

    /*!
     Set column cell data

     \param colIdx column index
     \param cellData column definition
    */
    protected function setColumnCellData( $colIdx, $cellData )
    {
        $columnCount = $this->attribute( 'columnCount' );
        $dataCount = count( $cellData );
        $dataOffset = 0;
        $cellOffset = $colIdx;

        for( $dataOffset = 0 ; $dataOffset < $dataCount; $dataOffset++ )
        {
            $this->Cells[$cellOffset] = $cellData[$dataOffset];
            $cellOffset += $columnCount;
        }
    }

    /*!
     Get column data

     \param colIdx column index

     \return column data
    */
    protected function columnCellsData( $colIdx )
    {
        $retArray = array();
        $columnCount = $this->attribute( 'columnCount' );
        $rowCount = $this->attribute( 'rowCount' );
        $cells = $this->attribute( 'cells' );
        $pos = $colIdx;

        // walk through rows and add elements one by one.
        while ( $rowCount > 0 )
        {
            $retArray[] = $cells[$pos];
            $pos += $columnCount;
            --$rowCount;
        }

        return $retArray;
    }

    function copyDataBetweenColumns( $firstColIdx, $secondColIdx )
    {
        $this->copyDefinitionBetweenColumns( $firstColIdx, $secondColIdx );
        $this->copyCellsDataBetweenColumns ( $firstColIdx, $secondColIdx );
    }

    protected function copyDefinitionBetweenColumns( $col1, $col2 )
    {
        $this->Matrix['columns']['sequential'][$col2] = $this->Matrix['columns']['sequential'][$col1];
    }

    protected function copyCellsDataBetweenColumns ( $firstColIdx, $secondColIdx )
    {
        $columnCount = $this->attribute( 'columnCount' );
        $rowCount = $this->attribute( 'rowCount' );
        $firstColPos = $firstColIdx;
        $secondColIdx= $secondColIdx;

        // walk through rows and add elements one by one.
        while ( $rowCount > 0 )
        {
            $this->Cells[$secondColIdx] = $this->Cells[$firstColIdx];
            $firstColIdx  += $columnCount;
            $secondColIdx += $columnCount;
            --$rowCount;
        }
    }

    /*!
        Removes columns that are in matrix but not in \a $matrixColumnDefinition

      \return true if matrix was modified.
    */
    function removeUselessColumns( $matrixColumnDefinition )
    {
        $columnsToRemove = $this->getColumnsToRemove( $matrixColumnDefinition );

        if ( count( $columnsToRemove ) > 0 )
        {
            // remove begins from last column (reverse order )
            foreach ( array_reverse( $columnsToRemove ) as $column )
            {
                $this->removeColumn( $column );
            }
            return true;
        }

        return false;
    }

    /*!
        Searches columns that are in matrix but not in \a $matrixColumnDefinition.
    */
    function getColumnsToRemove( $matrixColumnsDefinition )
    {
        $columnsToRemove = array();
        $matrix          = $this->attribute( 'matrix' );
        $columns         = $matrix['columns']['sequential'];

        foreach ( $columns as $column )
        {
            if ( !$this->columnExists( $column, $matrixColumnsDefinition ) )
            {
                $columnsToRemove[] = $column;
            }
        }

        return $columnsToRemove;
    }

    /*!
     Get internal column index by column indentifier

     \param columnIdent column identifier

     \return column index.
    */
    protected function columnIndex( $columnIdent )
    {
        $matrix = $this->attribute( 'matrix' );
        $columns = $matrix['columns']['sequential'];

        foreach( $columns as $key => $column )
        {
            if ( $column['identifier'] == $columnIdent )
            {
                return $key;
            }
        }

        return false;
    }

    /*!
        Searches column \a $columnToFind in \a $matrixColumnDefinition.
        Returns true if found, false - otherwise.
    */
    protected function columnExists( $columnToFind, $matrixColumnsDefinition )
    {
        $columns = $matrixColumnsDefinition->attribute( 'columns' );

        foreach ( $columns as $column )
        {
            if ( $column['identifier'] === $columnToFind['identifier'] )
                return true;
        }

        return false;
    }

    /*!
        Removess column \a $columnDefinition from eZMatrix object.
    */
    function removeColumn( $columnDefinition )
    {
        $this->removeColumnFromCells( $columnDefinition );
        $this->removeColumnFromMatrix( $columnDefinition );
    }

    /*!
        Removess column \a $columnDefinition from 'cells' member of eZMatrix.
    */
    protected function removeColumnFromCells( $columnDefinition )
    {
        $cells          = $this->attribute( 'cells' );
        $rowCount       = $this->attribute( 'rowCount' );
        $columnCount    = $this->attribute( 'columnCount' );

        // last position(index) of element to remove in $cells.
        $pos =  ( $rowCount - 1 ) * $columnCount + $columnDefinition['index'];

        // walk through rows and remove elements one by one.
        while ( $rowCount > 0 )
        {
            array_splice( $this->Cells, $pos, 1 );
            $pos -= $columnCount;
            --$rowCount;
        }
    }

    /*!
        Removess column \a $columnDefinition from 'matrix' member of eZMatrix.
    */
    protected function removeColumnFromMatrix( $columnDefinition )
    {
        $matrix  = $this->attribute( 'matrix' );
        $columns = $matrix['columns']['sequential'];
        $pos     = 0;

        foreach ( $columns as $column )
        {
            if ( $column['identifier'] == $columnDefinition['identifier'] )
            {
                array_splice( $this->Matrix['columns']['sequential'], $pos, 1 );
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
    function name()
    {
        return $this->Name;
    }

    function attributes()
    {
        return array( 'name' ,
                      'rows',
                      'columns',
                      'matrix',
                      'cells',
                      'rowCount',
                      'columnCount' );
    }

    function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

    function attribute( $name )
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
            default:
            {
                eZDebug::writeError( "Attribute '$name' does not exist", 'eZMatrix::attribute' );
                return null;
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
        $dom = new DOMDocument( '1.0', 'utf-8' );
        $success = $dom->loadXML( $xmlString );
        if ( $xmlString != "" )
        {
            // set the name of the node
            $nameArray = $dom->getElementsByTagName( "name" );
            $this->setName( $nameArray->item( 0 )->textContent );

            $columnsNode = $dom->getElementsByTagName( "columns" )->item( 0 );
            $numColumns = $columnsNode->getAttribute( 'number');

            $rowsNode = $dom->getElementsByTagName( "rows" )->item( 0 );
            $numRows = $rowsNode->getAttribute( 'number' );

            $namedColumns = $dom->getElementsByTagName( "column" );
            $namedColumnList = array();
            if ( $namedColumns->length > 0 )
            {
                foreach ( $namedColumns as $namedColumn )
                {
                    $columnName = $namedColumn->textContent;
                    $columnID = $namedColumn->getAttribute( 'id' );
                    $columnNumber = $namedColumn->getAttribute( 'num' );
                    $namedColumnList[$columnNumber] = array( 'name' => $columnName,
                                                             'column_number' => $columnNumber,
                                                             'column_id' => $columnID );
                }
            }
            $cellNodes = $dom->getElementsByTagName( "c" );
            $cellList = array();
            foreach ( $cellNodes as $cellNode )
            {
                $cellList[] = $cellNode->textContent;
            }

            $rows = array( 'sequential' => array() );
            $sequentialRows = array();

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
            $rows['sequential'] = $sequentialRows;

            $columns = array( 'sequential' => array(),
                              'id' => array() );
            $sequentialColumns = array();
            $idColumns = array();

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
                    $columnRows[] = $sequentialRows[$j]['columns'][$i];
                }
                $column['rows'] = $columnRows;
                $sequentialColumns[] = $column;
                $idColumns[$column['identifier']] = $column;
            }
            $columns['sequential'] = $sequentialColumns;
            $columns['id'] = $idColumns;

            $matrix = array( 'rows' => $rows,
                             'columns' => $columns,
                             'cells' => $cellList );

            $this->Matrix = $matrix;
            $this->NumRows = $numRows;
            $this->NumColumns = $numColumns;
            $this->Cells = $cellList;
        }
        else
        {
            $this->Cells = array();
            $this->Matrix = array();
        }
    }
    /*!
     \return the XML structure in \a $domDocument as text.
             It will take of care of the necessary charset conversions
             for content storage.
    */
    function domString( $domDocument )
    {
        $ini = eZINI::instance();
        $xmlCharset = $ini->variable( 'RegionalSettings', 'ContentXMLCharset' );
        if ( $xmlCharset == 'enabled' )
        {
            $charset = eZTextCodec::internalCharset();
        }
        else if ( $xmlCharset == 'disabled' )
            $charset = true;
        else
            $charset = $xmlCharset;
        if ( $charset !== true )
        {
            $charset = eZCharsetInfo::realCharsetCode( $charset );
        }
        $domString = $domDocument->saveXML();
        return $domString;
    }

    /*!
     Will return the XML string for this matrix.
    */
    function xmlString()
    {
        $doc = new DOMDocument( '1.0', 'utf-8' );
        $root = $doc->createElement( "ezmatrix" );
        $doc->appendChild( $root );

        $name = $doc->createElement( "name", $this->Name );
        $root->appendChild( $name );

        $columnsNode = $doc->createElement( "columns" );

        $sequentalColumns = $this->Matrix['columns']['sequential'];
        $columnAmount = $this->NumColumns;
        $columnsNode->setAttribute( 'number', $columnAmount );
        $root->appendChild( $columnsNode );

        if ( $sequentalColumns != null )
        {
            for( $i = 0; $i < $columnAmount; $i++ )
            {
                $column = $sequentalColumns[$i];
                if ( $column != null && $column['identifier'] != 'col_' . ($i+1) )
                {
                    unset( $columnNode );
                    $columnNode = $doc->createElement( 'column' );
                    $columnNode->appendChild( $doc->createTextNode( $column['name'] ) );
                    $columnNode->setAttribute( 'num', $i );
                    $columnNode->setAttribute( 'id', $column['identifier'] );

                    $columnsNode->appendChild( $columnNode );
                }
            }

        }

        $rowsNode =  $doc->createElement( "rows" );
        $rowAmount = $this->NumRows;

        $rowsNode->setAttribute( 'number', $rowAmount );

        $root->appendChild( $rowsNode );

        foreach ( $this->Cells as $cell )
        {
            unset( $cellNode );
            $cellNode = $doc->createElement( 'c' );
            $cellNode->appendChild( $doc->createTextNode( $cell ) );

            $root->appendChild( $cellNode );
        }

        return $this->domString( $doc );
    }

    /// Contains the Matrix name
    public $Name;

    /// Contains the Matrix array
    public $Matrix;

    /// Contains the number of columns
    public $NumColumns;

    /// Contains the number of rows

    public $NumRows;
    public $Cells;
}

?>
