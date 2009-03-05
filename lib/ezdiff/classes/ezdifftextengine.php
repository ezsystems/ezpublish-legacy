<?php
//
// Definition of eZDiffTextEngine class
//
// <creation-tag>
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
  eZDiffTextEngine class
*/

/*!
  \class eZDiffTextEngine ezdifftextengine.php
  \ingroup eZDiff
  \brief eZDiff provides an access point the diff system

  The eZDiffEngine class is an abstract class, providing interface and shared code
  for the different available DiffEngine.
*/

class eZDiffTextEngine extends eZDiffEngine
{
    function eZDiffTextEngine()
    {
    }

    /*!
      This function calculates changes in plain text and creates an object to hold
      overview of changes.
    */
    function createDifferenceObject( $fromData, $toData )
    {
        $pattern = array( '/[ ][ ]+/',
                          '/ \n( \n)+/',
                          '/^ /m',
                          '/(\n){3,}/' );
        $replace = array( ' ',
                          "\n",
                          '',
                          "\n\n" );

        $old = preg_replace( $pattern, $replace, $fromData );
        $new = preg_replace( $pattern, $replace, $toData );

        $oldArray = split( "\r\n", $old );
        $newArray = split( "\r\n", $new );

        $oldSums = array();
        foreach( $oldArray as $paragraph )
        {
            $oldSums[] = crc32( $paragraph );
        }

        $newSums = array();
        foreach( $newArray as $paragraph )
        {
            $newSums[] = crc32( $paragraph );
        }

        $changes = new eZTextDiff();

        $pre = $this->preProcess( $oldSums, $newSums );
        $out = $this->createOutput( $pre, $oldArray, $newArray );

        $changes->setChanges( $out );

        return $changes;
    }

    /*!
      This Method will create the differences array which is processed by the templates
    */
    function createOutput( $arr, $oldArray, $newArray )
    {
        $diff = array();

        foreach( $arr as $item )
        {
            $old = null;
            $new = null;
            foreach ( $item as $par => $state )
            {
                switch ( $state )
                {
                    case 'unchanged':
                    {
                        $index = $par;
                    }break;

                    case 'changed':
                    {
                        $old = $oldArray[$par];
                        $new = $newArray[$par];
                    }break;

                    case 'added':
                    {
                        $new = $newArray[$par];
                    }break;

                    case 'removed':
                    {
                        $old = $oldArray[$par];
                    }break;
                }
            }

            if ( $old === null )
            {
                if ( $new === null )
                {
                    // unchanged paragraph
                    $text = $newArray[$index];
                    $this->addNewLine( $text );
                    $diff[] = array( 'unchanged' => $text,
                                     'status' => 0 );
                }
                else
                {
                    // added paragraph
                    $text = $new;
                    $this->addNewLine( $text );
                    $diff[] = array( 'added' => $text,
                                     'status' => 2 );
                }
            }
            elseif ( $new === null )
            {
                // removed paragraph
                $text = $old;
                $this->addNewLine( $text );
                $diff[] = array( 'removed' => $text,
                                 'status' => 1 );
            }
            else
            {
                // changed paragraph
                $diffText = $this->buildDiff( explode( ' ', $old ), explode( ' ', $new ) );
                $size = count( $diffText ) - 1;

                foreach( $diffText as $number => $change )
                {
                    $state = $change['status'];
                    switch( $state )
                    {
                        case '0':
                        {
                            if ( $number == $size )
                                $this->addNewLine( $change['unchanged'] );
                        }break;

                        case '1':
                        {
                            if ( $number == $size )
                                $this->addNewLine( $change['removed'] );
                        }break;

                        case '2':
                        {
                            if ( $number == $size )
                                $this->addNewLine( $change['added'] );
                        }break;
                    }
                    $diff[] = $change;
                }
            }
        }
        $output = $this->postProcessDiff( $diff );
        return $output;
    }

    /*!
      \private
      This method will add a newline after unempty paragraphs
    */
    function addNewLine( &$text )
    {
        $text .= "\n";
    }

