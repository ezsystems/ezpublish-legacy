<?php

//
// Created on: <26-Aug-2003 15:15:32 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file class.ezpdf.php
*/

//include_once( 'lib/ezpdf/classes/class.pdf.php' );

/*!
  \class Cezpdf class.ezpdf.php
  \ingroup eZPDF
  \brief Cezpdf provides
*/

class Cezpdf extends Cpdf
{

    public $ez=array('fontSize'=>10); // used for storing most of the page configuration parameters
    public $y; // this is the current vertical positon on the page of the writing point, very important
    public $ezPages=array(); // keep an array of the ids of the pages, making it easy to go back and add page numbers etc.
    public $ezPageCount=0;

// ------------------------------------------------------------------------------

    function Cezpdf( $paper = 'a4', $orientation = 'portrait' )
    {
        // Assuming that people don't want to specify the paper size using the absolute coordinates
        // allow a couple of options:
        // orientation can be 'portrait' or 'landscape'
        // or, to actually set the coordinates, then pass an array in as the first parameter.
        // the defaults are as shown.
        //
        // -------------------------
        // 2002-07-24 - Nicola Asuni (info@tecnick.com):
        // Added new page formats (45 standard ISO paper formats and 4 american common formats)
        // paper cordinates are calculated in this way: (inches * 72) where 1 inch = 2.54 cm
        //
        // Now you may also pass a 2 values array containing the page width and height in centimeters
        // -------------------------

        if ( !is_array( $paper ) )
        {
            switch ( strtoupper( $paper ) )
            {
                case '4A0': {$size = array(0,0,4767.87,6740.79); break;}
                case '2A0': {$size = array(0,0,3370.39,4767.87); break;}
                case 'A0': {$size = array(0,0,2383.94,3370.39); break;}
                case 'A1': {$size = array(0,0,1683.78,2383.94); break;}
                case 'A2': {$size = array(0,0,1190.55,1683.78); break;}
                case 'A3': {$size = array(0,0,841.89,1190.55); break;}
                case 'A4': default: {$size = array(0,0,595.28,841.89); break;}
                case 'A5': {$size = array(0,0,419.53,595.28); break;}
                case 'A6': {$size = array(0,0,297.64,419.53); break;}
                case 'A7': {$size = array(0,0,209.76,297.64); break;}
                case 'A8': {$size = array(0,0,147.40,209.76); break;}
                case 'A9': {$size = array(0,0,104.88,147.40); break;}
                case 'A10': {$size = array(0,0,73.70,104.88); break;}
                case 'B0': {$size = array(0,0,2834.65,4008.19); break;}
                case 'B1': {$size = array(0,0,2004.09,2834.65); break;}
                case 'B2': {$size = array(0,0,1417.32,2004.09); break;}
                case 'B3': {$size = array(0,0,1000.63,1417.32); break;}
                case 'B4': {$size = array(0,0,708.66,1000.63); break;}
                case 'B5': {$size = array(0,0,498.90,708.66); break;}
                case 'B6': {$size = array(0,0,354.33,498.90); break;}
                case 'B7': {$size = array(0,0,249.45,354.33); break;}
                case 'B8': {$size = array(0,0,175.75,249.45); break;}
                case 'B9': {$size = array(0,0,124.72,175.75); break;}
                case 'B10': {$size = array(0,0,87.87,124.72); break;}
                case 'C0': {$size = array(0,0,2599.37,3676.54); break;}
                case 'C1': {$size = array(0,0,1836.85,2599.37); break;}
                case 'C2': {$size = array(0,0,1298.27,1836.85); break;}
                case 'C3': {$size = array(0,0,918.43,1298.27); break;}
                case 'C4': {$size = array(0,0,649.13,918.43); break;}
                case 'C5': {$size = array(0,0,459.21,649.13); break;}
                case 'C6': {$size = array(0,0,323.15,459.21); break;}
                case 'C7': {$size = array(0,0,229.61,323.15); break;}
                case 'C8': {$size = array(0,0,161.57,229.61); break;}
                case 'C9': {$size = array(0,0,113.39,161.57); break;}
                case 'C10': {$size = array(0,0,79.37,113.39); break;}
                case 'RA0': {$size = array(0,0,2437.80,3458.27); break;}
                case 'RA1': {$size = array(0,0,1729.13,2437.80); break;}
                case 'RA2': {$size = array(0,0,1218.90,1729.13); break;}
                case 'RA3': {$size = array(0,0,864.57,1218.90); break;}
                case 'RA4': {$size = array(0,0,609.45,864.57); break;}
                case 'SRA0': {$size = array(0,0,2551.18,3628.35); break;}
                case 'SRA1': {$size = array(0,0,1814.17,2551.18); break;}
                case 'SRA2': {$size = array(0,0,1275.59,1814.17); break;}
                case 'SRA3': {$size = array(0,0,907.09,1275.59); break;}
                case 'SRA4': {$size = array(0,0,637.80,907.09); break;}
                case 'LETTER': {$size = array(0,0,612.00,792.00); break;}
                case 'LEGAL': {$size = array(0,0,612.00,1008.00); break;}
                case 'EXECUTIVE': {$size = array(0,0,521.86,756.00); break;}
                case 'FOLIO': {$size = array(0,0,612.00,936.00); break;}
            }
            switch ( strtolower( $orientation ) )
            {
                case 'landscape':
                    $a=$size[3];
                    $size[3]=$size[2];
                    $size[2]=$a;
                break;
            }
        }
        else
        {
            if ( count( $paper ) > 2 )
            {   // then an array was sent it to set the size
                $size = $paper;
            }
            else
            {   //size in centimeters has been passed
                $size[0] = 0;
                $size[1] = 0;
                $size[2] = ( $paper[0] / 2.54 ) * 72;
                $size[3] = ( $paper[1] / 2.54 ) * 72;
            }
        }
        $this->Cpdf( $size );
        $this->ez['pageWidth']=$size[2];
        $this->ez['pageHeight']=$size[3];

        // also set the margins to some reasonable defaults
        $config = eZINI::instance( 'pdf.ini' );

        $this->ez['topMargin']=$config->variable( 'PDFGeneral', 'TopMargin' );
        $this->ez['bottomMargin']=$config->variable( 'PDFGeneral', 'BottomMargin' );
        $this->ez['leftMargin']=$config->variable( 'PDFGeneral', 'LeftMargin' );
        $this->ez['rightMargin']=$config->variable( 'PDFGeneral', 'RightMargin' );

        // set the current writing position to the top of the first page
        $this->y = $this->ez['pageHeight']-$this->ez['topMargin'];
        $this->ez['xOffset'] = 0;

        // set current text justification
        $this->ez['justification'] = 'left';
        $this->ez['lineSpace'] = 1;
        // and get the ID of the page that was created during the instancing process.
        $this->ezPages[1]=$this->getFirstPageId();
        $this->ezPageCount=1;
    }

// ------------------------------------------------------------------------------
// 2002-07-24: Nicola Asuni (info@tecnick.com)
// Set Margins in centimeters
    function ezSetCmMargins( $top, $bottom, $left, $right )
    {
        $top = ( $top / 2.54 ) * 72;
        $bottom = ( $bottom / 2.54 ) * 72;
        $left = ( $left / 2.54 ) * 72;
        $right = ( $right / 2.54 ) * 72;
        $this->ezSetMargins( $top, $bottom, $left, $right );
    }

// ------------------------------------------------------------------------------
// 2003-11-04 Kåre Køhler Høvik ( eZ Systems, http://ez.no )
// Set fontsize

