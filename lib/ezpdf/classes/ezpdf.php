<?php
//
// Created on: <26-Aug-2003 15:15:32 kk>
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

/*! \file eztemplateautoload.php
*/

include_once( 'lib/ezpdf/classes/class.ezpdftable.php' );
include_once( 'lib/ezpdf/classes/class.pdf.php' );

//include_once( 'lib/ezutils/classes/eztexttool.php' );

/*!
  \defgroup eZPDF PDF generator library
*/

/*!
  \class eZPDF ezpdf.php
  \ingroup eZPDF
  \brief eZPDF provides template operators for dealing with pdf generation
*/

class eZPDF
{

    /*!
     Initializes the object with the name $name, default is "attribute".
    */
    function eZPDF( $name = "pdf" )
    {
        $this->Operators = array( $name );
        $this->Config =& eZINI::instance( 'pdf.ini' );
    }

    /*!
     Returns the template operators.
    */
    function &operatorList()
    {
        return $this->Operators;
    }

    /*!
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return array( 'operation' => array( 'type' => 'string',
                                            'required' => true,
                                            'default' => '' ) );
    }

    /*!
     Display the variable.
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        switch ( $namedParameters['operation'] )
        {
            case 'toc':
            {
                $operatorValue = '<C:callTOC';

                if ( count( $operatorParameters ) > 1 )
                {
                    $params = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                    $operatorValue .= isset( $params['size'] ) ? ':size:'. implode(',', $params['size'] ) : '';
                    $operatorValue .= isset( $params['dots'] ) ? ':dots:'. $params['dots'] : '';
                    $operatorValue .= isset( $params['contentText'] ) ? ':contentText:'. $params['contentText'] : '';
                    $operatorValue .= isset( $params['indent'] ) ? ':indent:'. implode(',', $params['indent'] ) : '';

                }

                $operatorValue .= '>';
                eZDebug::writeNotice( 'PDF: Generating TOC', 'eZPDF::modify' );
            } break;

            case 'set_font':
            {
                $params = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                $operatorValue = '<ezCall:callFont';

                foreach ( $params as $key => $value )
                {
                    if ( $key == 'colorCMYK' )
                    {
                        $operatorValue .= ':cmyk:' . implode( ',', $value );
                    }
                    else if ( $key == 'colorRGB' )
                    {
                        $operatorValue .= ':cmyk:' . implode( ',', eZMath::rgbToCMYK2( $value[0],
                                                                                       $value[1],
                                                                                       $value[2] ) );
                    }
                    else
                    {
                        $operatorValue .= ':' . $key . ':' . $value;
                    }
                }
                $operatorValue .= '>';

                eZDebug::writeNotice( 'PDF: Changed font.' );
            } break;

            case 'table':
            {
                $operatorValue = '<ezGroup:callTable';

                if ( count( $operatorParameters > 2 ) )
                {
                    $tableSettings = $tpl->elementValue( $operatorParameters[2], $rootNamespace, $currentNamespace );

                    if ( isset( $tableSettings['showLines'] ) )
                    {
                        $operatorValue .= ':showLines:'. $tableSettings['showLines'];
                    }
                    if ( isset( $tableSettings['cellSpacing'] ) )
                    {
                        $operatorValue .= ':cellSpacing:' . $tableSettings['cellSpacing'];
                    }
                    if ( isset( $tableSettings['headerCMYK'] ) )
                    {
                        $operatorValue .= ':headerCMYK:' . implode( ',', $tableSettings['headerCMYK'] );
                    }
                    if ( isset( $tableSettings['cellCMYK'] ) )
                    {
                        $operatorValue .= ':cellCMYK:' . implode( ',', $tableSettings['cellCMYK'] );
                    }
                    if ( isset( $tableSettings['textCMYK'] ) )
                    {
                        $operatorValue .= ':textCMYK:' . implode( ',', $tableSettings['textCMYK'] );
                    }
                    if( isset( $tableSettings['rowGap'] ) )
                    {
                        $operatorValue .= ':rowGap:' . $tableSettings['rowGap'];
                    }
                    if( isset( $tableSettings['colGap'] ) )
                    {
                        $operatorValue .= ':colGap:' . $tableSettings['colGap'];
                    }
                    if ( isset( $tableSettings['cellPadding'] ) )
                    {
                        $operatorValue .= ':cellPadding:' . $tableSettings['cellPadding'];
                    }
                    if( isset( $tableSettings['firstRowTitle'] ) )
                    {
                        $operatorValue .= ':firstRowTitle:' . $tableSettings['firstRowTitle'];
                    }
                    if( isset( $tableSettings['titleFontSize'] ) )
                    {
                        $operatorValue .= ':titleFontSize:' . $tableSettings['titleFontSize'];
                    }
                    if( isset( $tableSettings['titleCellCMYK'] ) )
                    {
                        $operatorValue .= ':titleCellCMYK:' . implode( ',', $tableSettings['titleCellCMYK'] );
                    }
                    if( isset( $tableSettings['titleTextCMYK'] ) )
                    {
                        $operatorValue .= ':titleTextCMYK:' . implode( ',', $tableSettings['titleTextCMYK'] );
                    }
                    if( isset ( $tableSettings['width'] ) )
                    {
                        $operatorValue .= ':width:' . $tableSettings['width'];
                    }
                    if ( isset( $tableSettings['yBottom'] ) )
                    {
                        $operatorValue .= ':yBottom:' . $tableSettings['yBottom'];
                    }
                }

                $operatorValue .= '>';

                $rows = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                $operatorValue .= str_replace( array( ' ', "\t", "\r\n", "\n" ),
                                               '',
                                               $rows );

                $operatorValue .= '</ezGroup:callTable><C:callNewLine>';

                eZDebug::writeNotice( 'PDF: Added table to PDF',
                                      'eZPDF::modify' );
            } break;

            case 'header':
            {
                $header = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                $header['text'] = str_replace( array( ' ', "\t", "\r\n", "\n" ),
                                               '',
                                               $header['text'] );

                $operatorValue = '<ezCall:callHeader:level:'. $header['level'] .':size:'. $header['size'];

                if ( isset( $header['align'] ) )
                {
                    $operatorValue .= ':justification:'. $header['align'];
                }

                if ( isset( $header['font'] ) )
                {
                    $operatorValue .= ':fontName:'. $header['font'];
                }

                $operatorValue .= ':label:'. rawurlencode( $header['text'] );

                $operatorValue .= '><C:callNewLine>'. $header['text'] .'</ezCall:callHeader><C:callNewLine>';

                eZDebug::writeNotice( 'PDF: Added header: '. $header['text'] .', size: '. $header['size'] .
                                      ', align: '. $header['align'] .', level: '. $header['level'],
                                      'eZPDF::modify' );
            } break;

            case 'create':
            {
                $this->createPDF();
            } break;

            case 'newline':
            {
                $operatorValue = '<C:callNewLine>';
            } break;

            case 'newpage':
            {
                $operatorValue = '<C:callNewPage>';

                eZDebug::writeNotice( 'PDF: New page', 'eZPDF::modify' );
            } break;

            case 'image':
            {
                $image = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                $width = isset( $image['width'] ) ? $image['width']: 100;
                $height = isset( $image['height'] ) ? $image['height']: 100;

                $operatorValue = '<C:callImage:src:'. rawurlencode( $image['src'] ) .':width:'. $width .':height:'. $height;

                if ( isset( $image['static'] ) )
                {
                    $operatorValue .= ':static:' . $image['static'];
                }

                if ( isset ( $image['x'] ) )
                {
                    $operatorValue .= ':x:' . $image['x'];
                }

                if ( isset( $image['y'] ) )
                {
                    $operatorValue .= ':y:' . $image['y'];
                }

                if ( isset( $image['dpi'] ) )
                {
                    $operatorValue .= ':dpi:' . $image['dpi'];
                }

                if ( isset( $image['align'] ) ) // left, right, center, full
                {
                    $operatorValue .= ':align:' . $image['align'];
                }

                $operatorValue .= '>';

                eZDebug::writeNotice( 'PDF: Added Image '.$image['src'].' to PDF file', 'eZPDF::modify' );
            } break;

            case 'anchor':
            {
                $name = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                $operatorValue = '<C:callAnchor:'. $name['name'] .':FitH:>';
                eZDebug::writeNotice( 'PDF: Added anchor: '.$name['name'], 'eZPDF::modify' );
            } break;

            case 'link': // external link
            {
                $link = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                $link['text'] = str_replace( '&quot;',
                                             '"',
                                             $link['text'] );

                $operatorValue = '<c:alink:'. rawurlencode( $link['url'] ) .'>'. $link['text'] .'</c:alink>';
                eZDebug::writeNotice( 'PDF: Added link: '. $link['text'] .', url: '.$link['url'], 'eZPDF::modify' );
            } break;

            case 'stream':
            {
                $this->PDF->ezStream();
            }

            case 'close':
            {
                include_once( 'lib/ezfile/classes/ezfile.php' );
                include_once( 'lib/ezfile/classes/ezdir.php' );
                $filename = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                eZDir::mkdir( eZDir::dirpath( $filename ), false, true );

                eZFile::create( $filename, false, $this->PDF->ezOutput() );

                eZDebug::writeNotice( 'PDF file closed and saved to '. $filename, 'eZPDF::modify' );
            } break;

            case 'strike':
            {
                $text = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );
                $operatorValue = '<c:strike>'. $text .'</c:strike>';
                eZDebug::writeNotice( 'Striked text added to PDF: "'. $text .'"', 'eZPDF::modify' );
            } break;

            /* usage : execute/add text to pdf file, pdf(execute,<text>) */
            case 'execute':
            {
                $config =& eZINI::instance( 'pdf.ini' );

                $text = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                if ( count ( $operatorParameters ) > 2 )
                {
                    $options = $tpl->elementValue( $operatorParameters[2], $rootNamespace, $currentNamespace );

                    $size = isset( $options['size'] ) ? $options['size'] : $config->variable( 'PDFGeneral', 'Format' );
                    $orientation = isset( $options['orientation'] ) ? $options['orientation'] : $config->variable( 'PDFGeneral', 'Orientation' );

                    $this->createPDF( $size, $orientation );
                }
                else
                {
                    $this->createPDF( $config->variable( 'PDFGeneral', 'Format' ), $config->variable( 'PDFGeneral', 'Orientation' ) );
                }

                $text = str_replace( array( ' ', "\n", "\t" ), '', $text );

                $this->PDF->ezText( $text );
                eZDebug::writeNotice( 'Execute text in PDF, length: "'. strlen( $text ) .'"', 'eZPDF::modify' );
            } break;