    /*!
      This method will determine which paragraphs which need to be diffed.
    */
    function preProcess( $oldArray, $newArray )
    {
        $substr = $this->substringMatrix( $oldArray, $newArray );

        $nOldWords = count( $oldArray );
        $nNewWords = count( $newArray );

        /*
        $tmp = $substr['lengthMatrix'];
        print( "<pre>" );
        $this->dumpMatrix( $tmp, $nOldWords, $nNewWords );
        print( "</pre>" );
        */

        $strings = $this->substrings( $substr, $oldArray, $newArray );

        //Merge detected paragraphs
        $mergedStrings = array();
        foreach ( $strings as $sstring )
        {
            $mergedStrings = $mergedStrings + $sstring;
        }
        unset( $strings );

        //Check for changes in lead & inner paragraphs
        $offset = 0;
        $delOffset = 0;
        $internalOffset = 0;
        $merged = array();

        $oldOffset = -1;

        foreach ( $mergedStrings as $key => $wordArray )
        {
            if ( $oldOffset >= $wordArray['oldOffset'] )
            {
                continue;
            }

            $oldOffset = $wordArray['oldOffset'];

            //Check for inserted paragraphs
            $nk = $internalOffset;
            while ( $key > $offset )
            {
                $merged[$nk] = array( $offset => 'added' );
                $nk++;
                $offset++;
            }

            //Check for removed paragraphs
            $k = $internalOffset;
            while ( $oldOffset > $delOffset )
            {
                if ( $k < $nk )
                {
                    // Paragraph is changed
                    if ( array_key_exists( $delOffset, $merged[$k] ) )
                    {
                        // Old & new paragraph places is the same
                        $merged[$k][$delOffset] = 'changed';
                    }
                    else
                    {
                        $merged[$k][$delOffset] = 'removed';
                    }
                }
                else
                {
                    $merged[$k] = array( $delOffset => 'removed' );
                }
                $k++;
                $delOffset++;
            }

            $internalOffset = ($k > $nk) ? $k:$nk;

            //The default state - unchanged paragraph
            $merged[$internalOffset] = array( $key => 'unchanged' );
            $internalOffset++;
            $delOffset++;
            $offset++;
        }

        //Check for appended paragraphs
        $nk = $internalOffset;
        while ( $nNewWords > $offset )
        {
            $merged[$nk] = array( $offset => 'added' );
            $nk++;
            $offset++;
        }

        //Check for end-deleted paragraphs
        $k = $internalOffset;
        while ( $nOldWords > $delOffset )
        {
            if ( $k < $nk )
            {
                if ( array_key_exists( $delOffset, $merged[$k] ) )
                {
                    $merged[$k][$delOffset] = 'changed';
                }
                else
                {
                    $merged[$k][$delOffset] = 'removed';
                }
            }
            else
            {
                $merged[$k] = array( $delOffset => 'removed' );
            }
            $k++;
            $delOffset++;
        }

        return $merged;
    }


    /*!
      \private
      This method will contruct a diff output for plain text.
    */
    function buildDiff( $oldArray, $newArray )
    {
        $substr = $this->substringMatrix( $oldArray, $newArray );

        $nOldWords = count( $oldArray );
        $nNewWords = count( $newArray );

        /*
        $tmp = $substr['lengthMatrix'];
        print( "<pre>" );
        $this->dumpMatrix( $tmp, $nOldWords, $nNewWords );
        print( "</pre>" );
        */

        $strings = $this->substrings( $substr, $oldArray, $newArray );
        $len = count( $strings );

        //Merge detected substrings
        $mergedStrings = array();
        foreach ( $strings as $sstring )
        {
            $mergedStrings = $mergedStrings + $sstring;
        }
        unset( $strings );

        //Check for changes in lead & inner words
        $differences = array();
        $offset = 0;
        $delOffset = 0;

        $oldOffset = -1;

        foreach ( $mergedStrings as $key => $wordArray )
        {
            if ( $oldOffset >= $wordArray['oldOffset'] )
            {
                continue;
            }

            $oldOffset = $wordArray['oldOffset'];

            // Added words
            while ( $key > $offset )
            {
                $differences[] = array( 'added' => $newArray[$offset],
                                        'status' => 2 );
                $offset++;
            }

            // Removed words
            while ( $oldOffset > $delOffset )
            {
                $differences[] = array( 'removed' => $oldArray[$delOffset],
                                        'status' => 1 );
                $delOffset++;
            }

            //The default state - unchanged paragraph
            $differences[] = array( 'unchanged' => $newArray[$key],
                                    'status' => 0 );
            $delOffset++;
            $offset++;
        }

        // Appended words
        while ( $nNewWords > $offset )
        {
            $differences[] = array( 'added' => $newArray[$offset],
                                    'status' => 2 );
            $offset++;
        }

        // Words, removed at the paragraph end
        while ( $nOldWords > $delOffset )
        {
            $differences[] = array( 'removed' => $oldArray[$delOffset],
                                    'status' => 1 );
            $delOffset++;
        }

        $output = $this->postProcessDiff( $differences );
        return $output;

    }

