<?php
//
// Created on: <29-May-2002 09:52:16 bf>
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

?>
<p>
The eZ db&trade; library is an abstract interface to the supported databases. This
enabled you to create database independent PHP applications.
</p>

<h1>Connect to the database</h1>
<p>
eZ db&trade; will automatically connect to the current database and use the database
implementation needed. To figure out this you need to have the correct settings in the
configuration file settings/site.ini. There you can set the server to connect to, the
username and password to use and which database should be used. You can also set which
socket to use, this setting is specific for MySQL. You can enable or disable SQLOutput
this will help you track all the database calls and help you debug when developing
applications.
</p>

<pre class="example">
[DatabaseSettings]
Server=localhost
User=nextgen
Password=nextgen
Database=nextgen
Socket=disabled
SQLOutput=enabled
</pre>
<p>
When you have your library properly configured you can just use the database instance
to run queries to the database.
</p>
<pre class="example">
  // include the library
  include_once( "lib/ezdb/classes/ezdb.php" );

  // fetch the database instance
  $db =& eZDB::instance();
</pre>
