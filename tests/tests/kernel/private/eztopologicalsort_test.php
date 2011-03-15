<?php
/**
 * File containing the ezpTopologicalSortTest class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class ezpTopologicalSortTest extends ezpTestCase
{
    protected $data = array();

    public function setUp()
    {
        $this->data['simple'] = new ezpTopologicalSort(
            array(
                'a' => null,
                'c' => 'b',
                'b' => 'a',
                'd' => 'b',
                'e' => array( 'd', 'c' ) ) );
        $this->data['complex'] = new ezpTopologicalSort(
            array(
                'a' => null,
                'c' => 'b',
                'b' => 'a',
                'd' => 'b',
                'e' => array( 'd', 'c' ),
                'f' => range( 'a', 'e' ),
                'g' => range( 'h', 'k' ),
                'h' => array( 'j', 'k' ),
                'k' => 'j',
                'j' => 'e',
                'l' => 'm',
                'm' => 'n',
                'o' => 'p',
                'p' => 'g' ) );
        $this->data['keep-order'] = new ezpTopologicalSort(
            array_fill_keys(
                range( 'a', 'z' ),
                null ) );
        $this->data['empty'] = new ezpTopologicalSort( array() );
        $this->data['cycle'] = new ezpTopologicalSort(
            array(
                'a' => 'b',
                'b' => 'a' ) );
    }

    public function testSimpleSort()
    {
        $result = $this->data['simple']->sort();

        foreach ( range( 'a', 'e' ) as $letter )
            $$letter = array_search( $letter, $result );

        $this->assertSame( 5, count( $result ) );
        $this->assertLessThan( $c, $b );
        $this->assertLessThan( $b, $a );
        $this->assertLessThan( $d, $b );
        $this->assertLessThan( $e, $d );
        $this->assertLessThan( $e, $c );
    }

    public function testComplexSort()
    {
        $result = $this->data['complex']->sort();

        foreach ( range( 'a', 'p' ) as $letter )
            $$letter = array_search( $letter, $result );

        $this->assertSame( 16, count( $result ) );
        $this->assertLessThan( $c, $b );
        $this->assertLessThan( $b, $a );
        $this->assertLessThan( $d, $b );
        $this->assertLessThan( $e, $d );
        $this->assertLessThan( $e, $c );
        $this->assertLessThan( $f, $a );
        $this->assertLessThan( $f, $b );
        $this->assertLessThan( $f, $c );
        $this->assertLessThan( $f, $d );
        $this->assertLessThan( $f, $e );
        $this->assertLessThan( $g, $h );
        $this->assertLessThan( $g, $i );
        $this->assertLessThan( $g, $j );
        $this->assertLessThan( $g, $k );
        $this->assertLessThan( $k, $j );
        $this->assertLessThan( $j, $e );
        $this->assertLessThan( $l, $m );
        $this->assertLessThan( $m, $n );
        $this->assertLessThan( $o, $p );
        $this->assertLessThan( $p, $g );
    }

    public function testKeepOrderSort()
    {
        $this->assertSame( range( 'a', 'z' ), $this->data['keep-order']->sort() );
    }

    public function testEmptySort()
    {
        $this->assertSame( array(), $this->data['empty']->sort() );
    }

    public function testCycleSort()
    {
        $this->assertFalse( $this->data['cycle']->sort() );
    }
}
?>
