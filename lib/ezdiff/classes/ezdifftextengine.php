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

include_once( 'lib/ezutils/classes/ezdebug.php' );
include_once( 'lib/ezdiff/classes/ezdiffengine.php' );
include_once( 'lib/ezdiff/classes/ezdiffmatrix.php' );

class eZDiffTextEngine extends eZDiffEngine
{
    function eZDiffTextEngine()
    {
        eZDebug::writeNotice( "Initializing text diff engine", "eZDiffTextEngine" );
    }

    /*!
      This function calculates changes in plain text and creates an object to hold
      overview of changes.
    */
    function createDifferenceObject( $fromData, $toData )
    {
        include_once( 'lib/ezdiff/classes/eztextdiff.php' );
        eZDebug::writeNotice( "Creating difference object", 'eZDiffTextEngine' );

        //A side effect of the following two calls are possible to multiple spaces being
        //inserted. Thus the splitting of the string can cause empty array elements.
        $storedNewLines = $this->storeNewLines( $fromData );
        $storedNewLinesTo = $this->storeNewLines( $toData );

        $fromData = strtr( $fromData, "\r\n", "  " );
        $toData = strtr( $toData, "\r\n", "  " );

        $oldArray = explode( " ", $fromData );
        $newArray = explode( " ", $toData );

        $oldArray = $this->trimEmptyArrayElements( $oldArray );
        $newArray = $this->trimEmptyArrayElements( $newArray );

        $statistics = $this->createStatisticsArray( $fromData, $oldArray, $toData, $newArray );

        $changes = new eZTextDiff();
        $changes->setMetaData( $statistics, $oldArray, $newArray );

        $changeStatus = $this->simpleDiff( $statistics, $oldArray, $newArray );
        $changes->setDiff( $changeStatus );

        //$changeSet = $this->simpleChanges( $statistics, $oldArray, $newArray );
        //$changes->setChanges( $changeSet );

        $output = $this->buildDiff( $statistics, $oldArray, $newArray );
        $changes->setChanges( $output );

      return $changes;
    }

    /*!
      \private
      A simple diff method which will diff per word.
      Not used. Will be removed.
    */
    function simpleDiff( $stat, $oldWords, $newWords )
    {
        $newTextLonger = $stat['newTextLonger'];
        $changeStatus = array();
        $oldIndex = $stat['from']['wordCount'];
        for ( $newIndex = 0; $newIndex < $stat['to']['wordCount']; $newIndex++ )
        {
            if ( $newIndex < $oldIndex )
            {
                if ( $oldWords[$newIndex] == $newWords[$newIndex] )
                {
                    $changeStatus[] = array( 'status' => 0,
                                             'newWord' => $newWords[$newIndex] );
                }
                else
                {
                    $changeStatus[] = array( 'newWord' => $newWords[$newIndex],
                                             'oldWord' => $oldWords[$newIndex],
                                             'status' => 1 );
                }
            }
            else if ( $newIndex >= $oldIndex )
            {
                $changeStatus[] = array( 'newWord' => $newWords[$newIndex],
                                         'status' => 2 );
            }
        }
        return $changeStatus;
    }

