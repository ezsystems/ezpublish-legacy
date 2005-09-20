<?php
//
// Definition of eZImageInterface class
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

    /*!
     \return true if the image is true color. True color images behave differently from palette based
     and GD has problems with mixing the two types.
    */
    function isTruecolor()
    {
        return $this->IsTrueColor;
    }

    /*!
     \private
     \return a map array which maps from an attribute name to a member variable.
     Used by attributes, hasAttribute and attribute.
    */
    function attributeMemberMap()
    {
        return array( 'filepath' => 'StoredPath',
                      'filename' => 'StoredFile',
                      'width' => 'Width',
                      'height' => 'Height',
                      'alternative_text' => 'AlternativeText' );
    }

    /*!
     \private
     \return a map array which maps from an attribute name to a member function.
     Used by attributes, hasAttribute and attribute.
    */
    function attributeFunctionMap()
    {
        return array( 'imagepath' => 'imagePath',
                      'has_size' => 'hasSize' );
    }

    /*!
     \return an array with attribute names which this object supports.
    */
    function attributes()
    {
        return array_merge( array_keys( eZImageInterface::attributeMemberMap() ),
                            array_keys( eZImageInterface::attributeFunctionMap() ) );
    }

    /*!
     \return true if the attribute \a $name exists.
    */
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

    /*!
     \return the attribute with name \a $name or \c null if the attribute does not exist.
    */
    function &attribute( $name )
    {
        $attributeMemberMap = eZImageInterface::attributeMemberMap();
        if ( isset( $attributeMemberMap[$name] ) )
        {
            $member = $attributeMemberMap[$name];
            if ( isset( $this->$member ) )
                return $this->$member;
            eZDebug::writeWarning( 'The member variable $member was not found for attribute $name', 'eZImageInterface::attribute' );
            $retValue = null;
            return $retValue;
        }
        $attributeFunctionMap = eZImageInterface::attributeFunctionMap();
        if ( isset( $attributeFunctionMap[$name] ) )
        {
            $function = $attributeFunctionMap[$name];
            if ( method_exists( $this, $function ) )
                return $this->$function();
            eZDebug::writeWarning( 'The member function $function was not found for attribute $name', 'eZImageInterface::attribute' );
            $retValue = null;
            return $retValue;
        }
        eZDebug::writeError( "Attribute '$name' does not exist", 'eZImageInterface::attribute' );
        $retValue = null;
        return $retValue;
    }

    /*!
     \return true if the image object has been processed, this means that
             image has been rendered.
    */
    function isProcessed()
    {
        return $this->IsProcessed;
    }

    /*!
      \return true if the width and height of the image has been set.
    */
    function &hasSize()
    {
        $hasSize = ( $this->Width !== false and $this->Height !== false );
        return $hasSize;
    }

    /*!
     \return the path to the image file including the file.
    */
    function &imagePath()
    {
        $imagePath = $this->StoredPath . '/' . $this->StoredFile;
        return $imagePath;
    }

    /*!
     Sets the alternative text to \a $text, it will be used for describing the
     image and can be used by browsers that cannot view images.
    */
    function setAlternativeText( $text )
    {
        $this->AlternativeText = $text;
    }

    /*!
     \return the alternative text for the image.
     \sa setAlternativeText
    */
    function alternativeText()
    {
        return $this->AlternativeText;
    }

    /*!
     \protected
     Registers the GD image object \a $image for destruction upon script end.
     This makes sure that image resources are cleaned up after use.
     \return a reference for the image which can be used in unregisterImage later on. Returns false if resource can't be registered.
    */
    function registerImage( $image )
    {
        if ( !is_resource( $image ) or get_resource_type( $image ) != 'gd' )
            return false;
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

    /*!
     Tries to unregister the image with reference \a $imageRef
    */
    function unregisterImage( $imageRef )
    {
        $createdImageArray =& $GLOBALS['eZImageCreatedArray'];
        if ( !is_array( $createdImageArray ) )
            return;
        if ( !isset( $createdImageArray[$imageRef] ) )
            return;
        unset( $createdImageArray[$imageRef] );
    }

    /*!
     Cleans up all registered images.
    */
    function cleanupRegisteredImages()
    {
        $createdImageArray =& $GLOBALS['eZImageCreatedArray'];
        if ( !is_array( $createdImageArray ) )
            return;
        foreach ( array_keys( $createdImageArray ) as $createImageKey )
        {
            $createdImage = $createdImageArray[$createImageKey];
            if ( is_resource( $createdImage ) and get_resource_type( $createdImage ) == 'gd' )
                ImageDestroy( $createdImage );
        }
    }

    /*!
     Tries to load the PNG image from the path \a $storedPath and file \a $storedFile into
     the current image object.
     \return true if succesful.
    */
    function loadPNG( $storedPath, $storedFile )
    {
        if ( !function_exists( 'ImageCreateFromPNG' ) )
            return false;
        $this->ImageObject = ImageCreateFromPNG( $storedPath . '/' . $storedFile );
        if ( $this->ImageObject )
        {
            $this->ImageObjectRef = eZImageInterface::registerImage( $this->ImageObject );
            return true;
        }
        return false;
    }

    /*!
     Tries to load the JPEG image from the path \a $storedPath and file \a $storedFile into
     the current image object.
     \return true if succesful.
    */
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

    /*!
     Tries to load the GIF image from the path \a $storedPath and file \a $storedFile into
     the current image object.
     \return true if succesful.
    */
    function loadGIF( $storedPath, $storedFile )
    {
        if ( !function_exists( 'ImageCreateFromGIF' ) )
            return false;
        $this->ImageObject = ImageCreateFromGIF( $storedPath . '/' . $storedFile );
        if ( $this->ImageObject )
        {
            $this->ImageObjectRef = eZImageInterface::registerImage( $this->ImageObject );
            return true;
        }
        return false;
    }

    /*!
     Tries to load the stored image set by setStoredFile().
     If the stored type is not set it will try all formats until one succeeds.
     \return true if succesful.
    */
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

            case 'gif':
            {
                return $this->loadGIF( $this->StoredPath, $this->StoredFile );
            } break;

            default:
            {
                if ( @$this->loadPNG( $this->StoredPath, $this->StoredFile ) )
                    return true;
                else if ( @$this->loadJPEG( $this->StoredPath, $this->StoredFile ) )
                    return true;
                else if ( @$this->loadGIF( $this->StoredPath, $this->StoredFile ) )
                    return true;
                eZDebug::writeError( 'Image format not supported: ' . $this->StoredType, 'eZImageInterface::load' );
            };
        }
        return false;
    }

    /*!
     Cleans up the current image object if it is set.
    */
    function destroy()
    {
        if ( $this->ImageObjectRef === null )
            return;
        if ( is_resource( $this->ImageObject ) and get_resource_type( $this->ImageObject ) == 'gd' )
            ImageDestroy( $this->ImageObject );
        eZImageInterface::unregisterImage( $this->ImageObjectRef );
        unset( $this->ImageObject );
        $this->ImageObject = null;
        $this->ImageObjectRef = null;
    }

    /*!
     \return the current image object, if \a $createMissing is true if will
             run the image processing to make sure it is created.
             Returns \c null if no image is available.
     \sa imageObjectInternal
    */
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

    /*!
     \protected
     \return the current image object, will create an empty image object if \a $createMissing
             is true and the image object is not already created.
     \sa imageObject
    */
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

    /*!
     Makes sure the image object is processed and rendered.
     Calls processImage() which is implemented by all descendants of this class to do the real work.
    */
    function process()
    {
        if ( $this->processImage() )
            $this->IsProcessed = true;
    }

    /*!
     \virtual
     Tries to render an image onto the image object, each inheriting class must override this to do
     somethign sensible. By default it will try to load the stored image if one is set.
     \return true if the image was succesfully processed.
    */
    function processImage()
    {
        if ( $this->StoredFile == '' )
            return true;
        $fileArray = array( $this->StoredPath, $this->StoredFile );
        include_once( 'lib/ezfile/classes/ezdir.php' );
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

    /*!
     Stores the current image object to disk, the image is stored in the path \a $filePath with filename \a $fileName.
     The parameter \a $type determines the image format, supported are \c png and \c jpg.
     \return true if the image was stored correctly.
    */
    function store( $fileName, $filePath, $type )
    {
        if ( !$this->IsProcessed )
            $this->process();
        $imageObject = $this->imageObject();
        switch( $type )
        {
            case 'png':
            {
                include_once( 'lib/ezfile/classes/ezdir.php' );
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
                include_once( 'lib/ezfile/classes/ezdir.php' );
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
        $imageINI =& eZINI::instance( 'image.ini' );
        return $imageINI->variable( 'GDSettings', 'HasGD2' ) == 'true';
//         $testGD = get_extension_funcs( "gd" ); // Grab function list
//         if ( !$testGD )
//         {
// //             echo "GD not even installed.";
//             return false;
//         }
//         if ( in_array( "imagegd2",
//                        $testGD ) )
//             return true;
//         return false;
    }

    /*!
     \static
     \private
     Creates an image with size \a $width and \a $height using GD and returns it.
    */
    function createImage( $width, $height, &$useTruecolor )
    {
        if ( $useTruecolor === null )
        {
//             print( "has GD2='" . eZImageInterface::hasGD2() . "'<br/>" );
            $useTruecolor = eZImageInterface::hasGD2();
        }
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

    /*!
     Creates a new image object with width \a $width and height \a $height.
     \a $useTruecolor determines the type of image, if \c true it will be truecolor,
     if \c false it will be palette based or if \c null it will create it depending on
     the GD version. GD 2 will get truecolor while < 2 will get palette based.
    */
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

    /*!
     Copies the image from \a $image as the current image object.
    */
    function clone( &$image )
    {
        $this->cloneImage( $image->imageObject(), $image->width(), $image->height(),
                           $image->isTruecolor() );
    }

    /*!
     Clones the image object \a $imageObject with width \a $width, height \a $height
     and truecolor settings \a $useTruecolor.
    */
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

    /*!
     \return the current width of the image or \a false if no size has been set.
    */
    function width()
    {
        return $this->Width;
    }

    /*!
     \return the current height of the image or \a false if no size has been set.
    */
    function height()
    {
        return $this->Height;
    }

    /*!
     Sets the width of the image to \a $w.
    */
    function setWidth( $w )
    {
        $this->Width = $w;
    }

    /*!
     Sets the height of the image to \a $h.
    */
    function setHeight( $h )
    {
        $this->Height = $h;
    }

    /*!
     Sets the path, file and type of the stored file.
     These settings will be used by load().
    */
    function setStoredFile( $file, $path, $type )
    {
        $this->StoredFile = $file;
        $this->StoredPath = $path;
        $this->StoredType = $type;
    }

    /*!
     Sets the current font object to \a $font.
    */
    function setFont( $font )
    {
        $this->Font = $font;
    }

    /*!
     \return the current font object or \c null if not font object has been set.
    */
    function font()
    {
        return $this->Font;
    }

    /*!
     Copies the image \a $imageObject with size \a $sourceWidth and \a $sourceHeight
     and position \a $sourceX and \a $sourceY onto the destination image \a $destinationImageObject
     at position \a $destinationX and \a $destinationY.
    */
    function copyImage( $destinationImageObject, $imageObject,
                        $destinationX, $destinationY,
                        $sourceWidth, $sourceHeight, $sourceX = 0, $sourceY = 0 )
    {
        ImageCopy( $destinationImageObject, $imageObject,
                   $destinationX, $destinationY,
                   $sourceX, $sourceY, $sourceWidth, $sourceHeight );
    }

    /*!
     Merges the image \a $imageObject with size \a $sourceWidth and \a $sourceHeight
     and position \a $sourceX and \a $sourceY with the destination image \a $destinationImageObject
     at position \a $destinationX and \a $destinationY.
     The merged image is placed on the \a $destinationImageObject.
     \param $transparency determines how transparent the source image is. 0 is the same as copyImage
            and 100 is the same is no copy is made.
    */
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

    /*!
     Alpha blends the image \a $imageObject with size \a $sourceWidth and \a $sourceHeight
     and position \a $sourceX and \a $sourceY onto the destination image \a $destinationImageObject
     at position \a $destinationX and \a $destinationY.
     \note This required GD2 and uses color 0 (black) for blending.
    */
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

    /*!
     Clears the image object with color \a $color.
     If \a $color is not specified it will use the first color set.
    */
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

    /*!
     Allocates the color \a $red, \a $green and \a $blue with name \a $name and returns it.
     Will return the palette index for palette based images and the color value for true color.
    */
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
        $this->Palette[$name] = $color;
        $this->PaletteIndex[] = $color;
        return $color;
    }

    /*!
     \return the color for the name \a $name.
    */
    function color( $name )
    {
        if ( !isset( $this->Palette[$name] ) )
        {
            eZDebug::writeError( 'Color not defined: ' . $name, 'eZImageInterface::color' );
            return null;
        }
        return $this->Palette[$name];
    }

    /*!
     \return the color used for text drawing.
    */
    function textColor()
    {
        return $this->TextColor;
    }

    /*!
     Sets the color used for text drawing to \a $textColor.
    */
    function setTextColor( $textColor )
    {
        $this->TextColor = $textColor;
    }

    /*!
     Draws the text \a $text using the font \a $font and color \a $textColor
     at position \a $x and \a $y with angle \a $angle.
     If \a $imageObject is specified it will use that for drawing instead of
     the current image.
    */
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

/*!
 Global function for eZImageInterface. It will be called at the end of the script execution
 and will cleanup all images left behind.
*/
function eZGlobalImageCleanupFunction()
{
    eZImageInterface::cleanupRegisteredImages();
}

?>
