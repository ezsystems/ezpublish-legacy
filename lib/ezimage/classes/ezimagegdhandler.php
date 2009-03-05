<?php
//
// Definition of eZImageGDHandler class
//
// Created on: <16-Oct-2003 14:22:43 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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
  \class eZImageGDHandler ezimagegdhandler.php
  \ingroup eZImage
  \brief The class eZImageGDHandler does

  A geometry array has the following entries.
  - x - The x position
  - y - The y position
  - width - The width
  - height - The height
*/

class eZImageGDHandler extends eZImageHandler
{
    /*!
     Constructor
    */
    function eZImageGDHandler( $handlerName, $isGloballyEnabled,
                               $outputRewriteType = self::REPLACE_SUFFIX,
                               $conversionRules = false )
    {
        $supportedInputMIMETypes = array();
        $supportedOutputMIMETypes = array();
        $this->InputMap = array();
        $this->OutputMap = array();
        $this->OutputQualityMap = array();
        $isEnabled = false;
        if ( function_exists( "imagetypes" ) )
        {
            $gdFunctions = array( array( 'mimetype' => 'image/gif',
                                         'input' => 'imagecreatefromgif',
                                         'output' => 'imagegif' ),
                                  array( 'mimetype' => 'image/vnd.wap.wbmp',
                                         'input' => 'imagecreatefromwbmp',
                                         'output' => 'imagewbmp' ),
                                  array( 'mimetype' => 'image/png',
                                         'input' => 'imagecreatefrompng',
                                         'output' => 'imagepng' ),
                                  array( 'mimetype' => 'image/jpeg',
                                         'input' => 'imagecreatefromjpeg',
                                         'output' => 'imagejpeg',
                                         'qualityparameter' => true ) );
            foreach ( $gdFunctions as $gdFunction )
            {
                $inputFunction = $gdFunction['input'];
                $outputFunction = $gdFunction['output'];
                if ( function_exists( $inputFunction ) or function_exists( $outputFunction ) )
                {
                    $isEnabled = true;
                    $mimeType = $gdFunction['mimetype'];
                    if ( function_exists( $inputFunction ) )
                    {
                        $supportedInputMIMETypes[] = $mimeType;
                        $this->InputMap[$mimeType] = $inputFunction;
                    }
                    if ( function_exists( $outputFunction ) )
                    {
                        $this->OutputMap[$mimeType] = $outputFunction;
                        $supportedOutputMIMETypes[] = $mimeType;
                    }
                    if ( isset( $gdFunction['qualityparameter'] ) )
                        $this->OutputQualityMap[$mimeType] = $gdFunction['qualityparameter'];
                    else
                        $this->OutputQualityMap[$mimeType] = false;
                }
            }
        }
        if ( !$isGloballyEnabled )
            $isEnabled = false;
        $this->FilterFunctionMap = array( 'geometry/scale' => 'scaleImage',
                                          'geometry/scalewidth' => 'scaleImageWidth',
                                          'geometry/scaleheight' => 'scaleImageHeight',
                                          'geometry/scaledownonly' => 'scaleImageDownOnly',
                                          'geometry/scalewidthdownonly' => 'scaleImageWidthDownOnly',
                                          'geometry/scaleheightdownonly' => 'scaleImageHeightDownOnly',
                                          'geometry/scaleexact' => 'scaleImageExact',
                                          'geometry/scalepercent' => 'scaleImagePercent',
                                          'geometry/crop' => 'cropImage',
                                          'colorspace/gray' => 'setImageColorspaceGray',
                                          'luminance' => 'setImageLuminance',
                                          'luminance/gray' => 'setImageLuminanceNamed',
                                          'luminance/sepia' => 'setImageLuminanceNamed',
                                          'color/monochrome' => 'setImageColorThresholdName',
                                          'border' => 'createImageBorder',
                                          'border/color' => 'setImageBorderColor',
                                          'border/width' => 'setImageBorderWidth' );
        $this->LuminanceColorScales = array( 'luminance/gray' => array( 1.0, 1.0, 1.0 ),
                                             'luminance/sepia' => array( 1.0, 0.89, 0.74 ) );
        $this->ThresholdList = array( 'color/monochrome' => array( array( 'threshold' => 127,
                                                                          'rgb' => array( 0, 0, 0 ) ),
                                                                   array( 'threshold' => 255,
                                                                          'rgb' => array( 255, 255, 255 ) ) ) );

        $filters = array();
        foreach ( $this->FilterFunctionMap as $filterName => $filterFunction )
        {
            $filters[] = array( 'name' => $filterName );
        }
        $this->eZImageHandler( $handlerName, $isEnabled,
                               $outputRewriteType,
                               $supportedInputMIMETypes, $supportedOutputMIMETypes,
                               $conversionRules, $filters );
    }

