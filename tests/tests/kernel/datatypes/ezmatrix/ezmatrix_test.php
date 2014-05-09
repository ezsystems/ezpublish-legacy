<?php
/**
 * File containing the eZMatrixTypeTest class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 */
class eZMatrixTypeTest extends eZDatatypeAbstractTest
{
    public function __construct( $name = null, array $data = array(), $dataName = '' )
    {
        $this->setDatatype( 'ezmatrix' );
        $this->setDataSet( 'default', $this->defaultDataSet() );

        parent::__construct( $name, $data, $dataName );
        $this->setName( "eZMatrix Datatype Tests" );
    }

    private function defaultDataSet()
    {
        $dataSet = new ezpDatatypeTestDataSet();
        $dataSet->fromString = 'col1|col2&valA1|valA2&valB1|valB2';
        $dataSet->dataText = <<<EOF
<?xml version="1.0" encoding="utf-8"?>
<ezmatrix><name></name><columns number="2"><column num="0" id="col_0">Col_0</column><column num="1" id="col_1">Col_1</column></columns><rows number="0"/></ezmatrix>

EOF;
        $dataSet->content = unserialize( 'O:8:"eZMatrix":5:{s:4:"Name";s:0:"";s:6:"Matrix";a:3:{s:4:"rows";a:1:{s:10:"sequential";a:3:{i:0;a:3:{s:7:"columns";a:2:{i:0;s:4:"col1";i:1;s:4:"col2";}s:10:"identifier";s:5:"row_1";s:4:"name";s:5:"Row_1";}i:1;a:3:{s:7:"columns";a:2:{i:0;s:5:"valA1";i:1;s:5:"valA2";}s:10:"identifier";s:5:"row_2";s:4:"name";s:5:"Row_2";}i:2;a:3:{s:7:"columns";a:2:{i:0;s:5:"valB1";i:1;s:5:"valB2";}s:10:"identifier";s:5:"row_3";s:4:"name";s:5:"Row_3";}}}s:7:"columns";a:2:{s:10:"sequential";a:2:{i:0;a:4:{s:10:"identifier";s:5:"col_0";s:5:"index";i:0;s:4:"name";s:5:"Col_0";s:4:"rows";a:0:{}}i:1;a:4:{s:10:"identifier";s:5:"col_1";s:5:"index";i:1;s:4:"name";s:5:"Col_1";s:4:"rows";a:0:{}}}s:2:"id";a:2:{s:5:"col_0";a:4:{s:10:"identifier";s:5:"col_0";s:5:"index";i:0;s:4:"name";s:5:"Col_0";s:4:"rows";a:0:{}}s:5:"col_1";a:4:{s:10:"identifier";s:5:"col_1";s:5:"index";i:1;s:4:"name";s:5:"Col_1";s:4:"rows";a:0:{}}}}s:5:"cells";a:0:{}}s:10:"NumColumns";s:1:"2";s:7:"NumRows";i:3;s:5:"Cells";a:6:{i:0;s:4:"col1";i:1;s:4:"col2";i:2;s:5:"valA1";i:3;s:5:"valA2";i:4;s:5:"valB1";i:5;s:5:"valB2";}}' );
        return $dataSet;
    }

    /**
     * Tests the attribute's content
     *
     * Since XML might be rendered differently across version of PHP, libxml and options, uses a safer
     * way to compare xml text
     */
    public function testDataText()
    {
        $this->createObject( $this->dataSet() );
        $doc1 = new DOMDocument();
        $doc1->loadXML( $this->attribute->attribute( 'data_text' ) );
        $doc2 = new DOMDocument();
        $doc2->loadXML( $this->dataSet()->dataText );
        self::assertEquals( $doc1->saveXML(), $doc2->saveXML() );
        $this->destroyObject();
    }
}
?>