    /*!
      \private
      This method will chain together similar changes, in order to create a more connected output.
    */
    function postProcessDiff( $diffArray )
    {
        $string = "";
        $diff = array();
        $item = current( $diffArray );

        $lastStatus = $item['status'];

        $mode = array( 0 => 'unchanged',
                       1 => 'removed',
                       2 => 'added' );

        while ( $item = current( $diffArray ) )
        {
            if ( $item['status'] != $lastStatus )
            {
                $diff[] = array( $mode[$lastStatus] => $string,
                                 'status' => $lastStatus );
                $lastStatus = $item['status'];
                $string ="";
                continue;
            }

            $string .= $item[$mode[$lastStatus]] . " ";
            next( $diffArray );
        }
        if ( strlen( $string ) > 0 )
        {
            $diff[] = array( $mode[$lastStatus] => $string,
                             'status' => $lastStatus );
        }
        return $diff;
    }


    /*!
      \private
      This method will detect discontinuous substrings in the matrix.
      \return Array of substrings
    */
    function substrings( $sub, $old, $new )
    {
        $matrix = $sub['lengthMatrix'];
        $val = $sub['maxLength'];
        $row = $sub['maxRow'];
        $col = $sub['maxCol'];

        if ( $val == 0 )
        {
            //No common substrings were found
            return array();
        }

        $rows = count( $old );
        $cols = count( $new );

        $lcsOffsets = $this->findLongestSubstringOffsets( $sub );
        $lcsPlacement = $this->substringPlacement( $lcsOffsets['newStart'], $lcsOffsets['newEnd'], $cols );

        $substringSet = array();

        $substringArray = $this->traceSubstring( $row, $col, $matrix, $val, $new );
        $substring = $substringArray['substring'];
        unset( $substringArray );
        $substringSet[] = array_reverse( $substring, true );
        $substring = array();

        //Get more text
        if ( $lcsPlacement['hasTextLeft'] )
        {
            $row = $lcsOffsets['oldStart'];
            $col = $lcsOffsets['newStart'];

            if ( $row != 0 )
            {
                $row--;
            }

            if ( $col != 0 )
            {
                $col--;
            }

            $done = false;
            $info = true;
            $prevRowUsed = -1;

            while ( !$done && $info )
            {
                if ( $prevRowUsed == $row )
                {
                    $done = true;
                    continue;
                }

                $info = $this->localSubstring( 'left', $row, $col, $rows, $cols, $matrix );
                $prevRowUsed = $row;
                if ( $info )
                {
                    $substringArray = $this->traceSubstring( $info['remainRow'], $info['remainCol'], $matrix, $info['remainMax'], $new );
                    $substring = $substringArray['substring'];
                    array_unshift( $substringSet, array_reverse( $substring, true ) );
                    $substring = array();

                    if ( $info['remainCol'] == 0 || $substringArray['lastCol'] == 0 )
                    {
                        $done = true;
                    }
                    else
                    {
                        $row = $substringArray['lastRow'];
                        $col = $substringArray['lastCol'];

                        if ( $row != 0 )
                        {
                            $row--;
                        }
                        if ( $col != 0 )
                        {
                            $col--;
                        }
                    }
                    unset( $substringArray );
                }
            }
        }


        if ( $lcsPlacement['hasTextRight'] )
        {
            //reset search location in matrix
            $row = $sub['maxRow'];
            $col = $sub['maxCol'];

            if ( $row != $rows-1 )
            {
                $row++;
            }

            if ( $col != $cols-1 )
            {
                $col++;
            }

            $done = false;
            $info = true;
            $prevRowUsed = -1;

            while( !$done && $info )
            {
                if ( $prevRowUsed == $row )
                {
                    $done = true;
                    continue;
                }
                $info = $this->localSubstring( 'right', $row, $col, $rows, $cols, $matrix );
                $prevRowUsed = $row;
                if ( $info )
                {
                    $substringArray = $this->traceSubstring( $info['remainRow'], $info['remainCol'], $matrix, $info['remainMax'], $new );
                    $substring = $substringArray['substring'];
                    $substringSet[] = array_reverse( $substring, true );
                    $substring = array();

                    if ( $info['remainCol'] == $cols-1 || $substringArray['lastRow'] == $cols-1 )
                    {
                        $done = true;
                    }
                    else
                    {
                        $row = $info['remainRow'];
                        $col = $info['remainCol'];

                        if ( $row != $rows-1 )
                        {
                            $row++;
                        }

                        if ( $col != $cols-1 )
                        {
                            $col++;
                        }
                    }
                    unset( $substringArray );
                }
            }
        }
        return $substringSet;
    }

