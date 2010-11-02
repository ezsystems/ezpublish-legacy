<?php

class ezpPackageInfo
{
    /**
     * The package object to query information from.
     * @var eZPackage
     */
    public $package;

    public function __construct( $package )
    {
        if ( !$package instanceof eZPackage )
        {
            throw new Exception( __CLASS__ . " requires a valid package object of type eZPackage" );
        }
        $this->Package = $package;
    }

    /**
     * Returns information on package.
     */
    function query( $class, $data = false )
    {
        switch ( $class )
        {
            case 'thumbnail':
                if ( !is_array( $fileList = $this->Package->fileList( 'default' ) ) )
                    $fileList = array();
                foreach ( $fileList as $file )
                {
                    $fileType = $file["type"];
                    if ( $fileType == 'thumbnail' )
                    {
                        return $this->Package->fileItemPath( $file, 'default' );
                    }
                }
                return false;

            case 'filepath':
                $variableName = $data;
                $fileList = $this->Package->fileList( 'default' );
                foreach ( $fileList as $file )
                {
                    $fileIdentifier = $file["variable-name"];
                    if ( $fileIdentifier == $variableName )
                    {
                        return $this->Package->fileItemPath( $file, 'default' );
                    }
                }
                throw new Exception( "No filepath found for variable $variableName in package " . $this->Package->name );

            case 'fileitempath':
                $fileItem = $data;
                return $this->Package->fileItemPath( $fileItem, 'default' );

            case 'documentpath':
                $documentName = $data;
                $documentList = $this->Package->documents;
                foreach ( array_keys( $documentList ) as $key )
                {
                    $document =& $documentList[$key];
                    $name = $document["name"];
                    if ( $name == $documentName )
                    {
                        return $this->Package->path() . '/' . eZPackage::documentDirectory() . '/' . $document['name'];
                    }
                }
                throw new Exception( "No documentpath found for document $documentName in package " . $this->Package->name );

            case 'dirpath':
                return $this->Package->currentRepositoryPath() . "/" . $this->Package->name;

            default:
                throw new Exception( "Unknown package class name: '$class'" );
        }
    }
}

?>
