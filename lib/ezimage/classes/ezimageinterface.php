<?php
//
// Definition of eZImageInterface class
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

/*! \file ezimageinterface.php
*/

/*! \defgroup eZImageObject Image object and layer handling */

/*!
  \class eZImageInterface ezimageinterface.php
  \brief Base interface for all image object and layer classes

*/

class eZImageInterface
{
    function eZImageInterface( $imageObjectRef = null, $imageObject = null, $width = false, $height = false )
    {
        $this->ImageObjectRef = $imageObjectRef;
        $this->ImageObject = $imageObject;
        $this->Width = $width;
        $this->Height = $height;
        $this->AlternativeText = '';
        $this->IsTrueColor = null;
        $this->Palette = array();
        $this->PaletteIndex = array();
        $this->StoredPath = false;
        $this->Font = null;
        $this->IsProcessed = false;
    }

    function isTruecolor()
    {
        return $this->IsTrueColor;
    }

    function attributeMemberMap()
    {
        return array( 'filepath' => 'StoredPath',
                      'filename' => 'StoredFile',
                      'width' => 'Width',
                      'height' => 'Height',
                      'alternative_text' => 'AlternativeText' );
    }

    function attributeFunctionMap()
    {
        return array( 'imagepath' => 'imagePath',
                      'has_size' => 'hasSize' );
    }

    function attributes()
    {
        return array_merge( array_keys( eZImageInterface::attributeMemberMap() ),
                            array_keys( eZImageInterface::attributeFunctionMap() ) );
    }

    function hasAttribute( $name )
    {
        $attributeMemberMap = eZImageInterface::attributeMemberMap();
        if ( isset( $attributeMemberMap[$name] ) )
            return true;
        $attributeFunctionMap = eZImageInterface::attributeFunctionMap();
        if ( isset( $attributeFunctionMap[$name] ) )
            return true;
        return false;
    }

    function &attribute( $name )
    {
        $attributeMemberMap = eZImageInterface::attributeMemberMap();
        if ( isset( $attributeMemberMap[$name] ) )
        {
            $member = $attributeMemberMap[$name];
            if ( isset( $this->$member ) )
                return $this->$member;
            return null;
        }
        $attributeFunctionMap = eZImageInterface::attributeFunctionMap();
        if ( isset( $attributeFunctionMap[$name] ) )
        {
            $function = $attributeFunctionMap[$name];
            if ( method_exists( $this, $function ) )
                return $this->$function();
            return null;
        }
        return null;
    }

    function isProcessed()
    {
        return $this->IsProcessed;
    }

    function hasSize()
    {
        return $this->Width !== false and $this->Height !== false;
    }

    function &imagePath()
    {
        return $this->StoredPath . '/' . $this->StoredFile;
    }

    function setAlternativeText( $text )
    {
        $this->AlternativeText = $text;
    }

