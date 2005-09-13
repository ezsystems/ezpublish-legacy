<?php
//
// Definition of eZImageAnalyzer class
//
// Created on: <03-Nov-2003 15:19:16 amos>
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

/*! \file ezimageanalyzer.php
*/

/*! \defgroup eZImageAnalyzer Image analysis
    \ingroup eZImage
*/

/*!
  \class eZImageAnalyzer ezimageanalyzer.php
  \ingroup eZImageAnalyzer
  \brief The class eZImageAnalyzer does

*/


define( 'EZ_IMAGE_MODE_INDEXED', 1 );
define( 'EZ_IMAGE_MODE_TRUECOLOR', 2 );

define( 'EZ_IMAGE_TIMER_HUNDRETHS_OF_A_SECOND', 1 );

define( 'EZ_IMAGE_TRANSPARENCY_OPAQUE', 1 );
define( 'EZ_IMAGE_TRANSPARENCY_TRANSPARENT', 2 );
define( 'EZ_IMAGE_TRANSPARENCY_TRANSLUCENT', 3 );

class eZImageAnalyzer
{
    /*!
     Constructor
    */
    function eZImageAnalyzer()
    {
        $this->Name = false;
        $this->MIMEList = array();
    }

    /*!
     \pure
     Process the file based on the MIME data \a $mimeData and returns
     information on the analysis.
     \return \c false if the analysis fails.
    */
    function process( $mimeData, $parameters = array() )
    {
        return false;
    }

    /*!
     Creates an analyzer for the analyzer name \a $analyzerName and returns it.
    */
    function createForMIME( $mimeData )
    {
        $analyzerData =& eZImageAnalyzer::analyzerData();
        $mimeType = $mimeData['name'];
        if ( !isset( $analyzerData['analyzer_map'][$mimeType] ) )
            return false;
        $analyzerName = $analyzerData['analyzer_map'][$mimeType];
        $handlerName = $analyzerData['analyzer'][$analyzerName]['handler'];
        return eZImageAnalyzer::create( $handlerName );
    }

    /*!
     Creates an analyzer for the analyzer name \a $analyzerName and returns it.
    */
    function create( $analyzerName )
    {
        $analyzerData =& eZImageAnalyzer::analyzerData();
        if ( !isset( $analyzerData['handlers'][$analyzerName] ) )
        {
            include_once( 'lib/ezutils/classes/ezextension.php' );
            if ( eZExtension::findExtensionType( array( 'ini-name' => 'image.ini',
                                                        'repository-group' => 'AnalyzerSettings',
                                                        'repository-variable' => 'RepositoryList',
                                                        'extension-group' => 'AnalyzerSettings',
                                                        'extension-variable' => 'ExtensionList',
                                                        'extension-subdir' => 'imageanalyzer',
                                                        'alias-group' => 'AnalyzerSettings',
                                                        'alias-variable' => 'ImageAnalyzerAlias',
                                                        'suffix-name' => 'imageanalyzer.php',
                                                        'type-directory' => false,
                                                        'type' => $analyzerName ),
                                                 $result ) )
            {
                $filepath = $result['found-file-path'];
                include_once( $filepath );
                $className = $result['type'] . 'imageanalyzer';
                $analyzerData['handlers'][$analyzerName] = array( 'classname' => $className,
                                                                  'filepath' => $filepath );
            }
            else
            {
                eZDebug::writeWarning( "Could not locate Image Analyzer for $analyzerName",
                                       'eZImageAnalyzer::instance' );
            }
        }
        if ( isset( $analyzerData['handlers'][$analyzerName] ) )
        {
            $analyzer = $analyzerData['handlers'][$analyzerName];
            $className = $analyzer['classname'];
            if ( class_exists( $className ) )
            {
                return new $className();
            }
            else
            {
                eZDebug::writeWarning( "The Image Analyzer class $className was not found, cannot create analyzer",
                                       'eZImageAnalyzer::instance' );
            }
        }
        return false;
    }

    /*!
     \static
     \private
    */
    function &analyzerData()
    {
        $analyzerData =& $GLOBALS['eZImageAnalyzer'];
        if ( isset( $analyzerData ) )
            return $analyzerData;

        $ini =& eZINI::instance( 'image.ini' );
        $analyzerData['analyzers'] = $ini->variable( 'AnalyzerSettings', 'ImageAnalyzers' );
        $analyzerData['mime_list'] = $ini->variable( 'AnalyzerSettings', 'AnalyzerMIMEList' );
        $analyzerData['analyzer_map'] = array();
        $analyzerData['analyzer'] = array();
        return $analyzerData;
    }

    /*!
     \static
    */
    function readAnalyzerSettingsFromINI()
    {
        $analyzerData =& eZImageAnalyzer::analyzerData();
        $ini =& eZINI::instance( 'image.ini' );
        foreach ( $analyzerData['analyzers'] as $analyzerName )
        {
            $iniGroup = $analyzerName . 'Analyzer';
            if ( $ini->hasGroup( $iniGroup ) )
            {
                $handler = $ini->variable( $iniGroup, 'Handler' );
                $mimeList = $ini->variable( $iniGroup, 'MIMEList' );
                $analyzerData['analyzer'][$analyzerName] = array( 'handler' => $handler,
                                                                  'mime_list' => $mimeList );
                foreach ( $mimeList as $mimeItem )
                {
                    $analyzerData['analyzer_map'][$mimeItem] = $analyzerName;
                }
            }
            else
                eZDebug::writeWarning( "INI group $iniGroup does not exist in image.ini",
                                       'eZImageAnalyzer::readAnalyzerSettingsFromINI' );
        }
    }

    /// \privatesection

    var $MIMEList;
    var $Name;
}

?>
