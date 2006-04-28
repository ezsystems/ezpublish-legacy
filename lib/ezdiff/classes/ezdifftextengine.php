<?php
//
// Definition of eZDiffTextEngine class
//
// <creation-tag>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

/*! \file ezdifftextengine.php
  eZDiffTextEngine class
*/

/*!
  \class eZDiffTextEngine ezdifftextengine.php
  \ingroup eZDiff
  \brief eZDiff provides an access point the diff system

  The eZDiffEngine class is an abstract class, providing interface and shared code
  for the different available DiffEngine.
*/

include_once( 'lib/ezdiff/classes/ezdiffengine.php' );
include_once( 'lib/ezdiff/classes/ezdiffmatrix.php' );

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
        include_once( 'lib/ezdiff/classes/eztextdiff.php' );

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
            $oldSums[] = md5( $paragraph );
        }

        $newSums = array();
        foreach( $newArray as $paragraph )
        {
            $newSums[] = md5( $paragraph );
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
            $state = current( $item );
            $par = key( $item );
            switch ( $state )
            {
                case 'unchanged':
                {
                    $text = $newArray[$par];
                    $this->addNewLine( $text );
                    $diff[] = array( 'unchanged' => $text,
                                     'status' => 0 );
                }break;

                case 'changed':
                {
                    $old = explode( " ", $oldArray[$par] );
                    $new = explode( " ", $newArray[$par] );

                    $diffText = $this->buildDiff( $old, $new );
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
                }break;

                case 'added':
                {
                    $text = $newArray[$par];
                    $this->addNewLine( $text );
                    $diff[] = array( 'added' => $text,
                                     'status' => 2 );
                }break;

                case 'removed':
                {
                    $text = $oldArray[$par];
                    $this->addNewLine( $text );
                    $diff[] = array( 'removed' => $text,
                                     'status' => 1 );
                }break;
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
        $len = count( $strings );

        $differences = array();
        $offset = 0;
        $delOffset = 0;
        $internalOffset = 0;

        $unchanged = array();
        $added = array();
        $removed = array();
        $changed = array();

        if ( $len > 0 )
        {
            //Merge detected paragraphs
            $mergedStrings = array();
            foreach ( $strings as $sstring )
            {
                $mergedStrings = $mergedStrings + $sstring;
            }
            unset( $strings );

            //Check for new prepended paragraphs
            $first = key( $mergedStrings );
            if ( $first > 0 )
            {
                $k = 0;
                while ( $k < $first )
                {
                    $added[$k] = 'added';
                    $k++;
                    $offset++;
                }
            }

            //Check for removed paragraph
            $firstOffset = current( $mergedStrings );
            $firstOffset = $firstOffset['oldOffset'];

            if ( $firstOffset > 0 )
            {
                $k = 0;
                while( $firstOffset > 0 )
                {
                    $removed[$k] = 'removed';
                    $k++;
                    $firstOffset--;
                    $delOffset++;
                }
            }

            //Check for changes in between paragraphs
            $prevKey = 0;
            $prevOffset = 0;

            foreach ( $mergedStrings as $key => $wordArray )
            {
                $distance = $key - $prevKey - $offset;

                if ( $distance > 1 )
                {
                    $nk = $prevKey;
                    while ( $distance > 1 )
                    {
                        $nk++;
                        $added[$nk] = 'added';
                        $distance--;
                        $internalOffset++;
                    }
                }

                $offsetDistance = $wordArray['oldOffset'] + $internalOffset - $key - $delOffset + $offset;

                if ( $offsetDistance > 0 )
                {
                    $k = $prevOffset + 1;
                    while( $k < $wordArray['oldOffset'] )
                    {
                        $removed[$k] = 'removed';
                        $k++;
                        $delOffset++;
                    }
                }

                //The default state - unchanged paragraph
                $unchanged[$key] = 'unchanged';

                $prevKey = $key;
                $prevOffset = $wordArray['oldOffset'];
            }


            //Check for appended paragraphs
            end( $mergedStrings );
            $end = key( $mergedStrings );
            if ( $end < $nNewWords )
            {
                $k = $end + 1;
                while ( $k < $nNewWords )
                {
                    $added[$k] = 'added';
                    $k++;
                }
            }

            //Check for deleted paragraphs
            end( $oldArray );
            $end = key( $oldArray );

            if ( $prevOffset < $end )
            {
                $k = $prevOffset + 1;
                while ( $k < $nOldWords )
                {
                    $removed[$k] = 'removed';
                    $k++;
                }
            }
        }
        else
        {
            //No common paragraphs
            foreach ( $oldArray as $key => $dummy )
            {
                $removed[$key] = 'removed';
            }

            foreach( $newArray as $key => $dummy )
            {
                $added[$key] = 'added';
            }
        }

        foreach( $added as $addedKey => $addedPar )
        {
            $remKey = array_key_exists( $addedKey, $removed );
            if ( $remKey === true )
            {
                $changed[$addedKey] = 'changed';
                unset( $added[$addedKey] );
                unset( $removed[$addedKey] );
            }
        }
        $mergedChanges = $added + $removed + $changed;
        ksort( $mergedChanges );
        $merged = $this->mergeOverviewMatrix( $unchanged, $mergedChanges );
        return $merged;
    }

    /*!
      \private
      This method creates a combinen array of unchanged and changed paragraphs
    */
    function mergeOverviewMatrix( $unchanged, $changes )
    {
        $overview = array();
        $totalSize = count( $unchanged ) + count( $changes );

        $i = 0;
        while ( $i < $totalSize )
        {
            $unchangedIndex = array_key_exists( $i, $unchanged );
            $changedIndex = array_key_exists( $i, $changes );

            if ( $unchangedIndex !== false )
            {
                $overview[] = array( $i => 'unchanged' );
            }

            if ( $changedIndex !== false )
            {
                $overview[] = array( $i => $changes[$i] );
            }

            $i++;
        }
        return $overview;
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

        $differences = array();
        $offset = 0;
        $delOffset = 0;
        $internalOffset = 0;

        if ( $len > 0 )
        {
            //Merge detected substrings
            $mergedStrings = array();
            foreach ( $strings as $sstring )
            {
                $mergedStrings = $mergedStrings + $sstring;
            }
            unset( $strings );

            //Check for new prepended text before substring
            $first = key( $mergedStrings );
            if ( $first > 0 )
            {
                $k = 0;
                while ( $k < $first )
                {
                    $differences[] = array( 'added' => $newArray[$k],
                                            'status' => 2 );
                    $k++;
                    $offset++;
                }
            }

            //Check for removed words before first substring
            $firstOffset = current( $mergedStrings );
            $firstOffset = $firstOffset['oldOffset'];

            if ( $firstOffset > 0 )
            {
                $k = 0;
                while( $firstOffset > 0 )
                {
                    $differences[] = array( 'removed' => $oldArray[$k],
                                            'status' => 1 );
                    $k++;
                    $firstOffset--;
                    $delOffset++;
                }
            }

            //Check for changes within substring
            $prevKey = 0;
            $prevOffset = 0;
            foreach ( $mergedStrings as $key => $wordArray )
            {
                $distance = $key - $prevKey - $offset;

                //If the distance between current word and previous one is greater than one, words have been inserted
                if ( $distance > 1 )
                {
                    $nk = $prevKey;
                    while ( $distance > 1 )
                    {
                        $nk++;
                        $differences[] = array( 'added' => $newArray[$nk],
                                                'status' => 2 );
                        $distance--;
                        $internalOffset++;
                    }
                }

                //Check for deleted words in between
                $offsetDistance = $wordArray['oldOffset'] + $internalOffset - $key - $delOffset + $offset;

                if ( $offsetDistance > 0 )
                {
                    $k = $prevOffset + 1;
                    while( $k < $wordArray['oldOffset'] )
                    {
                        $differences[] = array( 'removed' => $oldArray[$k],
                                                'status' => 1 );
                        $k++;
                        $delOffset++;
                    }
                }

                //The default state - unchanged words
                $differences[] = array( 'unchanged' => $newArray[$key],
                                        'status' => 0 );

                $prevKey = $key;
                $prevOffset = $wordArray['oldOffset'];
            }


            //Check for appended text after substring
            end( $mergedStrings );
            $end = key( $mergedStrings );
            if ( $end < $nNewWords )
            {
                $k = $end + 1;
                while ( $k < $nNewWords )
                {
                    $differences[] = array( 'added' => $newArray[$k],
                                            'status' => 2 );
                    $k++;
                }
            }

            //Check for deleted words at end of substring
            end( $oldArray );
            $end = key( $oldArray );

            if ( $prevOffset < $end )
            {
                $k = $prevOffset + 1;
                while ( $k < $nOldWords )
                {
                    $differences[] = array( 'removed' => $oldArray[$k],
                                            'status' => 1 );
                    $k++;
                }
            }
        }
        else
        {
            //No common substring meaning all old words were deleted,
            //and new words added
            foreach ( $oldArray as $removed )
            {
                $differences[] = array( 'removed' => $removed,
                                        'status' => 1 );
            }

            foreach( $newArray as $added )
            {
                $differences[] = array( 'added' => $added,
                                        'status' => 2 );
            }
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
        $matrix = new eZDiffMatrix();
        $maxLength = 0;
        $sizeOld = count( $old );
        $sizeNew =  count( $new );

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
