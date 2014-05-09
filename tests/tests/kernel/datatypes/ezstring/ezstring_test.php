<?php
/**
 * File containing the eZStringTypeTest class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
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
