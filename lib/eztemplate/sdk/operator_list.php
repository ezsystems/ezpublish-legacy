<?php
//
// Created on: <24-Jan-2003 15:34:16 bf>
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

$Result = array( 'title' => 'Operator list' );

?>

<p>
This is a complete list of all the operators you can use in eZ publish
templates. Most operators are native to eZ template (general), some are specific
for eZ publish.
</p>

<h2>Array handling</h2>

<dl>
<dt>hash</dt>
<dd>Creates an associative array. E.g. hash(name,'Ola Norman',age,26)</dd>
</dl>

<h2>Execute</h2>
<dl>
<dt>fetch</dt>
<dd>Executes a given fetch operation</dd>
</dl>

<h2>Locale</h2>
<dl>
<dt>l10n</dt>
<dd>Localizes values. e.g. {42.50|l10n(price)}</dd>
</dl>

<h2>Debug</h2>
<dl>
<dt>attribute</dt>
<dd>Helper attribute to print available methods in objects. E.g. {$node|attribute(show)}</dd>
</dl>

<h2>Output washing</h2>
<dl>
<dt>wash</dt>
<dd>Will convert special characters to valid XHTML characters.</dd>
</dl>

<h2>Text operations</h2>
<dl>
<dt>concat</dt>
<dd>Concatenates values to one string. {concat('/var/',$node.node_id,'/')}</dd>
</dl>

<h2>Unit operators</h2>
<dl>
<dt>si</dt>
<dd>values </dd>
</dl>

<h2>Logical operators</h2>
<dl>
<dt>lt</dt>
<dd>Check if a value is less than the given value, returns true if the value is greater. Example: {1|gt(2)} {1|gt(2)} </dd>
<dt>gt</dt>
<dd>Greater than.</dd>
<dt>le</dt>
<dd>Less or equal</dd>
<dt>ge</dt>
<dd>Greater or equal</dd>
<dt>eq</dt>
<dd>Checks for equal values</dd>
<dt>null</dt>
<dd>Checks for null values</dd>
<dt>not</dt>
<dd></dd>
<dt>true</dt>
<dd></dd>
<dt>false</dt>
<dd></dd>
<dt>or</dt>
<dd></dd>
<dt>and</dt>
<dd></dd>
<dt>choose</dt>
<dd></dd>
</dl>

<h2>Type operators</h2>
<dl>
<dt>is_array</dt>
<dd></dd>
<dt>is_boolean</dt>
<dd></dd>
<dt>is_integer</dt>
<dd></dd>
<dt>is_float</dt>
<dd></dd>
<dt>is_numeric</dt>
<dd></dd>
<dt>is_string</dt>
<dd></dd>
<dt>is_object</dt>
<dd></dd>
<dt>is_class</dt>
<dd></dd>
<dt>is_null</dt>
<dd></dd>
<dt>is_set</dt>
<dd></dd>
<dt>is_unset</dt>
<dd></dd>
<dt>get_type</dt>
<dd></dd>
<dt>get_class</dt>
<dd></dd>
</dl>

<h2>Control operators</h2>
<dl>
<dt>cond</dt>
<dd></dd>
<dt>first_set</dt>
<dd></dd>
</dl>

<h2>Arithmentic operators</h2>
<dl>
<dt>sum</dt>
<dd></dd>
<dt>sub</dt>
<dd></dd>
<dt>inc</dt>
<dd></dd>
<dt>dec</dt>
<dd></dd>
<dt>div</dt>
<dd></dd>
<dt>mod</dt>
<dd></dd>
<dt>mul</dt>
<dd></dd>
<dt>max</dt>
<dd></dd>
<dt>min</dt>
<dd></dd>
<dt>abs</dt>
<dd></dd>
<dt>ceil</dt>
<dd></dd>
<dt>floor</dt>
<dd></dd>
<dt>round</dt>
<dd></dd>
<dt>count</dt>
<dd></dd>
</dl>

<h2>Image handling operators</h2>
<dl>
<dt>texttoimage</dt>
<dd></dd>
<dt>image</dt>
<dd></dd>
<dt>imagefile</dt>
<dd></dd>
<dt>imagelabel</dt>
<dd></dd>
</dl>

<h2>eZ publish URL operators</h2>
<dl>
<dt>ezurl</dt>
<dd></dd>
<dt>ezroot</dt>
<dd></dd>
<dt>ezsys</dt>
<dd></dd>
<dt>ezdesign</dt>
<dd></dd>
<dt>ezimage</dt>
<dd></dd>
<dt>exturl</dt>
<dd></dd>
<dt>i18n</dt>
<dd></dd>
<dt>upcase</dt>
<dd></dd>
<dt>upcase</dt>
<dd></dd>
<dt>reverse</dt>
<dd></dd>
<dt>nl2br</dt>
<dd></dd>
</dl>





