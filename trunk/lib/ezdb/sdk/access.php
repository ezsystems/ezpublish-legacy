<?php
include_once( "lib/ezdb/classes/ezdb.php" );

include_once( "lib/ezdb/classes/ezmysqldb.php" );
include_once( "lib/ezdb/classes/ezoracledb.php" );
include_once( "lib/ezdb/classes/ezpostgresqldb.php" );

// Use option: OCI_DEFAULT for execute command to delay execution
//OCIExecute( $stmt, OCI_DEFAULT );

// for retrieve data use (after fetch):



// create table

// $db->query( "DROP TABLE sql_test" );
// $db->query( "CREATE TABLE sql_test( id int, name char(100), description clob )" );

/*
  PostgreSQL:

  CREATE TABLE sql_test( id serial, name varchar(100), description text )

  Oracle code:

DROP TABLE sql_test;
DROP SEQUENCE sql_test_id_seq;
DROP TRIGGER sql_test_seq_ins;

CREATE TABLE sql_test( id int, name char(100), description clob );
CREATE SEQUENCE sql_test_id_seq;

create or replace trigger sql_test_seq_ins
before insert on sql_test
for each row
begin
select sql_test_id_seq.nextval into :new.id from dual;
end;
/

*/

eZDebug::writeNotice( "MySQL test" );

$db = new eZMySQLDB( "localhost", "bf", "bf", "bf" );

$db->query( "DELETE FROM sql_test" );
$db->commit();

$db->query( "INSERT INTO sql_test ( name, description ) VALUES ( 'New test', 'This is the desc..' )" );

$db->query( "INSERT INTO sql_test ( name, description ) VALUES ( 'New test', 'This is the desc2..' )" );

$ret =& $db->arrayQuery( "SELECT * FROM sql_test" );

eZDebug::writeNotice( $ret );
eZDebug::writeNotice( "Last serial id: " .$db->lastSerialID( "sql_test", "id" ) );


///
eZDebug::writeNotice( "PostgreSQL test" );

$db = new eZPostgreSQLDB( "localhost", "dbtest", "dbtest", "dbtest" );

$db->query( "DELETE FROM sql_test" );

$str = $db->escapeString( "Testing escaping'\"" );
$db->query( "INSERT INTO sql_test ( name, description ) VALUES ( 'New test', '$str' )" );
$db->query( "INSERT INTO sql_test ( name, description ) VALUES ( 'New test', 'This is the desc2..' )" );
$db->query( "INSERT INTO sql_test ( name, description ) VALUES ( 'New test', 'This is the desc2..' )" );
$db->query( "INSERT INTO sql_test ( name, description ) VALUES ( 'New test', 'This is the desc2..' )" );

$ret =& $db->arrayQuery( "SELECT * FROM sql_test" );

eZDebug::writeNotice( $ret );
eZDebug::writeNotice( "Last serial id: " . $db->lastSerialID( "sql_test", "id" ) );

///
eZDebug::writeNotice( "Oracle test" );

$db = new eZOracleDB( "oracl", "oracl", "bf", "bf" );


$db->begin();

//$db->query( "DELETE FROM sql_test" );
$db->commit();



//$db->query( "INSERT INTO sql_test ( name, description ) VALUES ( 'New test', 'This is the desc..' )" );

// eZDebug::writeNotice( $db->lastSerialID( "sql_test", "id" ) );

$longDesc = "This is the desc..This is the desc..This is the desc..This is the desc..This is the desc..
This is the desc..This is the desc..This is the desc..This is the desc..This is the desc..
This is the desc..This is the desc..This is the desc..This is the desc..This is the desc..
This is the desc..This is the desc..This is the desc..This is the desc..This is the desc..
This is the desc..This is the desc..This is the desc..This is the desc..This is the desc..
This is the desc..This is the desc..This is the desc..This is the desc..This is the desc..
";

//$db->query( "INSERT INTO sql_test ( name, description ) VALUES ( 'New test', '$longDesc' )" );

// eZDebug::writeNotice( "Last serial ID: " . $db->lastSerialID( "sql_test", "id" ) );


//$db->commit();
$db->rollback();

$ret =& $db->arrayQuery( "SELECT id, name, description, rownum FROM sql_test WHERE id>100", array( "offset" => 0, "limit" => 2 ) );

eZDebug::writeNotice( $ret );



