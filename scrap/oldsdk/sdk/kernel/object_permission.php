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

<p>
All content objects have permissions. These permissions control what a user
can and cannot do with eZ publish.
</p>

<h2>Predefined user roles</h2>
<p>
There are two predefined user roles in eZ publish. These users are <i>always</i>
present in eZ publish and cannot be deleted. The two defined users are:
</p>
<ul>
<li>anonymous</li>
<li>root</li>
</ul>
<p>
The <i>anonymous</i> user is a user which is not logged into the system. This
user defines the default behaviour on a site which does not require logins.
The <i>root</i> user is the built in administrator user account. The <i>root</i>
user has permissions to do <i>everyting</i> in eZ publish.
</p>

<h2>User groups</h2>
<p>
All permissions are set on a group basis. There is one user group, <i>all</i> which is the
default user group which defines the default permission settings.
</p>

<h2>Permission list</h2>
<p>
In eZ publish there are defined the following permissions. The permissions
restrict the four basic actions a user can perform in a content management
system. The different actions are:
</p>
<ul>
<li>Read </li>
<li>Create </li>
<li>Edit </li>
<li>Remove </li>
</ul>

<p>
Every object can adjust these permissions. You can enable one or more user user groups
to the given action. E.g. you can let <i>all</i> read and only <i>administrators</i>
create, edit and remove.
</p>

<h2>Shared permissions</h2>
<p>
In large content mangement systems you can get millons of objects. These objects will
be very hard to manage if you must set the permissions on every object. Therefore you
can choose to share permission settings between objects. The normal behaviour is that
permissions are inherited from the parent object.
</p>

<h2>Workflows</h2>
<p>
After an action is performed a workflow is started. This workflow defines the actions
that will take place before an action is completed. For example you can let every user
create a content object, but have a workflow with an editor approval. The editor can
then reject all content objects which should not be published. That way you can mix
the use of permissions and workflows to get a fine grained content management system.
</p>

<h2> Roles oveview </h2>
<p>

When user tries to access function in eZ Publish the first thing checked is the user permissions
to access this module and this function. eZ Publish fetches user roles from database or session variables. Access to function is checked by calling  <i> checkUri() </i> method of eZRole class.

After that before 
</p>

<img src="/doc/images/role.png" alt="Roles" />
