<?php
//
// Created on: <11-Jul-2002 17:01:08 bf>
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

<h1>Data storage</h1>

<p>
This document describes the XML format used in eZ publish. When eZ publish
stores content objects each attribute is stored as one row in the database.
The user can manually define the structure of content objects using
content classes.
</p>

<p>
The content class automatically creates the XML schema for the document.
</p>

<p>
Let us consider this simple content class:
</p>

<p>
Class name: Article
</p>
<p>
Class attributes:
Title : String ( <255 characters )
Intro : Text ( unformatted text )
Body : XML formatted text
</p>

<p>
When you create objects of this class each attribute will be stored in one row.
Actually it's a bit more complex than this since every object can have several
versions and every version has a number of translations.
</p>

<p>
The fields Title and Intro will be stored as plain text to the database wich
no XML tags. This is done because of speed when doing e.g. searching in specific
attributes and if you want to fetch only part of the content object - i.e. you
want to display the introduction of the article.
</p>

<p>
The Body field will be stored in XML. This field can contain different tags,
the most used tags are:
</p>

<h2>XML sections in eZ publish</h2>
<p>
The XML format in eZ publish has tags which handle the most frequently used
content, for specific content needs you can also use custom tags. A section
is the XML equivalent of a content object attribute.
</p>

<h3>Structured text</h3>
<ul>
	<li>emphasize - emphasis</li>
	<li>strong -  stronger emphasis</li>
	<li>cite - a citation</li>
	<li>code - computer code</li>
	<li>pre - pre formatted text</li>
	<li>sample - sample output from programs etc.</li>
	<li>blockquote - normally redered as an indented block</li>
	<li>quote - inline quote with delimited "</li>
</ul>

<p>
TODO: add section for technical tags, news tags, book tags, persons, addresses,
 organisations etc..
</p>

<h3>Headers</h3>
<p>
Headers are represented by the header tag. The header tag has the attribute
level which indicates the importance of the header, 1 beeing the most important.
Level must be a positive non zero number.
</p>

<h3>Lines and paragraphs</h3>
<ul>
	<li>paragraph - a paragraph of text</li>
	<li>break - a forced linebreak</li>
</ul>

<h3>Lists</h3>
<ul>
	<li>ordered list - </li>
	<li>unordered list - </li>
	<li>definition list - </li>
</ul>

<h3>Tables</h3>
<p>
You can use table to group structured text, lists and objects into rows
and columns.
</p>

<h3>Objects</h3>
<p>
The object tag refers to an external content object.
</p>

<h3>Sections</h3>
<p>
The tags defined above is the tags you can use to describe content. below is an
example of a typical section. The section would get the name of the content
class attribute.
</p>

<pre class="example">
&lt;?xml version="1.0" encoding="utf-8" ?&gt;
&lt;section&gt;
  &lt;header level="1"&gt;This is my article&lt;/header&gt;
  &lt;paragraph&gt;
    This is a paragraph
  &lt;/paragraph&gt;
&lt;/section&gt;
</pre>


<p>
Minimal example document:
</p>

<pre class="example">
&lt;?xml version="1.0" encoding="utf-8" ?&gt;
&lt;ezcontentobject&gt;
  &lt;title&gt;My article&lt;/title&gt;
  &lt;metadata&gt;
    &lt;language iso="en_GB"&gt;English&lt;/language&gt;
    &lt;created&gt;15-06-20002&lt;/created&gt;
    &lt;published&gt;15-06-20002&lt;/published&gt;
    &lt;modified&gt;15-06-20002&lt;/modified&gt;
  &lt;/metadata&gt;
  &lt;content&gt;
  &lt;introduction&gt;
    &lt;paragraph&gt;

    &lt;/paragraph&gt;
  &lt;/section&gt;
  &lt;/introduction&gt;
&lt;/ezcontentobject&gt;
</pre>

<p>
Sample document:
</p>

<p>
Schema:
</p>