            case 'pageNumber':
            {
                $numberDesc = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                if ( isset( $numberDesc['identifier'] ) )
                {
                    $identifier = $numberDesc['identifier'];
                }
                else
                {
                    $identifier = 'main';
                }

                if ( isset( $numberDesc['start'] ) )
                {
                    $operatorValue = '<C:callStartPageCounter:start:'. $numberDesc['start'] .':identifier:'. $identifier .'>';
                }
                else if ( isset( $numberDesc['stop'] ) )
                {
                    $operatorValue = '<C:callStartPageCounter:stop:1:identifier:'. $identifier .'>';
                }
            } break;

            /* usage {pdf( line, hash( x1, <x>, y1, <y>, x2, <x2>, y2, <y2>, pages, <all|current>, thickness, <1..100>,  ) )} */
            case 'line':
            {
                $lineDesc = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                if ( isset( $lineDesc['pages']) and
                     $lineDesc['pages'] == 'all' )
                {
                    $operatorValue = '<ezGroup:callLine';
                }
                else
                {
                    $operatorValue = '<C:callDrawLine';
                }

                $operatorValue .= ':x1:' . $lineDesc['x1'];
                $operatorValue .= ':x2:' . $lineDesc['x2'];
                $operatorValue .= ':y1:' . $lineDesc['y1'];
                $operatorValue .= ':y2:' . $lineDesc['y2'];

                $operatorValue .= ':thinkness:' . ( isset( $lineDesc['thickness'] ) ? $lineDesc['thickness'] : '1' );

                $operatorValue .= '>';

                if ( $lineDesc == 'all' )
                {
                    $operatorValue .= '___</ezGroup:callLine>';
                }

                return $operatorValue;
            } break;

