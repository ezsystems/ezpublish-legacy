<?php
//
// Definition of eZImageTextLayer class
//
// Created on: <03-Oct-2002 15:05:09 amos>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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

/*! \file ezimagetextlayer.php
*/

/*!
  \class eZImageTextLayer ezimagetextlayer.php
  \ingroup eZImageObject
  \brief Layer for text information and rendering

*/

include_once( 'lib/ezimage/classes/ezimagelayer.php' );
include_once( 'lib/ezimage/classes/ezimagefont.php' );

class eZImageTextLayer extends eZImageLayer
{
    /*!
     Constructor
    */
    function eZImageTextLayer( $imageObjectRef = null, $imageObject = null,
                                  $width = false, $height = false,
                                  $font = false, $boundingBox = null, $text = null, $textAngle = 0 )
    {
        $this->eZImageLayer( $imageObjectRef, $imageObject, $width, $height, $font );
        $this->Text = $text;
        $this->TextAngle = $textAngle;
        $this->TextBoundingBox = $boundingBox;
    }

    /*!
     \virtual
     Draws the text on the current image object.
    */
    function processImage()
    {
        $destinationImageObject = $this->imageObjectInternal();
        $bbox = $this->textBoundingBox();
        $this->clear();
        $font = $this->font();
        $this->drawText( $font, $this->textColor(), $this->text(), $bbox[6], -$bbox[7], $this->textAngle(),
                         $destinationImageObject );
        return true;
    }

    /*!
     Renders the text with the other layer data. It will perform something
     that will look like alphablending of the text.

     It will copy the area which it will render on from the other layer
     and render on it and then merge the result back on the other layer
     using the transparency value. This means that the original image data
     is kept and the actual text will be transparent.
    */
    function mergeLayer( &$image, &$layerData, &$lastLayerData )
    {
        $position = $image->calculatePosition( $layerData['parameters'], $this->width(), $this->height() );
        $x = $position['x'];
        $y = $position['y'];
        $destinationImageObject = $image->imageObjectInternal( false );
        if ( $lastLayerData === null and
             $destinationImageObject === null )
        {
            $destinationImageObject = $image->imageObjectInternal();
            $bbox = $this->textBoundingBox();
            $font = $this->font();
            $this->drawText( $font, $this->textColor(), $this->text(), $bbox[6], -$bbox[7], $this->textAngle(),
                             $destinationImageObject );
        }
        else
        {
            $destinationImageObject = $image->imageObjectInternal();
            $imageObject = $this->imageObjectInternal();
            $bbox = $this->textBoundingBox();
            $image->copyImage( $imageObject, $destinationImageObject,
                               0, 0, $this->width(), $this->height(),
                               $x, $y );
            $font = $this->font();
            $this->drawText( $font, $this->textColor(), $this->text(), $bbox[6], -$bbox[7], $this->textAngle() );
            $image->mergeImage( $destinationImageObject, $imageObject,
                                $x, $y,
                                $this->width(), $this->height(), 0, 0,
                                $image->getTransparencyPercent( $layerData['parameters'] ) );
        }
    }

    /*!
     Sets the current text to \a $text.
    */
    function setText( $text )
    {
        $this->Text = $text;
    }

    /*!
     \return the current text.
    */
    function text()
    {
        return $this->Text;
    }

    /*!
     Sets the angle of the text to \a $textAngle.
    */
    function setTextAngle( $textAngle )
    {
        $this->TextAngle = $textAngle;
    }

    /*!
     \return the current text angle.
    */
    function textAngle()
    {
        return $this->TextAngle;
    }

    /*!
     \return the current bounding box for the text. See the PHP function ImageTTFBBox for more info.
    */
    function textBoundingBox()
    {
        return $this->TextBoundingBox;
    }

    /*!
     Creates a new text layer with the text \a $text, font \a $font and adjustment
     \a $widthAdjustment and \a $heightAdjustment at the angle \a $angle and returns it.
    */
    function &createForText( $text, &$font, $widthAdjustment, $heightAdjustment, $angle,
                             $absoluteWidth = false, $absoluteHeight = false )
    {
        $Return = false;
        if ( get_class( $font ) != 'ezimagefont' )
            return $Return;
        if ( !function_exists( 'ImageTTFBBox' ) )
        {
            eZDebug::writeError( 'ImageTTFBBox function not in PHP, check PHP compilation', 'ezimagetextlayer.php' );
            return $Return;
        }
        $bbox = ImageTTFBBox( $font->pointSize(), $angle, $font->realFile(), $text );

        if ( !$bbox )
            return $Return;

        $xmin = min( $bbox[0], $bbox[2], $bbox[4], $bbox[6] );
        $xmax = max( $bbox[0], $bbox[2], $bbox[4], $bbox[6] );
        $ymin = min( $bbox[1], $bbox[3], $bbox[5], $bbox[7] );
        $ymax = max( $bbox[1], $bbox[3], $bbox[5], $bbox[7] );

        $width = abs( $xmax - $xmin );
        $height = abs( $ymax - $ymin );
        $width += $widthAdjustment;
        $height += $heightAdjustment;

        if ( $absoluteWidth !== false )
            $width = $absoluteWidth;
        if ( $absoluteHeight !== false)
            $height = $absoluteHeight;

        $layer = new eZImageTextLayer( null, null, $width, $height,
                                       $font, $bbox, $text, $angle );
        $layer->create( $width, $height, null );
        return $layer;
    }

    /// \privatesection
    var $TextBoundingBox;
    var $Text;
    var $Angle;
}

?>
