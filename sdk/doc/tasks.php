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
<h3>Temporary</h3>
<p>
The task was just created but is not finished.
</p>
<h3>Open</h3>
<p>
The task was just created and is active.
</p>
<h3>Closed</h3>
<p>
The task was closed by the receiver, meaning that it is done.
</p>
<h3>Cancelled</h3>
<p>
The task was either cancelled(denied) by the receiver or removed by the creator.
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
as ingoing. Only used when connection type is set to User.
</p>

<h2>Session Hash</h2>
<p>
The hash of the session which the task is connected. Only used when connection type is set to Session.
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

<h1>Class Diagram</h1>
<p>
This diagram shows a conceptual design for tasks, the classes are very abstract and may not be implemented like this.
</p>
<img src="/doc/design/uml/eztask.png">

<h1>Database Diagram</h1>
<p>
Here the class task and assignment has been merged together in one table. Some of the fields which represents special
behaviour are present below.
</p>
<img src="/doc/design/uml/eztask_db.png">
<h2>eztask</h2>
<h3>task_type</h3>
<p>
Will contain either 1 or 2, 1 meaning Task and 2 Assignment.
</p>
<h3>status</h3>
<p>
Will contain either 1,2 or 3, 1 meaning Open, 2 meaning Closed and 3 Cancelled.
</p>
<h3>connection_type</h3>
<p>
Will contain either 1 or 2, 1 meaning User and 2 Session.
</p>
<h3>parent_type</h3>
<p>
Will contain either 0, 1, 2 or 3, 0 meaning None, 1 Task, 2 Assignment and 3 Work-Flow process.
</p>
<h3>access_type</h3>
<p>
Will contain either 0, 1 or 2, 0 meaning None, 1 Read and 2 Read/Write.
</p>
<h3>object_type</h3>
<p>
Will contain either 0, 1, 2, 3 or 4, 0 meaning None, 1 ContentObject, 2 ContentClass, 3 Work-Flow and 4 Role.
</p>
<h2>eztask_message</h2>
<h3>creator_type</h3>
<p>
Will contain either 1 or 2, 1 meaning Task Creator and 2 Task Receiver.
</p>

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
