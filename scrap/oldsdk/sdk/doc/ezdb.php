<?php
//
// Created on: <12-Jun-2002 09:14:53 bf>
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
eZ db is a database abstraction library. It provides a uniform interface
to MySQL and PostgreSQL.
</p>

<h2>Features</h2>
<p>
eZ db provides functionality for developing database independant PHP applications.
To make the it easier to support the different databases we've defined a subset
of datatypes used. The types used is:
</p>

<ul>
<li>int - integers, date and time as UNIX timestamp, enums and boolean</li>
<li>float - float and prices</li>
<li>varchar - short text strings ( < 255 chars )</li>
<li>text - large text objects like article contents</li>
</ul>

<h2>Useful links</h2>
<ul>
<li><a href="http://www.mysql.com">MySQL</a></li>
<li><a href="http://postgresql.org">PostgreSQL</a></li>
</ul>
