<?php

class eZDBInterfaceTest extends ezpDatabaseTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public static function providerForTestImplodeWithTypeCast()
    {
        return array(
            array( array( 1, 2, 3, 4 ), '-', 'int', '1-2-3-4' ),
            array( array( 'a', 'b', 'c', 'd' ), '-', 'string', 'a-b-c-d' ),

            // non-array, empty string expected
            array( 'notanarray', '-', 'string', '' ),
        );
    }

    public static function providerForTestGenerateSQLINStatement()
    {
        return array(

            // Standard IN queries
            array( array( 1, 2, 3, 4 ), 'testrow', false, false, 'int',
                'testrow IN ( 1, 2, 3, 4 )' ),
            array( array( 'abc', 'def', 'geh' ), 'testrow', false, false, 'string',
                'testrow IN ( abc, def, geh )' ),

            // NOT IN queries
            array( array( 1, 2, 3, 4 ), 'testrow', true, false, 'int',
                'testrow NOT IN ( 1, 2, 3, 4 )' ),
            array( array( 'abc', 'def', 'geh' ), 'testrow', true, false, 'string',
                'testrow NOT IN ( abc, def, geh )' ),

            // Standard IN queries with a real cast (alpha => 0)
            array( array( 1, 2, 'a', 4 ), 'testrow', false, false, 'int',
                'testrow IN ( 1, 2, 0, 4 )' ),
        );
    }

    public static function providerForTestGenerateSQLINStatement2()
    {
        // Unicity test
        return array(
            array( array( 1, 2, 3, 3, 3, 4 ), 'testrow', false, true, 'int',
            'testrow IN ( 1, 2, 3, 4 )' )
        );
    }

    /**
     * @dataProvider providerForTestImplodeWithTypeCast
     **/
    public function testImplodeWithTypeCast( $array, $glue, $type, $expected )
    {
        $db = eZDB::instance();
        $ret = $db->implodeWithTypeCast( $glue, $array, $type );
        $this->assertEquals( $expected, $ret );
    }

    /**
     * @dataProvider providerForTestGenerateSQLINStatement
     **/
    public function testGenerateSQLINStatement( $elements, $columnName, $not, $unique, $type, $expected )
    {
        $db = eZDB::instance();
        $ret = $db->generateSQLINStatement(
            $elements, $columnName, $not, $unique, $type );
        $this->assertEquals( $expected, $ret );
    }

    /**
     * @dataProvider providerForTestGenerateSQLINStatement2
     **/
    public function testGenerateSQLINStatementOracle( $elements, $columnName, $not, $unique, $type, $expected )
    {
        $db = eZDB::instance();

        if ( $db->databaseName() !== "oracle" )
            self::markTestSkipped( "Only implemented in the Oracle driver, skipping" );

        $ret = $db->generateSQLINStatement(
            $elements, $columnName, $not, $unique, $type );
        $this->assertEquals( $expected, $ret );
    }

    /**
    * @var eZDBInterface
    **/
}

?>
