<?php
//
// Created on: <21-Apr-2004 11:04:30 kk>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

/*! \file ezdbschemainterface.php
 Database schema abstraction layer.
*/

/*! \defgroup eZDBSchema Database schema abstraction layer */

/*!
  \class eZDBSchemaInterface ezdbschemainterface.php
  \ingroup eZDBSchema
  \brief This class provide interface for DB schema library
*/

class eZDBSchemaInterface
{
    /*!
     Constructor

     \param eZDB instance

     \sa eZDB
     */
    function eZDBSchemaInterface( $dbInstance )
    {
        $this->DBInstance = $dbInstance;
    }

    /*!
     \pure
     Get SQL db schema

     \return DB schema array
    */
    function schema()
    {
    }

    /*!
     \pure
     Write upgrade sql to file

     \param difference array
     \param filename
    */
	function writeUpgradeFile( $differences, $filename )
    {
        $fp = @fopen( $filename, 'w' );
		if ( $fp )
		{
			fputs( $fp, $this->generateUpgradeFile( $differences ) );
			fclose( $fp );
			return true;
		}
        else
        {
			return false;
		}
    }

    /*!
      Write SQL schema definition to file

      \param filename
    */
    function writeSQLSchemaFile( $filename )
    {
        $fp = @fopen( $filename, 'w' );
		if ( $fp )
		{
			fputs( $fp, $this->generateSchemaFile( $this->schema() ) );
			fclose( $fp );
			return true;
		}
        else
        {
			return false;
		}
    }

    /*!
      Write PHP schema definition to file using PHP serialized format.

      \param filename
    */
    function writeSerializedSchemaFile( $filename )
    {
        $fp = @fopen( $filename, 'w' );
		if ( $fp )
		{
			fputs( $fp, serialize( $this->schema() ) );
			fclose( $fp );
			return true;
		}
        else
        {
			return false;
		}
    }

    /*!
      Write PHP schema definition to file using PHP array structures.

      \param filename
    */
    function writeArraySchemaFile( $filename )
    {
        $fp = @fopen( $filename, 'w' );
		if ( $fp )
		{
            include_once( 'lib/ezutils/classes/ezphpcreator.php' );
            $text = "\$schema = ";
            $text .= eZPHPCreator::variableText( $this->schema(), strlen( $text ), 0, false );
            $text .= ";\n";
			fputs( $fp, '<?' . 'php' . "\n" . $text . "\n" . '?>' );
			fclose( $fp );
			return true;
		}
        else
        {
			return false;
		}
    }


    /*!
     \private
     \param database schema
     \return schema for file output
    */
    function generateSchemaFile( $schema )
	{
		$sql = '';

		foreach ( $schema as $table => $table_def )
		{
            $sql .= $this->generateTableSchema( $table, $table_def );
			$sql .= "\n\n";
		}

		return $sql;
	}

	/*!
	 * \private
	 */
	function generateUpgradeFile( $differences )
	{
		$sql = '';

		/* Loop over all 'table_changes' */
		if ( isset( $differences['table_changes'] ) )
		{
			foreach ( $differences['table_changes'] as $table => $table_diff )
			{
				if ( isset ( $table_diff['added_fields'] ) )
				{
					foreach ( $table_diff['added_fields'] as $field_name => $added_field )
					{
						$sql .= $this->generateAddFieldSql( $table, $field_name, $added_field );
					}
				}

				if ( isset ( $table_diff['changed_fields'] ) )
				{
					foreach ( $table_diff['changed_fields'] as $field_name => $changed_field )
					{
						$sql .= $this->generateAlterFieldSql( $table, $field_name, $changed_field );
					}
				}
				if ( isset ( $table_diff['removed_fields'] ) )
				{
					foreach ( $table_diff['removed_fields'] as $field_name => $removed_field)
					{
						$sql .= $this->generateDropFieldSql( $table, $field_name );
					}
				}

				if ( isset ( $table_diff['added_indexes'] ) )
				{
					foreach ( $table_diff['added_indexes'] as $index_name => $added_index)
					{
						$sql .= $this->generateAddIndexSql( $table, $index_name, $added_index );
					}
				}

				if ( isset ( $table_diff['changed_indexes'] ) )
				{
					foreach ( $table_diff['changed_indexes'] as $index_name => $changed_index )
					{
						$sql .= $this->generateDropIndexSql( $table, $index_name );
						$sql .= $this->generateAddIndexSql( $table, $index_name, $changed_index );
					}
				}
				if ( isset ( $table_diff['removed_indexes'] ) )
				{
					foreach ( $table_diff['removed_indexes'] as $index_name => $removed_index)
					{
						$sql .= $this->generateDropIndexSql( $table, $index_name );
					}
				}
			}
		}
		if ( isset( $differences['new_tables'] ) )
		{
			foreach ( $differences['new_tables'] as $table => $table_def )
			{
                $sql .= $this->generateTableSchema( $table, $table_def );
            }
        }
        if ( isset( $differences['removed_tables'] ) )
        {
            foreach ( $differences['removed_tables'] as $table => $table_def )
            {
                $sql .= $this->generateDropTable( $table );
            }
        }
        return $sql;
    }

    /*!
     \pure
	 \private
	 */
	function generateTableSchema( $table, $table_def )
    {
    }

    /*!
	 \private
     \pure
	 */
	function generateAlterFieldSql( $table_name, $field_name, $def )
	{
    }

    /*!
	 \private
     \pure
	 */
	function generateAddFieldSql( $table_name, $field_name, $def )
	{
    }

    /*!
	 * \private
	 */
	function generateDropFieldSql( $table_name, $field_name )
	{
		$sql = "ALTER TABLE $table_name DROP COLUMN $field_name";

		return $sql . ";\n";
	}

    /// eZDB instance
    var $DBInstance;
}

?>
