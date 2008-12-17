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

/*! \file eztemplateautoload.php
*/

//include_once( 'lib/ezpdf/classes/class.ezpdftable.php' );
//include_once( 'lib/ezpdf/classes/class.pdf.php' );

////include_once( 'lib/ezutils/classes/eztexttool.php' );

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
        $this->Config = eZINI::instance( 'pdf.ini' );
    }

    /*!
     Returns the template operators.
    */
    function operatorList()
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
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        $config = eZINI::instance( 'pdf.ini' );

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
                        $operatorValue .= ':cmyk:' . implode( ',', eZMath::rgbToCMYK2( $value[0]/255,
                                                                                       $value[1]/255,
                                                                                       $value[2]/255 ) );
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

                    if ( is_array( $tableSettings ) )
                    {
                        foreach( array_keys( $tableSettings ) as $key )
                        {
                            switch( $key )
                            {
                                case 'headerCMYK':
                                case 'cellCMYK':
                                case 'textCMYK':
                                case 'titleCellCMYK':
                                case 'titleTextCMYK':
                                {
                                    $operatorValue .= ':' . $key . ':' . implode( ',', $tableSettings[$key] );
                                } break;

                                default:
                                {
                                    $operatorValue .= ':' . $key . ':' . $tableSettings[$key];
                                } break;
                            }
                        }
                    }
                }

                $operatorValue .= '>';

                $rows = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                $rows = str_replace( array( ' ', "\t", "\r\n", "\n" ),
                                                          '',
                                                          $rows );
                //include_once( 'lib/ezi18n/classes/eztextcodec.php' );
                $httpCharset = eZTextCodec::internalCharset();
                $outputCharset = $config->hasVariable( 'PDFGeneral', 'OutputCharset' )
                                 ? $config->variable( 'PDFGeneral', 'OutputCharset' )
                                 : 'iso-8859-1';
                $codec = eZTextCodec::instance( $httpCharset, $outputCharset );
                // Convert current text to $outputCharset (by default iso-8859-1)
                $rows = $codec->convertString( $rows );

                $operatorValue .= urlencode( $rows );

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

            case 'new_line':
            case 'newline':  // Deprecated
            {
                $operatorValue = '<C:callNewLine>';
            } break;

            case 'new_page':
            case 'newpage':  // Deprecated
            {
                $operatorValue = '<C:callNewPage><C:callNewLine>';

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
                //include_once( 'lib/ezfile/classes/ezdir.php' );
                $filename = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                eZDir::mkdir( eZDir::dirpath( $filename ), false, true );

                require_once( 'kernel/classes/ezclusterfilehandler.php' );
                $file = eZClusterFileHandler::instance( $filename );
                $file->storeContents( $this->PDF->ezOutput(), 'viewcache', 'pdf' );

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
                //include_once( 'lib/ezi18n/classes/eztextcodec.php' );
                $httpCharset = eZTextCodec::internalCharset();
                $outputCharset = $config->hasVariable( 'PDFGeneral', 'OutputCharset' )
                                 ? $config->variable( 'PDFGeneral', 'OutputCharset' )
                                 : 'iso-8859-1';
                $codec = eZTextCodec::instance( $httpCharset, $outputCharset );
                // Convert current text to $outputCharset (by default iso-8859-1)
                $text = $codec->convertString( $text );

                $this->PDF->ezText( $text );
                eZDebug::writeNotice( 'Execute text in PDF, length: "'. strlen( $text ) .'"', 'eZPDF::modify' );
            } break;

            case 'page_number':
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

                $operatorValue .= ':thickness:' . ( isset( $lineDesc['thickness'] ) ? $lineDesc['thickness'] : '1' );

                $operatorValue .= '>';

                if ( $lineDesc['pages'] == 'all' )
                {
                    $operatorValue .= '___</ezGroup:callLine>';
                }

                return $operatorValue;
            } break;

            case 'footer_block':
            case 'header_block':
            {
                $frameDesc = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                $operatorValue = '<ezGroup:callBlockFrame';
                $operatorValue .= ':location:'. $namedParameters['operation'];
                $operatorValue .= '>';

                if ( isset( $frameDesc['block_code'] ) )
                {
                    //include_once( 'lib/ezi18n/classes/eztextcodec.php' );
                    $httpCharset = eZTextCodec::internalCharset();
                    $outputCharset = $config->hasVariable( 'PDFGeneral', 'OutputCharset' )
                                 ? $config->variable( 'PDFGeneral', 'OutputCharset' )
                                 : 'iso-8859-1';
                    $codec = eZTextCodec::instance( $httpCharset, $outputCharset );
                    // Convert current text to $outputCharset (by default iso-8859-1)
                    $frameDesc['block_code'] = $codec->convertString( $frameDesc['block_code'] );
                    $operatorValue .= urlencode( $frameDesc['block_code'] );
                }

                $operatorValue .= '</ezGroup:callBlockFrame>';

                eZDebug::writeNotice( 'PDF: Added Block '.$namedParameters['operation'] .': '.$operatorValue, 'eZPDF::modify' );
                return $operatorValue;

            } break;

            /* deprecated */
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
                    //include_once( 'lib/ezi18n/classes/eztextcodec.php' );
                    $httpCharset = eZTextCodec::internalCharset();
                    $outputCharset = $config->hasVariable( 'PDFGeneral', 'OutputCharset' )
                                 ? $config->variable( 'PDFGeneral', 'OutputCharset' )
                                 : 'iso-8859-1';
                    $codec = eZTextCodec::instance( $httpCharset, $outputCharset );
                    // Convert current text to $outputCharset (by default iso-8859-1)
                    $frameDesc['text'] = $codec->convertString( $frameDesc['text'] );
                    $operatorValue .= urlencode( $frameDesc['text'] );
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

                    $operatorValue .= ':thickness:';
                    if ( isset( $frameDesc['line']['thickness'] ) )
                    {
                        $operatorValue .= $frameDesc['line']['thickness'];
                    }
                    else
                    {
                        $operatorValue .= $this->Config->variable( $frameType, 'LineThickness' );
                    }
                    $operatorValue .= '>';
                }

                eZDebug::writeNotice( 'PDF: Added frame '.$frameType .': '.$operatorValue, 'eZPDF::modify' );
            } break;

            case 'frontpage':
            {
                $pageDesc = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                $align = isset( $pageDesc['align'] ) ? $pageDesc['align'] : 'center';
                $text = isset( $pageDesc['text'] ) ? $pageDesc['text'] : '';
                $top_margin = isset( $pageDesc['top_margin'] ) ? $pageDesc['top_margin'] : 100;

                $operatorValue = '<ezGroup:callFrontpage:justification:'. $align .':top_margin:'. $top_margin;

                if ( isset( $pageDesc['size'] ) )
                {
                    $operatorValue .= ':size:'. $pageDesc['size'];
                }

                $text = str_replace( array( ' ', "\t", "\r\n", "\n" ),
                                     '',
                                     $text );
                //include_once( 'lib/ezi18n/classes/eztextcodec.php' );
                $httpCharset = eZTextCodec::internalCharset();
                $outputCharset = $config->hasVariable( 'PDFGeneral', 'OutputCharset' )
                             ? $config->variable( 'PDFGeneral', 'OutputCharset' )
                             : 'iso-8859-1';
                $codec = eZTextCodec::instance( $httpCharset, $outputCharset );
                // Convert current text to $outputCharset (by default iso-8859-1)
                $text = $codec->convertString( $text );

                $operatorValue .= '>'. urlencode( $text ) .'</ezGroup:callFrontpage>';

                eZDebug::writeNotice( 'Added content to frontpage: '. $operatorValue, 'eZPDF::modify' );
            } break;

            /* usage: pdf(set_margin( hash( left, <left_margin>,
                                            right, <right_margin>,
                                            x, <x offset>,
                                            y, <y offset> )))
            */
            case 'set_margin':
            {
                //include_once( 'lib/ezutils/classes/ezmath.php' );
                $operatorValue = '<C:callSetMargin';
                $options = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                foreach( array_keys( $options ) as $key )
                {
                    $operatorValue .= ':' . $key . ':' . $options[$key];
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
                //include_once( 'lib/ezi18n/classes/eztextcodec.php' );
                $httpCharset = eZTextCodec::internalCharset();
                $outputCharset = $config->hasVariable( 'PDFGeneral', 'OutputCharset' )
                             ? $config->variable( 'PDFGeneral', 'OutputCharset' )
                             : 'iso-8859-1';
                $codec = eZTextCodec::instance( $httpCharset, $outputCharset );
                // Convert current text to $outputCharset (by default iso-8859-1)
                $text = $codec->convertString( $text );

                $operatorValue = '<C:callKeyword:'. rawurlencode( $text ) .'>';
            } break;

            /* add Keyword index to pdf document */
            case 'createIndex':
            case 'create_index':
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
                    $params['rgb'] = eZMath::normalizeColorArray( $params['rgb'] );
                    $params['cmyk'] = eZMath::rgbToCMYK2( $params['rgb'][0]/255,
                                                          $params['rgb'][1]/255,
                                                          $params['rgb'][2]/255 );
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
                     ':delta_left:' . ( $params['indent'] + $params['radius'] * 2 + $params['pre_indent'] ) .
                     '>';

                $operatorValue .= $text;

                $operatorValue .= '<C:callSetMargin' .
                     ':delta_left:' . -1 * ( $params['indent'] + $params['radius'] * 2 + $params['pre_indent'] ) .
                     '>';
            } break;

            case 'filled_circle':
            {
                //include_once( 'lib/ezutils/classes/ezmath.php' );
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
                    $options['rgb'] = eZMath::normalizeColorArray( $options['rgb'] );
                    $options['cmyk'] = eZMath::rgbToCMYK2( $options['rgb'][0]/255,
                                                           $options['rgb'][1]/255,
                                                           $options['rgb'][2]/255 );
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
                //include_once( 'lib/ezutils/classes/ezmath.php' );
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
                        $options['rgb'] = eZMath::normalizeColorArray( $options['rgb'] );
                        $operatorValue .= ':cmyk:' . implode( ',',  eZMath::rgbToCMYK2( $options['rgb'][0]/255,
                                                                                        $options['rgb'][1]/255,
                                                                                        $options['rgb'][2]/255 ) );
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
                //include_once( 'lib/ezutils/classes/ezmath.php' );
                $operatorValue = '';
                $options = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );

                if ( !isset( $options['pages'] ) )
                {
                    $options['pages'] = 'current';
                }

                if ( isset( $options['rgb'] ) )
                {
                    $options['rgb'] = eZMath::normalizeColorArray( $options['rgb'] );
                    $options['cmyk'] = eZMath::rgbToCMYK2( $options['rgb'][0]/255,
                                                           $options['rgb'][1]/255,
                                                           $options['rgb'][2]/255 );
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
                        $options['rgbTop'] = eZMath::normalizeColorArray( $options['rgbTop'] );
                        $options['cmykTop'] = eZMath::rgbToCMYK2( $options['rgbTop'][0]/255,
                                                                  $options['rgbTop'][1]/255,
                                                                  $options['rgbTop'][2]/255 );
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
                        $options['rgbBottom'] = eZMath::normalizeColorArray( $options['rgbBottom'] );
                        $options['cmykBottom'] = eZMath::rgbToCMYK2( $options['rgbBottom'][0]/255,
                                                                     $options['rgbBottom'][1]/255,
                                                                     $options['rgbBottom'][2]/255 );
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
                            $textSettings['cmyk'] = eZMath::rgbToCMYK2( $textSettings['rgb'][0]/255,
                                                                        $textSettings['rgb'][1]/255,
                                                                        $textSettings['rgb'][2]/255 );
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

                $operatorValue = '<ezGroup:callTextBox';

                foreach( array_keys( $parameters ) as $key )
                {
                    if ( $key != 'text' )
                    {
                        $operatorValue .= ':' . $key . ':' . urlencode( $parameters[$key] );
                    }
                }

                //include_once( 'lib/ezi18n/classes/eztextcodec.php' );
                $httpCharset = eZTextCodec::internalCharset();
                $outputCharset = $config->hasVariable( 'PDFGeneral', 'OutputCharset' )
                             ? $config->variable( 'PDFGeneral', 'OutputCharset' )
                             : 'iso-8859-1';
                $codec = eZTextCodec::instance( $httpCharset, $outputCharset );
                // Convert current text to $outputCharset (by default iso-8859-1)
                $parameters['text'] = $codec->convertString( $parameters['text'] );

                $operatorValue .= '>';
                $operatorValue .= urlencode( $parameters['text'] );
                $operatorValue .= '</ezGroup:callTextBox>';

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
                                $operatorValue .= ':frameCMYK:' . implode( ',', eZMath::rgbToCMYK2( $textSettings['frameRGB'][0]/255,
                                                                                                    $textSettings['frameRGB'][1]/255,
                                                                                                    $textSettings['frameRGB'][2]/255 ) );
                            }
                            else if ( $key == 'textCMYK' )
                            {
                                $operatorValue .= ':textCMYK:' . implode( ',', $textSettings['textCMYK'] );
                            }
                            else if ( $key == 'textRGB' )
                            {
                                $operatorValue .= ':textCMYK:' . implode( ',', eZMath::rgbToCMYK2( $textSettings['textRGB'][0]/255,
                                                                                                   $textSettings['textRGB'][1]/255,
                                                                                                   $textSettings['textRGB'][2]/255 ) );
                            }
                            else
                            {
                                $operatorValue .= ':' . $key . ':' . $textSettings[$key];
                            }
                        }

                        //include_once( 'lib/ezi18n/classes/eztextcodec.php' );
                        $httpCharset = eZTextCodec::internalCharset();
                        $outputCharset = $config->hasVariable( 'PDFGeneral', 'OutputCharset' )
                                     ? $config->variable( 'PDFGeneral', 'OutputCharset' )
                                     : 'iso-8859-1';
                        $codec = eZTextCodec::instance( $httpCharset, $outputCharset );
                        // Convert current text to $outputCharset (by default iso-8859-1)
                        $text = $codec->convertString( $text );

                        $operatorValue .= '>' . urlencode( $text ) . '</ezGroup::callTextFrame>';

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
    public $Operators;
    public $PDF;
    public $Config;
}


?>