    /*!
     Creates the shell string and runs the executable.
    */
    function convert( $manager, $sourceMimeData, &$destinationMimeData, $filters = false )
    {
        $sourceMimeType = $sourceMimeData['name'];
        $destinationMimeType = $destinationMimeData['name'];
        if ( !isset( $this->InputMap[$sourceMimeType] ) )
        {
            eZDebug::writeError( "MIME-Type $sourceMimeType is not supported as input by GD converter",
                                 'eZImageGDHandler::convert' );
            return false;
        }
        if ( !isset( $this->OutputMap[$destinationMimeType] ) )
        {
            eZDebug::writeError( "MIME-Type $destinationMimeType is not supported as output by GD converter",
                                 'eZImageGDHandler::convert' );
            return false;
        }

        $inputFunction = $this->InputMap[$sourceMimeType];
        $outputFunction = $this->OutputMap[$destinationMimeType];
        $outputQualityParameter = $this->OutputQualityMap[$destinationMimeType];
        $inputFile = $sourceMimeData['url'];
        $outputFile = $destinationMimeData['url'];

        if ( !file_exists( $inputFile ) )
        {
            eZDebug::writeError( "Source image $inputFile does not exist, cannot convert",
                                 'eZImageGDHandler::convert' );
            return false;
        }

        $currentImage = $inputFunction( $inputFile );

        $filterVariables = array( 'border-color' => array( 127, 127, 127 ),
                                  'border-size' => array( 0, 0 ) );

        if ( $filters !== false )
        {
            foreach ( $filters as $filterData )
            {
                $filterName = $filterData['name'];
                if ( isset( $this->FilterFunctionMap[$filterName] ) )
                {
                    $filterFunction = $this->FilterFunctionMap[$filterName];
                    $filteredImage = $this->$filterFunction( $currentImage, $filterData, $filterVariables, $sourceMimeData, $destinationMimeData );
                    if ( $filteredImage !== false )
                    {
                        if ( $filteredImage != $currentImage )
                        {
                            ImageDestroy( $currentImage );
                        }
                        $currentImage = $filteredImage;
                    }
                }
            }
        }

        $outputImage = $currentImage;

        if ( $outputImage )
        {
            $outputQuality = false;
            if ( $outputQualityParameter )
                $outputQuality = $manager->qualityValue( $destinationMimeType );
            if ( $outputQuality !== false )
            {
                $returnCode = $outputFunction( $outputImage, $outputFile, $outputQuality );
            }
            else
            {
                $returnCode = $outputFunction( $outputImage, $outputFile );
            }

            ImageDestroy( $outputImage );
        }
        else
            $returnCode = false;

        if ( $returnCode )
        {
            if ( !file_exists( $destinationMimeData['url'] ) )
            {
                eZDebug::writeError( "Unknown destination file: " . $destinationMimeData['url'], "eZImageGDHandler(" . $this->HandlerName . ")" );
                return false;
            }
            $this->changeFilePermissions( $destinationMimeData['url'] );
            return true;
        }
        else
        {
            eZDebug::writeWarning( "Failed converting $inputFile ($sourceMimeType) to $outputFile ($destinationMimeType)", 'eZImageGDHandler::convert' );
            return false;
        }
    }

    static function setImageBorderColor( &$imageObject, $filterData, &$filterVariables, $sourceMimeData, $destinationMimeData )
    {
        $filterVariables['border-color'] = $filterData['data'];
        return false;
    }

    function setImageBorderWidth( $imageObject, $filterData, &$filterVariables, $sourceMimeData, $destinationMimeData )
    {
        $filterVariables['border-size'] = array( $filterData['data'][0], $filterData['data'][0] );
        return $this->createImageBorder( $imageObject, $filterData, $filterVariables, $sourceMimeData, $destinationMimeData );
    }

