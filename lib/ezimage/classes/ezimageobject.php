<?php
//
// Definition of eZImageObject class
//
// Created on: <03-Oct-2002 15:05:09 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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
*/

/*!
  \class eZImageObject ezimageobject.php
  \ingroup eZImageObject
  \brief Image object which handles image layers

*/

class eZImageObject extends eZImageInterface
{
    /// Alignment values @{
    const ALIGN_AXIS_NONE = 0x00;
    const ALIGN_AXIS_START = 0x01;
    const ALIGN_AXIS_STOP = 0x02;
    const ALIGN_AXIS_CENTER = 0x03; // ALIGN_AXIS_START | ALIGN_AXIS_STOP
    const ALIGN_AXIS_MASK = 0x03; // ALIGN_AXIS_START | ALIGN_AXIS_STOP
    ///@}

    /// Placement types @{
    /// Places the layer absolutely from on the axis.
    const PLACE_CONSTANT = 1;
    /// Places the layer relative to the axis size.
    const PLACE_RELATIVE = 2;
    ///@}

    function eZImageObject( $imageObjectRef = null, $imageObject = null, $width = false, $height = false )
    {
        $this->eZImageInterface( $imageObjectRef, $imageObject, $width, $height );
        $this->TemplateURI = 'design:image/imageobject.tpl';
        $this->ImageLayers = array();
        $this->ImageLayerCounter = 0;
    }

    /*!
     A definition which tells the template engine which template to use
     for displaying the image.
    */
    function templateData()
    {
        return array( 'type' => 'template',
                      'template_variable_name' => 'image',
                      'uri' => $this->TemplateURI );
    }

    /*!
     Sets the URI of the template to use for displaying it using the template engine to \a $uri.
    */
    function setTemplateURI( $uri )
    {
        $this->TemplateURI = $uri;
    }

    /*!
     Figures out the absolute axis placement and returns it.
     The variable \a $type determines how \a $value is used, it can either
     be a constant value (self::PLACE_CONSTANT) or a relative value
     (self::PLACE_RELATIVE) where input value is placed relative to the length
     of the axis (\a $axisStop - \a $axisStart).
     \a $alignment determines where the axis should start, self::ALIGN_AXIS_NONE
     and self::ALIGN_AXIS_START will return the position from \a $axisStart towards \a $axisStop,
     self::ALIGN_AXIS_STOP returns the position from the \a $axisStop towards \a $axisStart
     while self::ALIGN_AXIS_CENTER returns the middle of \a $axisStart and \a $axisStop.
    */
    function calculateAxisPlacement( $value, $type, $alignment, $axisStart, $axisStop, $currentLength )
    {
        $pos = 0;
        if ( $type == self::PLACE_CONSTANT )
            $pos = $value;
        else if ( $type == self::PLACE_RELATIVE )
        {
            $length = $axisStop - $axisStart;
            $pos = $value * $length;
        }
        $alignment = $alignment & self::ALIGN_AXIS_MASK;
        if ( $alignment == self::ALIGN_AXIS_NONE or
             $alignment == self::ALIGN_AXIS_START )
            return $axisStart + $pos;
        if ( $alignment == self::ALIGN_AXIS_CENTER )
        {
            $length = $axisStop - $axisStart;
            $halfLength = (int)(($length - $currentLength) / 2);
            return $axisStart + $halfLength + $value;
        }
        else // Align to stop
        {
            $pos = $axisStop - $pos - $currentLength;
            return $pos;
        }
    }

    /*!
     Initializes the axis from the parameter value \a $name filling
     in any missing values with defaults.
    */
    function initializeAxis( $parameters, $name )
    {
        $axis = array();
        if ( isset( $parameters[$name] ) )
            $axis = $parameters[$name];
        if ( !isset( $axis['alignment'] ) )
            $axis['alignment'] = self::ALIGN_AXIS_NONE;
        if ( !isset( $axis['placement'] ) )
            $axis['placement'] = self::PLACE_CONSTANT;
        if ( !isset( $axis['value'] ) )
            $axis['value'] = 0;
        return $axis;
    }

    /*!
     Calculates the position for \c x and \c y parameters in \a $parameters and returns
     an absolute position in an array.
     The returned array will contain the \c x and \c y key.
    */
    function calculatePosition( $parameters, $width, $height )
    {
        $xAxis = eZImageObject::initializeAxis( $parameters, 'x' );
        $yAxis = eZImageObject::initializeAxis( $parameters, 'y' );
        $x = eZImageObject::calculateAxisPlacement( $xAxis['value'], $xAxis['placement'], $xAxis['alignment'],
                                                    0, $this->width(), $width );
        $y = eZImageObject::calculateAxisPlacement( $yAxis['value'], $yAxis['placement'], $yAxis['alignment'],
                                                    0, $this->height(), $height );
        return array( 'x' => $x,
                      'y' => $y );
    }

    /*!
     \return the transparency value found in the parameters or 0 if no value is found.
    */
    function getTransparency( $parameters )
    {
        if ( !isset( $parameters['transparency'] ) )
        {
            return 0.0;
        }
        $transparency = max( 0.0, min( 1.0, $parameters['transparency'] ) );
        return $transparency;
    }

    /*!
     \return the transparency percentage found in the parameters or 0 if no percentage is found.
    */
    function getTransparencyPercent( $parameters )
    {
        $transparency = eZImageObject::getTransparency( $parameters );
        return (int)($transparency * 100.0);
    }

