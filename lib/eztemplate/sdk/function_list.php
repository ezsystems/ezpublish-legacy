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

$Result = array( 'title' => 'Function list' );

?>

<p>
This is a complete list of all the functions you can use in eZ publish
templates. Some functions are native to the eZ template library (and can be used in other applications),
while others are specific for eZ publish.
</p>

<h2>eZ publish template library functions</h2>
<dl>
<dt>section (delimiter, section-exclude, section-include, section-else)</dt>
<dd>Used for looping over arrays and numeric ranges, and conditional control of block and sequences. Several input parameters and sub functions are available. Requires an end tag. <a href="/sdk/eztemplate/view/function_section/">More...</a></dd>
<dt>ldelim</dt>
<dd>Outputs the { sign (left curly bracket). Requires no end tag. <a href="/sdk/eztemplate/view/function_delimit/">More...</a></dd>
<dt>rdelim</dt>
<dd>Outputs the } sign (right curly bracket). Requires no end tag. <a href="/sdk/eztemplate/view/function_delimit/">More...</a></dd>
<dt>include</dt>
<dd>Includes another template file. Requires no end tag. <a href="/sdk/eztemplate/view/function_include/">More...</a></dd>
<dt>switch (case)</dt>
<dd>Allows conditional control of output. Requires an end tag. <a href="/sdk/eztemplate/view/function_switch/">More...</a></dd>
<dt>sequence</dt>
<dd>Creates a sequence that can be iterated over, wrapping around to the beginning when the end is reached. Requires no end tag. <a href="/sdk/eztemplate/view/function_sequence/">More...</a></dd>
<dt>let</dt>
<dd>Assigns one or more variables within its tags. Requires an end tag.</dd>
<dd>E.g. {let var1=42 var2='forty-two'}<br/>
Variables: {$var1} ({$var2})<br/>
{/let}<br/>
This will output "Variables: 42 (forty-two)".</dd>
<dt>default</dt>
<dd>Assigns one or more variables within its tags, unless they are already assigned. Requires an end tag.</dd>
<dd>E.g. {let var1=42 var2='forty-two'}<br/>
{default var1=53}<br/>
Variables: {$var1} ({$var2})<br/>
{/default}<br/>
{/let}<br/>
This will output "Variables: 42 (forty-two)", because the first variable was set before the default statement.</dd>
<dt>set</dt>
<dd>Assigns a value to one or more variables. The value must have been created earlier using let or default. Requires no end tag.</dd>
<dd>E.g. {let var=4}<br/>
Before: {$var}<br/>
{set var=42}<br/>
After: {$var}<br/>
{/let}<br/>
This will output "Before: 4 After: 42".</dd>
<dt>set-block</dt>
<dd></dd>
<dt>append-block</dt>
<dd></dd>
<dt>run-once</dt>
<dd></dd>
<dt>cache-block</dt>
<dd></dd>
</dl>


<h2>eZ publish kernel functions</h2>
<dl>
<dt>attribute_edit_gui</dt>
<dd></dd>
<dt>attribute_view_gui</dt>
<dd></dd>
<dt>node_view_gui</dt>
<dd></dd>
<dt>content_view_gui</dt>
<dd></dd>
<dt>shop_account_view_gui</dt>
<dd></dd>
<dt>content_version_view_gui</dt>
<dd></dd>
<dt>collaboration_view_gui</dt>
<dd></dd>
<dt>collaboration_icon</dt>
<dd></dd>
<dt>collaboration_simple_message_view</dt>
<dd></dd>
<dt>collaboration_participation_view</dt>
<dd></dd>
<dt>event_edit_gui</dt>
<dd></dd>
<dt>class_attribute_edit_gui</dt>
<dd></dd>
</dl>