    function setImageBorder( $imageObject, $filterData, &$filterVariables, $sourceMimeData, $destinationMimeData )
    {
        $filterVariables['border-size'] = array( $filterData['data'][0], $filterData['data'][1] );
        return $this->createImageBorder( $imageObject, $filterData, $filterVariables, $sourceMimeData, $destinationMimeData );
    }

    static function createImageBorder( $imageObject, $filterData, &$filterVariables, $sourceMimeData, $destinationMimeData )
    {
        $width = ImageSX( $imageObject );
        $height = ImageSY( $imageObject );
        $borderWidth = $filterVariables['border-size'][0];
        $borderHeight = $filterVariables['border-size'][1];
        $borderColor = $filterVariables['border-color'];
        $newWidth = $width + $borderWidth*2;
        $newHeight = $height + $borderHeight*2;

        $temporaryImageObject = $this->imageCopy( $imageObject,
                                                  $this->createGeometry( $newWidth, $newHeight, $borderWidth, $borderHeight ),
                                                  $this->createGeometry( $width, $height, 0, 0 ),
                                                  $sourceMimeData, $destinationMimeData );
        $color = ImageColorAllocate( $temporaryImageObject, $borderColor[0], $borderColor[1], $borderColor[2] );
        ImageFilledRectangle( $temporaryImageObject, 0, 0, $newWidth, $borderHeight, $color );
        ImageFilledRectangle( $temporaryImageObject, $newWidth - $borderWidth, 0, $newWidth, $newHeight, $color );
        ImageFilledRectangle( $temporaryImageObject, 0, $newHeight - $borderHeight, $newWidth, $newHeight, $color );
        ImageFilledRectangle( $temporaryImageObject, 0, 0, $borderWidth, $newHeight, $color );
        return $temporaryImageObject;
    }

    /*!
     Converts the image to grayscale.
    */
    function setImageColorspaceGray( $imageObject, $filterData, &$filterVariables, $sourceMimeData, $destinationMimeData )
    {
        $colorScale = array( 1.0, 1.0, 1.0 );
        return $this->setImageLuminanceColorScale( $imageObject, $filterData, $sourceMimeData, $destinationMimeData,
                                                   $colorScale );
    }

    /*!
     Changes the colors of the image based on the luminance.
     The new scale for the colors are taken from the filter parameters, the parameters must contain three values.
    */
    function setImageLuminance( $imageObject, $filterData, &$filterVariables, $sourceMimeData, $destinationMimeData )
    {
        $colorScale = $filterData['data'];
        return $this->setImageLuminanceColorScale( $imageObject, $filterData, $sourceMimeData, $destinationMimeData,
                                                   $colorScale );
    }

    /*!
     Changes the colors of the image based on the luminance.
     The new scale for the colors are based on the name of the filters.
    */
    function setImageLuminanceNamed( $imageObject, $filterData, &$filterVariables, $sourceMimeData, $destinationMimeData )
    {
        if ( isset( $this->LuminanceColorScales[$filterData['name']] ) )
        {
            $colorScale = $this->LuminanceColorScales[$filterData['name']];
        }
        else
        {
            eZDebug::writeDebug( "No luminance scale named " . $filterData['name'] . ", applying gray scales",
                                 'eZImageGDHandler::setImageLuminanceName' );
            $colorScale = array( 1.0, 1.0, 1.0 );
        }
        return $this->setImageLuminanceColorScale( $imageObject, $filterData, $sourceMimeData, $destinationMimeData,
                                                   $colorScale );
    }

    /*!
     Changes the colors of the image based on the luminance.
     \param $colorScale is an array with three float elements in range 0 to 1 that define the new color scale.
    */
    function setImageLuminanceColorScale( $imageObject, $filterData, $sourceMimeData, $destinationMimeData,
                                          $colorScale )
    {
        $white = ImageColorAllocate( $imageObject, 255, 255, 255 );
        $black = ImageColorAllocate( $imageObject, 0, 0, 0 );

        $rmod = $colorScale[0];
        $gmod = $colorScale[1];
        $bmod = $colorScale[2];

        $width = ImageSX( $imageObject );
        $height = ImageSY( $imageObject );
        for ( $y = 0; $y < $height; ++$y )
        {
            for ( $x = 0; $x < $width; ++$x )
            {
                $rgb = ImageColorAt( $imageObject, $x, $y );

                $r = ( $rgb >> 16 ) & 0xff;
                $g = ( $rgb >> 8 ) & 0x0ff;
                $b = ( $rgb ) & 0x0ff;

                $luminance = ( $r * 0.3 ) + ( $g * 0.59 ) + ( $b * 0.11 );

                $r = $luminance * $rmod;
                $g = $luminance * $gmod;
                $b = $luminance * $bmod;

                $color = ImageColorAllocate( $imageObject, $r, $g, $b );

                ImageSetPixel( $imageObject, $x, $y, $color );
            }
        }
        return $imageObject;
    }