            case 'footer':
            case 'frame_header':
            {
                $frameDesc = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                $operatorValue  = '<ezGroup:callFrame';
                $operatorValue .= ':location:'. $namedParameters['operation'];

                if ( $namedParameters['operation'] == 'footer' )
                {
                    $frameType = 'Footer';
                }
                else if( $namedParameters['operation'] == 'frame_header' )
                {
                    $frameType = 'Header';
                }

                if ( isset( $frameDesc['align'] ) )
                {
                    $operatorValue .= ':justification:'. $frameDesc['align'];
                }

                if ( isset( $frameDesc['page'] ) )
                {
                    $operatorValue .= ':page:'. $frameDesc['page'];
                }
                else
                {
                    $operatorValue .= ':page:all';
                }

                $operatorValue .= ':newline:' . ( isset( $frameDesc['newline'] ) ? $frameDesc['newline'] : 0 );

                $operatorValue .= ':pageOffset:';
                if ( isset( $frameDesc['pageOffset'] ) )
                {
                    $operatorValue .= $frameDesc['pageOffset'];
                }
                else
                {
                    $operatorValue .= $this->Config->variable( $frameType, 'PageOffset' );
                }

                if ( isset( $frameDesc['size'] ) )
                {
                    $operatorValue .= ':size:'. $frameDesc['size'];
                }

                if ( isset( $frameDesc['font'] ) )
                {
                    $operatorValue .= ':font:'. $frameDesc['font'];
                }

                $operatorValue .= '>';

                if ( isset( $frameDesc['text'] ) )
                {
                    $operatorValue .= $frameDesc['text'];
                }

                $operatorValue .= '</ezGroup:callFrame>';

                if ( isset( $frameDesc['margin'] ) )
                {
                    $operatorValue .= '<C:callFrameMargins';

                    $operatorValue .= ':identifier:'. $namedParameters['operation'];

                    $operatorValue .= ':topMargin:';
                    if ( isset( $frameDesc['margin']['top'] ) )
                    {
                        $operatorValue .= $frameDesc['margin']['top'];
                    }
                    else
                    {
                        $operatorValue .= $this->Config->variable( $frameType, 'TopMargin' );
                    }

                    $operatorValue .= ':bottomMargin:';
                    if ( isset( $frameDesc['margin']['bottom'] ) )
                    {
                        $operatorValue .= $frameDesc['margin']['bottom'];
                    }
                    else
                    {
                        $operatorValue .= $this->Config->variable( $frameType, 'BottomMargin' );
                    }

                    $operatorValue .= ':leftMargin:';
                    if ( isset( $frameDesc['margin']['left'] ) )
                    {
                        $operatorValue .= $frameDesc['margin']['left'];
                    }
                    else
                    {
                        $operatorValue .= $this->Config->variable( $frameType, 'LeftMargin' );
                    }

                    $operatorValue .= ':rightMargin:';
                    if ( isset( $frameDesc['margin']['right'] ) )
                    {
                        $operatorValue .= $frameDesc['margin']['right'];
                    }
                    else
                    {
                        $operatorValue .= $this->Config->variable( $frameType, 'RightMargin' );
                    }

                    $operatorValue .= ':height:';
                    if ( isset( $frameDesc['margin']['height'] ) )
                    {
                        $operatorValue .= $frameDesc['margin']['height'];
                    }
                    else
                    {
                        $operatorValue .= $this->Config->variable( $frameType, 'Height' );
                    }

                    $operatorValue .= '>';
                }

                if ( isset( $frameDesc['line'] ) )
                {
                    $operatorValue .= '<C:callFrameLine';
                    $operatorValue .= ':location:'. $namedParameters['operation'];

                    $operatorValue .= ':margin:';
                    if( isset( $frameDesc['line']['margin'] ) )
                    {
                        $operatorValue .= $frameDesc['line']['margin'];
                    }
                    else
                    {
                        $operatorValue .= $this->Config->variable( $frameType, 'LineMargin' );
                    }

                    if ( isset( $frameDesc['line']['leftMargin'] ) )
                    {
                        $operatorValue .= ':leftMargin:'. $frameDesc['line']['leftMargin'];
                    }
                    if ( isset( $frameDesc['line']['rightMargin'] ) )
                    {
                        $operatorValue .= ':rightMargin:'. $frameDesc['line']['rightMargin'];
                    }

                    $operatorValue .= ':pageOffset:';
                    if ( isset( $frameDesc['line']['pageOffset'] ) )
                    {
                        $operatorValue .= $frameDesc['line']['pageOffset'];
                    }
                    else
                    {
                        $operatorValue .= $this->Config->variable( $frameType, 'PageOffset' );
                    }

                    $operatorValue .= ':page:';
                    if ( isset( $frameDesc['line']['page'] ) )
                    {
                        $operatorValue .= $frameDesc['line']['page'];
                    }
                    else
                    {
                        $operatorValue .= $this->Config->variable( $frameType, 'Page' );
                    }

                    $operatorValue .= ':thicknes:';
                    if ( isset( $frameDesc['line']['thicknes'] ) )
                    {
                        $operatorValue .= $frameDesc['line']['thicknes'];
                    }
                    else
                    {
                        $operatorValue .= $this->Config->variable( $frameType, 'LineThicknes' );
                    }

                    $operatorValue .= '>';
                }

                eZDebug::writeNotice( 'PDF: Added frame '.$frameType .': '.$operatorValue, 'eZPDF::modify' );
            } break;

