<?php
//
// Definition of eZImageLayer class
//
// Created on: <03-Oct-2002 15:05:09 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
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

/*! \file
*/

/*!
  \class eZImageLayer ezimagelayer.php
  \ingroup eZImageObject
  \brief Defines a layer in a image object

*/

class eZImageLayer extends eZImageInterface
{
    /*!
     Constructor
    */
    function eZImageLayer( $imageObjectRef = null, $imageObject = null,
                           $width = false, $height = false, $font = false )
    {
        $this->eZImageInterface( $imageObjectRef, $imageObject, $width, $height );
        $this->setFont( $font );
        $this->TemplateURI = 'design:image/layer.tpl';
    }

    /*!
     A definition which tells the template engine which template to use
     for displaying the image.
    */
    function templateData()
    {
        return array( 'type' => 'template',
                      'template_variable_name' => 'layer',
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
     \virtual
     Tries to merge the current layer with the layer \a $lastLayerData
     onto the image object \a $image.
     Different kinds of layer classes will merge layers differently.
    */
    function mergeLayer( $image, $layerData, $lastLayerData )
    {
        $position = $image->calculatePosition( $layerData['parameters'], $this->width(), $this->height() );
        $x = $position['x'];
        $y = $position['y'];
        $imageObject = $this->imageObject();
        if ( $lastLayerData === null )
        {
            $destinationImageObject = $image->imageObjectInternal( false );
            if ( $destinationImageObject === null )
            {
                $isTrueColor = $this->isTruecolor();
                $image->cloneImage( $this->imageObject(), $this->width(), $this->height(),
                                    $isTrueColor );
            }
            else
            {
                $image->mergeImage( $destinationImageObject, $imageObject,
                                    $x, $y,
                                    $this->width(), $this->height(), 0, 0,
                                    $image->getTransparencyPercent( $layerData['parameters'] ) );
            }
        }
        else
        {
            $destinationImageObject = $image->imageObjectInternal();
            $image->mergeImage( $destinationImageObject, $imageObject,
                                $x, $y,
                                $this->width(), $this->height(), 0, 0,
                                $image->getTransparencyPercent( $layerData['parameters'] ) );
        }
    }

    /*!
     Creates a new file layer for the file \a $fileName in path \a $filePath.
    */
    static function createForFile( $fileName, $filePath, $fileType = false )
    {
        $layer = new eZImageLayer();
        $layer->setStoredFile( $fileName, $filePath, $fileType );
        $layer->process();
        return $layer;
    }

    /// \privatesection
    public $TemplateURI;
}

?>
