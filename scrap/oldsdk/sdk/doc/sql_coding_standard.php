<?php
//
// Created on: <03-Jun-2002 13:35:42 bf>
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

$DocResult = array();
$DocResult["title"] = "SQL Coding Standard";
?>
<h2>Syntax</h2>
<p>
SQL commands are written in all capital letters.
</p>

<pre class="example">
SELECT * FROM
  ezuser
WHERE
  id='$user_id'
HAVING
  age > '42'
LIMIT 0,20

SELECT a, b as c FROM
  ezcontentobject,
  ezcontentobject_attribute,
  ezcontentobject_version
WHERE
  ezcontentobject.id = ezcontentobject_version.contentobject_id AND
  ezcontentobject.id = '42'
LIMIT 0, 20
</pre>

<h2>Tables</h2>
<p>
Table names should be in English. The names should be grammatically correct.
Table names with several words should be separated with capital letters.
</p>
<p>
Example:
</p>
<pre class="example">
ezuser
ezcontentobject_version
ezcontentclass_attribute
</pre>
<p>
Row names should be named in a similar manner.
</p>
<p>
Example:
</p>
<pre class="example">
ezuser
{
  id int,
  first_name varchar(150),
  last_name varchar(150),
  login varchar(150)
}
</pre>

