<?php
//
// Definition of eZImageShellHandler class
//
// Created on: <16-Oct-2003 14:22:43 amos>
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

/*! \file ezimageshellhandler.php
*/

/*!
  \class eZImageShellHandler ezimageshellhandler.php
  \ingroup eZImage
  \brief The class eZImageShellHandler does

*/

include_once( 'lib/ezimage/classes/ezimagehandler.php' );

class eZImageShellHandler extends eZImageHandler
{
    /*!
     Constructor
    */
    function eZImageShellHandler( $handlerName, $isEnabled = true, $outputRewriteType = EZ_IMAGE_HANDLER_REPLACE_SUFFIX,
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
    }

    /*!
     Creates the shell string and runs the executable.
    */
    function convert( &$manager, $sourceMimeData, &$destinationMimeData, $filters = false )
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
            $executable = $this->Path . '/' . $executable;
        $argumentList[] = $executable;

        if ( $this->PreParameters )
            $argumentList[] = $this->PreParameters;

        $qualityParameters = $this->QualityParameters;

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

        $argumentList[] = eZSys::escapeShellArgument( $sourceMimeData['url'] );

        $destinationURL = $destinationMimeData['url'];
        if ( $this->UseTypeTag )
            $destinationURL = $this->tagForMIMEType( $destinationMimeData ) . $this->UseTypeTag . $destinationURL;
        $argumentList[] = eZSys::escapeShellArgument( $destinationURL );

        if ( $this->PostParameters )
            $argumentList[] = $this->PostParameters;

        $systemString = implode( ' ', $argumentList );
//         print( $systemString . "<br/>" );
        $error = system( $systemString, $returnCode );

        if ( $returnCode == 0 )
        {
            if ( !file_exists( $destinationMimeData['url'] ) )
            {
                eZDebug::writeError( "Unknown destination file: " . $destinationMimeData['url'], "eZImageShellHandler(" . $this->HandlerName . ")" );
                return false;
            }
            $this->changeFilePermissions( $destinationMimeData['url'] );
            return true;
        }
        else
        {
            eZDebug::writeWarning( "Failed executing: $systemString, Error: $error, Return: $returnCode", 'eZImageShellHandler::convert' );
            return false;
        }

    }

    /*!
     Creates a new image handler for shell executable from INI settings.
     The INI settings are read from ini file \a $iniFilename and group \a $iniGroup.
     If \a $iniFilename is not supplied \c image.ini is used.
    */
    function &createFromINI( $iniGroup, $iniFilename = false )
    {
        if ( !$iniFilename )
            $iniFilename = 'image.ini';

        $handler = false;
        include_once( 'lib/ezutils/classes/ezini.php' );
        $ini =& eZINI::instance( $iniFilename );
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
            $outputRewriteType = EZ_IMAGE_HANDLER_REPLACE_SUFFIX;
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
            return $handler;
        }
        return $handler;
    }

    /// \privatesection
    var $Path;
    var $Executable;
    var $PreParameters;
    var $PostParameters;
}

class eZImageShellFactory extends eZImageFactory
{
    /*!
     Initializes the factory with the name \c 'shell'
    */
    function eZImageShellFactory()
    {
        $this->eZImageFactory( 'shell' );
    }

    /*!
     \reimp
     Creates eZImageShellHandler objects and returns them.
    */
    function &produceFromINI( $iniGroup, $iniFilename = false )
    {
        $convertHandler =& eZImageShellHandler::createFromINI( $iniGroup, $iniFilename );
        return $convertHandler;
    }
}

?>
