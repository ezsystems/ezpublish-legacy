<?php
//
// Created on: <09-Feb-2004 09:06:24 dr>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

define( 'SHOW_TABLES_QUERY', <<<END
SELECT n.nspname as "Schema",
	c.relname as "Name",
	CASE c.relkind
		WHEN 'r' THEN 'table' 
		WHEN 'v' THEN 'view' 
		WHEN 'i' THEN 'index' 
		WHEN 'S' THEN 'sequence' 
		WHEN 's' THEN 'special'
	END as "Type",
	u.usename as "Owner"
FROM pg_catalog.pg_class c
	LEFT JOIN pg_catalog.pg_user u ON u.usesysid = c.relowner
	LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
WHERE c.relkind IN ('r','')
	AND n.nspname NOT IN ('pg_catalog', 'pg_toast')
	AND pg_catalog.pg_table_is_visible(c.oid)
ORDER BY 1,2
END
);

define( 'FETCH_TABLE_OID_QUERY', <<<END
SELECT c.oid,
	n.nspname,
	c.relname
FROM pg_catalog.pg_class c
	LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
WHERE pg_catalog.pg_table_is_visible(c.oid)
	AND c.relname ~ '^<<tablename>>$'
ORDER BY 2, 3;
END
);

define( 'FETCH_TABLE_DEF_QUERY', <<<END
SELECT a.attname,
	pg_catalog.format_type(a.atttypid, a.atttypmod),
	(SELECT substring(d.adsrc for 128) FROM pg_catalog.pg_attrdef d
		WHERE d.adrelid = a.attrelid AND d.adnum = a.attnum AND a.atthasdef) as default,
	a.attnotnull, a.attnum
FROM pg_catalog.pg_attribute a
WHERE a.attrelid = '<<oid>>' AND a.attnum > 0 AND NOT a.attisdropped
ORDER BY a.attnum
END
);

define( 'FETCH_INDEX_DEF_QUERY', <<<END
SELECT c.relname, i.*
FROM pg_catalog.pg_index i, pg_catalog.pg_class c
WHERE indrelid = '<<oid>>'
	AND i.indexrelid = c.oid
END
);

define( 'FETCH_INDEX_COL_NAMES_QUERY', <<<END
SELECT a.attnum, a.attname
FROM pg_catalog.pg_attribute a
WHERE a.attrelid = '<<indexrelid>>' AND a.attnum IN (<<attids>>) AND NOT a.attisdropped
ORDER BY a.attnum
END
);


class eZPgsqlSchema
{
	function read( $con )
	{
		$schema = array();
		$res = pg_query( $con, SHOW_TABLES_QUERY );

		while ( $row = pg_fetch_assoc( $res ) )
		{
			$table_name = $row['Name'];
            $schema_table['name'] = $table_name;
			$schema_table['fields'] = $this->fetchTableFields( $table_name, $con );
			$schema_table['indexes'] = $this->fetchTableIndexes( $table_name, $con );

			$schema[$table_name] = $schema_table;
		}
		$this->schema = $schema;
		return $this->schema;
	}

	/*!
	 * \private
	 */
	function fetchTableFields( $table, $con )
	{
		$fields = array();

		$res = pg_query( $con, str_replace( '<<tablename>>', $table, FETCH_TABLE_OID_QUERY ) );
		$row = pg_fetch_assoc( $res );
		$oid = $row['oid'];

		$query = str_replace( '<<oid>>', $oid, FETCH_TABLE_DEF_QUERY );
		$res = pg_query( $con, $query );
		while ( $row = pg_fetch_assoc ( $res ) )
		{
			$field = array();
			$autoinc = false;
			$field['type'] = $this->parseType( $row['format_type'], $field['length'] );
			if ( !$field['length'] )
			{
				unset( $field['length'] );
			}
			if ( $row['attnotnull'] == 't' )
			{
				$field['not_null'] = '1';
			}

            $field['default'] = false;
            if ( !isset( $field['not_null'] ) )
            {
                if ( $row['default'] === null )
                    $field['default'] = null;
                else
                    $field['default'] = (string)$this->parseDefault ( $row['default'], $autoinc );
            }
            else
			{
                $field['default'] = (string)$this->parseDefault ( $row['default'], $autoinc );
			}
            $numericTypes = array( 'float', 'int' );
            $blobTypes = array( 'tinytext', 'text', 'mediumtext', 'longtext' );
            if ( $field['type'] == 'varchar' )
            {
                if ( $field['default'] === false or
                     $field['default'] === null )
                {
                    $field['default'] = '';
                }
            }
            else if ( in_array( $field['type'], $numericTypes ) )
            {
                if ( $field['default'] == false)
                {
                    $field['default'] = '0';
                }
                else if ( $field['type'] == 'integer' )
                {
                    $field['default'] = (int)$field['default'];
                }
                else if ( $field['type'] == 'float' )
                {
                    $field['default'] = (float)$field['default'];
                }
            }
            else if ( in_array( $field['type'], $blobTypes ) )
            {
                // We do not want default for blobs.
                $field['default'] = false;
            }

			if ( $autoinc )
			{
				unset( $field['length'] );
				unset( $field['not_null'] );
				$field['default'] = false;
				$field['type'] = 'auto_increment';
			}
			$fields[$row['attname']] = $field;
		}

		return $fields;
	}

