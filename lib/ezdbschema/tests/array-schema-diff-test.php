<?php
//
// Created on: <28-Jan-2004 16:22:02 dr>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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

include ('lib/ezdbschema/classes/ezdbschema.php');
include ('lib/ezdbschema/classes/ezdbschemachecker.php');

$dbschema1 = new eZDbSchema();
$schema1 = $dbschema1->readArray( '/tmp/schema1.php' );

$dbschema2 = new eZDbSchema();
$schema2 = $dbschema2->readArray( '/tmp/schema2.php' );

$differences = eZDbSchemaChecker::diff( $schema1, $schema2 ); /* empty 2nd param force new script */

var_dump($differences);
ezDbSchema::writeUpgradeFile( $differences, 'schema-diff.php' );

?>