    function setFontSize( $size )
    {
        $this->ez['fontSize'] = $size;
    }

// ------------------------------------------------------------------------------
// 2003-11-06 Kåre Køhler Høvik ( eZ Systems, http://ez.no )
// Set justification

    function setJustification( $align )
    {
        $this->ez['justification'] = $align;
    }

// ------------------------------------------------------------------------------
// 2003-11-06 Kåre Køhler Høvik ( eZ Systems, http://ez.no )
// Get justification

    function justification()
    {
        return $this->ez['justification'];
    }

// ------------------------------------------------------------------------------
// 2003-11-04 Kåre Køhler Høvik ( eZ Systems, http://ez.no )
// Get fontsize

    function fontSize()
    {
        return $this->ez['fontSize'];
    }

// ------------------------------------------------------------------------------

    function ezColumnsStart( $options = array() )
    {
        // start from the current y-position, make the set number of columne
        if ( isset( $this->ez['columns'] ) && $this->ez['columns'] == 1 )
        {   // if we are already in a column mode then just return.
            return;
        }
        $def = array( 'gap'=>10, 'num'=>2 );
        foreach( $def as $k => $v )
        {
            if ( !isset( $options[$k] ) )
            {
                $options[$k] = $v;
            }
        }
        // setup the columns
        $this->ez['columns'] = array( 'on' => 1, 'colNum' => 1 );

        // store the current margins
        $this->ez['columns']['margins'] = array( $this->ez['leftMargin'],
                                                 $this->ez['rightMargin'],
                                                 $this->ez['topMargin'],
                                                 $this->ez['bottomMargin'] );
        // and store the settings for the columns
        $this->ez['columns']['options'] = $options;
        // then reset the margins to suit the new columns
        // safe enough to assume the first column here, but start from the current y-position
        $this->ez['topMargin'] = $this->ez['pageHeight']-$this->y;
        $width = ( $this->ez['pageWidth'] - $this->ez['leftMargin'] - $this->ez['rightMargin'] - ( $options['num'] - 1 ) * $options['gap'] ) / $options['num'];
        $this->ez['columns']['width'] = $width;
        $this->ez['rightMargin'] = $this->ez['pageWidth'] - $this->ez['leftMargin'] - $width;

    }
// ------------------------------------------------------------------------------
    function ezColumnsStop()
    {
        if ( isset( $this->ez['columns'] ) && $this->ez['columns']['on']==1 )
        {
            $this->ez['columns']['on'] = 0;
            $this->ez['leftMargin'] = $this->ez['columns']['margins'][0];
            $this->ez['rightMargin'] = $this->ez['columns']['margins'][1];
            $this->ez['topMargin'] = $this->ez['columns']['margins'][2];
            $this->ez['bottomMargin'] = $this->ez['columns']['margins'][3];
        }
    }
// ------------------------------------------------------------------------------
    function ezInsertMode( $status = 1, $pageNum = 1, $pos = 'before' )
    {
        // puts the document into insert mode. new pages are inserted until this is re-called with status=0
        // by default pages wil be inserted at the start of the document
        switch ( $status )
        {
            case '1':
                if ( isset( $this->ezPages[$pageNum] ) )
                {
                    $this->ez['insertMode'] = 1;
                    $this->ez['insertOptions'] = array( 'id' => $this->ezPages[$pageNum],
                                                        'pos' => $pos );
                } break;
            case '0':
                $this->ez['insertMode'] = 0;
                break;
        }
    }
// ------------------------------------------------------------------------------

