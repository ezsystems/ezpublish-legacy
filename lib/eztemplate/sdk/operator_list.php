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
<dt>array</dt>
<dd>Creates an array. E.g. array(6,8,42)</dd>
<dt>hash</dt>
<dd>Creates an associative array. E.g. hash(name,'Ola Norman',age,26)</dd>
</dl>

<h2>Execute</h2>
<dl>
<dt>fetch</dt>
<dd>Executes a given fetch operation.</dd>
</dl>

<h2>Locale</h2>
<dl>
<dt>l10n</dt>
<dd>Localizes values, e.g. {42.50|l10n(currency)}. Allowed types are: time, shorttime, date, shortdate, currency or number.</dd>
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
<dd>Concatenates values to one string. E.g. {concat('/var/',$node.node_id,'/')}</dd>
</dl>

<h2>Unit operators</h2>
<dl>
<dt>si</dt>
<dd>values </dd>
</dl>

<h2>Logical operators</h2>
<dl>
<dt>lt</dt>
<dd>Returns true if the value to the left is less than the value to the right. E.g. {1|lt(2)} returns true.</dd>
<dt>gt</dt>
<dd>Returns true if the value to the left is greater than the value to the right. E.g. {2|gt(1)} returns true.</dd>
<dt>le</dt>
<dd>Returns true if the value to the left is less than or equal to the value to the right. E.g. {1|le(1)} and {1|le(2)} returns true.</dd>
<dt>ge</dt>
<dd>Returns true if the value to the left is greater than or equal to the value to the right. E.g. {1|le(1)} and {2|le(1)} returns true.</dd>
<dt>eq</dt>
<dd>Returns true if the value to the left is equal to the value to the right. E.g. {1|le(1)} returns true.</dd>
<dt>null</dt>
<dd>Returns true if the value to the left is null, which is not the same as 0. E.g. {0|null()} returns false.</dd>
<dt>not</dt>
<dd>Returns true if the value to the left is false. E.g. {false()|not()} returns true.</dd>
<dt>true</dt>
<dd>Returns a true boolean.</dd>
<dt>false</dt>
<dd>Returns a false boolean.</dd>
<dt>or</dt>
<dd>Evaluates all parameter values until one is found to be true, then returns that value. The remaining parameters are not evaluated at all. If there are no parameters or all elements were false it returns false. E.g. {or(false(),false(),true(),false())} returns true.</dd>
<dt>and</dt>
<dd>Evaluates all parameter values until one is found to be false, then returns that false. The remaining parameters are not evaluated at all. If there are no parameters it returns false, if no elements were false it returns the last parameter value. E.g. {or(false(),false(),true(),false())} returns false.</dd>
<dt>choose</dt>
<dd>Uses the input count to pick one of the parameter elements. The input count equals the parameter index. E.g. {0|choose("a","b","c")} returns "a".</dd>
</dl>

<h2>Type operators</h2>
<p>These operators correspond to the same PHP functions.</p>
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

<h2>eZ publish operators</h2>
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
<dd>Marks a string for translation. E.g. {"I like %1!"|i18n("design/standard",,array($food))} See the tutorial <a href="/sdk/sdk/tutorials/view/translation">Translation and i18n</a> for more information.</dd>
<dt>x18n</dt>
<dd>Marks a string in an extension template for translation. E.g. {"I like %1!"|i18n("myextension","design/standard",,array($food))} See the tutorial <a href="/sdk/sdk/tutorials/view/translation">Translation and i18n</a> for more information.</dd>
<dt>upcase</dt>
<dd></dd>
<dt>upcase</dt>
<dd></dd>
<dt>reverse</dt>
<dd></dd>
<dt>nl2br</dt>
<dd></dd>
</dl>

