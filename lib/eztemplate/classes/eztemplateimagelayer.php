<?php
//
// Definition of eZTemplateImageLayer class
//
// Created on: <03-Oct-2002 15:05:09 amos>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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

/*! \file eztemplateimagelayer.php
*/

/*!
  \class eZTemplateImageLayer eztemplateimagelayer.php
  \brief The class eZTemplateImageLayer does

*/

class eZTemplateImageFont
{
    function eZTemplateImageFont( $family, $size, $path )
    {
        $this->FontFamily = $family;
        $this->FontPath = $path;
        $this->PointSize = $size;

        $this->initialize();
    }

    function family()
    {
        return $this->FontFamily;
    }

    function path()
    {
        return $this->FontPath;
    }

    function file()
    {
        return $this->FontFile;
    }

    function pointSize()
    {
        return $this->PointSize;
    }

    /*!
     \static
     \return true if the font family \a $fontFamily exists in the path \a $fontPath.
    */
    function exists( $fontFamily, $fontPath )
    {
        return eZTemplateImageFont::fontFile( $fontFamily, $fontPath ) != false;
    }

    function initialize()
    {
        $this->FontFile = eZTemplateImageFont::fontFile( $this->FontFamily, $this->FontPath );
    }

    function fontFile( $fontFamily, $fontPath )
    {
        if ( preg_match( '/\.ttf$/', $fontFamily ) )
            $family_file = $fontFamily;
        else
            $family_file = $fontFamily . '.ttf';
        if ( $fontPath != '' )
        {
            $font = $fontPath . "/$family_file";
            if ( !file_exists( $font ) )
                $font = false;
        }
        else
            $font = $fontFamily;
        return $font;
    }

    /// \privatesection
    var $FontFamily;
    var $FontPath;
    var $FontFile;
    var $PointSize;
}

class eZTemplateImageObject
{
    function eZTemplateImageObject( $imageObjectRef = null, $imageObject = null, $width = false, $height = false )
    {
        $this->ImageObjectRef =& $imageObjectRef;
        $this->ImageObject =& $imageObject;
        $this->Width = $width;
        $this->Height = $height;
        $this->TemplateURI = 'design:image/imageobject.tpl';
        $this->AlternativeText = '';
        $this->ImageLayers = array();
    }

    function templateData()
    {
        return array( 'type' => 'template',
                      'template_variable_name' => 'image',
                      'uri' => $this->TemplateURI );
    }

    function hasAttribute( $attr )
    {
        return ( $attr == 'filepath' or
                 $attr == 'filename' or
                 $attr == 'imagepath' or
                 $attr == 'alternative_text' );
    }

    function &attribute( $attr )
    {
        switch( $attr )
        {
            case 'filepath':
            {
                return $this->StoredPath;
            } break;
            case 'htmlpath':
            {
                return $this->HTMLPath;
            } break;
            case 'filename':
            {
                return $this->StoredFile;
            } break;
            case 'imagepath':
            {
                return $this->HTMLPath . '/' . $this->StoredFile;
            } break;
            case 'alternative_text':
            {
                if ( $this->AlternativeText == '' )
                {
                }
                else
                    return $this->AlternativeText;
            } break;
            default:
                return null;
        }
    }

    function setAlternativeText( $text )
    {
        $this->AlternativeText = $text;
    }

    function alternativeText()
    {
        return $this->AlternativeText;
    }

    function setTemplateURI( $uri )
    {
        $this->TemplateURI = $uri;
    }

    function registerImage( $image )
    {
        $imageObjectRef = md5( microtime() );
        $createdImageArray =& $GLOBALS['eZImageCreatedArray'];
        if ( !is_array( $createdImageArray ) )
        {
            $createdImageArray = array();
            register_shutdown_function( 'eZGlobalImageCleanupFunction' );
        }
        $createdImageArray[$imageObjectRef] = $image;
        return $imageObjectRef;
    }

    function unregisterImage( $imageRef )
    {
        $createdImageArray =& $GLOBALS['eZImageCreatedArray'];
        if ( !is_array( $createdImageArray ) )
            return;
        if ( !isset( $createdImageArray[$imageRef] ) )
            return;
        unset( $createdImageArray[$imageRef] );
    }