    /*!
     Changes the colors of the image based on threshold values.
     The threshold values are based on the filter name.
    */
    function setImageColorThresholdName( $imageObject, $filterData, &$filterVariables, $sourceMimeData, $destinationMimeData )
    {
        if ( isset( $this->ThresholdList[$filterData['name']] ) )
        {
            $thresholdList = $this->ThresholdList[$filterData['name']];
        }
        else
        {
            eZDebug::writeDebug( "No threshold values named " . $filterData['name'] . ", applying black/white (monochrome) threshold",
                                 'eZImageGDHandler::setImageLuminanceName' );
            $thresholdList = array( array( 'threshold' => 127,
                                           'rgb' => array( 0, 0, 0 ) ),
                                    array( 'threshold' => 255,
                                           'rgb' => array( 255, 255, 255 ) ) );
        }
        return $this->setImageColorThreshold( $imageObject, $filterData, $sourceMimeData, $destinationMimeData,
                                              $thresholdList );
    }

    /*!
     Changes the colors of the image based on threshold values. The luminance will be calculated and if it is
     in a threshold range it will use the specified color for the range.
    */
    static function setImageColorThreshold( $imageObject, $filterData, $sourceMimeData, $destinationMimeData,
                                            $thresholdList )
    {
        foreach ( array_keys( $thresholdList ) as $thresholdKey )
        {
            $thresholdList[$thresholdKey]['color'] = ImageColorAllocate( $imageObject, $thresholdItem['rgb'][0], $thresholdItem['rgb'][1], $thresholdItem['rgb'][2] );
        }
        $defaultColor = $thresholdList[count( $thresholdList ) - 1]['color'];

        $width = ImageSX( $imageObject );
        $height = ImageSY( $imageObject );
        for ( $y = 0; $y < $height; ++$y )
        {
            for ( $x = 0; $x < $width; ++$x )
            {
                $rgb = ImageColorAt( $imageObject, $x, $y );

                $r = ( $rgb >> 16 ) & 0xff;
                $g = ( $rgb >> 8 ) & 0x0ff;
                $b = ( $rgb ) & 0x0ff;

                $luminance = ( $r * 0.3 ) + ( $g * 0.59 ) + ( $b * 0.11 );

                $color = false;
                foreach ( $thresholdList as $thresholdItem )
                {
                    if ( $luminance <= $thresholdItem['threshold'] )
                    {
                        $color = $thresholdItem['color'];
                        break;
                    }
                }
                if ( $color === false )
                    $color = $defaultColor;

                ImageSetPixel( $imageObject, $x, $y, $color );
            }
        }
        return $imageObject;
    }

    /*!
      Crops a portion of the image from the filter parameters.
    */
    function cropImage( $imageObject, $filterData, &$filterVariables, $sourceMimeData, $destinationMimeData )
    {
        $width = $filterData['data'][0];
        $height = $filterData['data'][1];
        $x = $filterData['data'][2];
        $y = $filterData['data'][3];
        $imageWidth = ImageSX( $imageObject );
        $imageHeight = ImageSY( $imageObject );
        $width = max( min( $width, $imageWidth - $x ), 0 );
        $height = max( min( $height, $imageHeight - $y ), 0 );
        $destinationGeometry = $this->createGeometry( $width, $height, 0, 0 );
        $geometry = $this->createGeometry( $width, $height, $x, $y );
        return $this->imageCopy( $imageObject,
                                 $destinationGeometry,
                                 $geometry,
                                 $sourceMimeData, $destinationMimeData );
    }

    /*!
     Scales the image \a $imageObject to the size specified in \a $filterData with aspect ration maintained.
     This means that image will not be exactly the image size.
     \sa scaleImageExact
    */
    function scaleImage( $imageObject, $filterData, &$filterVariables, $sourceMimeData, $destinationMimeData )
    {
        $geometry = $this->calculateScaledAspectGeometry( ImageSX( $imageObject ), ImageSY( $imageObject ),
                                                          $filterData['data'][0], $filterData['data'][1], true );
        return $this->scaleImageCopy( $imageObject,
                                      $geometry,
                                      $sourceMimeData, $destinationMimeData );
    }