	/*!
	 * \private
	 */
	function fetchTableIndexes( $table, $con )
	{
		$indexes = array();

		$res = pg_query( $con, str_replace( '<<tablename>>', $table, FETCH_TABLE_OID_QUERY ) );
		$row = pg_fetch_assoc( $res );
		$oid = $row['oid'];

		$res = pg_query( $con, str_replace( '<<oid>>', $oid, FETCH_INDEX_DEF_QUERY ) );

		while ( $row = pg_fetch_assoc ( $res ) )
		{
			$fields = array();
			$kn = $row['relname'];

			if ( $row['indisprimary'] == 't' )
			{
				$indexes[$kn]['type'] = 'primary';
			}
			else
			{
				$indexes[$kn]['type'] = $row['indisunique'] == 't' ? 'unique' : 'non-unique';
			}

			/* getting fieldnames requires yet another query and it doesn't return it 'in order' either.
			 * grumbl, stupid pgsql :) */
			$column_id_array = split( ' ', $row['indkey'] );
			$att_ids = join( ', ',  $column_id_array );
			$query = str_replace( '<<indexrelid>>', $row['indrelid'], FETCH_INDEX_COL_NAMES_QUERY );
			$query = str_replace( '<<attids>>', $att_ids, $query );
			$fields_res = pg_query( $con, $query );
			while ( $fields_row = pg_fetch_assoc ( $fields_res ) )
			{
				$fields[$fields_row['attnum']] = $fields_row['attname'];
			}
			foreach ( $column_id_array as $rank => $id )
			{
				$indexes[$kn]['fields'][$rank] = $fields[$id];
			}
		}

		return $indexes;
	}

	function parseType( $type_info, &$length_info )
	{
		preg_match ( "@([a-z ]*)(\(([0-9]*)\))?@", $type_info, $matches );
		if ( isset( $matches[3] ) )
		{
			$length_info = $matches[3];
		}
		$type = $this->convertToStandardType ( $matches[1], $length_info );
		return $type;
	}

    function isTypeLengthSupported( $pgType )
    {
        switch ( $pgType )
        {
            case 'integer':
            case 'double precision':
            case 'real':
            {
                return false;
            } break;
        }
        return true;
    }

	function convertFromStandardType( $type, &$length )
	{
		switch ( $type )
		{
            case 'char':
            {
                if ( $length == 1 )
                {
                    return 'character';
                }
                else
                {
                    return 'character varying';
                }
            } break;
            case 'int':
            {
                return 'integer';
            } break;
            case 'varchar':
            {
                return 'character varying';
            } break;
            case 'longtext':
            {
                return 'text';
            } break;
            case 'mediumtext':
            {
			return 'text';
            } break;
            case 'text':
            {
                return 'text';
            } break;
            case 'float':
            case 'double':
            {
                return 'double precision';
            } break;
		default:
			die ( "ERROR UNHANDLED TYPE: $type\n" );
		}
	}

	function convertToStandardType( $type, &$length )
	{
		switch ( $type )
		{
            case 'bigint':
            {
                return 'int';
            } break;
            case 'integer':
            {
                $length = 11;
                return 'int';
            } break;
            case 'character varying':
            {
                return 'varchar';
            } break;
            case 'text':
            {
                return 'longtext';
            } break;
            case 'double precision':
            {
                return 'float';
            } break;
            case 'character':
            {
                $lenght = 1;
                return 'char';
            } break;
		default:
			die ( "ERROR UNHANDLED TYPE: $type\n" );
		}
	}

	function parseDefault( $default, &$autoinc )
	{
		if ( preg_match( "@^nextval\('([a-z_]+_s)'::text\)$@", $default ) )
		{
			$autoinc = 1;
			return '';
		}

		if ( preg_match( "@^(.*)::double precision@", $default, $matches ) )
		{
			return $matches[1];
		}

		if ( preg_match( "@^(.*)::bigint@", $default, $matches ) )
		{
			return $matches[1];
		}

		if ( preg_match( "@^'(.*)'::character\ varying$@", $default, $matches ) )
		{
			return $matches[1];
		}

		if ( preg_match( "@^'(.*)'::[a-zA-Z ]+$@", $default, $matches ) )
		{
			return $matches[1];
		}

		if ( preg_match( "@^'(.*)'$@", $default, $matches ) )
		{
			return $matches[1];
		}

		return $default;
	}