            case 'frontpage':
            {
                $pageDesc = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                $align = isset( $pageDesc['align'] ) ? $pageDesc['align'] : 'center';
                $text = isset( $pageDesc['align'] ) ? $pageDesc['text'] : '';
                $top_margin = isset( $pageDesc['top_margin'] ) ? $pageDesc['top_margin'] : 100;

                $operatorValue = '<ezGroup:callFrontpage:justification:'. $align .':top_margin:'. $top_margin;

                if ( isset( $pageDesc['size'] ) )
                {
                    $operatorValue .= ':size:'. $pageDesc['size'];
                }

                $text = str_replace( array( ' ', "\t", "\r\n", "\n" ),
                                     '',
                                     $text );

                $operatorValue .= '>'. $text .'</ezGroup:callFrontpage>';

                eZDebug::writeNotice( 'Added content to frontpage: '. $operatorValue, 'eZPDF::modify' );
            } break;

            /* usage: pdf(set_margin( hash( left, <left_margin>,
                                            right, <right_margin>,
                                            x, <x offset>,
                                            y, <y offset> )))
            */
            case 'set_margin':
            {
                include_once( 'lib/ezutils/classes/ezmath.php' );
                $operatorValue = '<C:callSetMargin';
                $options = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                if ( isset( $options['left'] ) )
                {
                    $operatorValue .= ':left:' . $options['left'];
                }
                if ( isset( $options['right'] ) )
                {
                    $operatorValue .= ':right:' . $options['right'];
                }
                if ( isset( $options['x'] ) )
                {
                    $operatorValue .= ':x:' . $options['x'];
                }
                if ( isset( $options['y'] ) )
                {
                    $operatorValue .= ':y:' . $options['y'];
                }
                if ( isset( $options['bottom'] ) )
                {
                    $operatorValue .= ':bottom:' . $options['bottom'];
                }
                if ( isset( $options['top'] ) )
                {
                    $operatorValue .= ':top:' . $options['top'];
                }


                $operatorValue .= '>';

                eZDebug::writeNotice( 'Added new margin/offset setup: ' . $operatorValue );

                return $operatorValue;
            } break;

