<?php
//
// Created on: <25-Jun-2002 10:49:40 bf>
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

$Result = array( "title" => "INI files" );
?>

<h1>Introduction to INI files</h1>
<p>
INI files are a collection of configuration switches that can be used to control
the behaviour of a program. They are used in eZ publish to control everything from
access control to site design.
</p>

<h2>How they work</h2>
<p>
Each switch or key consists of a key name and a key value.
Each key is placed in a group to avoid name conflicts and to make the file more
structured. The INI file is line based, which means that a group name and a key
entry exists on a single line. Empty lines are ignored as well as lines beginning
with a <b>#</b> (hash) which start a single line comment.
</p>

<p>
A group is entered by placing square brackets <b>[</b> and <b>]</b> around the group name.
</p>
<pre class="example">
[MyGroup]
</pre>

<p>
A key is entered by adding a <b>=</b> (equal sign) between the key name and the key value.
</p>
<pre class="example">
MyKey=my value
</pre>

<p>
Keys are generally seen as strings, however it's possible to create simple lists by separating
items in the value with a semi-colon <b>;</b>. The value will still be read as a
string but may be interpreted as a list by the developer.
</p>
<pre class="example">
MyKey=item1;item2;item3
</pre>

<p>
Group names and key names are case sensitive and may contain any characters except the equal sign. *
Group names and key names will have their whitespace trimmed before and after
the name while key values will keep all their whitespace.
</p>

<p class="footnote">
* We recommend using only the characters a-z, A-Z, 0-9 and _.
</p>

<h2>Charsets</h2>
<p>
All INI files are read as they were written using the utf8 (Unicode) charset,
if however you want the file to be in another charset format you can specify
it at the beginning of the file with a special comment syntax that defines
INI attributes.
</p>
<pre class="example">
#?ini charset=iso-8859-1?
</pre>

<p>
All text within the <b>?ini</b> and the ending <b>?</b> is seen as a list of key/value
pairs and is separated with spaces, the key and value is separated with a <b>=</b> (equal sign).
For now only the attribute <b>charset</b> is used.
</p>

<h2>Overrides</h2>
<p>
In eZ publish all INI files can be overridden, this means that it's possible to
change configuration without modifying the original files. Two types of override is possible,
the first is overriding the whole file and second is append mode where you can override
existing keys or add new ones. The latter is probably the most useful one.
</p>

<p>
The override file is placed in the override directory which usually is <b>override</b>.
It mirrors the original ini directory. This means that an override for the file <b>site.ini</b>
would be called either <b>override/site.ini</b> or <b>override/site.ini.append</b>.
</p>

<h2>Example</h2>
<p>
Full example of an INI file
</p>

<pre class="example">
# This is comment and is ignored
# The next empty lines are also ignored



[Group1]
Key1=some value
Key2=42

[Group2]
Key1=some value, again
Key2=array;values;here

[Group3]
Key1=some value, third time
</pre>