    function cleanupRegisteredImages()
    {
        $createdImageArray =& $GLOBALS['eZImageCreatedArray'];
        if ( !is_array( $createdImageArray ) )
            return;
        foreach ( array_keys( $createdImageArray ) as $createImageKey )
        {
            $createdImage = $createdImage[$createdImageKey];
            ImageDestroy( $createdImage );
        }
    }

    function destroy()
    {
        if ( $this->ImageObjectRef === null )
            return;
        ImageDestroy( $this->ImageObject );
        eZTemplateImageObject::unregisterImage( $this->ImageObjectRef );
        $this->ImageObject = null;
        $this->ImageObjectRef = null;
    }

    function store( $fileName, $filePath, $type )
    {
        switch( $type )
        {
            case 'png':
            {
                include_once( 'lib/ezutils/classes/ezdir.php' );
                if ( !file_exists( $filePath ) )
                {
                    $ini =& eZINI::instance();
                    $perm = $ini->variable( 'FileSettings', 'StorageDirPermissions' );
                    eZDir::mkdir( $filePath, octdec( $perm ), true );
                }
                ImagePNG( $this->ImageObject, eZDir::path( array( $filePath, $fileName ) ) );
                $this->StoredPath = $filePath;
                $this->StoredFile = $fileName;
                $this->StoredType = $type;
                return true;
            } break;
            default:
            {
                eZDebug::writeError( 'Image format not supported: ' . $type, 'eZTemplateImageObject::store' );
            };
        }
        return false;
    }

    function load()
    {
        if ( $this->ImageObject !== null and
             $this->ImageObjectRef !== null )
            return;
        switch( $this->StoredType )
        {
            case 'png':
            {
                $this->ImageObject =& ImageCreateFromPNG( $this->ImageObject, $this->StoredPath . '/' . $this->StoredFile );
                if ( $this->ImageObject )
                {
                    $this->ImageObjectRef = eZTemplateImageObject::registerImage( $this->ImageObject );
                    return true;
                }
            } break;
            default:
            {
                eZDebug::writeError( 'Image format not supported: ' . $this->StoredType, 'eZTemplateImageObject::load' );
            };
        }
        return false;
    }

    function create( $width, $height )
    {
        if ( $this->ImageObject !== null and
             $this->ImageObjectRef !== null )
        {
            $this->destroy();
        }
        $this->ImageObject = ImageCreate( $width, $height );
        $this->ImageObjectRef = eZTemplateImageObject::registerImage( $imageObject );
    }

    function clone( $imageObject, $width, $height )
    {
        if ( $this->ImageObject !== null and
             $this->ImageObjectRef !== null )
        {
            $this->destroy();
        }
        $this->ImageObject = ImageCreate( $width, $height );
        $this->ImageObjectRef = eZTemplateImageObject::registerImage( $imageObject );
        ImageCopy( $this->ImageObject, $imageObject, 0, 0, 0, 0, $width, $height );
    }

    function merge( $imageObject, $x, $y, $width, $height )
    {
        if ( $this->ImageObject === null or
             $this->ImageObjectRef === null )
            return false;
//         ImageAlphaBlending( $this->ImageObject, true );
//         ImageCopy( $this->ImageObject, $imageObject, $x, $y, 0, 0, $width, $height );
        ImageCopyMerge( $this->ImageObject, $imageObject, $x, $y, 0, 0, $width, $height, 50 );
    }

    function addLayer( &$imageLayer )
    {
        if ( get_class( $imageLayer ) != 'eztemplateimagelayer' )
            return;
        $this->ImageLayers[] = $imageLayer;
    }

    function flatten()
    {
        $i = 0;
        $hasFirst = false;
        $firstImageObject = null;
        while ( $i < count( $this->ImageLayers ) and
                !$hasFirst )
        {
            $layer =& $this->ImageLayers[$i];
            if ( get_class( $layer ) == 'eztemplateimagelayer' )
            {
                unset( $imageObject );
                $imageObject = $layer->imageObject();
                if ( $imageObject !== null )
                {
                    $firstImageObject =& $layer;
                    $hasFirst = true;
                }
            }
            ++$i;
        }
        if ( $hasFirst )
        {
            $this->clone( $firstImageObject->imageObject(), $firstImageObject->width(), $firstImageObject->height() );
            while ( $i < count( $this->ImageLayers ) )
            {
                $layer =& $this->ImageLayers[$i];
                if ( get_class( $layer ) == 'eztemplateimagelayer' )
                {
                    unset( $imageObject );
                    $imageObject = $layer->imageObject();
                    if ( $imageObject !== null )
                    {
                        $this->merge( $imageObject,
                                      $layer->x(), $layer->y(),
                                      $layer->width(), $layer->height() );
                    }
                }
                ++$i;
            }
            return true;
        }
        return false;
    }

