<?php
/**
 * File containing the eZMySQLDBFKTest class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

/**
 * This test case handles removal of foreign keys using eZMySQLDB and eZMySQLiDB
 */
class eZMySQLDBFKTest extends ezpDatabaseTestCase
{
    protected function setUp()
    {
        if ( !in_array( ezpTestRunner::dsn()->dbsyntax, array( 'mysql', 'mysqli' ) ) )
            self::markTestSkipped( "Not running MySQL nor MysQLi, skipping" );

        parent::setUp();
    }

    /**
     * Test listing of foreign keys in a MySQLi DB (eZMySQLiDB::relationList( eZMySQLiDB::RELATION_FOREIGN_KEY )
     */
    public function testForeignKeyRelations()
    {
        $db = eZDB::instance();
        // This create 3 tables and 3 FK
        $this->createFKTables();

        // Count them
        self::assertEquals( 3, $db->relationCount( eZMySQLDB::RELATION_FOREIGN_KEY ), "Wrong relation count returned by relationCount( FK )" );

        // List them
        $foreignKeys = $db->relationList( eZMySQLDB::RELATION_FOREIGN_KEY );

        self::assertEquals( 3, count( $foreignKeys ), "Wrong FK count returned by relationList( FK )" );

        self::assertEquals( $foreignKeys[0]['table'], 'eztestfk_2',  'Table eztestfk_2 not found' );
        self::assertEquals( $foreignKeys[0]['fk'], 'eztestfk_2_fk1', 'FK eztestfk_2.eztestfk_2_fk1 not found' );

        self::assertEquals( $foreignKeys[1]['table'], 'eztestfk_2',  'Table eztestfk_2 not found' );
        self::assertEquals( $foreignKeys[1]['fk'], 'eztestfk_2_fk2', 'FK eztestfk_2.eztestfk_2_fk1 not found' );

        self::assertEquals( $foreignKeys[2]['table'], 'eztestfk_3',  'Table eztestfk_2 not found' );
        self::assertEquals( $foreignKeys[2]['fk'], 'eztestfk_3_fk1', 'FK eztestfk_1.eztestfk_1_fk1 not found' );

        // remove them
        foreach( $foreignKeys as $foreignKey )
        {
            $db->removeRelation( $foreignKey, eZMySQLDB::RELATION_FOREIGN_KEY );
        }
        self::assertEquals( 0, $db->relationCount( eZMySQLDB::RELATION_FOREIGN_KEY ) );
    }

    /**
     * Helper function that creates 3 tables, two of them having foreign keys towards the first one
     */
    public function createFKTables()
    {
        $db = eZDB::instance();
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
