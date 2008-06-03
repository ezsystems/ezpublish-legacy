<?php
//
// Definition of eZImageHandler class
//
// Created on: <16-Oct-2003 13:58:34 amos>
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

/*! \file ezimagehandler.php
*/

/*!
  \class eZImageHandler ezimagehandler.php
  \ingroup eZImage
  \brief The class eZImageHandler does

*/

class eZImageHandler
{
    const KEEP_SUFFIX = 1;
    const REPLACE_SUFFIX = 2;
    const PREPEND_TAG_REPLACE_SUFFIX = 3;

    /*!
     Initializes the image handler with data sent from the inheriting class.
     \param $handlerName The name of the current handler
     \param $isEnabled A boolean which tells whether the handler can be used or not
     \param $outputRewriteType Defines how output filenames are rewritten
     \param $supportedInputMIMETypes A list of MIME-Types the handler supports as input or \c false if no type as defined
     \param $supportedOutputMIMETypes A list of MIME-Types the handler supports as output or \c false if no type as defined
     \param $conversionRules A list of conversion rules specific for this handler, is combined with the global rules
     \param $filters A list of filters this handler supports
     \param $mimeTagMap A mapping table which maps from a MIME-Type to a specific tag, this tag can be used when rewriting the filename.
    */
    function eZImageHandler( $handlerName, $isEnabled = true, $outputRewriteType = self::REPLACE_SUFFIX,
                             $supportedInputMIMETypes = false, $supportedOutputMIMETypes,
                             $conversionRules = false, $filters = false, $mimeTagMap = false )
    {
        $this->HandlerName = $handlerName;
        $this->SupportedInputMIMETypes = $supportedInputMIMETypes;
        $this->SupportedOutputMIMETypes = $supportedOutputMIMETypes;
        $this->ConversionRules = $conversionRules;
        $this->OutputRewriteType = $outputRewriteType;
        $this->Filters = $filters;
        $this->FilterMap = array();
        $this->SupportImageFilters = array();
        $this->MIMETagMap = array();
        if ( $mimeTagMap )
            $this->MIMETagMap = $mimeTagMap;
        if ( $this->Filters)
        {
            foreach ( array_keys( $this->Filters ) as $filterKey )
            {
                $filter =& $this->Filters[$filterKey];
                $this->FilterMap[$filter['name']] =& $filter;
                $this->SupportImageFilters[] = $filter['name'];
            }
        }
        $this->SupportImageFilters = array_unique( $this->SupportImageFilters );
        $this->IsEnabled = $isEnabled;
    }

    /*!
     \virtual
     \return whether this handler can be used or not.

     Implementors of image handlers should implement this to return true if
     the image conversion system to be used is available, for instance to check
     for a PHP extension.
     \note default is to return \c true.
    */
    function isAvailable()
    {
        return $this->IsEnabled;
    }

    /*!
     \virtual
     \return the tag for the MIME type named \a $mimeName.
     This is a helper function for some shell based handlers, it will create a
     proper name from the MIME type \a $mimeData.
     \note The default returns the type part of the MIME type.
    */
    function tagForMIMEType( $mimeData )
    {
        $name = $mimeData['name'];
        if ( isset( $this->MIMETagMap[$name] ) )
            return $this->MIMETagMap[$name];
        $position = strpos( $name, '/' );
        if ( $position !== false )
            return substr( $name, $position + 1 );
        else
            return false;
    }

    /*!
     \return an array with the names of the filters this handler can work with.
    */
    function supportedImageFilters()
    {
        return $this->SupportImageFilters;
    }

    /*!
     \return The conversion rules for this handler.
    */
    function conversionRules()
    {
        return $this->ConversionRules;
     }