    function &alternativeText()
    {
        return $this->AlternativeText;
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
            $createdImage = $createdImageArray[$createImageKey];
            ImageDestroy( $createdImage );
        }
    }


    function loadPNG( $storedPath, $storedFile )
    {
        $this->ImageObject = ImageCreateFromPNG( $storedPath . '/' . $storedFile );
        if ( $this->ImageObject )
        {
            $this->ImageObjectRef = eZImageInterface::registerImage( $this->ImageObject );
            return true;
        }
        return false;
    }

    function loadJPEG( $storedPath, $storedFile )
    {
        $this->ImageObject = ImageCreateFromJPEG( $storedPath . '/' . $storedFile );
        if ( $this->ImageObject )
        {
            $this->ImageObjectRef = eZImageInterface::registerImage( $this->ImageObject );
            return true;
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
                return $this->loadPNG( $this->StoredPath, $this->StoredFile );
            } break;

            case 'jpg':
            {
                return $this->loadJPEG( $this->StoredPath, $this->StoredFile );
            } break;

            default:
            {
                if ( @$this->loadPNG( $this->StoredPath, $this->StoredFile ) )
                    return true;
                else if ( @$this->loadJPEG( $this->StoredPath, $this->StoredFile ) )
                    return true;
                eZDebug::writeError( 'Image format not supported: ' . $this->StoredType, 'eZImageInterface::load' );
            };
        }
        return false;
    }

    function destroy()
    {
        if ( $this->ImageObjectRef === null )
            return;
        ImageDestroy( $this->ImageObject );
        eZImageInterface::unregisterImage( $this->ImageObjectRef );
        unset( $this->ImageObject );
        $this->ImageObject = null;
        $this->ImageObjectRef = null;
    }

    function imageObject( $createMissing = true )
    {
        if ( $this->ImageObject === null or
             $this->ImageObjectRef === null )
        {
            if ( $createMissing )
            {
                if ( $this->StoredFile != '' )
                {
                    $this->process();
                }
            }
        }
        return $this->ImageObject;
    }

    function imageObjectInternal( $createMissing = true )
    {
        if ( $this->ImageObject === null or
             $this->ImageObjectRef === null )
        {
            if ( $createMissing )
                $this->create( $this->Width, $this->Height );
        }
        return $this->ImageObject;
    }

    function process()
    {
        if ( $this->processImage() )
            $this->IsProcessed = true;
    }

    /*!
     \virtual
    */
    function processImage()
    {
        if ( $this->StoredFile == '' )
            return true;
        $fileArray = array( $this->StoredPath, $this->StoredFile );
        include_once( 'lib/ezutils/classes/ezdir.php' );
        $filePath = eZDir::path( $fileArray );
        $imageinfo = getimagesize( $filePath );
        if ( $imageinfo )
        {
            $width = $imageinfo[0];
            $height = $imageinfo[1];
            if ( $this->load() )
            {
                $this->Width = $width;
                $this->Height = $height;
                return true;
            }
            else
                eZDebug::writeWarning( "Image failed to load '$filePath'", 'eZImageInterface::imageObject' );
        }
        else
            eZDebug::writeWarning( "No image info could be extracted from '$filePath'", 'eZImageInterface::imageObject' );
        return false;
    }

    function store( $fileName, $filePath, $type )
    {
        if ( !$this->IsProcessed )
            $this->process();
        $imageObject = $this->imageObject();
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
                $fileFullPath = eZDir::path( array( $filePath, $fileName ) );
                ImagePNG( $imageObject, $fileFullPath );
                $this->StoredPath = $filePath;
                $this->StoredFile = $fileName;
                $this->StoredType = $type;
                return true;
            } break;

            case 'jpg':
            {
                include_once( 'lib/ezutils/classes/ezdir.php' );
                if ( !file_exists( $filePath ) )
                {
                    $ini =& eZINI::instance();
                    $perm = $ini->variable( 'FileSettings', 'StorageDirPermissions' );
                    eZDir::mkdir( $filePath, octdec( $perm ), true );
                }
                ImageJPEG( $imageObject, eZDir::path( array( $filePath, $fileName ) ) );
                $this->StoredPath = $filePath;
                $this->StoredFile = $fileName;
                $this->StoredType = $type;
                return true;
            } break;

            default:
            {
                eZDebug::writeError( 'Image format not supported: ' . $type, 'eZImageInterface::store' );
            };
        }
        return false;
    }

    /*!
     \static
     \return true if GD2 is installed.
    */
    function hasGD2()
    {
        return true;
    }

    /*!
     \static
     \private
     Creates an image with size \a $width and \a $height using GD and returns it.
    */
    function createImage( $width, $height, &$useTruecolor )
    {
        if ( $useTruecolor === null )
            $useTruecolor = eZImageInterface::hasGD2();
        if ( $useTruecolor and
             !function_exists( 'ImageCreateTrueColor' ) )
        {
            eZDebug::writeWarning( 'Function ImageCreateTrueColor does not exist, cannot create true color images',
                                   'eZImageInterface::createImage' );
            $useTruecolor = false;
        }
        if ( $useTruecolor )
            $imageObject = ImageCreateTrueColor( $width, $height );
        else
            $imageObject = ImageCreate( $width, $height );
        return $imageObject;
    }

    function create( $width, $height, $useTruecolor = null )
    {
        if ( $this->ImageObject !== null and
             $this->ImageObjectRef !== null )
        {
            $this->destroy();
        }
        unset( $this->ImageObject );
        $this->ImageObject = eZImageInterface::createImage( $width, $height, $useTruecolor );
        $this->Width = $width;
        $this->Height = $height;
//         eZDebug::writeDebug( $this->ImageObject, 'create' );
        $this->IsTrueColor = $useTruecolor;
        $this->ImageObjectRef = eZImageInterface::registerImage( $this->ImageObject );
    }

    function clone( &$image )
    {
        $this->cloneImage( $image->imageObject(), $image->width(), $image->height(),
                           $image->isTruecolor() );
    }

    function cloneImage( $imageObject, $width, $height, $useTruecolor = null )
    {
        if ( $this->ImageObject !== null and
             $this->ImageObjectRef !== null )
        {
            $this->destroy();
        }
        $this->ImageObject = eZImageInterface::createImage( $width, $height, $useTruecolor );
        $this->IsTrueColor = $useTruecolor;
        $this->ImageObjectRef = eZImageInterface::registerImage( $this->ImageObject );
        ImageCopy( $this->ImageObject, $imageObject, 0, 0, 0, 0, $width, $height );
    }


    function width()
    {
        return $this->Width;
    }

    function height()
    {
        return $this->Height;
    }

    function setWidth( $w )
    {
        $this->Width = $w;
    }

    function setHeight( $h )
    {
        $this->Height = $h;
    }

    function setStoredFile( $file, $path, $type )
    {
        $this->StoredFile = $file;
        $this->StoredPath = $path;
        $this->StoredType = $type;
    }

    function setFont( $font )
    {
        $this->Font = $font;
    }

    function font()
    {
        return $this->Font;
    }

    function copyImage( $destinationImageObject, $imageObject,
                        $destinationX, $destinationY,
                        $sourceWidth, $sourceHeight, $sourceX = 0, $sourceY = 0 )
    {
        ImageCopy( $destinationImageObject, $imageObject,
                   $destinationX, $destinationY,
                   $sourceX, $sourceY, $sourceWidth, $sourceHeight );
    }

    function mergeImage( $destinationImageObject, $imageObject,
                         $destinationX, $destinationY,
                         $sourceWidth, $sourceHeight, $sourceX = 0, $sourceY = 0,
                         $transparency = 0 )
    {
        $percent = 100 - $transparency;
        ImageCopyMerge( $destinationImageObject, $imageObject,
                        $destinationX, $destinationY,
                        $sourceX, $sourceY, $sourceWidth, $sourceHeight,
                        $percent );
    }

    function blendImage( $destinationImageObject, $imageObject,
                         $destinationX, $destinationY,
                         $sourceWidth, $sourceHeight, $sourceX = 0, $sourceY = 0 )
    {
        ImageAlphaBlending( $destinationImageObject, true );
        ImageCopy( $destinationImageObject, $imageObject,
                   $destinationX, $destinationY,
                   $sourceX, $sourceY, $sourceWidth, $sourceHeight );
    }

    function merge( $imageObject, $x, $y, $width, $height )
    {
        if ( $this->ImageObject === null or
             $this->ImageObjectRef === null )
            return false;
//         ImageAlphaBlending( $this->ImageObject, true );
        imagecolortransparent( $imageObject, 0 );
//         ImageCopy( $this->ImageObject, $imageObject, $x, $y, 0, 0, $width, $height );
        ImageCopyMerge( $this->ImageObject, $imageObject, $x, $y, 0, 0, $width, $height, 50 );
    }

    function clear( $color = false )
    {
        if ( $color === false )
        {
            if ( count( $this->PaletteIndex ) > 0)
                $color = $this->PaletteIndex[0];
            else
                $color = 'bgcol';
        }
        if ( is_string( $color ) )
            $color = $this->color( $color );
        ImageFilledRectangle( $this->ImageObject, 0, 0, $this->Width, $this->Height, $color );
    }

    function allocateColor( $name, $red, $green, $blue )
    {
        if ( isset( $this->Palette[$name] ) )
        {
            eZDebug::writeError( 'Color already defined: ' . $name, 'eZImageInterface::allocateColor' );
            return null;
        }
        $red = max( 0, min( 255, $red ) );
        $green = max( 0, min( 255, $green ) );
        $blue = max( 0, min( 255, $blue ) );
        $color = ImageColorAllocate( $this->ImageObject, $red, $green, $blue );
        if ( count( $this->PaletteIndex ) == 0 )
        {
//             imagecolortransparent( $this->ImageObject, $color );
//             ImageFilledRectangle( $this->ImageObject, 0, 0, $this->Width, $this->Height, $color );
        }
        $this->Palette[$name] = $color;
        $this->PaletteIndex[] = $color;
        return $color;
    }

    function color( $name )
    {
        if ( !isset( $this->Palette[$name] ) )
        {
            eZDebug::writeError( 'Color not defined: ' . $name, 'eZImageInterface::color' );
            return null;
        }
        return $this->Palette[$name];
    }

    function textColor()
    {
        return $this->TextColor;
    }

    function setTextColor( $textColor )
    {
        $this->TextColor = $textColor;
    }

    function drawText( &$font, $textColor, $text, $x, $y, $angle,
                       $imageObject = null )
    {
        if ( !$font )
        {
            eZDebug::writeWarning( 'Cannot render text, no font is set',
                                   'eZImageInterface::drawText' );
            return false;
        }
        if ( !$textColor )
        {
            eZDebug::writeWarning( 'Cannot render text, no text color is set',
                                   'eZImageInterface::drawText' );
            return false;
        }
        if ( is_string( $textColor ) )
            $textColor = $this->color( $textColor );
        if ( $textColor === null )
        {
            eZDebug::writeWarning( 'Cannot render text, invalid text color',
                                   'eZImageInterface::drawText' );
            return false;
        }

        $x += $font->xAdjustment();
        $y += $font->yAdjustment();

        if ( $imageObject === null )
            $imageObject = $this->ImageObject;

        ImageTTFText( $imageObject, $font->pointSize(), $angle, $x, $y,
                      $textColor, $font->realFile(), $text );
    }

    /// \privatesection
    var $Width;
    var $Height;
    var $Font;
    var $ImageObject;
    var $ImageObjectRef;
    var $StoredFile;
    var $StoredPath;
    var $StoredType;
    var $PaletteIndex;
    var $Palette;
    var $AlternativeText;
    var $IsTrueColor;
    var $IsProcessed;
}

function eZGlobalImageCleanupFunction()
{
    eZImageInterface::cleanupRegisteredImages();
}

?>