    /*!
     Scales the image \a $imageObject to the size specified in \a $filterData with aspect ration maintained.
     This means that image will not be exactly the image size.
     \note The image will not be scaled if the source size is smaller than the destination size.
     \sa scaleImageExact
    */
    function scaleImageDownOnly( $imageObject, $filterData, &$filterVariables, $sourceMimeData, $destinationMimeData )
    {
        $geometry = $this->calculateScaledAspectGeometry( ImageSX( $imageObject ), ImageSY( $imageObject ),
                                                          $filterData['data'][0], $filterData['data'][1], false );
        return $this->scaleImageCopy( $imageObject,
                                      $geometry,
                                      $sourceMimeData, $destinationMimeData );
    }

    /*!
     Scales the image \a $imageObject to the size specified in \a $filterData with aspect ration maintained.
     This means that image will not be exactly the image size.
     \sa scaleImageExact
    */
    function scaleImageWidth( $imageObject, $filterData, &$filterVariables, $sourceMimeData, $destinationMimeData )
    {
        $geometry = $this->calculateFixedWidthAspectGeometry( ImageSX( $imageObject ), ImageSY( $imageObject ),
                                                              $filterData['data'][0], true );
        return $this->scaleImageCopy( $imageObject,
                                      $geometry,
                                      $sourceMimeData, $destinationMimeData );
    }

    /*!
     Scales the image \a $imageObject to the size specified in \a $filterData with aspect ration maintained.
     This means that image will not be exactly the image size.
     \sa scaleImageExact
    */
    function scaleImageHeight( $imageObject, $filterData, &$filterVariables, $sourceMimeData, $destinationMimeData )
    {
        $geometry = $this->calculateFixedHeightAspectGeometry( ImageSX( $imageObject ), ImageSY( $imageObject ),
                                                               $filterData['data'][0], true );
        return $this->scaleImageCopy( $imageObject,
                                      $geometry,
                                      $sourceMimeData, $destinationMimeData );
    }

    /*!
     Scales the image \a $imageObject to the size specified in \a $filterData with aspect ration maintained.
     This means that image will not be exactly the image size.
     \note The image will not be scaled if the source size is smaller than the destination size.
     \sa scaleImageExact
    */
    function scaleImageWidthDownOnly( $imageObject, $filterData, &$filterVariables, $sourceMimeData, $destinationMimeData )
    {
        $geometry = $this->calculateFixedWidthAspectGeometry( ImageSX( $imageObject ), ImageSY( $imageObject ),
                                                              $filterData['data'][0], false );
        return $this->scaleImageCopy( $imageObject,
                                      $geometry,
                                      $sourceMimeData, $destinationMimeData );
    }

    /*!
     Scales the image \a $imageObject to the size specified in \a $filterData with aspect ration maintained.
     This means that image will not be exactly the image size.
     \note The image will not be scaled if the source size is smaller than the destination size.
     \sa scaleImageExact
    */
    function scaleImageHeightDownOnly( $imageObject, $filterData, &$filterVariables, $sourceMimeData, $destinationMimeData )
    {
        $geometry = $this->calculateFixedHeightAspectGeometry( ImageSX( $imageObject ), ImageSY( $imageObject ),
                                                               $filterData['data'][0], false );
        return $this->scaleImageCopy( $imageObject,
                                      $geometry,
                                      $sourceMimeData, $destinationMimeData );
    }

    /*!
     Scales the image \a $imageObject to the size specified in \a $filterData without caring about aspect ratio.
    */
    function scaleImageExact( $imageObject, $filterData, &$filterVariables, $sourceMimeData, $destinationMimeData )
    {
        return $this->scaleImageCopy( $imageObject,
                                      $this->createGeometry( $filterData['data'][0], $filterData['data'][1] ),
                                      $sourceMimeData, $destinationMimeData );
    }

