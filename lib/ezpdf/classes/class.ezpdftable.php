<?php

//
// Created on: <01-Sep-2003 13:23:32 kk>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezpdftable.php
*/

include_once( 'lib/ezpdf/classes/class.ezpdf.php' );

/**
 This class extents Cezpdf ( class.ezpdf.php ) and adds extra support to tables.
*/

class eZPDFTable extends Cezpdf
{

    /**
     Constructor. This class is only used to encapsulate a table.
    */
    function eZPDFTable()
    {
        $this->Cezpdf();
    }

    /**
     Get the current Y offset
    */
    function offsetY()
    {
        return $this->y;
    }

    /*!
      Add a basic XHTML table to the pdf. This funtions converts basich html, and adds to the pdf file
    */
    function addXHTMLTable( &$table, $options='' )
    {
    }

    /** add a table of information to the pdf document
     * $data is a two dimensional array
     * $cols (optional) is an associative array, the keys are the names of the columns from $data
     * to be presented (and in that order), the values are the titles to be given to the columns
     * $title (optional) is the title to be put on the top of the table
     *
     * $options is an associative array which can contain:
     * 'cellData' => array( <coord> => array( 'size' => array( <width>, <height>),
     *                                        'justification' => <left|right|center> ),
     *                      <coord>....)
     *            All non specified coords will be threated with default settings. Coord is text, offset 0, ex: '5,6'
     *            Coord 'x,0' is table header
     * 'showLines'=> 0,1,2, default is 1 (show outside and top lines only), 2=> lines on each row
     * 'showHeadings' => 0 or 1
     * 'shaded'=> 0,1,2,3 default is 1 (1->alternate lines are shaded, 0->no shading, 2-> both shaded, second uses shadeCol2)
     * 'shadeCol' => (r,g,b) array, defining the colour of the shading, default is (0.8,0.8,0.8)
     * 'shadeCol2' => (r,g,b) array, defining the colour of the shading of the other blocks, default is (0.7,0.7,0.7)
     * 'fontSize' => 10
     * 'textCol' => (r,g,b) array, text colour
     * 'titleFontSize' => 12
     * 'rowGap' => 2 , the space added at the top and bottom of each row, between the text and the lines
     * 'colGap' => 5 , the space on the left and right sides of each cell
     * 'lineCol' => (r,g,b) array, defining the colour of the lines, default, black.
     * 'xPos' => 'left','right','center','centre',or coordinate, reference coordinate in the x-direction
     * 'xOrientation' => 'left','right','center','centre', position of the table w.r.t 'xPos'
     * 'width'=> <number> which will specify the width of the table, if it turns out to not be this
     *    wide, then it will stretch the table to fit, if it is wider then each cell will be made
     *    proportionalty smaller, and the content may have to wrap.
     * 'maxWidth'=> <number> similar to 'width', but will only make table smaller than it wants to be
     * 'options' => array(<colname>=>array('justification'=>'left','width'=>100,'link'=>linkDataName),<colname>=>....)
     *             allow the setting of other paramaters for the individual columns
     * 'minRowSpace'=> the minimum space between the bottom of each row and the bottom margin, in which a new row will be started
     *                  if it is less, then a new page would be started, default=-100
     * 'innerLineThickness'=>1
     * 'outerLineThickness'=>1
     * 'splitRows'=>0, 0 or 1, whether or not to allow the rows to be split across page boundaries
     * 'protectRows'=>number, the number of rows to hold with the heading on page, ie, if there less than this number of
     *                rows on the page, then move the whole lot onto the next page, default=1
     *
     * note that the user will have had to make a font selection already or this will not
     * produce a valid pdf file.
     */
    function ezTable(&$data,$cols='',$title='',$options='')
    {

        if (!is_array($data)){
            return;
        }

        // Get total column count and column indexes
        if (!is_array($cols)){
            // take the columns from the first row of the data set
            reset($data);
            list($k,$v)=each($data);
            if (!is_array($v)){
                return;
            }
            $cols=array();

            $realCount = 0;
            for ( $c = 0; $c < count($v); $c++ )
            {
                if ( isset( $options['cellData'][$realCount.',0']['size'] ) )
                {
                    for ( $innerCount = 0; $innerCount < $options['cellData'][$realCount.',0']['size'][0]; $innerCount++ )
                        $cols[$realCount] = $realCount++;
                }
                else
                {
                    $cols[$realCount] = $realCount++;
                }
            }
        }

        if (!is_array($options)){
            $options=array();
        }

        $defaults = array(
            'shaded'=>1,'showLines'=>1,'shadeCol'=>array(0.8,0.8,0.8),'shadeCol2'=>array(0.7,0.7,0.7),'fontSize'=>10,'titleFontSize'=>12
            ,'titleGap'=>5,'lineCol'=>array(0,0,0),'gap'=>5,'xPos'=>'centre','xOrientation'=>'centre'
            ,'showHeadings'=>1,'textCol'=>array(0,0,0),'width'=>0,'maxWidth'=>0,'cols'=>array(),'minRowSpace'=>-100,'rowGap'=>2,'colGap'=>5
            ,'innerLineThickness'=>1,'outerLineThickness'=>1,'splitRows'=>0,'protectRows'=>1
            );

        foreach($defaults as $key=>$value){
            if (is_array($value)){
                if (!isset($options[$key]) || !is_array($options[$key])){
                    $options[$key]=$value;
                }
            } else {
                if (!isset($options[$key])){
                    $options[$key]=$value;
                }
            }
        }
        $options['gap']=2*$options['colGap'];

        $middle = ($this->ez['pageWidth']-$this->ez['rightMargin'])/2+($this->ez['leftMargin'])/2;
        // figure out the maximum widths of the text within each column
        $maxWidth = array();

        // find the maximum cell widths based on the data
        foreach ( $data as $rowCount=>$row)
        {
            $realColCount = 0;
            for( $columnCount = 0; $columnCount < count( $row ); $columnCount++ )
            {
                // get col span
                $colSpan = 1;
                if ( isset( $options['cellData'][$realColCount.','.$rowCount]['size'] ) )
                {
                    $colSpan = $options['cellData'][$realColCount.','.$rowCount]['size'][0];
                }

                //get and set max width
                $w = $this->ezPrvtGetTextWidth($options['fontSize'],(string)$row[$columnCount])*1.01; // total actual text width
                if ( isset( $maxWidth[$colSpan][$realColCount] ) )
                {
                    if ( $w > $maxWidth[$colSpan][$realColCount] )
                    {
                        $maxWidth[$colSpan][$realColCount] = $w;
                    }
                }
                else
                {
                    $maxWidth[$colSpan][$realColCount] = $w;
                }

//                echo "row: $rowCount, column: $columnCount:$realColCount,<br/>";
                $realColCount += $colSpan;
            }
        }

        // and the maximum widths to fit in the headings //TODO: calculate max header width
/*        foreach($cols as $colName=>$colTitle){
            $w = $this->ezPrvtGetTextWidth($options['fontSize'],(string)$colTitle)*1.01;
            if ($w > $maxWidth[$colName]){
                $maxWidth[$colName]=$w;
            }
        } */

        // calculate the start positions of each of the columns
        // Set pre defined max column width data
        for ( $columnCount = 0; $columnCount < count( $cols ); $columnCount++ ){
            if ( isset( $options['cols'][$columnCount] ) && isset($options['cols'][$columnCount]['width']) && $options['cols'][$colName]['width']>0)
            {
                $colSpan = 1;
                if ( isset( $options['cellData'][$realColCount.',0']['size'] ) )
                {
                    $colSpan = $options['cellData'][$realColCount.',0']['size'][0];
                }

                $maxWidth[$colSpan][$columnCount] = $options['cols'][$colName]['width'] - $options['gap'];
            }
        }

//        print_r( $maxWidth );
//        echo '<br/><br/>';

        $pos=array();
        $columnWidths = array();
        for ( $offset = 0; $offset < count( $cols ); )
            $columnWidths[$offset++] = 0;
        $x=0;
        $t=$x;
        $adjustmentWidth=0;
        $setWidth=0;
        foreach ( $maxWidth as $span => $tmp1 )
        {
            foreach ( $maxWidth[$span] as $offset => $tmp2 )
            {
                $currentWidth = 0;
                for ( $innerCount = 0; $innerCount < $span; $innerCount++ )
                {
                    $currentWidth += $columnWidths[$offset+$innerCount];
                }
                if ( $maxWidth[$span][$offset] > $currentWidth )
                {
                    if ( $currentWidth == 0 ) // no width set
                    {
                        for ( $i = 0; $i < $span; $i++ )
                            $columnWidths[$offset+$i] = ceil( $maxWidth[$span][$offset] / $span );
                    }
                    else // scale previous set widths
                    {
                        for ( $i = 0; $i < $span; $i++ )
                            $columnWidths[$offset+$i] = ceil( $maxWidth[$span][$offset] / $currentWidth * $columnWidths[$offset+$i] );
                    }
                }
            }
        }

//        print_r( $columnWidths );
//        echo '<br/><br/>';

        foreach ( $columnWidths as $count => $width )
        {
            $pos[$count]=$t;
            // if the column width has been specified then set that here, also total the
            $t += $width + $options['gap'];
            $adjustmentWidth += $width;
            $setWidth += $options['gap'];
        }
        $pos['_end_'] = $t;


//        print_r( $pos );
//        echo '<br/><br/>';

        // if maxWidth is specified, and the table is too wide, and the width has not been set,
        // then set the width.
        if ($options['width']==0 && $options['maxWidth'] && $pos['_end_']>$options['maxWidth']){
            // then need to make this one smaller
            $options['width']=$options['maxWidth'];
        }

        // calculated width as forced. Shrink or enlarge
        if ( $options['width'] && $adjustmentWidth>0 ){
            $newCleanWidth = $options['width'] - $setWidth;
            $t = 0;
            foreach ( $columnWidths as $count => $width )
            {
                $pos[$count] = $t;
                $columnWidths[$count] = round( $newCleanWidth/$adjustmentWidth * $columnWidths[$count] );
                $t += $columnWidths[$count] + $options['gap'];
            }
            $pos['_end_']=$t;
        }

        // now adjust the table to the correct location across the page
        switch ($options['xPos']){
            case 'left':
                $xref = $this->ez['leftMargin'];
            break;
            case 'right':
                $xref = $this->ez['pageWidth'] - $this->ez['rightMargin'];
            break;
            case 'centre':
            case 'center':
                $xref = $middle;
            break;
            default:
                $xref = $options['xPos'];
            break;
        }
        switch ($options['xOrientation']){
            case 'left':
                $dx = $xref-$t;
            break;
            case 'right':
                $dx = $xref;
            break;
            case 'centre':
            case 'center':
                $dx = $xref-$t/2;
            break;
        }

        foreach($pos as $key=>$value){
            $pos[$key] += $dx;
        }
        $x0=$x+$dx;
        $x1=$t+$dx;

        $baseLeftMargin = $this->ez['leftMargin'];
        $basePos = $pos;
        $baseX0 = $x0;
        $baseX1 = $x1;

        // ok, just about ready to make me a table
        $this->setColor($options['textCol'][0],$options['textCol'][1],$options['textCol'][2]);
        $this->setStrokeColor($options['shadeCol'][0],$options['shadeCol'][1],$options['shadeCol'][2]);

        $middle = ($x1+$x0)/2;

        // start a transaction which will be used to regress the table, if there are not enough rows protected
        if ($options['protectRows']>0){
            $this->transaction('start');
            $movedOnce=0;
        }
        $abortTable = 1;
        while ($abortTable){
            $abortTable=0;

            $dm = $this->ez['leftMargin']-$baseLeftMargin;
            foreach($basePos as $key=>$value){
                $pos[$key] += $dm;
            }
            $x0=$baseX0+$dm;
            $x1=$baseX1+$dm;
            $middle = ($x1+$x0)/2;


            // if the title is set, then do that
            if (strlen($title)){
                $w = $this->getTextWidth($options['titleFontSize'],$title);
                $this->y -= $this->getFontHeight($options['titleFontSize']);
                if ($this->y < $this->ez['bottomMargin']){
                    $this->ezNewPage();
                    // margins may have changed on the newpage
                    $dm = $this->ez['leftMargin']-$baseLeftMargin;
                    foreach($basePos as $key=>$value){
                        $pos[$key] += $dm;
                    }
                    $x0=$baseX0+$dm;
                    $x1=$baseX1+$dm;
                    $middle = ($x1+$x0)/2;
                    $this->y -= $this->getFontHeight($options['titleFontSize']);
                }
                $this->addText($middle-$w/2,$this->y,$options['titleFontSize'],$title);
                $this->y -= $options['titleGap'];
            }

            // margins may have changed on the newpage
            $dm = $this->ez['leftMargin']-$baseLeftMargin;
            foreach($basePos as $key => $value){
                $pos[$key] += $dm;
            }
            $x0=$baseX0+$dm;
            $x1=$baseX1+$dm;

            $y=$this->y; // to simplify the code a bit

            // make the table
            $height = $this->getFontHeight($options['fontSize']);
            $decender = $this->getFontDecender($options['fontSize']);

            $y0=$y+$decender;
            $dy=0;
            if ($options['showHeadings']){
                // this function will move the start of the table to a new page if it does not fit on this one
                $headingHeight = $this->ezPrvtTableColumnHeadings($cols,$pos,$maxWidth,$height,$decender,$options['rowGap'],$options['fontSize'],$y,$options);
                $y0 = $y+$headingHeight;
                $y1 = $y;

                $dm = $this->ez['leftMargin']-$baseLeftMargin;
                foreach($basePos as $k=>$v){
                    $pos[$k]=$v+$dm;
                }
                $x0=$baseX0+$dm;
                $x1=$baseX1+$dm;

            } else {
                $y1 = $y0;
            }
            $firstLine=1;


            // open an object here so that the text can be put in over the shading
            if ($options['shaded']){
                $this->saveState();
                $textObjectId = $this->openObject();
                $this->closeObject();
                $this->addObject($textObjectId);
                $this->reopenObject($textObjectId);
            }

            $cnt=0;
            $newPage=0;
            foreach($data as $rowCount => $row){
                $cnt++;
                // the transaction support will be used to prevent rows being split
                if ($options['splitRows']==0){
                    $pageStart = $this->ezPageCount;
                    if (isset($this->ez['columns']) && $this->ez['columns']['on']==1){
                        $columnStart = $this->ez['columns']['colNum'];
                    }
                    $this->transaction('start');
                    $row_orig = $row;
                    $y_orig = $y;
                    $y0_orig = $y0;
                    $y1_orig = $y1;
                }
                $ok=0;
                $secondTurn=0;
                while(!$abortTable && $ok == 0){

                    $mx=0;
                    $newRow=1;
                    while(!$abortTable && ($newPage || $newRow)){

                        $y-=$height;
                        if ($newPage || $y<$this->ez['bottomMargin'] || (isset($options['minRowSpace']) && $y<($this->ez['bottomMargin']+$options['minRowSpace'])) ){
                            // check that enough rows are with the heading
                            if ($options['protectRows']>0 && $movedOnce==0 && $cnt<=$options['protectRows']){
                                // then we need to move the whole table onto the next page
                                $movedOnce = 1;
                                $abortTable = 1;
                            }

                            $y2=$y-$mx+2*$height+$decender-$newRow*$height;
                            if ($options['showLines']){
                                if (!$options['showHeadings']){
                                    $y0=$y1;
                                }
//                                $this->ezPrvtTableDrawLines($pos,$options['gap'],$x0,$x1,$y0,$y1,$y2,$options['lineCol'],$options['innerLineThickness'],$options['outerLineThickness'],$options['showLines']);
                            }
                            if ($options['shaded']){
                                $this->closeObject();
                                $this->restoreState();
                            }
                            $this->ezNewPage();
                            // and the margins may have changed, this is due to the possibility of the columns being turned on
                            // as the columns are managed by manipulating the margins

                            $dm = $this->ez['leftMargin']-$baseLeftMargin;
                            foreach($basePos as $k=>$v){
                                $pos[$k]=$v+$dm;
                            }
//        $x0=$x0+$dm;
//        $x1=$x1+$dm;
                            $x0=$baseX0+$dm;
                            $x1=$baseX1+$dm;

                            if ($options['shaded']){
                                $this->saveState();
                                $textObjectId = $this->openObject();
                                $this->closeObject();
                                $this->addObject($textObjectId);
                                $this->reopenObject($textObjectId);
                            }
                            $this->setColor($options['textCol'][0],$options['textCol'][1],$options['textCol'][2],1);
                            $y = $this->ez['pageHeight']-$this->ez['topMargin'];
                            $y0=$y+$decender;
                            $mx=0;
                            if ($options['showHeadings']){
                                $this->ezPrvtTableColumnHeadings($cols,$pos,$maxWidth,$height,$decender,$options['rowGap'],$options['fontSize'],$y,$options);
                                $y1=$y;
                            } else {
                                $y1=$y0;
                            }
                            $firstLine=1;
                            $y -= $height;
                        }
                        $newRow=0;
                        // write the actual data
                        // if these cells need to be split over a page, then $newPage will be set, and the remaining
                        // text will be placed in $leftOvers
                        $newPage=0;
                        $leftOvers=array();

//                        foreach($cols as $colName=>$colTitle)
                        $realColumnCount = 0;

//                        echo '<br/>';
//                        print_r( $row );
//                        echo '<br/>';

                        for ( $columnCount = 0; $columnCount < count ( $row ); $columnCount++ )
                        {
                            // Get colSpan
                            if ( isset( $options['cellData'][$realColumnCount.','.$rowCount]['size'] ) )
                            {
                                $colSpan = $options['cellData'][$realColumnCount.','.$rowCount]['size'][0];
                            }
                            else
                            {
                                $colSpan = 1;
                            }

                            $this->ezSetY($y+$height);
                            $colNewPage=0;

//                            echo "$y, $height";
//                            echo '<br/>';

                            //TODO: figure out how to handle links
/*                                if (isset($options['cols'][$colName]) && isset($options['cols'][$colName]['link']) && strlen($options['cols'][$colName]['link'])){

                                    $lines = explode("\n",$row[$colName]);
                                    if (isset($row[$options['cols'][$colName]['link']]) && strlen($row[$options['cols'][$colName]['link']])){
                                        foreach($lines as $k=>$v){
                                            $lines[$k]='<c:alink:'.$row[$options['cols'][$colName]['link']].'>'.$v.'</c:alink>';
                                        }
                                    }
                                } else {*/

                            $lines = explode("\n",$row[$columnCount]);
                            $this->y -= $options['rowGap'];
                            foreach ($lines as $line){
                                $line = $this->ezProcessText($line);
                                $start=1;

                                while (strlen($line) || $start){
                                    $start=0;
                                    if (!$colNewPage){
                                        $this->y=$this->y-$height;
                                    }
                                    if ($this->y < $this->ez['bottomMargin']){
                                        //            $this->ezNewPage();
                                        $newPage=1;  // whether a new page is required for any of the columns
                                        $colNewPage=1; // whether a new page is required for this column
                                    }
                                    if ($colNewPage){
                                        if (isset($leftOvers[$realColumnCount])){
                                            $leftOvers[$realColumnCount].="\n".$line;
                                        } else {
                                            $leftOvers[$realColumnCount] = $line;
                                        }
                                        $line='';
                                    } else {
                                        if (isset($options['cols'][$realColumnCount]) && isset($options['cols'][$realColumnCount]['justification']) ){
                                            $just = $options['cols'][$realColumnCount]['justification'];
                                        } else {
                                            $just='left';
                                        }
                                        $line=$this->addTextWrap($pos[$realColumnCount],$this->y,$maxWidth[$colSpan][$realColumnCount],$options['fontSize'],$line,$just);
                                    }
                                }
                            }

                            $dy=$y+$height-$this->y+$options['rowGap'];
                            if ($dy-$height*$newPage>$mx)
                            {
                                $mx=$dy-$height*$newPage;
                            }

//                            echo $row[$columnCount].": $mx<br>";

                            // Get colSpan
/*                            if ( isset( $options['cellData'][$realColumnCount.','.$rowCount]['size'] ) )
                            {
                                $colSpan = $options['cellData'][$realColumnCount.','.$rowCount]['size'][0];
                            }*/

                            $realColumnCount += $colSpan;

                        } // End for ( ... count( $row ) ... )

                        // set $row to $leftOvers so that they will be processed onto the new page
                        $row = $leftOvers;
                        // now add the shading underneath
                        if ($options['shaded'] && $cnt%2==0){
                            $this->closeObject();
                            $this->setColor($options['shadeCol'][0],$options['shadeCol'][1],$options['shadeCol'][2],1);
                            $this->filledRectangle($x0-$options['gap']/2,$y+$decender+$height-$mx,$x1-$x0,$mx);
                            $this->reopenObject($textObjectId);
                        }

                        if ($options['shaded']==2 && $cnt%2==1){
                            $this->closeObject();
                            $this->setColor($options['shadeCol2'][0],$options['shadeCol2'][1],$options['shadeCol2'][2],1);
                            $this->filledRectangle($x0-$options['gap']/2,$y+$decender+$height-$mx,$x1-$x0,$mx);
                            $this->reopenObject($textObjectId);
                        }

                        // Draw lines for each row and above
                        $this->saveState();
                        $this->setStrokeColor($options['lineCol'][0],$options['lineCol'][1],$options['lineCol'][2],1);
                        if ( $options['showLines'] > 0 )
                        {
                            if ( $rowCount == 0 )
                            {
                                $this->line( $x0-$options['gap']/2, $y+$decender+$height, $x1-$options['gap']/2, $y+$decender+$height );
                            }
                            $this->line( $x0-$options['gap']/2, $y+$decender+$height, $x0-$options['gap']/2, $y+$decender+$height-$mx );
                            $this->line( $x1-$options['gap']/2, $y+$decender+$height, $x1-$options['gap']/2, $y+$decender+$height-$mx );

                            if ( $options['showLines'] > 1 )
                            {
                                // draw inner lines
                                $this->line( $x0-$options['gap']/2, $y+$decender+$height-$mx, $x1-$options['gap']/2, $y+$decender+$height-$mx );
                                for ( $posOffset = 0; $posOffset < count( $pos ) - 2; )
                                {
                                    $colSpan = 1;
                                    if ( isset( $options['cellData'][$posOffset.','.$rowCount]['size'] ) )
                                    {
                                        $colSpan = $options['cellData'][$posOffset.','.$rowCount]['size'][0];
                                    }
                                    $this->line( $pos[$posOffset+$colSpan]-$options['gap']/2, $y+$decender+$height,
                                                 $pos[$posOffset+$colSpan]-$options['gap']/2, $y+$decender+$height-$mx );
                                    $posOffset += $colSpan;
                                }
                            }
                            else if ( $rowCount == count( $data ) - 1 )
                            {
                                $this->line( $x0-$options['gap']/2, $y+$decender+$height-$mx, $x1-$options['gap']/2, $y+$decender+$height-$mx );
                            }
                        }
                        if ($options['showLines']>1){
                            // then draw a line on the top of each block
//        $this->closeObject();
                            $this->saveState();
                            $this->setStrokeColor($options['lineCol'][0],$options['lineCol'][1],$options['lineCol'][2],1);
//        $this->line($x0-$options['gap']/2,$y+$decender+$height-$mx,$x1-$x0,$mx);
                            if ($firstLine){
                                $this->setLineStyle($options['outerLineThickness']);
                                $firstLine=0;
                            } else {
                                $this->setLineStyle($options['innerLineThickness']);
                            }
                            $this->line($x0-$options['gap']/2,$y+$decender+$height,$x1-$options['gap']/2,$y+$decender+$height);
                            $this->restoreState();
//        $this->reopenObject($textObjectId);
                        }
                    } // end of while
                    $y=$y-$mx+$height;

                    // checking row split over pages
                    if ($options['splitRows']==0){
                        if ( ( ($this->ezPageCount != $pageStart) || (isset($this->ez['columns']) && $this->ez['columns']['on']==1 && $columnStart != $this->ez['columns']['colNum'] ))  && $secondTurn==0){
                            // then we need to go back and try that again !
                            $newPage=1;
                            $secondTurn=1;
                            $this->transaction('rewind');
                            $row = $row_orig;
                            $y = $y_orig;
                            $y0 = $y0_orig;
                            $y1 = $y1_orig;
                            $ok=0;

                            $dm = $this->ez['leftMargin']-$baseLeftMargin;
                            foreach($basePos as $k=>$v){
                                $pos[$k]=$v+$dm;
                            }
                            $x0=$baseX0+$dm;
                            $x1=$baseX1+$dm;

                        } else {
                            $this->transaction('commit');
                            $ok=1;
                        }
                    } else {
                        $ok=1;  // don't go round the loop if splitting rows is allowed
                    }

                }  // end of while to check for row splitting
                if ($abortTable){
                    if ($ok==0){
                        $this->transaction('abort');
                    }
                    // only the outer transaction should be operational
                    $this->transaction('rewind');
                    $this->ezNewPage();
                    break;
                }

            } // end of foreach ($data as $row)

        } // end of while ($abortTable)

        // table has been put on the page, the rows guarded as required, commit.
        $this->transaction('commit');

        $y2=$y+$decender;
        if ($options['showLines']){
            if (!$options['showHeadings']){
                $y0=$y1;
            }
//            $this->ezPrvtTableDrawLines($pos,$options['gap'],$x0,$x1,$y0,$y1,$y2,$options['lineCol'],$options['innerLineThickness'],$options['outerLineThickness'],$options['showLines']);
        }

        // close the object for drawing the text on top
        if ($options['shaded']){
            $this->closeObject();
            $this->restoreState();
        }

        $this->y=$y;
        return $y;
    }

    function ezPrvtTableDrawLines($pos,$gap,$x0,$x1,$y0,$y1,$y2,$col,$inner,$outer,$opt=1){
        $x0=1000;
        $x1=0;
        $this->setStrokeColor($col[0],$col[1],$col[2]);
        $cnt=0;
        $n = count($pos);
        foreach($pos as $x){
            $cnt++;
            if ($cnt==1 || $cnt==$n){
                $this->setLineStyle($outer);
            } else {
                $this->setLineStyle($inner);
            }
            $this->line($x-$gap/2,$y0,$x-$gap/2,$y2);
            if ($x>$x1){ $x1=$x; };
            if ($x<$x0){ $x0=$x; };
        }
        $this->setLineStyle($outer);
        $this->line($x0-$gap/2-$outer/2,$y0,$x1-$gap/2+$outer/2,$y0);
        // only do the second line if it is different to the first, AND each row does not have
        // a line on it.
        if ($y0!=$y1 && $opt<2){
            $this->line($x0-$gap/2,$y1,$x1-$gap/2,$y1);
        }
        $this->line($x0-$gap/2-$outer/2,$y2,$x1-$gap/2+$outer/2,$y2);
    }

}


?>
