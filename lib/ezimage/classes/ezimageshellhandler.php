<?php
//
// Definition of eZImageShellHandler class
//
// Created on: <16-Oct-2003 14:22:43 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*! \file ezimageshellhandler.php
*/

/*!
  \class eZImageShellHandler ezimageshellhandler.php
  \ingroup eZImage
  \brief The class eZImageShellHandler does

*/

//include_once( 'lib/ezimage/classes/ezimagehandler.php' );

class eZImageShellHandler extends eZImageHandler
{
    /*!
     Constructor
    */
    function eZImageShellHandler( $handlerName, $isEnabled = true, $outputRewriteType = self::REPLACE_SUFFIX,
                                  $supportedInputMIMETypes = false, $supportedOutputMIMETypes = false,
                                  $conversionRules = false, $filters = false, $mimeTagMap = false)
    {
        $this->eZImageHandler( $handlerName, $isEnabled, $outputRewriteType,
                               $supportedInputMIMETypes, $supportedOutputMIMETypes,
                               $conversionRules, $filters, $mimeTagMap );
        $this->Path = false;
        $this->Executable = false;
        $this->PreParameters = false;
        $this->PostParameters = false;
        $this->UseTypeTag = false;
        $this->QualityParameters = false;
        $this->FrameRangeParameters = false;
    }