    /*!
     Parses the filter text \a $filterText which is taken from an INI file
     and returns a filter definition structure for it.
    */
    static function createFilterDefinitionFromINI( $filterText )
    {
        $equalPosition = strpos( $filterText, '=' );
        $filterData = false;
        if ( $equalPosition !== false )
        {
            $filterName = substr( $filterText, 0, $equalPosition );
            $filterData = substr( $filterText, $equalPosition + 1 );
        }
        else
            $filterName = $filterText;
        return array( 'name' => $filterName,
                      'text' => $filterData );
    }

    /*!
     Converts a filter definition and filter data into a text string.
     This string is usually the commandline parameter.
    */
    static function convertFilterToText( $filterDefinition, $filterData )
    {
        $replaceList = array();
        if ( $filterData['data'] )
        {
            $counter = 1;
            foreach ( $filterData['data'] as $data )
            {
                $replaceList['%' . $counter] = $data;
                ++$counter;
            }
        }
        $argumentText = $filterDefinition['text'];
        $text = eZSys::createShellArgument( $argumentText, $replaceList );
        return $text;
    }

    /*!
     Calls convertFilterToText with the correct filter definition and returns the text.
    */
    function textForFilter( $filterData )
    {
        $text = false;
        if ( isset( $this->FilterMap[$filterData['name']] ) )
        {
            $filterDefinition =& $this->FilterMap[$filterData['name']];
            $text = $this->convertFilterToText( $filterDefinition, $filterData );
        }
        return $text;
    }

    /*!
     \virtual
     Rewrites the URL in \a $originalMimeData to become a url for \a $destinationMimeData.
     The type of rewrite is determined by \a $rewriteType which can be one of:
     - self::KEEP_SUFFIX - Does nothing to the url
     - self::REPLACE_SUFFIX - Replaces the suffix or the url
     - self::PREPEND_TAG_REPLACE_SUFFIX - Prepends the tag name and replaces the suffix of the url
     The new url is placed in the \a $destinationMimeData.
    */
    static function rewriteURL( $originalMimeData, &$destinationMimeData, $rewriteType, $aliasName = false )
    {
        $extraText = false;
        if ( $aliasName and
             $aliasName != 'original' )
            $extraText = '_' . $aliasName;
        switch ( $rewriteType )
        {
            case self::KEEP_SUFFIX:
            {
                $destinationMimeData['basename'] = $originalMimeData['basename'];
                $destinationMimeData['filename'] = $originalMimeData['basename'] . $extraText . '.' . $originalMimeData['suffix'];
                $destinationMimeData['dirpath'] = $originalMimeData['dirpath'];
                if ( $originalMimeData['dirpath'] )
                    $destinationMimeData['url'] = $originalMimeData['dirpath'] . '/' . $destinationMimeData['filename'];
                else
                    $destinationMimeData['url'] = $destinationMimeData['filename'];
            } break;
            case self::REPLACE_SUFFIX:
            {
                $destinationMimeData['basename'] = $originalMimeData['basename'];
                $destinationMimeData['filename'] = $originalMimeData['basename'] . $extraText . '.' . $destinationMimeData['suffixes'][0];
                $destinationMimeData['dirpath'] = $originalMimeData['dirpath'];
                if ( $originalMimeData['dirpath'] )
                    $destinationMimeData['url'] = $originalMimeData['dirpath'] . '/' . $destinationMimeData['filename'];
                else
                    $destinationMimeData['url'] = $destinationMimeData['filename'];
            } break;
            case self::PREPEND_TAG_REPLACE_SUFFIX:
            {
                $tagName = $this->tagForMIMEType( $destinationMimeData );
                $destinationMimeData['basename'] = $originalMimeData['basename'];
                $destinationMimeData['filename'] = $originalMimeData['basename'] . $extraText . '.' . $destinationMimeData['suffixes'][0];
                $destinationMimeData['dirpath'] = $originalMimeData['dirpath'];
                if ( $originalMimeData['dirpath'] )
                    $destinationMimeData['url'] = $originalMimeData['dirpath'] . '/' . $destinationMimeData['filename'];
                else
                    $destinationMimeData['url'] = $destinationMimeData['filename'];
                $destinationMimeData['url'] = $tagName . ':' . $destinationMimeData['url'];
            } break;
        }
    }