    /*!
     Scales the image \a $imageObject to the size specified in \a $filterData with aspect ratio maintained.
    */
    function scaleImagePercent( $imageObject, $filterData, &$filterVariables, $sourceMimeData, $destinationMimeData )
    {
        $geometry = $this->calculateScaledPercentAspectGeometry( ImageSX( $imageObject ), ImageSY( $imageObject ),
                                                                 $filterData['data'][0] / 100.0, $filterData['data'][1] / 100.0, true );
        return $this->scaleImageCopy( $imageObject,
                                      $geometry,
                                      $sourceMimeData, $destinationMimeData );
    }

    /*!
     Calculates the geometry for the scaled image while maintaining the aspect ratio.
     \param $allowUpScale If this is true images will be scaled up as well, if not they will keep their source size.
     \return a geometry array.
     \sa createGeometry
    */
    function calculateScaledAspectGeometry( $sourceWidth, $sourceHeight,
                                            $destinationWidth, $destinationHeight,
                                            $allowUpScale )
    {
        $widthScale = $sourceWidth / $destinationWidth;
        $heightScale = $sourceHeight / $destinationHeight;

        $scale = $heightScale;
        if ( $heightScale != $widthScale )
            $scale = max( $heightScale, $widthScale );

        if ( $scale < 1.0 and !$allowUpScale )
        {
            $destinationWidth = $sourceWidth;
            $destinationHeight = $sourceHeight;
        }
        else
        {
            $destinationWidth = (int) ( $sourceWidth / $scale );
            $destinationHeight = (int) ( $sourceHeight / $scale );
        }
        return $this->createGeometry( $destinationWidth, $destinationHeight );
    }

    /*!
     Calculates the geometry for the scaled image in terms of percent while maintaining the aspect ratio.
     \param $allowUpScale If this is true images will be scaled up as well, if not they will keep their source size.
     \note Percentage must be given as a float value, e.g. 50% is 0.5 and 200% is 2.0
     \return a geometry array.
     \sa createGeometry
    */
    function calculateScaledPercentAspectGeometry( $sourceWidth, $sourceHeight,
                                                   $destinationWidthPercent, $destinationHeightPercent,
                                                   $allowUpScale )
    {
        $destinationWidth = $sourceWidth * $destinationWidthPercent;
        $destinationHeight = $sourceHeight * $destinationHeightPercent;
        return $this->calculateScaledAspectGeometry( $sourceWidth, $sourceHeight,
                                                     $destinationWidth, $destinationHeight,
                                                     $allowUpScale );
    }

    /*!
     Calculates the geometry for the scaled image with a fixed width while maintaining the aspect ratio.
     \param $allowUpScale If this is true images will be scaled up as well, if not they will keep their source size.
     \return a geometry array.
     \sa createGeometry
    */
    function calculateFixedWidthAspectGeometry( $sourceWidth, $sourceHeight,
                                                $destinationWidth,
                                                $allowUpScale )
    {
        $scale = $sourceWidth / $destinationWidth;

        if ( $scale < 1.0 and !$allowUpScale )
        {
            $destinationWidth = $sourceWidth;
            $destinationHeight = $sourceHeight;
        }
        else
        {
            $destinationWidth = (int) ( $sourceWidth / $scale );
            $destinationHeight = (int) ( $sourceHeight / $scale );
        }
        return $this->createGeometry( $destinationWidth, $destinationHeight );
    }

    /*!
     Calculates the geometry for the scaled image with a fixed height while maintaining the aspect ratio.
     \param $allowUpScale If this is true images will be scaled up as well, if not they will keep their source size.
     \return a geometry array.
     \sa createGeometry
    */
    function calculateFixedHeightAspectGeometry( $sourceWidth, $sourceHeight,
                                                 $destinationHeight,
                                                 $allowUpScale )
    {
        $scale = $sourceHeight / $destinationHeight;

        if ( $scale < 1.0 and !$allowUpScale )
        {
            $destinationWidth = $sourceWidth;
            $destinationHeight = $sourceHeight;
        }
        else
        {
            $destinationWidth = (int) ( $sourceWidth / $scale );
            $destinationHeight = (int) ( $sourceHeight / $scale );
        }
        return $this->createGeometry( $destinationWidth, $destinationHeight );
    }

