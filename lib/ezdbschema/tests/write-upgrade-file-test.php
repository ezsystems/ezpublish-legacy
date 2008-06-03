<?php
//
// Created on: <30-Jan-2004 10:37:22 dr>
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

include ('lib/ezdbschema/classes/ezdbschema.php');
include ('lib/ezdbschema/classes/ezmysqlschema.php');
include ('lib/ezdbschema/classes/ezpgsqlschema.php');
include ('lib/ezdbschema/classes/ezdbschemachecker.php');

$dbschema1 = new eZDbSchema();
$schema1 = $dbschema1->readArray( '/tmp/schema1.php' );

$dbschema2 = new eZDbSchema();
$schema2 = $dbschema2->readArray( '/tmp/schema2.php' );

$differences = eZDbSchemaChecker::diff( $schema1, $schema2 ); /* empty 2nd param force new script */

$foo = new eZMysqlSchema( nULL );
$foo->writeUpgradeFile( $differences, '/tmp/schema-diff.mysql' );

$foo = new eZPgsqlSchema( nULL );
$foo->writeUpgradeFile( $differences, '/tmp/schema-diff.pgsql' );

?>
