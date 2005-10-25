<?php
//
// Created on: <28-Jan-2004 15:46:30 dr>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \class eZDBSchema ezdbschema.php
  \ingroup eZDBSchema
  \brief A factory for schema handlers

*/

class eZDbSchema
{
    /*!
     \static
     Create new instance of eZDBSchemaInterface. placed here for simplicity.

     \param eZDB instance (optional), if none provided, eZDB::instance() will be used.
     \return new Instance of eZDBSchema, false if failed
    */
    function instance( $params = false )
    {
        if ( is_object( $params ) )
        {
            $db =& $params;
            unset( $params );
            $params = array( 'instance' => &$db );
        }

        if ( !isset( $params['instance'] ) )
        {
            include_once( 'lib/ezdb/classes/ezdb.php' );
            $db = eZDB::instance();
            $params['instance'] = &$db;
        }

        unset( $db );
        $db =& $params['instance'];
        $dbname = $db->databaseName();

        if ( !isset( $params['type'] ) )
            $params['type'] = $dbname;
        if ( !isset( $params['schema'] ) )
            $params['schema'] = false;

        /* Load the database schema handler INI stuff */
        require_once( 'lib/ezutils/classes/ezini.php' );
        $ini =& eZINI::instance( 'dbschema.ini' );
        $schemaPaths = $ini->variable( 'SchemaSettings', 'SchemaPaths' );
        $schemaHandlerClasses = $ini->variable( 'SchemaSettings', 'SchemaHandlerClasses' );

        /* Check if we have a handler */
        if ( !isset( $schemaPaths[$dbname] ) or !isset( $schemaHandlerClasses[$dbname] ) )
        {
            eZDebug::writeError( "No schema handler for database type: $dbname", 'eZDBSchema::instance()' );
            return false;
        }

        /* Include the schema file and instantiate it */
        require_once( $schemaPaths[$dbname] );
        return new $schemaHandlerClasses[$dbname]( $params );
    }

    /*!
     \static
    */
	function read( $filename, $returnArray = false )
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
                include_once( 'lib/ezfile/classes/ezfile.php' );
                return unserialize( eZFile::getContents( $filename ) );
            }
            else
            {
                eZDebug::writeError( "Unknown format for file $filename" );
                return false;
            }
        }
	}

    /*!
     \static
    */
	function readArray( $filename )
	{
		$schema = false;
        include( $filename );
        return $schema;
	}

	/*!
     \static
    */
	function generateUpgradeFile( $differences )
	{
		$diff = var_export( $differences, true );
		return ( "<?php \n\$diff = \n" . $diff . ";\nreturn \$diff;\n?>\n" );
	}

    /*!
     \static
    */
	function writeUpgradeFile( $differences, $filename )
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