    /*!
     Scales the image \a $imageObject to the size specified in \a $filterData.
    */
    static function scaleImageCopy( $imageObject,
                                    $geometry,
                                    $sourceMimeData, $destinationMimeData )
    {
        $destinationWidth = $geometry['width'];
        $destinationHeight = $geometry['height'];
        $sourceWidth = ImageSX( $imageObject );
        $sourceHeight = ImageSY( $imageObject );

        $temporaryImageObject = eZImageGDHandler::imageCreate( $destinationWidth, $destinationHeight, eZImageGDHandler::isImageTrueColor( $imageObject, $sourceMimeData ) );
        ImageCopyResampled( $temporaryImageObject, $imageObject,
                            0, 0, 0, 0,
                            $destinationWidth, $destinationHeight, $sourceWidth, $sourceHeight );
        return $temporaryImageObject;
    }

    /*!
      Copies a portion of the source image \a $imageObject to a new image.
    */
    static function imageCopy( $imageObject, $destinationGeometry, $sourceGeometry,
                               $sourceMimeData, $destinationMimeData )
    {
        $destinationWidth = $destinationGeometry['width'];
        $destinationHeight = $destinationGeometry['height'];

        $temporaryImageObject = eZImageGDHandler::imageCreate( $destinationWidth, $destinationHeight, eZImageGDHandler::isImageTrueColor( $imageObject, $sourceMimeData ) );
        ImageCopy( $temporaryImageObject, $imageObject,
                   $destinationGeometry['x'], $destinationGeometry['y'],
                   $sourceGeometry['x'], $sourceGeometry['y'], $sourceGeometry['width'], $sourceGeometry['height'] );
        return $temporaryImageObject;
    }

    /*!
     \static
     \return \c true if the image object \a $imageObject is in true color format.
    */
    static function isImageTrueColor( &$imageObject, $mimeData )
    {
        if ( eZSys::isPHPVersionSufficient( array( 4, 3, 2 ) ) )
            return ImageIsTrueColor( $imageObject );
        $nonTrueColorMimeTypes = array( 'image/gif' );
        if ( in_array( $sourceMimeData['name'], $nonTrueColorMimeTypes ) )
            return false;
        return true;
    }

    /*!
     Creates a new GD image and returns it.
     \param $isTrueColor determines if a true color image is created, if false an indexed image is created.
    */
    static function imageCreate( $width, $height, $isTrueColor = true )
    {
        if ( $isTrueColor )
            return ImageCreateTrueColor( $width, $height );
        else
            return ImageCreate( $width, $height );
    }

    /*!
     Creates a geometry array with width \a $width, height \a $height, x position \a $x and y position \a $y and returns it.
    */
    static function createGeometry( $width, $height, $x = 0, $y = 0 )
    {
        return array( 'x' => $x,
                      'y' => $y,
                      'width' => $width,
                      'height' => $height );
    }

    /*!
     Creates a new image handler for shell executable from INI settings.
     The INI settings are read from ini file \a $iniFilename and group \a $iniGroup.
     If \a $iniFilename is not supplied \c image.ini is used.
    */
    static function createFromINI( $iniGroup, $iniFilename = false )
    {
        if ( !$iniFilename )
            $iniFilename = 'image.ini';

        $handler = false;
        $ini = eZINI::instance( $iniFilename );
        if ( !$ini )
        {
            eZDebug::writeError( "Failed loading ini file $iniFilename",
                                 'eZImageGDHandler::createFromINI' );
            return $handler;
        }

        if ( $ini->hasGroup( $iniGroup ) )
        {
            $name = $iniGroup;
            if ( $ini->hasVariable( $iniGroup, 'Name' ) )
                $name = $ini->variable( $iniGroup, 'Name' );
            $conversionRules = false;
            if ( $ini->hasVariable( $iniGroup, 'ConversionRules' ) )
            {
                $conversionRules = array();
                $rules = $ini->variable( $iniGroup, 'ConversionRules' );
                foreach ( $rules as $ruleString )
                {
                    $ruleItems = explode( ';', $ruleString );
                    if ( count( $ruleItems ) >= 2 )
                    {
                        $conversionRules[] = array( 'from' => $ruleItems[0],
                                                    'to' => $ruleItems[1] );
                    }
                }
            }
            $isEnabled = $ini->variable( $iniGroup, 'IsEnabled' ) == 'true';
            $outputRewriteType = self::REPLACE_SUFFIX;
            $handler = new eZImageGDHandler( $name, $isEnabled,
                                             $outputRewriteType,
                                             $conversionRules );
            return $handler;
        }
        return $handler;
    }

    /// \privatesection
    public $Path;
    public $Executable;
    public $PreParameters;
    public $PostParameters;
}

?>
