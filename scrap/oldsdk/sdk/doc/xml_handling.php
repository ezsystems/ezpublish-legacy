<?php
//
// Created on: <10-Jul-2002 17:02:09 bf>
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
eZ publish could use XML to store content objects. The diagram below shows how
eZ publish handles XML. It stores the content object attribute data in XML
format, except for simple types like float and integer.
</p>

<img src="/doc/images/xml_processing.png" alt="XML processing" />

<h2>Input</h2>
<p>
eZ publish can input data in two ways, manual or automatic. When using
the manual data input the user normally input data in a simplified format.
For automatic data input the native XML format is normally used.
</p>

<p>
The example below shows how the user can input data in a simplified format.
</p>
<pre class="example">
&lt;header&gt;This is my header&lt;/header&gt;
Here comes the paragraph text, and &lt;em&gt;this is emphasized&lt;/em&gt;.

This is a new paragraph with a link to &lt;link href="http://ez.no"&gt;ez.no&lt;/link&gt;.
</pre>

<p>
This simplified format will be validated and then converted to a real XML
format. The validation must be done before the conversion so that the
error reporting to the user will make sense. Below you see the converted
user input. NOTE: newlines are <b>not</b> converted into breaks, paragraphs
are created by two or more newlines in a row.
</p>

<pre class="example">
&lt;?xml version="1.0"?&gt;
&lt;section&gt;
    &lt;title&gt;This is my header&lt;/title&gt;
    &lt;paragraph&gt;
    Here comes the paragraph text, and &lt;emphasize&gt;this is
    emphasized&lt;/emphasize&gt;.
    &lt;/paragraph&gt;
    &lt;paragraph&gt;
    This is a new paragraph with a link to
    &lt;link href="http://ez.no"&gt;ez.no&lt;/link&gt;.
    &lt;/paragraph&gt;
&lt;/section&gt;
</pre>

<h2>Supported tag format</h2>
<p>
eZ publish currently support following tags:
</p>
<p>
<ul>
<li> &lt;em&gt;emphasize text&lt;/em&gt; or &lt;emphasize&gt;emphasize text&lt;/emphasize&gt; </li>
<li> &lt;bold&gt;bold text&lt;/bold&gt; or &lt;strong&gt;bold text&lt;/strong&gt; </li>
<li> &lt;ul&gt;unordered list&lt;/ul&gt;</li>
<li> &lt;ol&gt;ordered list&lt;/ol&gt; </li>
<li> &lt;li&gt;list element inside  &lt;ul&gt or  &lt;ol&gt tag &lt;/li&gt;</li>
<li> &lt;header&gt;heading text&lt;/header&gt; or &lt;header level="1-6"&gt;heading text
with defined size&lt;/header&gt; </li>
<li> &lt;link href="link url"&gt;link text&lt;/link&gt; or &lt;link id="id"&gt;link
text&lt;/link&gt; </li>where id is an existing eZ url id.
<li> &lt;object id="id" view="view type" /&gt;</li> where 'id' should be an existing eZ
object id and 'view type' could be 'embed', 'text_linked' or not specified.
<li> &lt;table border='0-10' width="1-100%"&gt;table content&lt;/table&gt;</li> where
attribute 'border' and 'width' could be not presented. Table content should be written according to
normal table syntax with &lt;tr&gt; and &lt;td&gt; tag.

</ul>
</p>

<h2>Storage</h2>
<p>
eZ publish stores the document internally not as one XML document, but
each content object attribute is stored in a row in the database. This
means that simple types like strings ( unformatted ), integers, floats,
boolean, prices etc. are not stored in XML. This is because of speed
considerations with operations like speficic search in attributes.
</p>

<h2>Output</h2>
<p>
eZ publish converts the internal representation of the content object
to an XML document. This document is then converted to any disired
format.
</p>
