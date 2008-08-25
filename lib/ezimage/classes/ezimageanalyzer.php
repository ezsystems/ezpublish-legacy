<?php
//
// Definition of eZImageAnalyzer class
//
// Created on: <03-Nov-2003 15:19:16 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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

class eZImageAnalyzer
{
    const MODE_INDEXED = 1;
    const MODE_TRUECOLOR = 2;

    const TIMER_HUNDRETHS_OF_A_SECOND = 1;

    const TRANSPARENCY_OPAQUE = 1;
    const TRANSPARENCY_TRANSPARENT = 2;
    const TRANSPARENCY_TRANSLUCENT = 3;

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
    static function createForMIME( $mimeData )
    {
        $analyzerData = eZImageAnalyzer::analyzerData();
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
    static function create( $analyzerName )
    {
        $analyzerData = eZImageAnalyzer::analyzerData();
        if ( !isset( $analyzerData['handlers'][$analyzerName] ) )
        {
            //include_once( 'lib/ezutils/classes/ezextension.php' );
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
    static function analyzerData()
    {
        $analyzerData =& $GLOBALS['eZImageAnalyzer'];
        if ( isset( $analyzerData ) )
            return $analyzerData;

        $ini = eZINI::instance( 'image.ini' );
        $analyzerData['analyzers'] = $ini->variable( 'AnalyzerSettings', 'ImageAnalyzers' );
        $analyzerData['mime_list'] = $ini->variable( 'AnalyzerSettings', 'AnalyzerMIMEList' );
        $analyzerData['analyzer_map'] = array();
        $analyzerData['analyzer'] = array();
        return $analyzerData;
    }

    /*!
     \static
    */
    static function readAnalyzerSettingsFromINI()
    {
        $analyzerData = eZImageAnalyzer::analyzerData();
        $ini = eZINI::instance( 'image.ini' );
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

        $GLOBALS['eZImageAnalyzer'] = $analyzerData;
    }

    /// \privatesection

    public $MIMEList;
    public $Name;
}

?>
