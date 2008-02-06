#!/usr/bin/env php
<?php
//
// Created on: <03-Dec-2007 09:51:56 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

require 'autoload.php';



define( 'EZ_CONTENTCLASS_ATTRIBUTE_TMP_TABLE_NAME', 'ezcontentclass_attribute_tmp' );

define( 'EZ_CREATE_CONTENTCLASS_ATTRIBUTE_TMP_TABLE_SQL_MYSQL',
    "
    CREATE TABLE " . EZ_CONTENTCLASS_ATTRIBUTE_TMP_TABLE_NAME . " (
      id int(11) NOT NULL default '0',
      version int(11) NOT NULL default '0',
      is_always_available int(11) NOT NULL default '0',
      language_locale varchar(20) NOT NULL default '',
      name varchar(255) NOT NULL default '',
      PRIMARY KEY  (id,version,language_locale)
    )" );

define( 'EZ_CREATE_CONTENTCLASS_ATTRIBUTE_TMP_TABLE_SQL_POSTGRESQL',
    "
    CREATE TABLE " . EZ_CONTENTCLASS_ATTRIBUTE_TMP_TABLE_NAME . " (
        id integer DEFAULT 0 NOT NULL,
        version integer DEFAULT 0 NOT NULL,
        is_always_available  DEFAULT 0 NOT NULL,
        language_locale character varying(20) DEFAULT ''::character varying NOT NULL,
        name character varying(255) DEFAULT ''::character varying NOT NULL
    )" );

define( 'EZ_DROP_CONTENTCLASS_ATTRIBUTE_TMP_TABLE_SQL',
    "DROP TABLE " . EZ_CONTENTCLASS_ATTRIBUTE_TMP_TABLE_NAME );


/**************************************************************
* 'cli->output' wrappers                                      *
***************************************************************/
function showError( $message, $addEOL = true, $bailOut = true )
{
    global $cli;
    global $script;

    $cli->output( $cli->stylize( 'error', "Error: " .  $message ), $addEOL );

    if( $bailOut )
    {
        $script->shutdown( 1 );
    }
}

function showWarning( $message, $addEOL = true )
{
    global $cli;
    $cli->output( $cli->stylize( 'warning', "Warning: " . $message ), $addEOL );
}

function showNotice( $message, $addEOL = true )
{
    global $cli;
    $cli->output( $cli->stylize( 'notice', "Notice: " ) .  $message, $addEOL );
}

function showMessage( $message, $addEOL = true )
{
    global $cli;
    $cli->output( $cli->stylize( 'blue', $message ), $addEOL );
}

function showMessage2( $message, $addEOL = true )
{
    global $cli;
    $cli->output( $cli->stylize( 'red', $message ), $addEOL );
}

function showMessage3( $message, $addEOL = true )
{
    global $cli;
    $cli->output( $message, $addEOL );
}


/*!
 prompt user to choose what to do next
*/
function eZGetUserInput( $prompt )
{
    $stdin = fopen( "php://stdin", "r+" );

    fwrite( $stdin, $prompt );

    $userInput = fgets( $stdin );
    $userInput = trim( $userInput, "\n" );

    fclose( $stdin );

    return $userInput;
}

function eZExecuteShellCommand( $command, $errMessage = '', $retry = true )
{
    $err = 0;
    do
    {
        system( $command, $err );
        if ( $err )
        {
            if ( $errMessage )
            {
                showMessage2( $errMessage );
            }

            if ( $retry )
            {
                $action = false;
                while ( !$action )
                {
                    $action = eZGetUserInput( "Retry? [y/n]: ");
                    if( strpos( $action, 'n' ) === 0 )
                    {
                        showError( "Aborting..." );
                    }
                    else if ( strpos( $action, 'y' ) !== 0 )
                    {
                        $action = false;
                    }
                }
            }
            else
            {
                showError( "Aborting..." );
            }
        }
        else
        {
            $retry = false;
        }
    }
    while ( $retry );

    return $err;
}

/**************************************************************
* helper functions                                            *
***************************************************************/
function createContentClassAttributeTempTable()
{
    $db = eZDB::instance();
    $sql = 'EZ_CREATE_CONTENTCLASS_ATTRIBUTE_TMP_TABLE_SQL_' . strtoupper( $db->databaseName() );
    $sql = constant( $sql );
    $db->query( $sql );
}

function dropContentClassAttributeTempTable()
{
    $db = eZDB::instance();
    $sql = EZ_DROP_CONTENTCLASS_ATTRIBUTE_TMP_TABLE_SQL;
    $db->query( $sql );
}

function unserializeContentClassAttriubteNames()
{
    $db = eZDB::instance();

    $attributeName = new eZContentClassAttributeNameList();

    $limit = 100;
    $offset = 0;
    $selectSQL = "SELECT id, version, serialized_name_list FROM ezcontentclass_attribute ORDER BY id, version LIMIT $limit";

    while ( $result = $db->arrayQuery( $selectSQL . " OFFSET $offset" ) )
    {
        foreach ( $result as $row )
        {
            showMessage3( "id: '" . $row['id'] . "' version: '" . $row['version'] . "'" );

            $attributeName->initFromSerializedList( $row['serialized_name_list'] );

            $nameList = $attributeName->cleanNameList();
            $alwaysAvailableLocale = $attributeName->alwaysAvailableLanguageLocale();

            $insertSQL = 'INSERT INTO ' . EZ_CONTENTCLASS_ATTRIBUTE_TMP_TABLE_NAME . '(id, version, is_always_available, language_locale, name) VALUES (' .
                "{$row['id']}, {$row['version']}";

            foreach ( $nameList as $locale => $name )
            {
                $isAlwaysAvailable = ( $locale == $alwaysAvailableLocale ) ? 1 : 0;

                $sql = $insertSQL . ", $isAlwaysAvailable, '" . $db->escapeString( $locale) . "', '" . $db->escapeString( $name ) . "')";
                $db->query( $sql );
            }
        }
        $offset += $limit;
    }
}


function serializeContentClassNames()
{
    $selectSQL = "SELECT contentclass_id as id,\n" .
        "     contentclass_version as version,\n" .
        "     language_id as is_always_available,\n" .
        "     language_locale, name\n" .
        "FROM ezcontentclass_name \n" .
        "ORDER BY id, version";

    $table = 'ezcontentclass';

    serializeNames( $selectSQL, $table );
}

function serializeContentClassAttributeNames()
{
    $selectSQL = "SELECT * FROM " . EZ_CONTENTCLASS_ATTRIBUTE_TMP_TABLE_NAME . " ORDER BY id, version";
    $table = 'ezcontentclass_attribute';

    serializeNames( $selectSQL, $table );
}


function serializeNames( $selectSQL, $storeToTable )
{
    $db = eZDB::instance();

    $limit = 100;
    $offset = 0;

    $selectSQL .= "\nLIMIT $limit";

    echo "selectSQL: $selectSQL\n";

    while ( $result = $db->arrayQuery( $selectSQL . " OFFSET $offset" ) )
    {
        // since name data is splitted between rows,
        // need to adjust selected data:
        // exclude the last id/version and process it during next 'select' iteration
        // 1. get last id/version pair
        $lastIdx = count( $result ) - 1;
        if ( $lastIdx > 0 )
        {
            $lastID = $result[$lastIdx]['id'];
            $lastVersion = $result[$lastIdx]['version'];

            // 2. check remained data
            for ( $lastIdx = $lastIdx - 1; $lastIdx >= 0; $lastIdx-- )
            {
                $row = $result[$lastIdx];
                if ( $lastID != $row['id'] || $lastVersion != $row['version'] )
                {
                    break;
                }
            }

            // 3. check whether $lastIdx is valid
            if ( $lastIdx < 0 )
            {
                // all selected data belongs to the same id/version
                $lastIdx = count( $result ) - 1;
            }
        }

        // 4. adjust offset to include excluded data to the next 'select'
        $offset += $lastIdx + 1;

        // process selected data
        $serializedName = false;
        $prevId = false;
        $prevVersion = false;
        for ( $idx = 0; $idx <= $lastIdx; $idx++ )
        {
            $row = $result[$idx];

            // check whether serialized name is completely assembled
            if ( $prevId != $row['id'] || $prevVersion != $row['version'] )
            {
                if ( $serializedName !== false )
                {
                    // store serialized name
                    storeSerializedName( $serializedName, $prevId, $prevVersion, $storeToTable );
                }

                // create new $serializedName to collect data
                $serializedName = new eZSerializedObjectNameList();
                $serializedName->resetNameList();
            }

            $prevId = $row['id'];
            $prevVersion = $row['version'];

            $serializedName->setNameByLanguageLocale( $row['name'], $row['language_locale'] );

            if ( $row['is_always_available'] & 1 )
            {
                $serializedName->setAlwaysAvailableLanguage( $row['language_locale'] );
            }

            if ( $idx == $lastIdx )
            {
                // no more date => store serialized name
                storeSerializedName( $serializedName, $prevId, $prevVersion, $storeToTable );
            }
        }
    }
}


function storeSerializedName( $serializedName, $id, $version, $table )
{
    if ( $serializedName instanceof eZSerializedObjectNameList )
    {
        $serializedNameString = $serializedName->serializeNames();

        $db = eZDB::instance();

        $updateSQL = "UPDATE $table\n" .
            "SET serialized_name_list = '" . $db->escapeString( $serializedNameString ) . "'\n" .
            "WHERE id = $id AND version = $version";

        $db->query( $updateSQL );
    }
}

function convertXMLDatatypes( $tableInfoList )
{
    foreach ( $tableInfoList as $tableInfo )
    {
        showMessage3( "converting '" . $tableInfo['datatype'] . "': " . $tableInfo['table'] . "." . $tableInfo['data_field'] );

        convertXMLData( $tableInfo, "xmlDatatypeSelectSQL", "xmlDatatypeUpdateSQL", "convertXMLDatatypeProgress" );
    }
}

function convertCustomXMLData( $tableInfoList )
{
    foreach ( $tableInfoList as $tableInfo )
    {
        showMessage3( "converting: '" . $tableInfo['table'] . "." . $tableInfo['data_field'] . "' table" );

        convertXMLData( $tableInfo, "xmlCustomDataSelectSQL", "xmlCustomDataUpdateSQL", "convertXMLCustomDataProgress" );
    }
}

function xmlDatatypeSelectSQL( $dataTableInfo )
{
    $table = $dataTableInfo['table'];
    $data_field = $dataTableInfo['data_field'];
    $datatype = $dataTableInfo['datatype'];

    $selectSQL = "SELECT id, version, $data_field as xml_data\n" .
                 "FROM $table\n" .
                 "WHERE $data_field LIKE '<?xml%'\n" .
                    "AND data_type_string = '$datatype'\n" .
                    "ORDER BY id, version";

    return $selectSQL;
}

function xmlDatatypeUpdateSQL( $dataTableInfo, $row )
{
    $db = eZDB::instance();

    $table = $dataTableInfo['table'];
    $data_field = $dataTableInfo['data_field'];

    $updateSQL = "UPDATE $table\n" .
                 "SET $data_field = '" . $db->escapeString( $row['xml_data'] ) . "'\n" .
                 "WHERE id = " . $row['id'] . "\n" .
                    "AND version = " . $row['version'];

    return $updateSQL;
}

function convertXMLDatatypeProgress( $row )
{
    showMessage3( "id: '" . $row['id'] . "' version: '" . $row['version'] . "'" );
}

function xmlCustomDataSelectSQL( $dataTableInfo )
{
    $table = $dataTableInfo['table'];
    $data_field = $dataTableInfo['data_field'];

    $selectSQL = "SELECT id, $data_field as xml_data\n" .
                 "FROM $table\n" .
                 "WHERE $data_field LIKE '<?xml%'\n" .
                 "ORDER BY id";

    return $selectSQL;
}

function xmlCustomDataUpdateSQL( $dataTableInfo, $row )
{
    $db = eZDB::instance();

    $table = $dataTableInfo['table'];
    $data_field = $dataTableInfo['data_field'];

    $updateSQL = "UPDATE $table\n" .
                 "SET $data_field = '" . $db->escapeString( $row['xml_data'] ) . "'\n" .
                 "WHERE id = " . $row['id'];

    return $updateSQL;
}

function convertXMLCustomDataProgress( $row )
{
    showMessage3( "id: '" . $row['id'] );
}


/*!
 Convert xml text to db's charset. However for optimization the xml processing instruction 'encoding' will be set
 to utf-8.
 */
function convertXMLData( $tableInfo, $xmlDataSelectSQLFunction, $xmlDataUpdateSQLFunction, $convertXMLProgressFunction )
{
    $db = eZDB::instance();

    $dbEncoding = strtolower( $db->charset() );

    $limit = 500;
    $offset = 0;

    $selectSQL = $xmlDataSelectSQLFunction( $tableInfo );
    $selectSQL .= "\nLIMIT $limit";

    while ( $result = $db->arrayQuery( $selectSQL . " OFFSET $offset" ) )
    {
        foreach ( $result as $row )
        {
            $convertXMLProgressFunction( $row );

            $xmlString = $row['xml_data'];

            $xmlEncoding = false;
            if ( ereg( '^<\?xml[^>]+encoding="([^"]+)"', $xmlString, $match ) )
            {
                $xmlEncoding = strtolower( $match[1] );
            }

            if ( !$xmlEncoding )
            {
                showWarning( "encoding for xml not found. assuming db's($dbEncoding) encoding" );
                $row['xml_data'] = ereg_replace( '(^<\?xml[^>]+)( \?>)', "\\1 encoding=\"utf-8\" ?>", $xmlString );
            }
            else if ( $xmlEncoding != $dbEncoding )
            {
                //showMessage3( "converting $xmlEncoding -> $dbEncoding" );
                $convertedXMLString = iconv( $xmlEncoding, $dbEncoding, $xmlString );
                if ( $convertedXMLString !== false )
                {
                    $row['xml_data'] = ereg_replace( '^(<\?xml[^>]+encoding)="([^"]+)"', "\\1=\"utf-8\"", $convertedXMLString );
                }
                else
                {
                    showWarning( "iconv failed to convert xml from '$xmlEncoding' to '$dbEncoding'" );
                    continue;
                }
            }
            else
            {
                showMessage3( "xml's and db's encodings are equal" );
                $row['xml_data'] = ereg_replace( '^(<\?xml[^>]+encoding)="([^"]+)"', "\\1=\"utf-8\"", $xmlString );
            }

            $updateSQL = $xmlDataUpdateSQLFunction( $tableInfo, $row );
            $db->query( $updateSQL );
        }

        $offset += $limit;
    }

}


function changeDBCharset( $charset, $collation )
{
    $db = eZDB::instance();

    $function = "changeDBCharset" . strtoupper( $db->databaseName() );
    if ( function_exists( $function ) )
    {
        $function( $charset, $collation );
    }
    else
    {
        showError( "no function to change DB charset defined" );
    }

}

function changeDBCharsetMYSQL( $charset, $collation )
{
    $db = eZDB::instance();

    $db->query( "ALTER DATABASE " . $db->DB . " CHARACTER SET $charset COLLATE $collation" );

    $tables = $db->arrayQuery( 'SHOW tables' );
    foreach ( $tables as $table )
    {
       $tableName = reset( $table ); // get first element of $table
       if ( $tableName )
       {
           showMessage3( 'Changing table: ' . $tableName );
           $db->query( 'ALTER TABLE ' . $db->escapeString( $tableName ) . " CONVERT TO CHARACTER SET $charset COLLATE $collation" );
           showMessage3( 'Optimizing table: ' . $tableName );
           $db->query( 'OPTIMIZE TABLE ' . $db->escapeString( $tableName ) );
       }
    }
}

function changeDBCharsetPOSTGRESQL( $charset, $collation )
{
    $db = eZDB::instance();

    // get database name
    $dbName = $db->DB;

    // get connection params
    $host = $db->Server;
    $port = $db->Port;
    $user = $db->User;
    // password is not allowed for command-line tools
    // $pass = $db->Password;

    $connectionParams = "--host=$host --port=$port --username=$user";

    // prepare utility commands
    $pgDump = "pg_dump $connectionParams";
    $psql = "psql $connectionParams";
    $dropdb = "dropdb $connectionParams";
    $createdb = "createdb $connectionParams";

    // get temporary dir to store dump
    $ini = eZINI::instance();
    $dumpDir = $ini->variable( 'FileSettings', 'TemporaryDir' ) . basename( __FILE__,  '.php');
    $dumpFile = $dbName . ".psql";
    $dumpPath = "$dumpDir/$dumpFile";

    showMessage3( 'finalizing current changes' );
    // set output encoding
    $db->query( "ALTER DATABASE " . $dbName . " SET client_encoding = $charset" );
    // finalizing changes
    $db->commit();
    // close current connection
    $db->close();


    showMessage3( 'sleeping..' );
    sleep( 5 );

    // dump db
    showMessage3( "taking the db dump, tmp storage is '$dumpPath'" );
    eZDir::mkdir( $dumpDir, false, true );
    $command = "$pgDump $dbName > '$dumpPath'";
    eZExecuteShellCommand( $command, "failed to dump db. tried command '$command'");

    showMessage3( "re-creating db with charset '$charset'" );
    // drop db
    $command = "$dropdb $dbName";
    ezExecuteShellCommand( $command, "failed to drop db. tried command '$command'");
    // create new db in $charset
    $command = "$createdb $dbName --encoding=utf8";
    eZExecuteShellCommand( $command, "failed to create db. tried command '$command'");

    // restore dump into newly created db
    showMessage3( "restoring db dump" );
    $command = "$psql $dbName < '$dumpPath'";
    eZExecuteShellCommand( $command, "failed to restore db dump. tried command '$command'");

    showMessage3( "clean up" );
    // clean up
    eZDir::recursiveDelete( $dumpPath );

    // re-initialize db interface
    $db = eZDB::instance( false, false, true );
    $db->begin();
}

function createTestXMLData()
{
    $db = eZDB::instance();

    $files = array( 'rus_koi8_xml.txt',
                    'rus_utf8_xml.txt',
                    'rus_cp1251_xml.txt',
                    'rus_undefined_xml.txt' );

    $datatypes = array( 'ezxmltext',
                    'ezselection',
                    'ezmatrix',
                    'ezxmltext' );

    $id = 0;
    foreach ( $files as $idx => $file )
    {
        ++$id;
        $fp = fopen( $file, "rb" );

        $dataText = fread( $fp, 200 );

        $query = "INSERT INTO b_tmp(id, version, data_type_string, data_text)\n" .
                    "VALUES( $id, 0, '" . $datatypes[$idx] . "', '" . $db->escapeString( $dataText ) . "' )";

        $db->query( $query );

        fclose( $fp );
    }

    $db->commit();
}

function getTestXMLData()
{
    $db = eZDB::instance();

    $db->query( "SET NAMES utf8" );

    $result = $db->arrayQuery( "SELECT data_text FROM b_tmp" );

    $str = var_export( $result, true );

    $fp = fopen( "rus_xml_converted.txt", "wb" );

    fwrite( $fp, $str, 1000 );
    fclose( $fp );
}


/**************************************************************
* start script                                                *
***************************************************************/

$cli = eZCLI::instance();

$script =& eZScript::instance( array( 'description' => ( "Changes your eZ Publish database tables to use UTF8" ),
                                      'use-session' => false,
                                      'use-modules' => false,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[extra-xml-attributes:][extra-xml-data:][collation:][skip-class-translations]",
                                "",
                                array( 'extra-xml-attributes' => "specify custom attributes which store its data in xml.\n" .
                                                                 "usage: <datatype_string>[.<table>.<field>][,<datatype_string>.<table>.<field>...].\n" .
                                                                 "default table is 'ezcontentobject_attribute', defualt data field is 'data_text'\n" .
                                                                 "note: your custom table must have 'id', 'version' and 'data_type_string' fields.",
                                       'extra-xml-data' => "specify custom xml data.\n" .
                                                            "usage: <table>.<field>[,<table>.<field>...].\n" .
                                                            "note: your custom table must have 'id' field.",
                                       'collation' => "specify collation for converted db. default is 'utf8_general_ci'",
                                       'skip-class-translations' => "Content class translations were added in eZ Publish 3.9. Use this options if upgrading from early version." ),
                                false,
                                array( 'user' => true ) );


$script->initialize();

$db = eZDB::instance();

$dbType = $db->databaseName();
switch( strtolower( $dbType ) )
{
    case 'mysql':
    case 'postgresql':
        break;
    default:
        {
            showError( "Unsupported db type '$dbType'");
        } break;
}

$skipClassTranslations = $options["skip-class-translations"];
$collation = $options['collation'] ? $options['collation'] : 'utf8_general_ci';

//
// get info about extra xml attributes
//
$xmlAttributesOption = $options['extra-xml-attributes'] ? $options['extra-xml-attributes'] : '';

//
// add info about standard xml attributes
//
$xmlAttributesOption = $xmlAttributesOption ? $xmlAttributesOption . ',' : $xmlAttributesOption;

$xmlAttributesOption .= 'ezxmltext';
$xmlAttributesOption .= ', ezimage';
$xmlAttributesOption .= ', ezmatrix';
$xmlAttributesOption .= ', ezselection.ezcontentclass_attribute.data_text5';

//
// process xml attributes info
//
$xmlAttributesInfo = array();
if ( $xmlAttributesOption )
{
    $xmlAttributesOption = split( ',', $xmlAttributesOption );
    foreach ( $xmlAttributesOption as $attributeTableInfoOption )
    {
        $attributeTableInfo = split( '\.', $attributeTableInfoOption );
        switch ( count( $attributeTableInfo ) )
        {
            case 1:
                {
                    $attributeTableInfo = array( 'datatype' => trim( $attributeTableInfo[0] ),
                                                 'table' => 'ezcontentobject_attribute',
                                                 'data_field' => 'data_text' );
                } break;
            case 3:
                {
                    $attributeTableInfo = array( 'datatype' => trim( $attributeTableInfo[0] ),
                                                 'table' => trim( $attributeTableInfo[1] ),
                                                 'data_field' => trim( $attributeTableInfo[2] ) );
                } break;
            default:
                {
                    showError( "invalid 'extra-xml-attributes' '$attributeTableInfoOption' option" );
                } break;
        }

        $xmlAttributesInfo[] = $attributeTableInfo;
    }
}

if ( count( $xmlAttributesInfo ) == 0 )
{
    showWarning( "no xml attributes specified" );
}


//
// get info about custom xml data
//
$xmlCustomDataOption = $options['extra-xml-data'] ? $options['extra-xml-data'] : '';

//
// process custom xml data info
//
$xmlCustomDataInfo = array();
if ( $xmlCustomDataOption )
{
    $xmlCustomDataOption = split( ',', $xmlCustomDataOption );
    foreach ( $xmlCustomDataOption as $tableInfo )
    {
        $tableInfo = split( '\.', $tableInfo );
        switch ( count( $tableInfo ) )
        {
            case 2:
                {
                    $tableInfo = array( 'table' => trim( $tableInfo[0] ),
                                        'data_field' => trim( $tableInfo[1] ) );
                } break;
            default:
                {
                    showError( "invalid 'extra-xml-data' '$tableInfo' option" );
                } break;
        }

        $xmlCustomDataInfo[] = $tableInfo;
    }
}


$db->begin();


//
// Get db charset
//
$dbCharset = $db->charset();
showMessage( "Detected database charset: $dbCharset" );


/**************************************************************
* backup content class serialized names                               *
***************************************************************/

// do nothing, cause class names are stored in ezcontentclass_name table,
// so, it's possible to restore converted names from this table.


/**************************************************************
* backup content class attribute serialized names                    *
***************************************************************/
if ( !$skipClassTranslations )
{
    showMessage( "Unserializing content class attributes names..." );
    createContentClassAttributeTempTable();
    unserializeContentClassAttriubteNames();
}


/**************************************************************
* convert xml datatypes to db's charset                       *
***************************************************************/
showMessage( "Converting xml datatypes..." );
convertXMLDatatypes( $xmlAttributesInfo );


/**************************************************************
* convert custom xml datat                                    *
***************************************************************/
if ( count( $xmlCustomDataInfo ) > 0 )
{
    showMessage( "Converting custom xml data..." );
    convertCustomXMLData( $xmlCustomDataInfo );
}


/**************************************************************
* convert tables                                              *
***************************************************************/
showMessage( "Changing DB charset..." );
changeDBCharset( 'utf8', $collation );


/**************************************************************
* restore class serialized names                              *
***************************************************************/
if ( !$skipClassTranslations )
{
    showMessage( "Serializing content class names..." );
    serializeContentClassNames();
}


/**************************************************************
* restore class_attributes serialized names                   *
***************************************************************/
if ( !$skipClassTranslations )
{
    showMessage( "Serializing content class attributes names..." );
    serializeContentClassAttributeNames();
}


/**************************************************************
* clean up                                                    *
***************************************************************/
showMessage( "Cleanup..." );
if ( !$skipClassTranslations )
{
    dropContentClassAttributeTempTable();
}


showMessage( "Commiting..." );
$db->commit();

showMessage( "DB has been converted successfully." );
$script->shutdown();

?>
