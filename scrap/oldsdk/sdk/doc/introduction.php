<?php
//
// Created on: <11-Jun-2002 09:44:28 bf>
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
$DocResult["title"] = "Introduction";
?>

<p>
This document is an introduction to the eZ publish<sup>TM</sup> SDK.
</p>

<h2>Notations</h2>
<p>
In eZ publish<sup>TM</sup> we use some notations which describes central functionality.
This is important to understand in order to user the SDK and eZ publish<sup>TM</sup> in
general.
</p>

<h3>Kernel</h3>
<p>
The eZ publish<sup>TM</sup> kernel is the core code of eZ publish and controls all low level
functionality like content classes, content objects, workflows, permissions etc.
</p>

<h2>eZ publish<sup>TM</sup> libraries</h2>
<p>
eZ publish<sup>TM</sup> libraries are the building blocks of eZ publish<sup>TM</sup>.
The libraries are general purpose and object oriented PHP libraries. The following
libraries are a part of the eZ publish<sup>TM</sup> SDK.
</p>
<ul>
<li>eZ db - database access</li>
<li>eZ i18n - translation</li>
<li>eZ image - image handling</li>
<li>eZ locale - localization (times, dates, currency etc.)</li>
<li>eZ soap - SOAP (Simple Object Access Protocol) implementation</li>
<li>eZ template - template engine</li>
<li>eZ utils - various utilities</li>
<li>eZ xml - XML handling</li>
</ul>

<h3>Content class</h3>
<p>
In eZ publish<sup>TM</sup> you can define your own content classes. This is an object definition
if you like. A class defines the structure of the building blocks in eZ publish, objects.
Some examples of content classes are article, forum, product and user account.
</p>

<h3>Content class attribute</h3>
<p>
Each class consists of several attributes. The attributes or elements defines the
name and behaviour of the class. For instance, a simple article content class may consist
of these attributes: title, intro and body. The title could be a string data type, the intro
and body could be XML formatted text.
</p>

<h3>Content object</h3>
<p>
A content object is an instance of a defined content class. While the content class defines
the structure of content objects, the object has the actual content.
Content objects are the actual documents/articles etc. that are stored in eZ publish.
</p>

<h3>Content object attribute</h3>
<p>
Each content object consists of several attributes. These attributes is defined
by a content class attribute. The content object attribute contains the actual
data.
</p>

<h3>Content object version</h3>
<p>
Each content object can exist in several versions. Each time the object is changed a new version is
created. This is to keep track of changes and to have the possibility to revert changes in an object.
</p>

<h3>Information collector</h3>

<p>
Information collector is a class attribute setting which says if the attribute
can be used to get input from the user. This is used for instance when creating feedback
forms on a web site.
</p>

<h3>Site design</h3>
<p>
The site design is the visual (and in some cases also the logical) design of the site. It can consist
of templates, images, fonts, style sheets and other things. The page that the user sees can be built up
of more than one site design, for instance the admin interface uses the standard design and a few other
templates in the admin design. The standard design can be used as a fallback when a template of the
current design does not exist.
</p>

<h3>Site access</h3>
<p>
A site can be accessed in multiple ways, these are called site accesses and can control
the behaviour of certain modules, the sitedesign and many other things. This is often used
to create subsites or different views for different roles. An example of this is the
admin interface of eZ publish.
</p>

<h3>Access control</h3>
<p>
Access control can be used to limit the allowed modules and module views that are available
trough a site access. It also controls things like user authentication.
</p>