    function width()
    {
        return $this->Width;
    }

    function height()
    {
        return $this->Height;
    }

    function setStoredFile( $file, $path, $type )
    {
        $this->StoredFile = $file;
        $this->StoredPath = $path;
        $this->StoredType = $type;
    }

    function setHTMLPath( $path )
    {
        $this->HTMLPath = $path;
    }

    /// \privatesection
    var $Width;
    var $Height;
    var $ImageLayers;

    var $ImageObject;
    var $ImageObjectRef;
    var $StoredFile;
    var $StoredPath;
    var $StoredType;
    var $HTMLPath;
    var $AlternativeText;
    var $TemplateURI;
}

class eZTemplateImageLayer
{
    /*!
     Constructor
    */
    function eZTemplateImageLayer( $imageObjectRef = null, $imageObject = null, $width = false, $height = false, $x = false, $y = false, $font = false, $boundingBox = null )
    {
        $this->ImageObjectRef =& $imageObjectRef;
        $this->ImageObject =& $imageObject;
        $this->Width = $width;
        $this->Height = $height;
        $this->Font = $font;
        $this->X = $x;
        $this->Y = $y;
        $this->Palette = array();
        $this->TextBoundingBox = $boundingBox;
        $this->TemplateURI = 'design:image/layer.tpl';
        $this->AlternativeText = '';
    }

    function templateData()
    {
        return array( 'type' => 'template',
                      'template_variable_name' => 'layer',
                      'uri' => $this->TemplateURI );
    }

    function hasAttribute( $attr )
    {
        return ( $attr == 'filepath' or
                 $attr == 'filename' or
                 $attr == 'imagepath' or
                 $attr == 'alternative_text' );
    }

    function &attribute( $attr )
    {
        switch( $attr )
        {
            case 'filepath':
            {
                return $this->StoredPath;
            } break;
            case 'htmlpath':
            {
                return $this->HTMLPath;
            } break;
            case 'filename':
            {
                return $this->StoredFile;
            } break;
            case 'imagepath':
            {
                return $this->HTMLPath . '/' . $this->StoredFile;
            } break;
            case 'alternative_text':
            {
                return $this->AlternativeText;
            } break;
            default:
                return null;
        }
    }

    function setAlternativeText( $text )
    {
        $this->AlternativeText = $text;
    }

    function alternativeText()
    {
        return $this->AlternativeText;
    }

    function setTemplateURI( $uri )
    {
        $this->TemplateURI = $uri;
    }

    function allocateColor( $name, $red, $green, $blue )
    {
        if ( isset( $this->Palette[$name] ) )
        {
            eZDebug::writeError( 'Color already defined: ' . $name, 'eZTemplateImageLayer::allocateColor' );
            return null;
        }
        $color = ImageColorAllocate( $this->ImageObject, $red, $green, $blue );
        $this->Palette[$name] = $color;
        return $color;
    }

    function color( $name )
    {
        if ( !isset( $this->Palette[$name] ) )
        {
            eZDebug::writeError( 'Color not defined: ' . $name, 'eZTemplateImageLayer::color' );
            return null;
        }
        return $this->Palette[$name];
    }

    function setTextColor( $textColor )
    {
        $this->TextColor = $textColor;
    }

    function textBoundingBox()
    {
        return $this->TextBoundingBox;
    }

    function drawText( $text,
                       $x, $y, $angle )
    {
        $textColor = $this->TextColor;
        if ( is_string( $textColor ) )
            $textColor = $this->color( $textColor );

        ImageTTFText( $this->ImageObject, $this->Font->pointSize(), $angle, $x, $y,
                      $textColor, $this->Font->file(), $text );
    }

