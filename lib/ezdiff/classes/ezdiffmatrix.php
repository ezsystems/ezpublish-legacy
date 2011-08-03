<?php
/**
 * File containing the eZDiffMatrix class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
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
