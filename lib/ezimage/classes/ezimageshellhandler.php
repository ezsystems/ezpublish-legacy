<?php
//
// Definition of eZImageShellHandler class
//
// Created on: <16-Oct-2003 14:22:43 amos>
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
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
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
  \brief The class eZImageShellHandler does

*/

include_once( 'lib/ezimage/classes/ezimagehandler.php' );

class eZImageShellHandler extends eZImageHandler
{
    /*!
     Constructor
    */
    function eZImageShellHandler( $handlerName, $outputRewriteType = EZ_IMAGE_HANDLER_REPLACE_SUFFIX,
                                  $supportedMIMETypes = false, $conversionRules = false, $filters = false, $mimeTagMap = false )
    {
        $this->eZImageHandler( $handlerName, $outputRewriteType,
                               $supportedMIMETypes, $conversionRules, $filters, $mimeTagMap );
        $this->Path = false;
        $this->Executable = false;
        $this->PreParameters = false;
        $this->PostParameters = false;
        $this->UseTypeTag = false;
    }

    /*!
     Creates the shell string and runs the executable.
    */
    function convert( $sourceMimeData, &$destinationMimeData, $filters = false )
    {
        $argumentList = array();
        $executable = $this->Executable;
        if ( $this->Path )
            $executable = $this->Path . '/' . $executable;
        $argumentList[] = $executable;

        if ( $this->PreParameters )
            $argumentList[] = $this->PreParameters;

        if ( $filters !== false )
        {
            foreach ( $filters as $filterData )
            {
                $argumentList[] = $this->textForFilter( $filterData );
            }
        }

        $argumentList[] = $this->escapeShellArgument( $sourceMimeData['url'] );

        $destinationURL = $destinationMimeData['url'];
        if ( $this->UseTypeTag )
            $destinationURL = $this->tagForMIMEType( $destinationMimeData ) . $this->UseTypeTag . $destinationURL;
        $argumentList[] = $this->escapeShellArgument( $destinationURL );

        if ( $this->PostParameters )
            $argumentList[] = $this->PostParameters;

        $systemString = implode( ' ', $argumentList );
        print( $systemString . "<br/>" );
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
        include_once( 'lib/ezutils/classes/ezini.php' );
        $ini =& eZINI::instance( $iniFilename );
        if ( !$ini )
        {
            eZDebug::writeError( "Failed loading ini file $iniFilename",
                                 'eZImageShellHandler::createFromINI' );
            return false;
        }
        $handler = false;
        if ( $ini->hasGroup( $iniGroup ) )
        {
            $name = $iniGroup;
            if ( $ini->hasVariable( $iniGroup, 'Name' ) )
                $name = $ini->variable( $iniGroup, 'Name' );
            $mimeList = false;
            if ( $ini->hasVariable( $iniGroup, 'MIMEList' ) )
                $mimeList = $ini->variable( $iniGroup, 'MIMEList' );
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
                        $conversionRules[] = array( 'from_mimetype' => $ruleItems[0],
                                                    'to_mimetype' => $ruleItems[1] );
                    }
                }
            }
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
                return false;
            }
            $executable = $ini->variable( $iniGroup, 'Executable' );
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
            $handler = new eZImageShellHandler( $name, $outputRewriteType,
                                                $mimeList, $conversionRules, $filters, $mimeTagMap );
            $handler->Path = $path;
            $handler->Executable = $executable;
            $handler->PreParameters = $preParameters;
            $handler->PostParameters = $postParameters;
            $handler->UseTypeTag = $useTypeTag;
            return $handler;
        }
        return false;
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
