<?php
/**
 * File containing the eZPostgreSQLDBTest class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZPostgreSQLDBTest extends ezpDatabaseTestCase
{
    protected $insertDefaultData = false;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZPostgreSQLDB Unit Tests" );
    }

    protected function setUp()
    {
        if ( ezpTestRunner::dsn()->dbsyntax !== "postgresql" )
            self::markTestSkipped( "Not running PostgresSQL, skipping" );

        parent::setUp();

        $this->sharedFixture = ezpTestDatabaseHelper::create( ezpTestRunner::dsn() );

        ezpTestDatabaseHelper::clean( $this->sharedFixture );
    }

    public static function tearDownAfterClass()
    {
        // We need to clean up after this test case, since database will not
        // be reset by the suite it initialisation has happened once, see pull req. #234
        // Next: Suite which always prepares the db for you.

        $db = ezpTestDatabaseHelper::create( ezpTestRunner::dsn() );
        ezpTestDatabaseHelper::clean( $db );
        ezpTestDatabaseHelper::insertDefaultData( $db );
    }

    public function testRelationCounts()
    {
        $db = $this->sharedFixture;
        $db->query( "CREATE TABLE a ( name varchar(40) )" );
        $db->query( "CREATE TABLE b ( name varchar(40) )" );
        $db->query( "CREATE TABLE c ( name varchar(40) )" );

        $relationCount = $db->relationCounts( eZDBInterface::RELATION_TABLE_BIT );
        self::assertEquals( 3, (int) $relationCount );
    }

    public function testRelationCount()
    {
        $db = $this->sharedFixture;
        $db->query( "CREATE TABLE a ( name varchar(40) )" );
        $db->query( "CREATE TABLE b ( name varchar(40) )" );
        $db->query( "CREATE TABLE c ( name varchar(40) )" );

        $relationCount = $db->relationCount( eZDBInterface::RELATION_TABLE );
        self::assertEquals( 3, (int) $relationCount );
    }

    public function testRelationList()
    {
        $db = $this->sharedFixture;
        $db->query( "CREATE TABLE a ( name varchar(40) )" );
        $db->query( "CREATE TABLE b ( name varchar(40) )" );
        $db->query( "CREATE TABLE c ( name varchar(40) )" );

        $relationList = $db->relationList( eZDBInterface::RELATION_TABLE );
        $relationArray = array( "a", "b", "c" );
        self::assertEquals( $relationArray, $relationList );
    }
}

?>