    /*!
     Creates the shell string and runs the executable.
    */
    function convert( $manager, $sourceMimeData, &$destinationMimeData, $filters = false )
    {
        $argumentList = array();
        $executable = $this->Executable;
        if ( eZSys::osType() == 'win32' and $this->ExecutableWin32 )
            $executable = $this->ExecutableWin32;
        else if ( eZSys::osType() == 'mac' and $this->ExecutableMac )
            $executable = $this->ExecutableMac;
        else if ( eZSys::osType() == 'unix' and $this->ExecutableUnix )
            $executable = $this->ExecutableUnix;
        if ( $this->Path )
            $executable = $this->Path . eZSys::fileSeparator() . $executable;
        if ( eZSys::osType() == 'win32' )
            $executable = "\"$executable\"";

        $argumentList[] = $executable;

        if ( $this->PreParameters )
            $argumentList[] = $this->PreParameters;

        $qualityParameters = $this->QualityParameters;
        $frameRangeParameters = $this->FrameRangeParameters;

        if ( $qualityParameters and
             isset( $qualityParameters[$destinationMimeData['name']] ) )
        {
            $qualityParameter = $qualityParameters[$destinationMimeData['name']];
            $outputQuality = $manager->qualityValue( $destinationMimeData['name'] );
            if ( $outputQuality )
            {
                $qualityArgument = eZSys::createShellArgument( $qualityParameter, array( '%1' => $outputQuality ) );
                $argumentList[] = $qualityArgument;
            }
        }

        if ( $filters !== false )
        {
            foreach ( $filters as $filterData )
            {
                $argumentList[] = $this->textForFilter( $filterData );
            }
        }

        if ( $frameRangeParameters && isset( $frameRangeParameters[$sourceMimeData['name']] ) )
        {
            $sourceMimeData['url'] .= $frameRangeParameters[$sourceMimeData['name']];
        }

        $argumentList[] = eZSys::escapeShellArgument( $sourceMimeData['url'] );

        $destinationURL = $destinationMimeData['url'];
        if ( $this->UseTypeTag )
            $destinationURL = $this->tagForMIMEType( $destinationMimeData ) . $this->UseTypeTag . $destinationURL;
        $argumentList[] = eZSys::escapeShellArgument( $destinationURL );

        if ( $this->PostParameters )
            $argumentList[] = $this->PostParameters;

        $systemString = implode( ' ', $argumentList );
        if ( eZSys::osType() == 'win32' )
            $systemString = "\"$systemString\"";

        system( $systemString, $returnCode );

        if ( $returnCode == 0 )
        {
            if ( !file_exists( $destinationMimeData['url'] ) )
            {
                eZDebug::writeError( 'Unknown destination file: ' . $destinationMimeData['url'] . " when executing '$systemString'", 'eZImageShellHandler(' . $this->HandlerName . ')' );
                return false;
            }
            $this->changeFilePermissions( $destinationMimeData['url'] );
            return true;
        }
        else
        {
            eZDebug::writeWarning( "Failed executing: $systemString, Error code: $returnCode", 'eZImageShellHandler::convert' );
            return false;
        }

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
        //include_once( 'lib/ezutils/classes/ezini.php' );
        $ini = eZINI::instance( $iniFilename );
        if ( !$ini )
        {
            eZDebug::writeError( "Failed loading ini file $iniFilename",
                                 'eZImageShellHandler::createFromINI' );
            return $handler;
        }

        if ( $ini->hasGroup( $iniGroup ) )
        {
            $name = $iniGroup;
            if ( $ini->hasVariable( $iniGroup, 'Name' ) )
                $name = $ini->variable( $iniGroup, 'Name' );
            $inputMimeList = false;
            $outputMimeList = false;
            if ( $ini->hasVariable( $iniGroup, 'InputMIMEList' ) )
                $inputMimeList = $ini->variable( $iniGroup, 'InputMIMEList' );
            if ( $ini->hasVariable( $iniGroup, 'OutputMIMEList' ) )
                $outputMimeList = $ini->variable( $iniGroup, 'OutputMIMEList' );
            $qualityParameters = false;
            if ( $ini->hasVariable( $iniGroup, 'QualityParameters' ) )
            {
                $qualityParametersRaw = $ini->variable( $iniGroup, 'QualityParameters' );
                foreach ( $qualityParametersRaw as $qualityParameterRaw )
                {
                    $elements = explode( ';', $qualityParameterRaw );
                    $qualityParameters[$elements[0]] = $elements[1];
                }
            }

            $frameRangeParameters = false;
            if ( $ini->hasVariable( $iniGroup, 'FrameRangeParameters' ) )
            {
                foreach ( $ini->variable( $iniGroup, 'FrameRangeParameters' ) as $frameRangeParameter )
                {
                    $elements = explode( ';', $frameRangeParameter );
                    $frameRangeParameters[$elements[0]] = $elements[1];
                }
            }

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
            $path = false;
            $executable = false;
            $preParameters = false;
            $postParameters = false;
            if ( $ini->hasVariable( $iniGroup, 'ExecutablePath' ) )
                $path = $ini->variable( $iniGroup, 'ExecutablePath' );
            if ( !$ini->hasVariable( $iniGroup, 'Executable' ) )
            {
                eZDebug::writeError( "No Executable setting for group $iniGroup in ini file $iniFilename",
                                     'eZImageShellHandler::createFromINI' );
                return $handler;
            }
            $executable = $ini->variable( $iniGroup, 'Executable' );
            $executableWin32 = false;
            $executableMac = false;
            $executableUnix = false;
            $ini->assign( $iniGroup, 'ExecutableWin32', $executableWin32 );
            $ini->assign( $iniGroup, 'ExecutableMac', $executableMac );
            $ini->assign( $iniGroup, 'ExecutableUnix', $executableUnix );

            if ( $ini->hasVariable( $iniGroup, 'ExecutablePath' ) )
                $path = $ini->variable( $iniGroup, 'ExecutablePath' );
            if ( $ini->hasVariable( $iniGroup, 'ExecutablePath' ) )
                $path = $ini->variable( $iniGroup, 'ExecutablePath' );
            if ( $ini->hasVariable( $iniGroup, 'PreParameters' ) )
                $preParameters = $ini->variable( $iniGroup, 'PreParameters' );
            if ( $ini->hasVariable( $iniGroup, 'PostParameters' ) )
                $postParameters = $ini->variable( $iniGroup, 'PostParameters' );
            $useTypeTag = false;
            if ( $ini->hasVariable( $iniGroup, 'UseTypeTag' ) )
            {
                $useTypeTag = $ini->variable( $iniGroup, 'UseTypeTag' );
            }
            $outputRewriteType = self::REPLACE_SUFFIX;
            $filters = false;
            if ( $ini->hasVariable( $iniGroup, 'Filters' ) )
            {
                $filterRawList = $ini->variable( $iniGroup, 'Filters' );
                $filters = array();
                foreach ( $filterRawList as $filterRawItem )
                {
                    $filter = eZImageHandler::createFilterDefinitionFromINI( $filterRawItem );
                    $filters[] = $filter;
                }
            }
            $mimeTagMap = false;
            if ( $ini->hasVariable( $iniGroup, 'MIMETagMap' ) )
            {
                $mimeTagMapList = $ini->variable( $iniGroup, 'MIMETagMap' );
                $mimeTagMap = array();
                foreach ( $mimeTagMapList as $mimeTagMapItem )
                {
                    $mimeTagMapArray = explode( ';', $mimeTagMapItem );
                    if ( count( $mimeTagMapArray ) >= 2 )
                        $mimeTagMap[$mimeTagMapArray[0]] = $mimeTagMapArray[1];
                }
            }
            $handler = new eZImageShellHandler( $name, $isEnabled,
                                                $outputRewriteType,
                                                $inputMimeList, $outputMimeList,
                                                $conversionRules, $filters, $mimeTagMap );
            $handler->Path = $path;
            $handler->Executable = $executable;
            $handler->ExecutableWin32 = $executableWin32;
            $handler->ExecutableMac = $executableMac;
            $handler->ExecutableUnix = $executableUnix;
            $handler->PreParameters = $preParameters;
            $handler->PostParameters = $postParameters;
            $handler->UseTypeTag = $useTypeTag;
            $handler->QualityParameters = $qualityParameters;
            $handler->FrameRangeParameters = $frameRangeParameters;
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
