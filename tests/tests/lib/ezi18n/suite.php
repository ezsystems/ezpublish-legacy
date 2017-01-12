<?php
/**
 * File containing the eZFileTestSuite class
 *
 * @package tests
 */

class eZCharTransformTestSuite extends ezpTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZFile Test Suite" );
        $this->addTestSuite( 'eZCharTransformTests' );
    }

    public static function suite()
    {
        return new self();
    }

}