    function ezNewPage()
    {
        $pageRequired = 1;
        if ( isset( $this->ez['columns'] ) && $this->ez['columns']['on'] == 1 )
        {
            // check if this is just going to a new column
            // increment the column number

            $this->ez['columns']['colNum']++;
            if ( $this->ez['columns']['colNum'] <= $this->ez['columns']['options']['num'] )
            {
                // then just reset to the top of the next column
                $pageRequired=0;
            }
            else
            {
                $this->ez['columns']['colNum'] = 1;
                $this->ez['topMargin'] = $this->ez['columns']['margins'][2];
            }

            $width = $this->ez['columns']['width'];
            $this->ez['leftMargin'] = $this->ez['columns']['margins'][0] + ( $this->ez['columns']['colNum'] - 1 ) * ( $this->ez['columns']['options']['gap'] + $width );
            $this->ez['rightMargin'] = $this->ez['pageWidth'] - $this->ez['leftMargin'] - $width;
        }

        if ( $pageRequired )
        {
            // make a new page, setting the writing point back to the top
            $this->y = $this->ez['pageHeight']-$this->ez['topMargin'] - $this->getFontHeight();
            $this->ez['xOffset'] = 0;
            // make the new page with a call to the basic class.
            $this->ezPageCount++;
            if ( isset( $this->ez['insertMode'] ) && $this->ez['insertMode'] == 1 )
            {
                $id = $this->ezPages[$this->ezPageCount] = $this->newPage( 1, $this->ez['insertOptions']['id'], $this->ez['insertOptions']['pos'] );
                // then manipulate the insert options so that inserted pages follow each other
                $this->ez['insertOptions']['id'] = $id;
                $this->ez['insertOptions']['pos'] = 'after';
            }
            else
            {
                $this->ezPages[$this->ezPageCount] = $this->newPage();
            }
        }
        else
        {
            $this->y = $this->ez['pageHeight']-$this->ez['topMargin'] - $this->getFontHeight();
            $this->ez['xOffset'] = 0;
        }
        $this->RightMarginArray = array();
        $this->LeftMarginArray = array();
    }

// ------------------------------------------------------------------------------

    function ezSetMargins( $top, $bottom, $left, $right )
    {
        // sets the margins to new values
        $this->ez['topMargin'] = $top;
        $this->ez['bottomMargin'] = $bottom;
        $this->ez['leftMargin'] = $left;
        $this->ez['rightMargin'] = $right;
        $this->LeftMarginArray = array();
        $this->RightMarginArray = array();
        // check to see if this means that the current writing position is outside the
        // writable area
        if ( $this->y > $this->ez['pageHeight'] - $top )
        {
            // then move y down
            $this->ezSetY( $this->ez['pageHeight'] - $top );
        }
        if ( $this->y < $bottom )
        {
            // then make a new page
            $this->ezNewPage();
        }
    }

// ------------------------------------------------------------------------------

    function ezGetCurrentPageNumber()
    {
        // return the strict numbering (1,2,3,4..) number of the current page
        return $this->ezPageCount;
    }

// ------------------------------------------------------------------------------

    function ezStartPageNumbers( $x, $y, $size, $pos = 'left', $pattern = '{PAGENUM} of {TOTALPAGENUM}', $num = '' )
    {
        // put page numbers on the pages from here.
        // place then on the 'pos' side of the coordinates (x,y).
        // pos can be 'left' or 'right'
        // use the given 'pattern' for display, where (PAGENUM} and {TOTALPAGENUM} are replaced
        // as required.
        // if $num is set, then make the first page this number, the number of total pages will
        // be adjusted to account for this.
        // Adjust this function so that each time you 'start' page numbers then you effectively start a different batch
        // return the number of the batch, so that they can be stopped in a different order if required.
        if ( !$pos || !strlen( $pos ) )
        {
            $pos='left';
        }
        if ( !$pattern || !strlen( $pattern ) )
        {
            $pattern = '{PAGENUM} of {TOTALPAGENUM}';
        }
        if ( !isset( $this->ez['pageNumbering'] ) )
        {
            $this->ez['pageNumbering'] = array();
        }
        $i = count( $this->ez['pageNumbering'] );
        $this->ez['pageNumbering'][$i][$this->ezPageCount] = array( 'x' => $x, 'y' => $y, 'pos' => $pos, 'pattern' => $pattern, 'num' => $num, 'size' => $size );
        return $i;
    }

// ------------------------------------------------------------------------------

    function ezWhatPageNumber( $pageNum, $i = 0 )
    {
        // given a particular generic page number (ie, document numbered sequentially from beginning),
        // return the page number under a particular page numbering scheme ($i)
        $num = 0;
        $start = 1;
        $startNum = 1;
        if ( !isset( $this->ez['pageNumbering'] ) )
        {
            $this->addMessage( 'WARNING: page numbering called for and wasn\'t started with ezStartPageNumbers' );
            return 0;
        }
        foreach ( $this->ez['pageNumbering'][$i] as $k => $v )
        {
            if ( $k <= $pageNum )
            {
                if ( is_array( $v ) )
                {
                    // start block
                    if ( strlen( $v['num'] ) )
                    {
                        // a start was specified
                        $start = $v['num'];
                        $startNum = $k;
                        $num = $pageNum - $startNum + $start;
                    }
                }
                else
                {
                    // stop block
                    $num = 0;
                }
            }
        }
        return $num;
    }

// ------------------------------------------------------------------------------

    function ezStopPageNumbers( $stopTotal = 0, $next = 0, $i = 0 )
    {
        // if stopTotal=1 then the totalling of pages for this number will stop too
        // if $next=1, then do this page, but not the next, else do not do this page either
        // if $i is set, then stop that particular pagenumbering sequence.
        if ( !isset( $this->ez['pageNumbering'] ) )
        {
            $this->ez['pageNumbering'] = array();
        }
        if ( $next && isset( $this->ez['pageNumbering'][$i][$this->ezPageCount] ) &&
             is_array( $this->ez['pageNumbering'][$i][$this->ezPageCount] ) )
        {
            // then this has only just been started, this will over-write the start, and nothing will appear
            // add a special command to the start block, telling it to stop as well
            if ( $stopTotal )
            {
                $this->ez['pageNumbering'][$i][$this->ezPageCount]['stoptn'] = 1;
            }
            else
            {
                $this->ez['pageNumbering'][$i][$this->ezPageCount]['stopn'] = 1;
            }
        }
        else
        {
            if ( $stopTotal )
            {
                $this->ez['pageNumbering'][$i][$this->ezPageCount] = 'stopt';
            }
            else
            {
                $this->ez['pageNumbering'][$i][$this->ezPageCount] = 'stop';
            }
            if ( $next )
            {
                $this->ez['pageNumbering'][$i][$this->ezPageCount] .= 'n';
            }
        }
    }

// ------------------------------------------------------------------------------

