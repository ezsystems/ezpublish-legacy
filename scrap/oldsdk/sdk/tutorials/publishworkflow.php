<?php
//
// Created on: <01-Dec-2002 12:43:53 sp>
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

<h1>Approval workflow</h1>

<p>
One of the most powerful features of eZ publish 3 is that you can create custom workflows.
Workflow is a mechanism implemented to give you the possibility to customize or add new features
to operations in eZ Publish. eZ Publish 3 already has the workflow events needed to create a
publishing approval workflow.
</p>

<p>
The workflow we will create will allow you to set up approval of object publishing in some places of your site,
for certain user groups. It consists of an "Approve" event. The "Approve" event creates a collaboration item
when it runs, the collaboration item will link the author and the approver together were they may discuss
the content object and the approver will then decide what to do with it.
</p>

<p>
To set up an approval workflow you need to do next tree steps:
<ul>
<li>Create the workflow</li>
<li>Setup workflow parameters</li>
<li>Connect the workflow to the operation</li>
</ul>
</p>

<h2>Create the workflow</h2>

<p>
To create the new workflow you need to get into one of the workflow groups and click "New" there. It will create a new workflow and redirect you to an edit page. There you should fill in the "name" text entry with the name of your workflow, something like "Approval by editor", it is up to you. After that select Event/Approve from the drop down and click "New". Now you have created the necessary workflow.
</p>

<p>
<img src="/doc/images/workflow_approve1.png" alt="Approval workflow" />
</p>

<h2>Setup workflow parameters</h2>

<p>
To customize the approval workflow you need to choose the values for editor, sections, and user groups. Editor is the person to whom the system sends approval tasks. Sections are the sections in which the approval workflow takes effect (tasks will be created only if the object is published in one of the selected sections). Users without approval are the user groups whose users do not require approval when publishing objects. After setting up these parameters click the "Store" button.
</p>

<h2>Connect the workflow to the operation</h2>

<p>
Go to the "Triggers" list and select the newly created workflow from the dropdown list on the content - publish - before
line.
</p>

<p>
<img src="/doc/images/triggers.png" alt="Triggers" />
</p>

<p>
Click the "Store" button. Now the workflow is connected to the operation.
</p>
