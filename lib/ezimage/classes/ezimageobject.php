<?php
//
// Definition of eZImageObject class
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

/*! \file ezimageobject.php
*/

/*!
  \class eZImageObject ezimageobject.php
  \ingroup eZImageObject
  \brief Image object which handles image layers

*/

include_once( 'lib/ezimage/classes/ezimageinterface.php' );

/// Alignment values @{
define( 'EZ_IMAGE_ALIGN_AXIS_NONE', 0x00 );
define( 'EZ_IMAGE_ALIGN_AXIS_START', 0x01 );
define( 'EZ_IMAGE_ALIGN_AXIS_STOP', 0x02 );
define( 'EZ_IMAGE_ALIGN_AXIS_CENTER', EZ_IMAGE_ALIGN_AXIS_START | EZ_IMAGE_ALIGN_AXIS_STOP );
define( 'EZ_IMAGE_ALIGN_AXIS_MASK', EZ_IMAGE_ALIGN_AXIS_START | EZ_IMAGE_ALIGN_AXIS_STOP );
///@}

/// Placement types @{
/// Places the layer absolutely from on the axis.
define( 'EZ_IMAGE_PLACE_CONSTANT', 1 );
/// Places the layer relative to the axis size.
define( 'EZ_IMAGE_PLACE_RELATIVE', 2 );
///@}

class eZImageObject extends eZImageInterface
{
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
     be a constant value (EZ_IMAGE_PLACE_CONSTANT) or a relative value
     (EZ_IMAGE_PLACE_RELATIVE) where input value is placed relative to the length
     of the axis (\a $axisStop - \a $axisStart).
     \a $alignment determines where the axis should start, EZ_IMAGE_ALIGN_AXIS_NONE
     and EZ_IMAGE_ALIGN_AXIS_START will return the position from \a $axisStart towards \a $axisStop,
     EZ_IMAGE_ALIGN_AXIS_STOP returns the position from the \a $axisStop towards \a $axisStart
     while EZ_IMAGE_ALIGN_AXIS_CENTER returns the middle of \a $axisStart and \a $axisStop.
    */
    function calculateAxisPlacement( $value, $type, $alignment, $axisStart, $axisStop, $currentLength )
    {
        $pos = 0;
        if ( $type == EZ_IMAGE_PLACE_CONSTANT )
            $pos = $value;
        else if ( $type == EZ_IMAGE_PLACE_RELATIVE )
        {
            $length = $axisStop - $axisStart;
            $pos = $value * $length;
        }
        $alignment = $alignment & EZ_IMAGE_ALIGN_AXIS_MASK;
        if ( $alignment == EZ_IMAGE_ALIGN_AXIS_NONE or
             $alignment == EZ_IMAGE_ALIGN_AXIS_START )
            return $axisStart + $pos;
        if ( $alignment == EZ_IMAGE_ALIGN_AXIS_CENTER )
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
            $axis['alignment'] = EZ_IMAGE_ALIGN_AXIS_NONE;
        if ( !isset( $axis['placement'] ) )
            $axis['placement'] = EZ_IMAGE_PLACE_CONSTANT;
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
        if ( get_class( $imageLayer ) != 'ezimagelayer' and
             !is_subclass_of( $imageLayer, 'ezimagelayer' ) )
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
        if ( get_class( $imageLayer ) != 'ezimagelayer' and
             !is_subclass_of( $imageLayer, 'ezimagelayer' ) )
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
        if( is_array( $this->ImageLayers ) )
        {
            foreach( $this->ImageLayers as $item )
            {
                if ( get_class( $this->ImageLayerIndex[$item]['image'] ) == 'ezimagelayer' or
                     is_subclass_of( $this->ImageLayerIndex[$item]['image'], 'ezimagelayer' ) )
                    $this->ImageLayerIndex[$item]['image']->destroy();
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
     \virtual
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
            $layer =& $layerData['image'];
            if ( get_class( $layer ) == 'ezimagelayer' or
                 is_subclass_of( $layer, 'ezimagelayer' ) )
            {
                $firstImageLayerData =& $layerData;
                $firstImageLayer =& $layer;
                $hasFirst = true;
            }
            else
                eZDebug::writeWarning( 'Wrong image type ' . gettype( $layer ), 'eZImageObject::flatten' );
            ++$i;
        }
        if ( $hasFirst )
        {
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
                $layer =& $layerData['image'];
                unset( $imageObject );
                if ( get_class( $layer ) == 'ezimagelayer' or
                     is_subclass_of( $layer, 'ezimagelayer' ) )
                {
                    $layer->mergeLayer( $this,
                                        $layerData,
                                        $lastImageLayerData );
                    $lastImageLayerData =& $layerData;
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
    var $ImageLayers;
    var $TemplateURI;
    var $ImageLayerIndex;
}

?>
