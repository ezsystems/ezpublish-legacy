<?php
//
// Created on: <11-Jun-2002 09:44:28 bf>
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

$DocResult = array();
$DocResult["title"] = "Introduction";
?>

<p>
This document is an introduction to eZ publish<sup>TM</sup> SDK. The eZ publish<sup>TM</sup> SDK
is divided into two parts, one library part which contains general purpose PHP libraries
and one eZ publish kernel part. The eZ publish kernel handles the basic functionality
of eZ publish like content classes, content objects, workflow, permissions etc.
</p>

<h2>Notations</h2>
<p>
In eZ publish<sup>TM</sup> we use some notations which describes central functionality.
This is important to understand in order to user the SDK and eZ publish<sup>TM</sup> in
general.
</p>
<h3>Content class</h3>
<p>
In eZ publish you can define your own content classes. This is an object definition if you like.
Some examples of content classes are article, forum, product and user account.
</p>

<h3>Content class attribute</h3>
<p>
Each class consists of several attributes. The attributes or elements defines the
name and behaviour of the class. For example an article content class may consist
of the attributes: title, intro and body. The title could be a string datatype, the intro
and body could be XML formatted text.
</p>

<h3>Content object</h3>
<p>
A content object is an instance of a defined content class. The content class defines
the structure of content objects. The content object is the actual documents/articles etc
which are stored in eZ publish<sup>TM</sup>.
</p>

<h3>Content object version</h3>
<p>
Each content object can consist in several versions. This is to keep track
of changes and to have the possiblity to revert changes in an object.
</p>

<h3>Content object attribute</h3>
<p>
Each content object consists of several attributes. These attributes is defined
by a content class attribute. The content object attribute contains the actual
data.
</p>

<h3>Site access</h3>
<p>
A site can be viewed in multiple ways, each view which is called site access can control
the behaviour of allowed modules, sitedesign and many other things. This is often used
to create subsites or different views for different roles. An example of this is the
admin interface in the 2.x version of eZ publish.
</p>

<h3>Access control</h3>
<p>
Access control can be used to limit the allowed modules and module views which is available
trough a site access. It also controls things like user authentication.
</p>

<h2>eZ publish<sup>TM</sup> libraries</h2>
<p>
eZ publish<sup>TM</sup> libraries are the building blocks of eZ publish<sup>TM</sup>.
The libraries are general purpose and object oriented PHP libraries. The following
libraries are a part of the eZ publish<sup>TM</sup> SDK.
</p>
<ul>
<li>eZ db</li>
<li>eZ i18n</li>
<li>eZ image</li>
<li>eZ locale</li>
<li>eZ soap</li>
<li>eZ template</li>
<li>eZ utils</li>
<li>eZ xml</li>
</ul>

<h2>eZ publish<sup>TM</sup> kernel</h2>
<p>
The eZ publish kernel is the foundation of eZ publish. It handles all low level
functionality of eZ publish. 
</p>