            /* add keyword to pdf document */
            case 'keyword':
            {
                $text = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                $text = str_replace( array( ' ', "\n", "\t" ), '', $text );

                $operatorValue = '<C:callKeyword:'. rawurlencode( $text ) .'>';
            } break;

            /* add Keyword index to pdf document */
            case 'createIndex':
            {
                $operatorValue = '<C:callIndex>';

                eZDebug::writeNotice( 'Adding Keyword index to PDF', 'eZPDF::modify' );
            } break;

            case 'ul':
            {
                $text = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );
                if ( count( $operatorParameters ) > 2 )
                {
                    $params = $tpl->elementValue( $operatorParameters[2], $rootNamespace, $currentNamespace );
                }
                else
                {
                    $params = array();
                }

                if ( isset( $params['rgb'] ) )
                {
                    eZMath::normalizeColorArray( $params['rgb'] );
                    $params['cmyk'] = eZMath::rgbToCMYK2( $params['rgb'][0],
                                                          $params['rgb'][1],
                                                          $params['rgb'][2] );
                }

                if ( !isset( $params['cmyk'] ) )
                {
                    $params['cmyk'] = eZMath::rgbToCMYK2( 0, 0, 0 );
                }
                if ( !isset( $params['radius'] ) )
                {
                    $params['radius'] = 2;
                }
                if ( !isset ( $params['pre_indent'] ) )
                {
                    $params['pre_indent'] = 0;
                }
                if ( !isset ( $params['indent'] ) )
                {
                    $params['indent'] = 2;
                }
                if ( !isset ( $params['yOffset'] ) )
                {
                    $params['yOffset'] = -1;
                }

