<?php

class eZDBInterfaceTest extends ezpDatabaseTestCase
{
    public function setUp()
    {
        $this->db = $this->sharedFixture;
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

            // Unicity test
            array( array( 1, 2, 3, 3, 3, 4 ), 'testrow', false, true, 'int',
                'testrow IN ( 1, 2, 3, 4 )' ),
        );
    }

    /**
     * @dataProvider providerForTestImplodeWithTypeCast
     **/
    public function testImplodeWithTypeCast( $array, $glue, $type, $expected )
    {
        $ret = $this->db->implodeWithTypeCast( $glue, $array, $type );
        $this->assertEquals( $expected, $ret );
    }

    /**
     * @dataProvider providerForTestGenerateSQLINStatement
     **/
    public function testGenerateSQLINStatement( $elements, $columnName, $not, $unique, $type, $expected )
    {
        var_dump( func_get_args() );
        $ret = $this->db->generateSQLINStatement(
            $elements, $columnName, $not, $unique, $type );
        $this->assertEquals( $expected, $ret );
    }

    /**
    * @var eZDBInterface
    **/
    protected $db;
}

?>