	/*!
	 * \private
	 */
	function generateAddIndexSql( $table_name, $index_name, $def )
	{
		switch ( $def['type'] )
		{
            case 'primary':
            {
                $sql = "ALTER TABLE ONLY $table_name ADD CONSTRAINT $index_name PRIMARY KEY ";
            } break;

            case 'non-unique':
            {
                $sql = "CREATE INDEX $index_name ON $table_name USING btree ";
            } break;

            case 'unique':
            {
                $sql = "CREATE UNIQUE INDEX $index_name ON $table_name USING btree ";
            } break;
		}
		$sql .= '( "' . join ( '", "', $def['fields'] ) . '" )';

		return $sql . ";\n";
	}

	/*!
	 * \private
	 */
	function generateDropIndexSql( $table_name, $index_name )
	{
		if ($def['type'] == 'primary' )
		{
			$sql = "ALTER TABLE DROP CONSTRAINT $index_name";
		}
		else
		{
			$sql = "DROP INDEX $index_name";
		}
		return $sql . ";\n";
	}

	/*!
	 * \private
	 */
	function generateFieldDef( $table_name, $field_name, $def, $add_default_not_null = true )
	{
		$sql_def = $field_name . ' ';

		if ( $def['type'] != 'auto_increment' )
		{
			$pgType = eZPgsqlSchema::convertFromStandardType( $def['type'], $def['length'] );
            $sql_def .= $pgType;
			if ( eZPgsqlSchema::isTypeLengthSupported( $pgType ) and isset( $def['length'] ) && $def['length'] )
			{
				$sql_def .= "({$def['length']})";
			}
			$sql_def .= ' ';
			if ( $add_default_not_null )
			{
                $sql_def .= eZPGSQLSchema::generateDefaultDef( false, false, $def );
                $sql_def .= eZPGSQLSchema::generateNullDef( false, false, $def );
			}
		}
		else
		{
			$sql_def .= "integer DEFAULT nextval('{$table_name}_s'::text) NOT NULL";
		}
		return $sql_def;
	}

    /*!
     \private
    */
    function generateDefaultDef( $table_name, $field_name, $def )
    {
        $sql_def = '';
        if ( $table_name and $field_name )
        {
            $sql_def .= "ALTER TABLE $table_name ALTER $field_name SET ";
        }
        if ( array_key_exists( 'default', $def ) )
        {
            if ( $def['default'] === null )
            {
                $sql_def .= "DEFAULT NULL ";
            }
            else if ( $def['default'] !== false )
            {
                $sql_def .= "DEFAULT '{$def['default']}' ";
            }
        }
        else if ( $table_name and $field_name )
        {
            return false;
        }
        return $sql_def;
    }

    /*!
     \private
    */
    function generateNullDef( $table_name, $field_name, $def )
    {
        $sql_def = '';
        if ( $table_name and $field_name )
        {
            $sql_def .= "ALTER TABLE $table_name ALTER $field_name SET ";
        }
        if ( isset( $def['not_null'] ) && ( $def['not_null'] ) )
        {
            $sql_def .= 'NOT NULL ';
        }
        else if ( $table_name and $field_name )
        {
            return false;
        }
        return $sql_def;
    }

	/*!
	 * \private
	 */
	function generateAddFieldSql( $table_name, $field_name, $def )
	{
		$sql = "ALTER TABLE $table_name ADD COLUMN ";
		$sql .= eZPgsqlSchema::generateFieldDef( $table_name, $field_name, $def, false ) . ";\n";
        $defaultSQL = eZPGSQLSchema::generateDefaultDef( $table_name, $field_name, $def );
        if ( $defaultSQL )
            $sql .= $defaultSQL . ";\n";
        $nullSQL = eZPGSQLSchema::generateNullDef( $table_name, $field_name, $def );
        if ( $nullSQL )
            $sql .= $nullSQL . ";\n";
        $sql .= "\n";
        return $sql;
	}

