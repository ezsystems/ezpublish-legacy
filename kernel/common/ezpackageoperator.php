<?php
//
// Definition of eZPackageoperator class
//
// Created on: <16-Oct-2003 10:51:28 wy>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
    function &operatorList()
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
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        $package =& $operatorValue;
        $class = $namedParameters['class'];
        switch ( $class )
        {
            case 'filepath':
            {
                if ( get_class( $operatorValue ) == 'ezpackage' )
                {
                    $variableName = $namedParameters['data'];
                    $fileList = $operatorValue->fileList( 'default' );
                    foreach ( array_keys( $fileList ) as $key )
                    {
                        $file =& $fileList[$key];
                        $fileIdentifier = $file["variable-name"];
                        if ( $fileIdentifier == $variableName )
                        {
                            $operatorValue = $operatorValue->fileItemPath( $file, 'default' );
                            return;
                        }
                    }
                }
            } break;

            case 'fileitempath':
            {
                if ( get_class( $operatorValue ) == 'ezpackage' )
                {
                    $fileItem = $namedParameters['data'];
                    $operatorValue = $operatorValue->fileItemPath( $fileItem, 'default' );
                }
            } break;

            case 'documentpath':
            {
                if ( get_class( $package ) == 'ezpackage' )
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
    var $Operators;
};

?>
