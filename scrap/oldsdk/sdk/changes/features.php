<?php
//
// Created on: <29-Jan-2003 15:47:17 gl>
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
$DocResult["title"] = "Key Features in eZ publish 3.0";

?>

<h1>Key Features in eZ publish 3.0</h1>

<h2>Installation improvements</h2>
<p>
eZ publish 3.0 runs with standard Apache 1.3 or 2.0, PHP 4.1 or newer, and MySQL or
PostgreSQL. No need to compile patches. In addition to this, it has an installation helper
that lets you do a large part of the installation in your browser.
</p>

<h2>Custom content</h2>
<p>
In eZ publish 3.0 you don't have to make your content fit a certain predefined, one-size-fits-all
content model. eZ publish lets you create your own content classes with exactly the attributes
you need. Create a class, select attributes and attribute data types, and begin creating content.
Without even editing a template.
</p>

<h2>Object oriented</h2>
<p>
eZ publish 3.0 is object oriented like the 2.2 series are, but better implemented. Where 2.2 had
several different modules, each with their own content tree, 3.0 has one content tree for all
kinds of objects. Although content objects are of different content classes, all content objects
are of the same PHP class, thus they can be treated equally in PHP and template code.
</p>

<h2>Advanced template engine</h2>
<p>
The new template engine, eZ template, is a sophisticated and integrated
template engine. The site designers can now quickly do powerful customizations
without touching PHP code. You can for instance tell an object to display itself in a given mode,
or add a text label to an image using a single line of template code.
</p>

<h2>Integrated search engine</h2>
<p>
eZ publish has a fully integrated search engine architecture which enables
you to use the powerful built in search engine or create a plugin for your
favourite search engine. In custom content you can specify which attributes that can be searched.
</p>

<h2>Role-based permissions</h2>
<p>
The permissions are specified by roles. The role system allows you to specify access
by module (content, shop, collaboration, etc.), by function (read, create, edit, remove, etc.),
by object owner, by object class, and by site section. This gives the site administrator
full freedom to customize who gets access to what.
</p>

<h2>Events and workflows</h2>
<p>
You can specify workflows that are run whenever an event happens. This lets you change how things
work and extend functionality without changing eZ publish code. For instance, when a shop user
buys something, you can create a workflow that asks him whether he wants to have his item wrapped
in gift paper.
</p>

<h2>Collaboration system</h2>
<p>
The new collaboration system can be used to organize and distribute tasks within a work group,
as an instant messaging system, and as a private todo list, among other things. It is participant
based, and designed to be customized to fit your needs.
</p>

<h2>Extensions</h2>
<p>
Despite the ability to create custom content and workflows, you might run into a situation where
you need to extend eZ publish code. The extension system lets you keep your code separated from
eZ publish code, making it safe to update to newer versions of eZ publish.
</p>

<h2>Translation and internationalisation</h2>
<p>
eZ publish continues to use the Qt linguist tool for translation, but the process is simplified.
It is also easier to use translations in templates, and internationalised templates are easier to
read for the template developer.
</p>

<h2>Documentation</h2>
<p>
The eZ publish SDK has full, cross linked API documentation with UML diagrams, and several tutorials
and other documents helping you getting up to speed with the new system.
</p>

<h2>Reusable Libraries</h2>
<p>
eZ publish comes with a set of PHP libraries that can be reused in other situations, such as
XML document creation, parsing and validating, SOAP communication, database connectivity,
image manipulation, template engine, and internationalisation.
</p>

<h2>Licensing</h2>
<p>
eZ publish is dual licensed. You can choose between the GNU GPL and the eZ publish Professional Licence.
The GNU GPL gives you the right to use, modify and redistribute eZ publish under certain conditions.
The GNU GPL licence is distributed with the software, see the file LICENCE. It is also available at
<a href="http://www.gnu.org/licenses/gpl.txt">http://www.gnu.org/licenses/gpl.txt</a>.
Using eZ publish under the terms of the GNU GPL is free of charge.
</p>

<p>
The eZ publish Professional Licence gives you the right to use the source code for making your own
commercial software. It allows you full protection of your work made with eZ publish. You may re-brand,
license and close your source code. eZ publish is not free of charge when used under the terms of the
Professional Licence. For pricing and ordering, please contact us at
<a href="mailto:info@ez.no">info@ez.no</a>.
</p>