	/*!
	 * \private
	 */
	function generateAlterFieldSql( $table_name, $field_name, $def )
	{
		$sql = "ALTER TABLE $table_name RENAME COLUMN $field_name TO " . $field_name . "_tmp;\n";
		$sql .= "ALTER TABLE $table_name ADD COLUMN ";
		$sql .= eZPgsqlSchema::generateFieldDef( $table_name, $field_name, $def, false ) . ";\n";
        $defaultSQL = eZPGSQLSchema::generateDefaultDef( $table_name, $field_name, $def );
        if ( $defaultSQL )
            $sql .= $defaultSQL . ";\n";
        $nullSQL = eZPGSQLSchema::generateNullDef( $table_name, $field_name, $def );
        if ( $nullSQL )
            $sql .= $nullSQL . ";\n";
        $sql .= "UPDATE $table_name SET $field_name=" . $field_name . "_tmp;\n";
        $sql .= "ALTER TABLE $table_name DROP COLUMN " . $field_name . "_tmp;\n\n";
		return $sql;
	}

	/*!
	 * \private
	 */
	function generateDropFieldSql( $table_name, $field_name )
	{
		$sql = "ALTER TABLE $table_name DROP COLUMN $field_name";

		return $sql . ";\n";
	}

	/*!
	 * \private
	 */
	function generateSchemaFile( $schema )
	{
		$sql = '';

		foreach ( $schema as $table => $table_def )
		{
            $sql .= eZPGSQLSchema::generateTableSchema( $table, $table_def );
			$sql .= "\n\n";
		}

		return $sql;
	}

	/*!
	 * \private
	 */
	function generateTableSchema( $table, $table_def )
	{
		$sql = '';
        $sql_fields = array();
        /* First we need to check if we use auto increment fields as
         * sequences need to exist before we use them */
        foreach ( $table_def['indexes'] as $index_name => $index_def )
        {
            if ( $index_def['type'] == 'primary' )
            {
                $sql .= "CREATE SEQUENCE {$table}_s\n\tSTART 1\n\tINCREMENT 1\n\tMAXVALUE 9223372036854775807\n\tMINVALUE 1\n\tCACHE 1;\n\n";
            }
        }

        $sql .= "CREATE TABLE $table (\n";
        foreach ( $table_def['fields'] as $field_name => $field_def )
        {
            $sql_fields[] = "\t". eZPgsqlSchema::generateFieldDef( $table, $field_name, $field_def );
        }
        $sql .= join ( ",\n", $sql_fields ) . "\n);\n";

        foreach ( $table_def['indexes'] as $index_name => $index_def )
        {
            $sql .= eZPgsqlSchema::generateAddIndexSql( $table, $index_name, $index_def );
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
						$sql .= eZPGSQLSchema::generateAddFieldSql( $table, $field_name, $added_field );
					}
				}

				if ( isset ( $table_diff['changed_fields'] ) )
				{
					foreach ( $table_diff['changed_fields'] as $field_name => $changed_field )
					{
						$sql .= eZPGSQLSchema::generateAlterFieldSql( $table, $field_name, $changed_field );
					}
				}
				if ( isset ( $table_diff['removed_fields'] ) )
				{
					foreach ( $table_diff['removed_fields'] as $field_name => $removed_field)
					{
						$sql .= eZPGSQLSchema::generateDropFieldSql( $table, $field_name );
					}
				}

				if ( isset ( $table_diff['added_indexes'] ) )
				{
					foreach ( $table_diff['added_indexes'] as $index_name => $added_index)
					{
						$sql .= eZPGSQLSchema::generateAddIndexSql( $table, $index_name, $added_index );
					}
				}

				if ( isset ( $table_diff['changed_indexes'] ) )
				{
					foreach ( $table_diff['changed_indexes'] as $index_name => $changed_index )
					{
						$sql .= eZPGSQLSchema::generateDropIndexSql( $table, $index_name );
						$sql .= eZPGSQLSchema::generateAddIndexSql( $table, $index_name, $changed_index );
					}
				}
				if ( isset ( $table_diff['removed_indexes'] ) )
				{
					foreach ( $table_diff['removed_indexes'] as $index_name => $removed_index)
					{
						$sql .= eZPGSQLSchema::generateDropIndexSql( $table, $index_name );
					}
				}
			}
		}
		if ( isset( $differences['new_tables'] ) )
		{
			foreach ( $differences['new_tables'] as $table => $table_def )
			{
                $sql .= eZPGSQLSchema::generateTableSchema( $table, $table_def );
            }
        }
		return $sql;
	}

	function writeUpgradeFile( $differences, $filename )
	{
		$fp = @fopen( $filename, 'w' );
		if ( $fp )
		{
			fputs( $fp, eZPgsqlSchema::generateUpgradeFile( $differences ) );
			fclose( $fp );
			return true;
		}
        else
        {
			return false;
		}
	}

	function writeSchemaFile( $schema, $filename )
	{
		$fp = @fopen( $filename, 'w' );
		if ( $fp )
		{
			fputs( $fp, eZPgsqlSchema::generateSchemaFile( $schema ) );
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