/*
$contentObj = new eZContentObject();

$contentObj->setVariable( "id", 42 );
//$contentObj->setVariable( "name", 42 );

//$contentObj->store();


$article = new eZArticle();
$article->setVariable( "id", 42 );
$article->setVariable( "name", "test article" );
$article->setVariable( "content", "This is the article contents" );
$article->store();

$article->get();


print( $article->variable( "content" ) . "<br>" );

class eZContentObject
{
    function eZContentObject()
    {
        $this->Variables["id"] = array( "Name" => "id",
                                        "Type" => "int",
                                        "IsRequired" => "true",
                                        "Table" => "ezobject_content",
                                        "Value" => "" );

        $this->Variables["name"] = array( "Name" => "name",
                                        "Type" => "char",
                                        "IsRequired" => "true",
                                        "Table" => "ezobject_content",
                                        "Value" => "" );
    }

    function store( )
    {
        $db =& eZDB::instance();
        $db->query( "DELETE FROM ezarticle_article " );
        $db->query( "DELETE FROM ezobject_content " );
        
        print( "<hr> storing<hr>" );

        $insertSQL = array();
        
        // insert into ezobject_content ( id ) values ( '22' );
        foreach ( $this->Variables as $variable )
        {
            if ( $insertSQL[$variable["Table"]]["Count"] >= 1 )
            {                
                $insertSQL[$variable["Table"]]["Names"] .= ", " . $variable["Name"];
                $insertSQL[$variable["Table"]]["Values"] .= ", \"" . $variable["Value"] . "\"";
            }
            else
            {
                $insertSQL[$variable["Table"]]["Names"] .= $variable["Name"];
                $insertSQL[$variable["Table"]]["Values"] .= "\"" . $variable["Value"] . "\"";
            }

            $insertSQL[$variable["Table"]]["Count"] += 1;            
            print( $variable["Name"] . " " );
            print( $variable["Table"] . " " );
            print( $variable["Type"] . " " );
            print( $variable["Value"] . "<br>" );
        }


        while (list ($key, $val) = each ( $insertSQL ) )
        {
            $sql = "INSERT INTO " . $key . " ( " . $val["Names"] . " ) " .
                 " VALUES ( " . $val["Values"] . " ) ";

            $db->query( $sql );
            eZDebug::writeNotice( $sql );
        }        
    }

    function get( )
    {
        $db =& eZDB::instance();
        foreach ( $this->Variables as $variable )
        {
            if ( $getSQL[$variable["Table"]]["Count"] >= 1 )
            {                
                $getSQL[$variable["Table"]]["Names"] .= ", " . $variable["Name"];
                $getSQL[$variable["Table"]]["Values"] .= ", \"" . $variable["Value"] . "\"";
            }
            else
            {
                $getSQL[$variable["Table"]]["Names"] .= $variable["Name"];
                $getSQL[$variable["Table"]]["Values"] .= "\"" . $variable["Value"] . "\"";
            }

            $insertSQL[$variable["Table"]]["Count"] += 1;            
            print( $variable["Name"] . " " );
            print( $variable["Table"] . " " );
            print( $variable["Type"] . " " );
            print( $variable["Value"] . "<br>" );
        }


        $count = 0;
        while (list ($key, $val) = each ( $insertSQL ) )
        {
            if ( $count == 0 )
                $tables .= $key;
            else
                $tables .= ", " . $key;

            $count++;
        }
        $sql = "SELECT * FROM $tables";
        eZDebug::writeNotice( $sql );
        $db->query( $sql );        
    }

    function setVariable( $name, $value )
    {
        if ( is_array( $this->Variables[$name] ) )
        {
            $this->Variables[$name]["Value"] = $value;
        }
        else
        {
            print( "Error: member variable $name does not exist<br>" );
        }
    }


    function variable( $name )
    {
        if ( is_array( $this->Variables[$name] ) )
        {
            return $this->Variables[$name]["Value"];
        }
        else
        {
            print( "Error: member variable $name does not exist<br>" );
        }
    }
    
    var $Variables = array();

}

class eZArticle extends eZContentObject
{
    function eZArticle()
    {
        // special attributes
        $this->Variables["content"] = array( "Name" => "content",                                  
                                             "Type" => "text",
                                             "IsRequired" => "true",
                                             "Table" => "ezarticle_article",
                                             "Value" => "" );

        $this->Variables["content"] = array( "Name" => "content",                                  
                                             "Type" => "text",
                                             "IsRequired" => "true",
                                             "Table" => "ezarticle_article",
                                             "Value" => "" );

        $this->Variables["content"] = array( "Name" => "content",                                  
                                             "Type" => "text",
                                             "IsRequired" => "true",
                                             "Table" => "ezarticle_article",
                                             "Value" => "" );

        $this->Variables["content"] = array( "Name" => "content",                                  
                                             "Type" => "text",
                                             "IsRequired" => "true",
                                             "Table" => "ezarticle_article",
                                             "Value" => "" );

        $this->Variables["content"] = array( "Name" => "content",                                  
                                             "Type" => "text",
                                             "IsRequired" => "true",
                                             "Table" => "ezarticle_article",
                                             "Value" => "" );

        $this->Variables["content_id"] = array( "Name" => "content_id",
                                             "Type" => "int",
                                             "IsRequired" => "true",
                                             "Table" => "ezarticle_article",
                                             "Value" => "" );

        // run main object constructor
        $this->eZContentObject();
    }
}

*/

?>