    function &createForText( $text, $font,
                            $widthAdjustment, $heightAdjustment, $angle )
    {
        if ( get_class( $font ) != 'eztemplateimagefont' )
            return false;
        $bbox = @ImageTTFBBox( $font->pointSize(), $angle, $font->file(), $text );
        if ( !$bbox )
            return false;

        $width = $bbox[4] - $bbox[6];
        $height = $bbox[1] - $bbox[7];
        $width += $widthAdjustment;
        $height += $heightAdjustment;
        $imageObject = ImageCreate( $width, $height );
        $imageObjectRef = eZTemplateImageObject::registerImage( $imageObject );
        return new eZTemplateImageLayer( $imageObjectRef, $imageObject, $width, $height, 0, 0, $font, $bbox );
    }

    function imageObject( $createMissing = true )
    {
        if ( $this->ImageObject === null or
             $this->ImageObjectRef === null )
        {
            if ( $this->StoredFile != '' and
                 $this->StoredPath != '' and
                 $this->StoredType != '' )
            {
                $imageinfo = getimagesize( $this->StoredPath . '/' . $this->StoredFile );
                if ( $imageinfo )
                {
                    $width = $imageinfo[0];
                    $height = $imageinfo[1];
                    if ( $this->load() )
                    {
                        $this->Width = $width;
                        $this->Height = $height;
                    }
                }
            }
        }
        return $this->ImageObject;
    }

    function &createForFile( $fileName, $filePath, $fileType )
    {
        $layer =& new eZTemplateImageLayer();
        $layer->setStoredFile( $fileName, $filePath, $fileType );
        return $layer;
    }

    function destroy()
    {
        if ( $this->ImageObjectRef === null )
            return;
        ImageDestroy( $this->ImageObject );
        eZTemplateImageObject::unregisterImage( $this->ImageObjectRef );
        $this->ImageObject = null;
        $this->ImageObjectRef = null;
    }

    function store( $fileName, $filePath, $type )
    {
        switch( $type )
        {
            case 'png':
            {
                include_once( 'lib/ezutils/classes/ezdir.php' );
                if ( !file_exists( $filePath ) )
                {
                    $ini =& eZINI::instance();
                    $perm = $ini->variable( 'FileSettings', 'StorageDirPermissions' );;
                    eZDir::mkdir( $filePath, octdec( $perm ), true );
                }
                ImagePNG( $this->ImageObject, eZDir::path( array( $filePath, $fileName ) ) );
                $this->StoredPath = $filePath;
                $this->StoredFile = $fileName;
                $this->StoredType = $type;
                return true;
            } break;
            default:
            {
                eZDebug::writeError( 'Image format not supported: ' . $type, 'eZTemplateImageLayer::store' );
            };
        }
        return false;
    }

    function load()
    {
        if ( $this->ImageObject !== null and
             $this->ImageObjectRef !== null )
            return;
        switch( $this->StoredType )
        {
            case 'png':
            {
                $this->ImageObject =& ImageCreateFromPNG( $this->StoredPath . '/' . $this->StoredFile );
                if ( $this->ImageObject )
                {
                    $this->ImageObjectRef = eZTemplateImageObject::registerImage( $this->ImageObject );
                    return true;
                }
            } break;
            default:
            {
                eZDebug::writeError( 'Image format not supported: ' . $this->StoredType, 'eZTemplateImageLayer::load' );
            };
        }
        return false;
    }

    function width()
    {
        return $this->Width;
    }

    function height()
    {
        return $this->Height;
    }

    function setX( $px )
    {
        $this->X = $px;
    }

    function setY( $py )
    {
        $this->Y = $py;
    }
    function x()
    {
        return $this->X;
    }

    function y()
    {
        return $this->Y;
    }

    function setStoredFile( $file, $path, $type )
    {
        $this->StoredFile = $file;
        $this->StoredPath = $path;
        $this->StoredType = $type;
    }

    function setHTMLPath( $path )
    {
        $this->HTMLPath = $path;
    }

    /// \privatesection
    var $ImageObject;
    var $ImageObjectRef;
    var $Palette;
    var $Font;
    var $Width;
    var $Height;
    var $X;
    var $Y;
    var $TextBoundingBox;
    var $StoredFile;
    var $StoredPath;
    var $StoredType;
    var $HTMLPath;
    var $TemplateURI;
    var $AlternativeText;
}

function eZGlobalImageCleanupFunction()
{
    eZTemplateImageObject::cleanupRegisteredImages();
}

?>