    /*!
     \virtual
     \return an array with MIME type names that the handler supports as input.
             MIME type names can also be specified with wildcards, for instance
             image/* to say that all image types are supported.
     \note The default implementation returns the MIME types specified in the constructor
    */
    function supportedInputMIMETypes()
    {
        return $this->SupportedInputMIMETypes;
    }

    /*!
     \virtual
     \return an array with MIME type names that the handler supports as output.
             MIME type names can also be specified with wildcards, for instance
             image/* to say that all image types are supported.
     \note The default implementation returns the MIME types specified in the constructor
    */
    function supportedOutputMIMETypes()
    {
        return $this->SupportedOutputMIMETypes;
    }

    /*!
     \static
     Changes the file permissions for image file \a $filepath to the ones
     defines in image.ini. It uses the group FileSettings and variable ImagePermissions.
     \return \c true on success, \c false otherwise
    */
    static function changeFilePermissions( $filepath )
    {
        if ( !file_exists( $filepath ) )
            return false;
        $ini = eZINI::instance( 'image.ini' );
        $perm = $ini->variable( "FileSettings", "ImagePermissions" );
        $success = false;
        $oldmask = umask( 0 );
        if ( !chmod( $filepath, octdec( $perm ) ) )
            eZDebug::writeError( "Chmod $perm $filepath failed",
                                 'eZImageHandler::changeFilePermissions' );
        else
            $success = true;
        umask( $oldmask );
        return $success;
    }

    /*!
     \static
     Creats a regexp string out of the wildcard \a $wilcard and returns it.
    */
    static function wildcardToRegexp( $wildcard, $separatorCharacter = false )
    {
        if ( !$separatorCharacter )
            $separatorCharacter = '#';
        $wildcardArray = preg_split( "#[*]#", $wildcard, -1, PREG_SPLIT_DELIM_CAPTURE );
        $wildcardList = array();
        $i = 0;
        foreach ( $wildcardArray as $wildcardElement )
        {
            if ( ( $i % 2 ) == 1 )
                $wildcardList[] = '(.+)';
            else
                $wildcardList[] = preg_quote( $wildcardElement, $separatorCharacter );
            ++$i;
        }
        $wildcardString = implode( '', $wildcardList );
        return $wildcardString;
    }

    /*!
     \return \c true if the MIME-Type defined in \a $mimeData is supported as output by this handler.
    */
    function isOutputMIMETypeSupported( $mimeData )
    {
        $mimeTypes = $this->supportedOutputMIMETypes();
        $mimeName = $mimeData;
        if ( is_array( $mimeData ) )
            $mimeName = $mimeData['name'];
        foreach ( $mimeTypes as $mimeType )
        {
            if ( strpos( $mimeType, '*' ) !== false )
            {
                $matchString = eZImageHandler::wildcardToRegexp( $mimeType );
                if ( preg_match( "#^" . $matchString . "$#", $mimeName ) )
                {
                    return true;
                }
            }
            else if ( $mimeType == $mimeName )
            {
                return true;
            }
        }
        return false;
    }

    /*!
     \return \c true if the MIME-Type defined in \a $mimeData is supported as input by this handler.
    */
    function isInputMIMETypeSupported( $mimeData )
    {
        $mimeTypes = $this->supportedInputMIMETypes();
        $mimeName = $mimeData;
        if ( is_array( $mimeData ) )
            $mimeName = $mimeData['name'];
        foreach ( $mimeTypes as $mimeType )
        {
            if ( strpos( $mimeType, '*' ) !== false )
            {
                $matchString = eZImageHandler::wildcardToRegexp( $mimeType );
                if ( preg_match( "#^" . $matchString . "$#", $mimeName ) )
                {
                    return true;
                }
            }
            else if ( $mimeType == $mimeName )
            {
                return true;
            }
        }
        return false;
    }