    /*!
      \private
      This method checks  a patch of the length matrix for the longest substring.
      Depending on the \a $direction a sub matrix is searched for valid substrings.
    */
    function localSubstring( $direction, $row, $col, $rows, $cols, $matrix )
    {
        $colMax = 0;
        $prevColMax = 0;
        $colMaxRow = 0;
        $colMaxCol = 0;

        $remainMax = 0;
        $remainRow = 0;
        $remainCol = 0;

        switch( $direction )
        {
            case 'right':
            {
                for ( $j = $col; $j < $cols; $j++ )
                {
                    $startRow = $row;
                    $colMax = 0;

                    while ( $startRow < $rows )
                    {
                        $matVal = $matrix->get( $startRow, $j );
                        if ( $matVal > $colMax )
                        {
                            $colMax = $matVal;
                            $colMaxRow = $startRow;
                            $colMaxCol = $j;
                        }
                        $startRow++;
                    }

                    if ( $colMax > $remainMax )
                    {
                        //Set best candidate thus far
                        $remainMax = $colMax;
                        $remainRow = $colMaxRow;
                        $remainCol = $colMaxCol;
                    }

                    if ( ( $prevColMax > 1 ) &&  ( $colMax < $prevColMax ) )
                    {
                        break 2;
                    }

                    $prevColMax = $colMax;
                }
            }break;

            case 'left':
            {
                for ( $j = $col; $j >= 0; $j-- )
                {
                    $startRow = $row;
                    $colMax = 0;

                    while ( $startRow >= 0 )
                    {
                        $matVal = $matrix->get( $startRow, $j );
                        if ( $matVal > $colMax )
                        {
                            $colMax = $matVal;
                            $colMaxRow = $startRow;
                            $colMaxCol = $j;
                        }
                        $startRow--;
                    }
                    if ( $colMax > $remainMax )
                    {
                        //Set best candidate thus far
                        $remainMax = $colMax;
                        $remainRow = $colMaxRow;
                        $remainCol = $colMaxCol;
                    }
                    if ( ( $prevColMax > 1 ) && ( $colMax < $prevColMax ) )
                    {
                        break 2;
                    }

                    $prevColMax = $colMax;
                }
            }break;
        }

        if ( $remainMax > 0 )
        {
            return array( 'remainMax' => $remainMax,
                          'remainRow' => $remainRow,
                          'remainCol' => $remainCol );
        }
        else
        {
            return false;
        }
    }

    /*!
      \private
      This method will backtrace found substrings, it will start from the endpoint of the
      string and work towards its start.
      \return Substring with endpoing at \a $row, \a $col
    */
    function traceSubstring( $row, $col, $matrix, $val, $new )
    {
        $substring = array();
        while( $row >= 0 && $col >= 0 )
        {
            if ( $matrix->get( $row, $col ) == $val )
            {
                $substring[$col] = array( 'word' => $new[$col],
                                          'oldOffset' => $row );

                if ( $val > 1 )
                {
                    $val--;
                }
                else if ( $val == 1 )
                {
                    break;
                }

                $row--;
                $col--;
                if ( $row < 0 || $col < 0 )
                    break;
            }
        }
        return array( 'substring' => $substring,
                      'lastRow' => $row,
                      'lastCol' => $col );
    }

    /*!
      \private
      This method will return an array with boolean values indicating whether
      the specified offsets \a $startOffset and \a $endOffset have additional
      text to either the left or right side.
    */
    function substringPlacement( $startOffset, $endOffset, $totalStringLength )
    {
        $leftText = false;
        $rightText = false;

        if ( $startOffset > 0 )
            $leftText = true;

        if ( $endOffset < $totalStringLength-1 )
            $rightText = true;

        return array( 'hasTextLeft' => $leftText,
                      'hasTextRight' => $rightText );
    }

