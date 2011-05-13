<?php
/**
 * File containing the eZPackageoperator class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
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
}

?>