    /*!
     \virtual
     Figures out the output MIME type for the \a $currentMimeData. It goes trough
     all conversion rules for this handler and returns a MIME structure for the
     possible output. The returned structure also contains the correct url for the output.
     \param $wantedMimeData an optional MIME structure for the wanted output type,
                            if a direct conversion rule exists from \a $currentMimeData to \a $wantedMimeData
                            then this is used.
     \param $aliasName An optional name for the current alias being used, if supplied
                       the output MIME structure will have the alias name in the filename.
    */
    function outputMIMEType( &$manager, $currentMimeData, $wantedMimeData, $supportedFormatsOriginal, $aliasName = false )
    {
        if ( is_array( $this->conversionRules() ) )
        {
            $conversionRules = array_merge( $manager->conversionRules(), $this->conversionRules() );
        }
        else
        {
            $conversionRules = $manager->conversionRules();
        }
        $mimeData = false;
        $mimeType = false;
        if ( !$this->isInputMIMETypeSupported( $currentMimeData ) )
            return false;

        if ( $wantedMimeData )
        {
            $conversionRules = array_merge( array( array( 'from' => $currentMimeData['name'],
                                                          'to' => $wantedMimeData['name'] ) ),
                                            $conversionRules );
        }

        $supportedFormats = array();
        foreach ( $supportedFormatsOriginal as $supportedFormat )
        {
            if ( $this->isOutputMIMETypeSupported( $supportedFormat ) )
            {
                $supportedFormats[] = $supportedFormat;
                $conversionRules[] = array( 'from' => $supportedFormat,
                                            'to' => $supportedFormat );
            }
        }

        if ( $wantedMimeData and
             in_array( $wantedMimeData['name'], $supportedFormats ) )
        {
            $mimeType = $wantedMimeData['name'];
        }
        else if ( is_array( $conversionRules ) )
        {
            foreach ( $conversionRules as $rule )
            {
                if ( !$this->isOutputMIMETypeSupported( $rule['to'] ) or
                     !in_array( $rule['to'], $supportedFormats ) )
                    continue;

                $matchRule = false;
                if ( strpos( $rule['from'], '*' ) !== false )
                {
                    $matchString = eZImageHandler::wildcardToRegexp( $rule['from'] );
                    if ( preg_match( "#^" . $matchString . "$#", $currentMimeData['name'] ) )
                    {
                        $matchRule = $rule;
                    }
                }
                else if ( $rule['from'] == $currentMimeData['name'] )
                {
                    $matchRule = $rule;
                }
                if ( $matchRule )
                {
                    if ( $mimeType )
                    {
                        if ( $wantedMimeData and
                             $matchRule['to'] == $wantedMimeData['name'] )
                            $mimeType = $matchRule['to'];
                    }
                    else
                        $mimeType = $matchRule['to'];
                }
            }
        }
        if ( $mimeType )
        {
            $mimeData = eZMimeType::findByName( $mimeType );
            eZImageHandler::rewriteURL( $currentMimeData, $mimeData, $this->outputRewriteType(), $aliasName );
        }
        return $mimeData;
    }

    /*!
     \return the type of filename rewrite this handler uses for output.
    */
    function outputRewriteType()
    {
        return $this->OutputRewriteType;
    }

    /*!
     \return \c true if the filter \a $filter is supported by this handler.
    */
    function isFilterSupported( $filter )
    {
        return isset( $this->FilterMap[$filter['name']] );
    }

    /*!
     \pure
     Converts the source file \a $sourceMimeData to the destination file \a $destinationMimeData.
     If \a $filters is supplied then the filters will be applied to the conversion.
    */
    function convert( $manager, $sourceMimeData, &$destinationMimeData, $filters = false )
    {
    }

}

?>
