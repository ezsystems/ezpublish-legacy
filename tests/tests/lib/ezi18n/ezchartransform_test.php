<?php
/**
 * File containing the eZCharTransformTests class
 *
 * @package tests
 */

class eZCharTransformTests extends ezpTestCase
{

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZCharTransformTests" );
    }

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testCommandUrlCleanupMultipleSpecialCharsAtEnd()
    {
        $objectName = 'test."';
        $transformed = eZCharTransform::commandUrlCleanup( $objectName );
        $this->assertEquals( $transformed, 'test' );

        $objectName = 'te.st."';
        $transformed = eZCharTransform::commandUrlCleanup( $objectName );
        $this->assertEquals( $transformed, 'te.st' );

        $objectName = '.test!"';
        $transformed = eZCharTransform::commandUrlCleanup( $objectName );
        $this->assertEquals( $transformed, 'test' );
    }

    public function testCommandUrlCleanupIRI()
    {
        $objectName = '.täst."';
        $transformed = eZCharTransform::commandUrlCleanupIRI( $objectName );
        $this->assertEquals( 'täst', $transformed );

        $objectName = '.test!"';
        $transformed = eZCharTransform::commandUrlCleanupIRI( $objectName );
        $this->assertEquals( 'test', $transformed );
    }
}
