<?php
//
// Created on: <01-Dec-2002 12:43:53 sp>
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

?>

<h1>Publish workflow</h1>

<p>
One of the most powerful features of eZ publish 3 is that you can create custom workflow.
Worflow is a mechanism implemented to give a possibility to customize or add new fitures to operations in eZ Publish.
</p>

<p>
eZ Publish 3 already has workflow events needed to create approval publishing workflow.
</p>

<h5> Approval workflow</h5>
<p>
Approval is a worklflow which allows you to set up approval of publishing objects in some plases of your site. Which runs for some user groups. It consists of "Approve" and "Message" event. "Approve" event creates a task when it runs and "Message" creates a mesage and sends it bak to the user when task is closed.
</p>
<p>
To set up approval workflow you need to do next tree steps:
<ul>
<li> Create workflow</li>
<li> Setup workflow parameters</li>
<li> Connect workflow to the operation</li>
</ul>
</p>
<h5>Create workflow </h5>
<p>
To create new workflow you need to get into one of worklowgroups and click "New" there. I will create new workflow and redirect you to an edit page. There  you should fillup "name" text entry  with the name of your workflow, smth. like "Approval by editor" it is up to you. After that select Event/Publish from drop down and click "New". Ater that select Event/Message from dropdown and click "New". Now you have added two events to the workflow.
</p>
<img src="/doc/images/workflow_approve1.png" alt="Publish workflow" />
<h5>Workflow customizing </h5>
<p>
To customize approval workflow you need to select from  lists values  for editor , sections, and groups.  Where editor -  person to whom system sends approval tasks, sections - set of sections in which approval workflow takes effect ( tasks will be created only if object is published in one of selected sections ), Users without approval is  set of user groups  users of which do not require approval when publish objects.
</p>
<p>
After seting up that parameters click "Store" button. Workflow is created.
</p>
<h5> Connect workflow to the operation</h5>

<p>
Go to the "Triggers" ( under "Set up" box ) and select newlly created workflow from dropdown in front of content-publish-before.
</p>
<img src="/doc/images/triggers.png" alt="Triggers" />
<p>
Click "Store" button. Workflow is connected to the operation now.
</p>