    /*!
     Adds the image layer object \a $imageLayer to the end of the layer list with optional parameters \a $parameters
     \return the ID of the layer, the ID is unique per image object.
     \sa prependLayer
    */
    function appendLayer( &$imageLayer, $parameters = array() )
    {
        if ( !$imageLayer instanceof eZImageLayer )
        {
            eZDebug::writeWarning( 'Only eZImageLayer objects may be added as layer items',
                                   'eZImageObject::appendLayer' );
            return false;
        }
        ++$this->ImageLayerCounter;
        $layerID = $this->ImageLayerCounter;
        $this->ImageLayers[] = $layerID;
        $this->ImageLayerIndex[$layerID] = array( 'image' => &$imageLayer,
                                                  'parameters' => $parameters );
        return $layerID;
    }

    /*!
     Adds the image layer object \a $imageLayer to the beginning of the layer list with optional parameters \a $parameters
     \return the ID of the layer, the ID is unique per image object.
     \sa appendLayer
    */
    function prependLayer( &$imageLayer, $parameters = array() )
    {
        if ( !$imageLayer instanceof eZImageLayer )
        {
            eZDebug::writeWarning( 'Only eZImageLayer objects may be added as layer items',
                                   'eZImageObject::prependLayer' );
            return false;
        }
        ++$this->ImageLayerCounter;
        $layerID = $this->ImageLayerCounter;
        $this->ImageLayers = array_merge( array( $layerID ),
                                          $this->ImageLayers );
        $this->ImageLayerIndex[$layerID] = array( 'image' => &$imageLayer,
                                                  'parameters' => $parameters );
        return $layerID;
    }

    /*!
     \return true if the layer with ID \a $layerID exists in this image object.
    */
    function hasLayer( $layerID )
    {
        return ( in_array( $layerID, $this->ImageLayers ) and
                 array_key_exists( $layerID, $this->ImageLayerIndex ) );
    }

    /*!
     Cleans up the current image object if it is set.
    */
    function destroy()
    {
        if ( is_array( $this->ImageLayers ) )
        {
            foreach( $this->ImageLayers as $item )
            {
                if ( $this->ImageLayerIndex[$item]['image'] instanceof eZImageLayer )
                {
                    $this->ImageLayerIndex[$item]['image']->destroy();
                }
            }
        }
        parent::destroy();
    }

    /*!
     Removes the layer with ID \a $layerID.
     \return true if succesful
     \sa hasLayer, appendLayer
    */
    function removeLayer( $layerID )
    {
        if ( !in_array( $layerID, $this->ImageLayers ) or
             !array_key_exists( $layerID, $this->ImageLayerIndex ) )
            return false;
        $layerData = $this->ImageLayerIndex[$layerID];
        unset( $this->ImageLayerIndex[$layerID] );
        $imageLayers = array();
        foreach ( $this->ImageLayers as $imageLayerID )
        {
            if ( $imageLayerID != $layerID )
                $imageLayers[] = $imageLayerID;
        }
        $this->ImageLayers = $imageLayers;
        return true;
    }

    /*!
     Flattens the image so that it can be displayed.
    */
    function processImage()
    {
        $this->flatten();
        return true;
    }

    /*!
     Goes trough all layers and merges them together into a single image.
     This image can then be displayed on the webpage.
    */
    function flatten()
    {
        $i = 0;
        $hasFirst = false;
        $firstImageLayer = null;
        $firstImageLayerData = null;
        while ( $i < count( $this->ImageLayers ) and
                !$hasFirst )
        {
            $layerID = $this->ImageLayers[$i];
            $layerData =& $this->ImageLayerIndex[$layerID];
            $layer = $layerData['image'];
            if ( $layer instanceof eZImageLayer )
            {
                $firstImageLayerData = $layerData;
                $firstImageLayer = $layer;
                $hasFirst = true;
            }
            else
                eZDebug::writeWarning( 'Wrong image type ' . gettype( $layer ), 'eZImageObject::flatten' );
            ++$i;
        }
        if ( $hasFirst )
        {
            $lastImageLayerData = null;
            $firstImageLayer->imageObject();
            if ( !$this->width() )
            {
                $this->setWidth( $firstImageLayer->width() );
            }
            if ( !$this->height() )
            {
                $this->setHeight( $firstImageLayer->height() );
            }
            $firstImageLayer->mergeLayer( $this,
                                          $firstImageLayerData,
                                          $lastImageLayerData );
            $lastImageLayerData = null;
            while ( $i < count( $this->ImageLayers ) )
            {
                $layerID = $this->ImageLayers[$i];
                $layerData =& $this->ImageLayerIndex[$layerID];
                $layer = $layerData['image'];
                unset( $imageObject );
                if ( $layer instanceof eZImageLayer )
                {
                    $layer->mergeLayer( $this,
                                        $layerData,
                                        $lastImageLayerData );
                    $lastImageLayerData = $layerData;
                }
                else
                {
                    eZDebug::writeWarning( 'Wrong image type ' . gettype( $layer ), 'eZImageObject::flatten' );
                }
                ++$i;
            }
            return true;
        }
        return false;
    }

    /// \privatesection
    public $ImageLayers;
    public $TemplateURI;
    public $ImageLayerIndex;
}

?>
