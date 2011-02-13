<?php
//
// Definition of eZDiffMatrix class
//
// <05-Apr-2006 14:42:42>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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
  eZDiffMatrix class
*/

/*!
  \class eZDiffMatrix ezdiffmatrix.php
  \ingroup eZDiff
  \biref This class will store values concerned with diff data

  The eZDiffMatrix class will avoid storing 0, which for a large matrix will save
  memory.
*/

class eZDiffMatrix
{

    /*!
      Constructor
    */
    function eZDiffMatrix( $rows = null, $cols = null)
    {
        if ( isset( $rows ) && is_numeric( $rows ) )
            $this->Rows = $rows;

        if ( isset( $cols ) && is_numeric( $cols ) )
            $this->Cols = $cols;
    }

    /*!
      \public
      Sets the dimensions of the matrix
    */
    function setSize( $nRows, $nCols )
    {
        $this->Rows = $nRows;
        $this->Cols = $nCols;
    }

    /*!
      \public
      This method will set (\a $row, \a $col) in the matrix to \a $value, if
      it is not zero.
    */
    function set( $row, $col, $value )
    {
        if ( $value !== 0 )
        {
            $pos = $row * $this->Cols + $col;
            $pos = base_convert( $pos, 10, 36 );
            $this->Matrix["*$pos"] = $value;
        }
    }

    /*!
      \public
      This method will return the value at position (\a $row, \a $col)
    */
    function get( $row, $col )
    {
        $pos = $row * $this->Cols + $col;
        $pos = base_convert( $pos, 10, 36 );
        return isset( $this->Matrix["*$pos"] ) ? $this->Matrix["*$pos"] : 0;
    }

    ///\privatesection
    /// Internal array, holding necessary values.
    public $Matrix = array();

    /// Internal variable, width of the matrix.
    public $Cols;

    /// Internal variable, height of the matrix.
    public $Rows;
}

?>