    function ezPRVTpageNumberSearch( $lbl, &$tmp )
    {
        foreach ( $tmp as $i => $v )
        {
            if ( is_array( $v ) )
            {
                if ( isset( $v[$lbl] ) )
                {
                    return $i;
                }
            }
            else
            {
                if ( $v == $lbl )
                {
                    return $i;
                }
            }
        }
        return 0;
    }

// ------------------------------------------------------------------------------

    function ezPRVTaddPageNumbers()
    {
        // this will go through the pageNumbering array and add the page numbers are required
        if ( isset( $this->ez['pageNumbering'] ) )
        {
            $totalPages1 = $this->ezPageCount;
            $tmp1 = $this->ez['pageNumbering'];
            $status = 0;
            foreach ( $tmp1 as $i => $tmp )
            {
                // do each of the page numbering systems
                // firstly, find the total pages for this one
                $k = $this->ezPRVTpageNumberSearch( 'stopt', $tmp );
                if ( $k && $k > 0 )
                {
                    $totalPages = $k - 1;
                }
                else
                {
                    $l = $this->ezPRVTpageNumberSearch( 'stoptn', $tmp );
                    if ( $l && $l > 0 )
                    {
                        $totalPages = $l;
                    }
                    else
                    {
                        $totalPages = $totalPages1;
                    }
                }
                foreach ( $this->ezPages as $pageNum => $id )
                {
                    if ( isset( $tmp[$pageNum] ) )
                    {
                        if ( is_array( $tmp[$pageNum] ) )
                        {
                            // then this must be starting page numbers
                            $status = 1;
                            $info = $tmp[$pageNum];
                            $info['dnum'] = $info['num'] - $pageNum;
                            // also check for the special case of the numbering stopping and starting on the same page
                            if ( isset( $info['stopn'] ) || isset( $info['stoptn'] ) )
                            {
                                $status = 2;
                            }
                        }
                        else if ( $tmp[$pageNum] == 'stop' || $tmp[$pageNum] == 'stopt' )
                        {
                            // then we are stopping page numbers
                            $status = 0;
                        }
                        else if ( $status == 1 && ( $tmp[$pageNum] == 'stoptn' || $tmp[$pageNum] == 'stopn' ) )
                        {
                            // then we are stopping page numbers
                            $status = 2;
                        }
                    }
                    if ( $status )
                    {
                        // then add the page numbering to this page
                        if ( strlen( $info['num'] ) )
                        {
                            $num = $pageNum + $info['dnum'];
                        }
                        else
                        {
                            $num = $pageNum;
                        }
                        $total = $totalPages + $num - $pageNum;
                        $pat = str_replace( '{PAGENUM}', $num, $info['pattern'] );
                        $pat = str_replace( '{TOTALPAGENUM}', $total, $pat );
                        $this->reopenObject( $id );
                        switch ( $info['pos'] )
                        {
                            case 'right':
                                $this->addText( $info['x'], $info['y'], $info['size'], $pat );
                                break;
                            default:
                                $w = $this->getTextWidth( $info['size'], $pat );
                                $this->addText( $info['x'] - $w, $info['y'], $info['size'], $pat );
                                break;
                        }
                        $this->closeObject();
                    }
                    if ( $status == 2 )
                    {
                        $status = 0;
                    }
                }
            }
        }
    }

// ------------------------------------------------------------------------------

    function ezPRVTcleanUp()
    {
        $this->ezPRVTaddPageNumbers();
    }

// ------------------------------------------------------------------------------

    function ezStream( $options = '' )
    {
        $this->ezPRVTcleanUp();
        $this->stream( $options );
    }

// ------------------------------------------------------------------------------

    function ezOutput( $options = 0 )
    {
        $this->ezPRVTcleanUp();
        return $this->output( $options );
    }

// ------------------------------------------------------------------------------

    function ezSetY( $y )
    {
        // used to change the vertical position of the writing point.
        $this->y = $y;
        $this->ez['xOffset'] = 0;
        if ( $this->y < $this->ez['bottomMargin'] )
        {
            // then make a new page
            $this->ezNewPage();
        }
    }

// ------------------------------------------------------------------------------

    function ezSetDy( $dy, $mod = '' )
    {
        // used to change the vertical position of the writing point.
        // changes up by a positive increment, so enter a negative number to go
        // down the page
        // if $mod is set to 'makeSpace' and a new page is forced, then the pointed will be moved
        // down on the new page, this will allow space to be reserved for graphics etc.
        $this->y += $dy;
        $this->ez['xOffset'] = 0;
        if ( $this->y < $this->ez['bottomMargin'] )
        {
            // then make a new page
            $this->ezNewPage();
            if ( $mod == 'makeSpace' )
            {
                $this->y += $dy;
            }
        }
    }

// ------------------------------------------------------------------------------

    function ezPrvtTableDrawLines( $pos, $gap, $x0, $x1, $y0, $y1, $y2, $col, $inner, $outer, $opt = 1 )
    {
        $x0 = 1000;
        $x1 = 0;
        $this->setStrokeColorRGB( $col[0], $col[1], $col[2] );
        $cnt = 0;
        $n = count( $pos );
        foreach ( $pos as $x )
        {
            $cnt++;
            if ( $cnt == 1 || $cnt == $n )
            {
                $this->setLineStyle( $outer );
            }
            else
            {
                $this->setLineStyle( $inner );
            }
            $this->line( $x - $gap / 2, $y0, $x - $gap / 2, $y2 );
            if ( $x > $x1 )
            {
                $x1 = $x;
            }
            if ( $x < $x0 )
            {
                $x0 = $x;
            }
        }
        $this->setLineStyle( $outer );
        $this->line( $x0 - $gap / 2 - $outer / 2, $y0, $x1 - $gap / 2 + $outer / 2, $y0 );
        // only do the second line if it is different to the first, AND each row does not have
        // a line on it.
        if ( $y0 != $y1 && $opt < 2 )
        {
            $this->line( $x0 - $gap / 2, $y1, $x1 - $gap / 2, $y1 );
        }
        $this->line( $x0 - $gap / 2 - $outer / 2, $y2, $x1 - $gap / 2 + $outer / 2, $y2 );
    }

// ------------------------------------------------------------------------------

