<?php
//
// Definition of eZImageTextLayer class
//
// Created on: <03-Oct-2002 15:05:09 amos>
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
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
    */
    function processImage()
    {
        $destinationImageObject = $this->imageObjectInternal();
        $bbox = $this->textBoundingBox();
        $this->clear();
        $this->drawText( $this->font(), $this->textColor(), $this->text(), $bbox[6], -$bbox[7], $this->textAngle(),
                         $destinationImageObject );
        return true;
    }

    function mergeLayer( &$image, &$layerData, &$lastLayerData )
    {
        $position = $image->calculatePosition( $layerData['parameters'], $this->width(), $this->height() );
        $x = $position['x'];
        $y = $position['y'];
        $destinationImageObject =& $image->imageObjectInternal( false );
        if ( $lastLayerData === null and
             $destinationImageObject === null )
        {
            $destinationImageObject =& $image->imageObjectInternal();
            $bbox = $this->textBoundingBox();
            $this->drawText( $this->font(), $this->textColor(), $this->text(), $bbox[6], -$bbox[7], $this->textAngle(),
                             $destinationImageObject );
        }
        else
        {
            $destinationImageObject =& $image->imageObjectInternal();
            $imageObject =& $this->imageObjectInternal();
            $bbox = $this->textBoundingBox();
            $image->copyImage( $imageObject, $destinationImageObject,
                               0, 0, $this->width(), $this->height(),
                               $x, $y );
            $this->drawText( $this->font(), $this->textColor(), $this->text(), $bbox[6], -$bbox[7], $this->textAngle() );
            $image->mergeImage( $destinationImageObject, $imageObject,
                                $x, $y,
                                $this->width(), $this->height(), 0, 0,
                                $image->getTransparencyPercent( $layerData['parameters'] ) );
        }
    }

    function setText( $text )
    {
        $this->Text = $text;
    }

    function text()
    {
        return $this->Text;
    }

    function setTextAngle( $textAngle )
    {
        $this->TextAngle = $textAngle;
    }

    function textAngle()
    {
        return $this->TextAngle;
    }

    function textBoundingBox()
    {
        return $this->TextBoundingBox;
    }

    function &createForText( $text, &$font, $widthAdjustment, $heightAdjustment, $angle )
    {
        if ( get_class( $font ) != 'ezimagefont' )
            return false;
        if ( !function_exists( 'ImageTTFBBox' ) )
        {
            eZDebug::writeError( 'ImageTTFBBox function not in PHP, check PHP compilation', 'ezimagetextlayer.php' );
            return false;
        }
        $bbox = ImageTTFBBox( $font->pointSize(), $angle, $font->realFile(), $text );

        if ( !$bbox )
            return false;

        $width = $bbox[4] - $bbox[6];
        $height = $bbox[1] - $bbox[7];
        $width += $widthAdjustment;
        $height += $heightAdjustment;

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
