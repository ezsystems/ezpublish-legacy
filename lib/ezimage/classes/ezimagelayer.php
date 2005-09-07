<?php
//
// Definition of eZImageLayer class
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

/*! \file ezimagelayer.php
*/

/*!
  \class eZImageLayer ezimagelayer.php
  \ingroup eZImageObject
  \brief Defines a layer in a image object

*/

include_once( 'lib/ezimage/classes/ezimageinterface.php' );

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
    function mergeLayer( &$image, &$layerData, &$lastLayerData )
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
                $image->clone( $this );
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
    function &createForFile( $fileName, $filePath, $fileType = false )
    {
        $layer = new eZImageLayer();
        $layer->setStoredFile( $fileName, $filePath, $fileType );
        $layer->process();
        return $layer;
    }

    /// \privatesection
    var $TemplateURI;
}

?>