    function ezPrvtTableColumnHeadings( $cols, $pos, $maxWidth, $height, $decender, $gap, $size, &$y, $optionsAll = array() )
    {
        // uses ezText to add the text, and returns the height taken by the largest heading
        // this page will move the headings to a new page if they will not fit completely on this one
        // transaction support will be used to implement this

        if ( isset( $optionsAll['cols'] ) )
        {
            $options = $optionsAll['cols'];
        }
        else
        {
            $options = array();
        }

        $mx = 0;
        $startPage = $this->ezPageCount;
        $secondGo = 0;

        // $y is the position at which the top of the table should start, so the base
        // of the first text, is $y-$height-$gap-$decender, but ezText starts by dropping $height

        // the return from this function is the total cell height, including gaps, and $y is adjusted
        // to be the postion of the bottom line

        // begin the transaction
        $this->transaction( 'start' );
        $ok = 0;
//  $y-=$gap-$decender;
        $y -= $gap;
        while ( $ok == 0 )
        {
            foreach ( $cols as $colName => $colHeading )
            {
                $this->ezSetY( $y );
                if ( isset( $options[$colName] ) && isset( $options[$colName]['justification'] ) )
                {
                    $justification = $options[$colName]['justification'];
                }
                else
                {
                    $justification = $this->ez['justification'];
                }
//                $this->ezText($colHeading,$size,array('aleft'=> $pos[$colName],'aright'=>($maxWidth[$colName]+$pos[$colName]),'justification'=>$justification));
                $dy = $y - $this->y;
                if ( $dy > $mx )
                {
                    $mx = $dy;
                }
            }
            $y = $y - $mx - $gap + $decender;
//    $y -= $mx-$gap+$decender;

            // now, if this has moved to a new page, then abort the transaction, move to a new page, and put it there
            // do not check on the second time around, to avoid an infinite loop
            if ( $this->ezPageCount != $startPage && $secondGo == 0 )
            {
                $this->transaction( 'rewind' );
                $this->ezNewPage();
                $y = $this->y - $gap - $decender;
                $ok = 0;
                $secondGo = 1;
//      $y = $store_y;
                $mx = 0;
            }
            else
            {
                $this->transaction( 'commit' );
                $ok = 1;
            }
        }

        return $mx + $gap * 2 - $decender;
    }

// ------------------------------------------------------------------------------

    /*!
     Get maximum length of single word in sentence.

     \param font size
     \param text ( sentence )

     \return maximum word width
    */
    function eZGetMaxWordWidth( $size, $text )
    {
        $mx = 0;
        $text = str_replace( '-', ' ', $text );
        $words = str_word_count( $text, 1 );
        foreach ( $words as $word )
        {
            $w = $this->getTextWidth($size,$word);
            if ( $w > $mx )
            {
                $mx = $w;
            }
        }
        return $mx;
    }

    function ezPrvtGetTextWidth( $size, $text )
    {
        // will calculate the maximum width, taking into account that the text may be broken
        // by line breaks.
        $mx = 0;
        $lines = explode( "\n", $text );
        foreach ( $lines as $line )
        {
            $w = $this->getTextWidth( $size, $line );
            if ( $w > $mx )
            {
                $mx = $w;
            }
        }
        return $mx;
    }

    /**
     Draw a shaded rectangle

     direction is optional and set to vertical by default

     \param x1
     \param y1
     \param width
     \param height
     \param col1
     \param col2
     \param direction ('vertical', 'horizontal', or angle)
    */
    function ezShadedRectangle( $x1, $y1, $width, $height, $col1, $col2, $direction = 'vertical' )
    {
        $this->shadedRectangle( $x1, $y1, $width, $height, array( 'orientation' => $direction,
                                                                  'color0' => $col1,
                                                                  'color1' => $col2,
                                                                  'size' => array( 'x1' => $x1,
                                                                                   'y1' => $y1,
                                                                                   'width' => $width,
                                                                                   'height' => $height ) ) );
    }

// ------------------------------------------------------------------------------
    function ezProcessText( $text )
    {
        // this function will intially be used to implement underlining support, but could be used for a range of other
        // purposes
        $search = array( '<u>', '<U>', '</u>', '</U>' );
        $replace = array( '<c:uline>', '<c:uline>', '</c:uline>', '</c:uline>' );
        return str_replace( $search, $replace, $text );
    }

// ------------------------------------------------------------------------------

