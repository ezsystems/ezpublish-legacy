<?php
//
// Created on: <09-Aug-2002 15:55:16 bf>
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
Workflows are used to restrict or control a users action under certain
situations. You can e.g. create a workflow which consists of editor approval
to ensure that objects published are verified by certified personell first.
</p>

<h2>Triggers</h2>
<p>
A workflow is executed by a trigger. If a trigger matches the requirements
a workflow will be executed. Then the workflow will delay a certain function.
Triggers can be placed before and after a function is executed. If the trigger
will execute a workflow then the actual execution of a function will be delayed
until the workflow finishes successfully, if it finishes at all.
</p>

<h2>Workflows</h2>
<p>
A workflow is a series of events which will be executed in order. An event can
require user interaction like an approval.
</p>

<h2>Assigning of workflows</h2>
<p>
When you want to assign workflows you need to connect a workflow with a trigger.
Normally every function will have a pre and post trigger. Some functions also have
special custom triggers.
</p>

<p>
If no workflows are assigned, the functions will be executed without workflow
interruption.
</p>

<h2>Different types of workflows</h2>
<p>
Workflows can be divided into the following basic event types:
</p>
<ul>
    <li><b>Direct events</b> - a user will be prompted for some input, this
    input will then be validated. If the input was validated then the event
    is executed and the next event will be triggered.
    </li>
    <li><b>Redirect events</b> - a workflow will redirect the event to another
    user. This event will then come up in the the users task list. The user can
    continue/finish the event at any time.
    </li>
    <li><b>Internal events</b> - this is the kind of events which does not require
    user input. E.g. wait for 60 seconds, publish object, unpublish object, index in
    search engine(s), send e-mail notification(s).
    </li>
</ul>

<h2>Workflow rejection</h2>
<p>
If a workflow does not complete, e.g an editor does not approve the content or
payment was not successful, the workflow will reject the event.
</p>

<h2>Combination of workflows</h2>
<p>
To get complex functionality from workflows you can combine two or more workflows.
You can have one workflow event which triggers another workflow. That way you can
create multiplexing functionality from basic workflows. You will also be able to
reuse some workflows.
</p>

<h2>Example workflow usage</h2>
<p>
Some examples of workflow usage:
</p>

<ul>
	<li><b>Document approval</b> - approve by one or more editors before approval</li>
	<li><b>Order pipeline</b> - what the user needs to fill out before the order is completed </li>
</ul>
