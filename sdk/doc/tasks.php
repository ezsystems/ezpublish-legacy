<?php
//
// Created on: <09-Aug-2002 15:55:16 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

$DocResult = array( 'title' => 'Tasks' );

?>

<p>
Tasks is a way of doing communication and planning with other users. It opens up a connection
between two users were communication can be done in the form of content objects. The connection
is open until either the creator cancels it or the receiver accepts/denies it.
The creator will see it as an outgoing task while the receiver sees it as an incoming task.
Tasks may have another tasks as parent creating a structured tree of related tasks.
</p>

<h1>Task</h1>
<p>
The contents of a task are.
</p>
<h2>Status</h2>
<p>
The status can be one of the following values.
</p>
<h3>Open</h3>
<p>
The task was just created and is active.
</p>
<h3>Closed</h3>
<p>
The task was closed by the receiver, meaning that it is done.
</p>
<h3>Canceled</h3>
<p>
The task was either canceled(denied) by the receiver or removed by the creator.
</p>

<h2>Connection Type</h2>
<p>
How the task is connected, it's either connected directly to a user which means that the task
is present on different machines or it's connected to the session which means that the task
is present only on that session on one machine.
Direct connections are used for persistent tasks while session connections are used for
temporary tasks.
</p>

<h2>Creator</h2>
<p>
The user who created the task and is the one managing the task, this user will see the task as outgoing.
</p>

<h2>Receiver</h2>
<p>
The user which receives the task and thus should do it. This user will see the task
as ingoing.
</p>

<h2>Parent Type</h2>
<p>
The type of the parent, if any. Is either Task, Assignment or Work-flow process.
</p>

<h2>Parent</h2>
<p>
The parent of the task or none if no parent. This is used to perform structured task handling
or for private redirections.
</p>

<h2>Messages</h2>
<p>
An ordered list of content objects which are sent back and forth between the users.
The messages are visible only by the creator or the receiver of the task.
</p>

<h1>Assignment</h1>
<p>
Assignments are similar to Tasks and share the same properties with some additions.
Assignments are used for giving some access to an object, class etc. and still maintain
the task like communication.
</p>
<h2>Access Type</h2>
<p>
The type of assignment, it is either Read which means that the receiver can view the
selected assignment. Or it is Read/Write which means that the receiver can both
view and edit the assignment. Read/Write assignments usually involves creating a new
version for the receiver to work on.
</p>
<h2>Object Type</h2>
<p>
The type of the object assigned, it can be Content Object, Content Class, Work-flow or
Role. Some types may have more detailed assignments.
</p>
<h3>Content Object</h3>
<p>
Content Objects are determined by an ID and a version. It's also possible to further
restrict to a certain language in a version.
</p>
<h3>Content Class</h3>
<p>
Content Classes are determined by an ID.
</p>
<h3>Work-flow</h3>
<p>
Work-flows are determined by an ID.
</p>
<h3>Role</h3>
<p>
Roles are determined by an ID.
</p>

<h2>Object</h2>
<p>
The object which was assigned.
</p>

<img src="/doc/design/uml/eztask.png">

<h1>Example</h1>

<p>Your tasks</p>

<table class="example">
<tr><th>Task</th></tr>
<tr><td>Status: Open</td></tr>
<tr><td>
DE release 2.2.7
</td></tr>
<tr><td><i>Draft of 20(3) assigned to you</i></td></tr>
<tr><td><hr/></td></tr>
<tr><td>
<ul>
<li>Assigned 20(3,eng-GB) to John Doe (Check spelling) - Open</li>
<li>Assigned Sub-Children of 20 to Jane Doe (Photos) - Open</li>
</ul>
</td></tr>
</table>

<br/>

<p>John Doe tasks</p>

<table class="example">
<tr><th>Task</th></tr>
<tr><td>Status: Open</td></tr>
<tr><td>
Check spelling in my article.
</td></tr>
<tr><td><i>Object <b>20</b> with version <b>3</b> and language <b>eng-GB</b> assigned to You</i></td></tr>
<tr><td><hr/></td></tr>
<tr><td>
<ul>
</ul>
</td></tr>
</table>

<br/>

<p>Jane Doe tasks</p>

<table class="example">
<tr><th>Task</th></tr>
<tr><td>Status: Open</td></tr>
<tr><td>
Take some photos.
</td></tr>
<tr><td><i>Sub-Children of Object <b>20</b> assigned to You</i></td></tr>
<tr><td><hr/></td></tr>
<tr><td>
<ul>
</ul>
</td></tr>
</table>