    function ezText( $text, $size = 0, $options = array(), $test = 0 )
    {
        // this will add a string of text to the document, starting at the current drawing
        // position.
        // it will wrap to keep within the margins, including optional offsets from the left
        // and the right, if $size is not specified, then it will be the last one used, or
        // the default value (12 I think).
        // the text will go to the start of the next line when a return code "\n" is found.
        // possible options are:
        // 'left'=> number, gap to leave from the left margin
        // 'right'=> number, gap to leave from the right margin
        // 'aleft'=> number, absolute left position (overrides 'left')
        // 'aright'=> number, absolute right position (overrides 'right')
        // 'justification' => 'left','right','center','centre','full'
        // 'top_margin' => set top margin manualy

        // only set one of the next two items (leading overrides spacing)
        // 'leading' => number, defines the total height taken by the line, independent of the font height.
        // 'spacing' => a real number, though usually set to one of 1, 1.5, 2 (line spacing as used in word processing)

        // if $test is set then this should just check if the text is going to flow onto a new page or not, returning true or false

        // apply the filtering which will make the underlining function.
        if ( strlen( $text ) == 0 )
            return $this->y;

        $text = $this->ezProcessText( $text );

        $newPage = false;
        $store_y = $this->y;
        $left = $angle = $adjust = 0;

        if ( is_array( $options ) && isset( $options['aright'] ) )
        {
            $right = $options['aright'];
        }
        else
        {
            $right = $this->ez['pageWidth'] - $this->rightMargin() - ( ( is_array( $options ) && isset( $options['right'] ) ) ? $options['right'] : 0 );
        }
        if ( $size <= 0 )
        {
            $size = $this->ez['fontSize'];
        }
        else
        {
            $this->ez['fontSize'] = $size;
        }

        if ( is_array( $options ) && isset( $options['top_margin'] ) )
        {
            $this->y = $this->ez['pageHeight'] - $options['top_margin'];
        }

        if ( is_array( $options ) && isset( $options['justification'] ) )
        {
            $just = $options['justification'];
        }
        else
        {
            $just = $this->ez['justification'];
        }

        // modifications to give leading and spacing based on those given by Craig Heydenburg 1/1/02
        if ( is_array( $options ) && isset( $options['leading'] ) )
        { // use leading instead of spacing
            $height = $options['leading'];
        }
        else if ( is_array( $options ) && isset( $options['spacing'] ) )
        {
            $height = $this->getFontHeight( $size ) * $options['spacing'];
        }
        else
        {
            $height = $this->getFontHeight( $size );
        }

        $lastOnlyDirective = false;
        $lines = explode( "\n", $text );
        if ( $text == "\n" )
        {
            $lines = array( "" );
        }
        foreach ( array_keys( $lines ) as $key )
        {
            $line = $lines[$key];
            if ( ( $key > 0 || strlen( $line ) == 0 ) &&
                 !$lastOnlyDirective )
            {
                $this->y = $this->y - $height;
                $this->ez['xOffset'] = 0;
            }
            $lastOnlyDirective = false;
            while ( strlen( $line ) )
            {
                $noClose = 1;
                while ( $noClose )
                {
                    $f = 1;
                    $directiveArray = $this->PRVTcheckTextDirective( $line, 0, $f );
                    $noClose = $directiveArray['noClose'];
                    if ( $noClose )
                    {
                        if ( $this->ez['xOffset'] != 0 )
                        {
                            $left = $this->ez['xOffset'];
                        }
                        else
                        {
                            $left = $this->leftMargin() + ( (is_array( $options ) && isset( $options['left'] ) ) ? $options['left'] : 0 );
                        }

                        $textInfo = $this->addText( $left, $this->y, $size, substr( $line, 0, $directiveArray['directive'] ), $angle, $adjust );
                        $line = substr( $line, $directiveArray['directive'] );
                        if ( isset( $textInfo['width'] ) && $textInfo['width'] != 0 )
                        {
                            $this->setXOffset( $left + $textInfo['width'] );
                        }
                    }
                }

                if ( $this->y < $this->ez['bottomMargin'] )
                {
                    if ( $test )
                    {
                        $newPage = true;
                    }
                    else if ( strlen( trim( $line ) ) )
                    {
                        $this->ezNewPage();
                        // and then re-calc the left and right, in case they have changed due to columns
                    }
                }
                if ( $this->ez['xOffset'] != 0 )
                {
                    $left = $this->ez['xOffset'];
                }
                else
                {
                    $left = $this->leftMargin() + ( ( is_array( $options ) && isset( $options['left'] ) ) ? $options['left'] : 0 );
                }

                if ( is_array( $options ) && isset( $options['aleft'] ) )
                {
                    $left=$options['aleft'];
                }
                if ( is_array( $options ) && isset( $options['aright'] ) )
                {
                    $right = $options['aright'];
                }
                else
                {
                    $right = $this->ez['pageWidth'] - $this->rightMargin() - ( ( is_array( $options ) && isset( $options['right'] ) ) ? $options['right'] : 0 );
                }

                $textInfo = $this->addTextWrap( $left, $this->y, $right - $left, $size, $line, $just, 0, $test );
                if ( isset( $textInfo['only_directive'] ) &&
                     $textInfo['only_directive'] === true )
                {
                    $lastOnlyDirective = true;
                    $line = '';
                    continue;
                }
                $line = $textInfo['text'];
                if ( strlen( $line ) || $textInfo['width'] == 0 )
                {
                    $this->y = $this->y - $height;
                    $this->ez['xOffset'] = 0;
                }
                else if ( $textInfo['width'] != 0 )
                {
                    $this->setXOffset( $left + $textInfo['width'] );
                }
            }
        }

        if ( $test )
        {
            $this->y = $store_y;
            return $newPage;
        }
        else
        {
            return $this->y;
        }
    }

// ------------------------------------------------------------------------------

