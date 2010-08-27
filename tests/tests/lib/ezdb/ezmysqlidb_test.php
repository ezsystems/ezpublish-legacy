<?php
/**
 * File containing the eZMySQLiDBTest class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package tests
 */
class eZMySQLiDBTest extends ezpDatabaseTestCase
{
    function __construct()
    {
        $this->db = $this->sharedFixture;
    }

    protected function setUp()
    {
        if ( ezpTestRunner::dsn()->dbsyntax !== "mysqli" )
            self::markTestSkipped( "Not running MySQLi, skipping" );

        parent::setUp();

        // clean up the database so that the tests are independant from the ezp database
        ezpTestDatabaseHelper::clean( $this->sharedFixture );
    }

    public function testRelationListForeignKey()
    {
        $db = $this->sharedFixture;
        $this->createFKTables();

        // triggers
        $foreignKeys = $db->relationList( eZMySQLiDB::RELATION_FOREIGN_KEY );

        $this->assertEquals( 2, count( $foreignKeys ) );

        $this->assertEquals( $foreignKeys[0]['table'], 'eztestfk_2', "Table eztestfk_2 not found in foreign keys" );
        $this->assertEquals( 2, count( $foreignKeys[0]['keys'] ) );
        $this->assertEquals( 'eztestfk_2_fk1', $foreignKeys[0]['keys'][0] );
        $this->assertEquals( 'eztestfk_2_fk2', $foreignKeys[0]['keys'][1] );

        $this->assertEquals( $foreignKeys[1]['table'], 'eztestfk_3', "Table eztestfk_3 not found in foreign keys" );
        $this->assertEquals( 1, count( $foreignKeys[1]['keys'] ) );
        $this->assertEquals( 'eztestfk_3_fk1', $foreignKeys[1]['keys'][0] );
    }

    /**
    * Helper function that creates 3 tables, two of them having foreign keys towards the first one
    */
    public function createFKTables()
    {
        $db = $this->sharedFixture;
        $db->query( "CREATE TABLE eztestfk_1 (
id1 INT NOT NULL,
id2 INT NOT NULL,
id3 INT NOT NULL,
PRIMARY KEY (id1),
INDEX eztestfk_1_1 (id1),
INDEX eztestfk_1_2 (id2, id3)
) ENGINE=InnoDB" );

        $db->query( "CREATE TABLE eztestfk_2 (
id INT NOT NULL,
fk1 INT NULL,
fk2 INT NULL,
fk3 INT NULL,
PRIMARY KEY (id) ,
CONSTRAINT eztestfk_2_fk1 FOREIGN KEY ( fk1 ) REFERENCES eztestfk_1 (id1) ON DELETE CASCADE,
CONSTRAINT eztestfk_2_fk2 FOREIGN KEY ( fk2, fk3 ) REFERENCES eztestfk_1 (id2, id3) ON DELETE CASCADE
) ENGINE=InnoDB" );

        $db->query( "CREATE TABLE eztestfk_3 (
id INT NOT NULL,
fk1 INT NULL,
PRIMARY KEY (id) ,
CONSTRAINT eztestfk_3_fk1 FOREIGN KEY ( fk1 ) REFERENCES eztestfk_1 (id1) ON DELETE CASCADE
) ENGINE=InnoDB" );
    }
}

?>
