<?php
//
// Created on: <28-Jan-2004 15:46:30 dr>
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

class eZDbSchema
{
    /*!
     Create new instance of eZDBSchemaInterface. placed here for simplicity.

     \param eZDB instance (optional), if none provided, eZDB::instance() will be used.
     \return new Instance of eZDBSchema, false if failed
    */
    function instance( $db = false )
    {
        if ( $db === false )
        {
            include_once( 'lib/ezdb/classes/ezdb.php' );
            $db = eZDB::instance();
        }

        switch( $db->databaseName() )
        {
            case 'mysql':
            {
                include_once( 'lib/ezdbschema/classes/ezmysqlschema.php' );
                return new eZMysqlSchema( $db );
            } break;

            case 'postgresql':
            {
                include_once( 'lib/ezdbschema/classes/ezpgsqlschema.php' );
                return new eZPgsqlSchema( $db );
            } break;

            default:
            {
                eZDebug::writeError( 'No schema handler for database type : ' . $db->databaseName(),
                                     'eZDBSchema::instance()' );
            } break;
        }

        return false;
    }

    /*!
     \static
    */
	function read( $filename )
	{
        include_once( 'lib/ezfile/classes/ezfile.php' );
		return unserialize( eZFile::getContents( $filename ) );
	}

	/*!
	 * \private
	 */
	function generateUpgradeFile( $differences )
	{
		$diff = var_export( $differences, true );
		return ( "<?php \n\$diff = \n" . $diff . ";\nreturn \$diff;\n?>\n" );
	}

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