    function ezImage( $image, $pad = 5, $width = 0, $resize = 'full', $just = 'center', $border = '' )
    {
        // beta ezimage function
        if ( stristr( $image, '://' ) ) //copy to temp file
        {
            $fp = @fopen( $image, "rb" );
            while( !feof( $fp ) )
            {
                $cont.= fread( $fp, 1024 );
            }
            fclose( $fp );
            $image = tempnam ( "/tmp", "php-pdf" );
            $fp2 = @fopen( $image, "w" );
            fwrite( $fp2, $cont );
            fclose( $fp2 );
            $temp = true;
        }

        if ( !( file_exists( $image ) ) )
            return false; // return immediately if image file does not exist
        $imageInfo = getimagesize( $image );
        switch ( $imageInfo[2] )
        {
            case 2:
                $type = "jpeg";
                break;
            case 3:
                $type = "png";
                break;
            default:
                return false; // return if file is not jpg or png
        }
        if ( $width == 0 )
            $width = $imageInfo[0]; // set width
        $ratio = $imageInfo[0] / $imageInfo[1];

        //get maximum width of image
        if ( isset( $this->ez['columns'] ) && $this->ez['columns']['on'] == 1 )
        {
            $bigwidth = $this->ez['columns']['width'] - ( $pad * 2 );
        }
        else
        {
            $bigwidth = $this->ez['pageWidth'] - ( $pad * 2 );
        }
        //fix width if larger than maximum or if $resize=full
        if ( $resize == 'full' || $resize == 'width' || $width > $bigwidth )
        {
            $width = $bigwidth;
        }

        $height = ( $width / $ratio ); //set height

        //fix size if runs off page
        if ( $height > ( $this->y - $this->ez['bottomMargin'] - ( $pad * 2 ) ) )
        {
            if ( $resize != 'full' )
            {
                $this->ezNewPage();
            }
            else
            {
                $height = ( $this->y - $this->ez['bottomMargin'] - ( $pad * 2 ) ); // shrink height
                $width = ( $height * $ratio ); // fix width
            }
        }

        // fix x-offset if image smaller than bigwidth
        if ( $width < $bigwidth )
        {
            // center if justification=center
            if ( $just == 'center' )
            {
                $offset = ( $bigwidth - $width ) / 2;
            }
            //move to right if justification=right
            if ( $just == 'right' )
            {
                $offset = ( $bigwidth - $width );
            }
            //leave at left if justification=left
            if ( $just == 'left' )
            {
                $offset = 0;
            }
        }


        // call appropriate function
        if ( $type == "jpeg" )
        {
            $this->addJpegFromFile( $image, $this->leftMargin() + $pad + $offset, $this->y + $this->getFontHeight( $this->ez['fontSize'] ) - $pad - $height, $width );
        }

        if ( $type == "png" )
        {
            $this->addPngFromFile( $image, $this->leftMargin() + $pad + $offset, $this->y + $this->getFontHeight( $this->ez['fontSize'] ) - $pad - $height, $width );
        }
        //draw border
        if ( $border != '' )
        {
            if ( !( isset( $border['color'] ) ) )
            {
                $border['color']['red'] = .5;
                $border['color']['blue'] = .5;
                $border['color']['green'] = .5;
            }
            if ( !( isset( $border['width'] ) ) )
                $border['width'] = 1;
            if ( !( isset( $border['cap'] ) ) )
                $border['cap'] = 'round';
            if ( !( isset( $border['join']) ) )
                $border['join'] = 'round';

            $this->setStrokeColorRGB( $border['color']['red'], $border['color']['green'], $border['color']['blue'] );
            $this->setLineStyle( $border['width'], $border['cap'], $border['join'] );
            $this->rectangle( $this->leftMargin() + $pad + $offset, $this->y + $this->getFontHeight( $this->ez['fontSize'] ) - $pad - $height, $width, $height );

        }
        // move y below image
        $this->y = $this->y - $pad - $height;
        $this->ez['xOffset'] = 0;
        //remove tempfile for remote images
        if ( $temp == true )
            unlink( $image );

    }
// ------------------------------------------------------------------------------

// note that templating code is still considered developmental - have not really figured
// out a good way of doing this yet.
    function loadTemplate( $templateFile )
    {
        // this function will load the requested template ($file includes full or relative pathname)
        // the code for the template will be modified to make it name safe, and then stored in
        // an array for later use
        // The id of the template will be returned for the user to operate on it later
        if ( !file_exists( $templateFile ) )
        {
            return -1;
        }

        $code = implode( '', file( $templateFile ) );
        if ( !strlen( $code ) )
        {
            return;
        }

        $code = trim( $code );
        if ( substr( $code, 0, 5 ) == '<?php' )
        {
            $code = substr( $code, 5 );
        }
        if ( substr( $code, -2 ) == '?>' )
        {
            $code = substr( $code, 0, strlen( $code ) - 2 );
        }
        if ( isset( $this->ez['numTemplates'] ) )
        {
            $newNum = $this->ez['numTemplates'];
            $this->ez['numTemplates']++;
        }
        else
        {
            $newNum = 0;
            $this->ez['numTemplates'] = 1;
            $this->ez['templates'] = array();
        }

        $this->ez['templates'][$newNum]['code'] = $code;

        return $newNum;
    }

// ------------------------------------------------------------------------------

    function execTemplate( $id, $data = array(), $options = array() )
    {
        // execute the given template on the current document.
        if ( !isset( $this->ez['templates'][$id] ) )
        {
            return;
        }
        eval( $this->ez['templates'][$id]['code'] );
    }

// ------------------------------------------------------------------------------
    function ilink( $info )
    {
        return $this->alink( $info, 1 );
    }

    function alink( $info, $internal = 0 )
    {
        // a callback function to support the formation of clickable links within the document
        $lineFactor = 0.05; // the thickness of the line as a proportion of the height. also the drop of the line.
        switch( $info['status'] )
        {
            case 'start':
            case 'sol':
                // the beginning of the link
                // this should contain the URl for the link as the 'p' entry, and will also contain the value of 'nCallback'
                if (!isset($this->ez['links']))
                {
                    $this->ez['links'] = array();
                }
                $i = $info['nCallback'];

                $this->ez['links'][$i] = array( 'x' => $info['x'],
                                                'y' => $info['y'],
                                                'angle' => $info['angle'],
                                                'decender' => $info['decender'],
                                                'height' => $info['height'],
                                                'url'=>rawurldecode( $info['p'] ) );
                if ( $internal == 0 )
                {
                    $this->saveState();
                    $this->setColorRGB( 0, 0, 1 );
                    $this->setStrokeColorRGB( 0, 0, 1 );
                    $thick = $info['height'] * $lineFactor;
                    $this->setLineStyle( $thick );
                }
                break;
            case 'end':
            case 'eol':
                // the end of the link
                // assume that it is the most recent opening which has closed
                $i = $info['nCallback'];
                $start = $this->ez['links'][$i];
                // add underlining
                if ($internal)
                {
                    $this->addInternalLink( $start['url'], $start['x'], $start['y'] + $start['decender'], $info['x'], $start['y'] + $start['decender'] + $start['height'] );
                }
                else
                {
                    $a = deg2rad( (float)$start['angle'] - 90.0 );
                    $drop = $start['height'] * $lineFactor * 1.5;
                    $dropx = cos( $a ) * $drop;
                    $dropy = -sin( $a ) * $drop;
                    $this->line( $start['x'] - $dropx, $start['y'] - $dropy, $info['x'] - $dropx, $info['y'] - $dropy );
                    $this->addLink( $start['url'], $start['x'], $start['y'] + $start['decender'], $info['x'], $start['y'] + $start['decender'] + $start['height'] );
                    $this->restoreState();
                }
                break;
        }
    }

// ------------------------------------------------------------------------------

