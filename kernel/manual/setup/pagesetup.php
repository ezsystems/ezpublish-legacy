<!--
//
// Created on: <12-Dec-2002 12:21:35 bf>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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
//-->
<h1>Page setup</h1>

<p>
In eZ publish every page is generated dynamically. The main idea with the pages in
eZ publish is that the content, internationalization and design is separated.
Every page is therefore genenerated by a template. In the template you can decide where
the content should be placed and the layout of the site.
</p>

<p>
eZ publish is component based so there are several templates which makes up the page you
see. The main template in eZ publish, pagelayout.tpl, normally defines the menues and
basic layout of the site. You then have an area in this template where the actual content
is shows, this can be e.g. an article, a list of articles or an image. If you e.g. show a list
of articles in the main area there are several templates which makes out this list.
</p>

<h2>View modes</h2>

<p>
In eZ publish you can view content of different classes. Examples include article, product and forum
message. These objects can also be viewed in different ways. The two most common view modes are
line and full. The line view mode is normally used in lists and full is used when viewing an object,
e.g. an article.
</p>

<p>
For more information about how to customize eZ publish visit the <a href={"/sdk"|ezurl}>SDK documentation</a>
and read the tutorials on the subject.
</p>
