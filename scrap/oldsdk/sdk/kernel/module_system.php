<?php
//
// Created on: <16-Aug-2002 08:57:54 bf>
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

<h1>Module system</h1>

<p>
eZ publish is module based. The eZ publish kernel functionality is available
using different interfaces. Interface implementation is plugin based and the
current implementations are Web and SOAP. The Web interface is the normal
browser based eZ publish interface which outputs HTML. The SOAP interface is a
general SOAP interface useful for integration with other systems using
industri standard XML messages.
</p>

<img src="/doc/images/module.png" alt="Module system" />

<h2>Module directory structure</h2>
<p>
There are different types of modules which can be placed in different places
in the eZ publish directory structure. The kernel modules are located under
/kernel/.
</p>

<pre class="example">
/kernel/content/  <- content module
/kernel/content/module.php <- module definition

/kernel/content/web/ <- web implementation
/kernel/content/web/view.php <- web implementation of content / view

/kernel/content/soap/ <- soap implementation
/kernel/content/soap/view.php <- soap implementation of content / view
</pre>

<h2>Module definition</h2>
<p>
Each module has it's own definition file. This file describes the available
functionality.
</p>

<ul>
	<li>List of available functions ( all functions may not be implemented in all interfaces )</li>
    <li>List of parameters to each function</li>
    <li>List of context variables which can be used for permissions</li>
</ul>

<h2>Workflows</h2>
<p>
Every function in every module will get triggers, pre and post, which
can execute a workflow. These workflows can be used to add custom functionality
or to add special permission checking. Read the
<a href="/sdk/kernel/view/object_workflow/">workflow documentation</a> for 
details.
</p>

<h2>Permissions</h2>
<p>
The available modules, functions and context variables are used when creating
permission policies. These policies define what a specific user has access to
in the system. Permissions are checked <i>before</i> the pre workflow is trigged,
i.e. if you don't have access to a function the pre workflow will <i>never</i> be
trigged.
<a href="/sdk/kernel/view/permissions/">permission documentation</a> for 
details.

</p>