    /*!
      \private
      This method will calculate changes. Not used, will be removed.
    */
    function simpleChanges( $stat, $oldWords, $newWords )
    {
        $c2 = array();
        for ( $offset = 0; $offset < $stat['to']['wordCount']; $offset++ )
        {
            if ( $offset < $stat['from']['wordCount'] )
            {
                $val = $newWords[$offset] === $oldWords[$offset];
                $c2[$offset] = $val ? 0 : 1;
            }
            else if ( $offset >= $stat['from']['wordCount'] )
            {
                //Appended chars.
                $c2[$offset] = 2;
            }
        }

        //Create changeset
        $changeSet = array();
        $offset = 0;
        while ( $offset < $stat['to']['wordCount'] )
        {
            switch( $c2[$offset] )
            {
                case 0:
                {
                    $start = $offset;
                    $words = $this->getConnectedChange( $start, 0, $c2, $newWords );
                    $diff = array( 'unchanged' => $words,
                                   'status' => 0 );
                    $changeSet[] = $diff;
                    $offset += ( $start - $offset );
                }break;

                case 1:
                {
                    //append as long as c2 is 1
                    //$offset will here also be within bounds of oldarray, which may be shorter
                    $start = $offset;
                    $added = $this->getConnectedChange( $start, 1, $c2, $newWords );
                    $start = $offset;
                    $removed = $this->getConnectedChange( $start, 1,  $c2, $oldWords );
                    $diff = array( 'added' => $added,
                                   'removed' => $removed,
                                   'status' => 1 );
                    $changeSet[] = $diff;
                    $offset += ( $start - $offset );
                }break;

                case 2:
                {
                    $start = $offset;
                    $appended = $this->getConnectedChange( $start, 2,  $c2, $newWords );
                    $diff = array( 'appended' => $appended,
                                   'status' => 2 );
                    $changeSet[] = $diff;
                    $offset += ( $start - $offset );
                }break;
            }
        }
        return $changeSet;
    }