    /*!
      \private
      Helper method to matrices.
    */
    function dumpMatrix( $arr, $rows, $cols )
    {
        for ( $i = 0; $i < $rows; $i++ )
        {
            for ( $j = 0; $j < $cols; $j++ )
            {
                print( $arr->get( $i, $j ) . " " );
                if ( $j == $cols-1 )
                    print( "\n" );
            }
        }
    }

    /*!
      \private
      This method calculates the offsets in the old and new text for the
      longest common substring.
    */
    function findLongestSubstringOffsets( $varArray )
    {
        //The columns yields the offsets in the new text.
        //The rows gives us the offsets in the old text.
        $lengthMatrix = $varArray['lengthMatrix'];
        $max = $varArray['maxLength'];
        $len = $max;
        $maxRow = $varArray['maxRow'];
        $maxCol = $varArray['maxCol'];
        $newStart = 0;
        $newEnd = 0;
        $oldStart = 0;
        $oldEnd = 0;

        while ( $len > 0 && $maxRow >= 0 && $maxCol >= 0)
        {
            $len = $lengthMatrix->get( $maxRow, $maxCol );

            if ( $lengthMatrix->get( $maxRow, $maxCol ) == $max )
            {
                $newEnd = $maxCol;
                $oldEnd = $maxRow;
            }

            if ( $lengthMatrix->get( $maxRow, $maxCol ) == 1 )
            {
                $newStart = $maxCol;
                $oldStart = $maxRow;
            }

            $maxRow--;
            $maxCol--;
        }
        return array( 'newStart' => $newStart,
                      'newEnd' => $newEnd,
                      'oldStart' => $oldStart,
                      'oldEnd' => $oldEnd );
    }

    /*!
      \private
      This function find the longest common substrings in \a $old and \a $new
      It will build a matrix consisting of the length of detected substrings.

      The function will build a structure like the following:
      array = ( 'maxLength' =>
                'maxRow' =>
                'maxCol' =>
                'lengthMatrix' => )

      \return Matrix containing the length of longest common substring string and where it is found in the substring length matrix.
    */
    function substringMatrix( $old, $new )
    {
        $maxLength = 0;
        $sizeOld = count( $old );
        $sizeNew =  count( $new );
        $matrix = new eZDiffMatrix( $sizeOld, $sizeNew );

        $maxC = 0;
        $maxR = 0;

        for ( $row = 0; $row < $sizeOld; $row++ )
        {
            for ( $col = 0; $col < $sizeNew; $col++ )
            {
                if ( $old[$row] === $new[$col] )
                {
                    if ( $row > 0 && $col > 0 )
                    {
                        $val = 1 + $matrix->get( $row-1, $col-1 );
                        $matrix->set( $row, $col, $val );
                    }
                    else if ( $row > 0 && $col == 0 )
                    {
                        $val = 1 + $matrix->get( $row-1, $col );
                        $matrix->set( $row, $col, $val );
                    }
                    else if ( $row == 0 && $col > 0 )
                    {
                        $val = 1 + $matrix->get( $row, $col-1 );
                        $matrix->set( $row, $col, $val );
                    }
                    else if ( $row == 0 && $col == 0 )
                    {
                        $matrix->set( $row, $col, 1 );
                    }

                    if ( $matrix->get( $row, $col ) > $maxLength )
                    {
                        $maxLength = $matrix->get( $row, $col );
                        $maxR = $row;
                        $maxC = $col;
                    }
                }
                else
                {
                    $matrix->set( $row, $col, 0 );
                }
            }
        }
        return array( 'maxLength' => $maxLength,
                      'maxRow' => $maxR,
                      'maxCol' => $maxC,
                      'lengthMatrix' => $matrix );
    }

    /*!
      \private
      Removes empty elements from array
      \return array without empty elements
    */
    function trimEmptyArrayElements( $array )
    {
        foreach( $array as $key => $value )
        {
            if ( empty( $value ) )
            {
                unset( $array[$key] );
            }
        }
        //Calls array_merge to reset the array keys.
        return array_merge( $array );
    }
}

?>
