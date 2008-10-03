<?php

class eZPostgreSQLDBTest extends ezpDatabaseTestCase
{
    protected $insertDefaultData = false;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZPostgreSQLDB Unit Tests" );
    }

    public static function suite()
    {
        return new ezpTestSuite( __CLASS__ );
    }

    protected function setUp()
    {
        parent::setUp();

        if ( $this->sharedFixture->databaseName() !== "postgresql" )
            self::markTestSkipped( "Not running PostgresSQL, skipping" );

        ezpTestDatabaseHelper::clean( $this->sharedFixture );
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