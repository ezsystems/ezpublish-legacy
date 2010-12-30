<?php
/**
* File containing the eZDatatypeTest class
*
* @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
* @license http://ez.no/licenses/gnu_gpl GNU GPLv2
* @package tests
*/

/**
* Base class for all datatype implementations
*/
class eZStringTypeTest extends eZDatatypeAbstractTest
{
    public function __construct( $name = null, array $data = array(), $dataName = '' )
    {
        $this->setDatatype( 'ezstring' );
        $this->setDataSet( 'default', $this->defaultDataSet() );

        parent::__construct( $name, $data, $dataName );
        $this->setName( "eZString Datatype Tests" );
    }

    private function defaultDataSet()
    {
        $dataSet = new ezpDatatypeTestDataSet();
        $dataSet->fromString = 'this is a string';
        $dataSet->dataText = 'this is a string';
        $dataSet->content = 'this is a string';
        return $dataSet;
    }
}

?>
