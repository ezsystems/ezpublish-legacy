<?php
//
// Created on: <19-Jun-2002 18:48:00 bf>
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

<h1>Permissions</h1>

<p>
The restriction of what a user can do is handled by roles in eZ publish.
</p>

<h2>Role</h1>
<p>
Roles consist of a policy set, where each policy gives the user access to
a resource. Resources may be accessed in contect, e.g. you can read content in
section 42.
</p>

<p>
Roles is set up for the different roles users has on a site. E.g. you could
have visitor, journalist, editor and administrator. The different roles would
grant access to different resources.
</p>

<p>
Roles can be combined for simpler administration. E.g. you can have one role which
gives access to reading content and another role
</p>

<p>
A role can be assigned to any user or user group (combinations may be used).
</p>

<h2>Policy</h2>
<p>
A policy defines a certain resource. A policy defines a resource using the
following elements.
</p>

<ul>
	<li>Module</li>
	<li>Function</li>
	<li>Parameter(s)</li>
</ul>

<h3>Example policies</h3>

<table class="example">
<tr>
	<th>
	Module
	</th>
	<th>
	Function
	</th>
	<th>
	Parameters
	</th>
	<th>
	Comment
	</th>
</tr>
<tr>
	<td>
    Content
	</td>
	<td>
    Edit
	</td>
	<td>
    SectionID=42,
    Owner=self
	</td>
	<td>
	Acces to edit all content in section 42
    where the user is owner of the content.
	</td>
</tr>
<tr>
	<td>
    Content
	</td>
	<td>
    Read
	</td>
	<td>
    *
	</td>
	<td>
	Acces to read all content
	</td>
</tr>
<tr>
	<td>
    *
	</td>
	<td>
    -
	</td>
	<td>
	-
	</td>
	<td>
	Access to do everything
	</td>
</tr>
</table>

<h2>Permission check</h2>
<p>
The first thing that needs to be done is to identify the user.
When you have a user id you will know which roles this user
has. From these roles you will get the permission list.
</p>
