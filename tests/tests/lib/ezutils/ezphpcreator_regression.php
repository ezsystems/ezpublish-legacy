<?php
/**
 * File containing the eZPHPCreatorRegression class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */
class eZPHPCreatorRegression extends ezpDatabaseTestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( 'eZPHPCreator Regression Tests' );
    }

    /**
     * Setup an environment for this test.
     *
     */
    public function setUp()
    {
        parent::setUp();

        // Create a class 1
        $class1 = new ezpClass( 'Test 1', 'test_1', '<name>' );
        $class1->add( 'Name', 'name' );
        $class1->add( 'Relations', 'relations', 'ezobjectrelationlist' );
        $class1->store();

        // Generate class*.php cache files;
        eZContentClass::classIDByIdentifier( 'test_1' );

        // Wait 3 seconds to get the proper timestamps for test
        sleep( 3 );

        // Create a class 2
        // It marks class-identifier-cache as expired because a new class has been created
        $class2 = new ezpClass( 'Test 2', 'test_2', '<name>' );
        $class2->add( 'Name', 'name' );
        $class2->add( 'Relations', 'relations', 'ezobjectrelationlist' );
        $class2->store();
    }

    /**
     * Test scenario for issue #014897: Object/class name pattern and cache issues [patch]
     *
     * @result $phpCache->canRestore() returns true
     * @expected $phpCache->canRestore() should return false
     *
     * @link http://issues.ez.no/14897
     */
    public function testCanRestore()
    {
        $db = eZDB::instance();
        $dbName = md5( $db->DB );

        $cacheDir = eZSys::cacheDirectory();

        $phpCache = new eZPHPCreator( $cacheDir,
                                      'classidentifiers_' . $dbName . '.php',
                                      '',
                                      array( 'clustering' => 'classidentifiers' ) );

        eZExpiryHandler::registerShutdownFunction();
        $handler = eZExpiryHandler::instance();
        $expiryTime = 0;
        if ( $handler->hasTimestamp( 'class-identifier-cache' ) )
        {
            $expiryTime = $handler->timestamp( 'class-identifier-cache' );
        }

        $this->assertFalse( $phpCache->canRestore( $expiryTime ) );
    }
}

?>