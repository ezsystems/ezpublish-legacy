<?php
//
// Created on: <28-Jan-2004 15:46:30 dr>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
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

/*!
  \class eZDbSchema ezdbschema.php
  \ingroup eZDbSchema
  \brief A factory for schema handlers

*/

class eZDbSchema
{
    /*!
     \static
     Create new instance of eZDBSchemaInterface. placed here for simplicity.

     \param eZDB instance (optional), if none provided, eZDB::instance() will be used.
     \return new Instance of eZDbSchema, false if failed
    */
    static function instance( $params = false )
    {
        if ( is_object( $params ) )
        {
            $db = $params;
            $params = array( 'instance' => $db );
        }

        if ( !isset( $params['instance'] ) )
        {
            $db = eZDB::instance();
            $params['instance'] = $db;
        }

        $db = $params['instance'];

        if ( !isset( $params['type'] ) )
            $params['type'] = $db->databaseName();
        if ( !isset( $params['schema'] ) )
            $params['schema'] = false;

        $dbname = $params['type'];

        /* Load the database schema handler INI stuff */
        $ini = eZINI::instance( 'dbschema.ini' );
        $schemaPaths = $ini->variable( 'SchemaSettings', 'SchemaPaths' );
        $schemaHandlerClasses = $ini->variable( 'SchemaSettings', 'SchemaHandlerClasses' );

        /* Check if we have a handler */
        if ( !isset( $schemaPaths[$dbname] ) or !isset( $schemaHandlerClasses[$dbname] ) )
        {
            eZDebug::writeError( "No schema handler for database type: $dbname", 'eZDbSchema::instance()' );
            return false;
        }

        /* Include the schema file and instantiate it */
        require_once( $schemaPaths[$dbname] );
        return new $schemaHandlerClasses[$dbname]( $params );
    }

    /*!
     \static
    */
    static function read( $filename, $returnArray = false )
    {
        $fd = @fopen( $filename, 'rb' );
        if ( $fd )
        {
            $buf = fread( $fd, 100 );
            fclose( $fd );
            if ( preg_match( '#^<\?' . "php#", $buf ) )
            {
                include( $filename );
                if ( $returnArray )
                {
                    $params = array();
                    if ( isset( $schema ) )
                        $params['schema'] = $schema;
                    if ( isset( $data ) )
                        $params['data'] = $data;
                    return $params;
                }
                else
                {
                    return $schema;
                }
            }
            else if ( preg_match( '#a:[0-9]+:{#', $buf ) )
            {
                return unserialize( file_get_contents( $filename ) );
            }
            else
            {
                eZDebug::writeError( "Unknown format for file $filename" );
                return false;
            }
        }
        return false;
    }

    /*!
     \static
    */
    static function readArray( $filename )
    {
        $schema = false;
        include( $filename );
        return $schema;
    }

    /*!
     \static
    */
    static function generateUpgradeFile( $differences )
    {
        $diff = var_export( $differences, true );
        return ( "<?php \n\$diff = \n" . $diff . ";\nreturn \$diff;\n?>\n" );
    }

    /*!
     \static
    */
    static function writeUpgradeFile( $differences, $filename )
    {
        $fp = @fopen( $filename, 'w' );
        if ( $fp )
        {
            fputs( $fp, eZDbSchema::generateUpgradeFile( $differences ) );
            fclose( $fp );
            return true;
        }
        else
        {
            return false;
        }
    }
}
?>
