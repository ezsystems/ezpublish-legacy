<?php
//
// Definition of eZPackageoperator class
//
// Created on: <16-Oct-2003 10:51:28 wy>
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

/*! \file ezpackageoperator.php
*/

/*!
  \class eZPackageOperator ezpackageoperator.php
  \brief The class eZPackageOperator does

*/

class eZPackageOperator
{
    /*!
     Constructor
    */
    function eZPackageOperator( $name = 'ezpackage' )
    {
        $this->Operators = array( $name );
    }

    /*!
     Returns the operators in this class.
    */
    function operatorList()
    {
        return $this->Operators;
    }

    /*!
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return array( 'class' => array( 'type' => 'string',
                                        'required' => true,
                                        'default' => false ),
                      'data' => array( 'type' => 'string',
                                       'required' => false,
                                       'default' => false ) );
    }

    /*!
     \reimp
    */
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        $package = $operatorValue;
        $class = $namedParameters['class'];
        switch ( $class )
        {
            case 'thumbnail':
            {
                if ( $operatorValue instanceof eZPackage )
                {
                    if ( !is_array( $fileList = $operatorValue->fileList( 'default' ) ) )
                        $fileList = array();
                    foreach ( $fileList as $file )
                    {
                        $fileType = $file["type"];
                        if ( $fileType == 'thumbnail' )
                        {
                            $operatorValue = $operatorValue->fileItemPath( $file, 'default' );
                            return;
                        }
                    }
                    $operatorValue = false;
                }
            } break;

            case 'filepath':
            {
                if ( $operatorValue instanceof eZPackage )
                {
                    $variableName = $namedParameters['data'];
                    $fileList = $operatorValue->fileList( 'default' );
                    foreach ( $fileList as $file )
                    {
                        $fileIdentifier = $file["variable-name"];
                        if ( $fileIdentifier == $variableName )
                        {
                            $operatorValue = $operatorValue->fileItemPath( $file, 'default' );
                            return;
                        }
                    }
                    $tpl->error( $operatorName,
                                 "No filepath found for variable $variableName in package " . $package->attribute( 'name' ) );
                    $operatorValue = false;
                }
            } break;

            case 'fileitempath':
            {
                if ( $operatorValue instanceof eZPackage )
                {
                    $fileItem = $namedParameters['data'];
                    $operatorValue = $operatorValue->fileItemPath( $fileItem, 'default' );
                }
            } break;

            case 'documentpath':
            {
                if ( $package instanceof eZPackage )
                {
                    $documentName = $namedParameters['data'];
                    $documentList = $package->attribute( 'documents' );
                    foreach ( array_keys( $documentList ) as $key )
                    {
                        $document =& $documentList[$key];
                        $name = $document["name"];
                        if ( $name == $documentName )
                        {
                            $documentFilePath = $package->path() . '/' . eZPackage::documentDirectory() . '/' . $document['name'];
                            $operatorValue = $documentFilePath;
                            return;
                        }
                    }
                    $tpl->error( $operatorName,
                                 "No documentpath found for document $documentName in package " . $package->attribute( 'name' ) );
                    $operatorValue = false;
                }
            } break;

            case 'dirpath':
            {
                $dirPath = $operatorValue->currentRepositoryPath() . "/" . $operatorValue->attribute( 'name' );
                $operatorValue = $dirPath;
            } break;

            default:
                $tpl->error( $operatorName, "Unknown package operator name: '$class'" );
            break;
        }
    }
    /// \privatesection
    public $Operators;
};

?>