    function uline( $info )
    {
        // a callback function to support underlining
        $lineFactor = 0.05; // the thickness of the line as a proportion of the height. also the drop of the line.
        switch ( $info['status'] )
        {
            case 'start':
            case 'sol':
                // the beginning of the underline zone
                if ( !isset( $this->ez['links'] ) )
                {
                    $this->ez['links'] = array();
                }
                $i = $info['nCallback'];
                $this->ez['links'][$i] = array( 'x' => $info['x'],
                                                'y' => $info['y'],
                                                'angle' => $info['angle'],
                                                'decender' => $info['decender'],
                                                'height' => $info['height'] );
                $this->saveState();
                $thick = $info['height'] * $lineFactor;
                $this->setLineStyle( $thick );
                break;

            case 'end':
            case 'eol':
                // the end of the link
                // assume that it is the most recent opening which has closed
                $i = $info['nCallback'];
                $start = $this->ez['links'][$i];
                // add underlining
                $a = deg2rad( (float) $start['angle'] - 90.0 );
                $drop = $start['height'] * $lineFactor * 1.5;
                $dropx = cos( $a ) * $drop;
                $dropy = -sin( $a ) * $drop;
                $this->line( $start['x'] - $dropx, $start['y'] - $dropy, $info['x'] - $dropx, $info['y'] - $dropy );
                $this->restoreState();
                break;
        }
    }

// ------------------------------------------------------------------------------
// 2003-11-04 Kåre Køhler Høvik ( eZ Systems, http://ez.no )
// Set fontsize

    function strike( $info )
    {
        // a callback function to support underlining
        $lineFactor = 0.05; // the thickness of the line as a proportion of the height. also the drop of the line.
        switch ( $info['status'] )
        {
            case 'start':
            case 'sol':
                // the beginning of the underline zone
                if ( !isset( $this->ez['links'] ) )
                {
                    $this->ez['links'] = array();
                }
                $i = $info['nCallback'];
                $this->ez['links'][$i] = array( 'x' => $info['x'],
                                                'y' => $info['y'],
                                                'angle' => $info['angle'],
                                                'decender' => $info['decender'],
                                                'height' => $info['height'] );
                $this->saveState();
                $thick = $info['height'] * $lineFactor;
                $this->setLineStyle( $thick );
                break;

            case 'end':
            case 'eol':
                // the end of the link
                // assume that it is the most recent opening which has closed
                $i = $info['nCallback'];
                $start = $this->ez['links'][$i];
                // add underlining
                $a = deg2rad( (float) $start['angle'] - 90.0 );
                $drop = $start['height'] / 2;
                $dropx = cos( $a ) * $drop;
                $dropy = -sin( $a ) * $drop;
                $this->line( $start['x'] - $dropx, $start['y'] + $dropy, $info['x'] - $dropx, $info['y'] + $dropy );
                $this->restoreState();
                break;
        }
    }

    /**
     * Get current line height
     */
    function lineHeight( $options = array() )
    {
        if ( is_array( $options ) && isset( $options['size'] ) )
        {
            $size = $options['size'];
        }
        else
        {
            $size = $this->ez['fontSize'];
        }

        if ( is_array( $options ) && isset( $options['leading'] ) )
        {   // use leading instead of spacing
            $height = $options['leading'];
        }
        else if ( is_array( $options ) && isset( $options['spacing'] ) )
        {
            $height = $this->getFontHeight( $size ) * $options['spacing'];
        }
        else
        {
            $height = $this->getFontHeight( $size );
        }

        return $height;
    }

    /*!
     Get right margin, page offset

     \param y offset ( optional )
     \return right margin
    */
    function rightMargin( $yOffset = false )
    {
        if ( $yOffset === false )
        {
            $yOffset = $this->yOffset();
        }

        foreach ( $this->RightMarginArray as $rightMargin )
        {
            if ( $yOffset > $rightMargin['start'] &&
                 $yOffset < $rightMargin['stop'] )
            {
                return $rightMargin['margin'];
            }
        }

        return $this->ez['rightMargin'];
    }

    /*!
     Get left margin, page offset

     \param y offset ( optional )
     \return left margin
    */
    function leftMargin( $yOffset = false )
    {
        $maxMargin = $this->ez['leftMargin'];

        if ( $yOffset === false )
        {
            $yOffset = $this->yOffset();
        }

        foreach ( $this->LeftMarginArray as $leftMargin )
        {
            if ( $yOffset > $leftMargin['start'] &&
                 $yOffset < $leftMargin['stop']  &&
                 $leftMargin['margin'] > $maxMargin )
            {
                $maxMargin = $leftMargin['margin'];
            }
        }

        return $maxMargin;
    }

    /*!
     Set left margin for limited range

     \param y start
     \param y stop
     \param new left margin
    */
    function setLimitedLeftMargin( $startY, $stopY, $leftMargin )
    {
        $this->LeftMarginArray[] = array( 'start' => $startY,
                                          'stop' => $stopY,
                                          'margin' => $leftMargin );
    }

    /*!
     Set right margin for limited range

     \param y start
     \param y stop
     \param new right margin
    */
    function setLimitedRightMargin( $startY, $stopY, $rightMargin )
    {
        $this->RightMarginArray[] = array( 'start' => $startY,
                                           'stop' => $stopY,
                                           'margin' => $rightMargin );
    }

    public $LeftMarginArray = array();
    public $RightMarginArray = array();
}

// ------------------------------------------------------------------------------


?>
