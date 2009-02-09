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
    CREATE TEMPORARY TABLE " . EZ_CONTENTCLASS_ATTRIBUTE_TMP_TABLE_NAME . " (
      id int(11) NOT NULL default '0',
      version int(11) NOT NULL default '0',
      is_always_available int(11) NOT NULL default '0',
      language_locale varchar(20) NOT NULL default '',
      name varchar(255) NOT NULL default '',
      PRIMARY KEY  (id,version,language_locale)
    )" );

// create persistent tmp table since this table is needed between connect-sessions.
define( 'EZ_CREATE_CONTENTCLASS_ATTRIBUTE_TMP_TABLE_SQL_POSTGRESQL',
    "
    CREATE TABLE " . EZ_CONTENTCLASS_ATTRIBUTE_TMP_TABLE_NAME . " (
        id integer DEFAULT 0 NOT NULL,
        version integer DEFAULT 0 NOT NULL,
        is_always_available integer DEFAULT 0 NOT NULL,
        language_locale character varying(20) DEFAULT ''::character varying NOT NULL,
        name character varying(255) DEFAULT ''::character varying NOT NULL
    )" );

define( 'EZ_CREATE_CONTENTCLASS_ATTRIBUTE_TMP_TABLE_SQL_ORACLE',
    "
    CREATE GLOBAL TEMPORARY TABLE " . EZ_CONTENTCLASS_ATTRIBUTE_TMP_TABLE_NAME . " (
        id number(11) DEFAULT 0 NOT NULL,
        version number(11) DEFAULT 0 NOT NULL,
        is_always_available number(11) DEFAULT 0 NOT NULL,
        language_locale varchar2(20) NOT NULL,
        name varchar2(255) NOT NULL
    ) ON COMMIT PRESERVE ROWS;" );

define( 'EZ_DROP_CONTENTCLASS_ATTRIBUTE_TMP_TABLE_SQL',
    "DROP TABLE " . EZ_CONTENTCLASS_ATTRIBUTE_TMP_TABLE_NAME );


/**************************************************************
* 'cli->output' wrappers                                      *
***************************************************************/
function showError( $message, $addEOL = true, $bailOut = true )
{
    global $cli, $script, $eZDir;

    $cli->output( $cli->stylize( 'error', "Error: " .  $message ), $addEOL );

    if( $bailOut )
    {
        chdir( $eZDir ); // since it might have changed while running...
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
    echo $prompt;

    $userInput = fgets( STDIN );
    $userInput = trim( $userInput, "\n\r" );

    return $userInput;
}

function eZExecuteShellCommand( $command, $errMessage = '', $retry = true, $ignore = false )
{
    $err = 0;
    do
    {
        $out = system( $command, $err );
        if ( $err )
        {
            if ( $errMessage )
            {
                showMessage2( $errMessage );
            }

            if ( $retry )
            {
                do
                {
                    $action = $ignore ? eZGetUserInput( "Retry? [y/n/Ignore]: " ) : eZGetUserInput( "Retry? [y/n]: " );
                    if ( strpos( $action, 'y' ) === 0 )
                    {
                        $continue = false;
                    }
                    elseif ( $ignore && strpos( $action, 'I' ) === 0 )
                    {
                        $continue = true;
                        $retry = false;
                    }
                    else
                    {
                        // default action is not to retry but to abort
                        showError( "Aborting..." );
                    }
                }
                while ( !$continue );
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
/*!
 process xml attributes info
 \return \c false or an array of table infos.
*/
function parseXMLAttributesOption( $xmlAttributesOption )
{
    if ( !$xmlAttributesOption )
    {
        return false;
    }

    $xmlAttributesInfo = array();

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

    return $xmlAttributesInfo;
}

/*!
  process custom xml data info
  \retruns \c false of an array of table infos.
*/
function parseCustomXMLDataOption( $xmlCustomDataOption )
{
    if ( !$xmlCustomDataOption )
    {
        return false;
    }

    $xmlCustomDataInfo = array();

    $xmlCustomDataOption = split( ',', $xmlCustomDataOption );
    foreach ( $xmlCustomDataOption as $tableInfoOption )
    {
        $tableInfo = split( '\.', $tableInfoOption );
        switch ( count( $tableInfo ) )
        {
            case 2:
                {
                    $tableInfo = array( 'table' => trim( $tableInfo[0] ),
                                        'data_field' => trim( $tableInfo[1] ) );
                } break;
            default:
                {
                    showError( "invalid 'extra-xml-data' '$tableInfoOption' option" );
                } break;
        }

        $xmlCustomDataInfo[] = $tableInfo;
    }

    return $xmlCustomDataInfo;
}

/*!
 process custom xml data info
 \returns \c false or an array of table infos.
*/
function parseCustomSerializedDataOption( $serializedCustomDataOption )
{
    if ( !$serializedCustomDataOption )
    {
        return false;
    }

    $db = eZDB::instance();

    $serializedDataInfo = array();

    $serializedCustomDataOption = split( ',', $serializedCustomDataOption );
    foreach ( $serializedCustomDataOption as $tableInfoOption )
    {
        $tableInfo = split( '\;', $tableInfoOption );
        if ( count( $tableInfo ) != 2 )
        {
            showError( "invalid 'extra-serialized-data' '$tableInfoOption' option" );
        }

        $dataInfo = split( '\.', $tableInfo[0] );
        $keyInfo = split( '\.', $tableInfo[1] );

        switch ( count( $dataInfo ) )
        {
            case 2:
                {
                    $dataInfo = array( 'table' => trim( $dataInfo[0] ),
                                       'data_field' => trim( $dataInfo[1] ) );
                } break;
            default:
                {
                    showError( "invalid 'extra-serialized-data' '$tableInfoOption' option" );
                } break;
        }

        foreach ( array_keys( $keyInfo ) as $key => $value )
        {
            trim( $keyInfo[$key] );
            // check column exists
            $result = $db->query( "SELECT " . $keyInfo[$key] . " from " . $dataInfo['table'] . " limit 1" );
            if ( $result === false )
            {
                showError( "invalid 'extra-serialized-data' '$tableInfoOption' option" );
            }
        }

        $serializedDataInfo[] = array( 'table' => $dataInfo['table'],
                                       'data_field' => $dataInfo['data_field'],
                                       'blob_field' => $dataInfo['data_field'] . "_blob",
                                       'keys' => $keyInfo );
    }

    return $serializedDataInfo;
}



/*!
 Check db driver
*/
function checkDBDriver()
{
    $db = eZDB::instance();
    $dbType = $db->databaseName();
    switch( strtolower( $dbType ) )
    {
        case 'mysql':
        case 'postgresql':
        case 'oracle':
            break;
        default:
            {
                return false;
            } break;
    }

    return true;
}

/*!
 Check db charset
*/
function checkDBCharset()
{
    $db = eZDB::instance();
    $dbCharset = $db->charset();

    switch( strtolower( $dbCharset ) )
    {
        case 'utf8':
        case 'utf-8':
            {
                return false;
            } break;
        default:
            break;
    }

    return true;
}

/*!
 DB specific checks. A string is returned for error conditions (script halts after printing it)
*/
function checkDBExtraConditions()
{
    $db = eZDB::instance();

    $function = "checkDBExtraConditions" . strtoupper( $db->databaseName() );
    if ( function_exists( $function ) )
    {
        return $function();
    }

    return true;
}

/*!
 @todo We should really check for exp_full, imp_full, (sysdba) privileges rather than DBA role...
 @todo add check for RAC - do not try anything in such a case
*/
function checkDBExtraConditionsORACLE()
{
    global $oracleDbaAccount, $oracleHome;

    //$db = eZDB::instance();
    global $db;

    showMessage3( '' );
    showWarning( "The procedure of upgrading the character set of an Oracle database needs manual intervention.\n".
                 "This upgrade script will only create a database export, and later reimport it, leaving to\n".
                 "the end user (you) the responsibility of changing database character set.\n".
                 "Please refer to Oracle documentation for instructions on altering a database character set\n".
                 "(it usually involves creating a new database from scratch, and it always has effect on all\n".
                 "schemas contained in the database)." );
    showMessage3( '' );
    $continue = eZGetUserInput( "Do you want to continue?  (y to accept) " );

    if ( $continue != 'y' && $continue != 'Y' )
    {
        return "Aborting";
    }

    $oracleDbaAccount = false;
    while( $oracleDbaAccount === false )
    {
        // NB: we need export_full privileges for full export.
        // We could need SYSDBA for connecting AS SYSDBA and altering db - IF IT WORKED...
        // In that case we should try to support OSOPER connections, too...
        if ( $db->isConnected() )
        {
            $dba = $db->arrayQuery("select default_role from user_role_privs where granted_role in ('DBA')");
            //$dba = $db->arrayQuery("select sysdba from V\$PWFILE_USERS where username='".strtoupper($db->User)."'");
        }
        else
        {
            showWarning( "Supplied username/password are incorrect" );
            $dba = false;
        }
        if (count($dba) && $dba[0]['default_role'] == 'YES')
        //if (count($dba) && $dba[0]['sysdba'] == 'TRUE')
        {
            $oracleDbaAccount = array( 'user' => $db->User, 'password' => $db->Password );
            // reconnect using standard credentials, in case we have logged in as admin
            $db->close();
            $db = eZDB::instance( false, false, true );
        }
        else
        {
            //$account = eZGetUserInput( "Please enter username/password of a valid SYSDBA account (return to abort): ");
            $account = eZGetUserInput( "Please enter username/password of a valid DBA account (return to abort): ");
            if ( $account == "" )
            {
                //return "The charset conversion script needs to run with an oracle user account that has SYSDBA privileges. Aborting.";
                return "The charset conversion script needs to run with an oracle user account that has DBA privileges. Aborting.";
            }
            else
            {
                $account = explode( '/', $account );
                if ( count( $account ) > 1 )
                {
                    $db->close();
                    $db = eZDB::instance( false, array( 'user' => $account[0], 'password' => $account[1] ), true );
                }
            }
        }
    } // while

    $oracleHome = getenv( 'ORACLE_HOME' );
    if ( $oracleHome != "" )
    {
        $ok = eZGetUserInput( "Found ORACLE_HOME $oracleHome, is this the correct directory? (y to accept) ");
        if ( $ok != "y" && $ok != "Y" )
        {
            $oracleHome = '';
        }
    }
    $exe = eZSys::osType() == 'win32' ? '.exe' : '';
    //while( !is_dir( $oracleHome ) || !file_exists( $oracleHome . '/bin/sqlplus' . $exe ) || !file_exists( $oracleHome . '/bin/imp' . $exe ) || !file_exists( $oracleHome . '/bin/exp' . $exe ) )
    while( !is_dir( $oracleHome ) || !file_exists( $oracleHome . '/bin/imp' . $exe ) || !file_exists( $oracleHome . '/bin/exp' . $exe ) )
    {
        if ( $oracleHome != "" )
        {
            showWarning( "imp or exp tools not found in ORACLE_HOME $oracleHome" );
        }
        $oracleHome = eZGetUserInput( "Please enter the path to ORACLE_HOME, where the imp and exp tools are (return to abort): ");
        if ( $oracleHome == "" )
        {
            return "The charset conversion script needs to run with an oracle home where the imp and exp tools are available. Aborting.";
        }
    }

    return true;
}

/**************************************************************
* handle content class / content class attributes             *
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

function unserializeContentClassAttributeNames()
{
    $db = eZDB::instance();

    $attributeName = new eZContentClassAttributeNameList();

    $limit = 100;
    $offset = 0;
    $selectSQL = "SELECT id, version, serialized_name_list FROM ezcontentclass_attribute ORDER BY id, version";

    while ( $result = $db->arrayQuery( $selectSQL , array( 'limit' => $limit, 'offset' => $offset ) ) )
    {
        foreach ( $result as $row )
        {
            //showMessage3( "id: '" . $row['id'] . "' version: '" . $row['version'] . "'" );

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

    //$selectSQL .= "\nLIMIT $limit";

    while ( $result = $db->arrayQuery( $selectSQL , array( 'limit' => $limit, 'offset' => $offset ) ) )
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

/**************************************************************
* handle custom serialized data                               *
***************************************************************/

/*!
 Logic:
  - create binary column as temp storage to not loose data when table will be converted
  - get original data
  - unseiazlize
  - convert data to utf-8
  - serialize
  - store data in binary column
 */
function convertSerializedData( $serializedDataInfo )
{
    if ( !is_array( $serializedDataInfo ) )
    {
        return;
    }

    $db = eZDB::instance();

    // create blob column
    $function = "createBLOBColumn" . strtoupper( $db->databaseName() );
    if ( function_exists( $function ) )
    {
        foreach ( $serializedDataInfo as $tableInfo )
        {
            $function( $tableInfo );
        }
    }
    else
    {
        showError( "no function to create BLOB column" );
    }

    // convert data
    $dbEncoding = strtolower( $db->charset() );

    foreach ( $serializedDataInfo as $tableInfo )
    {
        showMessage3( $tableInfo['table'] . '.' . $tableInfo['data_field'] );

        $limit = 100;
        $offset = 0;

        $keysString = implode( ', ', $tableInfo['keys'] );
        $dataFieldName = $tableInfo['data_field'];
        $selectSQL = "SELECT " . $keysString . ', ' . $dataFieldName .
                     " FROM " . $tableInfo['table'];
                     //" LIMIT $limit";

        while ( $result = $db->arrayQuery( $selectSQL, array( 'limit' => $limit, 'offset' => $offset ) ) )
        {
            foreach ( $result as $row )
            {
                $data = unserialize( $row[$dataFieldName] );
                if ( !$data )
                {
                    // nothing to do
                    continue;
                }

                $data = convertArray( $data, $dbEncoding, 'utf8' );
                $data = serialize( $data );

                $whereSql = '';
                foreach ( $tableInfo['keys'] as $key )
                {
                    if ( $whereSql != '' )
                    {
                        $whereSql .= " AND ";
                    }
                    $whereSql .= "$key = " . $row[$key];
                }

                $updateSql = "UPDATE " . $tableInfo['table'] .
                             " SET " . $tableInfo['blob_field'] . " = '" . $data . "'" .
                             " WHERE $whereSql";

                $db->query( $updateSql );
            }
            $offset += $limit;
        }

    }
}

/*!
 Restore data from binary column
 */
function restoreSerializedData( $serializedDataInfo )
{
    if ( !is_array( $serializedDataInfo ) )
    {
        return;
    }

    $db = eZDB::instance();

    foreach ( $serializedDataInfo as $tableInfo )
    {
        $sql = "UPDATE " . $tableInfo['table'] .
               " SET " . $tableInfo['data_field'] . ' = ' . $tableInfo['blob_field'];

        $db->query( $sql );
    }
}

function dropBLOBColumns( $serializedDataInfo )
{
    if ( !is_array( $serializedDataInfo ) )
    {
        return;
    }

    foreach ( $serializedDataInfo as $tableInfo )
    {
        dropBLOBColumn( $tableInfo );
    }
}

function convertArray( $array, $inCharset, $outCharset )
{
    if ( !is_array( $array ) )
    {
        showError( "convertArray: not an array was passed" );
        var_dump( $array );
        return;
    }

    foreach ( array_keys( $array ) as $key )
    {
        $value = $array[$key];

        if ( is_string( $value ) )
        {
            $array[$key] = iconv( $inCharset, $outCharset, $value );
        }
        else if ( is_array( $value ) )
        {
            $array[$key] = convertArray( $value, $inCharset, $outCharset );
        }
        else
        {
            //nothing to do
        }
    }

    return $array;
}

function createBLOBColumnMYSQL( $tableInfo )
{
    $db = eZDB::instance();
    $query = "ALTER TABLE " . $tableInfo['table'] . " ADD COLUMN " . $tableInfo['blob_field'] . " BLOB";
    $db->query( $query );
}

function createBLOBColumnPOSTGRESQL( $tableInfo )
{

}


function createBLOBColumnORACLE( $tableInfo )
{
    $db = eZDB::instance();
    $query = "ALTER TABLE " . $tableInfo['table'] . " ADD " . $tableInfo['blob_field'] . " BLOB";
    $db->query( $query );
}

function dropBLOBColumn( $tableInfo )
{
    $db = eZDB::instance();
    $query = "ALTER TABLE " . $tableInfo['table'] . " DROP COLUMN " . $tableInfo['blob_field'];
    $db->query( $query );
}

/**************************************************************
* handle xml data                                             *
***************************************************************/
function convertXMLDatatypes( $tableInfoList )
{
    foreach ( $tableInfoList as $tableInfo )
    {
        showMessage3( "  converting '" . $tableInfo['datatype'] . "': " . $tableInfo['table'] . "." . $tableInfo['data_field'] );

        convertXMLData( $tableInfo, "xmlDatatypeSelectSQL", "xmlDatatypeUpdateSQL", "convertXMLDatatypeProgress" );
    }
}

function convertCustomXMLData( $tableInfoList )
{
    foreach ( $tableInfoList as $tableInfo )
    {
        showMessage3( "  converting: '" . $tableInfo['table'] . "." . $tableInfo['data_field'] . "' table" );

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
    //showMessage3( "id: '" . $row['id'] . "' version: '" . $row['version'] . "'" );
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
    //$selectSQL .= "\nLIMIT $limit";

    while ( $result = $db->arrayQuery( $selectSQL, array( 'limit' => $limit, 'offset' => $offset ) ) )
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
                //showMessage3( "xml's and db's encodings are equal" );
                $row['xml_data'] = ereg_replace( '^(<\?xml[^>]+encoding)="([^"]+)"', "\\1=\"utf-8\"", $xmlString );
            }

            $updateSQL = $xmlDataUpdateSQLFunction( $tableInfo, $row );
            $db->query( $updateSQL );
        }

        $offset += $limit;
    }

}


/**************************************************************
* handle tables conversion                                    *
***************************************************************/
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
           showMessage3( '  changing table: ' . $tableName );
           $db->query( 'ALTER TABLE ' . $db->escapeString( $tableName ) . " CONVERT TO CHARACTER SET $charset COLLATE $collation" );
           showMessage3( '  optimizing table: ' . $tableName );
           $db->query( 'OPTIMIZE TABLE ' . $db->escapeString( $tableName ) );
       }
    }

    $db->query( "SET NAMES $charset" );
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

    // note: is this necessary? since we close the connection asap, and we are not in a transaction,
    // there is no need to commit or alter encoding, really...
    showMessage3( '  finalizing current changes' );
    // set output encoding
    $db->query( "ALTER DATABASE " . $dbName . " SET client_encoding = $charset" );
    // finalizing changes
    $db->commit();
    // close current connection
    $db->close();


    showMessage3( '  sleeping..' );
    sleep( 5 );

    // dump db
    showMessage3( "  taking the db dump, tmp storage is '$dumpPath'" );
    eZDir::mkdir( $dumpDir, false, true );
    $command = "$pgDump $dbName > '$dumpPath'";
    eZExecuteShellCommand( $command, "failed to dump db. tried command '$command'");

    showMessage3( "  re-creating db with charset '$charset'" );
    // drop db
    $command = "$dropdb $dbName";
    eZExecuteShellCommand( $command, "failed to drop db. tried command '$command'");
    // create new db in $charset
    $command = "$createdb $dbName --encoding=utf8";
    eZExecuteShellCommand( $command, "failed to create db. tried command '$command'");

    // restore dump into newly created db
    showMessage3( "  restoring db dump" );
    $command = "$psql $dbName < '$dumpPath'";
    eZExecuteShellCommand( $command, "failed to restore db dump. tried command '$command'");

    showMessage3( "  clean up" );
    // clean up
    eZDir::recursiveDelete( $dumpPath );

    // re-initialize db interface
    $db = eZDB::instance( false, false, true );
    //$db->begin();

    $db->query( "SET NAMES $charset" );
}

/**
* NOTE: What if other data is also in the db? Either we do not convert it and
*       have it most likely corrupted, or we convert it - and leave to the client
*       for the other apps to set up NLS_LANG correctly to keep working.
*
*       We could use the csalter script iff we where sure that db version was > 9...
*
*       Oracle 9 exp exports data using the DB charset
*
* From http://www.experts-exchange.com/Database/Oracle/Q_22836430.html

May be the best procedure is the following one:
On PROD Database:
1. export full=y rows=n file=export_db_structure.dmp
2. export full=y file=export_db_date.dmp
On TEST Database:
1. create tablespaces with the same name on PROD
2. import full=y file=export_db_structure.dmp ignore=y
Now we have users of PROD, on TEST database. SYSTEM user is not imported, because already exists.
3. import fromuser=user1,user2,user3 touser=user1,user2,user3  file=export_db_data.dmp ignore=y
now we have user1..3 data on their tables...

*       Unfortunately even if we do that, Oracle will nto let us convert a db from
*       latin1 to utf8 charsets. The only way is to drop the db and creater it
*       from scratch. Since we have no clue about db storage, we will let the admin
*       take care of that part, and only do the export/import parts.
*
* @todo oracle servers might use UTF8 charset instead of AL32UTF8: check before executing!
*
* @todo using dbname as file name does not work with easy conection naming
*
* @todo log somewhere results of imp, exp commands for better understanding of errors
*/
function changeDBCharsetORACLE( $charset, $collation )
{
    global $db, $oracleDbaAccount, $oracleHome, $eZDir;

    //$db = eZDB::instance( false, array( 'user' => $oracleDbaAccount['user'], 'password' => $oracleDbaAccount['password'] ), true );

    // since we are here, we should be connected with a dba account (extra conditions check did it)
    $oracleCharset = $db->arrayQuery("select value from nls_database_parameters where parameter = 'NLS_CHARACTERSET'");
    $oracleCharset = $oracleCharset[0]['value'];
    //$oracleLocale = $db->arrayQuery("select language||'_'||territory as locale from (select value as language from nls_database_parameters where parameter = 'NLS_LANGUAGE'), (select value as territory from nls_database_parameters where parameter = 'NLS_TERRITORY')");
    //$oracleLocale = $oracleLocale[0]['locale'];
    /*$users = $db->arrayQuery("select username from all_users where username not in ('SYS', 'SYSTEM')");
    $oracleUsers = array();
    foreach( $users as $row )
    {
        $oracleUsers[] = $row['username'];
    }*/

    if ( $oracleCharset == 'AL32UTF8' )
    {
        // lucky case: client was configued to use another charset, but db was internally utf8 already!
        showMessage3( "  database charset is already UTF8: skipping." );
    }
    /*else if ( count( $oracleUsers  ) == 0 )
    {
        // this can only mean eZ Publish is installed using SYS or SYSTEM accounts. Brr...
        /// @todo find out best way to die here...
    }*/
    else
    {

    // get database name
    $dbName = $db->DB;
    //$dbversion = $db->databaseServerVersion();

    // get connection params
    //$host = $db->Server;
    //$port = $db->Port;
    //$user = $db->User;
    //$pass = $db->Password;

    $connectionParams = $oracleDbaAccount['user'].'/'.$oracleDbaAccount['password']."@$dbName";

    // get temporary dir to store dumps
    $ini = eZINI::instance();
    $dumpDir = $ini->variable( 'FileSettings', 'TemporaryDir' ) . basename( __FILE__, '.php' );
    $dumpFile1 = $dbName . "_structure_export.dmp";
    $dumpPath1 = "$dumpDir/$dumpFile1";
    $dumpFile2 = $dbName . "_full_export.dmp";
    $dumpPath2 = "$dumpDir/$dumpFile2";
    $commandPath = "$dumpDir/$dbName" . "_cmd.sql";
    $logPath = "$dumpDir/$dbName" . "_cmd.log";
    $users = implode( ',', $oracleUsers );

    //$ora_charset = "";

    // prepare utility commands
    $exe = eZSys::osType() == 'win32' ? '.exe' : '';
    $exec = eZSys::osType() == 'win32' ? '' : './';

    $expdb1 = "{$exec}exp{$exe} $connectionParams CONSISTENT=Y FULL=Y ROWS=N  FILE=$dumpPath1";
    $expdb2 = "{$exec}exp{$exe} $connectionParams CONSISTENT=Y FULL=Y FILE=$dumpPath2";
    $impdb1 = "{$exec}imp{$exe} $connectionParams FULL=Y IGNORE=Y BUFFER=30960 FILE=$dumpPath1";
    $impdb2 = "{$exec}imp{$exe} $connectionParams FROMUSER=$users TOUSER=$users IGNORE=Y BUFFER=30960 FILE=$dumpPath2";

    /*
    $alterdb = "SET ECHO ON
SPOOL $logPath;
WHENEVER SQLERROR EXIT FAILURE;
WHENEVER OSERROR EXIT FAILURE;";
    if ( $dbversion['string'][0] == '8' )
    {
        /// @todo: check if using oracle < 8.1.5: we have touse svrmgrl then, as sqlplus was not good enough yet
        $sqlplus = "{$exec}sqlplus{$exe} $connectionParams";
        $alterdb .= "
CONNECT $connectionParams AS SYSDBA";
    }
    else
    {
        $sqlplus = "{$exec}sqlplus{$exe} -L $connectionParams as sysdba";
    }

    //$dropdb = "dropdb $connectionParams";
    //$createdb = "createdb $connectionParams";
    foreach( $oracleUsers as $user )
    {
        $alterdb .= "
DROP USER $user CASCADE;";
    }
    $alterdb .= "
SHUTDOWN IMMEDIATE;
STARTUP MOUNT;
ALTER SYSTEM ENABLE RESTRICTED SESSION;
ALTER SYSTEM SET JOB_QUEUE_PROCESSES=0;
ALTER SYSTEM SET AQ_TM_PROCESSES=0;
ALTER DATABASE OPEN;
ALTER DATABASE CHARACTER SET AL32UTF8;
SHUTDOWN IMMEDIATE; -- or SHUTDOWN NORMAL;
STARTUP;
EXIT;
";
    */

    // finalizing changes. Note that since we asked for admin connection before,
    // disconnecting and reconnecting, this is surely a NOOP
    //showMessage3( 'finalizing current changes' );
    //$db->commit();

    /// @todo add a database logfile switch or checkpoint here? it would be nice...

    // close current connection
    $db->close();

    showMessage3( '  sleeping..' );
    sleep( 5 );

    // dump db
    showMessage3( "  taking the db dump, tmp storage is '$dumpPath1', '$dumpPath2'" );
    eZDir::mkdir( $dumpDir, false, true );
    chdir( $oracleHome . '/bin' );
    eZExecuteShellCommand( $expdb1, "failed to dump db schema. tried command '$expdb1'", true, true );
    eZExecuteShellCommand( $expdb2, "failed to dump db data. tried command '$expdb2'", true, true );
    chdir( $eZDir );

    // verify that dump files exist
    if ( !file_exists( $dumpPath1 ) || !file_exists( $dumpPath2 ) )
    {
        showError( "DB Dump files cannot be found. Aborting..." );
    }

    /*
    showMessage3( "altering db with charset '$charset'" );
    $command = $sqlplus . " @$commandPath";
    file_put_contents( $commandPath, $alterdb );
    eZExecuteShellCommand( $command, "failed to alter db. tried command '$command'");
    */
    showMessage3( '' );
    showMessage3( "Now you will have to alter the database character set." );
    showMessage3( "The recommended way is to create a new database from scratch" );
    showMessage3( "using AL32UTF8 as character set (THIS IS VERY IMPORTANT)," );
    showMessage3( "and delete the existing one." );
    showMessage3( "The new database should be empty (all schemas will be recreated by this script)" );
    showMessage3( "and have the same DBA account as the old one." );
    showMessage3( "It should also use the same connect identifier as the old one." );
    showMessage3( '' );
    showMessage3( "PLEASE do not terminate this php script while doing that," );
    showMessage3( "use a different command line shell." );
    showMessage3( '' );
    $continue = eZGetUserInput( "Press Y when you are ready to continue... " );
    if ( $continue != 'y' && $continue != 'Y' )
    {
        showError( "Aborting" );
    }

    // connect to new db with dba account, check that charset is OK
    while( true )
    {
        $db = eZDB::instance( false, array( 'user' => $oracleDbaAccount['user'], 'password' => $oracleDbaAccount['password'] ), true );
        if ( !$db->isConnected() )
        {
            showWarning( "Cannot connect to the new database.\n".
                         "Please check that it is up and running before continuing" );
        }
        else
        {
            $oracleCharset = $db->arrayQuery("select value from nls_database_parameters where parameter = 'NLS_CHARACTERSET'");
            $oracleCharset = $oracleCharset[0]['value'];
            $db->close();
            if ( $oracleCharset == 'AL32UTF8' )
            {
                break;
            }
            else
            {
                showWarning( "The new database uses the $oracleCharset character set instead of AL32UTF8.\n".
                             "Please recreate the database using AL32UTF8 before continuing" );
            }
        }
        $continue = eZGetUserInput( "Press Y when you are ready to continue. Any other letter to abort " );
        if ( $continue != 'y' && $continue != 'Y' )
        {
            showError( "Aborting" );
        }
    }

    // restore dump into newly created db
    showMessage3( "  restoring db dump" );
    chdir( $oracleHome . '/bin' );
    eZExecuteShellCommand( $impdb1, "failed to restore db dump. tried command '$impdb1'");
    eZExecuteShellCommand( $impdb2, "failed to restore db dump. tried command '$impdb2'");
    chdir( $eZDir );

    showMessage3( "  cleaning up" );
    // clean up
    eZDir::recursiveDelete( $dumpPath1 );
    eZDir::recursiveDelete( $dumpPath2 );

    }

    // re-initialize db interface, *** this time in UTF8 - with the standard user ***
    $db = eZDB::instance( false, array( 'charset' => 'utf8' ), true );
    if ( !$db->isConnected() )
    {
        showError( "Cannot reconnect to DB. Aborting..." );
    }
    //$db->begin();
}

/**************************************************************
* start script                                                *
***************************************************************/

// work around a bug in eZSys that prevents it from telling us eZ Publish base dir
$eZDir = getcwd();

$cli = eZCLI::instance();

$script = eZScript::instance( array( 'description' => ( "Changes your eZ Publish database tables to use UTF8" ),
                                      'use-session' => false,
                                      'use-modules' => false,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[extra-xml-attributes:][extra-xml-data:][extra-serialized-data:][collation:][skip-class-translations]",
                                "",
                                array( 'extra-xml-attributes' => "specify custom attributes which store its data in xml.\n" .
                                                                 "usage: <datatype_string>[.<table>.<field>][,<datatype_string>.<table>.<field>...].\n" .
                                                                 "default table is 'ezcontentobject_attribute', default data field is 'data_text'\n" .
                                                                 "note: your custom table must have 'id', 'version' and 'data_type_string' fields.",
                                       'extra-xml-data' => "specify custom xml data.\n" .
                                                            "usage: <table>.<field>[,<table>.<field>...].\n" .
                                                            "note: your custom table must have 'id' field.",
                                       'extra-serialized-data' => "specify custom serialized data.\n" .
                                                                  "usage: <table>.<field>;<key_field1>[.<key_field2>....][,<table>.<field>...].\n" .
                                                                  "ex: mytable.data_text;id.version,mytable2.data;id",

                                       'collation' => "specify collation for converted db. default is 'utf8_general_ci'",
                                       'skip-class-translations' => "Content class translations were added in eZ Publish 3.9. Use this options if upgrading from early version." ),
                                false,
                                array( 'user' => true ) );


$script->initialize();

$db = eZDB::instance();
$db->OutputTextCodec = null;
$db->InputTextCodec = null;

if ( !$db->isConnected() )
{
    showError( "Cannot connect to the database");
}

if ( !checkDBDriver() )
{
    showError( "Unsupported db type '$dbType'");
}

if ( !checkDBCharset() )
{
    showMessage( "The database is already in utf8." );
    $script->shutdown( 2 );
}

// Display big fat warning that this script it might leave your database in an
// inconsistent state
showMessage2( "WARNING: BACK UP YOUR DATABASE!" );
showMessage3( "Please make sure you have backed up your database before proceeding!");
showMessage3( "If this script, for some reasons fails, your database may be left in an inconsistent state.\n" );
showMessage3( "This script will continue in 25 seconds. Press ctrl+c to abort." );
sleep( 10 );
echo "Continuing in: ";
for ( $i = 15; $i > 0; $i-- )
{
    echo "$i ";
    sleep(1);
}
echo "\n";


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


$xmlAttributesInfo = parseXMLAttributesOption( $xmlAttributesOption );
if ( $xmlAttributesInfo && count( $xmlAttributesInfo ) == 0 )
{
    showWarning( "no xml attributes specified" );
}


//
// get info about custom xml data
//
$xmlCustomDataOption = $options['extra-xml-data'] ? $options['extra-xml-data'] : '';
$xmlCustomDataInfo = parseCustomXMLDataOption( $xmlCustomDataOption );


//
// get info about custom serialized data
//
$serializedCustomDataOption = $options['extra-serialized-data'] ? $options['extra-serialized-data'] : '';
$serializedDataInfo = parseCustomSerializedDataOption( $serializedCustomDataOption );


//
// extra prerequisite checking that can be Db specific
//
if ( $msg = checkDBExtraConditions() !== true )
{
    showError( $msg );
}


$db->begin();


/**************************************************************
* convert extra serialized data                               *
***************************************************************/
if ( is_array( $serializedDataInfo ) )
{
    showMessage( "Converting extra serialized data" );
    convertSerializedData( $serializedDataInfo );
}


/**************************************************************
* backup content class serialized names                       *
***************************************************************/

// do nothing, cause class names are stored in ezcontentclass_name table,
// so, it's possible to restore converted names from this table.


/**************************************************************
* backup content class attribute serialized names             *
***************************************************************/
if ( !$skipClassTranslations )
{
    showMessage( "Unserializing content class attributes names..." );
    createContentClassAttributeTempTable();
    unserializeContentClassAttributeNames();
}


/**************************************************************
* convert xml datatypes to db's charset                       *
***************************************************************/
showMessage( "Converting xml datatypes..." );
convertXMLDatatypes( $xmlAttributesInfo );


/**************************************************************
* convert custom xml data                                     *
***************************************************************/
if ( is_array( $xmlCustomDataInfo ) )
{
    showMessage( "Converting custom xml data..." );
    convertCustomXMLData( $xmlCustomDataInfo );
}


showMessage( "Commiting..." );
$db->commit();

/**************************************************************
* convert tables                                              *
***************************************************************/
showMessage( "Changing DB charset..." );
changeDBCharset( 'utf8', $collation );

$db->begin();

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
* restore extra serialized data                               *
***************************************************************/
if ( is_array( $serializedDataInfo ) )
{
    showMessage( "Restoring extra serialized data" );
    restoreSerializedData( $serializedDataInfo );
}

showMessage( "Commiting..." );
$db->commit();

/**************************************************************
* clean up                                                    *
***************************************************************/
showMessage( "Cleaning up..." );
if ( !$skipClassTranslations )
{
    dropContentClassAttributeTempTable();
}
dropBLOBColumns( $serializedDataInfo );


/**************************************************************
* finalize                                                    *
***************************************************************/

showMessage( "DB has been converted successfully." );
showMessage( "PLEASE REMEMBER to alter the database connection definition in site.ini with charset=utf8" );
$script->shutdown();

?>