                $operatorValue = '<C:callCircle' .
                     ':pages:current' .
                     ':x:-1' .
                     ':yOffset:' . $params['yOffset'] .
                     ':y:-1' .
                     ':indent:' . $params['indent'] .
                     ':pre_indent:' . $params['pre_indent'] .
                     ':radius:' . $params['radius'] .
                     ':cmyk:' . implode( ',', $params['cmyk'] ) .
                     '>';

                $operatorValue .= '<C:callSetMargin' .
                     ':delta_left:' . ( $params['indent'] + $params['radius'] * 2 + $params['pre_indent'] ) . ':' .
                     '>';
                $operatorValue .= $text;
                $operatorValue .= '<C:callSetMargin' .
                     ':delta_left:' . -1 * ( $params['indent'] + $params['radius'] * 2 + $params['pre_indent'] ) . ':' .
                     '>';
            } break;

            case 'filled_circle':
            {
                include_once( 'lib/ezutils/classes/ezmath.php' );
                $operatorValue = '';
                $options = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                if ( !isset( $options['pages'] ) )
                {
                    $options['pages'] = 'current';
                }

                if ( !isset( $options['x'] ) )
                {
                    $options['x'] = -1;
                }

                if ( !isset( $options['y'] ) )
                {
                    $options['y'] = -1;
                }

                if ( isset( $options['rgb'] ) )
                {
                    eZMath::normalizeColorArray( $options['rgb'] );
                    $options['cmyk'] = eZMath::rgbToCMYK2( $options['rgb'][0],
                                                           $options['rgb'][1],
                                                           $options['rgb'][2] );
                }

                $operatorValue = '<C:callCircle' .
                     ':pages:' . $options['pages'] .
                     ':x:' . $options['x'] .
                     ':y:' . $options['y'] .
                     ':radius:' . $options['radius'];

                if ( isset( $options['cmyk'] ) )
                {
                    $operatorValue .= ':cmyk:' . implode( ',', $options['cmyk'] );
                }

                $operatorValue .= '>';

                eZDebug::writeNotice( 'PDF Added circle: ' . $operatorValue );

                return $operatorValue;
            } break;

            case 'rectangle':
            {
                include_once( 'lib/ezutils/classes/ezmath.php' );
                $operatorValue = '';
                $options = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                if ( !isset( $options['pages'] ) )
                {
                    $options['pages'] = 'current';
                }
                if ( !isset( $options['line_width'] ) )
                {
                    $options['line_width'] = 1;
                }
                if ( !isset( $options['round_corner'] ) )
                {
                    $options['round_corner'] = false;
                }

                $operatorValue = '<C:callRectangle';
                foreach ( $options as $key => $value )
                {
                    if ( $key == 'rgb' )
                    {
                        eZMath::normalizeColorArray( $options['rgb'] );
                        $operatorValue .= ':cmyk:' . implode( ',',  eZMath::rgbToCMYK2( $options['rgb'][0],
                                                                                        $options['rgb'][1],
                                                                                        $options['rgb'][2] ) );
                    }
                    else if ( $key == 'cmyk' )
                    {
                        $operatorValue .= ':cmyk:' . implode( ',', $value );
                    }
                    else
                    {
                        $operatorValue .= ':' . $key . ':' . $value;
                    }
                }
                $operatorValue .= '>';

                eZDebug::writeNotice( 'PDF Added rectangle: ' . $operatorValue );

                return $operatorValue;
            } break;

            /* usage: pdf( filled_rectangle, hash( 'x', <x offset>, 'y' => <y offset>, 'width' => <width>, 'height' => <height>,
                                                    'pages', <'all'|'current'|odd|even>, (supported, current)
                                                    'rgb', array( <r>, <g>, <b> ),
                                                    'cmyk', array( <c>, <m>, <y>, <k> ),
                                                    'rgbTop', array( <r>, <b>, <g> ),
                                                    'rgbBottom', array( <r>, <b>, <g> ),
                                                    'cmykTop', array( <c>, <m>, <y>, <k> ),
                                                    'cmykBottom', array( <c>, <m>, <y>, <k> ) ) ) */
            case 'filled_rectangle':
            {
                include_once( 'lib/ezutils/classes/ezmath.php' );
                $operatorValue = '';
                $options = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                if ( !isset( $options['pages'] ) )
                {
                    $options['pages'] = 'current';
                }

                if ( isset( $options['rgb'] ) )
                {
                    eZMath::normalizeColorArray( $options['rgb'] );
                    $options['cmyk'] = eZMath::rgbToCMYK2( $options['rgb'][0],
                                                           $options['rgb'][1],
                                                           $options['rgb'][2] );
                }

                if ( isset( $options['cmyk'] ) )
                {
                    $options['cmykTop'] = $options['cmyk'];
                    $options['cmykBottom'] = $options['cmyk'];
                }

                if ( !isset( $options['cmykTop'] ) )
                {
                    if ( isset( $options['rgbTop'] ) )
                    {
                        eZMath::normalizeColorArray( $options['rgbTop'] );
                        $options['cmykTop'] = eZMath::rgbToCMYK2( $options['rgbTop'][0],
                                                                  $options['rgbTop'][1],
                                                                  $options['rgbTop'][2] );
                    }
                    else
                    {
                        $options['cmykTop'] = eZMath::rgbToCMYK2( 0, 0, 0 );
                    }
                }

                if ( !isset( $options['cmykBottom'] ) )
                {
                    if ( isset( $options['rgbBottom'] ) )
                    {
                        eZMath::normalizeColorArray( $options['rgbBottom'] );
                        $options['cmykBottom'] = eZMath::rgbToCMYK2( $options['rgbBottom'][0],
                                                                     $options['rgbBottom'][1],
                                                                     $options['rgbBottom'][2] );
                    }
                    else
                    {
                        $options['cmykBottom'] = eZMath::rgbToCMYK2( 0, 0, 0 );
                    }
                }

                if ( !isset( $options['pages'] ) )
                {
                    $options['pages'] = 'current';
                }

                $operatorValue = '<C:callFilledRectangle' .
                     ':pages:' . $options['pages'] .
                     ':x:' . $options['x'] .
                     ':y:' . $options['y'] .
                     ':width:' . $options['width'] .
                     ':height:' . $options['height'] .
                     ':cmykTop:' . implode( ',', $options['cmykTop'] ) .
                     ':cmykBottom:' . implode( ',', $options['cmykBottom'] ) .
                     '>';

                eZDebug::writeNotice( 'Added rectangle: ' . $operatorValue );
            } break;

            /* usage : pdf(text, <text>, array( 'font' => <fontname>, 'size' => <fontsize> )) */
            case 'text':
            {
                $text = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                $operatorValue = '';
                $changeFont = false;

                if ( count( $operatorParameters ) >= 3)
                {
                    $textSettings = $tpl->elementValue( $operatorParameters[2], $rootNamespace, $currentNamespace );

                    if ( isset( $textSettings ) )
                    {
                        $operatorValue .= '<ezCall:callText';
                        $changeFont = true;

                        if ( isset( $textSettings['font'] ) )
                        {
                            $operatorValue .= ':font:'. $textSettings['font'];
                        }

                        if ( isset( $textSettings['size'] ) )
                        {
                            $operatorValue .= ':size:'. $textSettings['size'];
                        }

                        if ( isset( $textSettings['align'] ) )
                        {
                            $operatorValue .= ':justification:'. $textSettings['align'];
                        }

                        if ( isset( $textSettings['rgb'] ) )
                        {
                            $textSettings['cmyk'] = eZMath::rgbToCMYK2( $textSettings['rgb'][0],
                                                                        $textSettings['rgb'][1],
                                                                        $textSettings['rgb'][2] );
                        }

                        if ( isset( $textSettings['cmyk'] ) )
                        {
                            $operatorValue .= ':cmyk:' . implode( ',', $textSettings['cmyk'] );
                        }

                        $operatorValue .= '>';
                    }
                }

                $operatorValue .= $text;
                if ( $changeFont )
                {
                    $operatorValue .= '</ezCall:callText>';
                }

            } break;

            case 'text_box':
            {
                $parameters = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                $operatorValue = '<C:callTextBox';

                foreach( array_keys( $parameters ) as $key )
                {
                    $operatorValue .= ':' . $key . ':' . urlencode( $parameters[$key] );
                }

                $operatorValue .= '>';

                return $operatorValue;
            } break;

            case 'text_frame':
            {
                $text = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                $operatorValue = '';
                $changeFont = false;

                if ( count( $operatorParameters ) >= 3)
                {
                    $textSettings = $tpl->elementValue( $operatorParameters[2], $rootNamespace, $currentNamespace );

                    if ( isset( $textSettings ) )
                    {
                        $operatorValue .= '<ezGroup:callTextFrame';
                        $changeFont = true;

                        foreach ( array_keys( $textSettings ) as $key ) //settings, padding (left, right, top, bottom), textcmyk, framecmyk
                        {
                            if ( $key == 'frameCMYK' )
                            {
                                $operatorValue .= ':frameCMYK:' . implode( ',', $textSettings['frameCMYK'] );
                            }
                            else if ( $key == 'frameRGB' )
                            {
                                $operatorValue .= ':frameCMYK:' . implode( ',', eZMath::rgbToCMYK2( $textSettings['frameRGB'][0],
                                                                                                    $textSettings['frameRGB'][1],
                                                                                                    $textSettings['frameRGB'][2] ) );
                            }
                            else if ( $key == 'textCMYK' )
                            {
                                $operatorValue .= ':textCMYK:' . implode( ',', $textSettings['textCMYK'] );
                            }
                            else if ( $key == 'textRGB' )
                            {
                                $operatorValue .= ':textCMYK:' . implode( ',', eZMath::rgbToCMYK2( $textSettings['textRGB'][0],
                                                                                                   $textSettings['textRGB'][1],
                                                                                                   $textSettings['textRGB'][2] ) );
                            }
                            else
                            {
                                $operatorValue .= ':' . $key . ':' . $textSettings[$key];
                            }
                        }

                        $operatorValue .= '>' . $text . '</ezGroup::callTextFrame>';

                    }
                }

                eZDebug::writeNotice( 'Added TextFrame: ' . $operatorValue );
            } break;

            default:
            {
                eZDebug::writeError( 'PDF operation "'. $namedParameters['operation'] .'" undefined', 'eZPDF::modify' );
            }

        }

    }

    /*
     \private
     Create PDF object
    */
    function createPDF( $paper = 'a4', $orientation = 'portrait' )
    {
        $this->PDF = new eZPDFTable( $paper, $orientation );
        $this->PDF->selectFont( 'lib/ezpdf/classes/fonts/Helvetica' );
        eZDebug::writeNotice( 'PDF: File created' );
    }

    /// The array of operators, used for registering operators
    var $Operators;
    var $PDF;
    var $Config;
}


?>