    /*!
      \private
      This method will contruct a diff output for plain text.
    */
    function buildDiff( $statistics, $oldArray, $newArray )
    {
        $substr = $this->substringMatrix( $oldArray, $newArray );
        /*
        $tmp = $substr['lengthMatrix'];
        print( "<pre>" );
        //$this->dumpMatrix( $substr['lengthMatrix'] );
        $this->dumpMatrix( $tmp, $statistics['from']['wordCount'], $statistics['to']['wordCount'] );
        print( "</pre>" );
        */

        $strings = $this->substrings( $substr, $oldArray, $newArray );

        //Merge detected substrings
        /*
        $mergedStrings = array();
        foreach ( $strings as $sstring )
        {
            $mergedStrings = $mergedStrings + $sstring;
        }
        */

        $differences = array();
        $prevKey = 0;
        $distanceAdjust = 0;

        //Check for prepended text first, before any substring begins
        current( $strings[0] );
        if ( $first = key( $strings[0] ) > 0 )
        {
            $k = 0;
            while ( $k < $first )
            {
                $differences[] = array( 'added' => $newArray[$k],
                                        'status' => 2 );
                $k++;
            }
        }

        foreach ( $strings as $substringKey => $string )
        {
            foreach ( $string as $key => $wordArray )
            {
                $distance = $key - $prevKey;
                if ( $distance > 1 )
                {
                    $newKey = $prevKey;
                    while ( $distance > 1 )
                    {
                        $newKey++;
                        $differences[] = array( 'added' => $newArray[$newKey],
                                                'status' => 2 );
                        $distance--;
                    }
                }

                if ( $key != $wordArray['oldOffset'] )
                {
                    if ( $wordArray['oldOffset'] - $distanceAdjust > $key )
                    {
                        $delDistance = $wordArray['oldOffset'] - $key;
                        $distanceAdjust = $delDistance;
                        $nk = $key;
                        while ( $delDistance > 0 )
                        {
                            $differences[] = array( 'removed' => $oldArray[$nk],
                                                    'status' => 1 );
                            $delDistance--;
                            $nk++;
                        }
                    }
                    //Detect deleted words where the surrounding text has been shifted.
                    else
                    {

                    }
                }

                $differences[] = array( 'unchanged' => $wordArray['word'],
                                        'status' => 0 );
                $prevKey = $key;
            }
        }

        //Check for added text after last substring
        if ( $prevKey < count( $newArray ) )
        {
            //Add new appended words
            $k = $prevKey +1;
            while ( $k < count( $newArray ) )
            {
                $differences[] = array( 'added' => $newArray[$k],
                                        'status' => 2 );
                $k++;
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
      This method will find discontinuous substrings in a length matrix.
      \return Array of discontiuous substrings.
    */
    function getDiscontinuousSubstrings( $sub, $old, $new)
    {
        $lengthMatrix = $sub['lengthMatrix'];
        $rows = count( $old );
        $cols = count( $new );

        $foundLength = 0;
        $foundRow = 0;
        $foundCol = 0;
        $substringSet = array();
        $detectedStrings = 0;
        $substring = array();

        $startRow = $sub['maxRow'];
        $startCol = $sub['maxCol'];

        $val = $sub['maxLength'];

        /* This loop goes through the length matrix upwards to left.
           That is, it starts from the end of the longest substring and
           goes forward in the text. This might leave substrings at the end
           of the text
        */

        while ( $startRow >= 0 && $startCol >= 0 )
        {
            if ( $lengthMatrix->get( $startRow, $startCol ) == $val )
            {
                $substring[$startCol] = array( 'word' => $new[$startCol],
                                               'oldOffset' => $startRow );
                if ( $val != 1 )
                    $val--;
                $startCol--;
                $startRow--;
            }
            else if ( $lengthMatrix->get( $startRow, $startCol ) != $val )
            {
                if ( count( $substring ) > 0 )
                {
                    array_unshift( $substringSet, array_reverse( $substring, true ) );
                    $detectedStrings++;
                    $substring = array();
                }
                $startRow--;
                $spotlight = $lengthMatrix->get( $startRow, $startCol );
                if ( $spotlight > 0 )
                {
                    $val = $spotlight-1;
                    $substring[$startCol] = array( 'word' => $new[$startCol],
                                                   'oldOffset' => $startRow );
                    $startRow--;
                    $startCol--;
                }
            }
        }
        if ( count( $substring ) > 0 )
        {
            array_unshift( $substringSet, array_reverse( $substring, true ) );
            $detectedStrings++;
        }

        /* Check if there is any text after the longest substring in the text */
        $offsets = $this->findLongestSubstringOffsets( $sub );
        $textInfo = $this->substringPlacement( $offsets['newStart'], $offsets['newEnd'], count( $new ) );
        if ( $textInfo['hasTextRight'] )
        {
            //Search towards end of text for more substrings
            $substring = array();
            $startRow = $sub['maxRow']+1;
            $startCol = $sub['maxCol']+1;
            $val = 1;
            while ( $startRow < $rows && $startCol < $cols )
            {
                if ( $lengthMatrix->get( $startRow, $startCol ) == $val )
                {
                    $substring[$startCol] = array( 'word' => $new[$startCol],
                                                   'oldOffset' => $startRow );
                    $val++;
                    $startRow++;
                    $startCol++;
                }
                else
                {
                    if ( count( $substring ) > 0 )
                    {
                        $substringSet[] = $substring;
                        $detectedStrings++;
                        $substring = array();
                    }

                    $startCol++;

                    if ( ( $spotlight = $lengthMatrix->get( $startRow, $startCol ) ) > 0 )
                    {
                        $substring[$startCol] = array( 'word' => $new[$startCol],
                                                       'oldOffset' => $startRow );
                        $val = $spotlight + 1;
                        $startRow++;
                        $startCol++;
                    }
                }
            }
            if ( count( $substring ) > 0 )
            {
                $substringSet[] = $substring;
                $detectedStrings++;
            }

        }
        return $substringSet;
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

        $rows = count( $old );
        $cols = count( $new );

        $lcsOffsets = $this->findLongestSubstringOffsets( $sub );
        $lcsPlacement = $this->substringPlacement( $lcsOffsets['newStart'], $lcsOffsets['newEnd'], $rows );

        $substringSet = array();

        $substring = $this->traceSubstring( $row, $col, $matrix, $val, $new );
        $substringSet[] = array_reverse( $substring, true );
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

            while ( !$done && $info )
            {
                $info = $this->localSubstring( 'left', $row, $col, $rows, $cols, $matrix );
                if ( $info )
                {
                    $sub = $this->traceSubstring( $info['remainRow'], $info['remainCol'], $matrix, $info['remainMax'], $new );
                    array_unshift( $substringSet, array_reverse( $sub, true ) );

                    if ( $info['remainCol'] == 0 )
                    {
                        $done = true;
                    }
                    else
                    {
                        $row = $info['remainRow'];
                        $col = $info['remainCol'];
                    }
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

            while( !$done && $info )
            {
                $info = $this->localSubstring( 'right', $row, $col, $rows, $cols, $matrix );
                if ( $info )
                {
                    $sub = $this->traceSubstring( $info['remainRow'], $info['remainCol'], $matrix, $info['remainMax'], $new );
                    $substringSet[] = array_reverse( $sub, true );

                    if ( $info['remainCol'] == $cols-1 )
                    {
                        $done = true;
                    }
                    else
                    {
                        $row = $info['remainRow'];
                        $col = $info['remainCol'];
                    }
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
                    while ( $row < $rows )
                    {
                        $matVal = $matrix->get( $row, $j );
                        if ( $matVal > $colMax )
                        {
                            $prevColMax = $colMax;
                            $colMax = $matVal;
                            $colMaxRow = $row;
                            $colMaxCol = $j;
                        }
                        $row++;
                    }
                    if ( $colMax > $remainMax )
                    {
                        //Set best candidate thus far
                        $remainMax = $colMax;
                        $remainRow = $colMaxRow;
                        $remainCol = $colMaxCol;
                    }
                    if ( $colMax < $prevColMax )
                    {
                        break;
                    }
                }
            }break;

            case 'left':
            {
                for ( $j = $col; $j >= 0; $j-- )
                {
                    while ( $row >= 0 )
                    {
                        $matVal = $matrix->get( $row, $j );
                        if ( $matVal > $colMax )
                        {
                            $prevColMax = $colMax;
                            $colMax = $matVal;
                            $colMaxRow = $row;
                            $colMaxCol = $j;
                        }
                        $row--;
                    }
                    if ( $colMax > $remainMax )
                    {
                        //Set best candidate thus far
                        $remainMax = $colMax;
                        $remainRow = $colMaxRow;
                        $remainCol = $colMaxCol;
                    }
                    if ( $colMax < $prevColMax )
                    {
                        break;
                    }
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
        return $substring;
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
      This method will add newlines to \a $input at positions specified
      by keys in \a $newLines.
    */
    function addNewLines( $input, $newLines )
    {
        foreach ( $newLines  as $offset => $value )
        {
            $input[$offset] = $value;
        }
        return $input;
    }

    /*!
      This method stores newlines present in \a $input.
      \return An array consisting of detechted newlines.
    */
    function storeNewLines( $input )
    {
        $newLines = array();
        for ( $i = 0; $i < strlen( $input ); $i++ )
        {
            $char = $input[$i];
            if ( $char == "\n" || $char == "\r" )
            {
                $newLines[$i] = $char;
            }
        }
        return $newLines;
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
      Constructs a consequtive string of added and removed strings
    */
    function getConnectedChange( &$offset, $type, $changeTypeArray, $dataArray )
    {
        $change = "";
        while ( isset( $changeTypeArray[$offset] ) && $changeTypeArray[$offset] == $type )
        {
            $change .= $dataArray[$offset] . " ";
            $offset++;
        }
        return $change;
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

    /*!
      \private
      \return Statistics verry about the two text versions.
      Builds a statistics array containing metadata about the input texts.
    */
    function createStatisticsArray( $fromText, $fromArray,  $toText, $toArray )
    {
        $stats = array();
        $stats['from'] = array( 'charCount' => strlen( $fromText ),
                                'wordCount' => count( $fromArray ) );
        $stats['to'] = array( 'charCount' => strlen( $toText ),
                              'wordCount' => count( $toArray ) );
        $stats['newTextLonger'] = strlen( $fromText ) < strlen( $toText );
        return $stats;
    }
}

?>
