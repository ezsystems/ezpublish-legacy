<?php
//
// Created on: <29-Jul-2002 13:19:03 bf>
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
eZ publish has a sophisticated role based permission system. This enables
you to control what users can and cannot do. The basic concept of the role
based permission system is that you specify what kind of function can be
performed by certain users.
</p>

<h2>User identification</h2>
<p>
Every visitor to eZ publish is identified as a user. If the user has not
logged into the system it will default to the anonymous user. The anonymous
user is a normal user in eZ publish which can be edited and assigned to user
groups.
</p>

<p>
Users which are logged in are identified in the same manner as the anonymous
user.
</p>

<h2>User groups</h2>
<p>
To simplify user administration users can be assigned to user groups. Every
user must be assigned to atleast one or more user groups.
</p>

<h2>Roles</h2>
<p>
The actual permissions are defined on a pr role basis. A role is a set of policies defining
what a user can do. A role can be shared by one or more users and
one or more user groups. To get access to a role a user must have
access to the role either by the user assignment or by one of the user groups.
</p>

<p>
A role contains zero or more policies. A policy is a granted access to a given module
or function. If a role contains no policies then the role will not grant access to anything.
</p>

<table class="example">
<th>Example roles</th>
</th>
<tr>
	<td>
    Anonymous
	</td>
	<td>
     Permission to read everything in a given section
	</td>
</tr>
<tr>
	<td>
    Administrator
	</td>
	<td>
     Permission to do everything
	</td>
</tr>
</table>

<img src="/doc/images/permission_overview.png" alt="Permissions" />
<h2>Policies</h2>
<p>
A policy grants access to a specific function in eZ publish. A policy consists of
the following parts; Module : Function : Context. You can on every level specify
wildcards. E.g. you want to grant everything to a role "*", another role will
get access to everything inside the content module "Content : *".
</p>

<table class="example">
<th>Example policies</th>
</th>
<tr>
	<td>
    *
	</td>
	<td>
     Permission to do everything
	</td>
</tr>
<tr>
	<td>
     Search : *
	</td>
	<td>
     Permission to do everything in the search module
	</td>
</tr>
<tr>
	<td>
     Content : Read : *
	</td>
	<td>
     Permission to do read content of every type
	</td>
</tr>
<tr>
	<td>
     Content : Create : ClassID in ( 1, 4 )
	</td>
	<td>
     Permission to do create content of class 1 or 4
	</td>
</tr>
<tr>
	<td>
     Content : Create : ClassID in ( 1, 4 ), SectionID in ( 1, 4 )
	</td>
	<td>
     Permission to do create content of class 1 or 4, in section 1 and 4.
	</td>
</tr>
</table>
